<?php 
    /*
        Template Name: Contactanos
    */
    get_header();

    wp_enqueue_script('recaptcha_contacto', "https://www.google.com/recaptcha/api.js", array(), '1.0.0');
	wp_enqueue_script('contactanos_js', getTema().'/js/contactanos.js', array(), '1.0.0');

        
    $HTML = '
    <div class="km-ficha-bg" style="background-image:url('.getTema().'/images/new/km-ficha/km-bg-ficha.jpg);">
        <div class="overlay"></div>
    </div>
    <section class="container text-left">

        <h2 style="font-size:24px; padding-top:20px;">
            Por favor llene el siguiente formulario y lo contactaremos a la brevedad posible
        </h2>
        <form id="contactanos">
            <div class="km-box-form">

                <div class="content-placeholder">
                    <div class="label-placeholder">
                        <label>Nombre</label>
                        <input  type="text" data-type="fields" data-charset="xlf" name="nombres" value="" 
                                class="input-label-placeholder solo_letras" maxlength="20">
                        <small  data-error="nombres" style="visibility: hidden;"></small>
                    </div>
                </div>

                <div class="label-placeholder">
                    <label>Correo electrónico</label>
                    <input type="email" data-type="fields" name="email" maxlength="250" data-charset="cormlfnum" 
                        autocomplete="off" type="text" value="" class="social_email input-label-placeholder">
                    <small data-error="email" style="visibility: hidden;"></small>
                </div>

                <div class="content-placeholder">
                    <div class="label-placeholder">
                        <label>Asunto</label>
                        <input type="text" data-type="fields" data-charset="xlfalfnum" name="asunto" value="" class="input-label-placeholder" maxlength="50">
                        <small data-error="asunto" style="visibility: hidden;"></small>
                    </div>
                </div>

                <div class="content-placeholder">
                    <div class="label-placeholder">
                        <label style="position: initial;">Mensaje</label>
                        <textarea data-type="fields" data-charset="xlfalfnum" name="contenido" value="" class="form-control" maxlength="500"></textarea> 
                        <small data-error="contenido" style="visibility: hidden;"></small>
                    </div>
                </div>
                
                <div style="padding:20px 0px;" class="g-recaptcha" data-sitekey="6LeX9TYUAAAAAF5L3Sr57SDQPlxUY74AojSrCYBW"></div>

                <div class="content-placeholder col-md-4" style="padding:20px 0px;">
                    <button id="enviar_mensaje" type="button" class="btn km-btn-primary">Enviar mensaje</button>
                </div>

                <div class="col-xs-12" style="padding:20px 0px;">
                    <span id="mensaje" class="alert" style="display:none;"></span>
                </div>

            </div>
        </form>

    </section>';

	echo comprimir_styles($HTML);

    get_footer(); 
?>