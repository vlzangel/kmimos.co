<?php 
/**********************************************************************************************************************************
*
* User Dashboard Page - Profile Form Request
* 
* Author: Webbu Design
*
* Location: /public_html/wp-content/themes/pointfinder/admin/estatemanagement/includes/pages/dashboard/
*
***********************************************************************************************************************************/


if(isset($_GET['ua']) && $_GET['ua']!=''){
	$ua_action = esc_attr($_GET['ua']);
}
if(isset($ua_action)){
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$errorval = '';
	$sccval = '';
	/**
	*Start: Profile Form Request
	**/
		if(isset($_POST) && $_POST!='' && count($_POST)>0){

			if (esc_attr($_POST['action']) == 'pfget_updateuserprofile') {

				$nonce = esc_attr($_POST['security']);
				if ( ! wp_verify_nonce( $nonce, 'pfget_updateuserprofile' ) ) {
					die( 'Security check' ); 
				}	

				
				$vars = $_POST;

			    $vars = PFCleanArrayAttr('PFCleanFilters',$vars);
				
				$newupload = '';
				if($user_id != 0){

					global $wpdb;

                    $current_user = wp_get_current_user();

					$arg = array('ID' => $user_id);

					if(isset($vars['referred'])){
						$arg['user_referred'] = $vars['referred'];
					}

					if(isset($vars['nickname'])){
						$arg['nickname'] = $vars['nickname'];
					}

					if(isset($vars['password']) && isset($vars['password2']) && $vars['password'] != '' && $vars['password2'] != ''){
						wp_set_password( $vars['password'], $user_id );
					}

					wp_update_user($arg); 

					// echo "<pre>";
					// 	print_r($user_id);
					// 	print_r($vars);
					// echo "</pre>";

					update_user_meta($user_id, 'user_birthdate',	$vars['user_birthdate']	);
					update_user_meta($user_id, 'first_name', 		$vars['firstname']		);
					update_user_meta($user_id, 'last_name', 		$vars['lastname']		);
					update_user_meta($user_id, 'user_referred', 	$vars['referred']		);
					update_user_meta($user_id, 'description', 		$vars['descr']			);
					update_user_meta($user_id, 'user_phone', 		$vars['phone']			);
					update_user_meta($user_id, 'user_mobile', 		$vars['mobile']			);
					update_user_meta($user_id, 'user_address', 		$vars['direccion']		);

					
					if( $vars['tipo_user'] == "vendor" ){
						$wpdb->query("UPDATE cuidadores SET telefono = '{$vars['phone']}', descripcion = '{$vars['descr']}' WHERE user_id = ".$user_id);
					}

					if( $_FILES['portada']['name'] != "" ){

						$id_cuidador = $wpdb->get_var("SELECT id FROM cuidadores WHERE user_id = '{$user_id}'");

						$name_photo = time();
						$foto_anterior = get_user_meta($user_id, 'name_photo', true);
						if( $foto_anterior != "" ){
							@unlink('wp-content/uploads/cuidadores/avatares/'.$id_cuidador."/".$foto_anterior);
						}
					
						if( $vars['tipo_user'] == "vendor" ){
							$fichero_subido = 'wp-content/uploads/cuidadores/avatares/'.$id_cuidador."/temp.jpg";
							if( !file_exists('wp-content/uploads/cuidadores/avatares/'.$id_cuidador)){
								mkdir('wp-content/uploads/cuidadores/avatares/'.$id_cuidador.'/', 0777);
								chown ('wp-content/uploads/cuidadores/avatares/'.$id_cuidador.'/', 'www-data www-data' );
							}
							$result = kmimos_upload_photo($name_photo, 'wp-content/uploads/cuidadores/avatares/'.$id_cuidador.'/', "portada", $_FILES );
							if($result['sts']==true){
								update_user_meta($user_id, 'name_photo', $result['name']);
							}
						}else{
							$fichero_subido = 'wp-content/uploads/avatares_clientes/'.$user_id."/{$name_photo}";
							if( !file_exists('wp-content/uploads/avatares_clientes/'.$user_id.'/')){
								mkdir('wp-content/uploads/avatares_clientes/'.$user_id.'/', 0777, true);
								chown('wp-content/uploads/avatares_clientes/'.$user_id.'/', "www-data www-data");
							}
							if (move_uploaded_file($_FILES['portada']['tmp_name'], $fichero_subido)) { 
								update_user_meta($user_id, 'name_photo', $name_photo);
							}
						}
						update_user_meta($user_id, 'user_photo', "1");
					}

					echo "
						<script>
							location.href = '".get_home_url()."/perfil-usuario/?ua=profile';
						</script>
					";
					
				}else{
				    $errorval .= esc_html__('Please login again to update profile (Invalid UserID).','pointfindert2d');
			  	}
			}
		}
	}
?>