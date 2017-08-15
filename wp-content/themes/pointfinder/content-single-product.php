<?php
	/**
	* The template for displaying product content in the single-product.php template
	**/

	global $wpdb;

	$D = $wpdb;

	$id_user = get_current_user_id(); // [SERVER_NAME] 

	$actual = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$referencia = $_SERVER['HTTP_REFERER'];

	if( $actual == $referencia ){
		$referencia = get_home_url();
	}

	echo "
	<style>
		.woocommerce .woocommerce-message { display: none !important; }
		.vlz_modal{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; display: table; z-index: 10000; background: rgba(0, 0, 0, 0.8); vertical-align: middle !important; display: none; }
		h1{ font-size: 18px; }
		h2{ font-size: 16px; }
		.vlz_modal_interno{ display: table-cell; text-align: center; vertical-align: middle !important; }
		.vlz_modal_ventana{ position: relative; display: inline-block; width: 60%!important; text-align: left; box-shadow: 0px 0px 4px #FFF; border-radius: 5px; z-index: 1000; }
		.vlz_modal_titulo{ background: #FFF; padding: 15px 10px; font-size: 18px; color: #52c8b6; font-weight: 600; border-radius: 5px 5px 0px 0px; }
		.vlz_modal_contenido{ background: #FFF; height: 450px; box-sizing: border-box; padding: 5px 15px; border-top: solid 1px #d6d6d6; border-bottom: solid 1px #d6d6d6; overflow: auto; text-align: justify; }
		.vlz_modal_pie{ background: #FFF; padding: 15px 10px; border-radius: 0px 0px 5px 5px; }
		.vlz_modal_fondo{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 500; }
		.vlz_boton_siguiente{ padding: 10px 50px; background-color: #a8d8c9; display: inline-block; font-size: 16px; border: solid 1px #2ca683; border-radius: 3px; float: right; cursor: pointer; } 
		@media screen and (max-width: 750px){ .vlz_modal_ventana{ width: 90% !important; } }
	</style>
	";

	if( $id_user  == ""){

		echo "								 
		<div id='jj_modal_ir_al_inicio' class='vlz_modal'>
			<div class='vlz_modal_interno'>
				<div class='vlz_modal_fondo' onclick='jQuery('#jj_modal_ir_al_inicio').css('display', 'none');'></div>
				<div class='vlz_modal_ventana jj_modal_ventana'S>
					<div class='vlz_modal_titulo'>¡Oops!</div>
					<div class='vlz_modal_contenido' style='height: auto;'>
						<h1 align='justify'>Debes iniciar sesión para poder realizar reservas.</h1>
						<h2 align='justify'>Selecciona <span id='cerrarModal' onclick=\"jQuery('#pf-login-trigger-button').click();\" style='color: #00b69d; font-weight: 600; cursor: pointer;'>Aquí</span> para acceder a kmimos.<h2>
					</div>
					<div class='vlz_modal_pie' style='border-radius: 0px 0px 5px 5px!important; height: 70px;'>
						<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
					</div>
				</div>
			</div>
		</div>";
	}else{

		$propietario = $D->get_var("SELECT post_author FROM wp_posts WHERE ID = ".get_the_ID() );
		if( $propietario == $id_user ){

			echo "						 
				<div id='jj_modal_ir_al_perfil' class='vlz_modal'>
					<div class='vlz_modal_interno'>
						<div class='vlz_modal_fondo' onclick='jQuery('#jj_modal_ir_al_perfil').css('display', 'none');'></div>
						<div class='vlz_modal_ventana jj_modal_ventana'S>
							<div class='vlz_modal_titulo'>¡Oops!</div>
							<div class='vlz_modal_contenido' style='height: auto;'>
								<h1 align='justify'>No puedes realizarte reservas a tí mismo.</h1>
								<h2 align='justify'>Selecciona <a href='".get_home_url()."/busqueda/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para buscar entre cientos de cuidadores certificados kmimos.<h2>
							</div>
							<div class='vlz_modal_pie' style='border-radius: 0px 0px 5px 5px!important; height: 70px;'>
								<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
							</div>
						</div>
					</div>
				</div>
			";

		}else{
			$meta = get_user_meta($id_user);

			if( $meta['first_name'][0] == '' ||  $meta['last_name'][0] == '' || ( $meta['user_mobile'][0] == '' ) && ( $meta['user_phone'][0] == '' )){

				echo "						 
					<div id='jj_modal_ir_al_perfil' class='vlz_modal'>
						<div class='vlz_modal_interno'>
							<div class='vlz_modal_fondo' onclick='jQuery('#jj_modal_ir_al_perfil').css('display', 'none');'></div>
							<div class='vlz_modal_ventana jj_modal_ventana'S>
								<div class='vlz_modal_titulo'>¡Oops!</div>
								<div class='vlz_modal_contenido' style='height: auto;'>
									<h1 align='justify'>Kmiusuario, para continuar con tu reserva debes ir a tu perfil para completar algunos datos de contacto.</h1>
									<h2 align='justify'>Selecciona <a href='".get_home_url()."/perfil-usuario/?ua=profile' target='_blank' style='color: #00b69d; font-weight: 600;'>Aquí</a> para cargar tu información.<h2>
								</div>
								<div class='vlz_modal_pie' style='border-radius: 0px 0px 5px 5px!important; height: 70px;'>
									<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
								</div>
							</div>
						</div>
					</div>
				";

			 }else{

				$mascotas = $D->get_var("SELECT count(*) FROM wp_posts WHERE post_type = 'pets' AND post_author = ".$id_user );

				if( $mascotas == 0 ){

					echo "						 
						<div id='jj_modal_ir_al_perfil' class='vlz_modal'>
							<div class='vlz_modal_interno'>
								<div class='vlz_modal_fondo' onclick='jQuery('#jj_modal_ir_al_perfil').css('display', 'none');'></div>
								<div class='vlz_modal_ventana jj_modal_ventana'S>
									<div class='vlz_modal_titulo'>¡Oops!</div>
									<div class='vlz_modal_contenido' style='height: auto;'>
										<h1 align='justify'>Debes cargar por lo menos una mascota para poder realizar una reserva.</h1>
										<h2 align='justify'>Selecciona <a href='".get_home_url()."/perfil-usuario/?ua=mypets' style='color: #00b69d; font-weight: 600;'>Aquí</a> para agregarlas.<h2>
									</div>
									<div class='vlz_modal_pie' style='border-radius: 0px 0px 5px 5px!important; height: 70px;'>
										<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
									</div>
								</div>
							</div>
						</div>
					";

				}else{

					$session = $D->get_var("SELECT session_value FROM wp_woocommerce_sessions WHERE session_key = ".$id_user );

					$carrito = unserialize($session);

					if( $carrito['total']+0 > 0){

						echo "						 
							<div id='jj_modal_ir_al_perfil' class='vlz_modal'>
								<div class='vlz_modal_interno'>
									<div class='vlz_modal_fondo' onclick='jQuery('#jj_modal_ir_al_perfil').css('display', 'none');'></div>
									<div class='vlz_modal_ventana jj_modal_ventana'S>
										<div class='vlz_modal_titulo'>¡Oops!</div>
										<div class='vlz_modal_contenido' style='height: auto;'>
											<h1 align='justify'>Ya tienes un servicio en tu carrito, para realizar otro, debes completar o eliminar el que tienes actualmente.</h1>
											<h2 align='justify'>Selecciona <a href='".get_home_url()."/carro/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para completar o eliminar.<h2>
										</div>
										<div class='vlz_modal_pie' style='border-radius: 0px 0px 5px 5px!important; height: 70px;'>
											<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
										</div>
									</div>
								</div>
							</div>
						";
						
					}else{
						do_action( 'woocommerce_before_single_product' ); ?>
						<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
							<div class="summary entry-summary">
								<?php do_action( 'woocommerce_single_product_summary' ); ?>
							</div>
							<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
							<meta itemprop="url" content="<?php the_permalink(); ?>" />
						</div>
						<?php do_action( 'woocommerce_after_single_product' ); 
					}

				}

			}

		}
		
	}
?>
<script type="text/javascript">
	jQuery('div.product_meta').hide();
	jQuery('span#cerrarModal').click(function(){
		jQuery('#jj_modal_ir_al_inicio').css('display', 'none');	
	});
	
	setTimeout(function(){
		jQuery('#jj_modal_ir_al_perfil').css('display', 'table');
		jQuery('#jj_modal_ir_al_inicio').css('display', 'table');
	}, 100);

</script>