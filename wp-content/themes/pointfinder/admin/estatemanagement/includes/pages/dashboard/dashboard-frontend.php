<script type="text/javascript">
    function jau_ver_municipios(CB){

		var id =  jQuery("#estados").val();
		var txt = jQuery("#estados option:selected").text();

        jQuery.ajax( {
            method: "POST",
                data: { estado: id },
            url: "<?php echo get_template_directory_uri(); ?>/vlz/ajax_municipios.php",
            beforeSend: function( xhr ) {
		    	jQuery("#municipios").html("<option value=''>Cargando Localidades</option>");
		    	console.log(jQuery('#municipios'))
            }
        }).done(function(data){
			jQuery("#municipios").html("<option value=''>Seleccione una Localidad</option>"+data);
            if( CB != undefined) {
                CB();
            }
        });
    }
</script>

<?php
/**********************************************************************************************************************************
*
* Custom Detail Fields Frontend Class
* 
* Author: Webbu Design
*
* Location: /public_html/wp-content/themes/pointfinder/admin/estatemanagement/includes/pages/dashboard/
*
***********************************************************************************************************************************/
get_template_part('vlz/admin/dashboard/frontend/functions');

if ( ! class_exists( 'PF_Frontend_Fields' ) ){
	class PF_Frontend_Fields
	{
		public $FieldOutput;
		public $ScriptOutput;
		public $ScriptOutputDocReady;
		public $VSORules;
		public $VSOMessages;
		public $PFHalf = 1;
		private $itemrecurringstatus = 0;


/************************************************************************************************************************************/


		function __construct($params = array()){	

			$defaults = array( 
		        'fields' => '',
		        'formtype' => '',
		        'sccval' => '',
				'errorval' => '',
				'post_id' => '',
				'sheader' => '',
				'sheadermes' => '',
				'current_user' => '',
				'dontshowpage' => 0,
				'redirect' => false
		    );

		    $params = array_merge($defaults, $params);

		    $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');
			$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
			$pfmenu_perout = PFPermalinkCheck();

			$lang_custom = '';

			if(function_exists('icl_object_id')) {
				$lang_custom = PF_current_language();
			}

			/**
			*Start: Page Header Actions / Divs / Etc...
			**/ 
				$this->FieldOutput = '<div class="golden-forms">';
				$this->FieldOutput .= '<form id="pfuaprofileform" enctype="multipart/form-data" name="pfuaprofileform" method="POST" action="">';
				$this->FieldOutput .= '<div class="pfsearchformerrors"><ul></ul><a class="button pfsearch-err-button">'.esc_html__('CERRAR','pointfindert2d').'</a></div>';
				if($params['sccval'] != ''){
					$this->FieldOutput .= '<div class="notification success" id="pfuaprofileform-notify"><div class="row"><p>'.$params['sccval'].'<br>'.$params['sheadermes'].'</p></div></div>';
					$this->ScriptOutput .= '$(document).ready(function(){$.pfmessagehide();});';
				}
				if($params['errorval'] != ''){
					$this->FieldOutput .= '<div class="notification error" id="pfuaprofileform-notify"><p>'.$params['errorval'].'</p></div>';
					$this->ScriptOutput .= '$(document).ready(function(){$.pfmessagehide();});';
				}
				$this->FieldOutput .= '<div class="">';
				$this->FieldOutput .= '<div class="">';
				$this->FieldOutput .= '<div class="row">';

			/**
			*End: Page Header Actions / Divs / Etc...
			**/
				$main_submit_permission = true;
				$main_package_purchase_permission = false;
				$main_package_renew_permission = false;
				$main_package_limit_permission = false;
				$main_package_upgrade_permission = false;
				$main_package_expire_problem = false;

				$hide_button = false;

				switch ($params['formtype']) {
					case 'purchaseplan':
					case 'renewplan':
					case 'upgradeplan':
						$formaction = 'pfget_membershipsystem';
						$noncefield = wp_create_nonce($formaction);
						/**
						*Start: Purchase Plan Content
						**/
							/*If membership activated*/
							$setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
							$user_idx = $params['current_user']; 
							$membership_user_package_id = get_user_meta( $user_idx, 'membership_user_package_id', true );
							$membership_user_package = get_user_meta( $user_idx, 'membership_user_package', true );
							$membership_user_item_limit = get_user_meta( $user_idx, 'membership_user_item_limit', true );
							$membership_user_featureditem_limit = get_user_meta( $user_idx, 'membership_user_featureditem_limit', true );
							$membership_user_image_limit = get_user_meta( $user_idx, 'membership_user_image_limit', true );
							$membership_user_trialperiod = get_user_meta( $user_idx, 'membership_user_trialperiod', true );

							$membership_user_activeorder = get_user_meta( $user_idx, 'membership_user_activeorder', true );
							$membership_user_expiredate = get_post_meta( $membership_user_activeorder, 'pointfinder_order_expiredate', true );
							$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');


							if(empty($membership_user_package_id) && $params['formtype'] == 'purchaseplan'){
								$main_package_purchase_permission = true;
							}
							if ($params['formtype'] == 'renewplan' && !empty($membership_user_package_id)) {
								$main_package_renew_permission = true;
							}elseif ($params['formtype'] == 'renewplan' && empty($membership_user_package_id)){
								$main_package_renew_permission = false;
								$main_package_purchase_permission = true;
								$params['formtype'] = 'purchaseplan';
							}
							if ($params['formtype'] == 'upgradeplan' && !empty($membership_user_package_id)) {
								$main_package_upgrade_permission = true;
							}elseif ($params['formtype'] == 'upgradeplan' && empty($membership_user_package_id)){
								$main_package_upgrade_permission = false;
								$main_package_purchase_permission = true;
								$params['formtype'] = 'purchaseplan';
							}
							

							/*
							* Start: Order removed expire problem - Membership package
							*/
								if ($main_package_expire_problem) {
									$hide_button = true;
									echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.esc_html__("Por favor, póngase en contacto con el administrador del sitio. Su pedido de membresía tiene un problema.","pointfindert2d").'</div>';
								}
							/*
							* End: Order removed expire problem - Membership package
							*/


							/*
							* Start: Show Limit Full Message - Membership package
							*/
								if ($main_package_limit_permission) {
									$hide_button = true;
									echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.esc_html__("Su plan de miembrecía ha alcanzado el límite. Por favor, actualice su paquete o ponerse en contacto con el administrador del sitio.","pointfindert2d").'</div>';
								}
							/*
							* End: Show Limit Full Message - Membership package
							*/


							/*
							* Start: Purchase Membership package
							*/
								if ($main_package_purchase_permission == true || $main_package_upgrade_permission == true || $main_package_renew_permission == true) {

									$p_continue = true;

									switch ($params['formtype']) {
										case 'purchaseplan':
											$buttonid = 'pf-ajax-purchasepack-button';
											$buttontext = esc_html__('Compra Completa',"pointfindert2d"  );
											break;
										
										case 'renewplan':
											$buttonid = 'pf-ajax-purchasepack-button';
											$buttontext = esc_html__('Renovar Plan',"pointfindert2d"  );
											break;

										case 'upgradeplan':
											$buttonid = 'pf-ajax-purchasepack-button';
											$buttontext = esc_html__('Actualizar Plan',"pointfindert2d"  );
											break;
									}
									
									if($p_continue){
										/** 
										*Purchase Membership Package 
										**/
												$is_pack = 0;
												
												switch ($params['formtype']) {
													case 'purchaseplan':
														$membership_query = new WP_Query(array('post_type' => 'pfmembershippacks','posts_per_page' => -1,'order_by'=>'ID','order'=>'ASC'));
														break;
													
													case 'renewplan':
														$membership_query = new WP_Query(array('post_type' => 'pfmembershippacks','posts_per_page' => -1,'order_by'=>'ID','order'=>'ASC','p'=>$membership_user_package_id));
														$this->ScriptOutput = "$.pfmembershipgetp(".$membership_user_package_id.",'".$params['formtype']."');";
														break;

													case 'upgradeplan':

														$total_icounts = pointfinder_membership_count_ui($user_idx);

														/*Count User's Items*/
														$user_post_count = 0;
														$user_post_count = $total_icounts['item_count'];

														/*Count User's Featured Items*/
														$users_post_featured = 0;
														$users_post_featured = $total_icounts['fitem_count'];

														
														if ($user_post_count == 0 && $users_post_featured == 0) {
															$membership_query = new WP_Query(array(
																'post_type' => 'pfmembershippacks',
																'posts_per_page' => -1,
																'order_by'=>'ID',
																'order'=>'ASC',
																'post__not_in' => array($membership_user_package_id),
																'meta_query' => array(
																	
																	'relation' => 'AND',
																	array(
																		'relation' => 'OR',
																		array(
																			'key'     => 'webbupointfinder_mp_itemnumber',
																			'value'   => $user_post_count,
																			'compare' => '>=',
																			'type' => 'NUMERIC'
																		),
																		array(
																			'key'     => 'webbupointfinder_mp_itemnumber',
																			'value'   => 0,
																			'compare' => '<',
																			'type' => 'NUMERIC'
																		)
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_fitemnumber',
																		'value'   => $users_post_featured,
																		'compare' => '>=',
																		'type' => 'NUMERIC'
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_showhide',
																		'value'   => 1,
																		'compare' => '=',
																		'type' => 'NUMERIC'
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_price',
																		'value'   => 0,
																		'compare' => '>',
																		'type' => 'NUMERIC'
																	),

																),
															));
														}else{
															$membership_query = new WP_Query(array(
																'post_type' => 'pfmembershippacks',
																'posts_per_page' => -1,
																'order_by'=>'ID',
																'order'=>'ASC',
																'post__not_in' => array($membership_user_package_id),
																'meta_query' => array(
																	
																	'relation' => 'AND',
																	array(
																		'relation' => 'OR',
																		array(
																			'key'     => 'webbupointfinder_mp_itemnumber',
																			'value'   => $user_post_count,
																			'compare' => '>=',
																			'type' => 'NUMERIC'
																		),
																		array(
																			'key'     => 'webbupointfinder_mp_itemnumber',
																			'value'   => 0,
																			'compare' => '<',
																			'type' => 'NUMERIC'
																		)
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_fitemnumber',
																		'value'   => $users_post_featured,
																		'compare' => '>=',
																		'type' => 'NUMERIC'
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_images',
																		'value'   => $membership_user_image_limit,
																		'compare' => '>=',
																		'type' => 'NUMERIC'
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_showhide',
																		'value'   => 1,
																		'compare' => '=',
																		'type' => 'NUMERIC'
																	),

																),
															));
														}
														
														
														break;
												}

												/*print_r($membership_query->request);*/
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-title-membershippack"><i class="pfadmicon-glyph-10"></i> '.esc_html__('SELECCIONE UN PLAN','pointfindert2d').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-membership">';
												if ($params['formtype'] == "renewplan") {
													$this->FieldOutput .= '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.esc_html__("Sólo se puede seleccionar su plan actual. Si quieren cambiar con otro plan, intenta actualizar.","pointfindert2d").'</div>';
												}
												if ($params['formtype'] == "upgradeplan" && $membership_query->have_posts()) {
													if ($user_post_count == 0 && $users_post_featured == 0) {
														/*$this->FieldOutput .= '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.sprintf(esc_html__("Your current limits require %d item and %d featured item limit. Only below packages available for upgrade. You can remove some items if want to use lower limited packages.","pointfindert2d"),$user_post_count,$users_post_featured).'</div>';*/
													}else{
														$this->FieldOutput .= '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.sprintf(esc_html__("Sus límites actuales requerien %d elementos y %d elementos destacados y %d imágenes. Only below packages available for upgrade. You can remove some items if want to use lower limited packages.","pointfindert2d"),$user_post_count,$users_post_featured,$membership_user_image_limit).'</div>';
													}
												}
												if ($params['formtype'] == "upgradeplan" && !$membership_query->have_posts()) {
													$this->FieldOutput .= '<div class="pf-dash-errorview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.sprintf(esc_html__("We can't find an available plan for you. Your current limits require %d item and %d featured item and %d image limit. Please try to remove some items or contact with administrator of your site.","pointfindert2d"),$user_post_count,$users_post_featured,$membership_user_image_limit).'</div>';
												}
												if ( $membership_query->have_posts() ) {
												  $this->FieldOutput .= '<ul class="pf-membership-package-list">';
												 
												  while ( $membership_query->have_posts() ) {
												    $membership_query->the_post();

												    $post_id = get_the_id();

												    $packageinfo = pointfinder_membership_package_details_get($post_id);

												    if ($packageinfo['webbupointfinder_mp_showhide'] == 1) {
													    $this->FieldOutput .= '<li>
													    <div class="pf-membership-package-box">
													    	<div class="pf-membership-package-title">' . get_the_title() . '</div>
													    	<div class="pf-membership-package-info">
																<ul>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Precio:','pointfindert2d').' </span> '.$packageinfo['packageinfo_priceoutput_text'].'</li>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Número de los listados incluidos en el paquete:','pointfindert2d').' </span> '.$packageinfo['packageinfo_itemnumber_output_text'].'</li>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Número de listados destacados incluidos en el paquete:','pointfindert2d').' </span> '.$packageinfo['webbupointfinder_mp_fitemnumber'].'</li>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Número de imágenes (por lista) incluidas en el paquete:','pointfindert2d').' </span> '.$packageinfo['webbupointfinder_mp_images'].'</li>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Los anuncios pueden ser enviados dentro de:','pointfindert2d').' </span> '.$packageinfo['webbupointfinder_mp_billing_period'].' '.$packageinfo['webbupointfinder_mp_billing_time_unit_text'].'</li>
																	';
																	if ($packageinfo['webbupointfinder_mp_trial'] == 1 && $packageinfo['packageinfo_priceoutput'] != 0) {
																		$this->FieldOutput .= '<li><span class="pf-membership-package-info-title">'.esc_html__('Periodo de prueba:','pointfindert2d').' </span> '.$packageinfo['webbupointfinder_mp_trial_period'].' '.$packageinfo['webbupointfinder_mp_billing_time_unit_text'].' <br/><small>'.esc_html__('Nota: Su anuncio en expirará al final del período de prueba.','pointfindert2d').'</small></li>';
																	}
																	if (!empty($packageinfo['webbupointfinder_mp_description'])) {
																		$this->FieldOutput .= '<li><span class="pf-membership-package-info-title">'.esc_html__('Descripción:','pointfindert2d').' </span> '.$packageinfo['webbupointfinder_mp_description'].'</li>';
																	}
																	
																	$this->FieldOutput .= '
																</ul>
													    	</div>
													    	<div class="pf-membership-splan-button">
							                                    <a data-id="'.$post_id.'" data-ptype="'.$params['formtype'].'">'.esc_html__('Seleccione','pointfindert2d').'</a>
							                                </div>
													    </div>
													    </li>';
													    $is_pack++;
													}
												  }
												  if ($is_pack == 0) {
												  	$this->FieldOutput .= esc_html__("Por favor, establezca a visible uno de sus planes.",'pointfindert2d');
												  }
												  $this->FieldOutput .= '</ul>';
												} else {
													if ($params['formtype'] == 'purchaseplan') {
														$this->FieldOutput .= esc_html__("Por favor, crear algunos planes de membresía.",'pointfindert2d' );
													}
												  
												}

											$this->FieldOutput .= '</section>';
										/**
										*Purchase Membership Package 
										**/

										/** 
										*PAY Membership Package 
										**/
											$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-title-membershippack-payment"><i class="pfadmicon-glyph-11"></i> '.esc_html__('SELECCIONE LA FORMA DE PAGO','pointfindert2d').'</div>';
											$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-membership-payment">';
													
													$this->FieldOutput .= '<div class="pfm-payment-plans"><div class="pfm-payment-plans-inner">'.esc_html__('Por favor, seleccione un plan de opciones de pago.','pointfindert2d' ).'</div></div>';
													
											$this->PFValidationCheckWrite(1,esc_html__('Por favor, seleccione una forma de pago.','pointfindert2d' ),'pf_membership_payment_selection');
											$this->PFValidationCheckWrite(1,esc_html__('Por favor seleccione un tipo de plan','pointfindert2d' ),'selectedpackageid');


											
											
											$this->FieldOutput .= '</section>';
										/**
										*PAY Membership Package 
										**/

										/**
										*Terms and conditions
										**/
											$setup4_mem_terms = PFSAIssetControl('setup4_mem_terms','','1');
											if ($setup4_mem_terms == 1) {

												$this->PFValidationCheckWrite(1,esc_html__('Debe aceptar los términos y condiciones.','pointfindert2d' ),'pftermsofuser');

												global $wpdb;
												$terms_conditions_template = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ",'_wp_page_template','terms-conditions.php'), ARRAY_A);
												if (isset($terms_conditions_template[0]['post_id'])) {
													$terms_permalink = get_permalink($terms_conditions_template[0]['post_id']);
												}else{
													$terms_permalink = '#';
												}
												
												
												if ($params['formtype'] == 'edititem') {
													$checktext1 = ' checked=""';
												}else{$checktext1 = '';}
												
												$pfmenu_perout = PFPermalinkCheck();

												$this->FieldOutput .= '<section style="margin-top: 20px;margin-bottom: 10px;">';
												$this->FieldOutput .= '
													<span class="goption upt">
					                                    <label class="options">
					                                        <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1"'.$checktext1.'>
					                                        <span class="checkbox"></span>
					                                    </label>
					                                    <label for="check1">'.sprintf(esc_html__( 'He leído los %s términos y condiciones de %s y las acepto.', 'pointfindert2d' ),'<a href="'.$terms_permalink.$pfmenu_perout.'ajax=true&width=800&height=400" rel="prettyPhoto[ajax]"><strong>','</strong></a>').'</label>
					                               </span>
												';
												
								                $this->FieldOutput .= '</section>';
								            }
										/**
										*Terms and conditions
										**/

										
									}
								}elseif (empty($membership_user_package_id) == false && $main_package_purchase_permission == false && $params['formtype'] == 'purchaseplan') {
									$hide_button = true;
									echo '<div class="pf-dash-errorview-plan"><i class="pfadmicon-glyph-485" style="color:black;font-size: 16px;"></i> '.esc_html__("No se puede comprar un nuevo plan. Debido a que ya tiene uno.","pointfindert2d").'</div>';
									$p_continue = false;
								}
							/*
							* End: Purchase - Membership package
							*/



						/**
						*End: Purchase Plan Content
						**/
						break;

					case 'upload':
					case 'edititem':
						/**
						*Start: New Item Page Content
						**/
							global $pointfindertheme_option;

							if($params['formtype'] == 'upload'){
								$formaction = 'pfget_uploaditem';
								$buttonid = 'pf-ajax-uploaditem-button';
								$buttontext = PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','');
								
							}else{
								$formaction = 'pfget_edititem';
								$buttonid = 'pf-ajax-uploaditem-button';
								$buttontext = PFSAIssetControl('setup29_dashboard_contents_submit_page_titlee','','');

							}

							$noncefield = wp_create_nonce($formaction);

							if ($params['dontshowpage'] != 1) {
							
							wp_enqueue_script('theme-dropzone');
							wp_enqueue_script('theme-google-api');
							wp_enqueue_script('theme-gmap3');
							wp_enqueue_style('theme-dropzone');
							wp_enqueue_script('jquery-ui-core');
							wp_enqueue_script('jquery-ui-datepicker');
							wp_enqueue_style('jquery-ui-smoothnesspf2', get_template_directory_uri() . "/css/jquery-ui.structure.min.css", false, null);
							wp_enqueue_style('jquery-ui-smoothnesspf', get_template_directory_uri() . "/css/jquery-ui.theme.min.css", false, null);


							/* Get Admin Settings for Default Fields */
							$setup4_submitpage_titletip = PFSAIssetControl('setup4_submitpage_titletip','','');
							$maplanguage= PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
							
							/*If membership activated*/
							$setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
							if ($setup4_membersettings_paymentsystem == 2) {
								$user_idx = $params['current_user']; 
								$membership_user_package_id = get_user_meta( $user_idx, 'membership_user_package_id', true );

								if (!empty($membership_user_package_id)) {
									$packageinfo = pointfinder_membership_package_details_get($membership_user_package_id);
								}
								$membership_user_package = get_user_meta( $user_idx, 'membership_user_package', true );
								$membership_user_item_limit = get_user_meta( $user_idx, 'membership_user_item_limit', true );
								$membership_user_featureditem_limit = get_user_meta( $user_idx, 'membership_user_featureditem_limit', true );
								$membership_user_image_limit = get_user_meta( $user_idx, 'membership_user_image_limit', true );
								$membership_user_trialperiod = get_user_meta( $user_idx, 'membership_user_trialperiod', true );

								$membership_user_activeorder = get_user_meta( $user_idx, 'membership_user_activeorder', true );
								$membership_user_expiredate = get_post_meta( $membership_user_activeorder, 'pointfinder_order_expiredate', true );
							}

							$current_post_status = get_post_status($params['post_id']);
							if ($params['post_id'] != '') {

								$order_id_current = PFU_GetOrderID($params['post_id'],1);

								$is_this_itemrecurring = get_post_meta( $order_id_current, 'pointfinder_order_recurring', true );
								if ($is_this_itemrecurring == false) {
									$is_this_itemrecurring = get_post_meta( $order_id_current, 'pointfinder_order_frecurring', true );
								}

								if (($current_post_status == 'publish' || $current_post_status == 'pendingapproval') && !empty($is_this_itemrecurring) ) {
									$this->itemrecurringstatus = 1;
								}

								/* Clean sub order values if exist. */
								$change_value_status = get_post_meta( $order_id_current, "pointfinder_sub_order_change", true);
								
								if ($change_value_status != false) {
									pointfinder_remove_sub_order_metadata($order_id_current);
								}
								
							}

							/*** DEFAULTS FOR FIRST COLUMN ***/
								$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
								$setup4_submitpage_itemtypes_check = PFSAIssetControl('setup4_submitpage_itemtypes_check','','1');
								$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
								$setup4_submitpage_locationtypes_check = PFSAIssetControl('setup4_submitpage_locationtypes_check','','1');
								$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
								$setup4_submitpage_featurestypes_check = PFSAIssetControl('setup4_submitpage_featurestypes_check','','1');
								$setup4_submitpage_minres = 10;
								$setup4_submitpage_maparea_verror = PFSAIssetControl('setup4_submitpage_maparea_verror','','');
								$st4_sp_med = PFSAIssetControl('st4_sp_med','','1');
								$setup4_submitpage_locationtypes_validation = PFSAIssetControl('setup4_submitpage_locationtypes_validation','','1');
								$setup4_submitpage_locationtypes_verror = PFSAIssetControl('setup4_submitpage_locationtypes_verror','','Please select a location.');
								$setup4_submitpage_itemtypes_validation = PFSAIssetControl('setup4_submitpage_itemtypes_validation','','1');
								$setup4_submitpage_itemtypes_verror = PFSAIssetControl('setup4_submitpage_itemtypes_verror','','Please select an item type.');
								$setup4_submitpage_itemtypes_multiple = PFSAIssetControl('setup4_submitpage_itemtypes_multiple','','0');
								$stp4_fupl = PFSAIssetControl("stp4_fupl","","0");

								$setup20_paypalsettings_paypal_price_short = PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','$');
								$setup20_paypalsettings_paypal_price_pref = PFSAIssetControl('setup20_paypalsettings_paypal_price_pref','',1);

							/*** DEFAULTS FOR SECOND COLUMN ***/
								$setup4_submitpage_video = PFSAIssetControl('setup4_submitpage_video','','1');
								$setup4_submitpage_imageupload = PFSAIssetControl('setup4_submitpage_imageupload','','1');
								$setup4_submitpage_imagelimit = PFSAIssetControl('setup4_submitpage_imagelimit','','10');
								$setup4_submitpage_messagetorev = PFSAIssetControl('setup4_submitpage_messagetorev','','1');
								$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','0');
								$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard );
								$setup4_submitpage_featuredverror = PFSAIssetControl('setup4_submitpage_featuredverror','','');
								$setup4_submitpage_featuredverror_status = PFSAIssetControl('setup4_submitpage_featuredverror_status','',1);
								$stp4_err_st = PFSAIssetControl("stp4_err_st","","0");
								$stp4_err = PFSAIssetControl("stp4_err","",esc_html__('Por favor, sube un archivo adjunto.', 'pointfindert2d'));
								$pfmenu_perout = PFPermalinkCheck();

								$setup4_submitpage_conditions_validation = PFSAIssetControl('setup4_submitpage_conditions_validation','',0);
								$setup4_submitpage_conditions_verror = PFSAIssetControl('setup4_submitpage_conditions_verror','','');
								$setup4_submitpage_conditions_check = PFSAIssetControl('setup4_submitpage_conditions_check','',0);
								$setup3_pt14_check = PFSAIssetControl('setup3_pt14_check','',0);
								$st4_sp_med2 = PFSAIssetControl('st4_sp_med2','',1);

								$package_featuredcheck = '';
								if ($params['post_id'] != '') {
									$package_featuredcheck = get_post_meta( $params['post_id'], 'webbupointfinder_item_featuredmarker', true );
								}

								$default_package = 1;

								if ($params['post_id'] != '' && $setup4_membersettings_paymentsystem == 1) {
									$default_package_meta = get_post_meta( PFU_GetOrderID($params['post_id'],1), 'pointfinder_order_listingpid',true);
									
									if (!empty($default_package_meta)) {
										if ($default_package_meta == 1 || $default_package_meta == 2) {
											$default_package = 1;
										}else{
											$default_package = $default_package_meta;
										}
									}
								}

							

							if ($setup4_membersettings_paymentsystem == 2) {
								if(empty($membership_user_package_id)){
									$main_submit_permission = false;
									$main_package_purchase_permission = true;
								}else{
									
									if (!empty($membership_user_expiredate)) {
										if (pf_membership_expire_check($membership_user_expiredate)) {
											$main_submit_permission = false;
											$main_package_renew_permission = true;
										}else{
											if ($membership_user_item_limit == 0 && $params['formtype'] == 'upload') {
												$main_submit_permission = false;
												$main_package_limit_permission = true;
											}elseif ($membership_user_item_limit == -1 && $params['formtype'] == 'upload') {
												$main_submit_permission = true;
											}
											
										}
									} else {
										$main_submit_permission = false;
										$main_package_expire_problem = true;
									}
									

									$setup4_submitpage_imagelimit = $membership_user_image_limit;
								}
								
							}

							if ($setup4_membersettings_paymentsystem == 2) {

								/*
								* Start: Order removed expire problem - Membership package
								*/
									if ($main_package_expire_problem) {
										$hide_button = true;
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.esc_html__("Por favor, póngase en contacto con el administrador del sitio. Su pedido de membresía tiene un problema.","pointfindert2d").'</div>';
									}
								/*
								* End: Order removed expire problem - Membership package
								*/


								/*
								* Start: Show Limit Full Message - Membership package
								*/
									if ($main_package_limit_permission) {
										$hide_button = true;
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.esc_html__("Su plan de membresía a alcanzado el límite. Por favor, actualice su paquete o ponerse en contacto con el administrador del sitio.","pointfindert2d").'</div>';
									}
								/*
								* End: Show Limit Full Message - Membership package
								*/


								/*
								* Start: Renew Membership package
								*/
									if ($main_package_renew_permission) {
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.esc_html__("Su plan de membresía expiró. Usted está siendo redirigido...","pointfindert2d").'</div>';
										echo '<script>window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=renewplan'.'";</script>';}
								/*
								* End: Renew Membership package
								*/


								/*
								* Start: Upgrade Membership package
								*/
									if ($main_package_upgrade_permission) {
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.esc_html__("Usted está siendo redirigido al Área de Actualización...","pointfindert2d").'</div>';
										echo '<script>window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=upgradeplan'.'";</script>';}
								/*
								* End: Upgrade Membership package
								*/

								/*
								* Start: Purchase Membership package
								*/
									if ($main_package_purchase_permission) {
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="pfadmicon-glyph-482" style="color:black;font-size: 16px;"></i> '.esc_html__("Usted debe comprar un nuevo plan de membresía. Usted está siendo redirigido...","pointfindert2d").'</div>';
										echo '<script>window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=purchaseplan'.'";</script>';}
								/*
								* End: Purchase Membership package
								*/
							}


							if ($main_submit_permission) {
								/** 
								*Start : First Column (Custom Fields)
								**/
										if ($this->itemrecurringstatus == 1) {
											$this->FieldOutput .= '<div class="notification warning" style="border:1px solid rgba(255, 206, 94, 0.99)!important" id="pfuaprofileform-notify"><div class="row"><p><i class="pfadmicon-glyph-731"></i> '.esc_html__('No se puede cambiar Tipos de listados, No se puede cambiar Tipos de listados. Por favor, cancelar la opción de pago recurrente para cambiar estos valores.','pointfindert2d').'<br></p></div></div>';
										}

										/**
										*Listing Types
										**/
											$setup4_submitpage_listingtypes_title = PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
											$setup4_submitpage_sublistingtypes_title = PFSAIssetControl('setup4_submitpage_sublistingtypes_title','','Sub Listing Type');
											$setup4_submitpage_subsublistingtypes_title = PFSAIssetControl('setup4_submitpage_subsublistingtypes_title','','Sub Sub Listing Type');
											$setup4_submitpage_listingtypes_verror = PFSAIssetControl('setup4_submitpage_listingtypes_verror','','Please select a listing type.');
											$stp4_forceu = PFSAIssetControl('stp4_forceu','',0);

											$setup4_ppp_catprice = PFSAIssetControl('setup4_ppp_catprice','','0');

											$itemfieldname = 'pfupload_listingtypes';
											$this_cat_price_output = $status_selector = $status_pc = '';

											$this->PFValidationCheckWrite(1,$setup4_submitpage_listingtypes_verror,$itemfieldname);

											$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderltypes', array("fields" => "ids")) : '' ;
											$item_defaultvalue_output = $sub_level = $sub_sub_level = $item_defaultvalue_output_orj = '';


											
											/* Get Prices For All Cats & Category options for this listing */
											
											$cat_extra_opts = get_option('pointfinderltypes_covars');

											if (count($item_defaultvalue) > 1) {
												if (isset($item_defaultvalue[0])) {
													$item_defaultvalue_output_orj = $item_defaultvalue[0];
													$find_top_parent = pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');

													$ci=1;
													foreach ($item_defaultvalue as $value) {
														$sub_level .= $value;
														if ($ci < count($item_defaultvalue)) {
															$sub_level .= ',';
														}
														$ci++;
													}
													$item_defaultvalue_output = $find_top_parent['parent'];
												}
											}else{
												if (isset($item_defaultvalue[0])) {
													$item_defaultvalue_output_orj = $item_defaultvalue[0];
													$find_top_parent = pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');

													switch ($find_top_parent['level']) {
														case '1':
															$sub_level = $item_defaultvalue[0];
															break;
														
														case '2':
															$sub_sub_level = $item_defaultvalue[0];
															$sub_level = pf_get_term_top_parent($item_defaultvalue[0],'pointfinderltypes');
															break;
													}
													

													$item_defaultvalue_output = $find_top_parent['parent'];
												}
											}

											
											
											$this->FieldOutput .= '<div class="pfsubmit-title">'.$setup4_submitpage_listingtypes_title.'</div>';
											$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-listingtype pferrorcontainer">';
											$this->FieldOutput .= '<section class="pfsubmit-inner-sub" style="margin-left: -10px!important;">';

												$listingtype_values = get_terms('pointfinderltypes',array('hide_empty'=>false,'parent'=> 0)); 
												
												$this->FieldOutput .= '<input type="hidden" name="pfupload_listingtypes" id="pfupload_listingtypes" value="'.$item_defaultvalue_output.'"/>';
												$this->FieldOutput .= '<input type="hidden" name="pfupload_listingpid" id="pfupload_listingpid" value="'.$params['post_id'].'"/>';
												$this->FieldOutput .= '<input type="hidden" name="pfupload_type" id="pfupload_type" value="'.$setup4_membersettings_paymentsystem .'"/>';

												if ($params['formtype'] == 'edititem' && $current_post_status != 'pendingpayment' && $setup4_ppp_catprice == 1 && $setup4_membersettings_paymentsystem == 1) {
													$control_cat_price = (isset($cat_extra_opts[$item_defaultvalue_output]['pf_categoryprice']))?$cat_extra_opts[$item_defaultvalue_output]['pf_categoryprice']:0;
													if ($control_cat_price != 0) {
														$status_selector = ' disabled="disabled"';
														$status_pc = 1;
													}
												}

												if ($this->itemrecurringstatus == 1) {
													$status_selector = ' disabled="disabled"';
												}

												if ($params['post_id'] != '') {
													$this->FieldOutput .= '<input type="hidden" name="pfupload_o" id="pfupload_o" value="'.PFU_GetOrderID($params['post_id'],1).'"/>';
												}

												if ($current_post_status != 'pendingpayment' && $params['formtype'] == 'edititem') {
													$this->FieldOutput .= '<input type="hidden" name="pfupload_c" id="pfupload_c" value="'.$status_pc.'"/>';
													$this->FieldOutput .= '<input type="hidden" name="pfupload_f" id="pfupload_f" value="'.$package_featuredcheck.'"/>';
													$this->FieldOutput .= '<input type="hidden" name="pfupload_p" id="pfupload_p" value="'.$default_package.'"/>';
												}else{
													$this->FieldOutput .= '<input type="hidden" name="pfupload_c" id="pfupload_c" />';
													$this->FieldOutput .= '<input type="hidden" name="pfupload_f" id="pfupload_f" />';
													$this->FieldOutput .= '<input type="hidden" name="pfupload_p" id="pfupload_p" />';
													$this->ScriptOutput .= "$(document).ready(function(){
														$.pf_get_priceoutput();
													});";
												}
												if ($params['formtype'] == 'edititem' && $current_post_status != 'pendingpayment'){
													$this->FieldOutput .= '<input type="hidden" name="pfupload_px" id="pfupload_px" value="1"/>';
												}
												
												

												$this->FieldOutput .= '<div class="pflistingtype-selector-main-top clearfix">';

												$subcatsarray = "var pfsubcatselect = [";
												$multiplesarray = "var pfmultipleselect = [";

												
												foreach ($listingtype_values as $listingtype_value) {
													
													/* Multiple select & Subcat Select */
													$multiple_select = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_multipleselect']))?$cat_extra_opts[$listingtype_value->term_id]['pf_multipleselect']:2;
													$subcat_select = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_subcatselect']))?$cat_extra_opts[$listingtype_value->term_id]['pf_subcatselect']:2;

													if ($multiple_select == 1) {$multiplesarray .= $listingtype_value->term_id.',';}
													if ($subcat_select == 1) {$subcatsarray .= $listingtype_value->term_id.',';}

													if ($setup4_ppp_catprice == 1 && $setup4_membersettings_paymentsystem == 1) {
														$this_cat_price = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_categoryprice']))?$cat_extra_opts[$listingtype_value->term_id]['pf_categoryprice']:0;
														if ($this_cat_price == 0) {
															$this_cat_price_output = '';
														}else{
															if ($setup20_paypalsettings_paypal_price_pref == 1) {
																$this_cat_price_output = ' <span style="font-weight:600;" title="'.esc_html__("Esta categoría de precio es ",'pointfindert2d' ).'('.$setup20_paypalsettings_paypal_price_short.$this_cat_price.')'.'">('.$setup20_paypalsettings_paypal_price_short.$this_cat_price.')</span>';
															}else{
																$this_cat_price_output = ' <span style="font-weight:600;" title="'.esc_html__("Esta categoría de precio es ",'pointfindert2d' ).'('.$this_cat_price.$setup20_paypalsettings_paypal_price_short.')'.'">('.$this_cat_price.$setup20_paypalsettings_paypal_price_short.')</span>';
															}
														}
													}

													
													$this->FieldOutput .= '<div class="pflistingtype-selector-main">';
													$this->FieldOutput .= '<input type="radio" name="radio" id="pfltypeselector'.$listingtype_value->term_id.'" class="pflistingtypeselector"'.$status_selector.' value="'.$listingtype_value->term_id.'" '.checked( $item_defaultvalue_output, $listingtype_value->term_id, 0 ).'/>';
													$this->FieldOutput .= '<label for="pfltypeselector'.$listingtype_value->term_id.'" style="font-weight:600;">'.$listingtype_value->name.$this_cat_price_output.'</label>';
													$this->FieldOutput .= '</div>';
													
												}

												$this->FieldOutput .= '</div>';
												$subcatsarray .= "];";
												$multiplesarray .= "];";

												$this->ScriptOutput .= $subcatsarray.$multiplesarray;

											$this->FieldOutput .= '<div style="margin-left:10px" class="pf-sub-listingtypes-container"></div>';
											

											$this->FieldOutput .= '</section>';
											$this->FieldOutput .= '</section>';

											
											$this->ScriptOutput .= "
											/* Start: Function for sub listing types */
												$.pf_get_sublistingtypes = function(itemid,defaultv){

													if ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) != -1) {
														var multiple_ex = 1;
													}else{
														var multiple_ex = 0;
													}
													$.ajax({
												    	beforeSend:function(){
												    		$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'show',message: '".esc_html__("Cargando campos...",'pointfindert2d')."'});
												    	},
														url: theme_scriptspf.ajaxurl,
														type: 'POST',
														dataType: 'html',
														data: {
															action: 'pfget_listingtype',
															id: itemid,
															default: defaultv,
															sname: 'pfupload_sublistingtypes',
															stext: '".$setup4_submitpage_sublistingtypes_title."',
															stype: 'listingtypes',
															stax: 'pointfinderltypes',
															lang: '".$lang_custom."',
															multiple: multiple_ex,
															security: '".wp_create_nonce('pfget_listingtype')."'
														},
													}).success(function(obj) {
														
														$('.pf-sub-listingtypes-container').append('<div class=\'pfsublistingtypes\'>'+obj+'</div>');

														if (obj != '') {
														";
														
														if ($stp4_forceu == 1) {
															$this->ScriptOutput .= "$('#pfupload_sublistingtypes').rules('add',{required: true,messages:{required:'".$setup4_submitpage_listingtypes_verror."'}});";
														}
														
														$this->ScriptOutput .= "

															if ($.pf_tablet_check()) {
																$('#pfupload_sublistingtypes').select2({
																	placeholder: '".esc_html__("Por favor, seleccione",'pointfindert2d')."', 
																	formatNoMatches:'".esc_html__("No se encontraron coincidencias",'pointfindert2d')."',
																	allowClear: true, 
																	minimumResultsForSearch: 10
																});
															}

															if ($.pf_tablet_check() == false) {
																$('.pf-special-selectbox').each(function(index, el) {
																	$(this).children('option:first').remove();

																	var dataplc = $(this).data('pf-plc');
																	if (dataplc) {
																		$(this).prepend('<option value=\"\">'+dataplc+'</option>');
																	}else{
																		$(this).prepend('<option value=\"\">'+theme_scriptspf.pfselectboxtex+'</option>');
																	};
																});
															};";

															if (empty($sub_sub_level)) {
															$this->ScriptOutput .= " if ($('#pfupload_sublistingtypes').val() != 0 && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																$.pf_get_subsublistingtypes($('#pfupload_sublistingtypes').val(),'');
															}";
															}

															
															$this->ScriptOutput .= "
															$('#pfupload_sublistingtypes').change(function(){
																if($(this).val() != 0 && $(this).val() != null){
																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		$('#pfupload_listingtypes').val($(this).val()).trigger('change');
																	}else{
																		$('#pfupload_listingtypes').val($(this).val());
																	}
																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		$.pf_get_subsublistingtypes($(this).val(),'');
																	}
																}else{
																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val());
																	}else{
																		$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val()).trigger('change');
																	}
																	
																}
																$('.pfsubsublistingtypes').remove();
															});
														}

													}).complete(function(obj,obj2){
														if (obj.responseText != '') {

															if (defaultv != '') {
																if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																	$('#pfupload_listingtypes').val(defaultv).trigger('change');
																}else{
																	$('#pfupload_listingtypes').val(defaultv);
																}
															}else{
																
																if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																	$('#pfupload_listingtypes').val(itemid).trigger('change');
																}else{
																	$('#pfupload_listingtypes').val(itemid);
																}
															}

															
															";
															
															if (!empty($sub_sub_level)) {
																$this->ScriptOutput .= "
																if (".$sub_level." == $('#pfupload_sublistingtypes').val()) {
																	$.pf_get_subsublistingtypes('".$sub_level."','".$sub_sub_level."');
																}
																";
															}
														$this->ScriptOutput .= "
														}
														setTimeout(function(){
															$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'hide'});
														},1000);
													});
												}

												$.pf_get_subsublistingtypes = function(itemid,defaultv){
													$.ajax({
												    	beforeSend:function(){
												    		$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'show',message: '".esc_html__("Cargando campos...",'pointfindert2d')."'});
												    	},
														url: theme_scriptspf.ajaxurl,
														type: 'POST',
														dataType: 'html',
														data: {
															action: 'pfget_listingtype',
															id: itemid,
															default: defaultv,
															sname: 'pfupload_subsublistingtypes',
															stext: '".$setup4_submitpage_subsublistingtypes_title."',
															stype: 'listingtypes',
															stax: 'pointfinderltypes',
															lang: '".$lang_custom."',
															security: '".wp_create_nonce('pfget_listingtype')."'
														},
													}).success(function(obj) {
														$('.pf-sub-listingtypes-container').append('<div class=\'pfsubsublistingtypes\'>'+obj+'</div>');
														if (obj != '') {
														";

														if ($stp4_forceu == 1) {
															$this->ScriptOutput .= "$('#pfupload_subsublistingtypes').rules('add',{required: true,messages:{required:'".$setup4_submitpage_listingtypes_verror."'}});";
														}
														$this->ScriptOutput .= "
														if ($.pf_tablet_check()) {
															$('#pfupload_subsublistingtypes').select2({
																placeholder: '".esc_html__("Por favor, seleccione",'pointfindert2d')."', 
																formatNoMatches:'".esc_html__("No se encontraron coincidencias",'pointfindert2d')."',
																allowClear: true, 
																minimumResultsForSearch: 10
															});
														}

														if ($.pf_tablet_check() == false) {
																$('.pf-special-selectbox').each(function(index, el) {
																	$(this).children('option:first').remove();

																	var dataplc = $(this).data('pf-plc');
																	if (dataplc) {
																		$(this).prepend('<option value=\"\">'+dataplc+'</option>');
																	}else{
																		$(this).prepend('<option value=\"\">'+theme_scriptspf.pfselectboxtex+'</option>');
																	};
																});
															};


															$('#pfupload_subsublistingtypes').change(function(){
																if($('#pfupload_subsublistingtypes').val() != 0){
																	
																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		$('#pfupload_listingtypes').val($(this).val()).trigger('change');
																	}else{
																		$('#pfupload_listingtypes').val($(this).val());
																	}

																}else{
																	
																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		$('#pfupload_listingtypes').val($('#pfupload_sublistingtypes').val()).trigger('change');
																	}else{
																		$('#pfupload_listingtypes').val($('#pfupload_sublistingtypes').val());
																	}
																}
															});
														}

													}).complete(function(obj,obj2){
														if (obj.responseText != '') {

															if (defaultv != '') {
																
																if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																	$('#pfupload_listingtypes').val(defaultv).trigger('change');
																}else{
																	$('#pfupload_listingtypes').val(defaultv);
																}
															}else{
																
																if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																	$('#pfupload_listingtypes').val(itemid).trigger('change');
																}else{
																	$('#pfupload_listingtypes').val(itemid);
																}
															}
														}
														setTimeout(function(){
															$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'hide'});
														},1000);
													});
												}
											/* End: Function for sub listing types */
											";


											/* Address/Location Area AJAX */

												$this->ScriptOutput .= "
												var pflimitarray = [
												";
												$pflimittext = '';
												/*Get Limits for Areas*/
												if ($st4_sp_med == 1) {
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_address_area'";
												}

												/*Get Limits for Areas*/
												if ($setup3_pointposttype_pt5_check == 1 && $setup4_submitpage_locationtypes_check == 1) {
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_location_area'";
												}

												/*Get Limits for Item Type Area*/
												if($setup3_pointposttype_pt4_check == 1 && $setup4_submitpage_itemtypes_check == 1){
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_itype_area'";
												}

												/*Get Limits for Conditions Area*/
												if($setup3_pt14_check == 1 && $setup4_submitpage_conditions_check == 1){
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_condition_area'";
												}

												/*Get Limits for Image Area*/
												if($setup4_submitpage_imageupload == 1){
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_image_area'";
												}

												/*Get Limits for File Area*/
												if($stp4_fupl == 1){
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_file_area'";
												}


												$this->ScriptOutput .= $pflimittext;
												$this->ScriptOutput .= "
												];

												$.pf_get_checklimits = function(itemid,limitvalue){
													$.ajax({
														url: theme_scriptspf.ajaxurl,
														type: 'POST',
														dataType: 'json',
														data: {
															action: 'pfget_listingtypelimits',
															id: itemid,
															limit: limitvalue,
															lang: '".$lang_custom."',
															security: '".wp_create_nonce('pfget_listingtypelimits')."'
														},
													}).success(function(obj) {";

															if ($st4_sp_med == 1) {
																$this->ScriptOutput .= "
																if (obj.pf_address_area == 2) {
																	$('.pfsubmit-inner-sub-address').hide();";
																	if ($st4_sp_med2 == 1) {
																		$this->ScriptOutput .= "
																		$('#pfupload_address').rules('remove');
																		$('#pfupload_lng_coordinate').rules('remove');
																		$('#pfupload_lat_coordinate').rules('remove');
																		";
																	}
																	$this->ScriptOutput .= "
																}else{
																	$('.pfsubmit-inner-sub-address').show();";
																	if ($st4_sp_med2 == 1) {
																		$this->ScriptOutput .= "
																		$('#pfupload_address').rules('add',{required: true,messages:{required:\"".esc_html__("Por favor ingrese una dirección",'pointfindert2d')."\"}});
																		$('#pfupload_lng_coordinate').rules('add',{required: true,messages:{required:\"".$setup4_submitpage_maparea_verror."\"}});
																		$('#pfupload_lat_coordinate').rules('add',{required: true,messages:{required:\"".$setup4_submitpage_maparea_verror."\"}});
																		";
																	}
																	$this->ScriptOutput .= "
																	$.pf_submit_page_map();
																}
																";
															}	

															if ($setup3_pointposttype_pt5_check == 1) {
																$this->ScriptOutput .= "
																if (obj.pf_location_area == 2) {
																	$('.pfsubmit-inner-sub-location').hide();
																";
																if ($setup4_submitpage_locationtypes_validation == 1) {
																	$this->ScriptOutput .= "$('#pfupload_locations').rules('remove');";
																}
																$this->ScriptOutput .= "
																}else{
																	$('.pfsubmit-inner-sub-location').show();
																";
																if ($setup4_submitpage_locationtypes_validation == 1) {
																	$this->ScriptOutput .= "$('#pfupload_locations').rules('add',{required: true,messages:{required:\"".$setup4_submitpage_locationtypes_verror."\"}});";
																}
																$this->ScriptOutput .= "
																}";
															}

															if ($setup3_pointposttype_pt4_check == 1) {
																$this->ScriptOutput .= "
																if (obj.pf_itype_area == 2) {
																	$('.pfsubmit-inner-sub-itype').hide();
																";
																
																if ($setup4_submitpage_itemtypes_validation == 1) {
																	$this->ScriptOutput .= "$('#pfupload_itemtypes').rules('remove');";
																}
																$this->ScriptOutput .= "
																}else{
																	$('.pfsubmit-inner-sub-itype').show();
																";
																if ($setup4_submitpage_itemtypes_validation == 1) {
																	$this->ScriptOutput .= "$('#pfupload_itemtypes').rules('add',{required: true,messages:{required:\"".$setup4_submitpage_itemtypes_verror."\"}});";
																}
																$this->ScriptOutput .= "
																}";
															}
															
															if ($setup3_pt14_check == 1) {

																$this->ScriptOutput .= "
																if (obj.pf_condition_area == 2) {
																	$('.pfsubmit-inner-sub-conditions').hide();
																";
																if ($setup4_submitpage_conditions_validation == 1) {
																	$this->ScriptOutput .= "$('#pfupload_conditions').rules('remove');";
																}
																$this->ScriptOutput .= "
																}else{
																	$('.pfsubmit-inner-sub-conditions').show();
																";
																if ($setup4_submitpage_conditions_validation == 1) {
																	$this->ScriptOutput .= "$('#pfupload_conditions').rules('add',{required: true,messages:{required:\"".$setup4_submitpage_conditions_verror."\"}});";
																}
																$this->ScriptOutput .= "
																}";
															}

															if ($setup4_submitpage_imageupload == 1) {
																$this->ScriptOutput .= "
																if (obj.pf_image_area == 2) {
																	$('.pfsubmit-inner-sub-image').hide();
																";
																$itemfieldname = 'pfuploadimagesrc' ;
																if ($params['formtype'] != 'edititem' && $setup4_submitpage_featuredverror_status == 1) {
																	$this->ScriptOutput .= "$('#".$itemfieldname."').rules('remove');";
																}
																$this->ScriptOutput .= "
																}else{
																	$('.pfsubmit-inner-sub-image').show();
																";
																if ($params['formtype'] != 'edititem' && $setup4_submitpage_featuredverror_status == 1) {
																	$this->ScriptOutput .= "$('#".$itemfieldname."').rules('add',{required: true,messages:{required:\"".$setup4_submitpage_featuredverror."\"}});";
																}
																$this->ScriptOutput .= "
																}";
															}

															if ($stp4_fupl == 1) {
																$this->ScriptOutput .= "
																if (obj.pf_file_area == 2) {
																	$('.pfsubmit-inner-sub-file').hide();
																";
																$itemfieldname = 'pfuploadfilesrc' ;
																if ($params['formtype'] != 'edititem' && $stp4_err_st == 1) {
																	$this->ScriptOutput .= "$('#".$itemfieldname."').rules('remove');";
																}
																$this->ScriptOutput .= "
																}else{
																	$('.pfsubmit-inner-sub-file').show();
																";
																if ($params['formtype'] != 'edititem' && $stp4_err_st == 1) {
																	$this->ScriptOutput .= "$('#".$itemfieldname."').rules('add',{required: true,messages:{required:\"".$stp4_err."\"}});";
																}
																$this->ScriptOutput .= "
																}";
															}
														
													$this->ScriptOutput .= "
													}).complete(function(){
														";
														/* if this is edit */
														if ($params['post_id'] != '') {
														$this->ScriptOutput .= "
														$('.pf-excludecategory-container').show();

														$('#pf-ajax-uploaditem-button').show();
														";
														}
														$this->ScriptOutput .= "
													});
												};";

										
											$this->ScriptOutput .= "$(function(){
												";
												/* if this is edit */

												if ($params['post_id'] != '') {

													$this->ScriptOutput .= "
													$.pf_get_checklimits('".$item_defaultvalue_output."',pflimitarray);
													$.pf_get_sublistingtypes($('#pfupload_listingtypes').val(),'".$sub_level."');
													
													if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) != -1)) {
														$.pf_getcustomfields_now(".$item_defaultvalue_output.");
														$.pf_getfeatures_now(".$item_defaultvalue_output.");
													}else{
														$.pf_getcustomfields_now($('#pfupload_listingtypes').val());
														$.pf_getfeatures_now($('#pfupload_listingtypes').val());
													}
													$.pf_customtab_generator($('#pfupload_listingtypes').val(),'".$params['post_id']."');

													";
													if (empty($sub_sub_level) && !empty($sub_level)) {
														$this->ScriptOutput .= "$('#pfupload_listingtypes').val('".$sub_level."');";
													}
												}
											$this->ScriptOutput .= "});";
											

											
											$this->ScriptOutput .= "
											$('#pfupload_listingtypes').change(function(){

												$.pf_getcustomfields_now($(this).val());

												$.pf_getfeatures_now($(this).val());

												$('.pf-excludecategory-container').show();

												$('#pf-ajax-uploaditem-button').show();

											});

											$('.pflistingtypeselector').change(function(){

												$('.pf-sub-listingtypes-container').html('');

												$('#pfupload_listingtypes').val($(this).val()).trigger('change');

												$.pf_get_sublistingtypes($(this).val(),'');

												$.pf_customtab_generator($(this).val(),'".$params['post_id']."');

												$.pf_get_checklimits($(this).val(),pflimitarray);

												$.pf_get_priceoutput();
											});
											";

										/**
										*Listing Types
										**/


										/**
										* Title & Description Area
										**/
											$this->FieldOutput .= '<div class="pf-excludecategory-container">';

											$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('INFORMACIÓN','pointfindert2d').'</div>';
											$this->FieldOutput .= '<section class="pfsubmit-inner">';

												/**
												*Title
												**/
													$setup4_submitpage_titleverror = PFSAIssetControl('setup4_submitpage_titleverror','','Please type a title.');
													$item_title = ($params['post_id'] != '') ? get_the_title($params['post_id']) : '' ;
													$this->FieldOutput .= '
													<section class="pfsubmit-inner-sub">
								                        <label for="item_title" class="lbl-text">'.esc_html__('Título','pointfindert2d').':</label>
								                        <label class="lbl-ui">
								                        	<input type="text" name="item_title" id="item_title" class="input" value="'.$item_title.'"/>';
													if ($setup4_submitpage_titletip!='') {
														$this->FieldOutput .= '<b class="tooltip left-bottom"><em>'.$setup4_submitpage_titletip.'</em></b>';
													} 
								                    $this->FieldOutput .= '</label>                          
								                   </section>  
													';
													$this->PFValidationCheckWrite(1,$setup4_submitpage_titleverror,'item_title');
												/**
												*Title
												**/


												/**
												*Desc
												**/
													$setup4_submitpage_descriptionvcheck = PFSAIssetControl('setup4_submitpage_descriptionvcheck','','0');
													$setup4_submitpage_description_verror = PFSAIssetControl('setup4_submitpage_description_verror','','Please write a description');
													$item_desc = ($params['post_id'] != '') ? get_post_field('post_content',$params['post_id']) : '' ;

													$this->FieldOutput .= '
													<section class="pfsubmit-inner-sub">
								                        <label for="item_desc" class="lbl-text">'.esc_html__('Descripción','pointfindert2d').':</label>
								                        <label class="lbl-ui">';

								                        $this->FieldOutput .= do_action( 'pf_desc_editor_hook',$item_desc);
								                        $this->FieldOutput .= '<textarea id="item_desc" name="item_desc" class="textarea mini">'.$item_desc.'</textarea>';

								                    $this->FieldOutput .= '</label></section>';
													$this->PFValidationCheckWrite($setup4_submitpage_descriptionvcheck,$setup4_submitpage_description_verror,'item_desc');
												/**
												*Desc
												**/

											$this->FieldOutput .= '</section>';
										/**
										* Title & Description Area
										**/


										/**
										*Item Types
										**/
											if($setup3_pointposttype_pt4_check == 1 && $setup4_submitpage_itemtypes_check == 1){
												
												$setup4_submitpage_itemtypes_title = PFSAIssetControl('setup4_submitpage_itemtypes_title','','Item Type');
												$setup4_submitpage_itemtypes_group = PFSAIssetControl('setup4_submitpage_itemtypes_group','','0');
												$setup4_submitpage_itemtypes_group_ex = PFSAIssetControl('setup4_submitpage_itemtypes_group_ex','','1');
												

												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-itype">'.$setup4_submitpage_itemtypes_title.'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-sub-itype">';

												$itemfieldname = ($setup4_submitpage_itemtypes_multiple == 1) ? 'pfupload_itemtypes[]' : 'pfupload_itemtypes' ;

												$this->PFValidationCheckWrite($setup4_submitpage_itemtypes_validation,$setup4_submitpage_itemtypes_verror,$itemfieldname);

												$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderitypes', array("fields" => "ids")) : '' ;

												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												$fields_output_arr = array(
													'listname' => 'pfupload_itemtypes',
											        'listtype' => 'itemtypes',
											        'listtitle' => '',
											        'listsubtype' => 'pointfinderitypes',
											        'listdefault' => $item_defaultvalue,
											        'listgroup' => $setup4_submitpage_itemtypes_group,
											        'listgroup_ex' => $setup4_submitpage_itemtypes_group_ex,
											        'listmultiple' => $setup4_submitpage_itemtypes_multiple
												);
												$this->FieldOutput .= $this->PFGetList($fields_output_arr);
												$this->FieldOutput .= '</section>';

												$this->ScriptOutput .= '
												if ($.pf_tablet_check()) {
													$("#pfupload_itemtypes").select2({
													placeholder: "'.esc_html__("Por favor, seleccione","pointfindert2d").'", 
													formatNoMatches:"'.esc_html__("Nada Encontrado.","pointfindert2d").'",
													allowClear: true, 
													minimumResultsForSearch: '.esc_js($setup4_submitpage_minres).'
												});
												}';
												
										
												$this->FieldOutput .= '</section>';
											}
										/**
										*Item Types
										**/



										/**
										*Conditions
										**/	
											if($setup3_pt14_check == 1 && $setup4_submitpage_conditions_check == 1){
												
												$setup4_submitpage_conditions_title = PFSAIssetControl('setup4_submitpage_conditions_title','','Conditions');												

												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-conditions">'.$setup4_submitpage_conditions_title.'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-sub-conditions">';

												$this->PFValidationCheckWrite($setup4_submitpage_conditions_validation,$setup4_submitpage_conditions_verror,'pfupload_conditions');

												$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderconditions', array("fields" => "ids")) : '' ;

												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												$fields_output_arr = array(
													'listname' => 'pfupload_conditions',
											        'listtype' => 'conditions',
											        'listtitle' => '',
											        'listsubtype' => 'pointfinderconditions',
											        'listdefault' => $item_defaultvalue
												);
												$this->FieldOutput .= $this->PFGetList($fields_output_arr);
												$this->FieldOutput .= '</section>';

												$this->ScriptOutput .= '
												if ($.pf_tablet_check()) {
													$("#pfupload_conditions").select2({
													placeholder: "'.esc_html__("Por favor, seleccione","pointfindert2d").'", 
													formatNoMatches:"'.esc_html__("Nada Encontrado.","pointfindert2d").'",
													allowClear: true, 
													minimumResultsForSearch: '.esc_js($setup4_submitpage_minres).'
												});
												}';
												
										
												$this->FieldOutput .= '</section>';
											}
										/**
										*Conditions
										**/




										/**
										*Features
										**/
											if($setup3_pointposttype_pt6_check == 1 && $setup4_submitpage_featurestypes_check == 1){
												$setup4_submitpage_featurestypes_title = PFSAIssetControl('setup4_submitpage_featurestypes_title','','Features');

												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-features-title">'.$setup4_submitpage_featurestypes_title.'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-features"></section>';
											}
										/**
										*Features
										**/


										/** 
										*Start : Custom Fields
										**/
											$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-customfields-title">'.esc_html__('INFORMACIÓN ADICIONAL','pointfindert2d').'</div>';
											$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-customfields"></section>';
										/** 
										*End : Custom Fields
										**/


										/**
										*Custom Tabs
										**/	

											$this->FieldOutput .= '<div class="customtab-output-container"></div>';
											$this->ScriptOutput .= "
											$.pf_customtab_generator = function(ltid,postid){
												/*
												* ltid = Listing Type ID
												* lang = WPML language val.
												*/

												$.ajax({
													url: theme_scriptspf.ajaxurl,
													type: 'POST',
													dataType: 'html',
													data: {
														action: 'pfget_customtabsystem',
														ltid: ltid,
														postid: postid,
														lang: '".$lang_custom."',
														security: '".wp_create_nonce('pfget_customtabsystem')."'
													},
												}).success(function(obj) {
													
													$('.customtab-output-container').html(obj);

												}).complete(function(){
													
												});
											}
											";
										/**
										*Custom Tabs
										**/


										/**
										*Post Tags
										**/
											$stp4_psttags = PFSAIssetControl('stp4_psttags','','1');
											if ($stp4_psttags == 1) {
												$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('Etiquetas','pointfindert2d').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner">';
												$this->FieldOutput .= '
												<section class="pfsubmit-inner-sub">
							                     
							                        <label class="lbl-ui">
							                        	<input type="text" name="posttags" id="posttags" class="input" placeholder="'.esc_html__('Por favor, añadir etiquetas separadas con comas como: palabra, palabra2, palabra3','pointfindert2d').'" value=""/>
													</label>
												
							                    ';

							                    $post_tags = wp_get_post_tags( $params['post_id']);
												if (isset($post_tags) && $params['formtype'] == 'edititem') {
													$this->FieldOutput .= '<div class="pf-posttag-container">';
							                    	foreach ($post_tags as $value) {
							                    		$this->FieldOutput .= '<div class="pf-item-posttag">'.$value->name.'';
							                    		$this->FieldOutput .= '<a data-pid="'.$value->term_taxonomy_id.'" id="pf-delete-tag-'.$value->term_taxonomy_id.'" title="'.esc_html__('Borrar','pointfindert2d').'"><i class="pfadmicon-glyph-644"></i></a></div>';
							                    	}
							                    	$this->FieldOutput .= '</div>';
												}
												$this->FieldOutput .= '</section></section>';

												$this->ScriptOutput .= "
												$('.pf-item-posttag a').live('click touchstart',function(){

													var selectedtag = $(this);
													var selectedtagicon = $(this).children('i');
		
													$.ajax({
												    	beforeSend:function(){
												    		selectedtagicon.switchClass('pfadmicon-glyph-644','pfadmicon-glyph-647');
												    	},
														url: theme_scriptspf.ajaxurl,
														type: 'POST',
														dataType: 'html',
														data: {
															action: 'pfget_posttag',
															id: $(this).data('pid'),
															pid: '".$params['post_id']."',
															lang: '".$lang_custom."',
															security: '".wp_create_nonce('pfget_posttag')."'
														}
													}).success(function(obj) {
														console.log(obj);

														if (obj == 1) {
															selectedtag.closest('.pf-item-posttag').remove();
														}
														
													}).complete(function(){

														selectedtagicon.switchClass('pfadmicon-glyph-647','pfadmicon-glyph-644');

													});
												});
												";
											}
										/**
										*Post Tags
										**/



										/**
										*Opening Hours
										**/
											$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
											$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
											$setup3_modulessetup_openinghours_ex2 = PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');

											if($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 1){

												$this->FieldOutput .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Horario de Apertura','pointfindert2d').' <small>('.esc_html__('Dejar en blanco para mostrar cerrada','pointfindert2d' ).')</small></div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pf-openinghours-div">';

												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												$this->FieldOutput .= '
								                <label class="lbl-ui">
								                <input type="text" name="o1" class="input" placeholder="Monday-Friday: 09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o1',true)).'" />
									            </label>
									            </section>
									            ';
									            $this->FieldOutput .= '</section>';
												
											}elseif($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 0){
												
												
												if ($setup3_modulessetup_openinghours_ex2 == 1) {
													$text_ohours1 = esc_html__('Lunes','pointfindert2d');
													$text_ohours2 = esc_html__('Domingo','pointfindert2d');
												}else{
													$text_ohours1 = esc_html__('Domingo','pointfindert2d');
													$text_ohours2 = esc_html__('Lunes','pointfindert2d');
												}

												$ohours_first = '<section>
													<label for="o1" class="lbl-text">'.esc_html__('Lunes','pointfindert2d').':</label>
										            <label class="lbl-ui">
										            <input type="text" name="o1" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o1',true)).'" />
										            </label>
										            </section>';

										        $ohours_last = '<section>
										            <label for="o7" class="lbl-text">'.esc_html__('Domingo','pointfindert2d').':</label>
										            <label class="lbl-ui">
										            <input type="text" name="o7" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o7',true)).'"/>
										            </label>
										            </section>';

												if ($setup3_modulessetup_openinghours_ex2 != 1) {
													$ohours_first = $ohours_last . $ohours_first;
													$ohours_last = '';
												}

												$this->FieldOutput .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Horario de Apertura','pointfindert2d').' <small>('.esc_html__('Dejar en blanco para mostrar cerrada','pointfindert2d' ).')</small></div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pf-openinghours-div">';
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												
												$this->FieldOutput .= $ohours_first;
												$this->FieldOutput .= '
										            <section>
										            <label for="o2" class="lbl-text">'.esc_html__('Martes','pointfindert2d').':</label>
									                <label class="lbl-ui">
									                <input type="text" name="o2" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o2',true)).'"/>
										            </label>
										            </section>
										            <section>
										            <label for="o3" class="lbl-text">'.esc_html__('Miércoles','pointfindert2d').':</label>
									                <label class="lbl-ui">
									                <input type="text" name="o3" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o3',true)).'"/>
										            </label>
										            </section>
										            <section>
										            <label for="o4" class="lbl-text">'.esc_html__('Jueves','pointfindert2d').':</label>
									                <label class="lbl-ui">
									                <input type="text" name="o4" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o4',true)).'"/>
										            </label>
										            </section>
										            <section>
										            <label for="o5" class="lbl-text">'.esc_html__('Viernes','pointfindert2d').':</label>
									                <label class="lbl-ui">
									                <input type="text" name="o5" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o5',true)).'"/>
										            </label>
										            </section>
										            <section>
										            <label for="o6" class="lbl-text">'.esc_html__('Sábado','pointfindert2d').':</label>
									                <label class="lbl-ui">
									                <input type="text" name="o6" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o6',true)).'"/>
										            </label>
										            </section>
									            ';
									            $this->FieldOutput .= $ohours_last;
									            $this->FieldOutput .= '</section>';
									            $this->FieldOutput .= '</section>';
												
											}elseif($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 2){
												
												wp_enqueue_script('jquery-ui-core');
												wp_enqueue_script('jquery-ui-datepicker');
												wp_enqueue_script('jquery-ui-slider');
												wp_register_script('theme-timepicker', get_template_directory_uri() . '/js/jquery-ui-timepicker-addon.js', array('jquery','jquery-ui-datepicker'), '4.0',true); 
												wp_enqueue_script('theme-timepicker');
				   								wp_enqueue_style('jquery-ui-smoothnesspf', get_template_directory_uri() . "/css/jquery-ui.theme.min.css", false, null);


												$this->FieldOutput .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Horario de Apertura','pointfindert2d').' <small>('.esc_html__('Dejar en blanco para mostrar cerrada','pointfindert2d' ).')</small></div>';

												$this->FieldOutput .= '<section class="pfsubmit-inner pf-openinghours-div">';									
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												
												$general_rtlsupport = PFSAIssetControl('general_rtlsupport','','0');
												if ($general_rtlsupport == 1) {
													$rtltext_oh = 'true';
												}else{
													$rtltext_oh = 'false';
												}

												for ($i=0; $i < 7; $i++) { 
													$o_value[$i] = get_post_meta($params['post_id'],'webbupointfinder_items_o_o'.($i+1),true);
													if (!empty($o_value[$i])) {
														$o_value[$i] = explode("-", $o_value[$i]);
														if (count($o_value[$i]) < 1) {
															$o_value[$i] = array("","");
														}elseif (count($o_value[$i]) < 2) {
															$o_value[$i][1] = "";
														}
													}else{
														$o_value[$i] = array("","");
													}

													
													$this->ScriptOutput .= "
													$.timepicker.timeRange(
														$('input[name=\"o".($i+1)."_1\"]'),
														$('input[name=\"o".($i+1)."_2\"]'),
														{
															minInterval: (1000*60*60),
															timeFormat: 'HH:mm',
															start: {},
															end: {},
															timeOnlyTitle: '".esc_html__('Elegir Hora','pointfindert2d')."',
															timeText: '".esc_html__('Tiempo','pointfindert2d')."',
															hourText: '".esc_html__('Hora','pointfindert2d')."',
															currentText: '".esc_html__('Ahora','pointfindert2d')."',
															isRTL: ".$rtltext_oh."
														}
													);
													";
												}
												

												$ohours_first = '
													<section>
													<label for="o1" class="lbl-text">'.esc_html__('Lunes','pointfindert2d').':</label>
									   				<div class="row">
									   					<div class="col6 first">
											                <label class="lbl-ui">
											                <input type="text" name="o1_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[0][0].'" />
												            </label>
									   					</div>
									   					<div class="col6 last">
											                <label class="lbl-ui">
											                <input type="text" name="o1_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[0][1].'" />
												            </label>
									   					</div>
									   				</div>
										            </section>
												';
												$ohours_last = '
													<section>
										            <label for="o7" class="lbl-text">'.esc_html__('Domingo','pointfindert2d').':</label>
										            <div class="row">
									                	<div class="col6 first">
											                <label class="lbl-ui">
											                <input type="text" name="o7_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[6][0].'" />
												            </label>
									   					</div>
									   					<div class="col6 last">
											                <label class="lbl-ui">
											                <input type="text" name="o7_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[6][1].'" />
												            </label>
									   					</div>
									   				</div>
										            </section>

												';

												if ($setup3_modulessetup_openinghours_ex2 != 1) {
													$ohours_first = $ohours_last . $ohours_first;
													$ohours_last = '';
												}

												
												$this->FieldOutput .= $ohours_first;
												$this->FieldOutput .= '
										            <section>
										            <label for="o2" class="lbl-text">'.esc_html__('Martes','pointfindert2d').':</label>
										            <div class="row">
									                	<div class="col6 first">
											                <label class="lbl-ui">
											                <input type="text" name="o2_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[1][0].'" />
												            </label>
									   					</div>
									   					<div class="col6 last">
											                <label class="lbl-ui">
											                <input type="text" name="o2_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[1][1].'" />
												            </label>
									   					</div>
									   				</div>
										            </section>
										            <section>
										            <label for="o3" class="lbl-text">'.esc_html__('Miércoles','pointfindert2d').':</label>
										            <div class="row">
									                	<div class="col6 first">
											                <label class="lbl-ui">
											                <input type="text" name="o3_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[2][0].'" />
												            </label>
									   					</div>
									   					<div class="col6 last">
											                <label class="lbl-ui">
											                <input type="text" name="o3_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[2][1].'" />
												            </label>
									   					</div>
									   				</div>
										            </section>
										            <section>
										            <label for="o4" class="lbl-text">'.esc_html__('Jueves','pointfindert2d').':</label>
										            <div class="row">
									                	<div class="col6 first">
											                <label class="lbl-ui">
											                <input type="text" name="o4_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[3][0].'" />
												            </label>
									   					</div>
									   					<div class="col6 last">
											                <label class="lbl-ui">
											                <input type="text" name="o4_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[3][1].'" />
												            </label>
									   					</div>
									   				</div>
										            </section>
										            <section>
										            <label for="o5" class="lbl-text">'.esc_html__('Viernes','pointfindert2d').':</label>
										            <div class="row">
									                	<div class="col6 first">
											                <label class="lbl-ui">
											                <input type="text" name="o5_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[4][0].'" />
												            </label>
									   					</div>
									   					<div class="col6 last">
											                <label class="lbl-ui">
											                <input type="text" name="o5_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[4][1].'" />
												            </label>
									   					</div>
									   				</div>
										            </section>
										            <section>
										            <label for="o6" class="lbl-text">'.esc_html__('Sábado','pointfindert2d').':</label>
										            <div class="row">
									                	<div class="col6 first">
											                <label class="lbl-ui">
											                <input type="text" name="o6_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[5][0].'" />
												            </label>
									   					</div>
									   					<div class="col6 last">
											                <label class="lbl-ui">
											                <input type="text" name="o6_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[5][1].'" />
												            </label>
									   					</div>
									   				</div>
										            </section>
									            ';
									            $this->FieldOutput .= $ohours_last;
									            $this->FieldOutput .= '</section>';
									            $this->FieldOutput .= '</section>';
												
											}
											if ($setup3_modulessetup_openinghours == 1) {
												/* Opening Hours show/hide by Listing Category Options*/
												$taxonomies = array( 
									                'pointfinderltypes'
									            );

									            $args = array(
									                'orderby'           => 'name', 
									                'order'             => 'ASC',
									                'hide_empty'        => false, 
									                'parent'            => 0,
									            ); 
												$pf_get_term_details = get_terms($taxonomies,$args); 
									            $pfstart = (!empty($pf_get_term_details))? true:false;

									            $ohours_term_arr = "[";

												if($pfstart){
													foreach ($pf_get_term_details as &$pf_get_term_detail) {

														if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_advanced_status','','0') == 1) {
															
															if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_ohoursmodule','',$setup3_modulessetup_openinghours) == 0) {

																$ohours_term_arr .= '"'.$pf_get_term_detail->term_id.'"';
																$ohours_term_arr .= ",";
																$args2 = array(
														            'orderby'           => 'name', 
														            'order'             => 'ASC',
														            'hide_empty'        => false, 
														            'parent'            => $pf_get_term_detail->term_id,
														        ); 
																$pf_get_term_details2 = get_terms($taxonomies,$args2); 
														        $pfstart = (!empty($pf_get_term_details2))? true:false;
														        if($pfstart){
														        	foreach ($pf_get_term_details2 as $pf_get_term_detail2) {
														        		$ohours_term_arr .= '"'.$pf_get_term_detail2->term_id.'"';
																		$ohours_term_arr .= ",";
														        	}
														        }
															}

														}
													}
												}
												$ohours_term_arr .= "]";

												$this->ScriptOutput .= "
												var openingharr = ".$ohours_term_arr.";
											

												$(function(){
													if ($( '#pfupload_listingtypes' ).val() != '') {

														if ($.inArray( $('#pfupload_listingtypes').val(), openingharr ) != -1) {
															$('.pf-openinghours-div').hide();
														}else{
															$('.pf-openinghours-div').show();
														}

													}else{
														$('.pf-openinghours-div').hide();
													}
													
												});

												$( '#pfupload_listingtypes' ).change(function(){
															
													if ($.inArray( $('#pfupload_listingtypes').val(), openingharr ) != -1) {
														$('.pf-openinghours-div').hide();
													}else{
														$('.pf-openinghours-div').show();
													}
												});
						
												";
											}
										/**
										*Opening Hours
										**/



										/**
										*Featured Video 
										**/
											$setup4_submitpage_video = PFSAIssetControl('setup4_submitpage_video','','1');
											
											if ($setup4_submitpage_video == 1) {
												
												$pfuploadfeaturedvideo = ($params['post_id'] != '') ? get_post_meta($params['post_id'], 'webbupointfinder_item_video', true) : '' ;

												$this->FieldOutput .= '<div class="pfsubmit-title pf-videomodule-div">'.esc_html__('VIDEO','pointfindert2d').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pf-videomodule-div">';
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												$this->FieldOutput .= '<small style="margin-bottom:4px">'.esc_html__('Por favor, copiar y pegar la URL del vídeo para compartir. ','pointfindert2d').'</small>';
													$this->FieldOutput .= '
						                            <label for="file" class="lbl-ui" >
						                            <input class="input" name="pfuploadfeaturedvideo" placeholder="" value="'.$pfuploadfeaturedvideo.'">
						                            </label> 
													';
												$this->FieldOutput .= '</section>';
												$this->FieldOutput .= '</section>';

												/* Opening Hours show/hide by Listing Category Options*/
												$taxonomies = array( 
									                'pointfinderltypes'
									            );

									            $args = array(
									                'orderby'           => 'name', 
									                'order'             => 'ASC',
									                'hide_empty'        => false, 
									                'parent'            => 0,
									            ); 
												$pf_get_term_details = get_terms($taxonomies,$args); 
									            $pfstart = (!empty($pf_get_term_details))? true:false;

									            $video_term_arr = "[";

												if($pfstart){
													foreach ($pf_get_term_details as &$pf_get_term_detail) {


														if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_advanced_status','','0') == 1) {
															
															if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_videomodule','','1') == 0) {
																$video_term_arr .= '"'.$pf_get_term_detail->term_id.'"';
																$video_term_arr .= ",";
																$args2 = array(
														            'orderby'           => 'name', 
														            'order'             => 'ASC',
														            'hide_empty'        => false, 
														            'parent'            => $pf_get_term_detail->term_id,
														        ); 
																$pf_get_term_details2 = get_terms($taxonomies,$args2); 
														        $pfstart = (!empty($pf_get_term_details2))? true:false;
														        if($pfstart){
														        	foreach ($pf_get_term_details2 as $pf_get_term_detail2) {
														        		$video_term_arr .= '"'.$pf_get_term_detail2->term_id.'"';
																		$video_term_arr .= ",";
														        	}
														        }
															}
														}
													}
												}
												$video_term_arr .= "]";

												$this->ScriptOutput .= "
												var videomoarr = ".$video_term_arr.";

												$(function(){
													if ($( '#pfupload_listingtypes' ).val() != '') {
														if ($.inArray( $('#pfupload_listingtypes').val(), videomoarr ) != -1) {
															$('.pf-videomodule-div').hide();
														}else{
															$('.pf-videomodule-div').show();
														}

													}else{
														$('.pf-videomodule-div').hide();
													}
													
												});

												$( '#pfupload_listingtypes' ).change(function(){
													if ($.inArray( $('#pfupload_listingtypes').val(), videomoarr ) != -1) {
														$('.pf-videomodule-div').hide();
													}else{
														$('.pf-videomodule-div').show();
													}

												});
						
												";
												

											}
										/** 
										*Featured Video 
										**/


										
										/**
										*Locations
										**/
											if($setup3_pointposttype_pt5_check == 1 && $setup4_submitpage_locationtypes_check == 1){
													
													$stp4_loc_new = PFSAIssetControl('stp4_loc_new','','0');
													$setup4_submitpage_locationtypes_title = PFSAIssetControl('setup4_submitpage_locationtypes_title','','Location');

													$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-location">'.$setup4_submitpage_locationtypes_title.'</div>';
													$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-sub-location pfsubmit-location-errc">';

													if ($stp4_loc_new == 1) {
														
														$stp4_sublotyp_title = PFSAIssetControl('stp4_sublotyp_title','',esc_html__('Sub Localización', 'pointfindert2d'));
														$stp4_subsublotyp_title = PFSAIssetControl('stp4_subsublotyp_title','',esc_html__('Sub Sub Localización', 'pointfindert2d'));
														
														$itemfieldname = 'pfupload_locations' ;

														$this->PFValidationCheckWrite($setup4_submitpage_locationtypes_validation,$setup4_submitpage_locationtypes_verror,$itemfieldname);


														$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderlocations', array("fields" => "ids")) : '' ;
														$item_defaultvalue_output = $sub_level = $sub_sub_level = $item_defaultvalue_output_orj = '';


	
														if (isset($item_defaultvalue[0])) {
															$item_defaultvalue_output_orj = $item_defaultvalue[0];
															$find_top_parent = pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderlocations');

															switch ($find_top_parent['level']) {
																case '1':
																	$sub_level = $item_defaultvalue[0];
																	break;
																
																case '2':
																	$sub_sub_level = $item_defaultvalue[0];
																	$sub_level = pf_get_term_top_parent($item_defaultvalue[0],'pointfinderlocations');
																	break;
															}
															

															$item_defaultvalue_output = $find_top_parent['parent'];
														}

														$this->FieldOutput .= '<input type="hidden" name="pfupload_locations" id="pfupload_locations" value="'.$item_defaultvalue_output.'"/>';

														$this->FieldOutput .= '<section class="pfsubmit-inner-sub pfsubmit-inner-sub-locloader">';
														$fields_output_arr = array(
															'listname' => 'pflocationselector',
													        'listtype' => 'locations',
													        'listtitle' => $setup4_submitpage_locationtypes_title,
													        'listsubtype' => 'pointfinderlocations',
													        'listdefault' => $item_defaultvalue_output,
													        'listgroup' => 0,
													        'listgroup_ex' => 0,
													        'listmultiple' => 0,
													        'parentonly' => 1
														);
														$this->FieldOutput .= $this->PFGetList($fields_output_arr);
														$this->FieldOutput .= '<div class="pf-sub-locations-container"></div>';

														/* Custom location */
														$stp4_loc_add = PFSAIssetControl('stp4_loc_add','','0');
														if ($stp4_loc_add == 1) {
															
															$this->FieldOutput .= '<section class="pfsubmit-inner-sub-customcity">';
															$this->FieldOutput .= ' <label for="item_title" class="lbl-text">'.esc_html__('Ciudad personalizada','pointfindert2d').': '.esc_html__('(Opcional)','pointfindert2d').'</label>';
																$this->FieldOutput .= '
									                            <label for="file" class="lbl-ui" >
									                            <input class="input" name="customlocation" placeholder="'.esc_html__("Si no ha podido encontrar su ciudad. Por favor, escriba la ciudad personalizada aquí.",'pointfindert2d').'" value="">
									                            </label> 
																';
															$this->FieldOutput .= '</section>';
														}


														$this->FieldOutput .= '</section>';
														$this->ScriptOutput .= '
														if ($.pf_tablet_check()) {
														$("#pflocationselector").select2({
															placeholder: "'.esc_html__("Por favor, seleccione","pointfindert2d").'", 
															formatNoMatches:"'.esc_html__("Nada Encontrado.","pointfindert2d").'",
															allowClear: true, 
															minimumResultsForSearch: '.esc_js($setup4_submitpage_minres).'
														});
														}
														';
											
														

														/* Script Side */

														$this->ScriptOutput .= "
															/* Start: Function for sub location types */
																$.pf_get_sublocations = function(itemid,defaultv){
																	$.ajax({
																    	beforeSend:function(){
																    		$('.pfsubmit-inner-sub-locloader').pfLoadingOverlay({action:'show',message: '".esc_html__('Cargando ubicaciones...','pointfindert2d')."'});
																    	},
																		url: theme_scriptspf.ajaxurl,
																		type: 'POST',
																		dataType: 'html',
																		data: {
																			action: 'pfget_listingtype',
																			id: itemid,
																			default: defaultv,
																			sname: 'pfupload_sublocations',
																			stext: '".$stp4_sublotyp_title."',
																			stype: 'locations',
																			stax: 'pointfinderlocations',
																			lang: '".$lang_custom."',
																			security: '".wp_create_nonce('pfget_listingtype')."'
																		},
																	}).success(function(obj) {
																		$('.pf-sub-locations-container').append('<div class=\'pfsublocations\'>'+obj+'</div>');
																		if ($.pf_tablet_check()) {
																			$('#pfupload_sublocations').select2({
																				placeholder: '".esc_html__('Por favor, seleccione','pointfindert2d')."', 
																				formatNoMatches:'".esc_html__('Nada Encontrado','pointfindert2d')."',
																				allowClear: true, 
																				minimumResultsForSearch: 10
																			});
																		}
																			

																			$('#pfupload_sublocations').change(function(){
																				if($(this).val() != 0){
																					$('#pfupload_locations').val($(this).val()).trigger('change');
																					$.pf_get_subsublocations($(this).val(),'');
																					$('.pfsubmit-inner-sub-customcity').show();
																				}else{
																					$('#pfupload_locations').val(itemid);
																					$('.pfsubmit-inner-sub-customcity').hide();
																				}
																				$('.pfsubsublocations').remove();
																			});

																	}).complete(function(){
																		if (defaultv != '') {
																			$('#pfupload_locations').val(defaultv).trigger('change');
																			$.pf_get_subsublocations($('#pfupload_sublocations').val(),'');
																			$('.pfsubmit-inner-sub-customcity').show();
																		}else{
																			$('#pfupload_locations').val(itemid).trigger('change');
																		}
																		setTimeout(function(){
																			$('.pfsubmit-inner-sub-locloader').pfLoadingOverlay({action:'hide'});
																		},1000);
																		";
																						
																		if (!empty($sub_sub_level)) {
																			$this->ScriptOutput .= "
																			if (".$sub_level." == $('#pfupload_sublocations').val()) {
																				$.pf_get_subsublocations('".$sub_level."','".$sub_sub_level."');
																			}
																			";
																		}
																		$this->ScriptOutput .= "
																		
																	});
																}


																$.pf_get_subsublocations = function(itemid,defaultv){
																	$.ajax({
																    	beforeSend:function(){
																    		$('.pfsubmit-inner-sub-locloader').pfLoadingOverlay({action:'show',message: '".esc_html__('Cargando ubicaciones...','pointfindert2d')."'});
																    	},
																		url: theme_scriptspf.ajaxurl,
																		type: 'POST',
																		dataType: 'html',
																		data: {
																			action: 'pfget_listingtype',
																			id: itemid,
																			default: defaultv,
																			sname: 'pfupload_subsublocations',
																			stext: '".$stp4_subsublotyp_title."',
																			stype: 'locations',
																			stax: 'pointfinderlocations',
																			lang: '".$lang_custom."',
																			security: '".wp_create_nonce('pfget_listingtype')."'
																		},
																	}).success(function(obj) {
																		$('.pf-sub-locations-container').append('<div class=\'pfsubsublocations\'>'+obj+'</div>');
																		if ($.pf_tablet_check()) {
																			$('#pfupload_subsublocations').select2({
																				placeholder: '".esc_html__('Por favor, seleccione','pointfindert2d')."', 
																				formatNoMatches:'".esc_html__('Nada Encontrado','pointfindert2d')."',
																				allowClear: true, 
																				minimumResultsForSearch: 10
																			});
																		}
																			


																			$('#pfupload_subsublocations').change(function(){
																				if($(this).val() != 0){
																					$('#pfupload_locations').val($(this).val()).trigger('change');
																				}else{
																					$('#pfupload_locations').val($('#pfupload_sublocations').val())
																				}
																			});

																	}).complete(function(){
																		if (defaultv != '') {
																			$('#pfupload_locations').val(defaultv).trigger('change');
																		}else{
																			$('#pfupload_locations').val(itemid).trigger('change');
																		}
																		setTimeout(function(){
																			$('.pfsubmit-inner-sub-locloader').pfLoadingOverlay({action:'hide'});
																		},1000);
																	});
																}

															/* End: Function for sub location types */
															";


														$this->ScriptOutput .= "$(function(){";

															
															/* Edit works */
															if ($params['post_id'] != '') {
																$this->ScriptOutput .= "$.pf_get_sublocations($('#pfupload_locations').val(),'".$sub_level."');";
																if (empty($sub_sub_level) && !empty($sub_level)) {
																	$this->ScriptOutput .= "$('#pfupload_locations').val('".$sub_level."');";
																}
															}

														$this->ScriptOutput .= "});";
														
														$this->ScriptOutput .= "
														$('#pflocationselector').change(function(){
															$('.pf-sub-locations-container').html('');
															$('#pfupload_locations').val($(this).val()).trigger('change');
															$.pf_get_sublocations($(this).val(),'');
														});
														";

													}else{
														
														$setup4_submitpage_locationtypes_multiple = PFSAIssetControl('setup4_submitpage_locationtypes_multiple','','0');
														$setup4_submitpage_locationtypes_group = PFSAIssetControl('setup4_submitpage_locationtypes_group','','0');
														$setup4_submitpage_locationtypes_group_ex = PFSAIssetControl('setup4_submitpage_locationtypes_group_ex','','1');

														$itemfieldname = ($setup4_submitpage_locationtypes_multiple == 1) ? 'pfupload_locations[]' : 'pfupload_locations' ;

														$this->PFValidationCheckWrite($setup4_submitpage_locationtypes_validation,$setup4_submitpage_locationtypes_verror,$itemfieldname);

														$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderlocations', array("fields" => "ids")) : '' ;

														$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
														$fields_output_arr = array(
															'listname' => 'pfupload_locations',
													        'listtype' => 'locations',
													        'listtitle' => $setup4_submitpage_locationtypes_title,
													        'listsubtype' => 'pointfinderlocations',
													        'listdefault' => $item_defaultvalue,
													        'listgroup' => $setup4_submitpage_locationtypes_group,
													        'listgroup_ex' => $setup4_submitpage_locationtypes_group_ex,
													        'listmultiple' => $setup4_submitpage_locationtypes_multiple
														);
														$this->FieldOutput .= $this->PFGetList($fields_output_arr);
														$this->FieldOutput .= '</section>';
														$this->ScriptOutput .= '
														if ($.pf_tablet_check()) {
														$("#pfupload_locations").select2({
															placeholder: "'.esc_html__("Por favor, seleccione","pointfindert2d").'", 
															formatNoMatches:"'.esc_html__("Nada Encontrado.","pointfindert2d").'",
															allowClear: true, 
															minimumResultsForSearch: '.esc_js($setup4_submitpage_minres).'
														});
														}
														';
														
													}
												
													$this->FieldOutput .= '</section>';
											}
										/**
										*Locations 
										**/


										/** 
										*Map  & Locations
										**/	
											
											if($st4_sp_med == 1){
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-address">'.esc_html__('DIRECCIÓN','pointfindert2d').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-sub-address pfsubmit-address-errc">';

											
												$setup4_submitpage_maparea_title = PFSAIssetControl('setup4_submitpage_maparea_title','','');
												$setup4_submitpage_maparea_tooltip = PFSAIssetControl('setup4_submitpage_maparea_tooltip','','');
												

												$this->PFValidationCheckWrite($st4_sp_med2,$setup4_submitpage_maparea_verror,'pfupload_lat');
												$this->PFValidationCheckWrite($st4_sp_med2,$setup4_submitpage_maparea_verror,'pfupload_lng');
												$this->PFValidationCheckWrite($st4_sp_med2,esc_html__('Por favor ingrese una direccion','pointfindert2d'),'pfupload_address');


												$setup5_mapsettings_zoom = PFSAIssetControl('setup5_mapsettings_zoom','','6');
												$setup5_mapsettings_type = PFSAIssetControl('setup5_mapsettings_type','','ROADMAP');
												$setup5_mapsettings_lat = PFSAIssetControl('setup5_mapsettings_lat','','');
												$setup5_mapsettings_lng = PFSAIssetControl('setup5_mapsettings_lng','','');

												$setup5_mapsettings_lat_text = $setup5_mapsettings_lng_text = '';

												if($params['post_id'] != ''){
													$coordinates = esc_attr(get_post_meta( $params['post_id'], 'webbupointfinder_items_location', true ));
													if(isset($coordinates)){
														$coordinates = explode(',', $coordinates);
														
														if (isset($coordinates[1])) {
															$setup5_mapsettings_lat = $setup5_mapsettings_lat_text = $coordinates[0];
															$setup5_mapsettings_lng = $setup5_mapsettings_lng_text = $coordinates[1];
														}else{
															$setup5_mapsettings_lat = $setup5_mapsettings_lat_text = '';
															$setup5_mapsettings_lng = $setup5_mapsettings_lng_text = '';
														}
														
													}
												}

												$description = ($setup4_submitpage_maparea_tooltip!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$setup4_submitpage_maparea_tooltip.'</span></a>' : '' ;

												$pfupload_address = ($params['post_id'] != '') ? esc_html(get_post_meta($params['post_id'], 'webbupointfinder_items_address', true)) : '' ;

												
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
													$this->FieldOutput .= '<label for="pfupload_address" class="lbl-text">'.$setup4_submitpage_maparea_title.':'.$description.'</label>';
													$this->FieldOutput .= '<label class="lbl-ui pflabelfixsearch search">';
													$this->FieldOutput .= '<input id="pfupload_address" value="'.$pfupload_address.'" name="pfupload_address" class="controls input" type="text" placeholder="'.esc_html__('Por favor, escriba una dirección...','pointfindert2d').'">';
													$this->FieldOutput .= '<a class="button" id="pf_search_geolocateme" title="'.esc_html__('Localizarme!','pointfindert2d').'">
													<img src="'.get_template_directory_uri().'/images/geoicon.svg" width="16px" height="16px" class="pf-search-locatemebut" alt="'.esc_html__('Localizarme!','pointfindert2d').'">
													<div class="pf-search-locatemebutloading"></div>
													</a>';
													$this->FieldOutput .= '</label>';
													$this->FieldOutput .= '<div id="pfupload_map" style="width: 100%;height: 300px;border:0" data-pf-zoom="'.$setup5_mapsettings_zoom.'" data-pf-type="'.$setup5_mapsettings_type.'" data-pf-lat="'.$setup5_mapsettings_lat.'" data-pf-lng="'.$setup5_mapsettings_lng.'"></div>';


												$this->FieldOutput .= '</section>';


												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';

													$this->FieldOutput .= '<div class="row">';


													$this->FieldOutput .= '<div class="col6 first"><div id="pfupload_lat">';
														 $this->FieldOutput .= '<label for="pfupload_lat" class="lbl-text">'.esc_html__('Coordenada Latitud','pointfindert2d').':</label>
						                                <label class="lbl-ui">
						                                	<input type="text" name="pfupload_lat" id="pfupload_lat_coordinate" class="input" value="'.$setup5_mapsettings_lat_text.'" />
						                                </label>';
													$this->FieldOutput .= '</div></div>';/*inner*//*col6 first*/



													$this->FieldOutput .= '<div class="col6 last colspacer-two"><div id="pfupload_lng">';
														$this->FieldOutput .= '<label for="pfupload_lng" class="lbl-text">'.esc_html__('Coordenada Longitud','pointfindert2d').':</label>
						                                <label class="lbl-ui">
						                                	<input type="text" name="pfupload_lng" id="pfupload_lng_coordinate" class="input" value="'.$setup5_mapsettings_lng_text.'"/>
						                                </label>';
													$this->FieldOutput .= '</div></div>';/*inner*//*col6 last*/


													$this->FieldOutput .= '<div>';/*row*/
												$this->FieldOutput .= '</section>';
												

											$this->FieldOutput .= '</section>';
											}
										/**
										*Map & Locations
										**/


										/**
										*Image Upload
										**/
											if ($setup4_submitpage_imageupload == 1) {
												$setup4_submitpage_status_old = PFSAIssetControl('setup4_submitpage_status_old','','0');
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-image">'.esc_html__('CARGAR IMAGEN','pointfindert2d' ).'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfitemimgcontainer pferrorcontainer pfsubmit-inner-sub-image">';
												
												
												
												if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') !== false || $setup4_submitpage_status_old == 1) {
													/** 
													*Old Image Upload - if this is an ie9 or 8
													**/
														wp_register_script('moxieformforie', get_template_directory_uri() . '/js/moxie.min.js', array('jquery'), '1.4.1',true); 
														wp_enqueue_script('moxieformforie'); 

														$this->FieldOutput .= '<div class="pfuploadedimages"></div>';

														$this->FieldOutput .= '
														<script type="text/javascript">
														(function($) {
														"use strict";
															$(function(){
																';
																if(!empty($params['post_id'])){
																$this->FieldOutput .= '$.pfitemdetail_listimages_old('.$params['post_id'].');';
																}
														$this->FieldOutput .= ' 	
															});
															
														})(jQuery);
														</script>';

														$pfimageuploadimit = $setup4_submitpage_imagelimit + 1;
														$imagesvalue = '';
														if ($params['formtype'] != 'edititem') {
															$images_count = 0;
															$images_newlimit = $pfimageuploadimit;
														}else{

															$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_images');
															$featuredimagenum = get_post_thumbnail_id($params['post_id']);

															$images_count = count($images_of_thispost) + 1;
															$images_newlimit = $pfimageuploadimit - $images_count;
														}

														$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
														
														
														$this->FieldOutput .= '<label for="file" class="lbl-text">'.esc_html__('CARGAR NUEVAS IMÁGENES','pointfindert2d').': ('.esc_html__('MAX','pointfindert2d').': '.$pfimageuploadimit.'/<span class="pfmaxtext">'.$images_newlimit.'</span>) '.esc_html__('(Permitido: JPG,GIF,PNG)','pointfindert2d').':</label><small style="margin-bottom:4px;display:block;">'.esc_html__('Primera imagen será la imagen principal.','pointfindert2d').'</small>';
														$this->FieldOutput .= '<div class="pfuploadfeaturedimg-container"><a id="pfuploadfeaturedimg_remove" style="font-size: 12px;line-height: 14px;"><i class="pfadmicon-glyph-64" style="font-size: 14px;"></i> '.esc_html__('Eliminar las imágenes cargadas','pointfindert2d').'</a></div>';
														$this->FieldOutput .= '<div class="pfuploadfeaturedimgupl-container">';
														$this->FieldOutput .= '
							                            <label for="file" class="lbl-ui file-input">
								                            <div id="pffeaturedimageuploadcontainer">
														        <a id="pffeaturedimageuploadfilepicker" href="javascript:;"><i class="pfadmicon-glyph-512"></i> '.esc_html__('Elija Imágenes','pointfindert2d').'</a>
														    </div>
							                            </label> 
							                            </div>
														';
														
														$this->FieldOutput .= '</section>';

														$this->FieldOutput .= '<input type="hidden" name="pfuploadimagesrc" id="pfuploadimagesrc" value="'.$imagesvalue.'">';
														
														if($setup4_submitpage_featuredverror_status == 1 && $params['formtype'] != 'edititem'){
															
															if($this->VSOMessages != ''){
																$this->VSOMessages .= ',pfuploadimagesrc:"'.$setup4_submitpage_featuredverror.'"';
															}else{
																$this->VSOMessages = 'pfuploadimagesrc:"'.$setup4_submitpage_featuredverror.'"';
															}

															if($this->VSORules != ''){
																$this->VSORules .= ',pfuploadimagesrc:"required"';
															}else{
																$this->VSORules = 'pfuploadimagesrc:"required"';
															}
														}

														$nonceimgup = wp_create_nonce('pfget_imageupload');
														

														$this->ScriptOutput .= "$.pfuploadimagelimit = ".$images_newlimit.";";

														if ($params['formtype'] == 'edititem') {
															$this->ScriptOutput .= "
															if ($.pfuploadimagelimit <= 0) {
																$('.pfuploadfeaturedimg-container').css('display','none');
																$('.pfuploadfeaturedimgupl-container').css('display','none');
															}
															
															if ($.pfuploadimagelimit <= ".$pfimageuploadimit."){
																$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
															}

															if (".$images_newlimit." == 0) {
																$('.pfuploadfeaturedimgupl-container').css('display','none');
															}
															
															";
														}

														$this->ScriptOutput .= "

														/*Image upload featured image AJAX */
															var FeaturedfileInput = new mOxie.FileInput({
													            browse_button: document.getElementById('pffeaturedimageuploadfilepicker'),
													            container: 'pffeaturedimageuploadcontainer',
													            accept: [{title: 'Image files', extensions: 'jpg,gif,png'}],
													            multiple: true
													        });

													        $.pfuploadedfilecount = 0;

													        $.pfuploadoldimages = function(id){
													        	var numberi = id;
													        	numberi = numberi + 1;
												        		$('.pfitemimgcontainer').pfLoadingOverlay({action:'show',message: '".esc_html__('Carga de archivos: ','pointfindert2d')."'+numberi});

																if ($.pfuploadimagelimit > 0 && $.pfuploadedfilecount < $.pfuploadmaximage) {
																
														            var formData = new mOxie.FormData();
														            formData.append('action','pfget_imageupload');
																    formData.append('security','".$nonceimgup."');
																    formData.append('oldup',1);
																    formData.append('pfuploadfeaturedimg', FeaturedfileInput.files[id]);

																    var featured_xhr = new mOxie.XMLHttpRequest();
																    featured_xhr.open('POST', theme_scriptspf.ajaxurl, true);
																    featured_xhr.responseType = 'text';
																    featured_xhr.send(formData);

																    var clearfeaturedinterval = function(){

																    	clearInterval(featureimgint);

																    	$.pfuploadedfilecount = $.pfuploadedfilecount + 1;
																		$.pfuploadimagelimit = $.pfuploadimagelimit - 1;

																    	if ($.pfuploadedfilecount == $.pfuploadmaximage) {
																	    	if ($.pfuploadimagelimit > 0) {
																	    		$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
																	    	}else{
																	    		$('.pfuploadfeaturedimgupl-container').css('display','none');
																	    	}
																			$('.pfuploadfeaturedimg-container').css('display','inline-block');
																			$('.pfitemimgcontainer').pfLoadingOverlay({action:'hide'});
																	    }

																	    if ($.pfuploadimagelimit > 0) {
																    		$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
																    	}else{
																    		$('.pfuploadfeaturedimgupl-container').css('display','none');
																    	}

																	    $('.pfmaxtext').text($.pfuploadimagelimit);
																	    $.pfuploadoldimages($.pfuploadedfilecount);
																    }

																    var featureimgint = setInterval(function(){
																    	if (featured_xhr.readyState == 4) {
																    		var obj = featured_xhr.response;
																    		obj = $.parseJSON(obj)
																    		
																	    		if (obj.process == 'up') {

																					var uploadedimages = $('#pfuploadimagesrc').val();
																					if (uploadedimages.length > 0) {
																						uploadedimages = uploadedimages+','+obj.id;
																						$('#pfuploadimagesrc').val(uploadedimages);
																					}else{
																						$('#pfuploadimagesrc').val(obj.id);
																					}
																				}
																			clearfeaturedinterval();
																    	}
																    }, 1000);																	
																}else{
																	if ($.pfuploadimagelimit > 0) {
															    		$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
															    	}else{
															    		$('.pfuploadfeaturedimgupl-container').css('display','none');
															    	}
																	$('.pfuploadfeaturedimg-container').css('display','inline-block');
																	$('.pfitemimgcontainer').pfLoadingOverlay({action:'hide'});
																	$.pfuploadmaximage = $.pfuploadedfilecount = 0;
																};
													        };

													        FeaturedfileInput.onchange = function(e) {
													        	if (FeaturedfileInput.files && FeaturedfileInput.files.length) {
														       		$.pfuploadmaximage = FeaturedfileInput.files.length;
														       		$.pfuploadoldimages(0);
													       		}
													        };
													        FeaturedfileInput.init();

														/* Remove Featured Image Ajax */
															$('#pfuploadfeaturedimg_remove').live('click touchstart',function(){

																$('.pfitemimgcontainer').pfLoadingOverlay({action:'show',message: '".esc_html__('Eliminación de Archivos(s)...','pointfindert2d')."'});

															    var formData = new mOxie.FormData();
													            formData.append('action','pfget_imageupload');
															    formData.append('security','".$nonceimgup."');
															    formData.append('oldup',1);
															    formData.append('exid', $('#pfuploadimagesrc').val());

															    var remove_xhr = new mOxie.XMLHttpRequest();
															    remove_xhr.open('POST', theme_scriptspf.ajaxurl, true);
															    remove_xhr.responseType = 'text';
															    remove_xhr.send(formData);
															    var clearfeaturedinterval = function(){
															    	clearInterval(removefeaturedimg);
															    }
															    var removefeaturedimg = setInterval(function(){
															    	if (remove_xhr.readyState == 4) {
															    		var obj = remove_xhr.response;
															    		obj = $.parseJSON(obj)
															    		
															    		if (obj.process == 'del') {
																			$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
																			$('.pfuploadfeaturedimg-container').css('display','none');
																			$.pfuploadimagelimit = ".$images_newlimit.";
																			$('.pfmaxtext').text($.pfuploadimagelimit);
																		}
																		$('.pfitemimgcontainer').pfLoadingOverlay({action:'hide'});
																		clearfeaturedinterval();
															    	}
															    	$('#pfuploadimagesrc').val('');
															    	$.pfuploadmaximage = $.pfuploadedfilecount = 0;

															    }, 1000);
															});

														";
													/** 
													*Old Image Upload 
													**/

												}elseif ($setup4_submitpage_status_old == 0) {
												
													/**
													*Dropzone Upload
													**/
														$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
														$images_count = 0;
														if($setup4_submitpage_imageupload == 1){
															
															$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_images');
															$images_count = count($images_of_thispost) + 1;

															$this->FieldOutput .= '<div class="pfuploadedimages"></div>';

															/* Validation for upload */
															if ($params['formtype'] != 'edititem' && $setup4_submitpage_featuredverror_status == 1) {
															if($this->VSOMessages != ''){
																$this->VSOMessages .= ',pfuploadimagesrc:"'.$setup4_submitpage_featuredverror.'"';
															}else{
																$this->VSOMessages = 'pfuploadimagesrc:"'.$setup4_submitpage_featuredverror.'"';
															}

															if($this->VSORules != ''){
																$this->VSORules .= ',pfuploadimagesrc:"required"';
															}else{
																$this->VSORules = 'pfuploadimagesrc:"required"';
															}
															}
															if ($params['formtype'] != 'edititem') {
																$upload_limited = $setup4_submitpage_imagelimit;
															}else{
																$upload_limited = $setup4_submitpage_imagelimit - $images_count;
															}
															$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
															

															$setup4_submitpage_imagesizelimit = PFSAIssetControl('setup4_submitpage_imagesizelimit','','2');/*Image size limit*/

															$this->FieldOutput .= '<div id="pfdropzoneupload" class="dropzone"></div>';
															if ($params['formtype'] != 'edititem') {
															$this->FieldOutput .= '<input type="hidden" class="pfuploadimagesrc" name="pfuploadimagesrc" id="pfuploadimagesrc">';
															}
															$this->FieldOutput .= '
															<script type="text/javascript">
															(function($) {
															"use strict";
																$(function(){
																	';
																	if(!empty($params['post_id'])){
																	$this->FieldOutput .= '$.pfitemdetail_listimages('.$params['post_id'].');';
																	}
																	$this->FieldOutput .= '
																	
																	$.drzoneuploadlimit = '.$upload_limited.';
																	var myDropzone = new Dropzone("div#pfdropzoneupload", {
																		url: theme_scriptspf.ajaxurl,
																		params: {
																	      action: "pfget_imageupload",
																	      security: "'.wp_create_nonce('pfget_imageupload').'",
																	      ';
																	      if ($params['formtype'] == 'edititem') {
																	      	$this->FieldOutput .= ' id:'.$params['post_id'];
																	      }
																		$this->FieldOutput .= ' 
																	    },
																		autoProcessQueue: true,
																		acceptedFiles:"image/*",
																		maxFilesize: '.$setup4_submitpage_imagesizelimit.',
																		maxFiles: '.$upload_limited.',
																		parallelUploads:1,
																		uploadMultiple: false,
																		';
																	      if ($params['formtype'] != 'edititem') {
																	      	$this->FieldOutput .= 'addRemoveLinks:true,';
																	      }
																		$this->FieldOutput .= ' 
																		dictDefaultMessage: "'.esc_html__( 'Arrastra los archivos aquí para subirlos!','pointfindert2d').'<br/>'.esc_html__( 'Puede añadir hasta','pointfindert2d').' <div class=\'pfuploaddrzonenum\'>{0}</div> '.esc_html__( 'imágen(es)','pointfindert2d').' '.sprintf(esc_html__('(Tamaño del archivo Max.: %dMB por imagen)','pointfindert2d'),$setup4_submitpage_imagesizelimit).' ".format($.drzoneuploadlimit),
																		dictFallbackMessage: "'.esc_html__( 'Su navegador no soporta arrastrar y soltar el archivo de carga', 'pointfindert2d' ).'",
																		dictInvalidFileType: "'.esc_html__( 'Tipo de archivo no soportado', 'pointfindert2d' ).'",
																		dictFileTooBig: "'.sprintf(esc_html__( 'Tamaño del archivo es demasiado grande. (Tamaño máximo del archivo: %dmb)', 'pointfindert2d' ),$setup4_submitpage_imagesizelimit).'",
																		dictCancelUpload: "",
																		dictRemoveFile: "'.esc_html__( 'Eliminar', 'pointfindert2d' ).'",
																		dictMaxFilesExceeded: "'.esc_html__( 'Máximo del archivo excedid', 'pointfindert2d' ).'",
																		clickable: "#pf-ajax-fileuploadformopen"
																	});
																	
																	Dropzone.autoDiscover = false;
																	
																	var uploadeditems = new Array();

																	myDropzone.on("success", function(file,responseText) {
																		var obj = [];
																		$.each(responseText, function(index, element) {
																			obj[index] = element;
																		});
																		';
																		
																	    if ($params['formtype'] != 'edititem') {
																		    $this->FieldOutput .= '

																			if (obj.process == "up" && obj.id.length != 0) {
																				file._removeLink.id = obj.id;
																				uploadeditems.push(obj.id);
																				$("#pfuploadimagesrc").val(uploadeditems);
																			}
																			';
																		}else{
																			$this->FieldOutput .= '
																				$(".pfuploaddrzonenum").text($.drzoneuploadlimit -1);
																				$.drzoneuploadlimit = $.drzoneuploadlimit -1
																		    	$.pfitemdetail_listimages('.$params['post_id'].');
																		    	myDropzone.options.maxFiles = $.drzoneuploadlimit;
																	      	';
																		}
																	    
																	$this->FieldOutput .= ' 
																		
																	});

																	myDropzone.on("totaluploadprogress",function(uploadProgress){
																		if (uploadProgress > 0) {
																			$("#pf-ajax-uploaditem-button").val("'.esc_html__( 'Por favor Espere Subiendo imagen...', 'pointfindert2d' ).'");
																			$("#pf-ajax-uploaditem-button").attr("disabled", true);
																		}
																	});
																	';
																	if ($params['formtype'] != 'edititem') {
																		$this->FieldOutput .= ' 	
																			myDropzone.on("removedfile", function(file) {
																			    if (file.upload.progress != 0) {
																					if(file._removeLink.id.length != 0){
																						var removeditem = file._removeLink.id;
																						removeditem.replace(\'"\', "");
																						$.ajax({
																						    type: "POST",
																						    dataType: "json",
																						    url: theme_scriptspf.ajaxurl,
																						    data: { 
																						        action: "pfget_imageupload",
																				      			security: "'.wp_create_nonce('pfget_imageupload').'",
																				      			iid:removeditem
																						    }
																						});
																						for(var i = uploadeditems.length; i--;) {
																					          if(uploadeditems[i] == removeditem) {
																					              uploadeditems.splice(i, 1);
																					          }
																					      }
																						
																						$("#pfuploadimagesrc").val(uploadeditems);

																						$("#pf-ajax-uploaditem-button").attr("disabled", false);
																						$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','').'");
																					}
																			    }
																			});
																			

																			myDropzone.on("queuecomplete",function(file){
																				$("#pf-ajax-uploaditem-button").attr("disabled", false);
																				$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','').'");
																			});
																		';
																	}else{
																		$this->FieldOutput .= '
																			myDropzone.on("queuecomplete",function(file){
																				myDropzone.removeAllFiles();
																			});
																			
																			myDropzone.on("queuecomplete",function(file){
																				$("#pf-ajax-uploaditem-button").attr("disabled", false);
																				$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_titlee','','').'");
																			});
																		';
																	}
																$this->FieldOutput .= ' 	
																});
																
															})(jQuery);
															</script>
															
															<a id="pf-ajax-fileuploadformopen" class="button pfmyitempagebuttonsex" style="width:100%">'.esc_html__( 'Haga clic para seleccionar las fotos', 'pointfindert2d' ).'</a>
															';
															$this->FieldOutput .= '</section>';
														}
													/**
													*Dropzone Upload
													**/
												}
												$this->FieldOutput .= '</section>';
											}
										/**
										*Image Upload
										**/



										/**
										*File Upload
										**/
											

											if ($stp4_fupl == 1) {
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-file">'.esc_html__('CARGAR DOCUMENTO ADJUNTO','pointfindert2d' ).'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfitemfilecontainer pferrorcontainer pfsubmit-inner-sub-file">';
												

												$stp4_Filelimit = PFSAIssetControl("stp4_Filelimit","","10");
												$stp4_Filesizelimit = PFSAIssetControl("stp4_Filesizelimit","","10");
												
												wp_register_script('moxieformforie', get_template_directory_uri() . '/js/moxie.min.js', array('jquery'), '1.4.1',true); 
												wp_enqueue_script('moxieformforie'); 

												$this->FieldOutput .= '<div class="pfuploadedfiles"></div>';

												$this->FieldOutput .= '
												<script type="text/javascript">
												(function($) {
												"use strict";
													$(function(){
														';
														if(!empty($params['post_id'])){
														$this->FieldOutput .= '$.pfitemdetail_listfiles('.$params['post_id'].');';
														}
												$this->FieldOutput .= ' 	
													});
													
												})(jQuery);
												</script>';

												$pffileuploadlimit = $stp4_Filelimit;
												$imagesvalue = '';
												if ($params['formtype'] != 'edititem') {
													$files_count = 0;
													$files_newlimit = $pffileuploadlimit;
												}else{

													$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_files');

													$files_count = count($images_of_thispost);
													$files_newlimit = $pffileuploadlimit - $files_count;
												}

												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												
												
												$this->FieldOutput .= '<label for="file" class="lbl-text">'.esc_html__('SUBIR NUEVO ADJUNTO','pointfindert2d').': ('.esc_html__('MAX','pointfindert2d').': '.$pffileuploadlimit.'/<span class="pfmaxtext2">'.$files_newlimit.'</span>) '.esc_html__('(Permitido: Documentos)','pointfindert2d').':</label>';
												$this->FieldOutput .= '<div class="pfuploadfeaturedfile-container"><a id="pfuploadfeaturedfile_remove" style="font-size: 12px;line-height: 14px;"><i class="pfadmicon-glyph-64" style="font-size: 14px;"></i> '.esc_html__('Eliminar los archivos subidos','pointfindert2d').'</a></div>';
												$this->FieldOutput .= '<div class="pfuploadfeaturedfileupl-container">';
												$this->FieldOutput .= '
					                            <label for="file" class="lbl-ui file-input">
						                            <div id="pffeaturedfileuploadcontainer">
												        <a id="pffeaturedfileuploadfilepicker" href="javascript:;"><i class="pfadmicon-glyph-512"></i> '.esc_html__('Seleccionar archivos','pointfindert2d').'</a>
												    </div>
					                            </label> 
					                            </div>
												';
												
												$this->FieldOutput .= '</section>';

												$this->FieldOutput .= '<input type="hidden" name="pfuploadfilesrc" id="pfuploadfilesrc" value="'.$imagesvalue.'">';
												
												if($stp4_err_st == 1 && $params['formtype'] != 'edititem'){
													
													if($this->VSOMessages != ''){
														$this->VSOMessages .= ',pfuploadfilesrc:"'.$stp4_err.'"';
													}else{
														$this->VSOMessages = 'pfuploadfilesrc:"'.$stp4_err.'"';
													}

													if($this->VSORules != ''){
														$this->VSORules .= ',pfuploadfilesrc:"required"';
													}else{
														$this->VSORules = 'pfuploadfilesrc:"required"';
													}
												}

												$nonceimgup = wp_create_nonce('pfget_fileupload');
												

												$this->ScriptOutput .= "$.pfuploadfilelimit = ".$files_newlimit.";";

												if ($params['formtype'] == 'edititem') {
													$this->ScriptOutput .= "
													if ($.pfuploadfilelimit <= 0) {
														$('.pfuploadfeaturedfile-container').css('display','none');
														$('.pfuploadfeaturedfileupl-container').css('display','none');
													}
													
													if ($.pfuploadfilelimit <= ".$pffileuploadlimit."){
														$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
													}

													if (".$files_newlimit." == 0) {
														$('.pfuploadfeaturedfileupl-container').css('display','none');
													}
													
													";
												}

												$stp4_allowed = PFSAIssetControl("stp4_allowed","",'jpg,jpeg,gif,png,pdf,rtf,csv,zip, x-zip, x-zip-compressed,rar,doc,docx,docm,dotx,dotm,docb,xls,xlt,xlm,xlsx,xlsm,xltx,xltm,ppt,pot,pps,pptx,pptm');

												$this->ScriptOutput .= "

												/*File upload featured image AJAX */
													var PFfileInput = new mOxie.FileInput({
											            browse_button: document.getElementById('pffeaturedfileuploadfilepicker'),
											            container: 'pffeaturedfileuploadcontainer',
											            accept: [{ title: 'Documents', extensions: '".$stp4_allowed."' }],
											            multiple: true
											        });

											        $.pfuploadedfilecount = 0;

											        $.pfuploadoldfiles = function(id){
											        	var numberi = id;
											        	numberi = numberi + 1;
										        		$('.pfitemfilecontainer').pfLoadingOverlay({action:'show',message: '".esc_html__('Cargando archivos: ','pointfindert2d')."'+numberi});

														if ($.pfuploadfilelimit > 0 && $.pfuploadedfilecount < $.pfuploadmaxfile) {
														
												            var formData = new mOxie.FormData();
												            formData.append('action','pfget_fileupload');
														    formData.append('security','".$nonceimgup."');
														    formData.append('oldup',1);
														    formData.append('pfuploadfeaturedfile', PFfileInput.files[id]);

														    var featured_xhr = new mOxie.XMLHttpRequest();
														    featured_xhr.open('POST', theme_scriptspf.ajaxurl, true);
														    featured_xhr.responseType = 'text';
														    featured_xhr.send(formData);

														    var clearfeaturedinterval = function(){

														    	clearInterval(featureimgint);

														    	$.pfuploadedfilecount = $.pfuploadedfilecount + 1;
																$.pfuploadfilelimit = $.pfuploadfilelimit - 1;

														    	if ($.pfuploadedfilecount == $.pfuploadmaxfile) {
															    	if ($.pfuploadfilelimit > 0) {
															    		$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
															    	}else{
															    		$('.pfuploadfeaturedfileupl-container').css('display','none');
															    	}
																	$('.pfuploadfeaturedfile-container').css('display','inline-block');
																	$('.pfitemfilecontainer').pfLoadingOverlay({action:'hide'});
															    }

															    if ($.pfuploadfilelimit > 0) {
														    		$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
														    	}else{
														    		$('.pfuploadfeaturedfileupl-container').css('display','none');
														    	}

															    $('.pfmaxtext2').text($.pfuploadfilelimit);
															    $.pfuploadoldfiles($.pfuploadedfilecount);
														    }

														    var featureimgint = setInterval(function(){
														    	if (featured_xhr.readyState == 4) {
														    		var obj = featured_xhr.response;
														    		obj = $.parseJSON(obj)
														    		
															    		if (obj.process == 'up') {

																			var uploadedfiles = $('#pfuploadfilesrc').val();
																			if (uploadedfiles.length > 0) {
																				uploadedfiles = uploadedfiles+','+obj.id;
																				$('#pfuploadfilesrc').val(uploadedfiles);
																			}else{
																				$('#pfuploadfilesrc').val(obj.id);
																			}
																		}
																	clearfeaturedinterval();
														    	}
														    }, 1000);																	
														}else{
															if ($.pfuploadfilelimit > 0) {
													    		$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
													    	}else{
													    		$('.pfuploadfeaturedfileupl-container').css('display','none');
													    	}
															$('.pfuploadfeaturedfile-container').css('display','inline-block');
															$('.pfitemfilecontainer').pfLoadingOverlay({action:'hide'});
															$.pfuploadmaxfile = $.pfuploadedfilecount = 0;
														};
											        };

											        PFfileInput.onchange = function(e) {
											        	if (PFfileInput.files && PFfileInput.files.length) {
												       		$.pfuploadmaxfile = PFfileInput.files.length;
												       		$.pfuploadoldfiles(0);
											       		}
											        };
											        PFfileInput.init();

												/* Remove Featured Files Ajax */
													$('#pfuploadfeaturedfile_remove').live('click touchstart',function(){

														$('.pfitemfilecontainer').pfLoadingOverlay({action:'show',message: '".esc_html__('Eliminando archivo(s)...','pointfindert2d')."'});

													    var formData = new mOxie.FormData();
											            formData.append('action','pfget_fileupload');
													    formData.append('security','".$nonceimgup."');
													    formData.append('oldup',1);
													    formData.append('exid', $('#pfuploadfilesrc').val());

													    var remove_xhr = new mOxie.XMLHttpRequest();
													    remove_xhr.open('POST', theme_scriptspf.ajaxurl, true);
													    remove_xhr.responseType = 'text';
													    remove_xhr.send(formData);
													    var clearfeaturedinterval = function(){
													    	clearInterval(removefeaturedimg);
													    }
													    var removefeaturedimg = setInterval(function(){
													    	if (remove_xhr.readyState == 4) {
													    		var obj = remove_xhr.response;
													    		obj = $.parseJSON(obj)
													    		
													    		if (obj.process == 'del') {
																	$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
																	$('.pfuploadfeaturedfile-container').css('display','none');
																	$.pfuploadfilelimit = ".$files_newlimit.";
																	$('.pfmaxtext2').text($.pfuploadfilelimit);
																}
																$('.pfitemfilecontainer').pfLoadingOverlay({action:'hide'});
																clearfeaturedinterval();
													    	}
													    	$('#pfuploadfilesrc').val('');
													    	$.pfuploadmaxfile = $.pfuploadedfilecount = 0;

													    }, 1000);
													});

												";
												$this->FieldOutput .= '</section>';

											}
										/**
										*File Upload
										**/


										/**
										*Message to Reviewer
										**/
											if($setup4_submitpage_messagetorev == 1){

												$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('Mensaje al revisor','pointfindert2d').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner">';
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												$this->FieldOutput .= '
							                        <label class="lbl-ui">
							                        	<textarea id="item_mesrev" name="item_mesrev" class="textarea mini"></textarea>';
												$this->FieldOutput .= '<b class="tooltip left-bottom"><em>'.esc_html__('OPCIONAL:','pointfindert2d').esc_html__('Puede enviar un mensaje al revisor.','pointfindert2d').'</em></b>';
												 
							                    $this->FieldOutput .= '</label>';
							                    $this->FieldOutput .= '</section>';                     
							                  	$this->FieldOutput .= '</section>'; 
												
											}
										/**
										*Message to Reviewer
										**/


										/** 
										*Featured Item 
										**/
											$featured_permission = true;

											if ($setup4_membersettings_paymentsystem == 2) {
												if ($params['formtype'] == 'edititem') {
													if ($packageinfo['webbupointfinder_mp_fitemnumber'] <= 0) {
														$featured_permission = false;
													}
												}else{
													if ($membership_user_featureditem_limit <= 0) {
														$featured_permission = false;
													}
												}
											}else{
												if ($params['formtype'] == 'edititem') {
													$featured_permission = true;
												}
												if (PFSAIssetControl('setup31_userpayments_featuredoffer','','1') != 1) {
													$featured_permission = false;
												}
											}

											if ($featured_permission) {
												if ($setup4_membersettings_paymentsystem != 2) {

													$setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','5');
													$stp31_daysfeatured = PFSAIssetControl('stp31_daysfeatured','','3');

													if ($stp31_daysfeatured > 1) {
														$featured_day_word = esc_html__('días','pointfindert2d');
													}else{
														$featured_day_word = esc_html__('día','pointfindert2d');
													}

													$featured_price_output = '';
													if ($package_featuredcheck != 1) {
														if ($setup31_userpayments_pricefeatured == 0) {
															$featured_price_output = '<span class="pfitem-featuredprice" title="'.sprintf(esc_html__('Por %d %s','pointfindert2d' ),$stp31_daysfeatured,$featured_day_word).'">'.$stp31_daysfeatured.$featured_day_word.'</span>';
														}else{
															if ($setup20_paypalsettings_paypal_price_pref == 1) {
																$featured_price_output = ' <span class="pfitem-featuredprice" title="'.sprintf(esc_html__('El precio es %s por %d %s','pointfindert2d' ),$setup20_paypalsettings_paypal_price_short.$setup31_userpayments_pricefeatured,$stp31_daysfeatured,$featured_day_word).'">'.$setup20_paypalsettings_paypal_price_short.$setup31_userpayments_pricefeatured.' / '.$stp31_daysfeatured.$featured_day_word.'</span>';
															}else{
																$featured_price_output = ' <span class="pfitem-featuredprice" title="'.sprintf(esc_html__('El precio es %s por %d %s','pointfindert2d' ),$setup31_userpayments_pricefeatured.$setup20_paypalsettings_paypal_price_short,$stp31_daysfeatured,$featured_day_word).'">'.$setup31_userpayments_pricefeatured.$setup20_paypalsettings_paypal_price_short.' / '.$stp31_daysfeatured.$featured_day_word.'</span>';
															}
														}
													}

													$this->FieldOutput .= '<div class="pfsubmit-title">'.PFSAIssetControl('setup31_userpayments_titlefeatured','','Featured Item').$featured_price_output.'</div>';
													$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-nopadding">';

													
								                    if($package_featuredcheck == 1 && $current_post_status != 'pendingpayment'){
														$pointfinder_order_expiredate_featured = esc_attr(get_post_meta( PFU_GetOrderID($params['post_id'],1), 'pointfinder_order_expiredate_featured', true ));
														$featured_listing_expiry = PFU_Dateformat($pointfinder_order_expiredate_featured);
														$status_featured_it_text = sprintf(esc_html__('Este artículo es ofrecido hasta %s','pointfindert2d'),'<b>'.$featured_listing_expiry.'</b>');
								                    }else{
								                    	$status_featured_it_text = PFSAIssetControl('setup31_userpayments_textfeatured','','');
								                    }

													$this->FieldOutput .= '								
							                            <div class="gspace pfupload-featured-item-box" style="border:0;padding: 12px;">
							                            	<p>
															';
																$pp_status_checked = $pp_status_checked2 = '';

																if ($this->itemrecurringstatus == 1) {
																	$pp_status_checked2 = ' disabled="disabled"';
																}


																if($package_featuredcheck == 1 && $current_post_status != 'pendingpayment'){
																	$this->FieldOutput .='<input type="hidden" name="featureditembox" id="featureditembox">';
																}else{

																	if ($current_post_status == 'pendingpayment') {
																		if ($package_featuredcheck == 1) {
																			$pp_status_checked = ' checked="checked"';
																		}
																	}
																	
																	
																	$this->FieldOutput .='
																	<label class="toggle-switch blue">
																	<input type="checkbox" name="featureditembox" id="featureditembox"'.$pp_status_checked.$pp_status_checked2.'>
																	<label for="featureditembox" data-on="'.esc_html__('SI','pointfindert2d').'" data-off="'.esc_html__('NO','pointfindert2d').'"></label>
																	</label>';
																	
																}
																$this->FieldOutput .= $status_featured_it_text;
															  $this->FieldOutput .= '
															</p>
							                            </div>';
								                    
								                    $this->FieldOutput .= '</section>';
												}else{
													$pf_member_checked_t = '';

													$pf_member_checked = get_post_meta( $params['post_id'], 'webbupointfinder_item_featuredmarker', true );

													if (!empty($pf_member_checked)) {
														$pf_member_checked_t = ' checked';
													}
													$setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','');

													$this->FieldOutput .= '<div class="pfsubmit-title">'.PFSAIssetControl('setup31_userpayments_titlefeatured','','Featured Item').'</div>';
													$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-nopadding">';
													

													$this->FieldOutput .= '								
							                            <div class="gspace pfupload-featured-item-box" style="border:0;padding: 12px;">
							                            	<p>
															<label class="toggle-switch blue">
																<input type="checkbox" name="featureditembox" id="featureditembox"'.$pf_member_checked_t.'>
																<label for="featureditembox" data-on="'.esc_html__('SI','pointfindert2d').'" data-off="'.esc_html__('NO','pointfindert2d').'"></label>
															</label> 
															 <span>
															   '.PFSAIssetControl('setup31_userpayments_textfeatured','','').'
															  </span>
															</p>          
															

							                            </div>';
							                        $this->FieldOutput .= '</section>';
												}
												
												
						                    	
						                    	
						                    }
										/**
										*Featured Item 
										**/


										/**
										*Select package
										**/
											if ($setup4_membersettings_paymentsystem == 1) {
												
												$stp31_up2_pn = PFSAIssetControl('stp31_up2_pn','','Basic Package');
												$setup31_userpayments_priceperitem = PFSAIssetControl('setup31_userpayments_priceperitem','','10');
												$setup31_userpayments_timeperitem = PFSAIssetControl('setup31_userpayments_timeperitem','','10');

												$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('Paquetes de listado','pointfindert2d').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner">';
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub" style="margin-left: -7px;">';

													$this->FieldOutput .= '<div class="pflistingtype-selector-main-top clearfix">';
													/* Add first package - Price/Time/Name */
													$ppp_packages = array();
													$ppp_packages[] = array('id'=>1,'price'=>$setup31_userpayments_priceperitem,'time'=>$setup31_userpayments_timeperitem,'title'=>$stp31_up2_pn);


													$listing_query = new WP_Query(array('post_type' => 'pflistingpacks','posts_per_page' => -1,'order_by'=>'ID','order'=>'ASC'));
													$this_pack_price = $this_pack_info = '';

													$founded_listingpacks = 0;
													$founded_listingpacks = $listing_query->found_posts;

													if ($founded_listingpacks > 0) {
														if ( $listing_query->have_posts() ) {
															$this->FieldOutput .= '<ul>';
															while ( $listing_query->have_posts() ) {
																$listing_query->the_post();
																$lp_post_id = get_the_id();

																$lp_price = get_post_meta( $lp_post_id, 'webbupointfinder_lp_price', true );
																if (empty($lp_price)) {
																	$lp_price = 0;
																}

																$lp_time = get_post_meta( $lp_post_id, 'webbupointfinder_lp_billing_period', true );
																if (empty($lp_time)) {
																	$lp_time = 0;
																}

																$lp_show = get_post_meta( $lp_post_id, 'webbupointfinder_lp_showhide', true );

																if ($lp_show == 1) {
																	array_push($ppp_packages, array('id'=>$lp_post_id, 'price'=>$lp_price, 'time'=>$lp_time, 'title'=>get_the_title($lp_post_id)));
																}
															}
															$this->FieldOutput .= '</ul>';
															wp_reset_postdata();
														}
													}
													
													if ($this->itemrecurringstatus == 1) {
														$status_checked_pack = ' disabled="disabled"';
													}else{
														$status_checked_pack = '';
													}

													$stp31_userfree = PFSAIssetControl("stp31_userfree","","0");

													$status_package_selection = true;

													if ($ppp_packages > 0) {
														foreach ($ppp_packages as $ppp_package) {
															
															if ($ppp_package['price'] == 0) {
																$this_pack_price = esc_html__('Grátis','pointfindert2d');
															}else{
																if ($setup20_paypalsettings_paypal_price_pref == 1) {
																	$this_pack_price = ' <span style="font-weight:600;" title="'.esc_html__('El precio del paquete es ','pointfindert2d' ).$setup20_paypalsettings_paypal_price_short.$ppp_package['price'].'">'.$setup20_paypalsettings_paypal_price_short.$ppp_package['price'].'</span>';
																}else{
																	$this_pack_price = ' <span style="font-weight:600;" title="'.esc_html__('El precio del paquete es ','pointfindert2d' ).$ppp_package['price'].$setup20_paypalsettings_paypal_price_short.'">'.$ppp_package['price'].$setup20_paypalsettings_paypal_price_short.'</span>';
																}
															}

															if ($current_post_status == 'publish') {
																if ($default_package == $ppp_package['id']) {
																	$status_package_selection = true;
																}else{
																	if ($ppp_package['price'] == 0 && $params['formtype'] == 'edititem' && $stp31_userfree == 0) {
																		$status_package_selection = false;
																	}else{
																		$status_package_selection = true;
																	}
																}
																
															}elseif ($current_post_status == 'pendingpayment') {
																if ($params['formtype'] == 'edititem') {
																	$pointfinder_order_expiredate = esc_attr(get_post_meta( PFU_GetOrderID($params['post_id'],1), 'pointfinder_order_expiredate', true ));
																}else{
																	$pointfinder_order_expiredate = false;
																}
																
																
																if ($ppp_package['price'] == 0 && $pointfinder_order_expiredate != false && $params['formtype'] == 'edititem' && $stp31_userfree == 0) {
																	$status_package_selection = false;
																}else{
																	$status_package_selection = true;
																}
															}

															if ($status_package_selection) {
																$this->FieldOutput .= '<div class="pfpack-selector-main">';
																$this->FieldOutput .= '<input type="radio" name="pfpackselector" id="pfpackselector'.$ppp_package['id'].'" class="pfpackselector" value="'.$ppp_package['id'].'"'.$status_checked_pack.' '.checked( $default_package, $ppp_package['id'],0).'/>';
																$this->FieldOutput .= '<label for="pfpackselector'.$ppp_package['id'].'" style="font-weight:600;">
																<span class="packselector-title">'.$ppp_package['title'].'</span>
																<span class="packselector-info">'.sprintf(esc_html__("Por %s día(s)",'pointfindert2d' ),$ppp_package['time']).'</span>
																<span class="packselector-price">'.$this_pack_price.'</span>
																</label>';
																$this->FieldOutput .= '</div>';
															}

															
															
															

														}
													}
													
													
													$this->FieldOutput .= '</div>';

							                    $this->FieldOutput .= '</section>';                     
							                  	$this->FieldOutput .= '</section>';
							                  	$this->PFValidationCheckWrite(1,esc_html__('Por favor, seleccione un paquete.','pointfindert2d' ),'pfpackselector');
							                }
										/**
										*Select package
										**/


										/**
										*Total Cost
										**/
											if ($setup4_membersettings_paymentsystem == 1) {
												
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-payment">'.esc_html__('Pago','pointfindert2d').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-payment">';
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';

													$this->FieldOutput .= '<div class="pfsubmit-inner-totalcost-output"></div>';

							                    $this->FieldOutput .= '</section>';                     
							                  	$this->FieldOutput .= '</section>';
							                  	$this->PFValidationCheckWrite(1,esc_html__('Por favor, seleccione una forma de pago.','pointfindert2d' ),'pf_lpacks_payment_selection');
							                }
										/**
										*Total Cost
										**/


										/**
										*Terms and conditions
										**/
											$setup4_ppp_terms = PFSAIssetControl('setup4_ppp_terms','','1');
											if ($setup4_ppp_terms == 1) {
												if($this->VSOMessages != ''){
													$this->VSOMessages .= ',pftermsofuser:"'.esc_html__( 'Debe aceptar los términos y condiciones.', 'pointfindert2d' ).'"';
												}else{
													$this->VSOMessages = 'pftermsofuser:"'.esc_html__( 'Debe aceptar los términos y condiciones.', 'pointfindert2d' ).'"';
												}

												if($this->VSORules != ''){
													$this->VSORules .= ',pftermsofuser:"required"';
												}else{
													$this->VSORules = 'pftermsofuser:"required"';
												}

												global $wpdb;
												$terms_conditions_template = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ",'_wp_page_template','terms-conditions.php'), ARRAY_A);
												if (isset($terms_conditions_template[0]['post_id'])) {
													$terms_permalink = get_permalink($terms_conditions_template[0]['post_id']);
												}else{
													$terms_permalink = '#';
												}
												
												
												if ($params['formtype'] == 'edititem') {
													$checktext1 = ' checked=""';
												}else{$checktext1 = '';}
												$pfmenu_perout = PFPermalinkCheck();
												$this->FieldOutput .= '<section>';
												$this->FieldOutput .= '
													<span class="goption upt">
					                                    <label class="options">
					                                        <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1"'.$checktext1.'>
					                                        <span class="checkbox"></span>
					                                    </label>
					                                    <label for="check1">'.sprintf(esc_html__( 'He leído los %s términos y condiciones %s y los acepto.', 'pointfindert2d' ),'<a href="'.$terms_permalink.$pfmenu_perout.'ajax=true&width=800&height=400" rel="prettyPhoto[ajax]"><strong>','</strong></a>').'</label>
					                               </span>
												';
												
								                $this->FieldOutput .= '</section>';
								            }
										/**
										*Terms and conditions
										**/

									$this->FieldOutput .= '</div>';


								
								/** 
								*End : First Column (Map area, Image upload etc..)
								**/
							}


						/**
						*End: New Item Page Content
						**/
						}
						break;
                        
					case 'myshop':
						include("./wp-content/themes/pointfinder/vlz/admin/page_myshop.php");
					break;

					case 'mypets':
						/**
						*Start: Pets list Page Content
						**/
								$formaction = 'pfadd_new_pet';
								$noncefield = wp_create_nonce($formaction);
								$buttonid = 'pf-ajax-add-pet-button';
								$buttontext = esc_html__('AGREGAR MASCOTA','pointfindert2d');
								$user_id = $current_user->ID;
                                $count = ($params['count']!='')? $params['count']: 0;

								// BEGIN New style Italo
								if($count>0) {
								    $pets = explode(",",$params['list']);

								    $this->FieldOutput .= '<ul class="pfitemlists-content-elements pf3col" data-layout-mode="fitRows" style="position: relative; margin: 20px -15px 20px 0px;">';
								    foreach($pets as $pet){ 
									    $pet_detail = kmimos_get_pet_info($pet);

									    $photo = get_option('siteurl')."/";
									    $photo .= (!empty($pet_detail['photo']))? $pet_detail['photo'] : 'wp-content/themes/pointfinder/images/default.jpg';
									    $this->FieldOutput .= '
										<li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item text-center">
											<div class="pflist-item" style="background-color:#ffffff;">  
												<div class="pflist-item-inners">
													<div class="pflist-imagecontainer pflist-subitem" style="
														background-image: url('.$photo.')!important;
														background-size:contain;
														background-repeat:no-repeat;
														background-position:center;
													">
													<a href="'.$params['detail_url'].$pet.'">
														<div class="vlz_postada_cuidador" style="height:160px;width:100%;background-color:transparent;"></div>
														</div>
														<h3 class="kmi_link">'. get_the_title($pet).'</h3>
													</a>
													<br>
													<img src="'.get_option('siteurl').'/wp-content/plugins/kmimos/assets/rating/100.png" width="50px">
												</div>
											</div>
										</li>';
									}
								    $this->FieldOutput .= '</ul>';
								}else{
									$this->FieldOutput .=  '
									<p>
										<img src="/wp-content/plugins/kmimos/assets/rating/100.png" width="50px">
										No tienes ninguna mascota cargada
									</p>';
								}
								// END New style Italo
		

                        // BEGIN Italo Old
                            // $this->FieldOutput .= '<div class="lista_mascotas" data-user="'.$user_id.'"><ul>';
                            // if($count>0) {
                            //     $pets = explode(",",$params['list']);
                            //     $this->FieldOutput .= '<div class="inside">';
                            //     $this->FieldOutput .= '<ul>';
                            //     foreach($pets as $pet){ 
                            //         $this->FieldOutput .= '<li><a href="'.$params['detail_url'].$pet.'">'. get_the_title($pet).'</a></li>';
                            //     }
                            //     $this->FieldOutput .= '</ul>';
                            //     $this->FieldOutput .= '<br><p>Total: '.count($pets).' mascotas</p>';
                            //     $this->FieldOutput .= '</div>';
                            // }
                            // else $this->FieldOutput .=  '<p>No tienes ninguna mascota cargada</p>';
                            // $this->FieldOutput .=  '</ul><br></div>';
                        // END Italo Old
                        
                            $this->ScriptOutput .= "
                            jQuery('#pf-ajax-add-pet-button').on('click',function(e){
                                e.preventDefault();
                                jQuery('#pfuaprofileform').attr('action','?ua=newpet');
                                jQuery('#pfuaprofileform').submit();
                            });
                            ";

						/** ITALo 
						*End: Pets list Page Content
						**/
						break;
    
					case 'mypet':
						/**
						*Start: Pet detail Page Content
						**/

				        $formaction = 'pfupdate_my_pet';
                        $noncefield = wp_create_nonce($formaction);
                        $buttonid = 'pf-ajax-update-pet-button';
                        $buttontext = esc_html__('ACTUALIZAR MASCOTA','pointfindert2d');
                        $pet_id = $params['pet_id'];
                        $current_pet = kmimos_get_pet_info($pet_id);

                        $photo_pet = get_option('siteurl');
                        $photo_pet .= (!empty($current_pet['photo']))? "/".$current_pet['photo']: "/wp-content/themes/pointfinder/images/noimg.png";

                        $this->FieldOutput .= '
                                <style>
                                .cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                                .cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                                </style>
								<style>
		                        	.cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
		                        	.cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
		                        	.cell33 {width: 33.333333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
		                        	.img_portada_pet{ position: relative; height: 400px; overflow: hidden; border: solid 1px #777; background: #EEE; }
		                        	.img_portada_pet_fondo{ position: absolute; top: -1px; left: -1px; width: calc( 100% + 2px ); height: 402px; z-index: 50; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: transparent; filter: blur(2px); transition: all .5s ease; }
		                        	.img_portada_pet_normal{ position: absolute; top: 0px; left: 0px; width: 100%; height: 380px; z-index: 100; background-size: contain; background-position: center; background-repeat: no-repeat; background-color: transparent; margin: 10px 0px; transition: all .5s ease; }
		                        	.cambiar_portada_pet{ position: absolute; bottom: 10px; right: 10px; width: auto; padding: 10px; font-size: 16px; color: #FFF; background: #000; border: solid 1px #777; z-index: 200; }
		                        	.cambiar_portada_pet input{ position: absolute; top: -24px; left: 0px; width: 100%; height: 167%; z-index: 100; opacity: 0; cursor: pointer; }
		                        	
		                        	.jj_dash_cel50{float: left; width: calc(50% - 9px);}
									.jj_dash2_cel50{float: right; width: calc(50% - 9px);}

									@media (max-width: 568px) { 
										.jj_dash_cel50{float: left; width: calc(100% - 9px);}
										.jj_dash2_cel50{float: left; width: calc(100% - 9px);} 
									}
		                        </style>
								<section>
									<div class="img_portada_pet">
		                                <div class="img_portada_pet_fondo" style="background-image: url('.$photo_pet.');"></div>
		                                <div class="img_portada_pet_normal" style="background-image: url('.$photo_pet.');"></div>
		                                <div class="cambiar_portada_pet">
		                                	Cambiar Foto
		                                	<input type="file" id="portada_pet" name="portada_pet" accept="image/*" />
		                                </div>
                                	</div>
                                </section>

                                   <div class="cell50">
		                               <section>
		                                    <label for="pet_name" class="lbl-text">'.esc_html__('Nombre de la Mascota','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="hidden" name="pet_id" value="'.$current_pet['pet_id'].'" />
		                                    	<input type="text" name="pet_name" class="input" value="'.$current_pet['name'].'" />
		                                    </label>
		                               </section>
		                           </div>';
                        $this->FieldOutput .= '
		                           <div class="cell50">
		                           	   <section>
		                                    <label for="pet_type" class="lbl-text"><strong>'.esc_html__('Tipo de Mascota','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<select name="pet_type" class="input" id="pet_type" onchange="vlz_cambio_tipo()" />
                                                    <option value="">Favor Seleccione</option>';
							                        $tipos = kmimos_get_types_of_pets(); // Tipos de mascotas
							                        foreach ( $tipos as $tipo ) {
							                            $this->FieldOutput .= '<option value="'.$tipo->ID.'"';
							                            if($tipo->ID == $current_pet['type']) $this->FieldOutput .= ' selected';
							                            $this->FieldOutput .= '>'.esc_html( $tipo->name ).'</option>
							                            ';
							                        }
                        $this->FieldOutput .= '
                                                </select>
		                                    </label>
		                               </section>
		                           </div>';

		                           	global $wpdb;

                            		$razas = $wpdb->get_results("SELECT * FROM razas ORDER BY nombre ASC");

                            		$razas_str = "";
                                	foreach ($razas as $value) {
                                		$razas_str .= '<option value="'.$value->id.'"';
			                            if($value->id == $current_pet['breed']) $razas_str .= ' selected';
			                            $razas_str .= '>'.esc_html( $value->nombre ).'</option>';
                                	}

                            		$razas_str_gatos = "<option value=1>Gato</option>";

                        $this->FieldOutput .= '
                        			<div id="razas_perros" style="display: none;">'.$razas_str.'</div>
                        			<div id="razas_gatos" style="display: none;">'.$razas_str_gatos.'</div>
                                   	<div class="cell50">
		                               <section>
		                                    <label for="pet_breed" class="lbl-text">'.esc_html__('Raza de la Mascota','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<select id="pet_breed" name="pet_breed" class="input" value="'.$current_pet['breed'].'" />
		                                    		<option value="">Favor Seleccione</option>
		                                    		'.$razas_str.'
		                                    	</select>
		                                    </label>
		                               </section>
		                           </div>';
                        $this->FieldOutput .= ' 
                                   <div class="cell50">
		                               <section>
		                                    <label for="pet_colors" class="lbl-text">'.esc_html__('Colores de la Mascota','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="pet_colors" class="input" value="'.$current_pet['colors'].'" />
		                                    </label>
		                               </section>
		                           </div>';
                        $this->FieldOutput .= '
                                   <div class="cell25">
		                               <section>
		                                    <label for="pet_birthdate" class="lbl-text">'.esc_html__('Fecha de nacimiento','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="date" name="pet_birthdate" class="input datepicker" value="'.$current_pet['birthdate'].'" />
		                                    </label>
		                               </section>
		                           </div>';
                        $this->FieldOutput .= ' 
                                   <div class="cell25">
		                               <section>
		                                    <label for="pet_gender" class="lbl-text">'.esc_html__('Género de la mascota','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<select name="pet_gender" class="input" />
                                                    <option value="">Favor Seleccione</option>';
							                        $generos = kmimos_get_genders_of_pets(); // Géneros de mascotas
							                        foreach ( $generos as $genero ) {
							                            $this->FieldOutput .= '<option value="'.$genero['ID'].'"';
							                            if($genero['ID'] == $current_pet['gender']) $this->FieldOutput .= ' selected';
							                            $this->FieldOutput .= '>'.esc_html( $genero['singular'] ).'</option>
							                            ';
							                        }
                        $this->FieldOutput .= '
                                                </select>
		                                    </label>
		                               </section>
		                           </div>';
                        $this->FieldOutput .= '
		                           <div class="cell50">
		                           	   <section>
		                                    <label for="pet_size" class="lbl-text"><strong>'.esc_html__('Tamaño de la Mascota','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<select name="pet_size" class="input" />
                                                    <option value="">Favor Seleccione</option>';
                        $tamanos = kmimos_get_sizes_of_pets(); // Tamaños de mascotas
                        foreach ( $tamanos as $tamano ) {
                            $this->FieldOutput .= '<option value="'.$tamano['ID'].'"';
                            if($tamano['ID'] == $current_pet['size']) $this->FieldOutput .= ' selected';
                            $this->FieldOutput .= '>'.esc_html( $tamano['name'].' ('.$tamano['desc'].')' ).'</option>
                            ';
                        }
                        $this->FieldOutput .= '
                                                </select>
		                                    </label>
		                               </section>
		                           </div>';
                        $this->FieldOutput .= ' 
                                   <div class="cell25">
		                               <section>
		                                    <label for="pet_sterilized" class="lbl-text">'.esc_html__('Mascota Esterilizada','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<select name="pet_sterilized" class="input" />
                                                    <option value="">Favor Seleccione</option>';
							                        $si_no = array('no','si');
							                        for ( $i=0; $i<2; $i++ ) {
							                            $this->FieldOutput .= '<option value="'.$i.'"';
							                            if($i == (int)$current_pet['sterilized']) $this->FieldOutput .= ' selected';
							                            $this->FieldOutput .= '>'.$si_no[$i].'</option>
							                            ';
							                        }
                        $this->FieldOutput .= '
                                                </select>
		                                    </label>
		                               </section> 
		                           </div>';
                         $this->FieldOutput .= ' 
                                   <div class="cell25">
		                               <section>
		                                    <label for="pet_sociable" class="lbl-text">'.esc_html__('Mascota Sociable','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<select name="pet_sociable" class="input" />
                                                    <option value="">Favor Seleccione</option>';
                        for ( $i=0; $i<count($si_no); $i++ ) {
                            $this->FieldOutput .= '<option value="'.$i.'"';
                            if($i == (int)$current_pet['sociable']) $this->FieldOutput .= ' selected';
                            $this->FieldOutput .= '>'.$si_no[$i].'</option>
                            ';
                        }
                        $this->FieldOutput .= '
                                                </select>
		                                    </label>
		                               </section> 
		                           </div>';
                         $this->FieldOutput .= ' 
                                   <div class="cell25">
		                               <section>
		                                    <label for="aggresive_humans" class="lbl-text">'.esc_html__('Agresiva con Humanos','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<select name="aggresive_humans" class="input" />
                                                    <option value="">Favor Seleccione</option>';
                        for ( $i=0; $i<count($si_no); $i++ ) {
                            $this->FieldOutput .= '<option value="'.$i.'"';
                            if($i == (int)$current_pet['aggresive_humans']) $this->FieldOutput .= ' selected';
                            $this->FieldOutput .= '>'.$si_no[$i].'</option>
                            ';
                        }
                        $this->FieldOutput .= '
                                                </select>
		                                    </label>
		                               </section> 
		                           </div>';
                        $this->FieldOutput .= ' 
                                   <div class="cell25">
		                               <section>
		                                    <label for="aggresive_pets" class="lbl-text">'.esc_html__('Agresiva c/otras Mascotas','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<select name="aggresive_pets" class="input" />
                                                    <option value="">Favor Seleccione</option>';
                        for ( $i=0; $i<count($si_no); $i++ ) {
                            $this->FieldOutput .= '<option value="'.$i.'"';
                            if($i == (int)$current_pet['aggresive_pets']) $this->FieldOutput .= ' selected';
                            $this->FieldOutput .= '>'.$si_no[$i].'</option>
                            ';
                        }
                        $this->FieldOutput .= '
                                                </select>
		                                    </label>
		                               </section>
		                           </div>';
                        $this->FieldOutput .= '     
		                               <section>
		                                    <label for="pet_observations" class="lbl-text">'.esc_html__('Observaciones','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<textarea name="pet_observations" class="textarea">'. $current_pet['observations'].'</textarea>
		                                    </label>';
                        if($_GET['ua']=='mypet') $this->FieldOutput .= '
		                                    <label for="delete_pet" class="lbl-text" style="float: right; padding: 10px 0px;">
                                            <input type="checkbox" name="delete_pet" value="1">
                                            <strong>'.esc_html__('Eliminar esta mascota','pointfindert2d').'</strong>.</label>';
                        $this->FieldOutput .= '
		                               </section>
                            <script>
                            	function vlz_cambio_tipo(){
                            		var valor = jQuery("#pet_type").val();
                            		if( valor == "2605" ){
                            			var opciones = jQuery("#razas_perros").html();
                            			jQuery("#pet_breed").html(opciones);
                            		}
                            		if( valor == "2608" ){
                            			var opciones = jQuery("#razas_gatos").html();
                            			jQuery("#pet_breed").html(opciones);
                            		}
                            	}

							function vista_previa_pet(evt) {
							  	var files = evt.target.files;
							  	for (var i = 0, f; f = files[i]; i++) {  
							       	if (!f.type.match("image.*")) {
							            continue;
							       	}
							       	var reader = new FileReader();
							       	reader.onload = (function(theFile) {
							           return function(e) {
							    			jQuery(".img_portada_pet_fondo").css("background-image", "url("+e.target.result+")");
							    			jQuery(".img_portada_pet_normal").css("background-image", "url("+e.target.result+")");
							           };
							       })(f);
							       reader.readAsDataURL(f);
							   	}
							}      
								document.getElementById("portada_pet").addEventListener("change", vista_previa_pet, false);
							</script> 
                        ';
//                       $this->ScriptOutput = 'jQuery(".datepicker").datepicker();';
						/**
						*End: Pet detail Page Content
						**/
						break;
                        
					case 'dirpets':
						/**
						*Start: Profile Page Content
						**/
                        $formaction = 'pfpets_view_list';
                        $noncefield = wp_create_nonce($formaction);
                        $buttonid = 'pf-ajax-pets-view-list';
                        $buttontext = esc_html__('VER MIS MASCOTAS','pointfindert2d');
                        $this->ScriptOutput = '
                                    jQuery("#pfuaprofileform").prop("action","?ua=mypets");
				        ';
						break;
                        
					case 'delpet':
						/**
						*Start: Profile Page Content
						**/
                        $formaction = 'pfpet_delete_confirm';
                        $noncefield = wp_create_nonce($formaction);
                        $buttonid = 'pf-ajax-delete-confirm-button';
                        $buttontext = esc_html__('CONFIRMAR ELIMINACIÓN','pointfindert2d');
                        $this->FieldOutput .= '
                                    <h1>Eliminación de Mascota de lista del cuidador</h1><hr><br>
		                                  <section>
		                                    <label for="confirm_delete" class="lbl-text">
                                            <input type="hidden" name="pet_id" value="'.$_GET['id'].'">
                                            <input type="checkbox" name="confirm_delete" id="confirm_delete" value="1">
                                            <strong>'.esc_html__('Estoy realmente seguro de querer eliminar esta mascota','pointfindert2d').'</strong>.</label>
                                        </section>
                                    <br>';
                        $this->ScriptOutput = '
                                    jQuery("#pf-ajax-delete-confirm-button").prop("disabled",true);
                                    jQuery("#pfuaprofileform").prop("action","?ua=delpet");
                                    jQuery("#confirm_delete").on("click",function(e){
                                        if(jQuery(this).is(":checked")) jQuery("#pf-ajax-delete-confirm-button").prop("disabled",false);
                                    });
								';
						/**
						*End: Pictures list Page Content
						**/
						break;

					case 'myissues':
						/**
						*Start: My Pending Issues Page Content
						**/
                        $user_id = $current_user->ID;
                        $count = ($params['count']!='')? $params['count']: 0;
                        $this->FieldOutput .= '<div class="lista_pendientes" data-user="'.$user_id.'"><ul>';
                        if($count>0) {
                            $issues = explode(",",$params['list']);
                            $this->FieldOutput .= '<div class="inside">';
                            $this->FieldOutput .= '<ul>';
                            foreach($issues as $issue){ 
                                $this->FieldOutput .= '<li><a href="'.$params['detail_url'].$issue.'">'. get_the_title($issue).'</a></li>';
                            }
                            $this->FieldOutput .= '</ul>';
                            $this->FieldOutput .= '<br><p>Total: '.count($issues).' asuntos pendientes</p>';
                            $this->FieldOutput .= '</div>';
                        }
                        else $this->FieldOutput .=  '<p>No tienes ningún asunto pendiente</p>';
                        $this->FieldOutput .=  '</ul><br></div>';
						/**
						*End: My Pending Issues Page Content
						**/
						break;


					case 'mybookings':
						/**
						*Start: Bookings list Page Content
						**/

						include("./wp-content/themes/pointfinder/vlz/admin/dashboard/frontend/mybookings.php");

					break;
    
					case 'myservice':
						/**
						*Start: Service detail Page Content
						**/
                        $over_price = kmimos_get_over_price();
                        $formaction = 'pfupdate_my_service';
                        $noncefield = wp_create_nonce($formaction);
                        $buttonid = 'pf-ajax-update-service-button';
                        $buttontext = esc_html__('ACTUALIZAR SERVICIO','pointfindert2d');
                        $service_id = $params['service_id'];
                        $current_service = kmimos_get_service_info($service_id);


                        global $wpdb;
                        $cuidador = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE ID = '$service_id'" );
                        $servicio = explode("-", $cuidador->post_name);
                        $servicio = $servicio[0];
                        $metas = get_post_meta( $cuidador->post_parent );

                        $addons =  unserialize($current_service['addons']);

                        $metas_2 = array();
                        foreach ($metas as $key => $value) {
                        	$metas_2[$key] = $value[0];
                        }

                        $this->FieldOutput .= '
                        <style>
	                        .cell100 {width: 100%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important; vertical-align: top;}
	                        .cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important; vertical-align: top;}
	                        .cell33 {width: 33.33333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important; vertical-align: top;}
	                        .cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important; vertical-align: top;}
	                        .vlz_nombre_servicio{ border: solid 1px #848484; padding: 8px; padding-Bottom: 7px; }
	                        @media (max-width: 568px){ 
	                        	.cell50{width:100%!important;}
	                        	.cell25{width:50%!important;}
	                        }
                        </style>
                        <section>
                        	<input type="hidden" name="service_id" value="'.$service_id.'">
                            <div class="cell50">
                                <label for="service_category" class="lbl-text"><strong>'.esc_html__('Categoría del Servicio','pointfindert2d').'</strong>:</label>
                                <label class="lbl-ui">';

	                                if( $service_id == "" ){

				                        $cat = "";

	                                    $this->FieldOutput .= '<select name="service_category" class="input" id="service_category" name="service_category">';
	                        				if($service_id == ''){
	                        					$this->FieldOutput .= '<option value="">'.esc_html( 'Seleccione una opción','pointfindert2d' ).'</option>';
	                        				}
					                        $models = kmimos_get_product_models_of_services(); // Servicios de Cuidadores
					                        foreach ( $models as $model ) {
					                            $category = wp_get_post_terms($model->ID,'product_cat');

					                            $this->FieldOutput .= '<option value="'.$category[0]->term_id.'" data-model="'.$model->ID.'" data-desc="'.$model->post_excerpt.'"';
					                            if($category[0]->term_id == $current_service['category']) $this->FieldOutput .= ' selected';
					                            $this->FieldOutput .= '>'.esc_html( $category[0]->name ).'</option>
					                            ';
					                        } $this->FieldOutput .= '
	                                    </select>
	                                    <input type="hidden" name="service_model" id="service_model" value="">';
	                                	
	                                }else{

										$models = kmimos_get_product_models_of_services(); // Servicios de Cuidadores
				                        foreach ( $models as $model ) {
				                            $category = wp_get_post_terms($model->ID, 'product_cat');
				                            if( $category[0]->term_id == $current_service['category']){
				                            	$this->FieldOutput .= '
			                                		<div class="vlz_nombre_servicio">'.esc_html( $category[0]->name ).'</div>
			                                		<input type="hidden" name="service_model" id="service_model" value="'.$model->ID.'">
			                                	';
				                            }
				                        }

	                                }

                                    $this->FieldOutput .= '
                                </label>
                            </div>
                            <div class="cell50">
                                <label for="service_capacity" class="lbl-text">'.esc_html__('Capacidad de Mascotas','pointfindert2d').':</label>
                                <label class="lbl-ui">
                                    <input  type="text" name="service_capacity" class="input" value="'.$current_service['capacity'].'">
                                </label>
                            </div> 
                            <div class="cell100" style="display: none !important;">
                                <label for="short_description" class="lbl-text">'.esc_html__('Descripción Corta','pointfindert2d').':</label>
                                <label class="lbl-ui">
                                    <textarea name="short_description" id="short_description" class="textarea mini no-resize">'. $current_service['short'].'</textarea>
                                </label>                          
                            </div>
                        </section>
                        <h5>PRECIO SEGÚN TAMAÑO DE MASCOTA</h5>
                        <h6 style="color: red">* LOS PRECIO INDICADOS NO INCLUYEN LA COMISIÓN DE KMIMOS</h6>
                        <section>';
                        if($service_id!='') {
                            $tamanos = array(
                            	"pequeno" 	=> "PEQUEÑOS",
                            	"mediano" 	=> "MEDIANOS",
                            	"grande" 	=> "GRANDE",
                            	"gigante" 	=> "GIGANTES"
                            );

                            $i=0; $precios = unserialize(unserialize($metas_2["vlz_precio_{$servicio}"]));

                            foreach ($tamanos as $key => $value) {
                            	$precio = $precios[$i];
                            	$precio = ( ($precio*100) / 120 );
                            	$this->FieldOutput .= '
	                            <div class="cell25">
	                                <label for="price_size_'.$i.'" class="lbl-text">'.$value.':</label>
	                                <label class="lbl-ui">
	                                    <input  type="number" name="price_size_'.$i.'" class="input" value="'.$precio.'">
	                                </label>                          
	                            </div>';
	                            $i++;
                            }

                        } else {
                            $tamanos = array(
                            	"pequeno" 	=> "PEQUEÑOS",
                            	"mediano" 	=> "MEDIANOS",
                            	"grande" 	=> "GRANDE",
                            	"gigante" 	=> "GIGANTES"
                            );
                            $i=0;
                            foreach($tamanos as $key=>$value){
                                $this->FieldOutput .= '
                                <div class="cell25">
                                    <label for="price_size_'.$i.'" class="lbl-text">'.esc_html__('Mascotas ','pointfindert2d').$value.':</label>
                                    <label class="lbl-ui">
                                        <input  type="text" name="price_size_'.$i.'" class="input" value=""
                                    </label>                          
                                </div>';
	                            $i++;
                            }
                        }
                       	$this->FieldOutput .= '<h4 style="margin: 15px 0px 5px; font-weight: bold;">SERVICIOS ADICIONALES</h4>';
                        
		                $this->FieldOutput .= '     
                                <section>     
                                <h5>'.$addons[0]['name'].'</h5>
                                    <div class="cell100" style="display: none !important;">
                                        <label for="transportation_description" class="lbl-text">'.esc_html__('Descripción del Servicio','pointfindert2d').':</label>
                                        <label class="lbl-ui">
                                            <textarea name="transportation_description" class="textarea mini no-resize">'. $addons[0]['description'].'</textarea>
                                        </label>
                                    </div>';
                        $opciones = $addons[0]['options'];
                        foreach($opciones as $key=>$value){
                            $price = ($value['price']!='')? round($value['price']/$over_price):'';
                            $this->FieldOutput .= '
                                    <div class="cell33">
                                        <label for="price_transportation_'.$key.'" class="lbl-text">'.$value['label'].':</label>
                                        <label class="lbl-ui">
                                            <input  type="text" name="price_transportation_'.$key.'" class="input" value="'.$price.'">
                                        </label>
                                    </div>';                                    
                        }
		                $this->FieldOutput .= '     
                                </section>
                                <section>
                                <h5>'.$addons[1]['name'].'</h5>
                                    <div class="cell100" style="display: none !important;">
                                        <label for="aditional_description" class="lbl-text">'.esc_html__('Descripción del Servicio','pointfindert2d').':</label>
                                        <label class="lbl-ui">
                                            <textarea name="aditional_description" class="textarea mini no-resize">'. $addons[1]['description'].'</textarea>
                                        </label>
                                    </div>';
                        $opciones = $addons[1]['options'];
                        foreach($opciones as $key=>$value){
                            $price = ($value['price']!='')? round($value['price']/$over_price):'';
                            $this->FieldOutput .= '
                                    <div class="cell50">
                                        <label for="price_aditional_'.$key.'" class="lbl-text">'.$value['label'].':</label>
                                        <label class="lbl-ui">
                                            <input  type="text" name="price_aditional_'.$key.'" class="input" value="'.$price.'">
                                        </label>
                                    </div>';                                    
                        }
                        $this->FieldOutput .= '     
                                </section>';
                        if($service_id!='') $this->FieldOutput .= '
                                <section>
                                    <label for="delete_service" class="lbl-text" style="float: right;">
                                        <input type="checkbox" name="delete_service" value="1">
                                        <strong>'.esc_html__('Eliminar este servicio','pointfindert2d').'</strong>.
                                    </label>
                                </section>';
                        $this->ScriptOutput .= "
                        jQuery('#service_category').on('change',function(e){
                            jQuery('#short_description').html(jQuery('option:selected', this).attr('data-desc'));
                            jQuery('#service_model').val(jQuery('option:selected', this).attr('data-model'));
                        });
                        ";
						/**
						*End: Service detail Page Content
						**/
						break;

					case 'myservices':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_myservices.php");
					break;

					case 'mypictures':
						/**
						*Start: Pictures list Page Content
						**/								
							$current_user = get_user_by( 'id', $params['current_user'] ); 
                            $formaction = 'pfadd_new_picture';
                            $noncefield = wp_create_nonce($formaction);
							$buttonid = 'pf-ajax-add-picture-button';
							$buttontext = esc_html__('AGREGAR FOTO','pointfindert2d');
							$user_id = $current_user->ID;
                            $pics_count = ($params['count']!='')? $params['count']: 0;

                            //$this->FieldOutput .= '<div class="lista_fotos" data-user="'.$user_id.'">';
							$this->FieldOutput .= '<ul class="pfitemlists-content-elements pf3col" data-layout-mode="fitRows" style="position: relative; margin: 20px -15px 20px 0px;">
							';
                            $exist_file = false;
                            $tmp_user_id = $user_id;
							$path_galeria = "wp-content/uploads/cuidadores/galerias/".$tmp_user_id."/";
							$count_picture =0;
							if( is_dir($path_galeria) ){
								if ($dh = opendir($path_galeria)) { 
									$imagenes = array();
							        while (($file = readdir($dh)) !== false) { 
							            if (!is_dir($path_galeria.$file) && $file!="." && $file!=".."){ 
				                           $exist_file = true;
				                           $count_picture++;
// italocontenedor_pictures							               
									    $this->FieldOutput .= '
										<li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item">
											<div class="pflist-item" style="background-color:#ffffff;">  
												<div class="pflist-item-inners">
													<div class="pflist-imagecontainer pflist-subitem" style="
														background-image: url('.get_option('siteurl').'/'.$path_galeria.$file.')!important;
														background-size:contain;
														background-repeat:no-repeat;
														background-position:center;
													">
														<div class="vlz_postada_cuidador" style="height:160px;width:100%;background-color:transparent;"></div>
													</div>
													<a href="'.get_option('siteurl').'/perfil-usuario/?ua=mypicturesdel&p='.$file.'" style="color:red;">Eliminar</a>
												</div>
											</div>
										</li>';
							            } 
							        } 
							      	closedir($dh);
								}
							}					
                            if(!$exist_file)
                            {
                            	$this->FieldOutput .=  '<li><p>No tienes ninguna foto cargada</p></li>';
                            }
						    $this->FieldOutput .= '</ul>';
						if($count_picture >= 10){
							$hide_button = true;
                    	}

						$this->ScriptOutput .= "
                        jQuery('#pf-ajax-add-picture-button').on('click',function(e){
                            e.preventDefault();
                            jQuery('#pfuaprofileform').attr('action','?ua=newpicture');
                            jQuery('#pfuaprofileform').submit();
                        });
                        jQuery('.img_gallery').on('click',function(e){
                            e.preventDefault();
                            var id = jQuery(this).attr('data-id');
                            var num = jQuery(this).attr('title');
                            jQuery('#pfuaprofileform').attr('action','?ua=mypicture&id='+id+'&num='+num);
                            jQuery('#pfuaprofileform').submit();
                        });
                        ";                       
						/**
						*End: Pictures list Page Content
						**/
						break;
    
					case 'mypicture':
						/**
						*Start: Picture detail Page Content
						**/

                        $formaction = 'pfupdate_my_picture';
                        $noncefield = wp_create_nonce($formaction);
                        $buttonid = 'pf-ajax-update-picture-button';
                        $buttontext = esc_html__('ACTUALIZAR FOTO','pointfindert2d');
                        $picture_id = $params['picture_id'];
                        $pictures = $params['pictures_count'];

                        $this->FieldOutput .= '
	                        <section>
	                              <div class="photo-container" style="text-align: center;">
	                       		  '.wp_get_attachment_image($picture_id, array(800,600)).'
	                       		  </div>
	                        </section>';
	                    // italo nueva foto
                        if($_GET['ua']=='newpicture') $this->FieldOutput .= '     
								<style>
		                        	.cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
		                        	.cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
		                        	.cell33 {width: 33.333333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
		                        	.img_portada_picture{ position: relative; height: 400px; overflow: hidden; border: solid 1px #777; background: #EEE; }
		                        	.img_portada_picture_fondo{ position: absolute; top: -1px; left: -1px; width: calc( 100% + 2px ); height: 402px; z-index: 50; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: transparent; filter: blur(2px); transition: all .5s ease; }
		                        	.img_portada_picture_normal{ position: absolute; top: 0px; left: 0px; width: 100%; height: 380px; z-index: 100; background-size: contain; background-position: center; background-repeat: no-repeat; background-color: transparent; margin: 10px 0px; transition: all .5s ease; }
		                        	.cambiar_portada_picture{ position: absolute; bottom: 10px; right: 10px; width: auto; padding: 10px; font-size: 16px; color: #FFF; background: #000; border: solid 1px #777; z-index: 200; }
		                        	.cambiar_portada_picture input{ position: absolute; top: -24px; left: 0px; width: 100%; height: 167%; z-index: 100; opacity: 0; cursor: pointer; }
		                        	
		                        	.jj_dash_cel50{float: left; width: calc(50% - 9px);}
									.jj_dash2_cel50{float: right; width: calc(50% - 9px);}

									@media (max-width: 568px) { 
										.jj_dash_cel50{float: left; width: calc(100% - 9px);}
										.jj_dash2_cel50{float: left; width: calc(100% - 9px);} 
									}
		                        </style>
								<section>
									<div class="img_portada_picture">
		                                <div class="img_portada_picture_fondo" style="background-image: url('.$photo_picture.');"></div>
		                                <div class="img_portada_picture_normal" style="background-image: url('.$photo_picture.');"></div>
		                                <div class="cambiar_portada_picture">
		                                	Cargar Foto
		                                	<input type="file" id="portada_picture" name="petsitter_photo" accept="image/*" />
		                                </div>
                                	</div>
									<label for="userphoto" class="lbl-text">'.esc_html__('Dimensiones de la Foto: 800px x 600px (Recomendado, Ancho x Alto)','pointfindert2d').'<br>'.esc_html__('Tipos de archivos admitidos','pointfindert2d').': .jpg, .png, .gif<br>
									</label>                                	
                                </section>
								<script>
								function vista_previa_picture(evt) {
									var files = evt.target.files;
									for (var i = 0, f; f = files[i]; i++) {  
								   	if (!f.type.match("image.*")) {
								        continue;
								   	}
								   	var reader = new FileReader();
								   	reader.onload = (function(theFile) {
								       return function(e) {
											jQuery(".img_portada_picture_fondo").css("background-image", "url("+e.target.result+")");
											jQuery(".img_portada_picture_normal").css("background-image", "url("+e.target.result+")");
											jQuery("#portada_picture_base64").val(e.target.result);
								       };
								   })(f);
								   reader.readAsDataURL(f);
									}
								}      
								document.getElementById("portada_picture").addEventListener("change", vista_previa_picture, false);
								</script> 
								';

								// <section>
								// 	<label for="userphoto" class="lbl-text">'.esc_html__('Dimensiones de la Foto: 800px x 600px (Recomendado, Ancho x Alto)','pointfindert2d').'<br>'.esc_html__('Tipos de archivos admitidos','pointfindert2d').': .jpg, .png, .gif<br>
								// 	<span style="color: #ff0000; font-size: 1.2em">'.esc_html__('TAMAÑO MÁXIMO DEL ARCHIVO: 2MB','pointfindert2d').'</span></label>
								// 	<div class="col-lg-6" style="padding: 10px;">
								// 		<label for="petsitter_photo" class="lbl-ui file-input">
								// 		<input type="file" name="petsitter_photo" />
								// 	</div>
								// </section>

                        else $this->FieldOutput .= '     
		                               <section>
		                                    <label for="delete_picture" class="lbl-text" style="float: right;">
                                            <input type="checkbox" name="delete_picture" value="1">
                                            <strong>'.esc_html__('Eliminar esta foto','pointfindert2d').'</strong>.</label>
		                               </section>';
/*                        $this->FieldOutput .= '
                                    <br>';*/
						/**
						*End: Picture detail Page Content
						**/
						break;
  
					case 'cancelreq':
						/**
						*Start: Profile Page Content
						**/
								$formaction = 'pfbepetsitter_cancel_confirm';
								$noncefield = wp_create_nonce($formaction);
								$buttonid = 'pf-ajax-cancel-confirm-button';
								$buttontext = esc_html__('SOLICITAR CANCELACIÓN','pointfindert2d');
                            $this->FieldOutput .= '
                                    <h1>Cancelación de Postulación para ser cuidador</h1><hr><br>
		                                  <section>
		                                    <label for="confirm_cancel" class="lbl-text">
                                            <input type="checkbox" name="confirm_cancel" id="confirm_cancel" value="1">
                                            <strong>'.esc_html__('Estoy realmente seguro de querer cancelar mi postulación','pointfindert2d').'</strong>.</label>
                                        </section>
                                    <br>';
								$this->ScriptOutput = '
                                    jQuery("#pf-ajax-cancel-confirm-button").prop("disabled",true);
                                    jQuery("#confirm_cancel").on("click",function(e){
                                        if(jQuery(this).is(":checked")) jQuery("#pf-ajax-cancel-confirm-button").prop("disabled",false);
                                    });
								';
						/**
						*End: Pictures list Page Content
						**/
						break;


					case 'bepetsitter':
						/**
						*Start: Profile Page Content
						**/
								$current_user = get_user_by( 'id', $params['current_user'] ); 
								$user_id = $current_user->ID;
								$meta_user = get_user_meta($user_id);
                                $postulacion = $meta_user['petsitter_postulation'][0];
                        if($postulacion!=''){
								$formaction = 'pfbepetsitter_cancel_request';
								$noncefield = wp_create_nonce($formaction);
								$buttonid = 'pf-ajax-cancel-be-petsitter-button';
								$buttontext = esc_html__('CANCELAR POSTULACIÓN','pointfindert2d');
                            $estatus = 'Enviada';
                            $current_postulation = get_post($postulacion);
                            $fecha = $current_postulation->post_date;
                            $this->FieldOutput .= '
                                    <h1>Postulación para ser cuidador #'.$postulacion.'</h1><hr><br>
                                    <p>Estatus de solicitud: '.$estatus.'</p>
                                    <p>Fecha de envío: '.$fecha.'</p>
                                    <br>';
								$this->ScriptOutput = '
                                    jQuery("#pfuaprofileform").prop("action","?ua=cancelreq");
								';
                        }
                        else {
//print_r($meta_user);
								$formaction = 'pfbepetsitter_submit_request';
								$noncefield = wp_create_nonce($formaction);
								$buttonid = 'pf-ajax-be-petsitter-button';
								$buttontext = esc_html__('ENVIAR POSTULACIÓN','pointfindert2d');
				                $this->FieldOutput .= '
                                <style>
                                .cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                                .cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                                </style>';
                        
		                      $this->FieldOutput .= '
		                           <h1>Postularse para ser Cuidador</h1><hr><br>
		                           <p>Rellena los campos del siguiente formulario para postularte como Cuidador miembro de la gran famila Kmimos</p>
		                           <p>Un representante de Kmimos se pondrá en contacto contigo en las próximas 48 horas a través del correo certificacion.kmimos@desdigitec.com</p>
		                           <p>Recuerda revisar también tu bandeja de correos no deseados por si acaso te llegan ahí.</p>
		                           <p>Cualquier pregunta o dudas, llama al teléfono +57 315 849 2186</p><br>
		                           <h2>Formulario de Postulación para Cuidador</h2><hr><br>
		                           <div class="cell50">
		                           	   <section>
		                                    <label for="first_name" class="lbl-text"><strong>'.esc_html__('Nombres','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input type="hidden" name="user_id" value="'.$user_id.'" />
		                                    	<input type="hidden" name="username" value="'.$current_user->user_login.'" />
		                                    	<input type="text" name="first_name" class="input" value="'.$meta_user['first_name'][0].'" />
		                                    </label>
		                               </section>
                                    </div>';
		                      $this->FieldOutput .= '
		                           <div class="cell50">
		                           	   <section>
		                                    <label for="last_name" class="lbl-text"><strong>'.esc_html__('Apellidos','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="last_name" class="input" value="'.$meta_user['last_name'][0].'" />
		                                    </label>
		                               </section>
                                    </div>';
		                      $this->FieldOutput .= '
		                           <div class="cell50">
		                           	   <section>
		                                    <label for="email" class="lbl-text"><strong>'.esc_html__('Correo Electrónico','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="email" class="input" value="'.$current_user->user_email.'" />
		                                    </label>
		                               </section>
                                    </div>';
		                      $this->FieldOutput .= '
		                           <div class="cell25">
		                           	   <section>
		                                    <label for="mobile" class="lbl-text"><strong>'.esc_html__('Número de Celular','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="mobile" class="input" value="'.$meta_user->mobile.'" />
		                                    </label>
		                               </section>
                                    </div>';
		                      $this->FieldOutput .= '
		                           <div class="cell25">
		                           	   <section>
		                                    <label for="phone" class="lbl-text"><strong>'.esc_html__('Número de Teléfono','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="phone" class="input" value="'.$meta_user->phone.'" />
		                                    </label>
		                               </section>
                                    </div>';
                                $this->FieldOutput .= '
                                   <div class="cell25">
		                               <section>
		                                    <label for="birthdate" class="lbl-text"><strong>'.esc_html__('Fecha de nacimiento','pointfindert2d').':</strong></label>
		                                    <label class="lbl-ui">
		                                    	<input type="date" name="birthdate" class="input" value="'.$meta_user->birthdate.'" />
		                                    </label>
		                               </section>
		                           </div>';
                                $this->FieldOutput .= ' 
                                   <div class="cell25">
		                               <section>
		                                    <label for="gender" class="lbl-text"><strong>'.esc_html__('Género', 'pointfindert2d').':</strong></label>
		                                    <label class="lbl-ui">
		                                    	<select name="gender" class="input" />
                                                    <option value="">Favor Seleccione</option>';
                                $generos = array(1=>'Masculino', 2=>'Femenino');
                                foreach ( $generos as $key=>$value ) {
                                    $this->FieldOutput .= '<option value="'.$key.'"';
                                    if($key == $meta_user->gender) $this->FieldOutput .= ' selected';
                                    $this->FieldOutput .= '>'.$value.'</option>
                                    ';
                                }
								$this->FieldOutput .= '
                                                </select>
		                                    </label>
		                               </section>
		                           </div>';
		                      $this->FieldOutput .= '
		                           <div class="cell25">
		                           	   <section>
		                                    <label for="dni" class="lbl-text"><strong>'.esc_html__('Documento de Identidad','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="dni" class="input" value="'.$meta_user->dni.'" />
		                                    </label>
		                               </section>
                                    </div>';
                                $this->FieldOutput .= '
                                   <div class="cell25">
		                               <section>
		                                    <label for="startdate" class="lbl-text"><strong>'.esc_html__('Cuidando mascotas desde','pointfindert2d').':</strong></label>
		                                    <label class="lbl-ui">
		                                    	<input type="date" name="startdate" class="input datepicker" value="'.$meta_user->startdate.'" />
		                                    </label>
		                               </section>
		                           </div>';

                                $this->FieldOutput .= '
 		                               <section>
		                                    <label for="description" class="lbl-text">'.esc_html__('Descripción Corta','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<textarea name="description" class="textarea">'. $meta_user->description.'</textarea>
		                                    </label>                          
		                               </section>'; 
   
								$this->ScriptOutput = '
								';
                        }
						/**
						*End: New Item Page Content
						**/
						break;


					case 'profile':
						/**
						*Start: Profile Page Content
						**/
								$noncefield = wp_create_nonce('pfget_updateuserprofile');
								$formaction = 'pfget_updateuserprofile';
								$buttonid = 'pf-ajax-profileupdate-button';
								$buttontext = esc_html__('ACTUALIZAR INFORMACIÓN','pointfindert2d');
								$current_user = get_user_by( 'id', $params['current_user'] ); 
								$user_id = $current_user->ID;
								$usermetaarr = get_user_meta($user_id);
								$setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

								$stp_prf_vat = PFSAIssetControl('stp_prf_vat','','1');
								$stp_prf_country = PFSAIssetControl('stp_prf_country','','1');
								$stp_prf_address = PFSAIssetControl('stp_prf_address','','1');
								
								if(!isset($usermetaarr['first_name'])){$usermetaarr['first_name'][0] = '';}
								if(!isset($usermetaarr['last_name'])){$usermetaarr['last_name'][0] = '';}
								if(!isset($usermetaarr['user_referred'])){$usermetaarr['user_referred'][0] = '';}
								if(!isset($usermetaarr['user_phone'])){$usermetaarr['user_phone'][0] = '';}
								if(!isset($usermetaarr['user_mobile'])){$usermetaarr['user_mobile'][0] = '';}
								if(!isset($usermetaarr['description'])){$usermetaarr['description'][0] = '';}
								if(!isset($usermetaarr['nickname'])){$usermetaarr['nickname'][0] = '';}
								if(!isset($usermetaarr['user_twitter'])){$usermetaarr['user_twitter'][0] = '';}
								if(!isset($usermetaarr['user_facebook'])){$usermetaarr['user_facebook'][0] = '';}
								if(!isset($usermetaarr['user_googleplus'])){$usermetaarr['user_googleplus'][0] = '';}
								if(!isset($usermetaarr['user_linkedin'])){$usermetaarr['user_linkedin'][0] = '';}
								if(!isset($usermetaarr['user_vatnumber'])){$usermetaarr['user_vatnumber'][0] = '';}
								if(!isset($usermetaarr['user_country'])){$usermetaarr['user_country'][0] = '';}
								if(!isset($usermetaarr['user_address'])){$usermetaarr['user_address'][0] = '';}

								if(!isset($usermetaarr['user_photo'])){
									$usermetaarr['user_photo'][0] = '0';
								}

								$this->ScriptOutput = "
									$.pfAjaxUserSystemVars4 = {};
									$.pfAjaxUserSystemVars4.email_err = '".esc_html__('Por favor, escriba un correo electrónico','pointfindert2d')."';
									$.pfAjaxUserSystemVars4.email_err2 = '".esc_html__('Su dirección de correo electrónico debe estar en el formato de nombre@dominio.com','pointfindert2d')."';
									$.pfAjaxUserSystemVars4.nickname_err = '".esc_html__('Por favor, escriba apodo','pointfindert2d')."';
									$.pfAjaxUserSystemVars4.nickname_err2 = '".esc_html__('Por favor, introduzca al menos 3 caracteres por el apodo.','pointfindert2d')."';
									$.pfAjaxUserSystemVars4.passwd_err = '".esc_html__('Introduzca al menos 7 caracteres','pointfindert2d')."';
									$.pfAjaxUserSystemVars4.passwd_err2 = '".esc_html__('Introduzca la misma contraseña que el anterior','pointfindert2d')."';
								";

								$user = new WP_User( $user_id );

								$name_photo = get_user_meta($user_id, "name_photo", true);
							    if( $name_photo == "" ){ $name_photo = "0"; }
								if( file_exists("./wp-content/uploads/avatares/".$user_id."/{$name_photo}") ){
									$imagen = get_home_url()."/wp-content/uploads/avatares/".$user_id."/{$name_photo}";
								}elseif( file_exists("./wp-content/uploads/avatares/".$user_id."/{$name_photo}.jpg") ){
									$imagen = get_home_url()."/wp-content/uploads/avatares/".$user_id."/{$name_photo}.jpg";
								}else{
									$imagen = get_template_directory_uri().'/images/noimg.png';
								}
                                $referred = $usermetaarr['user_referred'][0];

                                $opciones = get_referred_list_options(); $ref_str = "";
                                foreach($opciones as $key=>$value){
                                    $selected = ($referred==$key)? ' selected':'';
                                    $ref_str .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
                                }

								$this->FieldOutput .= '
									<style>
			                        	.cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
			                        	.cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
			                        	.cell33 {width: 33.333333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
			                        	.img_portada{ position: relative; height: 400px; overflow: hidden; border: solid 1px #777; background: #EEE; }
			                        	.img_portada_fondo{ position: absolute; top: -1px; left: -1px; width: calc( 100% + 2px ); height: 402px; z-index: 50; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: transparent; filter: blur(2px); transition: all .5s ease; }
			                        	.img_portada_normal{ position: absolute; top: 0px; left: 0px; width: 100%; height: 380px; z-index: 100; background-size: contain; background-position: center; background-repeat: no-repeat; background-color: transparent; margin: 10px 0px; transition: all .5s ease; }
			                        	.cambiar_portada{ position: absolute; bottom: 10px; right: 10px; width: auto; padding: 10px; font-size: 16px; color: #FFF; background: #000; border: solid 1px #777; z-index: 200; }
			                        	.cambiar_portada input{ position: absolute; top: -24px; left: 0px; width: 100%; height: 167%; z-index: 100; opacity: 0; cursor: pointer; }
			                        	
			                        	.jj_dash_cel50{float: left; width: calc(50% - 9px);}
										.jj_dash2_cel50{float: right; width: calc(50% - 9px);}

										@media (max-width: 568px) { 
											.jj_dash_cel50{float: left; width: calc(100% - 9px);}
											.jj_dash2_cel50{float: left; width: calc(100% - 9px);} 
										}
			                        </style>

									<section>
										<input type="hidden" name="tipo_user" value="'.$user->roles[0].'" />
										<input type="hidden" name="user_tipo" value="'.$user_id_tipo.'" />
										<div class="img_portada">
			                                <div class="img_portada_fondo" style="background-image: url('.$imagen.');"></div>
			                                <div class="img_portada_normal" style="background-image: url('.$imagen.');"></div>
			                                <div class="cambiar_portada">
			                                	Cambiar Foto
			                                	<input type="file" id="portada" name="portada" accept="image/*" />
			                                </div>
	                                	</div>
	                                </section>

		                           	<div class="jj_dash_cel50">
		                           		<section>
		                                    <label for="firstname" class="lbl-text">'.esc_html__('Nombres','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="firstname" class="input" value="'.$usermetaarr['first_name'][0].'" />
		                                    </label>
		                                </section>
		                            </div>
		                            <div class="jj_dash2_cel50">  
		                                <section>
		                                    <label for="lastname" class="lbl-text">'.esc_html__('Apellidos','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="lastname" class="input" value="'.$usermetaarr['last_name'][0].'" />
		                                    </label>
		                                </section> 
		                            </div>
		                            <div class="jj_dash_cel50">
		                            <!--<div class="col6 first">-->
		                               	<section>
		                                    <label for="nickname" class="lbl-text"><strong>'.esc_html__('Apodo (Nombre a mostrar)','pointfindert2d').'(*)</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input  type="text" name="nickname" class="input" value="'.$usermetaarr['nickname'][0].'" />
		                                    </label>
		                                </section>  

		                                <section>
		                                    <label for="phone" class="lbl-text">'.esc_html__('Teléfono','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="tel" name="phone" maxlength="12" class="input" placeholder="" value="'.$usermetaarr['user_phone'][0].'" />
		                                    </label>                            
		                                </section> 
		                                <section>
		                                    <label for="mobile" class="lbl-text">'.esc_html__('Móvil','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="tel" name="mobile" maxlength="12" class="input" placeholder="" value="'.$usermetaarr['user_mobile'][0].'"/>
		                                    </label>                            
		                                </section> 

		                           </div>

		                           <div class="jj_dash2_cel50">
		                           <!--<div class="col6 last">-->
		                           		<section>
		                                    <label for="referred" class="lbl-text">'.esc_html__('¿Como nos conoció?','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<select name="referred" class="input">
                                                    <option value="">Por favor seleccione</option>
                                                    '.$ref_str.'
                                                </select>
		                                    </label>
		                                </section>  
                                                  
		                               	<section>
		                                    <label for="descr" class="lbl-text">'.esc_html__('Información biográfica','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<textarea name="descr" class="textarea mini" style="border:1px solid #848484; height: 112px;">'.$usermetaarr['description'][0].'</textarea>
		                                    </label>                          
		                               	</section> 
		                           	</div>
		                           	
		                           	
		                           	<div class="jj_dash_cel50">
		                           		<section>
		                                    <label for="username" class="lbl-text"><strong>'.esc_html__('Usuario','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" id="username" class="input" value="'.$current_user->user_login.'" disabled />
		                                    </label>
		                               </section>
		                            </div>
		                            <div class="jj_dash2_cel50">
		                               <section>
		                                    <label for="email" class="lbl-text"><strong>'.esc_html__('Email Address','pointfindert2d').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input  type="email" id="email" class="input" value="'.$current_user->user_email.'" disabled />
		                                    </label>
		                                </section> 
		                           	</div>
		                           	<div class="jj_dash_cel50">
			                           	<section>
		                                    <label for="password" class="lbl-text">'.esc_html__('Nueva contraseña','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="password" 
												placeholder="Contraseña" 
												autocomplete="off" name="password" id="password" class="input" />
		                                    </label>                          
		                               	</section> 

		                           	</div>
		                           	<div class="jj_dash2_cel50">
		                           		<section>
		                                    <label for="password2" class="lbl-text">'.esc_html__('Repita la nueva contraseña','pointfindert2d').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="password" placeholder="Repita la Contraseña" autocomplete="off" name="password2" class="input" />
		                                    </label>                          
		                               	</section> 

		                           	</div>

		                          	<script>
								      	function vista_previa(evt) {
									      	var files = evt.target.files;
									      	for (var i = 0, f; f = files[i]; i++) {  
									           	if (!f.type.match("image.*")) {
									                continue;
									           	}
									           	var reader = new FileReader();
									           	reader.onload = (function(theFile) {
									               return function(e) {
					                        			jQuery(".img_portada_fondo").css("background-image", "url("+e.target.result+")");
					                        			jQuery(".img_portada_normal").css("background-image", "url("+e.target.result+")");
									               };
									           })(f);
									           reader.readAsDataURL(f);
									       	}
										}      
								      	document.getElementById("portada").addEventListener("change", vista_previa, false);
							      	</script> 
					            ';

					            if ($setup4_membersettings_paymentsystem == 2) {
									/*Get user meta*/
									$membership_user_activeorder = get_user_meta( $user_id, 'membership_user_activeorder', true );
									$membership_user_recurring = get_user_meta( $user_id, 'membership_user_recurring', true );
									$recurring_status = esc_attr(get_post_meta( $membership_user_activeorder, 'pointfinder_order_recurring',true));
									if($recurring_status == 1 && $membership_user_recurring == 1){
										$this->FieldOutput .= '
											<div class="row"><div class="col12">
											<hr/>
											<div class="col8 first">
											<section>
			                                    <label for="recurring" class="lbl-text" style="margin-top:12px"><strong>'.esc_html__('Perfil Recurrente','pointfindert2d').'</strong>:</label>
			                                    <label class="lbl-ui">
											<p>'.__("Está utilizando PayPal pagos periódicos. Si desea actualizar su plan de membresía por favor cancele esta opción. Tenga cuidado de que esta acción no se puede deshacer.",'pointfindert2d').'</p></label></section></div>
											<div class="col4 last"><section style="text-align:right;margin-top: 35px;">
			                                    	<a class="pf-dash-cancelrecurring" title="'.esc_html__('Esta opción para cancelar perfil de pagos recurrentes.','pointfindert2d').'">'.esc_html__('Cancelar Perfil Recurrente','pointfindert2d').'</a></section></div>
			                                    
			                            	</div></div>';
			                        }
								}
				        /**
						*End: Profile Page Content
						**/
						break;

					case 'myitems':
						/**
						*Start: My Items Page Content
						**/
							$formaction = 'pf_refineitemlist';
							$noncefield = wp_create_nonce($formaction);
							$buttonid = 'pf-ajax-itemrefine-button';
							$setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
							$setup20_paypalsettings_paypal_price_short = PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','$');
								$setup20_paypalsettings_paypal_price_pref = PFSAIssetControl('setup20_paypalsettings_paypal_price_pref','',1);

							if ($params['redirect']) {
								echo '<script>window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems'.'";</script>';
							}
							
							/**
							*Start: Content Area
							**/
//								$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
								$setup3_pointposttype_pt1 = 'pets';
								$setup3_pointposttype_pt7 = PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');

								/*User Limits*/
								$setup31_userlimits_useredit = PFSAIssetControl('setup31_userlimits_useredit','','1');
								$setup31_userlimits_userdelete = PFSAIssetControl('setup31_userlimits_userdelete','','1');
								$setup31_userlimits_useredit_pending = PFSAIssetControl('setup31_userlimits_useredit_pending','','1');
								$setup31_userlimits_userdelete_pending = PFSAIssetControl('setup31_userlimits_userdelete_pending','','1');

								$setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','1');
								$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
								$setup31_userpayments_featuredoffer = PFSAIssetControl('setup31_userpayments_featuredoffer','','1');



								$this->FieldOutput .= '<div class="pfmu-itemlisting-container pfmu-itemlisting-container-new">';
									if ($params['fields']!= '') {
										$fieldvars = $params['fields'];
									}else{
										$fieldvars = '';
									}

									$selected_lfs = $selected_lfl = $selected_lfo2 = $selected_lfo = '';

									if (PFControlEmptyArr($fieldvars)) {
										
			                            if(isset($fieldvars['listing-filter-status'])){
			                           		if ($fieldvars['listing-filter-status'] != '') {
			                           			$selected_lfs = $fieldvars['listing-filter-status'];
			                           		}
			                            }

				                        if(isset($fieldvars['listing-filter-ltype'])){
				                       		if ($fieldvars['listing-filter-ltype'] != '') {
				                       			$selected_lfl = $fieldvars['listing-filter-ltype'];
				                       		}
				                        }

			                            if(isset($fieldvars['listing-filter-orderby'])){
			                           		if ($fieldvars['listing-filter-orderby'] != '') {
			                           			$selected_lfo = $fieldvars['listing-filter-orderby'];
			                           		}
			                            }

			                            if(isset($fieldvars['listing-filter-order'])){
			                           		if ($fieldvars['listing-filter-order'] != '') {
			                           			$selected_lfo2 = $fieldvars['listing-filter-order'];
			                           		}
			                            }

									}

									$current_user = wp_get_current_user();
									$user_id = $current_user->ID;

									$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
									if (empty($paged)) {
										$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
									}

									$output_args = array(
											'post_type'	=> $setup3_pointposttype_pt1,
											'author' => $user_id,
											'posts_per_page' => 10,
											'paged' => $paged,
											'order'	=> 'DESC',
											'orderby' => 'ID'
										);

									if($selected_lfs != ''){$output_args['post_status'] = $selected_lfs;}
									if($selected_lfo != ''){$output_args['orderby'] = $selected_lfo;}
									if($selected_lfo2 != ''){$output_args['order'] = $selected_lfo2;}
									if($selected_lfl != ''){
										$output_args['tax_query']=
											array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'pointfinderltypes',
													'field' => 'id',
													'terms' => $selected_lfl,
													'operator' => 'IN'
												)
											);
									}

									

									if($params['post_id'] != ''){
										$output_args['p'] = $params['post_id'];
									}

									$output_loop = new WP_Query( $output_args );

									/**
									*Header for search
									**/
										
										if($params['sheader'] != 'hide'){
											
											$this->FieldOutput .= '<section><div class="row">';
/*
// Status
                                            $this->FieldOutput .= '<div class="col1-5 first">';
												$this->FieldOutput .= '<label for="listing-filter-status" class="lbl-ui select">
					                              <select id="listing-filter-status" name="listing-filter-status">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Status','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfs == 'publish') ? '<option value="publish" selected>'.esc_html__('Published','pointfindert2d').'</option>' : '<option value="publish">'.esc_html__('Published','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfs == 'pendingapproval') ? '<option value="pendingapproval" selected>'.esc_html__('Pending Approval','pointfindert2d').'</option>' : '<option value="pendingapproval">'.esc_html__('Pending Approval','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfs == 'pendingpayment') ? '<option value="pendingpayment" selected>'.esc_html__('Pending Payment','pointfindert2d').'</option>' : '<option value="pendingpayment">'.esc_html__('Pending Payment','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfs == 'rejected') ? '<option value="rejected" selected>'.esc_html__('Rejected','pointfindert2d').'</option>' : '<option value="rejected">'.esc_html__('Rejected','pointfindert2d').'</option>';
					                               
					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';
// Tipos de Listados
					                        $this->FieldOutput .= '<div class="col1-5 first">';
												$this->FieldOutput .= '<label for="listing-filter-ltype" class="lbl-ui select">
					                              <select id="listing-filter-ltype" name="listing-filter-ltype">
					                                <option value="">'.$setup3_pointposttype_pt7.'</option>
					                                ';
					                                 
					                                $fieldvalues = get_terms('pointfinderltypes',array('hide_empty'=>false)); 
													foreach( $fieldvalues as $fieldvalue){
														
														$this->FieldOutput  .= ($selected_lfl == $fieldvalue->term_id) ? '<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>' : '<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';	
														
													}

					                                $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';
/**/

					                        $this->FieldOutput .= '<div class="col1-5">';
												$this->FieldOutput .= '<label for="listing-filter-orderby" class="lbl-ui select">
					                              <select id="listing-filter-orderby" name="listing-filter-orderby">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Ordenar Por','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'title') ? '<option value="title" selected>'.esc_html__('Título','pointfindert2d').'</option>' : '<option value="title">'.esc_html__('Título','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'date') ? '<option value="date" selected>'.esc_html__('Fecha','pointfindert2d').'</option>' : '<option value="date">'.esc_html__('Fecha','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'ID') ? '<option value="ID" selected>'.esc_html__('ID','pointfindert2d').'</option>' : '<option value="ID">'.esc_html__('ID','pointfindert2d').'</option>';


					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';

					                        $this->FieldOutput .= '<div class="col1-5">';
												$this->FieldOutput .= '<label for="listing-filter-order" class="lbl-ui select">
					                              <select id="listing-filter-order" name="listing-filter-order">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Orden','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo2 == 'ASC') ? '<option value="ASC" selected>'.esc_html__('ASC','pointfindert2d').'</option>' : '<option value="ASC">'.esc_html__('ASC','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo2 == 'DESC') ? '<option value="DESC" selected>'.esc_html__('DESC','pointfindert2d').'</option>' : '<option value="DESC">'.esc_html__('DESC','pointfindert2d').'</option>';

					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';

					                        

					                        $this->FieldOutput .= '<div class="col1-5 last">';
												$this->FieldOutput .= '<button type="submit" value="" id="'.$buttonid.'" class="button blue pfmyitempagebuttons" title="'.esc_html__('Buscar','pointfindert2d').'"  ><i class="pfadmicon-glyph-627"></i></button>';
												$this->FieldOutput .= '<a class="button pfmyitempagebuttons" style="margin-left:4px;" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems" title="'.esc_html__('REINICIO','pointfindert2d').'"><i class="pfadmicon-glyph-825"></i></a>';
												$this->FieldOutput .= '<a class="button pfmyitempagebuttons" style="margin-left:4px;" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newpet" title="'.esc_html__('AÑADIR NUEVA MASCOTA','pointfindert2d').'"><i class="pfadmicon-glyph-722"></i></a>';
											$this->FieldOutput .= '</div></div></section>';
										}


									if ( $output_loop->have_posts() ) {
										/**
										*Start: Column Headers
										**/
										$setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
										$this->FieldOutput .= '<section>';

										$this->FieldOutput .= '<div class="pfhtitle pf-row clearfix hidden-xs">';
											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfmu-itemlisting-htitlenc col-lg-1 col-md-1 col-sm-2 hidden-xs">';
											
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 hidden-xs">';
											$this->FieldOutput .= esc_html__('Información','pointfindert2d');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
											$this->FieldOutput .= $setup3_pointposttype_pt7s;
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
											$this->FieldOutput .= esc_html__('Publicado en','pointfindert2d');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle col-lg-3 col-md-3 col-sm-2">';
											$this->FieldOutput .= '</div>';
										/**
										*End: Column Headers
										**/
										$this->FieldOutput .= '</div>';

										while ( $output_loop->have_posts() ) {
											$output_loop->the_post(); 

											$author_post_id = get_the_ID();
												
												
												
													/*Post Meta Info*/
													global $wpdb;
													if ($setup4_membersettings_paymentsystem == 2) {
														$current_user = wp_get_current_user();
														$user_id = $current_user->ID;
														$result_id = get_user_meta( $user_id, 'membership_user_activeorder', true );
													}else{
														$result_id = $wpdb->get_var( $wpdb->prepare( 
															"
																SELECT post_id
																FROM $wpdb->postmeta 
																WHERE meta_key = %s and meta_value = %s
															", 
															'pointfinder_order_itemid',
															$author_post_id
														) );
													}
													
													if ($setup4_membersettings_paymentsystem == 2) {
														$pointfinder_order_datetime = PFU_GetPostOrderDate($author_post_id);
													} else {
														$pointfinder_order_datetime = PFU_GetPostOrderDate($result_id);
													}
													
													
													$pointfinder_order_datetime = PFU_Dateformat($pointfinder_order_datetime);
													
													$pointfinder_order_datetime_approval = esc_attr(get_post_meta( $result_id, 'pointfinder_order_datetime_approval', true ));
													$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
													$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
													$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
													$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
													$pointfinder_order_frecurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_frecurring', true ));
													$pointfinder_order_expiredate = esc_attr(get_post_meta( $result_id, 'pointfinder_order_expiredate', true ));
													$pointfinder_order_bankcheck = esc_attr(get_post_meta( $result_id, 'pointfinder_order_bankcheck', true ));

													$featured_enabled = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_item_featuredmarker', true ));

													$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
													

													if($pointfinder_order_expiredate != ''){
														$item_listing_expiry = PFU_Dateformat($pointfinder_order_expiredate);
													}else{
														$item_listing_expiry = '';
													}
												
													$item_recurring_text = ($pointfinder_order_recurring == 1)? '('.esc_html__('Periódico','pointfindert2d').')' : '';


													$status_of_post = get_post_status($author_post_id);

													$status_of_order = get_post_status($result_id);

													switch ($status_of_post) {
														case 'pendingpayment':
															if ($status_of_order == 'pfsuspended') {
																$status_text = sprintf(esc_html__('Suspendido (Requiere la activación de Paypal)','pointfindert2d'));
																$status_payment = 1;
																$status_icon = 'pfadmicon-glyph-411';
																$status_lbl = 'lblpending';
															}else{
																if ($setup4_membersettings_paymentsystem == 2) {
																	$status_text = esc_html__('Suspendido','pointfindert2d');
																} else {
																	if ($setup20_paypalsettings_paypal_price_pref == 1) {
																		$pf_price_output = $setup20_paypalsettings_paypal_price_short.$pointfinder_order_price;
																	}else{
																		$pf_price_output = $pointfinder_order_price.$setup20_paypalsettings_paypal_price_short;
																	}
																	if ($pointfinder_order_price == 0) {
																		$status_text = sprintf(esc_html__('Pago pendiente %s Por favor, modifique este elemento y cambie el plan.','pointfindert2d'),'<br/>');
																	}else{
																		$status_text = sprintf(esc_html__('Pago pendiente (%s)','pointfindert2d'),$pf_price_output);
																	}
																}
																$status_payment = 0;
																$status_icon = 'pfadmicon-glyph-411';
																$status_lbl = 'lblpending';
															}
															
															break;
														
														case 'rejected':
															$status_text = esc_html__('Rechazado','pointfindert2d');
															$status_payment = 1;
															$status_icon = 'pfadmicon-glyph-411';
															$status_lbl = 'lblcancel';
															break;

														case 'pendingapproval':
															$status_text = esc_html__('Aprobación Pendiente','pointfindert2d');
															$status_payment = 1;
															$status_icon = 'pfadmicon-glyph-411';
															$status_lbl = 'lblpending';
															break;

														case 'publish':
															if ($setup4_membersettings_paymentsystem == 2) {
																$status_text = esc_html__('Activo','pointfindert2d');
															} else {
																$status_text = sprintf(esc_html__('Activo hasta: %s','pointfindert2d'),$item_listing_expiry);
															}
															$status_payment = 1;
															$status_icon = 'pfadmicon-glyph-411';
															$status_lbl = 'lblcompleted';
															break;
													}


													/*
														Reviews Store in $review_output:
													*/
														$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
														if ($setup11_reviewsystem_check == 1) {
															global $pfitemreviewsystem_options;
															$setup11_reviewsystem_criterias = $pfitemreviewsystem_options['setup11_reviewsystem_criterias'];
															$review_status = PFControlEmptyArr($setup11_reviewsystem_criterias);

															if($review_status != false){
																$review_output = '';
																$setup11_reviewsystem_singlerev = PFREVSIssetControl('setup11_reviewsystem_singlerev','','0');
																$criteria_number = pf_number_of_rev_criteria();
																$return_results = pfcalculate_total_review($author_post_id);
																if ($return_results['totalresult'] > 0) {
																	
																	$review_output .= '<span class="pfiteminfolist-infotext pfreviews" title="'.esc_html__('Revisiones','pointfindert2d').'"><i class="pfadmicon-glyph-631"></i>';
																		$review_output .=  $return_results['totalresult'].' (<a title="'.esc_html__('Revisión Total','pointfindert2d').'" style="cursor:pointer">'.pfcalculate_total_rusers($author_post_id).'</a>)';
																	$review_output .= '</span>';
																}else{
																	
																	$review_output .= '<span class="pfiteminfolist-infotext pfreviews" title="'.esc_html__('Revisiones','pointfindert2d').'"><i class="pfadmicon-glyph-631"></i>';
																		$review_output .=  '0 (<a title="'.esc_html__('Revisión Total','pointfindert2d').'" style="cursor:pointer">0</a>)';
																	$review_output .= '</span>';
																}
															}
														}else{
															$review_output = '';
														}

													/*
														Favorites Store in $fav_output:
													*/
														$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
														if($setup4_membersettings_favorites == 1){
															$fav_number = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_items_favorites', true ));
															$fav_number = ($fav_number == false) ? '0' : $fav_number ;
															$fav_output = '';
															if ($fav_number > 0) {
																$fav_output .= '<span class="pfiteminfolist-title pfstatus-title pfreviews" title="'.esc_html__('Favoritos','pointfindert2d').'"><i class="pfadmicon-glyph-376"></i> </span>';
																$fav_output .= '<span class="pfiteminfolist-infotext pfreviews">';
																	$fav_output .=  $fav_number;
																$fav_output .= '</span>';
															}else{
																$fav_output .= '<span class="pfiteminfolist-title pfstatus-title pfreviews" title="'.esc_html__('Favoritos','pointfindert2d').'"><i class="pfadmicon-glyph-376"></i></span>';
																$fav_output .= '<span class="pfiteminfolist-infotext pfreviews">0</span>';
															}
														}else{
															$fav_output = '';
														}

													/*
														View Count for item.
													*/
														$view_count_num = esc_attr(get_post_meta($author_post_id,"webbupointfinder_page_itemvisitcount",true));
														if (!empty($view_count_num)) {
															$view_outputx = $view_count_num;
														}else{
															$view_outputx = 0;
														}
														$view_output = '<span class="pfiteminfolist-title pfstatus-title pfreviews" title="'.esc_html__('Vistas','pointfindert2d').'"><i class="pfadmicon-glyph-729"></i></span>';
														$view_output .= '<span class="pfiteminfolist-infotext pfreviews">'.$view_outputx.'</span>';
					
													$setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','1');


												$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pfmu-itemlisting-inner'.$author_post_id.' pf-row clearfix">';
														
														if (get_post_status($author_post_id) == 'publish') {
															$permalink_item = get_permalink($author_post_id);
														}else{
															$permalink_item = '#';
														}

														/*Item Photo Area*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-photo col-lg-1 col-md-1 col-sm-2 hidden-xs">';
															if ( has_post_thumbnail()) {
															   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(),'full');
															   $this->FieldOutput .= '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" rel="prettyPhoto">';
															   $this->FieldOutput .= '<img src="'.aq_resize($large_image_url[0],60,60,true).'" alt="" />';
															   $this->FieldOutput .= '</a>';
															}else{
															   $this->FieldOutput .= '<a href="#" style="border:1px solid #efefef">';
															   $this->FieldOutput .= '<img src="'.get_template_directory_uri().'/images/noimg.png'.'" alt="" />';
															   $this->FieldOutput .= '</a>';
															}
														$this->FieldOutput .= '</div>';



														/* Item Title */
														$this->FieldOutput .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pfmu-itemlisting-title-wd">';
														$this->FieldOutput .= '<div class="pfmu-itemlisting-title">';
														$this->FieldOutput .= '<a href="'.$permalink_item.'">'.get_the_title().'</a>';
														$this->FieldOutput .= '</div>';


														/*Status*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
															$this->FieldOutput .= '<ul class="pfiteminfolist">';



																/** Basic & Featured Listing Setting **/																
																$this->FieldOutput .= '<li>';
																/*$this->FieldOutput .= '<span class="pfiteminfolist-title pfstatus-title">'.esc_html__('Listing Status','pointfindert2d').' '.$item_recurring_text.'  : </span>';*/
															
																
																if($status_payment == 1 && $status_of_post == 'pendingapproval'){
																	$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'"><a href="javascript:;" class="info-tip info-tipex" aria-describedby="helptooltip"> <i class="'.$status_icon.'"></i> <span role="tooltip">'.esc_html__('Este artículo está a la espera para su aprobación. Por favor, sea paciente mientras este proceso continúa.','pointfindert2d').'</span></a>';
																}else{
																	if (empty($item_listing_expiry) && $status_of_post == 'publish') {
																		$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'">';
																	}else{
																		$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'"><i class="'.$status_icon.'"></i>';
																	}
																}
																if (empty($item_listing_expiry) && $status_of_post == 'publish') {
																	$this->FieldOutput .= '</span>';
																}else{
																	$this->FieldOutput .= ' '.$status_text.'</span>';
																}
																
																$this->FieldOutput .= '</li>';

																/** Basic & Featured Listing Setting **/


																
																
																
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';
														$this->FieldOutput .= '</div>';

														
														
														/*Type of item*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';														
																$this->FieldOutput .= '<li><strong>'.get_the_term_list( $author_post_id, 'pointfinderltypes', '<ul class="pointfinderpflistterms"><li>', ',</li><li>', '</li></ul>' ).'</strong></li>';

																
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';

														/*Date Creation*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';
																$this->FieldOutput .= '<li>'.$pointfinder_order_datetime.'</li>';
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';



														


														/*Item Footer*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-3 col-md-3 col-sm-2 col-xs-12">';
													    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist">';

													    if ($this->PF_UserLimit_Check('delete',$status_of_post) == 1) {
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-delete-item-button wpf-transition-all pf-itemdelete-link" data-pid="'.$author_post_id.'" id="pf-delete-item-'.$author_post_id.'" title="'.esc_html__('Borrar','pointfindert2d').'"><i class="pfadmicon-glyph-644"></i></a></li>';
														}
														
														if($status_of_post == 'publish'){
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.$permalink_item.'" title="'.esc_html__('Vista','pointfindert2d').'"><i class="pfadmicon-glyph-410"></i></a></li>';
														}

														if ($this->PF_UserLimit_Check('edit',$status_of_post) == 1 && $status_of_order != 'pfsuspended') {
															
															$show_edit_button = 1;

															if ($setup4_membersettings_paymentsystem == 2 && $status_of_post == 'pendingpayment') {
																$show_edit_button = 0;
															}
															if ($show_edit_button == 1) {
																$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-edit-item-button wpf-transition-all" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=edititem&i='.$author_post_id.'" title="'.esc_html__('Editar','pointfindert2d').'"><i class="pfadmicon-glyph-685"></i></a></li>';
															} 
															
														}


														$this->FieldOutput .= '</ul>';

														if ($setup4_membersettings_paymentsystem != 2) {
															$stp31_userfree = PFSAIssetControl("stp31_userfree","","0");


															$pointfinder_order_listingpid = get_post_meta($result_id, "pointfinder_order_listingpid",true);
															$package_price_check = pointfinder_get_package_price_ppp($pointfinder_order_listingpid);
															
															$ip_process = true;

															if (empty($package_price_check) && !empty($pointfinder_order_expiredate) && $status_of_post == 'pendingpayment' && $stp31_userfree == 0) {
																$ip_process = false;
															}


															if ($ip_process) {
																if ($status_payment == 0 && $pointfinder_order_price != 0) {

													            	$this->FieldOutput .= '<div class="pfmu-payment-area golden-forms pf-row clearfix">';

													            	if($pointfinder_order_bankcheck == 0){

														            	$this->FieldOutput .= '<label for="paymenttype" class="lbl-text">'.esc_html__('PAGAR CON:','pointfindert2d');
														            		if($pointfinder_order_recurring == 1){
														            			$this->FieldOutput .= '<a href="javascript:;" class="info-tip info-tipex" aria-describedby="helptooltip" style="background-color:#b00000"> ? <span role="tooltip">'.esc_html__('Los pagos recurrentes no son compatibles TRANSFERENCIA BANCARIA Y PAGOS CON TARJETA DE CRÉDITO.','pointfindert2d').'</span></a>';
														            		}
														            		$this->FieldOutput .= '</label>';

														            
														            	$this->FieldOutput .= '<div class="col-lg-7 col-md-7 col-sm-12 col-xs-8">';
															            	
															                $this->FieldOutput .= '<label class="lbl-ui select">';
															            
																	        	$this->FieldOutput .= '<select name="paymenttype">';
																	        		if (PFSAIssetControl('setup20_paypalsettings_paypal_status','','1') == 1) {	
																	        			if ($pointfinder_order_recurring == 1 || $pointfinder_order_frecurring == 1) {
					        																$this->FieldOutput .= '<option value="paypal">'.esc_html__('PAYPAL RECURRENTE','pointfindert2d').'</option>';
					        															}else{
					        																$this->FieldOutput .= '<option value="paypal">'.esc_html__('PAYPAL','pointfindert2d').'</option>';
					        															}
																		       			
																		       		}
																		       		if(($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFSAIssetControl('setup20_paypalsettings_bankdeposit_status','',0) == 1){
																		       			$this->FieldOutput .= '<option value="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_pay2&i='.$author_post_id.'">'.esc_html__('TRANSFERENCIA BANCARIA','pointfindert2d').'</option>';
																		       		}
																		       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFSAIssetControl('setup20_stripesettings_status','','0') == 1) {
																		       			$this->FieldOutput .= '<option value="creditcard">'.esc_html__('TARJETA DE CRÉDITO','pointfindert2d').'</option>';
																		       		}

																		        $this->FieldOutput .= '</select>';
																		        
																	        $this->FieldOutput .= '</label>';

																        $this->FieldOutput .= '</div>';

																        $this->FieldOutput .= '<div class="col-lg-5 col-md-5 col-sm-12 col-xs-4">';
														            		$this->FieldOutput .= '<a class="button buttonpaymentb pfbuttonpaymentb" data-pfitemnum="'.$author_post_id.'" title="'.esc_html__('Instrucciones para el pago','pointfindert2d').'">'.esc_html__('PAGO','pointfindert2d').'</a>';
														            	$this->FieldOutput .= '</div>';
														            }else{
														            	$this->FieldOutput .= '<div class="col-lg-12">';
														            		$this->FieldOutput .= '<div class="pfcanceltext">';
														            		$this->FieldOutput .= '<label class="lbl-text"><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_pay2c&i='.$author_post_id.'">'.esc_html__('CANCELAR TRANSFERENCIA','pointfindert2d').'</a> ';
														            		$this->FieldOutput .= '<a href="javascript:;" class="info-tip info-tipex" aria-describedby="helptooltip" style="background-color:#b00000"> ? <span role="tooltip">'.esc_html__('Esperando Transferencia bancaria, pero se puede cancelar esta transferencia y hacer el pago con otro método de pago.','pointfindert2d').'</span></a>';
														            		$this->FieldOutput .= '</label>';
														            		$this->FieldOutput .= '</div>';
														            	$this->FieldOutput .= '</div>';
														            }

														            $this->FieldOutput .= '</div>';
													           
													        	}elseif ($status_payment == 0 && $pointfinder_order_price == 0 && $stp31_userfree == 1) {
													        		/*If user is free user then extend it free.*/
													        		$this->FieldOutput .= '<div class="col-lg-12">';
														            		$this->FieldOutput .= '<div class="pfcanceltext">';
														            		$this->FieldOutput .= '<label class="lbl-text">
														            		<a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_extend&i='.$author_post_id.'" class="button buttonrenewpf" title="'.esc_html__('Haga clic para renovar (Ampliar)','pointfindert2d').'"><i class="pfadmicon-glyph-486"></i> '.esc_html__('RENOVAR','pointfindert2d').'</a>';
														            		$this->FieldOutput .= '</label>';
														            		$this->FieldOutput .= '</div>';
														            	$this->FieldOutput .= '</div>';
													        	}
															}else{
																$this->FieldOutput .= '<div class="pfmu-payment-area golden-forms pf-row clearfix">';
																$this->FieldOutput .= '<label for="paymenttype" class="lbl-text">'.esc_html__('Notificacion de pago ','pointfindert2d');
																$this->FieldOutput .= '<a href="javascript:;" class="info-tip info-tipex" aria-describedby="helptooltip" style="background-color:#b00000"> ? <span role="tooltip">
																'.esc_html__('Si desea ampliar esta lista, por favor, editar y cambiar el paquete.','pointfindert2d').'</span></a></label>';
																$this->FieldOutput .= '</div>';
															}

															
												        }

														$this->FieldOutput .= '</div>';

													

													$this->FieldOutput .= '</div>';
													$this->FieldOutput .= '<div class="pf-listing-item-inner-addinfo">
													<ul>';
														/** Reviews: show **/
														$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
														if($setup4_membersettings_favorites == 1 && !empty($review_output)){
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= $review_output;
															$this->FieldOutput .= '</li>';
														}

														/** Favorites: show **/
														$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
														if ($setup11_reviewsystem_check == 1 && !empty($fav_output)) {
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= $fav_output;
															$this->FieldOutput .= '</li>';
														}

														/** View: show **/
														$this->FieldOutput .= '<li>';
														$this->FieldOutput .= $view_output;
														$this->FieldOutput .= '</li>';

														if ($featured_enabled == 1) {
															$pf_featured_exptime = get_post_meta( $result_id, 'pointfinder_order_expiredate_featured', true );
															if ($pf_featured_exptime != false) {
																$pf_featured_exptime = sprintf(esc_html__('Destacado hasta %s','pointfindert2d'),PFU_Dateformat($pf_featured_exptime));
															}else{
																$pf_featured_exptime = esc_html__('Destacado','pointfindert2d');
															}
															/** Featured: show **/
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= '<span class="pfiteminfolist-title pfstatus-title pffeaturedbuttondash" title="'.$pf_featured_exptime.'"><i class="pfadmicon-glyph-379"></i></span>';
															$this->FieldOutput .= '</li>';
														}

														$is_listing_recurring = get_post_meta($result_id, 'pointfinder_order_recurring', true );
														if ($is_listing_recurring == false) {
															$is_listing_recurring = get_post_meta($result_id, 'pointfinder_order_frecurring', true );
														}
														if ($is_listing_recurring != false) {
															/** Recurring: show **/
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= '<span class="pfiteminfolist-title pfstatus-title pfrecurringbuttonactive" title="'.esc_html__('Pago Recurrente','pointfindert2d').'"><i class="pfadmicon-glyph-655"></i></span>';
															$this->FieldOutput .= '</li>';
														}

														/** on/off: show **/
														$current_status_onoff = get_post_meta( $author_post_id, "pointfinder_item_onoffstatus", true );
														if (empty($current_status_onoff)) {
															$onoff_text = 'pfstatusbuttonactive';
															$onoff_word = esc_html__("activar","pointfindert2d" );
														}else{
															$onoff_text = 'pfstatusbuttondeactive';
															$onoff_word = esc_html__("desactivar","pointfindert2d" );
														}

														$this->FieldOutput .= '<li>';
														$this->FieldOutput .= '<span data-pfid="'.$author_post_id.'" class="pfiteminfolist-title pfstatus-title '.$onoff_text.' pfstatusbuttonaction" title="'.sprintf(esc_html__("Su anuncio es %s.",'pointfindert2d'),$onoff_word).'"><i class="pfadmicon-glyph-348"></i></span>';
														$this->FieldOutput .= '</li>';

													$this->FieldOutput .= '
													</ul>
													</div>';
												
										}

										$this->FieldOutput .= '</section>';
									}else{
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
										if (PFControlEmptyArr($fieldvars)) {
											$this->FieldOutput .= '<strong>'.esc_html__('No se encontró el registro!','pointfindert2d').'</strong><br>'.esc_html__('Por favor, revise sus criterios de búsqueda y tratar de comprobar de nuevo. O puede presionar el botón <strong>Reiniciar</strong> para ver todos los artículos.','pointfindert2d').'</p></div>';
										}else{
											$this->FieldOutput .= '<strong>'.esc_html__('No hay registros!','pointfindert2d').'</strong><br>'.esc_html__('Si ve este error primera vez por favor subir nuevos artículos a la lista en esta página.','pointfindert2d').'</p></div>';
										}
										$this->FieldOutput .= '</section>';
									}
									$this->FieldOutput .= '<div class="pfstatic_paginate" >';
									$big = 999999999;
									$this->FieldOutput .= paginate_links(array(
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max(1, $paged),
										'total' => $output_loop->max_num_pages,
										'type' => 'list',
									));
									$this->FieldOutput .= '</div>';
									wp_reset_postdata();

								$this->FieldOutput .= '</div>';

							/**
							*End: Content Area
							**/
						/**
						*End: My Items Page Content
						**/
						break;

					case 'errorview':
						/**
						*Start: Error Page Content
						**/
							
							
						/**
						*End: Error Page Content
						**/
						break;

					case 'banktransfer':
						/**
						*Start: Bank Transfer Page Content
						**/
							$this->FieldOutput .= '<div class="pf-banktransfer-window">';

								$this->FieldOutput .= '<span class="pf-orderid-text">';
								$this->FieldOutput .= esc_html__('Su pedido ID:','pointfindert2d').' '.$params['post_id'];
								$this->FieldOutput .= '</span>';

								$this->FieldOutput .= '<span class="pf-order-text">';
								global $pointfindertheme_option;
								$setup20_bankdepositsettings_text = ($pointfindertheme_option['setup20_bankdepositsettings_text'])? wp_kses_post($pointfindertheme_option['setup20_bankdepositsettings_text']):'';
								$this->FieldOutput .= $setup20_bankdepositsettings_text;
								$this->FieldOutput .= '</span>';

							$this->FieldOutput .= '</div>';
							
						/**
						*End: Bank Transfer Page Content
						**/
						break;

					case 'favorites':
						$formaction = 'pf_refinefavlist';
						$noncefield = wp_create_nonce($formaction);
						$buttonid = 'pf-ajax-itemrefine-button';

						/**
						*Start: Favorites Page Content
						**/
							
							$user_favorites_arr = get_user_meta( $params['current_user'], 'user_favorites', true );

                            if (!empty($user_favorites_arr)) {
								$user_favorites_arr = json_decode($user_favorites_arr,true);
							}else{
								$user_favorites_arr = array();
							}


							$output_arr = '';
							$countarr = count($user_favorites_arr);
							
							if($countarr>0){
								// Contenedor de los favoritos del usuario
								$this->FieldOutput .= '<div class="pfmu-itemlisting-container">';
									
									if ($params['fields']!= '') {
										$fieldvars = $params['fields'];
									}else{
										$fieldvars = '';
									}

									$selected_lfs = $selected_lfl = $selected_lfo2 = $selected_lfo = '';

									$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
									if (empty($paged)) {
										$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
									}

//									$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
									$setup3_pointposttype_pt1 = 'petsitters';
									$setup3_pointposttype_pt7 = PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');

									if (PFControlEmptyArr($fieldvars)) {

				                        if(isset($fieldvars['listing-filter-ltype'])){
				                       		if ($fieldvars['listing-filter-ltype'] != '') {
				                       			$selected_lfl = $fieldvars['listing-filter-ltype'];
				                       		}
				                        }

			                            if(isset($fieldvars['listing-filter-orderby'])){
			                           		if ($fieldvars['listing-filter-orderby'] != '') {
			                           			$selected_lfo = $fieldvars['listing-filter-orderby'];
			                           		}
			                            }

			                            if(isset($fieldvars['listing-filter-order'])){
			                           		if ($fieldvars['listing-filter-order'] != '') {
			                           			$selected_lfo2 = $fieldvars['listing-filter-order'];
			                           		}
			                            }

									}

									$user_id = $params['current_user'];


									$output_args = array(
											'post_type'	=> $setup3_pointposttype_pt1,
											'posts_per_page' => 10,
											'paged' => $paged,
											'order'	=> 'ASC',
											'orderby' => 'Title',
											'post__in' => $user_favorites_arr
									);

									if($selected_lfs != ''){$output_args['post_status'] = $selected_lfs;}
									if($selected_lfo != ''){$output_args['orderby'] = $selected_lfo;}
									if($selected_lfo2 != ''){$output_args['order'] = $selected_lfo2;}
									if($selected_lfl != ''){
										$output_args['tax_query']=
											array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'pointfinderltypes',
													'field' => 'id',
													'terms' => $selected_lfl,
													'operator' => 'IN'
												)
											);
									}

									

									if($params['post_id'] != ''){
										$output_args['p'] = $params['post_id'];
									}

									$output_loop = new WP_Query( $output_args );
									
									/**
									*START: Header for search
									**/
/*										
										if($params['sheader'] != 'hide'){
											
											$this->FieldOutput .= '<section><div class="row">';
												
/* Selecciona tipo de listado
					                        $this->FieldOutput .= '<div class="col3 first">';
												$this->FieldOutput .= '<label for="listing-filter-ltype" class="lbl-ui select">
					                              <select id="listing-filter-ltype" name="listing-filter-ltype">
					                                <option value="">'.$setup3_pointposttype_pt7.'</option>
					                                ';
					                                 
					                                $fieldvalues = get_terms('pointfinderltypes',array('hide_empty'=>false)); 
													foreach( $fieldvalues as $fieldvalue){
														
														$this->FieldOutput  .= ($selected_lfl == $fieldvalue->term_id) ? '<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>' : '<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';	
														
													}

					                                $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';
*/
/*
					                        $this->FieldOutput .= '<div class="col3">';
												$this->FieldOutput .= '<label for="listing-filter-orderby" class="lbl-ui select">
					                              <select id="listing-filter-orderby" name="listing-filter-orderby">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Ordenar Por','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'title') ? '<option value="title" selected>'.esc_html__('Título','pointfindert2d').'</option>' : '<option value="title">'.esc_html__('Título','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'date') ? '<option value="date" selected>'.esc_html__('Fecha','pointfindert2d').'</option>' : '<option value="date">'.esc_html__('Fecha','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'ID') ? '<option value="ID" selected>'.esc_html__('ID','pointfindert2d').'</option>' : '<option value="ID">'.esc_html__('ID','pointfindert2d').'</option>';


					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';

					                        $this->FieldOutput .= '<div class="col3">';
												$this->FieldOutput .= '<label for="listing-filter-order" class="lbl-ui select">
					                              <select id="listing-filter-order" name="listing-filter-order">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Orden','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo2 == 'ASC') ? '<option value="ASC" selected>'.esc_html__('ASC','pointfindert2d').'</option>' : '<option value="ASC">'.esc_html__('ASC','pointfindert2d').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo2 == 'DESC') ? '<option value="DESC" selected>'.esc_html__('DESC','pointfindert2d').'</option>' : '<option value="DESC">'.esc_html__('DESC','pointfindert2d').'</option>';

					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';

					                        

					                        $this->FieldOutput .= '<div class="col3 last">';
												$this->FieldOutput .= '<button type="submit" value="" id="'.$buttonid.'" class="button blue pfmyitempagebuttons" title="'.esc_html__('Buscar','pointfindert2d').'"  ><i class="pfadmicon-glyph-627"></i></button>';
												$this->FieldOutput .= '<a class="button pfmyitempagebuttons" style="margin-left:4px;" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites" title="'.esc_html__('REINICIAR','pointfindert2d').'"><i class="pfadmicon-glyph-825"></i></a>';
											$this->FieldOutput .= '</div></div></section>';
										}

									/**
									*END: Header for search
									**/

									if ( $output_loop->have_posts() ) {
										
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="pfhtitle pf-row clearfix hidden-xs" style="border-top-width: 0px !important;">';

										$setup3_pointposttype_pt4 = PFSAIssetControl('setup3_pointposttype_pt4s','','Item Type');
										$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
										$setup3_pointposttype_pt5 = PFSAIssetControl('setup3_pointposttype_pt5s','','Location');
										$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
										$setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
										/**
										*Start: Column Headers
										**/
/*
											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfmu-itemlisting-htitlenc col-lg-1 col-md-1 col-sm-2 hidden-xs">';
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 hidden-xs">';
											$this->FieldOutput .= esc_html__('Information','pointfindert2d');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
											$this->FieldOutput .= $setup3_pointposttype_pt7s;
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
												
												if($setup3_pointposttype_pt5_check == 1){
													$this->FieldOutput .= $setup3_pointposttype_pt5;
												}else{
													if($setup3_pointposttype_pt4_check == 1){
														$this->FieldOutput .= $setup3_pointposttype_pt4;
													}
												}
											
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfmu-itemlisting-htitlenc col-lg-3 col-md-3 col-sm-2">';
											$this->FieldOutput .= '</div>';
										/**
										*End: Column Headers
										**/

										$this->FieldOutput .= '</div>';

										while ( $output_loop->have_posts() ) {
											$output_loop->the_post(); 

											$author_post_id = get_the_ID();
											$xuser_id = get_the_author_ID();
												
												$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pf-row clearfix">';
														
														$permalink_item = get_permalink($author_post_id);
														$name_photo = get_user_meta($xuser_id, "name_photo", true);
													    if( $name_photo == "" ){ $name_photo = "0"; }
														if( file_exists("./wp-content/uploads/avatares/".$xuser_id."/{$name_photo}") ){
															$user_photo_field_output = get_home_url()."/wp-content/uploads/avatares/".$xuser_id."/{$name_photo}";
														}elseif( file_exists("./wp-content/uploads/avatares/".$xuser_id."/{$name_photo}.jpg") ){
															$user_photo_field_output = get_home_url()."/wp-content/uploads/avatares/".$xuser_id."/{$name_photo}.jpg";
														}else{
															$user_photo_field_output = get_template_directory_uri().'/images/noimg.png';
														}
														// $name_photo = get_user_meta($xuser_id, "name_photo", true);
							       //                      if( $name_photo == "" ){ $name_photo = "0"; }
														// if( file_exists("./wp-content/uploads/avatares/".$xuser_id."/{$name_photo}") ){
														// 	$user_photo_field_output = get_home_url()."/wp-content/uploads/avatares/".$xuser_id."/{$name_photo}";
														// }else{
														// 	$user_photo_field_output = get_template_directory_uri().'/images/noimg.png';
														// }

														/*Item Photo Area*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-photo col-lg-1 col-md-1 col-sm-2 hidden-xs">';
															$this->FieldOutput .= '<a href="' . $user_photo_field_output . '" title="' . the_title_attribute('echo=0') . '" rel="prettyPhoto">';
														   	$this->FieldOutput .= '<img src="'.$user_photo_field_output.'" alt="" />';
														   	$this->FieldOutput .= '</a>';
														$this->FieldOutput .= '</div>';



														/* Item Title */
														$this->FieldOutput .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pfmu-itemlisting-title-wd">';
														$this->FieldOutput .= '<div class="pfmu-itemlisting-title">';
														$this->FieldOutput .= '<a href="'.$permalink_item.'">'.get_the_title().'</a>';
														$this->FieldOutput .= '</div>';


														/*Other Infos*/
														$output_data = PFIF_DetailText_ld($author_post_id);
														$rl_pfind = '/pflistingitem-subelement pf-price/';
														$rl_pfind2 = '/pflistingitem-subelement pf-onlyitem/';
					                                    $rl_preplace = 'pf-fav-listing-price';
					                                    $rl_preplace2 = 'pf-fav-listing-item';
					                                    $mcontent = preg_replace( $rl_pfind, $rl_preplace, $output_data);
					                                    $mcontent = preg_replace( $rl_pfind2, $rl_preplace2, $mcontent );

					                                    if (isset($mcontent['content'])) {
					                                    	$this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
						                                    $this->FieldOutput .= $mcontent['content'];
															$this->FieldOutput .= '</div>';
					                                    }

					                                    if (isset($mcontent['priceval'])) {
					                                    	$this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
						                                    $this->FieldOutput .= $mcontent['priceval'];
															$this->FieldOutput .= '</div>';
					                                    }

					                                    $this->FieldOutput .= '</div>';
														

														
														
														/*Type of item*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';														
																$this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderltypes').'</li>';
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';

														/*Location*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-3 col-md-3 col-sm-2 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';
																if($setup3_pointposttype_pt5_check == 1){
																	$this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderlocations').'</li>';
																}else{
																	if($setup3_pointposttype_pt4_check == 1){
																		$this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderitypes').'</li>';
																	}
																}
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';



														


														/*Item Footer*/
															
														
														$fav_check = 'true';
														$favtitle_text = esc_html__('Remover de favoritos','pointfindert2d');
														
														
														
														$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-2 col-md-2 col-sm-2 col-xs-12">';
													    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist">';
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-delete-item-button wpf-transition-all pf-favorites-link" data-pf-num="'.$author_post_id.'" data-pf-active="'.$fav_check.'" data-pf-item="false" title="'.$favtitle_text.'"><i class="pfadmicon-glyph-644"></i></a></li>';
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.$permalink_item.'" title="'.esc_html__('Vista','pointfindert2d').'"><i class="pfadmicon-glyph-410"></i></a></li>';
														$this->FieldOutput .= '</ul>';
														
														$this->FieldOutput .= '</div>';


													$this->FieldOutput .= '</div>';

												
										}

										$this->FieldOutput .= '</section>';
									}else{
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
										if (PFControlEmptyArr($fieldvars)) {
											$this->FieldOutput .= '<strong>'.esc_html__('No se encontró el registro!','pointfindert2d').'</strong><br>'.esc_html__('Por favor, revise sus criterios de búsqueda y tratar de comprobar de nuevo. O puede presionar el botón <strong>Reiniciar</ strong> para ver todas.','pointfindert2d').'</p></div>';
										}else{
											$this->FieldOutput .= '<strong>'.esc_html__('No se encontró el registro!','pointfindert2d').'</strong></p></div>';
										}
										$this->FieldOutput .= '</section>';
									}
									$this->FieldOutput .= '<div class="pfstatic_paginate" >';
									$big = 999999999;
									$this->FieldOutput .= paginate_links(array(
										//'base' => @add_query_arg('page','%#%'),
										//'format' => '?page=%#%',
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max(1, $paged),
										'total' => $output_loop->max_num_pages,
										'type' => 'list',
									));
									$this->FieldOutput .= '</div>';
									

								$this->FieldOutput .= '</div>';
							}else{
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>'.esc_html__('No se encontró el registro!','pointfindert2d').'</p></div>';
								$this->FieldOutput .= '</section>';
							}
						/**
						*End: Favorites Page Content
						**/
						break;

					case 'reviews':
						$formaction = 'pf_refinerevlist';
						$noncefield = wp_create_nonce($formaction);
						$buttonid = 'pf-ajax-revrefine-button';

						/**
						*Start: Reviews Page Content
						**/
							/*Post Meta Info*/
							global $wpdb;
							$results = $wpdb->get_results( $wpdb->prepare( 
								"
									SELECT ID
									FROM $wpdb->posts
									WHERE post_type = '%s' and post_author = %d
								", 
								'pointfinderreviews',
								$params['current_user']
							),'ARRAY_A' );

							function pf_arraya_2_array($aval = array()){
								$aval_output = array();
								foreach ($aval as $aval_single) {

									$aval_output[] = (isset($aval_single['ID']))? $aval_single['ID'] : '';
								}
								return $aval_output;
							}
							$results = pf_arraya_2_array($results);

							$output_arr = '';
							$countarr = count($results);

							
							if($countarr>0){
								
								$this->FieldOutput .= '<div class="pfmu-itemlisting-container">';

									$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
									if (empty($paged)) {
										$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
									}

									
									$user_id = $params['current_user'];


									$output_args = array(
											'post_type'	=> 'pointfinderreviews',
											'posts_per_page' => 10,
											'paged' => $paged,
											'order'	=> 'DESC',
											'orderby' => 'Date',
											'post__in' => $results
									);


									$output_loop = new WP_Query( $output_args );
									/*
									print_r($output_loop->query).PHP_EOL;
									echo $output_loop->request.PHP_EOL;
									*/
									

									if ( $output_loop->have_posts() ) {
										
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="pfhtitle pf-row clearfix hidden-xs">';

										$setup3_pointposttype_pt4 = PFSAIssetControl('setup3_pointposttype_pt4s','','Item Type');
										$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
										$setup3_pointposttype_pt5 = PFSAIssetControl('setup3_pointposttype_pt5s','','Location');
										$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
										$setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
										/**
										*Start: Column Headers
										**/
											
											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 hidden-xs">';
											$this->FieldOutput .= esc_html__('Título','pointfindert2d');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
											$this->FieldOutput .= esc_html__('Revisión','pointfindert2d');
											$this->FieldOutput .= '</div>';

											
											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 hidden-xs">';
											$this->FieldOutput .= esc_html__('Fecha','pointfindert2d');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfmu-itemlisting-htitlenc col-lg-2 col-md-2 col-sm-2">';
											$this->FieldOutput .= '</div>';
										/**
										*End: Column Headers
										**/

										$this->FieldOutput .= '</div>';

										while ( $output_loop->have_posts() ) {
											$output_loop->the_post(); 

											$author_post_id = get_the_ID();
											$item_post_id = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_review_itemid', true ));

												$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pf-row clearfix">';
														

														/* Item Title */
														$this->FieldOutput .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pfmu-itemlisting-title-wd">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list" style="padding-left:10px">';
																$this->FieldOutput .= '<a href="'.get_permalink($item_post_id).'">'.get_the_title($item_post_id).'</a>';
															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';


					                                    /* Review Title */
														$this->FieldOutput .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pfmu-itemlisting-title-wd">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list">';

																	
																		$review_output = '';
																		$return_results = pfcalculate_single_review($author_post_id);
																		
																		if (!empty($return_results)) {
																			$review_output .= '<span class="pfiteminfolist-infotext pfreviews" style="padding-left:10px">';
																				$review_output .=  $return_results;
																			$review_output .= '</span>';
																		}else{
																			$review_output .= ''.esc_html__('Revisiones','pointfindert2d').' : ';
																			$review_output .= '<span class="pfiteminfolist-infotext pfreviews" style="padding-left:10px">';
																				$review_output .=  '0 (<a title="'.esc_html__('Review Total','pointfindert2d').'" style="cursor:pointer">0</a>)';
																			$review_output .= '</span>';
																		}
																	
																$this->FieldOutput .= $review_output;

															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';

														
														
														/*Type of item*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-4 col-md-4 col-sm-4 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';														
																$this->FieldOutput .= '<li>'.sprintf( esc_html__('%1$s at %2$s', 'pointfindert2d'), get_the_date(),  get_the_time()).'</li>';
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';


														/*Item Footer*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-2 col-md-2 col-sm-2 col-xs-12">';
													    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist" style="padding-left:10px">';
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.get_permalink($item_post_id).'" title="'.esc_html__('Vista','pointfindert2d').'"><i class="pfadmicon-glyph-410"></i></a></li>';
														$this->FieldOutput .= '</ul>';
														
														$this->FieldOutput .= '</div>';


													$this->FieldOutput .= '</div>';

												
										}

										$this->FieldOutput .= '</section>';
									}else{
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
										
										$this->FieldOutput .= esc_html__('No se encontró el registro!','pointfindert2d').'</p></div>';
										
										$this->FieldOutput .= '</section>';
									}
									$this->FieldOutput .= '<div class="pfstatic_paginate" >';
									$big = 999999999;
									$this->FieldOutput .= paginate_links(array(
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max(1, $paged),
										'total' => $output_loop->max_num_pages,
										'type' => 'list',
									));
									$this->FieldOutput .= '</div>';
									wp_reset_postdata();

								$this->FieldOutput .= '</div>';
							}else{
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>'.esc_html__('No se encontró el registro!','pointfindert2d').'</p></div>';
								$this->FieldOutput .= '</section>';
							}
						/**
						*End: Reviews Page Content
						**/
						break;

					case 'invoices':
						$formaction = 'pf_refineinvlist';
						$noncefield = wp_create_nonce($formaction);
						$buttonid = 'pf-ajax-invrefine-button';

						
						break;

				}

			/**
			*Start: Page Footer Actions / Divs / Etc...
			**/
				$this->FieldOutput .= '</div>';/*row*/
				$this->FieldOutput .= '</div>';/*form-section*/
				$this->FieldOutput .= '</div>';/*form-enclose*/

				
				if($params['formtype'] != 'myitems' && $params['formtype'] != 'myissues' && $params['formtype'] != 'mybookings' && $params['formtype'] != 'favorites' && $params['formtype'] != 'reviews'){$xtext = '';}else{$xtext = 'style="background:transparent;background-color:transparent;display:none!important"';}
				


				$this->FieldOutput .= '
				<div class="pfalign-right" '.$xtext.'>';
				if($params['formtype'] != 'errorview' && $params['formtype'] != 'banktransfer'){
					if($params['formtype'] != 'myitems' && $params['formtype'] != 'favorites' && $params['formtype'] != 'reviews' && $params['formtype'] != 'invoices' && $params['dontshowpage'] != 1 && $main_package_expire_problem != true){
			            
			                //if( $_GET['ua'] != "myservices"){

			                	$this->FieldOutput .='    
				                <section '.$xtext.'> ';
				                if($params['formtype'] == 'upload'){
					                $setup31_userpayments_recurringoption = PFSAIssetControl('setup31_userpayments_recurringoption','','1');
					         
				                }elseif ($params['formtype'] == 'edititem') {
				                	
				                	$this->FieldOutput .='
					                   <input type="hidden" name="edit_pid" value="'.$params['post_id'].'">';
				                }
				                if ($main_package_purchase_permission == true || $main_package_upgrade_permission == true) {
				                	$this->FieldOutput .='<input type="hidden" name="selectedpackageid" value="">';
				                }elseif ($main_package_renew_permission == true && !empty($membership_user_package_id)) {
				                	$this->FieldOutput .='<input type="hidden" name="selectedpackageid" value="'.$membership_user_package_id.'">';
				                }
				                if ($main_package_renew_permission == true) {
				                	$this->FieldOutput .='<input type="hidden" name="subaction" value="r">';
				                }elseif ($main_package_purchase_permission == true) {
				                	$this->FieldOutput .='<input type="hidden" name="subaction" value="n">';
				                }elseif ($main_package_upgrade_permission == true) {
				                	$this->FieldOutput .='<input type="hidden" name="subaction" value="u">';
				                }

				                $this->FieldOutput .= '
				                   <input type="hidden" value="'.$formaction.'" name="action" />
				                   <input type="hidden" value="'.$noncefield.'" name="security" />
				                   ';
				                if (!$hide_button) {
				                	$this->FieldOutput .= '
					                   <input type="submit" value="'.$buttontext.'" id="'.$buttonid.'" class="button blue pfmyitempagebuttonsex" data-edit="'.$params['post_id'].'"  />
				                   ';
				                }

				                $this->FieldOutput .= '
					                </section>  
					            ';

			                //}
			                
		         	}else{
		       			$this->FieldOutput .='    
			                <section  '.$xtext.'> 
			                   <input type="hidden" value="'.$formaction.'" name="action" />
			                   <input type="hidden" value="'.$noncefield.'" name="security" />
			                </section>  
			            ';
		       		}
		       	}
	        
	            $this->FieldOutput.='              
	            </div>
				';
				
				$this->FieldOutput .= '</form>';
				$this->FieldOutput .= '</div>';/*golden-forms*/
			/**
			*End: Page Footer Actions / Divs / Etc...
			**/


		}

		/**
		*Start: Class Functions
		**/
			public function PFGetList($params = array())
			{
			    $defaults = array( 
			        'listname' => '',
			        'listtype' => '',
			        'listtitle' => '',
			        'listsubtype' => '',
			        'listdefault' => '',
			        'listgroup' => 0,
			        'listgroup_ex' => 1,
			        'listmultiple' => 0,
			        'parentonly' => 0
			    );
				
			    $params = array_merge($defaults, $params);
			    	
			    	$output_options = '';
			    	if($params['listmultiple'] == 1){ $multiplevar = ' multiple';$multipletag = '[]';}else{$multiplevar = '';$multipletag = '';};

			    	if ($params['parentonly'] == 1) {
			    		$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false,'parent'=>0));
			    	}else{
			    		$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false));
			    	}
					 

					
					if($params['listgroup'] == 1){
						foreach( $fieldvalues as $parentfieldvalue){
							if($parentfieldvalue->parent == 0){
								$output_options .=  '<optgroup label="'.$parentfieldvalue->name.'">';
								
									if ($params['listgroup_ex'] == 1) {
								
										if(is_array($params['listdefault'])){
											if(in_array($parentfieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValuex = 1;}else{ $fieldtaxSelectedValuex = 0;}
										}else{
											if(strcmp($params['listdefault'],$parentfieldvalue->term_id) == 0){ $fieldtaxSelectedValuex = 1;}else{ $fieldtaxSelectedValuex = 0;}
										}
										if($fieldtaxSelectedValuex == 1){
											$output_options .= '<option value="'.$parentfieldvalue->term_id.'" selected>'.$parentfieldvalue->name.' ('.esc_html__('Todos','pointfindert2d').')</option>';
										}else{
											$output_options .= '<option value="'.$parentfieldvalue->term_id.'">'.$parentfieldvalue->name.' ('.esc_html__('Todos','pointfindert2d').')</option>';
										}
									}
									foreach( $fieldvalues as $fieldvalue){
										if($fieldvalue->parent == $parentfieldvalue->term_id){
											if($params['listdefault'] != ''){
												if(is_array($params['listdefault'])){
													if(in_array($fieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
												}else{
													if(strcmp($params['listdefault'],$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
												}
											}else{
												$fieldtaxSelectedValue = 0;
											}
											
											if($fieldtaxSelectedValue == 1){
												$output_options .= '	<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>';
											}else{
												$output_options .= '	<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';
											}
										}
									}
									
								$output_options .= '</optgroup>';
							
							}
						}
					}else{
						foreach( $fieldvalues as $fieldvalue){
							if($fieldvalue->parent != 0){$hasparentitem = ' ';}else{$hasparentitem = '';}
							if($params['listdefault'] != ''){
								if(is_array($params['listdefault'])){
									if(in_array($fieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
								}else{
									if(strcmp($params['listdefault'],$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
								}
							}else{
								$fieldtaxSelectedValue = 0;
							}
							
							if($fieldtaxSelectedValue == 1){
								$output_options .= '	<option value="'.$fieldvalue->term_id.'" selected>'.$hasparentitem.$fieldvalue->name.'</option>';
							}else{
								$output_options .= '	<option value="'.$fieldvalue->term_id.'">'.$hasparentitem.$fieldvalue->name.'</option>';
							}
									
						}
					}
					


			    	$output = '';
					$output .= '<div class="pf_fr_inner" data-pf-parent="">';
		   			
			   		switch ($params['listtype']) {
			   			/**
			   			*Listing Types,Item Types,Locations,Features
			   			**/
			   			case 'listingtypes':
			   			case 'itemtypes':
			   			case 'locations':
			   			case 'features':
			   			case 'conditions':
			   				if (!empty($params['listtitle'])) {
				   				$output .= '<label for="'.$params['listname'].'" class="lbl-text">'.$params['listtitle'].':</label>';
			   				}

			   				$as_mobile_dropdowns = PFASSIssetControl('as_mobile_dropdowns','','0');

							if ($as_mobile_dropdowns == 1) {
								$as_mobile_dropdowns_text = 'class="pf-special-selectbox"';
							} else {
								$as_mobile_dropdowns_text = '';
							}
							
			   				$output .= '
			                <label class="lbl-ui select">
			                <select'.$multiplevar.' name="'.$params['listname'].$multipletag.'" id="'.$params['listname'].'" '.$as_mobile_dropdowns_text.'>';
			                $output .= '<option></option>';
			                $output .= $output_options.'
			                </select>
			                </label>';
			   			break;
			   		}

			   		$output .= '</div>';

	            return $output;
			}

			private function PFValidationCheckWrite($field_validation_check,$field_validation_text,$itemid){
				
				$itemname = (string)trim($itemid);
				$itemname = (strpos($itemname, '[]') == false) ? $itemname : "'".$itemname."'" ;

				if($field_validation_check == 1){
					if($this->VSOMessages != ''){
						$this->VSOMessages .= ','.$itemname.':"'.$field_validation_text.'"';
					}else{
						$this->VSOMessages = $itemname.':"'.$field_validation_text.'"';
					}

					if($this->VSORules != ''){
						$this->VSORules .= ','.$itemname.':"required"';
					}else{
						$this->VSORules = $itemname.':"required"';
					}
				}
			}

			private function PF_UserLimit_Check($action,$post_status){
	
				switch ($post_status) {
					case 'publish':
							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;
					
					case 'pendingpayment':
							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit_pendingpayment','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_pendingpayment','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'rejected':
							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit_rejected','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_rejected','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'pendingapproval':
							switch ($action) {
								case 'edit':
									$output = 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_pendingapproval','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;
				}

				return $output;
			}
	    /**
		*End: Class Functions
		**/


	   function __destruct() {
		  $this->FieldOutput = '';
		  $this->ScriptOutput = '';
	    }
	}
}

?>