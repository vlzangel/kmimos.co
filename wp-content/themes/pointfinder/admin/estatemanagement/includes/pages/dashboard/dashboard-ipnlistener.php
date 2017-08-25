<?php 
/**********************************************************************************************************************************
*
* User Dashboard Page - IPN Listener
* 
* Author: Webbu Design
***********************************************************************************************************************************/

if (!empty($_POST)) {
    if (isset($_POST['txn_type'])) {

        $paypal_host = (PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0') == 0)? 'www.paypal.com' : 'www.sandbox.paypal.com';

        $url = 'https://'.$paypal_host.'/cgi-bin/webscr';

        $post_data = $_POST;
        $encoded_data = 'cmd=_notify-validate';
        foreach ($post_data as $key => $value) {
            $encoded_data .= "&$key=".urlencode($value);
        }

        $response = wp_remote_get( $url.'?'.$encoded_data, array(
              'method' => 'GET',
              'sslverify'   => true,
              'redirection' => 5,
              'httpversion' => '1.0',
              'headers' => array('Expect:'),
              'body' => $encoded_data,
            )
        );
        
        $verified = (isset($response['body']))? ($response['body'] == 'VERIFIED')? true : false : false;
       
        if ($verified) {

        	$setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
        	
        	switch ($_POST['txn_type']) {
        		case 'recurring_payment_profile_cancel':
        		case 'recurring_payment_failed':
        		case 'recurring_payment_expired':
        			/** 
        			*Start : Cancel Recurring Payment Profile
        			**/
	        			if (isset($_POST['recurring_payment_id'])) {
	        				
	        				$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
	        				/*Find Item & Order by using profile ID */
		        			global $wpdb;

		        			$order_id = $wpdb->get_var( $wpdb->prepare(
									"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
									$_POST['recurring_payment_id']
								));

							$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_recurring',true));

							if ($setup4_membersettings_paymentsystem == 2) {

								if (!empty($order_id) && $recurring_status == 1) {
									
									$user_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_userid', true ));
									$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
									$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
									$packageinfo = pointfinder_membership_package_details_get($pointfinder_order_packageid);

									update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
									update_user_meta( $user_id, 'membership_user_recurring', 0);

									PFCreateProcessRecord(
										array( 
									        'user_id' => $user_id,
									        'item_post_id' => $order_id,
											'processname' => esc_html__('Recurring Payment Profile Cancelled','pointfindert2d'),
											'membership' => 1
									    )
									);

									if ($setup33_emaillimits_listingexpired == 1) {
										
										$user_info = get_userdata( $user_id);

									 	pointfinder_mailsystem_mailsender(
											array(
											'toemail' => $user_info->user_email,
									        'predefined' => 'expiredrecpaymentmember',
									        'data' => array(
									        	'packagename' => $packageinfo['webbupointfinder_mp_title'], 
									        	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'], 
									        	'expiredate' => PFU_DateformatS($pointfinder_order_expiredate),
									        	'orderid' => $order_id
									        	),
											)
										);
									}
								}

							}else{

								if (!empty($order_id) && $recurring_status == 1) {
									
									$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
									$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
									
									$post_author = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
										$item_id
									));

									update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
									update_post_meta( $order_id, 'pointfinder_order_frecurring', 0 );
									
									PFCreateProcessRecord(
										array( 
									        'user_id' => $post_author,
									        'item_post_id' => $item_id,
											'processname' => esc_html__('Recurring Payment Profile Cancelled','pointfindert2d')
									    )
									);

									if ($setup33_emaillimits_listingexpired == 1) {
										$user_info = get_userdata( $post_author);
									 	pointfinder_mailsystem_mailsender(
											array(
											'toemail' => $user_info->user_email,
									        'predefined' => 'expiredrecpayment',
									        'data' => array('ID' => $item_id, 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
											)
										);
									}
								}else{
									$order_id = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_frecurringid' and meta_value = '%s'", 
										$_POST['recurring_payment_id']
									));
									$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_frecurring',true));

									if (!empty($order_id) && $recurring_status == 1) {
									
										$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
										$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
										
										$post_author = $wpdb->get_var( $wpdb->prepare(
											"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
											$item_id
										));

										update_post_meta( $order_id, 'pointfinder_order_frecurring', 0 );
										
										PFCreateProcessRecord(
											array( 
										        'user_id' => $post_author,
										        'item_post_id' => $item_id,
												'processname' => esc_html__('Recurring Payment Profile Cancelled (For Featured Option)','pointfindert2d')
										    )
										);
									}
								}
							}
	        			}
        			/** 
        			*End : Cancel Recurring Payment Profile
        			**/
        			break;


        		case 'recurring_payment_suspended':
        		case 'recurring_payment_suspended_due_to_max_failed_payment':
        		
        			/** 
        			*Start : Suspended Recurring Payment Profile
        			**/
	        			if (isset($_POST['recurring_payment_id'])) {
	        				
	        				/*Find Item & Order by using profile ID */
		        			global $wpdb;
							
							$order_id = $wpdb->get_var( $wpdb->prepare(
								"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
								$_POST['recurring_payment_id']
							));

							$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_recurring',true));

							if ($setup4_membersettings_paymentsystem == 2) {
								if (!empty($order_id) && $recurring_status == 1) {
								
									$user_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_userid', true));
									$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true);
									
									$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
									$packageinfo = pointfinder_membership_package_details_get($pointfinder_order_packageid);
									
									update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
									update_user_meta( $user_id, 'membership_user_recurring', 0);
									
									PF_Cancel_recurring_payment_member(
									 array( 
									        'user_id' => $user_id,
									        'profile_id' => $_POST['recurring_payment_id'],
									        'item_post_id' => $order_id,
									        'order_post_id' => $order_id,
									    )
									 );

									PFCreateProcessRecord(
										array( 
									        'user_id' => $user_id,
									        'item_post_id' => $order_id,
											'processname' => esc_html__('Recurring Payment Profile Cancelled by IPN (Failed Payment)','pointfindert2d'),
											'membership' => 1
									    )
									);

									$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

									if ($setup33_emaillimits_listingexpired == 1) {
										$user_info = get_userdata( $user_id);
									 	pointfinder_mailsystem_mailsender(
											array(
											'toemail' => $user_info->user_email,
									        'predefined' => 'expiredrecpaymentmember',
									        'data' => array(
									        	'packagename' => $packageinfo['webbupointfinder_mp_title'], 
									        	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'], 
									        	'expiredate' => PFU_DateformatS($pointfinder_order_expiredate),
									        	'orderid' => $order_id
									        	),
											)
										);
									}

									
								}
							
							}else{
								if (!empty($order_id) && $recurring_status == 1) {
								
									$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
									$pointfinder_order_expiredate = esc_attr(get_post_meta( $order_id, 'pointfinder_order_expiredate', true ));
									
									$post_author = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
										$item_id
									));


									update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
									update_post_meta( $order_id, 'pointfinder_order_frecurring', 0 );
									
									PF_Cancel_recurring_payment(
									 array( 
									        'user_id' => $post_author,
									        'profile_id' => $_POST['recurring_payment_id'],
									        'item_post_id' => $item_id,
									        'order_post_id' => $order_id,
									    )
									 );

									PFCreateProcessRecord(
										array( 
									        'user_id' => $post_author,
									        'item_post_id' => $item_id,
											'processname' => esc_html__('Recurring Payment Profile Cancelled by IPN (Failed Payment)','pointfindert2d')
									    )
									);

									$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

									if ($setup33_emaillimits_listingexpired == 1) {
										$user_info = get_userdata( $post_author);
									 	pointfinder_mailsystem_mailsender(
											array(
											'toemail' => $user_info->user_email,
									        'predefined' => 'expiredrecpayment',
									        'data' => array('ID' => $item_id, 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
											)
										);
									}
								}else{
									$order_id = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_frecurringid' and meta_value = '%s'", 
										$_POST['recurring_payment_id']
									));
									$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_frecurring',true));

									if (!empty($order_id) && $recurring_status == 1) {
									
										$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
										$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
										
										$post_author = $wpdb->get_var( $wpdb->prepare(
											"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
											$item_id
										));

										update_post_meta( $order_id, 'pointfinder_order_frecurring', 0 );
										
										PFCreateProcessRecord(
											array( 
										        'user_id' => $post_author,
										        'item_post_id' => $item_id,
												'processname' => esc_html__('Recurring Payment Profile Cancelled by IPN (Failed Payment) (For Featured Option)','pointfindert2d')
										    )
										);
									}
								}
							}
							
	        			}
        			/** 
        			*End : Suspended Recurring Payment Profile
        			**/
        			break;


        		case 'recurring_payment':
        			/** 
        			*Start : Extend Recurring Payed Item 
        			**/
	        			if ($_POST['payment_status'] == 'Completed') {
	        				
	        				
		        			/*Find Item & Order by using profile ID */
		        			global $wpdb;
							
							$order_id = $wpdb->get_var( $wpdb->prepare(
								"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
								$_POST['recurring_payment_id']
							));



							if ($setup4_membersettings_paymentsystem == 2) {
								if (!empty($order_id)) {

				        			$old_expire_date = get_post_meta( $order_id, 'pointfinder_order_expiredate', true);
				        			$user_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_userid', true ));

									$membership_user_activeorder = get_user_meta( $user_id, 'membership_user_activeorder', true );
                  					$expire_date_rec = get_post_meta( $membership_user_activeorder, 'pointfinder_order_expiredate', true );

									$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
									$packageinfo = pointfinder_membership_package_details_get($pointfinder_order_packageid);
									
									if (pf_membership_expire_check($expire_date_rec) == false) {
										$exp_date = strtotime(PFU_DateformatS($old_expire_date)." +".$packageinfo['webbupointfinder_mp_billing_period']." ".pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
										$app_date = strtotime("now");
									} else {
										$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
										$app_date = strtotime("now");
									}


									$app_date = strtotime("now");
								
									update_post_meta( $order_id, 'pointfinder_order_expiredate', $exp_date);
									update_post_meta( $order_id, 'pointfinder_order_datetime_approval', $app_date);

									PF_CreatePaymentRecord(
										array(
										'user_id'	=>	$user_id,
										'order_post_id'	=>	$order_id,
										'processname'	=>	'RecurringPayment',
										'response' => $post_data,
										'membership' => 1
										)
									);

									PFCreateProcessRecord(
										array( 
								        'user_id' => $user_id,
								        'item_post_id' => $order_id,
										'processname' => sprintf(esc_html__('Expire date extended by IPN System: (Order Date: %s / Expire Date: %s)','pointfindert2d'),
											PFU_DateformatS($app_date),
											PFU_DateformatS($exp_date)
											),
										'membership' => 1
									    )
									);

									/* Create an invoice for this */
						              PF_CreateInvoice(
						                array( 
						                  'user_id' => $user_id,
						                  'item_id' => 0,
						                  'order_id' => $order_id,
						                  'description' => $packageinfo['webbupointfinder_mp_title'],
						                  'processname' => esc_html__('Recurring Payment','pointfindert2d'),
						                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
						                  'datetime' => strtotime("now"),
						                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
						                  'status' => 'publish'
						                )
						              );
								}
							} else {
								if (!empty($order_id)) {

									$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
									$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
									$setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
									$setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');
							
									$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
				        			$pointfinder_order_listingtime = esc_attr(get_post_meta( $order_id, 'pointfinder_order_listingtime', true ));
				        			$old_expire_date = get_post_meta( $order_id, 'pointfinder_order_expiredate', true);

				        			$exp_date = date("Y-m-d H:i:s",strtotime($old_expire_date .'+'.$pointfinder_order_listingtime.' day'));
									$app_date = date("Y-m-d H:i:s");
								
									update_post_meta( $order_id, 'pointfinder_order_expiredate', $exp_date);
									update_post_meta( $order_id, 'pointfinder_order_datetime_approval', $app_date);

									$post_author = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
										$item_id
									));

									
									PF_CreatePaymentRecord(
										array(
										'user_id'	=>	$post_author,
										'item_post_id'	=>	$item_id,
										'order_post_id'	=>	$order_id,
										'processname'	=>	'RecurringPayment',
										'response' => $post_data,
										)
									);

									PFCreateProcessRecord(
										array( 
								        'user_id' => $post_author,
								        'item_post_id' => $item_id,
										'processname' => sprintf(esc_html__('Expire date extended by IPN System: (Order Date: %s / Expire Date: %s)','pointfindert2d'),
											$app_date,
											$exp_date
											)
									    )
									);

									$order_price_inv = get_post_meta( $order_id, 'pointfinder_order_price', true );
									if (empty($order_price_inv)) {$order_price_inv = 0;}

									$order_pname_inv = get_post_meta( $order_id, 'pointfinder_order_listingpname', true );
									if (empty($order_pname_inv)) {$order_pname_inv = '-';}


									


									/* Create an invoice for this */
						              PF_CreateInvoice(
						                array( 
						                  'user_id' => $post_author,
						                  'item_id' => $item_id,
						                  'order_id' => $order_id,
						                  'description' => $order_pname_inv,
						                  'processname' => esc_html__('Recurring Payment','pointfindert2d'),
						                  'amount' => number_format($order_price_inv, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands),
						                  'datetime' => strtotime("now"),
						                  'packageid' => 0,
						                  'status' => 'publish'
						                )
						              );
								}else{
									$order_id = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_frecurringid' and meta_value = '%s'", 
										$_POST['recurring_payment_id']
									));

									if (!empty($order_id)) {
									
										$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
										$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
										$setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
										$setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');
								
										$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
					        			
					        			$stp31_daysfeatured = PFSAIssetControl('stp31_daysfeatured','','3');
					        			
					        			$old_expire_date = get_post_meta( $order_id, 'pointfinder_order_expiredate_featured', true);

					        			$exp_date = date("Y-m-d H:i:s",strtotime($old_expire_date .'+'.$stp31_daysfeatured.' day'));
										$app_date = date("Y-m-d H:i:s");
										
										update_post_meta( $order_id, 'pointfinder_order_expiredate_featured', $exp_date);

										$post_author = $wpdb->get_var( $wpdb->prepare(
											"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
											$item_id
										));

										
										PF_CreatePaymentRecord(
											array(
											'user_id'	=>	$post_author,
											'item_post_id'	=>	$item_id,
											'order_post_id'	=>	$order_id,
											'processname'	=>	'RecurringPayment',
											'response' => $post_data,
											)
										);

										PFCreateProcessRecord(
											array( 
									        'user_id' => $post_author,
									        'item_post_id' => $item_id,
											'processname' => sprintf(esc_html__('Expire date extended by IPN System: (For Featured) (Order Date: %s / Expire Date: %s)','pointfindert2d'),
												$app_date,
												$exp_date
												)
										    )
										);


										$order_price_inv = PFSAIssetControl('setup31_userpayments_pricefeatured','',0);

										$order_pname_inv = esc_html__('Recurring Payment for Featured Option','pointfindert2d');

										/* Create an invoice for this */
							              PF_CreateInvoice(
							                array( 
							                  'user_id' => $post_author,
							                  'item_id' => $item_id,
							                  'order_id' => $order_id,
							                  'description' => $order_pname_inv,
							                  'processname' => esc_html__('Recurring Payment for Featured Option','pointfindert2d'),
							                  'amount' => number_format($order_price_inv, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands),
							                  'datetime' => strtotime("now"),
							                  'packageid' => 0,
							                  'status' => 'publish'
							                )
							              );
									}

								}
							}
							
							
							
						}elseif ($_POST['payment_status'] == 'Pending') {
							/*Find Item & Order by using profile ID */
		        			global $wpdb;
							
							$order_id = $wpdb->get_var( $wpdb->prepare(
								"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
								$_POST['recurring_payment_id']
							));

							if (!empty($order_id)) {
								$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
			        			
								$post_author = $wpdb->get_var( $wpdb->prepare(
									"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
									$item_id
								));

								PF_CreatePaymentRecord(
									array(
									'user_id'	=>	$post_author,
									'item_post_id'	=>	$item_id,
									'order_post_id'	=>	$order_id,
									'processname'	=>	'RecurringPaymentPending',
									'response' => $post_data,
									)
								);

							}
						}
					/** 
        			*End : Extend Recurring Payed Item 
        			**/
        			break;

        		case 'web_accept':

        			/** 
        			*Start : Refund & reversals
        			**/
	        			if($_POST["payment_status"] == "Refunded" || $_POST["payment_status"] == "Reversed"){
						    if (isset($_POST['custom'])) {
						    	$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
		        				/*Find Item & Order by using profile ID */
			        			global $wpdb;
								
								$order_id = get_user_meta( $_POST['custom'], 'membership_user_package_id', true );

								if ($setup4_membersettings_paymentsystem == 2) {

									$user_id = esc_attr($_POST['custom']);
									$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
									$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
									$packageinfo = pointfinder_membership_package_details_get($pointfinder_order_packageid);

									
									/* This is direct payment */
									PFExpireItemManualMember(
										array( 
									        'order_id' => $order_id,
									        'post_author' => $user_id,
											'payment_type' => 'web_accept',
											'payment_err' => $_POST["payment_status"]
									    )
									 );

									 if ($setup33_emaillimits_listingexpired == 1) {
									 	$user_info = get_userdata( $user_id);

									 	pointfinder_mailsystem_mailsender(
											array(
											'toemail' => $user_info->user_email,
									        'predefined' => 'directafterexpiremember',
									        'data' => array(
									        	'orderid' => $order_id,
									        	'packagename' => $packageinfo['webbupointfinder_mp_title'], 
									        	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'], 
									        	'expiredate' => PFU_DateformatS($pointfinder_order_expiredate)
									        	),
											)
										);
									 }
								} else {
									$post_author = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
										$_POST['custom']
									));



									/* This is direct payment */
									PFExpireItemManual(
										array( 
									        'order_id' => $order_id,
									        'post_id' => $_POST['custom'],
									        'post_author' => $post_author,
											'payment_type' => 'web_accept',
											'payment_err' => $_POST["payment_status"]
									    )
									 );

									 if ($setup33_emaillimits_listingexpired == 1) {
									 	$user_info = get_userdata( $post_author);
									 	$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
									 	pointfinder_mailsystem_mailsender(
											array(
											'toemail' => $user_info->user_email,
									        'predefined' => 'directafterexpire',
									        'data' => array('ID' => $_POST['custom'], 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
											)
										);
									 }
								}
						    } 
						    
						}
					/** 
        			*End : Refund & reversals
        			**/
        			break;
        		
        	}
        } else {
           //wp_mail( 'mail', 'not verified', $encoded_data);
        }
    }
}
/**
*End: Update & Add function for new item
**/
?>