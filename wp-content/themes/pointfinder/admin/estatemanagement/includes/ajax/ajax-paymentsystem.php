<?php
/**********************************************************************************************************************************
*
* Ajax Payment System
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


add_action( 'PF_AJAX_HANDLER_pfget_paymentsystem', 'pf_ajax_paymentsystem' );
add_action( 'PF_AJAX_HANDLER_nopriv_pfget_paymentsystem', 'pf_ajax_paymentsystem' );

function pf_ajax_paymentsystem(){
  
	//Security
  check_ajax_referer( 'pfget_paymentsystem', 'security');
  
	header('Content-Type: application/json; charset=UTF-8;');

	//Get form type
  if(isset($_POST['formtype']) && $_POST['formtype']!=''){
    $formtype = esc_attr($_POST['formtype']);
  }

  //Get item id
  if(isset($_POST['itemid']) && $_POST['itemid']!=''){
    $item_post_id = esc_attr($_POST['itemid']);
  }else{
    $item_post_id = '';
  }

  //Get process type
  if(isset($_POST['otype']) && $_POST['otype']!=''){
    $otype = esc_attr($_POST['otype']);
  }else{
    $otype = 0;
  }


	switch($formtype){
/**
*Paypal Request Work
**/
		case 'paypalrequest':
      //62 olumlu 485 olumsuz
      $icon_processout = 62;
      $msg_output = $pfreturn_url = '';
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      if($user_id != 0){

        if ($item_post_id != '') {

          $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','',site_url());
          $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
          $pfmenu_perout = PFPermalinkCheck();
          $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

          /*Check if item user s item*/
          global $wpdb;

          $result = $wpdb->get_results( $wpdb->prepare( 
            "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
            $item_post_id,
            $user_id,
            $setup3_pointposttype_pt1
          ) );

          
          if (is_array($result) && count($result)>0) {  
            
            if ($result[0]->ID == $item_post_id) {

              $setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
              $setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
              $setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');

              /*Meta for order*/
              global $wpdb;
              $result_id = $wpdb->get_var( $wpdb->prepare(
                "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
                'pointfinder_order_itemid',
                $item_post_id
              ) );

              /* Check is this a change */
              $pointfinder_sub_order_change = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_change', true ));
              
              if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                
                $pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
                $pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_listingtime', true ));
                $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                $pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
                $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpid', true)); 
                $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true)); 

                $total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);

                $paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

                $billing_description = $pointfinder_order_recurring = $total_package_price_recurring = $featured_package_price = $featuredrecurring  = $billing_description_featured = '';


                $response = pointfinder_paypal_request(
                  array(
                    'returnurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_rec',
                    'cancelurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_cancel',
                    'total_package_price' => $total_package_price,
                    'total_package_price_recurring' => $total_package_price_recurring,
                    'featured_package_price' => $featured_package_price,
                    'payment_custom_field' => $item_post_id,
                    'recurring' => $pointfinder_order_recurring,
                    'billing_description' => $billing_description,
                    'paymentName' => $paymentName,
                    'apipackage_name' => $pointfinder_order_listingpname,
                    'featuredrecurring' => $featuredrecurring,
                    'featured_billing_description' => $billing_description_featured,
                    'payment_custom_field1' => $otype
                  )
                );


              }else{
                /* Normal process */
                $pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
                $pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
                $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                $pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
                $pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
                $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true)); 
                $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 

                $total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);

                $paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

                $billing_description = $total_package_price_recurring = $featured_package_price = $featuredrecurring  = $billing_description_featured = '';

                if ($pointfinder_order_recurring == 1) {

                  /* Added with v1.6.4 */
                  $pointfinder_order_featured = esc_attr(get_post_meta($result_id, 'pointfinder_order_featured', true)); 
                  if ($pointfinder_order_featured == 1) {
                    $setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','5');
                    $stp31_daysfeatured = PFSAIssetControl('stp31_daysfeatured','','3');

                    $total_package_price_recurring = $pointfinder_order_price -  $setup31_userpayments_pricefeatured;

                    $total_package_price_recurring = number_format($total_package_price_recurring, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);
                    $setup31_userpayments_pricefeatured = number_format($setup31_userpayments_pricefeatured, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);

                    $billing_description_featured = sprintf(
                    esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s)','pointfindert2d'),
                    $paymentName,
                    esc_html__('Featured Point','pointfindert2d'),
                    $setup31_userpayments_pricefeatured,
                    $pointfinder_order_pricesign,
                    $stp31_daysfeatured,
                    $item_post_id
                    );

                    $featuredrecurring = 1;
                    $featured_package_price = $setup31_userpayments_pricefeatured;
                  }else{
                    $total_package_price_recurring = $total_package_price;
                    $featuredrecurring = $billing_description_featured = $featured_package_price = '';
                  }

                  $billing_description = sprintf(
                    esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s)','pointfindert2d'),
                    $paymentName,
                    $pointfinder_order_listingpname,
                    $total_package_price_recurring,
                    $pointfinder_order_pricesign,
                    $pointfinder_order_listingtime,
                    $item_post_id
                    );
                }


                $response = pointfinder_paypal_request(
                  array(
                    'returnurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_rec',
                    'cancelurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_cancel',
                    'total_package_price' => $total_package_price,
                    'total_package_price_recurring' => $total_package_price_recurring,
                    'featured_package_price' => $featured_package_price,
                    'payment_custom_field' => $item_post_id,
                    'recurring' => $pointfinder_order_recurring,
                    'billing_description' => $billing_description,
                    'paymentName' => $paymentName,
                    'apipackage_name' => $pointfinder_order_listingpname,
                    'featuredrecurring' => $featuredrecurring,
                    'featured_billing_description' => $billing_description_featured
                  )
                );
              }

              if(!$response){ 
                $msg_output .= esc_html__( 'Error: No Response', 'pointfindert2d' ).'<br>';
                $icon_processout = 485;
                /*$errorval .= $paypal->getErrors();*/
              }
             
              if(is_array($response) && ($response['ACK'] == 'Success')) { 
                $token = $response['TOKEN']; 
                
                if ($pointfinder_sub_order_change == 1) {
                  update_post_meta($result_id, 'pointfinder_sub_order_token', $token ); 
                }else{
                  update_post_meta($result_id, 'pointfinder_order_token', $token ); 
                }

                /*Create a payment record for this process */
                PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'response'  =>  $response,
                    'token' =>  $response['TOKEN'],
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  $response['ACK'],
                    )
                  );
              
                $paypal_sandbox = PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
                if($paypal_sandbox == 0){
                  $pfreturn_url = 'https://www.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token).'';
                }else{
                  $pfreturn_url = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token).'';
                }
                
                $msg_output .= esc_html__('Payment process is ok. Please wait redirection.(Sub order)','pointfindert2d');

              }else{
                /*Create a payment record for this process */
           
                PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'response'  =>  $response,
                    'token' =>  '',
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  $response['ACK'],
                    )
                  );

                $msg_output .= esc_html__( 'Error: Not Success', 'pointfindert2d' ).'<br>';
                if (isset($response['L_SHORTMESSAGE0'])) {
                 $msg_output .= '<small>'.$response['L_SHORTMESSAGE0'].'</small><br/>';
                }
                if (isset($response['L_LONGMESSAGE0'])) {
                 $msg_output .= '<small>'.$response['L_LONGMESSAGE0'].'</small><br/>';
                }
                $icon_processout = 485;
                
              }

            }else{
              $msg_output .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
              $icon_processout = 485;
            }
          }
        }else{
          $msg_output .= esc_html__('Wrong item ID.','pointfindert2d');
          $icon_processout = 485;
        }
      }else{
        $msg_output .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
        $icon_processout = 485;
      }

      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      
      $output_html .= "<div class='pf-overlay-close'><i class='pfadmicon-glyph-707'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      
      $output_html .= '</div>';
      $output_html .= '</div>';    
      if ($icon_processout == 485) {  
        echo json_encode( array( 'process'=>false, 'mes'=>$output_html, 'returnurl' => $pfreturn_url));
      }else{
        echo json_encode( array( 'process'=>true, 'mes'=>'', 'returnurl' => $pfreturn_url));
      }
		break;

    case 'creditcardstripe':
       
      $icon_processout = 62;
      $msg_output = $pfreturn_url = '';
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      if($user_id != 0){

        if ($item_post_id != '') {

          $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

          /*Check if item user s item*/
          global $wpdb;

          $result = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", $item_post_id,$user_id,$setup3_pointposttype_pt1) );
          
          if (is_array($result) && count($result)>0) {  
            
            if ($result[0]->ID == $item_post_id) {

              $setup20_stripesettings_decimals = PFSAIssetControl('setup20_stripesettings_decimals','','2');
              $setup20_stripesettings_publishkey = PFSAIssetControl('setup20_stripesettings_publishkey','','');
              $setup20_stripesettings_currency = PFSAIssetControl('setup20_stripesettings_currency','','USD');
              $setup20_stripesettings_sitename = PFSAIssetControl('setup20_stripesettings_sitename','','');
              
              $user_email = $current_user->user_email;

              /*Meta for order*/
              $result_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 'pointfinder_order_itemid',$item_post_id) );


              /* Check is this a change */
              $pointfinder_sub_order_change = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_change', true ));
              
              if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                 $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                 $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true)); 
              }else{
                 $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                 $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
              }
             

              if ($setup20_stripesettings_decimals == 0) {
                $total_package_price =  $pointfinder_order_price;
              }else{
                $total_package_price =  $pointfinder_order_price.'00';
              }
              
            }else{
              $msg_output .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
              $icon_processout = 485;
            }
          }
        }else{
          $msg_output .= esc_html__('Wrong item ID.','pointfindert2d');
          $icon_processout = 485;
        }
      }else{
        $msg_output .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
        $icon_processout = 485;
      }


      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      
      $output_html .= "<div class='pf-overlay-close'><i class='pfadmicon-glyph-707'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      
      $output_html .= '</div>';
      $output_html .= '</div>';    

      if ($icon_processout == 485) {  
        echo json_encode( array( 'process'=>false, 'mes'=>$output_html, 'returnurl' => ''));
      }else{
        echo json_encode( array( 'process'=>true, 'otype'=>$otype, 'name'=>$setup20_stripesettings_sitename, 'description'=>$pointfinder_order_listingpname, 'amount' => $total_package_price,'key'=>$setup20_stripesettings_publishkey,'email'=>$user_email,'currency'=>$setup20_stripesettings_currency));
      }
    break;

    case 'stripepayment':
      
      $icon_processout = 62;
      $msg_output = $pfreturn_url = '';
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      if($user_id != 0){

        if ($item_post_id != '') {

          $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

          /*Check if item user s item*/
          global $wpdb;

          $result = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", $item_post_id,$user_id,$setup3_pointposttype_pt1) );
          
          if (is_array($result) && count($result)>0) {  
            
            if ($result[0]->ID == $item_post_id) {

              $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','',site_url());
              $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
              $pfmenu_perout = PFPermalinkCheck();

              $order_post_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %d", 'pointfinder_order_itemid',$item_post_id) );

              $setup20_stripesettings_decimals = PFSAIssetControl('setup20_stripesettings_decimals','','2');
              $user_email = $current_user->user_email;

              /*Meta for order*/
              $result_id = $order_post_id;


              /* Check is this a change */
              $pointfinder_sub_order_change = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_change', true ));
              
              if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true));
                $pointfinder_order_listingpname .= esc_html__('(Plan/Featured/Category Change)','pointfindert2d'); 
              }else{
                $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
              }

              if ($setup20_stripesettings_decimals == 0) {
                $total_package_price =  $pointfinder_order_price;
                $total_package_price_ex =  $pointfinder_order_price;
              }else{
                $total_package_price =  $pointfinder_order_price.'00';
                $total_package_price_ex =  $pointfinder_order_price.'.00';
              }
              
              $setup20_stripesettings_secretkey = PFSAIssetControl('setup20_stripesettings_secretkey','','');
              $setup20_stripesettings_publishkey = PFSAIssetControl('setup20_stripesettings_publishkey','','');
              $setup20_stripesettings_currency = PFSAIssetControl('setup20_stripesettings_currency','','USD');

              require_once( get_template_directory().'/admin/core/stripe/init.php');

              $stripe = array(
                "secret_key"      => $setup20_stripesettings_secretkey,
                "publishable_key" => $setup20_stripesettings_publishkey
              );

              \Stripe\Stripe::setApiKey($stripe['secret_key']);
              

              $token  = $_POST['token'];
              $token = PFCleanArrayAttr('PFCleanFilters',$token);
         
              $charge = '';
              if ($total_package_price != 0) {
                try {

                  $charge = \Stripe\Charge::create(array(
                    'amount'   => $total_package_price,
                    'currency' => ''.$setup20_stripesettings_currency.'',
                    'source'  => $token['id'],
                    'description' => "Charge for ".$pointfinder_order_listingpname.'(ItemID: '.$item_post_id.' / UserID: '.$user_id.')'
                  ));

                  if ($charge->status == 'succeeded') {

                    pointfinder_order_fallback_operations($order_post_id,$pointfinder_order_price);
                    
                    PF_CreatePaymentRecord(
                      array(
                      'user_id' =>  $user_id,
                      'item_post_id'  =>  $item_post_id,
                      'order_post_id' => $order_post_id,
                      'processname' =>  'DoExpressCheckoutPaymentStripe',
                      'status'  =>  $charge->status
                      )
                    );

                    /* Create an invoice for this */
                    PF_CreateInvoice(
                      array( 
                        'user_id' => $user_id,
                        'item_id' => $item_post_id,
                        'order_id' => $order_post_id,
                        'description' => $pointfinder_order_listingpname,
                        'processname' => esc_html__('Credit Card Payment','pointfindert2d'),
                        'amount' => $total_package_price_ex,
                        'datetime' => strtotime("now"),
                        'packageid' => 0,
                        'status' => 'publish'
                      )
                    );

                    if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                      
                      $pointfinder_sub_order_changedvals = get_post_meta( $order_post_id, 'pointfinder_sub_order_changedvals', true );
                                      
                      pointfinder_additional_orders(
                        array(
                          'changedvals' => $pointfinder_sub_order_changedvals,
                          'order_id' => $order_post_id,
                          'post_id' => $item_post_id
                        )
                      );

                    }else{
                      $setup31_userlimits_userpublish = PFSAIssetControl('setup31_userlimits_userpublish','','0');
                      $publishstatus = ($setup31_userlimits_userpublish == 1) ? 'publish' : 'pendingapproval' ;

                      wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
                      wp_reset_postdata();
                      wp_update_post(array('ID' => $order_post_id,'post_status' => 'completed') );
                      wp_reset_postdata();

                      $admin_email = get_option( 'admin_email' );
                      $setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);
                      $mail_item_title = get_the_title($item_post_id);
                      
                      pointfinder_mailsystem_mailsender(
                        array(
                          'toemail' => $user_email,
                              'predefined' => 'paymentcompleted',
                              'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex.'('.$setup20_stripesettings_currency.')','packagename' => $pointfinder_order_listingpname),
                          )
                        );

                      pointfinder_mailsystem_mailsender(
                        array(
                          'toemail' => $setup33_emailsettings_mainemail,
                              'predefined' => 'newpaymentreceived',
                              'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex.'('.$setup20_stripesettings_currency.')','packagename' => $pointfinder_order_listingpname),
                          )
                        );
                    }

                    $msg_output .= esc_html__('Payment is successful.','pointfindert2d');
                  }

                } catch(\Stripe\Error\Card $e) {
                  if(isset($e)){
                    $error_mes = json_decode($e->httpBody,true);
                    $icon_processout = 485;
                    $msg_output = (isset($error_mes['error']['message']))? $error_mes['error']['message']:'';
                    if (empty($msg_output)) {
                      $msg_output .= esc_html__('Payment not completed.','pointfindert2d');
                    }
                  }
                }
              }else{
                $msg_output .= esc_html__('Price can not be 0!). Payment process is stopped.','pointfindert2d');
                $icon_processout = 485;
              }
              
              

            }else{
              $msg_output .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
              $icon_processout = 485;
            }
          }
        }else{
          $msg_output .= esc_html__('Wrong item ID.','pointfindert2d');
          $icon_processout = 485;
        }
      }else{
        $msg_output .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
        $icon_processout = 485;
      }

      if ($icon_processout != 485) {
        $overlar_class = ' pfoverlayapprove';
      }else{
        $overlar_class = '';
      }

      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      
      $output_html .= "<div class='pf-overlay-close'><i class='pfadmicon-glyph-707'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext".$overlar_class."'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      
      $output_html .= '</div>';
      $output_html .= '</div>';    

      if ($icon_processout == 485) {  
        echo json_encode( array( 'process'=>false, 'mes'=>$output_html, 'returnurl' => ''));
      }else{
        echo json_encode( array( 'process'=>true, 'mes'=>$output_html, 'returnurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems'));
      }

    break;
	}
die();
}

?>