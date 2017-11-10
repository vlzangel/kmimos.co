<?php 
    /*
        Template Name: Finalizar
    */

    wp_enqueue_style('finalizar', getTema()."/css/finalizar.css", array(), '1.0.0');
	wp_enqueue_style('finalizar_responsive', getTema()."/css/responsive/finalizar_responsive.css", array(), '1.0.0');

	wp_enqueue_script('finalizar', getTema()."/js/finalizar.js", array("jquery"), '1.0.0');

	get_header();

		global $wpdb;
		
		$orden_id = vlz_get_page();

		$pdf = get_post_meta($orden_id, "_openpay_pdf", true);
		if( $pdf != "" ){
			$pdf = "<a class='btn_fin_reserva' href='{$pdf}' target='_blank'>DESCARGAR INSTRUCCIONES PARA PAGO EN TIENDA DE CONVENIENCIA</a>";
		}

		$data_reserva = kmimos_desglose_reserva_data($orden_id);

	    $email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID='{$data_reserva["cliente"]}'");
	    $telefonos = get_user_meta($data_reserva["cliente"], "user_phone", true)." / ".get_user_meta($data_reserva["cliente"], "user_mobile", true);

	    if( strtolower($data_reserva["metodo_pago"]) == "tarjeta" ){
	    	$pixel = "<script> fbq ('track','Purchase'); </script>";
	    }else{
	    	$pixel = "";
	    }

	    $info = $pixel.'
	        <div class="desglose_box">
	            <div>
	                <div class="sub_titulo">RESERVA</div>
	                <span>'.$data_reserva["id_reserva"].'</span>
	            </div>
	            <div>
	                <div class="sub_titulo">MEDIO DE PAGO</div>
	                <span>Pago por '.$data_reserva["metodo_pago"].'</span>
	            </div>
	        </div>
	        <div class="desglose_box datos_cuidador">
	            
	            <strong>CLIENTE</strong>
	            <div class="item">
	                <div>Nombre</div>
	                <span>
	                    '.get_user_meta($data_reserva["cliente"], "first_name", true).'
	                    '.get_user_meta($data_reserva["cliente"], "last_name", true).'
	                </span>
	            </div>
	            <div class="item">
	                <div>Email</div>
	                <span>
	                    '.$email.'
	                </span>
	            </div>
	            <div class="item">
	                <div>Tel&eacute;fono</div>
	                <span>
	                    '.$telefonos.'
	                </span>
	            </div>
	        </div>
	    ';

	    $variaciones = "";
	    foreach ($data_reserva["variaciones"] as $value) {
	        $variaciones .= '
	            <div class="item">
	                <div>'.$value[0].' '.$value[1].' x '.$value[2].' x $'.$value[3].'</div>
	                <span>$'.$value[4].'</span>
	            </div>
	        ';
	    }
	    $variaciones = "
	        <div class='desglose_box'>
	            <strong>Servicio</strong>
	            <div class='item'>
	                <div>".$data_reserva["servicio_titulo"]."</div>
	                <span>
	                    <span>".$data_reserva["inicio"]."</span>
	                        &nbsp; &gt; &nbsp;
	                    <span>".$data_reserva["fin"]."</span>
	                </span>
	            </div>
	        </div>
	        <div class='desglose_box'>
	            <strong>Mascotas</strong>
	            ".$variaciones."
	        </div>
	    ";

	    $adicionales = "";
	    if( count($data_reserva["adicionales"]) > 0 ){
	        foreach ($data_reserva["adicionales"] as $value) {
	            $adicionales .= '
	                <div class="item">
	                    <div>'.$value[0].' - '.$value[1].' x $'.$value[2].'</div>
	                    <span>$'.$value[3].'</span>
	                </div>
	            ';
	        }
	        $adicionales = "
	            <div class='desglose_box'>
	                <strong>Servicios Adicionales</strong>
	                ".$adicionales."
	            </div>
	        ";
	    }

	    $transporte = "";
	    if( count($data_reserva["transporte"]) > 0 ){
	        foreach ($data_reserva["transporte"] as $value) {
	            $transporte .= '
	                <div class="item">
	                    <div>'.$value[0].'</div>
	                    <span>$'.$value[2].'</span>
	                </div>
	            ';
	        }
	        $transporte = "
	            <div class='desglose_box'>
	                <strong>Transportaci&oacute;n</strong>
	                ".$transporte."
	            </div>
	        ";
	    }

	    $totales = ""; $descuento = "";

	    if( $data_reserva["descuento"]+0 > 0 ){
	        $descuento = "
	            <div class='item'>
	                <div>Descuento</div>
	                <span>".number_format( $data_reserva["descuento"], 2, ',', '.')."</span>
	            </div>
	        ";
	    }

	    if( $data_reserva["desglose"]["enable"] == "yes" ){
	        
	        $totales = "
	            <div class='desglose_box totales'>
	                <strong>Totales</strong>
	                <div class='item'>
	                    <div class='pago_en_efectivo'>Monto a pagar en EFECTIVO al cuidador</div>
	                    <span>".number_format( ($data_reserva["desglose"]["remaining"]), 2, ',', '.')."</span>
	                </div>
	                <div class='item'>
	                    <div>Pagado</div>
	                    <span>".number_format( $data_reserva["desglose"]["deposit"], 2, ',', '.')."</span>
	                </div>
	                ".$descuento."
	                <div class='item total'>
	                    <div>Total</div>
	                    <span>".number_format( $data_reserva["desglose"]["total"], 2, ',', '.')."</span>
	                </div>
	            </div>
	        ";
	        
	    }else{
	        
	        $totales = "
	            <div class='desglose_box totales'>
	                <strong>Totales</strong>
	                <div class='item'>
	                    <div>Pagado</div>
	                    <span>".number_format( $data_reserva["desglose"]["deposit"]-$data_reserva["descuento"], 2, ',', '.')."</span>
	                </div>
	                ".$descuento."
	                <div class='item total'>
	                    <div>Total</div>
	                    <span>".number_format( $data_reserva["desglose"]["deposit"], 2, ',', '.')."</span>
	                </div>
	            </div>
	        ";
	    }

	    $CONTENIDO .= 
	        "
	        <div class='desglose_container'>".
	            $info.
	            $variaciones.
	            $adicionales.
	            $transporte.
	            $totales.
	        "</div>"
	    ;

	    $que_hacer = "
	    <div class='que_debo_hacer'>
			<div>¿QUÉ DEBO HACER AHORA?</div>
			<ul>
				<li>Revisa tu correo. Te enviaremos la Confirmación o Rechazo de tu Reserva en unos momentos (puede durar desde 30 min a 4 horas).</li>
				<li>Luego de aceptada la Reserva, el cuidador seleccionado y/o Atención al Cliente de Kmimos se pondrán en contacto contigo para alinear la logística de entrega.</li>
				<li>En caso de dudas contáctanos al (01) 800 056 4667, y te atenderemos de inmediato.</li>
			</ul>
		</div>";
	    if( $data_reserva["metodo_pago"] == "Tienda" ){
		    $que_hacer = "
		    <div class='que_debo_hacer'>
				<div>¿QUÉ DEBO HACER AHORA?</div>
				<ul>
					<li>Pícale al botón con las Instrucciones para pagar en la Tienda de Conveniencia que elijas.</li>
					<li>Recuerda que tienes 48 horas para hacer el pago.</li>
					<li><strong>El Cuidador que seleccionaste no recibirá tu solicitud de Reserva sino hasta que hayas hecho el pago en la tienda.</strong></li>
					<li>Una vez que pagues en la Tienda de Conveniencia, el cuidador seleccionado y/o Atención al Cliente de Kmimos se pondrán en contacto contigo dentro de las siguientes 1 a 4 horas para alinear la logística de entrega.</li>
					<li>En caso de dudas contáctanos al (01) 800 056 4667, y te atenderemos de inmediato.</li>
				</ul>

			</div>";
	    }

		$HTML .= '
	 		<div class="km-content km-step-end" style="max-width: 840px;">
				<div style="padding: 20px 40px 20px; background: #FFF;">
					<img src="'.getTema().'/images/new/km-reserva/img-end-step.png" width="197">
					<br>
					¡Genial '.get_user_meta($data_reserva["cliente"], "first_name", true).' '.get_user_meta($data_reserva["cliente"], "last_name", true).'!<br>
					Reservaste Exitosamente

					<div class="que_debo_hacer" style="margin-top: 5px;">
						<div style="max-width: 450px; margin: 0px auto; text-align: center;">Te acabamos de enviar un correo a tu dirección registrada con ésta información. Por favor revisa tu Buzón de Entrada o Buzón de No Deseados.</div>
					</div>

					<div style="text-align: left; max-width: 840px;" >
						'.$CONTENIDO.'
					</div>

					'.$que_hacer.'
					
					<div style="padding-top: 20px;">
						'.$pdf.'
						<a class="btn_fin_reserva" href="'.get_home_url().'/perfil-usuario/historial/">VER MIS RESERVAS</a>
					</div>
				</div>
			</div>

			<!-- SECCIÓN BENEFICIOS -->
			<div class="km-beneficios km-beneficios-footer" style="margin-top: 60px;">
				<div class="container">
					<div class="row">
						<div class="col-xs-4">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-pago.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">PAGO EN EFECTIVO O CON TARJETA</h5>
							</div>
						</div>
						<div class="col-xs-4 brd-lr">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-certificado.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">CUIDADORES CERTIFICADOS</h5>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-veterinaria.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">COBERTURA VETERINARIA</h5>
							</div>
						</div>
					</div>
				</div>
			</div>
	 	';

		echo comprimir_styles($HTML);

    get_footer(); 
?>