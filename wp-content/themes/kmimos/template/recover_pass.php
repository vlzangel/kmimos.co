<?php 
	/*
		Template Name: Restaurar Contraseña
	*/

	$id_user = get_current_user_id();
	if( $id_user != "" ){
		header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
	}

	if( isset( $_GET['r'] ) ){
        $xuser = $wpdb->get_row("SELECT * FROM wp_users WHERE md5(ID) = '{$_GET['r']}'");

        $pos = strpos($xuser->user_pass, "$");
        $tipo = "viejo";
		$tipo = "nuevo";
		if ($pos === false) {
		    $tipo = "nuevo";
		}

        $_SESSION['kmimos_recuperar'] = array( $xuser->ID, $xuser->user_email, $tipo);
        header("location: ".get_home_url()."/restablecer/");
    }

    if( $_SESSION['kmimos_recuperar'] == "" ){
        header("location: ".get_home_url());
    }

	get_header();

		if(function_exists('PFGetHeaderBar')){PFGetHeaderBar();} ?>
		<div class="header-search" style="background-image:url(<?php echo site_url(); ?>/wp-content/themes/kmimos/images/new/km-fondo-buscador.gif);">
			<div class="overlay"></div>
		</div>
 
		<div class="pf-blogpage-spacing pfb-top"></div>
		<section role="main" class="blog-full-width" style="overflow: hidden;">
			<div class="pf-container">
				<div class="pf-row">
					<div class="col-lg-12">
							<form id="vlz_form_recuperar" class="km-box-form" enctype="multipart/form-data" method="POST" onsubmit="return false;">

								<?php
									$datos = $_SESSION['kmimos_recuperar'];
						            echo "<input type='hidden' name='user_id' value='{$datos[0]}' />";
						            echo "<input type='hidden' name='user_email' value='{$datos[0]}' />";
								?>
								<div class="vlz_modal" id="terminos_y_condiciones" style="display: none;">
									<div class="vlz_modal_interno">
										<div id="vlz_modal_cerrar_registrar" class='vlz_modal_fondo' onclick="jQuery('.vlz_modal').css('display', 'none');"></div>
										<div class="vlz_modal_ventana">
											<div id="vlz_titulo_registro" class="popup-tit">Recuperación</div>
											<div id="vlz_cargando" class="vlz_modal_contenido" style="display: none;">
											</div>
											<div id="vlz_terminos" class="vlz_modal_contenido" style="height: auto;">
												<h1 style="font-size: 15px;">Recuperando, por favor espere...</h1>
											</div>
										</div>
									</div>
								</div>

								<article style="max-width: 600px; display: block; margin: 0px auto;">
									<div class="vlz_parte">
										<div class="popup-tit">Recuperar Contraseña</div>

										<div class="vlz_seccion">

											<h2 class="vlz_titulo_interno" style="font-size: 20px;">Email: <?php echo $datos[1]; ?></h2>

											<div class="label-placeholder verify">
												<div class="vlz_cell50">
													<input data-title="<strong>Las contraseñas son requeridas y deben ser iguales</strong>" type='password' id='clave_1' name='clave_1' class='' placeholder='Ingrese su nueva contraseña'  maxlength="20" minlength="3">
													<span id="error_clave_1" class="verify_result"></span>
												</div>

												<div class="vlz_cell50 verify" style="margin: 20px 0;">
													<input data-title="<strong>Las contraseñas son requeridas y deben ser iguales</strong>" type='password' id='clave_2' name='clave_2' class='' placeholder='Reingrese su nueva contraseña' minlength="3" maxlength="20">
													<span id="error_clave_2" class="verify_result"></span>
												</div>
											</div>

										</div>

										<div class="vlz_contenedor_botones_footer">
											<div class="vlz_bloqueador"></div>
											<button type='submit' id="vlz_boton_recuperar" class="km-btn-basic" style=" outline: none; border: none; width: 100%;" >Recuperar</button>
										</div>

									</div>
								</article>

								<script>

									var form = document.getElementById('vlz_form_recuperar');

									function error_clean(){
										jQuery(".verify_result").css({'color':'green'}).html('');
									}

									function error_show(id){
										jQuery("#error_"+id).css({'color':'red'}).html( jQuery("#"+id).attr("data-title") );
									}

									function vlz_validar(){
										error_clean();
										var action = true;
										var clv1 = jQuery("#clave_1").attr("value");
										var clv2 = jQuery("#clave_2").attr("value");

										if( clv1.length < 3 ){
											error_show("clave_1");
											action = false;

										}else if( clv2.length < 3 ){
											error_show("clave_2");
											action = false;

										}else if( clv1 != clv2 ){
											var text = jQuery("#clave_2").attr("data-title");
											jQuery("#error_clave_2").css({'color':'red'}).html(text);
											action = false;
										}

										return action;
									}


									jQuery("#vlz_form_recuperar").submit(function(e){
										e.preventDefault();

										if(!vlz_validar()){
											return false;
										}

										jQuery('#vlz_boton_recuperar').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> GUARDANDO DATOS');
										var result = getAjaxData('/procesos/login/recuperar_pass.php','post',  jQuery(this).serialize());
										console.log(result);
										result = jQuery.parseJSON(result);

										if(result['result']=='success'){
											jQuery('#vlz_boton_recuperar').html('Restablecer');
											jQuery('#vlz_boton_recuperar').after('<p style="font-size:16px;font-weight:bold;">Clave restaurada con exito, Redireccionando al perfil. <br> por favor espere...</p>');
											setTimeout(function(){
												location.href = "<?php echo get_home_url()."/perfil-usuario/?ua=profile"; ?>";
											},6000);
										}else if(result['result']=='error'){
											jQuery('#vlz_boton_recuperar').html('Restablecer');
											alert(result['message']);
										}

									});
								</script>

							</form>

						</article>
						
					</div>
					
				</div>
			</div>
		</section>
<?php get_footer(); ?>