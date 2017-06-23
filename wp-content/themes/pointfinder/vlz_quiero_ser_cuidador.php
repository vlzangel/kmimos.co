<?php 
	/*
		Template Name: vlz quiero ser cuidador
	*/

	if (!isset($_SESSION)) {
        session_start();
    }

	get_header();

	if(function_exists('PFGetHeaderBar')){PFGetHeaderBar();} 

	wp_enqueue_script(
		'redimencionar_imagenes',
		get_home_url()."/wp-content/themes/pointfinder/js/kmimos_imgs.js",
		array('jquery'),
		'1.0.0',
		true
	); 
	echo get_estados_municipios(); ?>

	<?php include("vlz/form/vlz_styles.php"); ?>

	<div class="pf-blogpage-spacing pfb-top"></div>
	<section role="main" class="blog-full-width">
		<div class="pf-container">
			<div class="pf-row">
				<div class="col-lg-12">

					<article style='position: relative'>

						<div class="vlz_seccion">
							<div class="vlz_cell75 vlz_titulo_contenedor">
								<h1 class="vlz_titulo">Sé parte de Kmimos</h1>
								<div class="vlz_sub_titulo">Para registrarte como cuidador de Kmimos, llena este formulario y nos pondremos en contacto contigo.</div>
							</div>
							<div class="vlz_cell25 vlz_banner_cuidador_contenedor">
								<img class="vlz_banner_cuidador" src="<?php echo get_home_url(); ?>/wp-content/themes/pointfinder/images/banner_cuidador.jpeg">
							</div>
						</div>

						<form id="vlz_form_nuevo_cuidador" class="vlz_form" enctype="multipart/form-data" method="POST">

							<?php
								$tam = array(
									"pequenos" => "Peque&ntilde;os (0.0 cm - 25.0)",
									"medianos" => "Medianos (25.0 cm - 58.0 cm)",
									"grandes"  => "Grandes (58.0 cm - 73.0 cm)",
									"gigantes" => "Gigantes (73.0 cm - 200.0 cm)",
								);
							?>

							<div class="vlz_parte">
								<div class="vlz_titulo_parte">Informaci&oacute;n General</div>

								<h2 class="vlz_titulo_interno">Datos Personales</h2>
								
								<div class="">

									<div id="kmimos_datos_personales" class="vlz_cell50 jj_input_cell00">

										<div class="vlz_sub_seccion">
											<div class="vlz_cell50">
												<input data-title="Debes ingresar tu apellido<br>Este debe tener mínimo 3 caracteres."  type='text' id='nombres' name='nombres' class='vlz_input' placeholder='Nombres' required minlength="3">
											</div>
											
											<div class="vlz_cell50">
												<input data-title="Debes ingresar tu apellido<br>Este debe tener mínimo 3 caracteres." type='text' id='apellidos' name='apellidos' class='vlz_input' placeholder='Apellidos' required minlength="3" >
											</div>
										</div>

										<div class="vlz_sub_seccion">
											<div class="vlz_cell50">
												<input data-title="El IFE debe ser de 13 dígitos." data-help="Coloca los 13 Números que se encuentran en la parte trasera de tu IFE o INE" type='number' id='ife' name='ife' class='vlz_input' placeholder='IFE' min=13 pattern="^\d{13}$" >
											</div>
											
											<div class="vlz_cell50">
												<input data-title="Debes ingresar tu número telefónico<br>Este debe tener al menos 10 dígitos." type='number' id='telefono' name='telefono' min="7" class='vlz_input' placeholder='Tel&eacute;fono' required pattern="[0-9]">
											</div>
										</div>

										<div class="vlz_sub_seccion">
											<div class="vlz_cell100">
												<select id="referido" name="referido" class="vlz_input" data-title="Debes seleccionar una opción" required>
													<option value="">¿Cómo nos conoció?</option>
													<?php
														$referidos = get_referred_list_options();
														foreach ($referidos as $key => $value) {
															$selected='';
															if(array_key_exists('wlabel',$_SESSION)){
																$wlabel=$_SESSION['wlabel'];

																if($key=='Volaris' && $wlabel=='volaris'){
																	$selected='selected';

																}else if($key=='Vintermex' && $wlabel=='viajesintermex'){
																	$selected='selected';
																}
															}
															echo "<option value='{$key}' $selected>{$value}</option>";
														}
													?>
                                                </select>
											</div>
										</div>

										<div class="vlz_sub_seccion">
											<div class="vlz_cell100">
												<div class="vlz_titulo_interno vlz_sub_seccion_mensaje">Aquí puedes completar la descripción sugerida,<spam style="color: red;">"sin embargo tu descripción personalizada es valiosa para atraer más clientes"</spam></div>
												<textarea 
													data-title="Ingresa una descripción de al menos 50 caracteres incluyendo los espacios." 
													data-help='"Cuentanos sobre ti, tus cualidades y porque deberían permitirte cuidar sus perritos"'
													class='vlz_input jj_desc' id='descripcion' name='descripcion' placeholder='Preséntate con la comunidad Kmimos' required minlength="50"
												>¡Hola! Soy ________, tengo ___ años y me encantan los animales. Estaré 100% al cuidado de tu perrito, lo consentiré y recibirás fotos diarias de su estancia conmigo. Mis huéspedes peludos duermen dentro de casa SIN JAULAS NI ENCERRADOS. Cuento con _______ para que jueguen, además cerca de casa hay varios parques donde los saco a pasear diariamente. En su estancia tu perrito contará con seguro de gastos veterinarios, que en caso de emergencia se encuentra a dentro d mi colonia, muy cerca de mi casa. Cualquier duda que tengas no dudes en contactarme.</textarea>
											</div>
										</div>

									</div>
								
									<div id="cargar_imagen_1" class="vlz_cell50 jj_input_cell00">

										<div style="position: relative;">
											<img src="<?php echo get_home_url()."/wp-content/themes/pointfinder/images/cargando.gif"; ?>" class="kmimos_cargando">
											<div class="vlz_img_portada">
				                                <div class="vlz_img_portada_fondo" style="background-image: url(<?php echo get_home_url()."/wp-content/themes/pointfinder"."/images/noimg.png"; ?>);"></div>
				                                <div class="vlz_img_portada_normal" style="background-image: url(<?php echo get_home_url()."/wp-content/themes/pointfinder"."/images/noimg.png"; ?>);"></div>
				                                <div class="vlz_cambiar_portada">
				                                	Subir Foto
				                                	<input type="file" id="portada" name="portada" accept="image/*" />
												</div>
		                                	</div>
			                                <input 
												data-help='Te recomendamos que en la foto de perfil, aparezcas tú sonriente con perritos.'
												type="text" id="vlz_img_perfil" name="vlz_img_perfil" class="vlz_input" style="visibility: hidden; height: 0px !important; margin: 0px; padding: 0px;" value="" data-title="Debes cargar una foto. Fomatos aceptados: png, jpg, jpeg, gif" required />
										</div>

									</div>

								</div>

								<h2 class="vlz_titulo_interno">Datos de Acceso</h2>
								
								<div class="vlz_seccion">
									<div class="vlz_cell25">
										<input 
											data-title="Ingresa un usuario valido<br>Este debe tener solo letras, numeros y<br> una longitud minima de 3 caracteres."
											data-help='Ingresa un usuario, sin espacios en blanco. Ej.: Lucas1'
											title="El nombre de usuario que colocaste aquí es con el que vas a ingresar en tu perfil y tu nombre y apellido será utilizado en las reservas" 
											type='text' 
											id='username' 
											name='username' 
											class='vlz_input' 
											placeholder='Nombre de Usuario' 
											minlength="3" maxlength="50" required pattern="[A-Za-z0-9\-_\.]+">
									</div>
									<div class="vlz_cell25">
										<input data-title="Ingresa tu E-mail<br>Ej: xxxx@xxx.xx" autocomplete="off" type='text' id='email' name='email' class='vlz_input' placeholder='E-mail' required pattern="^[\w._%-]+@[\w.-]+\.[a-zA-Z]{2,4}$" title="Ej. xxxx@xxxxx.xx">
									</div>								
									<div class="vlz_cell25">
										<input type='password' id='clave' name='clave' data-title="<strong>Las contraseñas son requeridas y deben ser iguales</strong>" class='vlz_input' placeholder='Contraseña' required autocomplete="off">
									</div>
									<div class="vlz_cell25">
										<input type='password' id='clave2' name='clave2' data-title="<strong>Las contraseñas son requeridas y deben ser iguales</strong>" class='vlz_input' placeholder='Contraseña' required autocomplete="off">
									</div>
								</div>

								<h2 class="vlz_titulo_interno">Datos de Ubicaci&oacute;n</h2>
								
								<div class="vlz_seccion" style="margin: 0 !important;">
									<div class="vlz_sub_seccion" style="margin: 0 !important;">
										<div class="vlz_contenedor_listados">
											<select id="estado" name="estado" class="vlz_input" data-title="Debe seleccionar un Estado" required>
												<option value="">Seleccione un estado</option>
												<?php
													global $wpdb;

												    $estados = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY name ASC");
												    $str_estados = "";
												    foreach($estados as $estado) { 
												        $str_estados .= "<option value='".$estado->id."'>".$estado->name."</option>";
												    } 

												    echo $str_estados = utf8_decode($str_estados);
												?>
											</select>
										</div>
										<div class="vlz_contenedor_listados">
											<select id="municipio" name="municipio" class="vlz_input" data-title="Debe seleccionar un Municipio">
												<option value="">Seleccione un municipio</option>
											</select>
										</div>
										<div class="vlz_contenedor_dir">
											<input type='text' id='direccion' name='direccion' class='vlz_input' placeholder='Direcci&oacute;n' data-title="Debe agregar una Dirección" data-help="Escribe la dirección que aparece en tu comprobante de domicilio.">
										</div>
										<input type="hidden" class="geolocation" id="latitud" name="latitud" placeholder="Latitud" step="any" value="" />
										<input type="hidden" class="geolocation" id="longitud" name="longitud" placeholder="Longitud" step="any" value="" />
									</div>

								</div>

								<h2 class="vlz_titulo_interno">Datos como Cuidador</h2>

								<div class="vlz_seccion">

									<div class="vlz_cell25">
										<input type='number' id='num_mascotas_casa' min='0' name='num_mascotas_casa' class='vlz_input' placeholder='Num. de mascotas en casa' data-help="Coloca cuantas mascotas tienes actualmente en casa." required>
									</div>
									
									<div class="vlz_cell25">
										<select class='vlz_input' id='num_mascotas_aceptadas' name='num_mascotas_aceptadas' data-help="Cuantos perritos puedes recibir al mismo tiempo." required>
											<option value="">Num. de mascotas aceptadas</option>
											<?php
												$anio = date("Y");
												for ($i=1; $i <= 6; $i++) { 
													echo '<option value="'.$i.'">'.$i.'</option>';
												}
											?>
										</select>
									</div>
									
									<div class="vlz_cell25">
										<select class='vlz_input' id='cuidando_desde' name='cuidando_desde' required>
											<option value="">Cuidando desde</option>
											<?php
												$anio = date("Y");
												for ($i=$anio; $i > 1900; $i-=1) { 
													echo '<option value="'.$i.'">'.$i.'</option>';
												}
											?>
										</select>
									</div>
									
									<div class="vlz_cell25">
										<select class='vlz_input' id='emergencia' name='emergencia' required>
											<option value="">&#191;Transporte de Emergencia?</option>
											<option value="1">Si</option>
											<option value="0">No</option>
										</select>
									</div>
									
								</div>

								
								<h2 class="vlz_titulo_interno">&#191;Qu&eacute; Tama&ntilde;os tienen tus Mascotas?</h2>
																
								<div class="vlz_seccion">

									<?php
										foreach ($tam as $key => $value) {
											echo '
												<div class="vlz_cell25">
													<div class="vlz_input vlz_no_check vlz_pin_check"><input type="hidden" id="tengo_'.$key.'" name="tengo_'.$key.'" value="0">'.$value.'</div>
												</div>
											';
										}
									?>
									
								</div>

								<div class="vlz_seccion">

									<div class="vlz_cell50 jj_input_cell00">

										<h2 class="vlz_titulo_interno">&#191;Qu&eacute; Edades Aceptas?</h2>

										<div class="vlz_cell50">
											<div class="vlz_input vlz_no_check vlz_pin_check"><input type="hidden" id="cachorros" name="cachorros" value="0">Cachorros</div>
										</div>
										
										<div class="vlz_cell50 ">
											<div class="vlz_input vlz_no_check vlz_pin_check"><input type="hidden" id="adultos" name="adultos" value="0">Adultos</div>
										</div>

									</div>

									<div class="vlz_cell50 jj_input_cell00">
										
										<h2 class="vlz_titulo_interno">&#191;Qu&eacute; Condiciones Aceptas?</h2>

										<div class="vlz_cell50 jj_input_cell00">
											<div class="vlz_input vlz_no_check vlz_pin_check"><input type="hidden" id="esterilizado" name="esterilizado" value="0">No Esterilizados</div>
										</div>

									</div>
																		
								</div>

								<h2 class="vlz_titulo_interno">&#191;Qu&eacute; Tama&ntilde;o de Mascotas Aceptas?</h2>
																
								<div class="vlz_seccion">

									<?php
										foreach ($tam as $key => $value) {
											echo '
												<div class="vlz_cell25">
													<div class="vlz_input vlz_no_check vlz_pin_check"><input type="hidden" id="acepto_'.$key.'" name="acepto_'.$key.'" value="0">'.$value.'</div>
												</div>
											';
										}
									?>
									
								</div>

								<h2 class="vlz_titulo_interno">&#191;Qu&eacute; Comportamientos Aceptas?</h2>
																
								<div class="vlz_seccion">

									<?php
							
										$Comportamientos = array(
											"sociables" 		=> "Sociables",
											"no_sociables" 		=> "No sociables",
											"agresivos_perros"  => "Agresivos con perros",
											"agresivos_humanos" => "Agresivos con humanos",
										);

										foreach ($Comportamientos as $key => $value) {
											echo '
												<div class="vlz_cell25">
													<div class="vlz_input vlz_no_check vlz_pin_check"><input type="hidden" id="comportamiento_'.$key.'" name="comportamiento_'.$key.'" value="0">'.$value.'</div>
												</div>
											';
										}
									?>
									
								</div>

								<h2 class="vlz_titulo_interno">Datos de t&uacute; Propiedad</h2>

								<div class="vlz_seccion">

									<div class="vlz_cell25">
										<select class='vlz_input' id="tipo_propiedad" name="tipo_propiedad" required>
											<option value="">Tipo de Propiedad</option>
											<option value="1">Casa</option>
											<option value="2">Departamento</option>
										</select>
									</div>
									
									<div class="vlz_cell25">
										<div class="vlz_input vlz_no_check vlz_pin_check"><input type="hidden" id="areas_verdes" name="areas_verdes" value="0">&#191;Tiene Areas Verdes?</div>
									</div>
									
									<div class="vlz_cell25">
										<div class="vlz_input vlz_no_check vlz_pin_check"><input type="hidden" id="tiene_patio" name="tiene_patio" value="0">&#191;Tiene Patio?</div>
									</div>
									
								</div>

							</div>

							<div class="vlz_parte">
								<div class="vlz_titulo_parte">Informaci&oacute;n sobre los Servicios que Brindas</div>

								<h2 class="vlz_titulo_interno">Horario</h2>

								<?php
									$dial = " a.m."; $horas = "";
									for ($i=7; $i < 20; $i++) {
										$t = $i;
										if( $t > 12 ){ $t = $t-12; $dial = ' p.m.'; }else{ if($t == 12){ $dial = ' m'; } }
										if( $t < 10 ){ $x = "0"; }else{ $x = ''; }
										if( $i < 10 ){ $xi = "0"; }else{ $xi = ''; }
										$horas .= '<option value="'.$xi.$i.':00:00" data-id="'.$i.'">'.$x.$t.':00 '.$dial.'</option>';
										if( $i != 19){ $horas .= '<option value="'.$xi.$i.':30:00" data-id="'.$i.'.5">'.$x.$t.':30 '.$dial.'</option>'; }
									}
								?>

								<div>

									<div class="vlz_cell25">
										<select class='vlz_input' id="entrada" name="entrada" data-help="Indica la hora que te prefieres que lleguen los huéspedes." required>
											<option value="">Hora de Entrada</option>
											<?php echo $horas; ?>
										</select>
									</div>

									<div class="vlz_cell25">
										<select class='vlz_input' id="salida" name="salida" data-help="Indica la hora en que prefieres entregar a tus huéspedes." required>
											<option value="">Hora de Salida</option>
											<?php echo $horas; ?>
										</select>
									</div>
							
								</div>
						
								<h2 id="trigger_precios" class="vlz_titulo_interno">Precios de Hospedaje </h2>
			 
								<div id="jj_modal" class="vlz_modal">

									<div class="vlz_modal_interno">

										<div class='vlz_modal_fondo' onclick="jQuery('#jj_modal').css('display', 'none');"></div>

										<div class="vlz_modal_ventana jj_modal_ventana" style="margin-top: 12%!important;">

											<div class="vlz_modal_titulo">Precios Sugeridos</div>

											<div class="vlz_modal_contenido" style="height: auto;">
													<p align="justify">
														Kmimos no te obliga a fijar un precio, sin embargo quisiéramos sugerir que sigas la siguiente tabla, la cual está creada en base a las tendencias de mercado actuales
													</p>
													<ul style="margin-left: -10px;">
														<li style="font-size: 12px;">Tamaño pequeño: 120 pesos por noche</li>
														<li style="font-size: 12px;">Tamaño mediano: 180 <span>pesos por noche</span></li>
														<li style="font-size: 12px;">Tamaño grande: 220 <span>pesos por noche</span></li>
														<li style="font-size: 12px;">Tamaño gigante: 250 <span>pesos por noche</span></li>
													</ul>
													<p align="justify">
														<strong>IMPORTANTE:</strong> recuerda que siempre podrás colocar el precio que a ti mejor se te acomode
													</p>
													
													<p align="justify">
														<strong>NOTA</strong>: en esta sección deberás colocar el precio que deseas percibir como ingreso. sobre dicho precio, el sistema le incrementará un 20% de manera automática. Cuando un cliente haga una búsqueda y se le despliegue los diferentes cuidadores disponibles para él, el precio que le mostrará la página será tu costo mas dicho 20%.
													</p>
											</div>
											<div class="vlz_modal_pie" style="border-radius: 0px 0px 5px 5px!important; height: 70px;">

												<input type='button' style="text-align: center;" class="vlz_boton_siguiente" value='Cerrar' onclick="jQuery('#jj_modal').css('display', 'none');" />

											</div>
										</div>
									</div>
								</div>
								<!-- /Modal "Precios de Hosedajes"-->

								<div class="vlz_seccion">
									<?php
										$txts = array(
											"pequenos" => "Te sugerimos un precio entre $100 a $200 pesos.",
											"medianos" => "Te sugerimos un precio entre $180 a $250 Pesos.",
											"grandes"  => "Te sugerimos un precio entre $220 a $300 pesos.",
											"gigantes" => "Te sugerimos un precio entre $250 a $350 pesos"
										);
										foreach ($tam as $key => $value) {
											echo '
												<div class="vlz_cell25">
													<input type="number" class="vlz_input" id="hospedaje_'.$key.'" name="hospedaje_'.$key.'" placeholder="'.$value.'" data-help="'.$txts[$key].'" >
												</div>
											';
										}
									?>

									<div class='no_error' id='error_hospedaje' style="margin: 3px 6px 0px;">Debe llenar al menos uno de los campos</div>
																		
								</div>

								<div style="display: none;">

									<div>
										<div class="vlz_cell50 jj_cell50">
											<h2 class="vlz_titulo_interno">Transportaci&oacute;n Sencilla</h2>
											<?php
											    $rutas = array(
											        "corto" => "Cortas",
											        "medio" => "Medias",
											        "largo" => "Largas"
											    );

											    $ayudas_trans_s = array(
											        "corto" => "Precio por recoger o llevar al perrito con tu carro. (Máximo 5 km. de Distancia)",
											        "medio" => "Precio por recoger o llevar al perrito con tu carro. (Máximo 9 km. de distancia)",
											        "largo" => "Precio por recoger o llevar al perrito con tu carro (10 km en adelante. de distancia)"
											    );

												foreach ($rutas as $key => $value) {
													echo '
														<div class="vlz_cell33">
															<input type="text" class="vlz_input" id="trans_sencillo_'.$key.'" name="trans_sencillo_'.$key.'" placeholder="'.$value.'" data-help="'.$ayudas_trans_s[$key].'" >
														</div>
													';
												}
											?>
										</div>

										<div class="vlz_cell50 jj_cell50">
											<h2 class="vlz_titulo_interno">Transportaci&oacute;n Redonda </h2>
											<?php
											    $ayudas_trans_r = array(
											        "corto" => "Precio por recoger y llevar al perrito con tu carro. (Máximo 5 km. de Distancia)",
											        "medio" => "Precio por recoger y llevar al perrito con tu carro. (Máximo 9 km. de distancia)",
											        "largo" => "Precio por recoger y llevar al perrito con tu carro (10 km en adelante. de distancia)"
											    );
												foreach ($rutas as $key => $value) {
													echo '
														<div class="vlz_cell33">
															<input type="text" class="vlz_input" id="trans_redonda_'.$key.'" name="trans_redonda_'.$key.'" placeholder="'.$value.'" data-help="'.$ayudas_trans_r[$key].'" >
														</div>
													';
												}
											?>
										</div>
									</div>

									<h2 class="vlz_titulo_interno">Otros Servicios</h2>
									<div class="vlz_seccion jj_input_cell00">
										<?php
										    $adicionales_extra = array(
										        "bano"                      => "Baño",
										        "corte"                     => "Corte de pelo y uñas",
										        "limpieza_dental"           => "Limpieza Dental",
										        "visita_al_veterinario"     => "Visita al Veterinario",
										        "acupuntura"                => "Acupuntura"
										    );
										    $adicionales_ayudas = array(
										        "bano"                      => "Coloca el precio por Bañar a tu huésped.",
										        "corte"                     => "Coloca el precio por corte de pelo y uñas.",
										        "limpieza_dental"           => "Coloca el precio por limpieza Dental.",
										        "visita_al_veterinario"     => "Coloca el precio por llevarlo al veterinario.",
										        "acupuntura"                => "Coloca el precio por realizarle acupuntura a tu huésped."
										    );
											foreach ($adicionales_extra as $key => $value) {
												echo '
													<div class="vlz_cell20 jj_input_cell00">
														<input type="text" class="vlz_input" id="adicional_'.$key.'" name="adicional_'.$key.'" placeholder="'.$value.'" data-help="'.$adicionales_ayudas[$key].'" >
													</div>
												';
											}
										?>
									</div>

								</div>

								<h2 class="vlz_titulo_interno" style="margin-top: 10px;">Servicios Adicionales</h2>
								<div class="vlz_seccion">
									<div class="vlz_contenedor_adicionales"></div>
									<div class="vlz_boton_agregar">Agregar Servicio Adicional</div>
								</div>

								<div id="cargar_imagen_2" style="display: none;"></div>

								<?php include("vlz_terminos_cuidador.php"); ?>

								<div class="vlz_contenedor_botones_footer">
									<div class="vlz_bloqueador"></div>
									<input type='button' id="vlz_boton_modal_terminos" class="vlz_boton_siguiente" value='Registrarme' onclick="vlz_validar()" />
								</div>

							</div>

							<div class="vlz_banner_footer">
								<div class="vlz_cell25">
									<img src="<?php echo get_home_url(); ?>/wp-content/themes/pointfinder/images/hombre.png">
								</div>
								<div class="vlz_cell75">
									<span class='vlz_verde'>Tienes dudas sobre el registro? Tienes poco tiempo para registrarte?</span> Kmimos te ayuda!
									Ponte en contacto con nosotros. Mándanos un mail a <span class='vlz_naranja'>a.vera@kmimos.la</span> o por Teléfono o Whatsapp al <span class='vlz_naranja'>(55) 6178 0320</span>.

									<span class='vlz_gris_footer'>La familia Kmimos te espera!!</span>
								</div>
							</div>

						</form>

						 <?php include("kmimos/js/quiero_ser_cuidador.php"); ?>

					</article>
					
				</div>
				
			</div>
		</div>
	</section>
	<div class="pf-blogpage-spacing pfb-bottom"></div> <?php 

	get_footer(); 
?>