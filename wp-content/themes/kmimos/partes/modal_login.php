<?php
/*wp_enqueue_script('index.js', getTema()."/js/index.js", array("jquery"), '1.0.0');*/

$datos = kmimos_get_info_syte();
	$HTML .='
		<!-- POPUP INICIAR SESIÓN -->
		<div id="popup-iniciar-sesion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<div class="popup-iniciar-sesion-1">
						<p class="popup-tit">INICIAR SESIÓN</p>
						
						<form id="form_login" autocomplete="off">
							<div class="km-box-form">
								<div class="content-placeholder">
									<div class="label-placeholder">
										<!-- <label>Correo electrónico</label>-->
										<input type="text" id="usuario" placeholder="Usuario &oacute; Correo El&eacute;ctronico" class="input-label-placeholder">
									</div>
									<div class="label-placeholder">
										<!--<label>Contraseña</label>-->
										<input type="password" id="clave" placeholder="Contraseña" class="input-label-placeholder" autocomplete="off">
									</div>
								</div>
							</div>
							<input type="submit" name="enviar" class="hidden">
							<a href="#" id="login_submit" class="km-btn-basic">INICIAR SESIÓN AHORA</a>

							<div class="row km-recordatorio">
								<div class="col-xs-12 col-sm-4">

									<div class="km-checkbox">
										<input type="checkbox" value="active" id="km-checkbox" name="check" checked/>
										<label for="km-checkbox">
										</label>
									</div>

								</div>
							</form>

							<div class="col-xs-12 col-sm-8" style="text-align: right;">
								<a href="#" class="km-btn-contraseña-olvidada">¿OLVIDASTE TU CONTRASEÑA?</a>
							</div>
						</div>
						<div class="line-o hidden">
							<p class="text-line">o</p>
							<div class="bg-line"></div>
						</div>
						
						<div class="alert alert-danger" style="
							display:none;
				            -webkit-transition: All 1s; /* Safari */
				            transition: All 1s;" 
							data-error="auth"></div>

						<a href="#" onClick="auth_facebook();" class="km-btn-fb hidden">
							<img src="'.getTema().'/images/new/icon/km-redes/icon-fb-blanco.svg">
							 CONÉCTATE CON FACEBOOK
						</a>

						<a href="#" class="google_login km-btn-border hidden">
							<img src="'.getTema().'/images/new/icon/km-redes/icon-gmail.svg">
							CONÉCTATE CON GOOGLE
						</a>
						
						<p style="color: #979797; margin-top: 20px;">Al crear una cuenta, aceptas las <a style="color: blue;" target="_blank" href="'.site_url().'/terminos-y-condiciones/">condiciones del servicio y la Política de privacidad</a> de Kmimos.</p>
						<p><img style="width: 20px; margin-right: 5px; position: relative; top: -3px;" src="'.getTema().'/images/new/icon/km-redes/icon-wsp.svg">En caso de dudas escríbenos al whatsapp +52 (55) 6892-2182</p>
						<hr>
						<div class="row">
							<div class="col-xs-12 col-sm-5">
								<p style="margin-bottom: 0px;">¿No eres miembro todavía?</p>
								<p>REGÍSTRATE AHORA - Es Gratis</p>
							</div>
							<div class="col-xs-12 col-sm-7">
								<a data-target="#popup-registrarte" class="modal_show km-btn-border" ><b>REGÍSTRATE</b></a>
							</div>
						</div>
					</div>
					<div class="popuphide popup-olvidaste-contrasena">
						<p class="popup-tit">¿OLVIDASTE TU CONTRASEÑA?</p>
						<p>No te preocupes, a todos nos pasa. Ingresa tu correo electrónico y listo!</p>
						<form id="form_recuperar" onsubmit="return false;">
							<div class="km-box-form">
								<div class="content-placeholder">
									<div class="label-placeholder verify" style="margin: 20px 0;">
										<input type="email" id="usuario" data-verify="noactive" placeholder="Ingresar dirección de email"  maxlength="30" class="verify_mail input-label-placeholder">
										<span class="verify_result"></span>
									</div>
									
									<div class="botones_box">
										<button type="button" class="km-btn-basic" style=" outline: none; border: none; width: 100%;" id="recovery-clave">
											ENVIAR CONTRASEÑA
										</button>

										<!-- button type="button" style=" outline: none; border: none; width: 100%;" id="recuperar_submit-1" class="km-btn-basic recover_pass">ENVIAR CONTRASEÑA</button -->
									</div>
									<div class="response"></div>
								</div>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
		<!-- FIN POPUP INICIAR SESIÓN -->
';