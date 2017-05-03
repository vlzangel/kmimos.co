<?php 
	/*
		Template Name: vlz restablecer
	*/

	session_start();

	$id_user = get_current_user_id();
	if( $id_user != "" ){
		header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
	}

	if( isset( $_GET['r'] ) ){
        $xuser = $wpdb->get_row("SELECT * FROM wp_users WHERE md5(ID) = '{$_GET['r']}'");

        $pos = strpos($xuser->user_pass, "$");
        $tipo = "viejo";
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

		<?php 
			include("vlz/form/vlz_styles.php"); 
		?>

		<style type="text/css">
			.vlz_titulo_interno span {
			    color: #d80606;
			    font-size: 11px;
			    vertical-align: middle;
			    float: none;
			    display: block;
			    line-height: 1.2;
			    margin-top: 0px;
			}
			label{
			    display: block;
			}
			.no_error{
				display: none;
			}
			.error{
				display: block;
			    font-size: 10px;
			    border: solid 1px #CCC;
			    padding: 3px;
			    border-radius: 0px 0px 3px 3px;
			    background: #ffdcdc;
			    line-height: 1.2;
			    font-weight: 600;
			}
			.vlz_input_error{
			    border-radius: 3px 3px 0px 0px !important;
    			border-bottom: 0px !important;
			}
			.vlz_seccion {
			    margin-bottom: 0 !important;
			}
			@media screen and (max-width: 568px){
				.vlz_seccion{
				    margin-bottom: 5px;
				}
				.vlz_sub_seccion {
				    margin-bottom: 0px;
				}
				.vlz_cell50{
			        width: calc( 100% - 9px ) !important;
				    margin-bottom: 5px;
				}
			}	
			@media (max-width: 520px){
				.vlz_modal_contenido {
				    height: auto !important;
				}
				.vlz_modal_titulo {
				    font-size: 15px;
				}
			}
		</style>

		<div class="pf-blogpage-spacing pfb-top"></div>
		<section role="main" class="blog-full-width">
			<div class="pf-container">
				<div class="pf-row">
					<div class="col-lg-12">

						

							<form id="vlz_form_recuperar" class="vlz_form" enctype="multipart/form-data" method="POST">

								<?php
									$datos = $_SESSION['kmimos_recuperar'];
						            echo "<input type='hidden' name='user_id' value='{$datos[0]}' />";
						            echo "<input type='hidden' name='user_email' value='{$datos[0]}' />";

						            if( $datos[2] == "viejo" ){ ?>
						            	<div class="vlz_modal" id="terminos_y_condiciones">
											<div class="vlz_modal_interno">
												<div id="vlz_modal_cerrar_registrar" class='vlz_modal_fondo' onclick="jQuery('.vlz_modal').css('display', 'none');"></div>
												<div class="vlz_modal_ventana">
													<div id="vlz_titulo_registro" class="vlz_modal_titulo">Recuperación</div>
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
												<div class="vlz_titulo_parte">Recuperar Contraseña</div>

												<div class="vlz_seccion">

													<h2 class="vlz_titulo_interno">Email: <?php echo $datos[1]; ?></h2>

													<div class="vlz_sub_seccion">
														<div class="vlz_cell50">
															<input data-title="<strong>Las contraseñas son requeridas y deben ser iguales</strong>" type='password' id='clave_1' name='clave_1' class='vlz_input' placeholder='Ingrese su nueva contraseña' required>
															<div class='no_error' id='error_clave_1'></div>
														</div>
														
														<div class="vlz_cell50">
															<input data-title="<strong>Las contraseñas son requeridas y deben ser iguales</strong>" type='password' id='clave_2' name='clave_2' class='vlz_input' placeholder='Reingrese su nueva contraseña' required>
															<div class='no_error' id='error_clave_2'></div>
														</div>
													</div>
												
												</div>

												<?php include("vlz_terminos.php"); ?>

												<div class="vlz_contenedor_botones_footer">
													<div class="vlz_bloqueador"></div>
													<input type='button' id="vlz_boton_recuperar" class="vlz_boton_siguiente" value='Recuperar' />
												</div>

											</div> 
										</article> 

										<script>

											var form = document.getElementById('vlz_form_recuperar');

											function mostrar_error(id){
												jQuery("#error_"+id).html( jQuery("#"+id).attr("data-title") );

										        jQuery("#error_"+id).removeClass("no_error");
										        jQuery("#error_"+id).addClass("error");
										        jQuery("#"+id).addClass("vlz_input_error");
											}

											function quitar_error(id){
												jQuery("#error_"+id).html( "" );

										        jQuery("#error_"+id).removeClass("error");
										        jQuery("#error_"+id).addClass("no_error");
										        jQuery("#"+id).removeClass("vlz_input_error");
											}

									      	function vlz_validar(){
									      		var error = 0;
									      		var clv1 = jQuery("#clave_1").attr("value");
									      		var clv2 = jQuery("#clave_2").attr("value");

									      		if( clv1 == "" ){ mostrar_error("clave_1"); error++; }else{ quitar_error("clave_1"); }
									      		if( clv2 == "" ){ mostrar_error("clave_2"); error++; }else{ quitar_error("clave_2"); }

									      		if( clv1 != clv2 ){
									      			jQuery("#error_clave_2").html( jQuery("#clave_2").attr("data-title") );
									      			jQuery("#error_clave_2").removeClass("no_error");
										        	jQuery("#error_clave_2").addClass("error");
										        	jQuery("#clave_2").addClass("vlz_input_error");
										        	error++;
									      		}else{
									      			if( clv1 != "" ){
										      			quitar_error("clave_2");
										      		}
									      		}
									      		return ( error == 0);
									      	}

									      	jQuery("#vlz_boton_recuperar").on("click", function(){
									      		if( vlz_validar() ){
									      			jQuery("#vlz_form_recuperar").submit();
									      		}else{

									      		}
									      	});

									      	jQuery("#vlz_form_recuperar").submit(function(e){
									      		e.preventDefault();
							      				jQuery("#terminos_y_condiciones").css("display", "table");
							      				jQuery("#boton_registrar_modal").css("display", "inline-block");
								      			var a = "<?php echo get_template_directory_uri()."/kmimos/proceso_restablecer.php"; ?>";
								             	jQuery.post( a, jQuery("#vlz_form_recuperar").serialize(), function( data ) {
										      		location.href = "<?php echo get_home_url()."/perfil-usuario/?ua=profile"; ?>";
												});
									      	});
								      	</script> <?php

									}

									if( $datos[2] == "nuevo" ){ ?>
										<style type="text/css">
											.vlz_titulo_interno span {
											    color: #d80606;
											    font-size: 11px;
											    vertical-align: middle;
											    float: none;
											    display: block;
											    line-height: 1.2;
											    margin-top: 0px;
											}
											label{
											    display: block;
											}
											.no_error{
												display: none;
											}
											.error{
												display: block;
											    font-size: 10px;
											    border: solid 1px #CCC;
											    padding: 3px;
											    border-radius: 0px 0px 3px 3px;
											    background: #ffdcdc;
											    line-height: 1.2;
											    font-weight: 600;
											}
											.vlz_input_error{
											    border-radius: 3px 3px 0px 0px !important;
								    			border-bottom: 0px !important;
											}
											.vlz_contenedor_listados {
											    width: calc( 50% - 2px );
											    display: inline-block;
											    margin-bottom: 5px;
											}
											.vlz_img_portada{
											    position: relative;
											    height: 250px;
											    overflow: hidden;
											    border: solid 1px #ccc;
											    background: #EEE;
											    margin: 27px 6px 0px 3px;
											    border-radius: 5px;
											}
											.vlz_seccion {
											    margin-bottom: 0 !important;
											}
											#vlz_contenedor_selector_img .error{
											    z-index: 200;
											    position: absolute;
											    width: 100%;
											    border-radius: 5px 5px 0px 0px;
											    top: 0px;
											    font-size: 11px;
											}
											textarea.vlz_input {
											    resize: none;
											    height: 155px;
											}
											@media screen and (max-width: 750px){
												.vlz_modal_ventana{
													width: 90% !important;
												}
											}
											@media screen and (max-width: 568px){
												.vlz_seccion{
												    margin-bottom: 5px;
												}
												.vlz_sub_seccion {
												    margin-bottom: 0px;
												}
												.vlz_cell50{
											        width: calc( 100% - 9px ) !important;
												    margin-bottom: 5px;
												}
												.vlz_img_portada {
												    margin: 0px 6px 0px 3px;
												}

												.vlz_img_portada {
												    position: relative;
												    height: 200px;
												    overflow: hidden;
												    border: solid 1px #ccc;
												    background: #EEE;
												    margin: 0px 6px 0px 3px;
												    border-radius: 5px;
												}

												.vlz_img_portada_fondo {
												    position: absolute;
												    top: 0px;
												    left: 0px;
												    width: 100%;
												    height: 100%;
												    z-index: 50;
												    background-size: cover;
												    background-position: center;
												    background-repeat: no-repeat;
												    background-color: transparent;
												    filter: blur(2px);
												    transition: all .5s ease;
												}

												.vlz_img_portada_normal {
												    position: absolute;
												    top: 0px;
												    left: 10px;
												    width: calc( 100% - 20px );
												    height: 180px;
												    z-index: 100;
												    background-size: contain;
												    background-position: center;
												    background-repeat: no-repeat;
												    background-color: transparent;
												    margin: 10px 0px;
												    transition: all .5s ease;
												}

												#check_term{
												    display: block;
												    padding-right: 30px;
												    font-size: 12px !important;
												    height: auto !important;
												}
												#boton_registrar_modal{
											        display: inline-block;
												    font-size: 13px;
												    margin-top: 5px;
												}
												.vlz_modal_contenido {
												    height: 320px !important;
												}

												.vlz_contenedor_listados, .vlz_contenedor_dir{
											        width: calc( 100% - 9px ) !important;
								    				margin-bottom: 5px;
												}

											}	
										</style>
						            	<article>
											<div class="vlz_parte">
												<div class="vlz_titulo_parte">Actualización de datos</div>

												<div class="vlz_seccion">

													<div class="vlz_cell50 jj_input_cell00"><h2 class="vlz_titulo_interno">Datos Personales</h2>

														<?php
												            global $wpdb;

												            $user_id = $datos[0];
												            $datos = get_user_meta($datos[0]);

												            $name_photo = get_user_meta($user_id, "name_photo", true);
												            
												            if( $datos["wp_capabilities"][0] == 'a:1:{s:6:"vendor";b:1;}'){
																$cuidador = $wpdb->get_row("SELECT id, portada FROM cuidadores WHERE user_id = '$user_id'");
																$user_id_tipo = $cuidador->id;

																$name_photo = get_user_meta($user_id, "name_photo", true);
																if( empty($name_photo)  ){ $name_photo = "0.jpg"; }
																if( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador->id."/{$name_photo}") ){
																	$imagen = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador->id."/{$name_photo}";
																}elseif( file_exists("/wp-content/uploads/cuidadores/avatares/".$cuidador->id."/0.jpg") ){
																	$imagen = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador->id."/0.jpg";
																}else{
																	$imagen = get_template_directory_uri().'/images/noimg.png';
																}
												            }else{
																if( empty($name_photo)  ){ $name_photo = "0"; }
																if( file_exists("wp-content/uploads/avatares_clientes/".$user_id."/{$name_photo}") ){
																	$imagen = get_home_url()."/wp-content/uploads/avatares_clientes/".$user_id."/{$name_photo}";
																}elseif( file_exists("wp-content/uploads/avatares_clientes/".$user_id."/0.jpg") ){
																	$imagen = get_home_url()."/wp-content/uploads/avatares_clientes/".$user_id."/0.jpg";
																}else{
																	$imagen = get_template_directory_uri().'/images/noimg.png';
																}
												            }
												        ?>

														<div class="vlz_sub_seccion">
															<div class="vlz_cell50">
																<input data-title="El nombre no debe tener números y debe ser minimo de 2 caracteres."  type='text' id='nombres' name='nombres' class='vlz_input' placeholder='Nombres' value="<?php echo $datos['first_name'][0]; ?>" required>
															</div>
															
															<div class="vlz_cell50">
																<input data-title="Debes ingresar tu apellido<br>Este debe tener mínimo 3 caracteres." type='text' id='apellidos' name='apellidos' class='vlz_input' placeholder='Apellidos' value="<?php echo $datos['last_name'][0]; ?>" required> 
															</div>
														</div>

														<div class="vlz_sub_seccion">
															<div class="vlz_cell50">
																<input data-title="Debes ingresar tu número móvil<br>Este debe tener entre 10 y 11 dígitos." type='number' id='movil' maxlength="11" name='movil' class='vlz_input' placeholder='M&oacute;vil' value="<?php echo $datos['user_mobile'][0]; ?>" required pattern="[0-9]{11}">
															</div>
															
															<div class="vlz_cell50">
																<input data-title="Debes ingresar tu número telefónico<br>Este debe tener entre 10 y 11 dígitos." type='number' id='telefono' maxlength="11" name='telefono' class='vlz_input' placeholder='Tel&eacute;fono' value="<?php echo $datos['user_phone'][0]; ?>" required pattern="[0-9]{11}">
															</div>
														</div>

														<h2 class="vlz_titulo_interno">¿Cómo nos conoció?</h2>

														<div class="vlz_sub_seccion">
															<select id="referido" name="referido" class="vlz_input" data-title="Debes seleccionar una opción" required>
																<option value="">Selecciona una opción</option>
																<?php
																	$referidos = get_referred_list_options();
																	foreach ($referidos as $key => $value) {
																		if( $key == $datos['user_referred'][0] ){
																			$selected = "selected";
																		}else{
																			$selected = "";
																		}
																		echo "<option value='{$key}' {$selected}>{$value}</option>";
																	}
																?>
			                                                </select>
														</div>

														<h2 class="vlz_titulo_interno">Datos de Acceso</h2>

														<!-- <div class="vlz_sub_seccion">
															<strong>Email:</strong> <?php echo $datos[1]; ?>
														</div> -->
																	
														<div class="vlz_sub_seccion">							
															<div class="vlz_cell50">
																<input 
																	type='password' 
																	id='clave' 
																	name='clave' 
																	data-title="
																		<strong>
																			Las contraseñas son requeridas
																		</strong>" 
																	class='vlz_input'
																	placeholder='Contraseña' 
																	required 
																	autocomplete="off"
																>
															</div>
															
															<div class="vlz_cell50">
																<input 
																	type='password' 
																	id='clave2' 
																	name='clave2' 
																	data-title="
																		<strong>
																			Las contraseñas son requeridas
																		</strong>" 
																	class='vlz_input'
																	placeholder='Contraseña' 
																	required 
																	autocomplete="off"
																>
															</div>
														</div>
													</div>
												
													<div class="vlz_cell50 jj_input_cell00">

														<div class="vlz_seccion">
															<div class="vlz_img_portada">
								                                <div class="vlz_img_portada_fondo" style="background-image: url(<?php echo $imagen; ?>);"></div>
								                                <div class="vlz_img_portada_normal" style="background-image: url(<?php echo $imagen; ?>);"></div>
								                                <div class="vlz_cambiar_portada">
								                                	Subir Foto
								                                	<input type="file" id="portada" name="portada" accept="image/*" />
																</div>
																<div id='vlz_contenedor_selector_img'>
																	<input type="text" id="vlz_img_perfil" name="vlz_img_perfil" class="vlz_input" style="visibility: hidden; height: 0px !important; margin: 0px; padding: 0px;" value="" data-title="Debes cargar una foto. Fomatos aceptados: png, jpg, jpeg, gif"  />
						                                		</div>
						                                	</div>
														</div>

														<div class="vlz_sub_seccion" style="margin-top: 8px;">
															<div class="vlz_cell100">
																<textarea class='vlz_input jj_desc' id='descripcion' name='descripcion' placeholder='Información biográfica'><?php echo $datos['description'][0]; ?></textarea>
															</div>
														</div>

													</div>

												</div>

												<?php include("vlz_terminos.php"); ?>

												<div class="vlz_contenedor_botones_footer">
													<div class="vlz_bloqueador"></div>
													<input type='button' id="vlz_boton_modal_terminos" class="vlz_boton_siguiente" value='Actualizar' onclick="vlz_validar()" />
												</div>

												<script>

													var form = document.getElementById('vlz_form_recuperar');
													form.addEventListener( 'invalid', function(event){
												        event.preventDefault();
												        jQuery("#error_"+event.target.id).html( jQuery("#error_"+event.target.id).attr("data-title") );

												        jQuery("#error_"+event.target.id).removeClass("no_error");
												        jQuery("#error_"+event.target.id).addClass("error");
												        jQuery("#"+event.target.id).addClass("vlz_input_error");
													}, true);

													function especiales(id){
														switch(id){
															case "movil":
													      		var telefono = jQuery( "#movil" ).val();

													      		if( telefono.length >= 10 && telefono.length <= 11 ){
													      			return true;
													      		}else{
													      			return false;
													      		}
															break;
															case "telefono":
													      		var telefono = jQuery( "#telefono" ).val();

													      		if( telefono.length >= 10 && telefono.length <= 11 ){
													      			return true;
													      		}else{
													      			return false;
													      		}
															break;
															case "email_2":
													      		var clv1 = jQuery("#email_1").attr("value");
													      		var clv2 = jQuery("#email_2").attr("value");
													      		return ( clv1 == clv2 );
															break;
															default:
																return true;
															break;
														}
													}

													form.addEventListener( 'keypress', function(event){
												        if ( event.target.validity.valid && especiales(event.target.id) ) {
												        	if( jQuery("#error_"+event.target.id).hasClass( "error" ) ){
												        		jQuery("#error_"+event.target.id).removeClass("error");
													        	jQuery("#error_"+event.target.id).addClass("no_error");
													        	jQuery("#"+event.target.id).removeClass("vlz_input_error");
												        	}
													    } else {
												        	if( jQuery("#error_"+event.target.id).hasClass( "no_error" ) ){
												        		jQuery("#error_"+event.target.id).html( jQuery("#error_"+event.target.id).attr("data-title") );
												        		jQuery("#error_"+event.target.id).removeClass("no_error");
													        	jQuery("#error_"+event.target.id).addClass("error");
													        	jQuery("#"+event.target.id).addClass("vlz_input_error");
												        	} 
													    }
													}, true);

													form.addEventListener( 'change', function(event){
												        if ( event.target.validity.valid && especiales(event.target.id) ) {
												        	if( jQuery("#error_"+event.target.id).hasClass( "error" ) ){
												        		jQuery("#error_"+event.target.id).removeClass("error");
													        	jQuery("#error_"+event.target.id).addClass("no_error");
													        	jQuery("#"+event.target.id).removeClass("vlz_input_error");
												        	}
													    } else {
												        	if( jQuery("#error_"+event.target.id).hasClass( "no_error" ) ){
												        		jQuery("#error_"+event.target.id).html( jQuery("#error_"+event.target.id).attr("data-title") );
												        		jQuery("#error_"+event.target.id).removeClass("no_error");
													        	jQuery("#error_"+event.target.id).addClass("error");
													        	jQuery("#"+event.target.id).addClass("vlz_input_error");
												        	} 
													    }

													}, true);

													jQuery(".vlz_input").each(function( index ) {
													  	var error = jQuery("<div class='no_error' id='error_"+( jQuery( this ).attr('id') )+"' data-id='"+( jQuery( this ).attr('id') )+"'></div>");
													  	var txt = jQuery( this ).attr("data-title");
													  	if( txt == "" || txt == undefined ){ txt = "Completa este campo."; }
													  	error.attr( "data-title", txt );
													  	error.html( txt );
													  	jQuery( this ).parent().append( error );
													});

											      	function vista_previa(evt) {
												      	var files = evt.target.files;
												      	for (var i = 0, f; f = files[i]; i++) {  
												           	if (!f.type.match("image.*")) {
												                continue;
												           	}
												           	var reader = new FileReader();
												           	reader.onload = (function(theFile) {
												               return function(e) {
								                        			jQuery(".vlz_img_portada_fondo").css("background-image", "url("+e.target.result+")");
								                        			jQuery(".vlz_img_portada_normal").css("background-image", "url("+e.target.result+")");
								                        			jQuery("#vlz_img_perfil").attr("value", e.target.result);
								                        			jQuery("#error_vlz_img_perfil").css("display", "none");
												               };
												           })(f);
												           reader.readAsDataURL(f);
												       	}
													}      
											      	document.getElementById("portada").addEventListener("change", vista_previa, false);

											      	jQuery(".vlz_pin_check").on("click", function(){
											      		if( jQuery("input", this).attr("value") == "0" ){
											      			jQuery("input", this).attr("value", "1");
											      			jQuery(this).removeClass("vlz_no_check");
											      			jQuery(this).addClass("vlz_check");
											      		}else{
											      			jQuery("input", this).attr("value", "0");
											      			jQuery(this).removeClass("vlz_check");
											      			jQuery(this).addClass("vlz_no_check");
											      		}
											      	});

											      	jQuery("#vlz_form_recuperar").submit(function(e){
											      		e.preventDefault();
											      		jQuery("#vlz_modal_cerrar_registrar").attr("onclick", "");
											      		if( form.checkValidity() ){
											      			var a = "<?php echo get_template_directory_uri()."/kmimos/actualizar_perfil.php"; ?>";
												      		jQuery("#vlz_contenedor_botones").css("display", "none");
												      		jQuery(".vlz_modal_contenido").css("display", "none");
												      		jQuery("#vlz_cargando").css("display", "block");
												      		jQuery("#vlz_cargando").css("height", "auto");
												      		jQuery("#vlz_cargando").css("text-align", "center");
												      		jQuery("#vlz_cargando").html("<h2>Actualizando, por favor espere...</h2>");
												      		jQuery("#vlz_titulo_registro").html("Actualizando, por favor espere...");
												      		jQuery.post( a, jQuery("#vlz_form_recuperar").serialize(), function( data ) {
													      		// jQuery("#vlz_cargando").html("<h2>Completado</h2>");
												      			// jQuery("#vlz_titulo_registro").html("Proceso Completado");
												      			location.href = "<?php echo get_home_url()."/perfil-usuario/?ua=profile"; ?>";
												      			//console.log(data);
															});
											      		}

											      	});

											      	function clvs_iguales(e){
											      		if( e.currentTarget.name == 'clave' || e.currentTarget.name == 'clave' ){
												      		var clv1 = jQuery("#clave").attr("value");
												      		var clv2 = jQuery("#clave2").attr("value");
												      		if( clv1 != clv2 ){
												      			jQuery("#error_clave2").html("Las contraseñas deben ser iguales");
												      			jQuery("#error_clave2").removeClass("no_error");
													        	jQuery("#error_clave2").addClass("error");
													        	jQuery("#clave2").addClass("vlz_input_error");
												      		}else{
												      			jQuery("#error_clave2").removeClass("error");
													        	jQuery("#error_clave2").addClass("no_error");
													        	jQuery("#clave2").removeClass("vlz_input_error");
												      		}
											      		}
											      	}

											      	jQuery( "#clave" ).keyup(clvs_iguales);
											      	jQuery( "#clave2" ).keyup(clvs_iguales);

											      	function mostrar_error(id){
														jQuery("#error_"+id).html( jQuery("#"+id).attr("data-title") );

												        jQuery("#error_"+id).removeClass("no_error");
												        jQuery("#error_"+id).addClass("error");
												        jQuery("#"+id).addClass("vlz_input_error");
													}

													function quitar_error(id){
														jQuery("#error_"+id).html( "" );

												        jQuery("#error_"+id).removeClass("error");
												        jQuery("#error_"+id).addClass("no_error");
												        jQuery("#"+id).removeClass("vlz_input_error");
													}

											      	function vlz_validar_claves(){
											      		var error = 0;
											      		var clv1 = jQuery("#clave").attr("value");
											      		var clv2 = jQuery("#clave2").attr("value");

											      		if( clv1 == "" ){ mostrar_error("clave"); error++; }else{ quitar_error("clave"); }
											      		if( clv2 == "" ){ mostrar_error("clave2"); error++; }else{ quitar_error("clave2"); }

											      		if( clv1 != clv2 ){
											      			jQuery("#error_clave2").html( jQuery("#clave2").attr("data-title") );
											      			jQuery("#error_clave2").removeClass("no_error");
												        	jQuery("#error_clave2").addClass("error");
												        	jQuery("#clave2").addClass("vlz_input_error");
												        	error++;
											      		}else{
											      			if( clv1 != "" ){
												      			quitar_error("clave2");
												      		}
											      		}
											      		return ( error == 0);
											      	}

											      	function vlz_validar(){
											      		var error = 0;
											      		var campos = ["movil", "telefono"];
											      		campos.forEach(function(item, index){
											      			if( !especiales(item) ){
											      				console.log(item);
											      				error++;
											      			}
											      		});
											      		if( !form.checkValidity() || error > 0 ){
											      			var primer_error = ""; var z = true;
											      			jQuery( ".error" ).each(function() {
															  	if( jQuery( this ).css( "display" ) == "block" ){
															  		if( z ){
															  			primer_error = "#"+jQuery( this ).attr("data-id");
															  			z = false;
															  		}
															  	}
															});
											      			jQuery('html, body').animate({ scrollTop: jQuery(primer_error).offset().top-75 }, 2000);
											      		}else{
											      			if( vlz_validar_claves() ){
												      			vlz_modal('terminos', 'Términos y Condiciones');
											      			}
											      		}
											      	}

											      	function vlz_modal(tipo, titulo, contenido){
											      		switch(tipo){
											      			case "terminos":
											      				jQuery("#vlz_titulo_registro").html(titulo);
											      				jQuery("#terminos_y_condiciones").css("display", "table");
											      				jQuery("#boton_registrar_modal").css("display", "inline-block");
											      			break;
											      		}
											      	}

										      	</script> 

											</div>
										</article>  <?php
									}
								?>

							</form>

						</article>
						
					</div>
					
				</div>
			</div>
		</section>
		<div class="pf-blogpage-spacing pfb-bottom"></div> <?php 

	get_footer(); 
?>