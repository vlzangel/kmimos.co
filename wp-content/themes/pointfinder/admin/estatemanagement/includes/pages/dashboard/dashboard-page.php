<?php

/**********************************************************************************************************************************
*
* User Dashboard Actions
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

//Get form type
global $current_user;
global $redirect_to;

if($redirect_to!='') {
//    die('Redirect: '.$redirect_to);
    header('Location: '.$redirect);
}

if(isset($_GET['ua']) && $_GET['ua']!=''){
	$ua_action = esc_attr($_GET['ua']);
}
$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');

if(isset($ua_action)){
	$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
	$pfmenu_perout = PFPermalinkCheck();

	if(is_user_logged_in()){

 		if($setup4_membersettings_dashboard != 0){

				if ($ua_action == 'profile') {
					get_template_part('admin/estatemanagement/includes/pages/dashboard/form','profilereq');
				}

				$setup3_pointposttype_pt1 = 'petsitters';
				$setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
				$setup4_submitpage_status_old = PFSAIssetControl('setup4_submitpage_status_old','','0');

				$current_user = wp_get_current_user();
				$user_id = $current_user->ID;

				if (is_page($setup4_membersettings_dashboard)) {

					
					/**
					*Start: Menu
					**/
						$sidebar_output = '';
						$item_count = $favorite_count = $review_count = 0;

						global $wpdb;

						$item_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM  $wpdb->postmeta AS pm INNER JOIN $wpdb->posts AS p ON p.ID=pm.post_id WHERE pm.meta_value = %s and pm.meta_key = %d and post_status = %s",$user_id,"owner_pet","publish")  );
						$item_count = (empty($item_count)) ? 0 : $item_count ;

						$favorite_count = pfcalculatefavs($user_id);

						/** Prepare Menu Output **/
						$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
						$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
						$setup4_membersettings_frontend = PFSAIssetControl('setup4_membersettings_frontend','','0');
						$setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','1');
						

						$setup29_dashboard_contents_my_page_menuname = PFSAIssetControl('setup29_dashboard_contents_my_page_menuname','','');
						$setup29_dashboard_contents_inv_page_menuname = PFSAIssetControl('setup29_dashboard_contents_inv_page_menuname','','');
						$setup29_dashboard_contents_favs_page_menuname = PFSAIssetControl('setup29_dashboard_contents_favs_page_menuname','','');
						$setup29_dashboard_contents_profile_page_menuname = PFSAIssetControl('setup29_dashboard_contents_profile_page_menuname','','');
						$setup29_dashboard_contents_submit_page_menuname = PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','');
						$setup29_dashboard_contents_rev_page_menuname = PFSAIssetControl('setup29_dashboard_contents_rev_page_menuname','','');
                        $setup29_dashboard_contents_back_end_menuname = 'Panel de Control';
                        $setup29_dashboard_contents_shop_menuname = 'Descripción del Cuidador';
                        $setup29_dashboard_contents_issues_menuname = 'Mis Asuntos Pendientes';
                        $setup29_dashboard_contents_vendor_shop_menuname = 'Mis Servicios';
                        $setup29_dashboard_contents_vendor_sales_menuname = 'Mis Ventas';
                        $setup29_dashboard_contents_vendor_purchases_menuname = 'Mis Compras';
                        $setup29_dashboard_contents_vendor_pictures_menuname= 'Mis Fotos';
						$setup29_dashboard_contents_vendor_bookings_menuname = 'Mis Reservas';
                        $setup29_dashboard_contents_pets_list_menuname = 'Mis Mascotas';
						$setup29_dashboard_contents_pets_list_menuname = 'Mis Mascotas';
                        $setup29_dashboard_contents_caregiver_menuname = 'Mis Solicitudes';
						$setup_invoices_sh = PFASSIssetControl('setup_invoices_sh','','1');

						$pfmenu_output = '';

						$user_name_field = get_user_meta( $user_id, 'first_name', true ).' '.get_user_meta( $user_id, 'last_name', true );
						if ($user_name_field == ' ') {$user_name_field = $current_user->user_login;}

						$user_photo_field_output = kmimos_get_foto($user_id);
						$pfmenu_output .= '<li class="pf-dash-userprof"><img src="'.$user_photo_field_output.'" class="pf-dash-userphoto" style="width: 70px; height: 70px;"/><span class="pf-dash-usernamef">'.$user_name_field.'</span></li>';

                        /*
                        *   Muestra botón para ver el perfíl del usuario
                        */
                        if($_GET['ua']=='profile'){
                            $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-406"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
                        }
                        else {
                            $pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile"><i class="pfadmicon-glyph-406"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
                        }
                        /*
                        *   Muestra botón para ver las mascotas del usuario
                        */
                        $pets = kmimos_get_my_pets($current_user->ID);
                        if ($_GET['ua']=='mypets'){
                            $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-871"></i> '. $setup29_dashboard_contents_pets_list_menuname.'<span class="pfbadge">'.$pets['count'].'</span></li>';
                        }
                        else {
                            $class = ($_GET['ua']=='mypet' || $_GET['ua']=='newpet')? ' class="selected_option"':'';
                            $pfmenu_output .= '<li'.$class.'><a  href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypets"><i class="pfadmicon-glyph-871"></i> '. $setup29_dashboard_contents_pets_list_menuname.'<span class="pfbadge">'.$pets['count'].'</span></a></li>';
                        }
                        /*
                        *   Muestra botón para ver los cuidadores favoritos del usuario
                        */
                        $favorites = kmimos_get_my_favorites($current_user->ID);
                        if($_GET['ua']=='favorites'){
                            $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-375"></i> '. $setup29_dashboard_contents_favs_page_menuname.'<span class="pfbadge">'.$favorites['count'].'</span></a></li>';
                        }
                        else {
                            $class = ($_GET['ua']=='myfavorite')? ' class="selected_option"':'';
                            $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites"><i class="pfadmicon-glyph-375"></i> '. $setup29_dashboard_contents_favs_page_menuname.'<span class="pfbadge">'.$favorites['count'].'</span></a></li>';
                        }
                        /*
                        *   Muestra botón para ver las compras del usuario
                        */
                        if($_GET['ua']=='invoices'){
                            $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-33"></i>Historial</a></li>';
                        } else {
                            $class = ($_GET['ua']=='invoices')? ' class="selected_option"':'';
                            $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=invoices"><i class="pfadmicon-glyph-33"></i>Historial</a></li>';
                        }
                        /*
                        *   Si el usuario es administrador muestra botón para acceder al back-panel
                        */
                        $user_info = get_userdata($current_user->ID);
                        $user_roles = $user_info->roles;

						//SOLICITUDES DE CONOCER AL CUIDADOR POR EL CLIENTE
						if(!in_array('vendor',$user_roles)){
							if($_GET['ua']=='caregiver'){
								$pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-33"></i>'.$setup29_dashboard_contents_caregiver_menuname.'</a></li>';
							} else {
								$class = ($_GET['ua']=='caregiver')? ' class="selected_option"':'';
								$pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=caregiver"><i class="pfadmicon-glyph-33"></i>'.$setup29_dashboard_contents_caregiver_menuname.'</a></li>';
							}
						}


                        if(current_user_can( 'manage_options' )){
                            $pfmenu_output .= '<li class="negative">ADMINISTRADOR</li>';
                            $pfmenu_output .= '<li><a href="'.get_home_url().'/wp-admin" target="_blank"><i class="pfadmicon-glyph-421"></i> '. $setup29_dashboard_contents_back_end_menuname.'</a></li>';
                        }

                        /* --- */
                        /*
                        *   Si el usuario es un Cuidador muestra las opciones de los cuidadores
                        */
                        if(in_array('vendor',$user_roles)){
                            /*
                            *   Muestra la portada de la tienda del cuidador
                            */
                            $pfmenu_output .= '<li class="negative">CUIDADOR</li>';
                            if ($_GET['ua']=='myshop'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-664"></i> '.$setup29_dashboard_contents_shop_menuname.'</li>';
                            }
                            else {
                                $pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myshop"><i class="pfadmicon-glyph-664"></i> '. $setup29_dashboard_contents_shop_menuname.'</a></li>';
                            }
                            /*
                            *   Muestra los asuntos pendientes del cuidador
                            */
                            
                          /*  $issues = kmimos_get_my_pending_issues($current_user->ID);
                            $color = ( $issues['count'] > 0 ) ? ' red': '';
                            if ($_GET['ua']=='myissues'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-403'.$color.'"></i> Solicitudes para conocerme </li>';
                            } else {
                                $class = ($_GET['ua']=='myissue')? ' class="selected_option"':'';
                                $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myissues"><i class="pfadmicon-glyph-403'.$color.'"></i> Solicitudes para conocerme </a></li>';
                            }*/


                            /*
                            *   Muestra los servicios ofrecidos por el cuidador
                            */
                            $services = kmimos_get_my_services($current_user->ID);
                            if ($_GET['ua']=='myservices'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-453"></i> '. $setup29_dashboard_contents_vendor_shop_menuname.'<span class="pfbadge">'.$services['count'].'</span></li>';
                            } else {
                                $class = ($_GET['ua']=='myservice' || $_GET['ua']=='newservice')? ' class="selected_option"':'';
                                $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myservices"><i class="pfadmicon-glyph-453"></i> '. $setup29_dashboard_contents_vendor_shop_menuname.'<span class="pfbadge">'.$services['count'].'</span></a></li>';
                            }

                            if ($_GET['ua']=='disponibilidad'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-28"></i> Disponibilidad <span class="pfbadge"> </span></li>';
                            } else {
                                $pfmenu_output .= '<li'.$class.'><a href="?ua=disponibilidad"><i class="pfadmicon-glyph-28"></i> Disponibilidad <span class="pfbadge"> </span></a></li>';
                            }
                            
                            /* 
                             * Muestra la galería de fotos del cuidador
                             * Italo Gallery
                             */
                            $pictures = kmimos_get_my_pictures($current_user->ID);
                            if ($_GET['ua']=='mypictures'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-82"></i> '. $setup29_dashboard_contents_vendor_pictures_menuname.'<span class="pfbadge">'.$pictures['count'].'</span></li>';
                            }
                            else {
                                $pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypictures"><i class="pfadmicon-glyph-82"></i> '. $setup29_dashboard_contents_vendor_pictures_menuname.'<span class="pfbadge">'.$pictures['count'].'</span></a></li>';
                            }
                            
                            /*
                            *   Muestra el listado de las reservas activas del cuidador
                            */
                            $bookings = kmimos_get_my_bookings($current_user->ID);
                            if ($_GET['ua']=='mybookings'){
                                $pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-28"></i> '. $setup29_dashboard_contents_vendor_bookings_menuname.'</li>';//<span class="pfbadge">'.$bookings['count'].'</span>
                            }
                            else {
                                $class = ($_GET['ua']=='mybooking')? ' class="selected_option"':'';
                                $pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mybookings"><i class="pfadmicon-glyph-28"></i> '. $setup29_dashboard_contents_vendor_bookings_menuname.'</a></li>';//<span class="pfbadge">'.$bookings['count'].'</span>
                            }

							//SOLICITUDES DE CONOCER AL CUIDADOR
							if($_GET['ua']=='caregiver'){
								$pfmenu_output .= '<li class="selected_option"><a href="#" onclick="return false;"><i class="pfadmicon-glyph-33"></i>'.$setup29_dashboard_contents_caregiver_menuname.'</a></li>';
							} else {
								$class = ($_GET['ua']=='caregiver')? ' class="selected_option"':'';
								$pfmenu_output .= '<li'.$class.'><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=caregiver"><i class="pfadmicon-glyph-33"></i>'.$setup29_dashboard_contents_caregiver_menuname.'</a></li>';
							}
                        }

						$pfmenu_output .= ($setup11_reviewsystem_check == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=reviews"><i class="pfadmicon-glyph-377"></i> '. $setup29_dashboard_contents_rev_page_menuname.'</a></li>' : '';
                       
						$pfmenu_output .= '<li><a href="'.esc_url(wp_logout_url( home_url() )).'"><i class="pfadmicon-glyph-476"></i> '. esc_html__('Cerrar Sesión','pointfindert2d').'</a></li>';
						
						$sidebar_output .= '
							<div class="pfuaformsidebar ">
							<ul class="pf-sidebar-menu">
								'.$pfmenu_output.'
							</ul>
							</div>

							<div class="sidebar-widget"></div>
						';
					/** 
					*End: Menu
					**/
					
					/**
					*Start: Page Start Actions / Divs etc...
					**/
						switch ($ua_action) {
							case 'profile':
								$case_text = 'profile';
							break;
							case 'favorites':
								$case_text = 'favs';
							break;
							case 'newitem':
							case 'edititem':
							case 'newpet':
							case 'editpet':
								$case_text = 'submit';
							break;
							case 'reviews':
								$case_text = 'rev';
							break;
							case 'mypets':
							break;
							case 'mybookings':
							case 'myservices':
							case 'myitems':
								$case_text = 'my';
							break;
							case 'invoices':
								$case_text = 'inv';
							break;
							default:
								$case_text = 'my';
							break;

						}

						if (!in_array($case_text, array('purchaseplan','renewplan','upgradeplan'))) {
						
							$setup29_dashboard_contents_my_page = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page','','');
							$setup29_dashboard_contents_my_page_pos = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_pos','','1');
							$setup29_dashboard_contents_my_page_layout = PFSAIssetControl('setup29_dashboard_contents_profile_page_layout','','3');
							if ($ua_action == 'edititem') {
								$setup29_dashboard_contents_my_page_title = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_titlee','','');
							}else{
								$setup29_dashboard_contents_my_page_title = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_menuname','','');
							}
						}else{
							$setup29_dashboard_contents_my_page = PFSAIssetControl('setup29_dashboard_contents_submit_page','','');
							$setup29_dashboard_contents_my_page_layout = PFSAIssetControl('setup29_dashboard_contents_profile_page_layout','','3');
							$setup29_dashboard_contents_my_page_pos = PFSAIssetControl('setup29_dashboard_contents_submit_page_pos','','1');
							$membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id', true );

							switch ($case_text) {
								case 'purchaseplan':
									$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindert2d" );
									break;
								
								case 'renewplan':
									if (!empty($membership_user_package_id)) {
										$setup29_dashboard_contents_my_page_title = esc_html__("Renew Current Plan","pointfindert2d" );
									}else{
										$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindert2d" );
									}
									
									break;

								case 'upgradeplan':
									if (!empty($membership_user_package_id)) {
										$setup29_dashboard_contents_my_page_title = esc_html__("Upgrade Plan","pointfindert2d" );
									}else{
										$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindert2d" );
									}
									
									break;
							}
						}
					
						$pf_ua_col_codes = '<div class="col-lg-9 col-md-9">';
						$pf_ua_col_close = '</div>';
						$pf_ua_prefix_codes = '<section role="main"><div class="pf-container clearfix"><div class="pf-row clearfix"><div class="pf-uadashboard-container clearfix">';
						$pf_ua_suffix_codes = '</div></div></div></section>';
						$pf_ua_sidebar_codes = '<div class="col-lg-3 col-md-3">';
						$pf_ua_sidebar_close = '</div>';
						

						PFGetHeaderBar('',$setup29_dashboard_contents_my_page_title);

						$content_of_section = '';
						if ($setup29_dashboard_contents_my_page != '') {	
							$content_of_section = do_shortcode(get_post_field( 'post_content', $setup29_dashboard_contents_my_page, 'raw' ));
						}
						if ($setup29_dashboard_contents_my_page_pos == 1 && $setup29_dashboard_contents_my_page != '') {
							echo $content_of_section;
						}


						switch($setup29_dashboard_contents_my_page_layout) {
							case '3':
							echo $pf_ua_prefix_codes.$pf_ua_col_codes;	
							break;
							case '2':
							echo $pf_ua_prefix_codes.$pf_ua_sidebar_codes.$sidebar_output;
							echo $pf_ua_sidebar_close.$pf_ua_col_codes;	
							break;
						}
					/**
					*End: Page Start Actions / Divs etc...
					**/

					get_template_part('admin/estatemanagement/includes/pages/dashboard/dashboard','frontend');

					$errorval = '';
					$sccval = '';

					switch ($ua_action) {

                        case 'myshop':
							if(isset($_POST) && $_POST!='' && count($_POST)>0){
								if (esc_attr($_POST['action']) == 'pfupdate_my_shop') {
									$nonce = esc_attr($_POST['security']);
									if ( ! wp_verify_nonce( $nonce, 'pfupdate_my_shop' ) ) {
										die( 'Security check' );
									}
									$vars = PFCleanArrayAttr('PFCleanFilters', $_POST);
									if($user_id == 0){
										$errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
										break;
									}else{
										extract($_POST);
										$mascotas_cuidador = array(
											'pequenos' => $tengo_pequenos,
											'medianos' => $tengo_medianos,
											'grandes'  => $tengo_grandes,
											'gigantes' => $tengo_gigantes
										);
										$mascotas_cuidador = serialize($mascotas_cuidador);
										$tamanos_aceptados = array(
											'pequenos' => $acepta_pequenos,
											'medianos' => $acepta_medianos,
											'grandes'  => $acepta_grandes,
											'gigantes' => $acepta_gigantes
										);
										$tamanos_aceptados = serialize($tamanos_aceptados);

										$edades_aceptadas = array(
											'cachorros' => $acepta_cachorros,
											'adultos'	=> $acepta_adultos
										);
										$edades_aceptadas = serialize($edades_aceptadas);
										$comportamientos_aceptados = array(
											'sociables' 		  => $sociables,
											'no_sociables'		  => $no_sociables,
											'agresivos_humanos'	  => $agresivos_humanos,
											'agresivos_mascotas'  => $agresivos_mascotas
										);
										$comportamientos_aceptados = serialize($comportamientos_aceptados);
										$ini_url = substr($video_youtube, 0, 5);
										if( $ini_url == 'https' ){
											preg_match_all("#v=(.*?)&#", $video_youtube."&", $matches);
											if( count( $matches[0] ) > 0 ){
												$video_youtube = $matches[1][0];
											}else{
												preg_match_all("#be/(.*?)\?#", $video_youtube."?", $matches);
												$video_youtube = $matches[1][0];
											}
										}
										$atributos = array(
											'yard'	  		=> $yard,
											'green'		  	=> $green,
											'propiedad' 	=> $propiedad,
											'esterilizado'  => $solo_esterilizadas,
											'emergencia' 	=> $emergencia,
											'video_youtube' => $video_youtube
										);
										$atributos = serialize($atributos);

										$coordenadas = unserialize( $wpdb->get_var("SELECT valor FROM kmimos_opciones WHERE clave = 'municipio_{$delegacion}' ") );

						                $latitud  = $coordenadas["referencia"]->lat;
						                $longitud = $coordenadas["referencia"]->lng;

										$sql = "
                                                UPDATE
                                                    cuidadores
                                                SET
                                                    dni 				= '{$dni}',
                                                    experiencia 		= '{$cuidando_desde}',
                                                    direccion 			= '{$direccion}',
                                                    check_in 			= '{$entrada}',
                                                    check_out 			= '{$salida}',
                                                    num_mascotas 		= '{$num_mascotas_casa}',
                                                    mascotas_permitidas = '{$acepto_hasta}',
                                                    latitud		 		= '{$latitud}',
                                                    longitud		 	= '{$longitud}',
                                                    mascotas_cuidador	= '{$mascotas_cuidador}',
                                                    tamanos_aceptados	= '{$tamanos_aceptados}',
                                                    edades_aceptadas	= '{$edades_aceptadas}',
                                                    atributos			= '{$atributos}',
                                                    comportamientos_aceptados	= '{$comportamientos_aceptados}'
                                                WHERE
                                                    id = {$id}";

										global $wpdb;

										$wpdb->query($sql);

										$sql = "
                                                UPDATE
                                                    ubicaciones
                                                SET
                                                    estado 	   = '={$estado}=',
                                                    municipios = '={$delegacion}='
                                                WHERE
                                                    cuidador = {$id}";

										$wpdb->query($sql);


										//UPDATE WC_META
										$query="SELECT * FROM wp_posts WHERE post_author='{$user_id}' AND post_type='product'";
										$result=$wpdb->get_results($query);
										//var_dump($result);

										foreach($result as $product){
											$product_id=$product->ID;
											update_post_meta($product_id, '_wc_booking_qty', $acepto_hasta);
											update_post_meta($product_id, '_wc_booking_max_persons_group', $acepto_hasta);
										}


									}
								}
							}

							$output = new PF_Frontend_Fields(
								array(
									'formtype' => 'myshop',
									'current_user' => $user_id
								)
							);

							echo $output->FieldOutput;
							echo '<script type="text/javascript">
                                    '.$output->ScriptOutput.'
                                </script>';
							unset($output);

							if( count($_POST) > 0){
								header("location: ?ua=myshop");
							}
                        break;							
                            
                        case 'myissues':
							include("./wp-content/themes/pointfinder/vlz/admin/process/myissues.php");
                        break;							
							
                        case 'myservices':
							include("./wp-content/themes/pointfinder/vlz/admin/process/myservices.php");
                        break;						
							
                        case 'disponibilidad':
							$output = new PF_Frontend_Fields(
								array(
									'formtype' => 'disponibilidad',
									'current_user' => $user_id
								)
							);

							echo $output->FieldOutput;
							echo '<script type="text/javascript">
                                    '.$output->ScriptOutput.'
                                </script>';
							unset($output);
							
							echo $output->FieldOutput;
                        break;

                        case 'mybookings':
							include("./wp-content/themes/pointfinder/vlz/admin/process/mybookings.php");
	                    break;
							
						case 'updateservices':
							include("./wp-content/themes/pointfinder/vlz/admin/procesar_mis_servicios.php");
						break;

						case 'mypicturesdel':
							include("./wp-content/themes/pointfinder/vlz/admin/process/mypicturesdel.php");
                        break;

                        case 'mypictures':
							include("./wp-content/themes/pointfinder/vlz/admin/process/mypictures.php");
                        break;

                        case 'newpicture':
                            echo '<h1>Agregar Nueva Foto </h1><hr><br>';
                            if(isset($_POST) && $_POST!='' && count($_POST)>0){
                                if (esc_attr($_POST['action']) == 'pfadd_new_picture') {
                                    $nonce = esc_attr($_POST['security']);
                                    if ( ! wp_verify_nonce( $nonce, 'pfadd_new_picture' ) ) {
                                        die( 'Security check' ); 
                                    }

                                    $vars = $_POST;

                                    $vars = PFCleanArrayAttr('PFCleanFilters',$vars);

                                    if($user_id == 0){
                                        $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                        break;
                                    }
                                }
                            }
                         case 'mypicture':
							 if($ua_action=='mypicture') echo '<h1>Mi Foto #'.$_GET['num'].'</h1><hr><br>';
							 if(isset($_POST) && $_POST!='' && count($_POST)>0){
								 if (esc_attr($_POST['action']) == 'pfupdate_my_picture') {

									 $nonce = esc_attr($_POST['security']);
									 if ( ! wp_verify_nonce( $nonce, 'pfupdate_my_picture' ) ) {
										 die( 'Security check' );
									 }

									 $vars = $_POST;

									 $vars = PFCleanArrayAttr('PFCleanFilters',$vars);

									 if($user_id == 0){
										 $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
										 break;
									 }
									 else {
										 $picture_id = ($_GET['id']!='')? $_POST['id']:0;

										 if ( isset($_FILES['petsitter_photo'])) {
											 if ( $_FILES['petsitter_photo']['size'] >0) {
												 $file = array(
													 'name'     => $_FILES['petsitter_photo']['name'],
													 'type'     => $_FILES['petsitter_photo']['type'],
													 'tmp_name' => $_FILES['petsitter_photo']['tmp_name'],
													 'error'    => $_FILES['petsitter_photo']['error'],
													 'size'     => $_FILES['petsitter_photo']['size']
												 );
												 $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');

												 if(!in_array($_FILES['petsitter_photo']['type'], $allowed_file_types)) { // wrong file type
													 $errorval .= esc_html__("Please upload a JPG, GIF, or PNG file.<br>",'pointfindert2d');
												 }else{
													 global $wpdb;
													 $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
													 $tmp_user_id = ($cuidador->id) - 5000;
													 $_FILES = array("petsitter_photo" => $file);
													 foreach ($_FILES as $file => $array) {
														 $url_gallery = 'wp-content/uploads/cuidadores/galerias/'.$tmp_user_id.'/';
														 if(!file_exists($url_gallery)){
															 mkdir($url_gallery, 0777, true);
															 chown ( $url_gallery , 'www-data www-data' );
														 }
														 $name_photo_gallery = "gallery_".md5($_FILES['petsitter_photo']['name']);
														 $result = kmimos_upload_photo($name_photo_gallery, $url_gallery."/", "petsitter_photo", $_FILES );
													 }
												 }
											 }
											 echo "<script> location.href = '".get_home_url()."/perfil-usuario/?ua=mypictures'; </script>";
										 }
										 $sccval .= '<strong>'.esc_html__('Your update was successful.','pointfindert2d').'</strong>';
									 }
								 }
							 }
							 $output = new PF_Frontend_Fields(
								 array(
									 'formtype' => 'mypicture',
									 'current_user' => $user_id,
									 'picture_id' => $_GET['id']
								 )
							 );
							 echo $output->FieldOutput;
							 echo '<script type="text/javascript">
                                (function($) {
                                    "use strict";
                                    '.$output->ScriptOutput.'
                                })(jQuery);</script>';
							 unset($output);
                       	break;
							
                        case 'mypets':
							include("./wp-content/themes/pointfinder/vlz/admin/process/mypets.php");
                        break;
                            
                        case 'delpet':
							if($_POST['confirm_delete']==1){
								if(isset($_POST) && $_POST!='' && count($_POST)>0){
									if (esc_attr($_POST['action']) == 'pfpet_delete_confirm') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfpet_delete_confirm' ) ) {
											die( 'Security check' );
										}

										$vars = $_POST;

										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);

										if($user_id == 0){
											$errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
											break;
										}
									}
								}

								$pet_id = $_POST['pet_id'];
								update_post_meta($pet_id, 'owner_pet', '', $user_id);
								wp_trash_post( $pet_id  );

								echo '<h1>Mascota Borrada Satisfactoriamente</h1><hr><br>';
								$output = new PF_Frontend_Fields(
									array(
										'formtype' => 'dirpets',
										'fields' => $vars,
										'current_user' => $user_id,
										'pet_id' => $pet_id
									)
								);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
                                    (function($) {
                                        "use strict";
                                        '.$output->ScriptOutput.'
                                    })(jQuery);</script>';
								unset($output);
								break;
							}
                        break;
                           
                        case 'newpet':
                            echo '<h1>Agregar Nueva Mascota </h1><hr><br>';
								if(isset($_POST) && $_POST!='' && count($_POST)>0){
									if (esc_attr($_POST['action']) == 'pfadd_new_pet') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfadd_new_pet' ) ) {
											die( 'Security check' );
										}

										$vars = $_POST;

										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);

										if($user_id == 0){
 										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                            break;
									  	}
									}
								}
                        case 'mypet':
                            /**
                            *Start: My Pet Detail Page Content
                            **/
                            $pet_id = $_GET['id'];
                            if($ua_action=='mypet') echo '<h1>Mi Mascota '.get_the_title($pet_id).'</h1><hr><br>';
								if(isset($_POST) && $_POST!='' && count($_POST)>0){
									if (esc_attr($_POST['action']) == 'pfupdate_my_pet') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pfupdate_my_pet' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id == 0){
 										    $errorval .= esc_html__('Por favor inicie sesión (Usuario Inválido).', 'pointfindert2d');
                                            break;
									  	}
                                        else {
                                            if($_POST['delete_pet']==1){
                                                // Solicita la confirmación del borrado de la mascota
                                                $output = new PF_Frontend_Fields(
                                                    array(
                                                        'formtype' => 'delpet',
                                                        'fields' => $vars,
                                                        'current_user' => $user_id,
                                                        'pet_id' => $pet_id
                                                    )
                                                );
                                                echo $output->FieldOutput;
                                                echo '<script type="text/javascript">
                                                (function($) {
                                                    "use strict";
                                                    '.$output->ScriptOutput.'
                                                })(jQuery);</script>';
                                                unset($output);

                                                break;
                                                
                                            }
                                       
                                        $photo_pet = "";
                                        if ( isset($_FILES['portada_pet'])) {   
                                            if ( $_FILES['portada_pet']['size'] >0) { 
                                                $file = array(
                                                  'name'     => $_FILES['portada_pet']['name'],
                                                  'type'     => $_FILES['portada_pet']['type'],
                                                  'tmp_name' => $_FILES['portada_pet']['tmp_name'],
                                                  'error'    => $_FILES['portada_pet']['error'],
                                                  'size'     => $_FILES['portada_pet']['size']
                                                );
                                                $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
                                                if(!in_array($_FILES['portada_pet']['type'], $allowed_file_types)) { // wrong file type
                                                  $errorval .= esc_html__("Please upload a JPG, GIF, or PNG file.<br>",'pointfindert2d');
                                                }else{
                                                	$tmp_user_id = ($user_id) - 5000;

                                                    $_FILES = array("portada_pet" => $file);
                                                    foreach ($_FILES as $file => $array) {
                                                        //  Sube y procesa la imagen para que tenga 800x600px
                                                        $ext = pathinfo($_FILES['portada_pet']['name'], PATHINFO_EXTENSION);
                                                        $url_pets = 'wp-content/uploads/mypet/'.$tmp_user_id.'/';
														if(!file_exists($url_pets)){	
                                                        	mkdir($url_pets, 0777, true);
                                                        	chown ( $url_gallery , 'www-data www-data' );
                                                        }
                                                    	$photo_name_pet = "pet_".md5($_FILES['portada_pet']['name']);
                            							$photo = kmimos_upload_photo(
                            								$photo_name_pet, 
                            								$url_pets, 
                            								"portada_pet", 
                            								$_FILES 
                            							);
                                                    }
                                                }
                                            }
                                        }
                                        // END Pets - Photo

                                            $metas = array(
                                                'owner_pet' => $user_id,
                                                // foto pet
                                                //'foto_pet_file' => $photo_pet,
                                                'photo_pet' => $photo['path'],
                                                'name_pet' => wp_strip_all_tags( $_POST['pet_name'] ),
                                                'breed_pet' => wp_strip_all_tags( $_POST['pet_breed'] ),
                                                'colors_pet' => wp_strip_all_tags( $_POST['pet_colors'] ),
                                                'birthdate_pet' => wp_strip_all_tags( $_POST['pet_birthdate'] ),
                                                'size_pet' => $_POST['pet_size'],
                                                'gender_pet' => $_POST['pet_gender'],
                                                'pet_sterilized'=> $_POST['pet_sterilized'],
                                                'pet_sociable'=> $_POST['pet_sociable'],
                                                'aggressive_with_humans' => $_POST['aggresive_humans'],
                                                'aggressive_with_pets' => $_POST['aggresive_pets'],
                                                'about_pet' => wp_strip_all_tags( $_POST['pet_observations'] ),
                                            );
                                            $pet_id =($_POST['pet_id']=='')? 0: $_POST['pet_id'];

                                        	if( $photo['path'] == "" ){
                                        		unset( $metas['photo_pet'] );
                                        	}

                                            $args = array(
                                                'ID'            => $pet_id,
                                                'post_title'    => wp_strip_all_tags( $_POST['pet_name'] ),
                                                'post_status'   => 'publish',
                                                'post_author'   => $user_id,
                                                'post_type'     => 'pets',
                                                'meta_input'    => $metas
                                            );
                                            $pet_id = wp_insert_post( $args );
                                            wp_set_post_terms($pet_id, $_POST['pet_type'],'pets-types',false);
                                            echo '<h1>Mascota Guardada Satisfactoriamente</h1><hr><br>';
                                            $output = new PF_Frontend_Fields(
                                                array(
                                                    'formtype' => 'dirpets',
                                                    'fields' => $vars,
                                                    'current_user' => $user_id,
                                                    'pet_id' => $pet_id
                                                )
                                            );
                                            echo $output->FieldOutput;
                                            echo '<script type="text/javascript">
                                            (function($) {
                                                "use strict";
                                                '.$output->ScriptOutput.'
                                            })(jQuery);</script>';
                                            unset($output);

		                                    echo "<script> location.href = '".get_option( 'siteurl' )."/perfil-usuario/?ua=mypets'; </script>";
                                        	break;
                                        }
									}
								}

                                $output = new PF_Frontend_Fields(
                                    array(
                                        'formtype' => 'mypet',
                                        'fields' => $vars,
                                        'current_user' => $user_id,
                                        'pet_id' => $pet_id
                                    )
                                );
                                echo $output->FieldOutput;
                                echo '<script type="text/javascript">
                                (function($) {
                                    "use strict";
                                    '.$output->ScriptOutput.'
                                })(jQuery);</script>';
                                unset($output);

                        break;

						case 'profile':
                        	echo '<h1>Mi Perfil</h1><hr>';
							$output = new PF_Frontend_Fields(
								array(
									'formtype' => 'profile',
									'current_user' => $user_id,
									'sccval' => $sccval,
									'errorval' => $errorval
								)
								);
							echo $output->FieldOutput;
							echo '<script type="text/javascript">
							(function($) {
								"use strict";
								'.$output->ScriptOutput.'
							})(jQuery);</script>';
							unset($output);
						break;

						case 'favorites':

                            echo '<h1>Cuidadores Favoritos</h1><hr><br>';
                            echo "<p>A continuación te mostramos la lista de los cuidadores que haz marcado como tus favoritos</p><br>";
								if(isset($_POST) && $_POST!='' && count($_POST)>0){

									if (esc_attr($_POST['action']) == 'pf_refinefavlist') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pf_refinefavlist' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id != 0){

											$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'favorites',
														'fields' => $vars,
														'current_user' => $user_id
													)
												);
											echo $output->FieldOutput;
											echo '<script type="text/javascript">
											(function($) {
												"use strict";
												'.$output->ScriptOutput.'
											})(jQuery);</script>';
											unset($output);
											break;
											
										}else{
										    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
									  	}
									}
								}
							/**
							*Favs Page Content
							**/

							$output = new PF_Frontend_Fields(
										array(
											'formtype' => 'favorites',
											'current_user' => $user_id
										)
									);
								echo $output->FieldOutput;
							break;

						case 'invoices':
							include("./wp-content/themes/pointfinder/vlz/admin/page_invoices.php");
						break;

						case 'caregiver':
							include("./wp-content/themes/pointfinder/vlz/admin/frontend/caregiver.php");
						break;

					}
					
					/**
					*Start: Page End Actions / Divs etc...
					**/
						switch($setup29_dashboard_contents_my_page_layout) {
							case '3':
							echo $pf_ua_col_close.$pf_ua_sidebar_codes.$sidebar_output;
							echo $pf_ua_sidebar_close.$pf_ua_suffix_codes;	
							break;
							case '2':
							echo $pf_ua_col_close.$pf_ua_suffix_codes;
							break;						
						}


						if ($setup29_dashboard_contents_my_page_pos == 0 && $setup29_dashboard_contents_my_page != '') {
							echo $content_of_section;
						}
					/**
					*End: Page End Actions / Divs etc...
					**/

				}
				/**
				*End: Member Page Actions
				**/
		}


	}else{
		
	   PFLoginWidget();
	}
}else{
	$content = get_the_content();
	if (!empty($setup4_membersettings_dashboard)) {
		if (is_page($setup4_membersettings_dashboard)) {
			$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
			$pfmenu_perout = PFPermalinkCheck();
			pf_redirect(''.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile');
		}else{
			if(function_exists('PFGetHeaderBar')){
			  PFGetHeaderBar();
			}
			
			if (!has_shortcode( $content , 'vc_row' )) {
				echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	            echo '<section role="main">';
	                echo '<div class="pf-container">';
	                    echo '<div class="pf-row">';
	                        echo '<div class="col-lg-12">';
	                            the_content();
	                        echo '</div>';
	                    echo '</div>';
	                echo '</div>';
	            echo '</section>';
	            echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
			}else{
				the_content();
			}
		    
		}
	}else{
		if(function_exists('PFGetHeaderBar')){
		  PFGetHeaderBar();
		}
		if (!has_shortcode( $content , 'vc_row' )) {
			echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	        echo '<section role="main">';
	            echo '<div class="pf-container">';
	                echo '<div class="800row">';
	                    echo '<div cla600col-lg-12">';
	                        the_content();
	                    echo '</div>';
	                echo '</div>';
	            echo '</div>';
	        echo '</section>';
	        echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
		}else{
			the_content();
		}
	}
	
	
}


function resizeImage ($tmpname, $size, $save_dir, $save_name){
	$save_dir  .= ( substr( $save_dir, -1 ) != "/" ) ? "/" : "";
	$gis        = getimagesize( $tmpname ); 	    
	$type       = $gis[2]; 	    	    
	switch($type){
        case "1": $imorig = @imagecreatefromgif($tmpname); break;
        case "2": $imorig = @imagecreatefromjpeg($tmpname);break;
        case "3": $imorig = @imagecreatefrompng($tmpname); break; 	
        default:  $imorig = @imagecreatefromjpeg($tmpname);
    }
    $x = imagesx($imorig);
    $y = imagesy($imorig);

    $aw = 800;
    $ah = 600;
    $im = imagecreatetruecolor($aw,$ah);
    if (imagecopyresampled($im,$imorig , 0,0,0,0,$aw,$ah,$x,$y)){
    	if (imagejpeg($im, "{$save_dir}{$save_name}")){
    		return true;
    	}else{
    		return false;
    	}
    }
}



function upload($name, $path, $fieldName, $user_id){
	$fichero_subido = $path.$name;
	if (@move_uploaded_file($_FILES[$fieldName]['tmp_name'], $fichero_subido)) { 

        $aImage = @imageCreateFromJpeg( $fichero_subido );

        $nWidth  = 800;
        $nHeight = 600;

        $aSize = getImageSize( $fichero_subido );

        if( $aSize[0] > $aSize[1] ){
            $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
        }else{
            $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
        }

        $aThumb = imageCreateTrueColor( $nWidth, $nHeight );

        imageCopyResampled( $aThumb, $aImage, 0, 0, 0, 0, $nWidth, $nHeight, $aSize[0], $aSize[1] );

        imagejpeg( $aThumb, $fichero_subido );

        imageDestroy( $aImage );
        imageDestroy( $aThumb );

        //unlink($fichero_subido);

	}else{
		// echo "Fallido";
	}
}

// function upload($name, $path, $fieldName)
// {
// 	$result = false;
// 	// Temp 
// 	$fileTemp= $_FILES[$fieldName]['tmp_name'];
// 	$size = $_FILES[$fieldName]['size'];
// 	// $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
// 	// New 
// //		$save_name = "$name.{$ext}";
// 	$save_name = $name;
// 	$pathFile = $path.$save_name;
// 	if( $size <= 50000000 ){ // Size Max
// 		// Upload 
// 		if(move_uploaded_file($fileTemp, $pathFile)){ 
// 			if($resize > 0){
// 				resizeImage($pathFile, $size, $path, $save_name);
// 			}
// 			$result = true;	
// 		}
// 	}
// 	return $result;
// }

?>