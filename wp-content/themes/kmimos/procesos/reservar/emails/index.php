<?php
	
	extract($_GET);
	if( isset($_GET["id_orden"]) ){
		include((dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))))."/wp-load.php");
	}

	$PATH_TEMPLATE = (dirname(dirname(dirname(__DIR__))));

	$info = kmimos_get_info_syte();
	add_filter( 'wp_mail_from_name', function( $name ) { global $info; return $info["titulo"]; });
    add_filter( 'wp_mail_from', function( $email ) { global $info; return $info["email"]; });

    global $wpdb;
	$id = $id_orden;
	$data = kmimos_desglose_reserva_data($id, true);

	extract($data);

	/*	
	echo "<pre>";
		print_r($data);
	echo "</pre>";
	*/
	
 	$modificacion_de = get_post_meta($servicio["id_reserva"], "modificacion_de", true);
    if( $modificacion_de != "" ){ 
    	$modificacion = "
    	<div style='font-family: Arial; font-size: 20px; font-weight: bold; letter-spacing: 0.4px; color: #777; padding-bottom: 19px; text-align: center;'>
            Esta es una modificación de la reserva #: ".$modificacion_de."
        </div>";
 	}else{ $modificacion = ""; }

	$email_admin = $info["email"];

	$mascotas_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/mascotas.php';
    $mascotas_plantilla = file_get_contents($mascotas_plantilla);
    $mascotas = "";
	foreach ($cliente["mascotas"] as $mascota) {
		$temp = str_replace('[NOMBRE]', $mascota["nombre"], $mascotas_plantilla);
		$temp = str_replace('[RAZA]', $mascota["raza"], $temp);
		$temp = str_replace('[EDAD]', $mascota["edad"], $temp);
		$temp = str_replace('[TAMANO]', $mascota["tamano"], $temp);
		$temp = str_replace('[CONDUCTA]', $mascota["conducta"], $temp);
		$mascotas .= $temp;
	}
	
	$desglose_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/desglose.php';
    $desglose_plantilla = file_get_contents($desglose_plantilla);

    $desglose = "";
	foreach ($servicio["variaciones"] as $variacion) {
		$plural = ""; if($variacion[0]>1){$plural="s";}
		$temp = str_replace('[TAMANO]', strtoupper($variacion[1]), $desglose_plantilla);
		$temp = str_replace('[CANTIDAD]', $variacion[0]." mascota".$plural, $temp);
		$temp = str_replace('[TIEMPO]', $variacion[2], $temp);
		$temp = str_replace('[PRECIO_C_U]', "$ ".$variacion[3], $temp);
		$temp = str_replace('[SUBTOTAL]', "$ ".$variacion[4], $temp);
		$desglose .= $temp;
	}

	$adicionales_desglose_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/adicionales_desglose.php';
    $adicionales_desglose_plantilla = file_get_contents($adicionales_desglose_plantilla);

    $adicionales = "";
    foreach ($servicio["adicionales"] as $adicional) {
		$temp = str_replace('[SERVICIO]', $adicional[0], $adicionales_desglose_plantilla);
		$temp = str_replace('[CANTIDAD]', $adicional[1], $temp);
		$temp = str_replace('[PRECIO_C_U]', "$ ".$adicional[2], $temp);
		$temp = str_replace('[SUBTOTAL]', "$ ".$adicional[3], $temp);
		$adicionales .= $temp;
	}

	if( $adicionales != "" ){
		$adicionales_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/adicionales.php';
    	$adicionales_plantilla = file_get_contents($adicionales_plantilla);

    	$adicionales = $adicionales_plantilla.$adicionales;
	}
	
	$transporte_desglose_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/transporte_desglose.php';
    $transporte_desglose_plantilla = file_get_contents($transporte_desglose_plantilla);

    $transporte = "";
    foreach ($servicio["transporte"] as $valor) {
		$temp = str_replace('[SERVICIO]', $valor[0], $transporte_desglose_plantilla);
		$temp = str_replace('[SUBTOTAL]', $valor[2], $temp);
		$transporte .= $temp;
	}

	if( $transporte != "" ){
		$transporte_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/transporte.php';
    	$transporte_plantilla = file_get_contents($transporte_plantilla);

    	$transporte = $transporte_plantilla.$transporte;
	}

	$totales_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/totales.php';
    $totales_plantilla = file_get_contents($totales_plantilla);
    $totales_plantilla = str_replace('[TIPO_PAGO]', $servicio["tipo_pago"], $totales_plantilla);

    $MONTO = "";
    if( $servicio["desglose"]["enable"] == "yes" ){
    	$deposito_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/deposito.php';
    	$deposito_plantilla = file_get_contents($deposito_plantilla);

    	$servicio["desglose"]["remaining"] -= $servicio["desglose"]["descuento"];

    	$deposito_plantilla = str_replace('[REMANENTE]', number_format( $servicio["desglose"]["remaining"], 2, ',', '.'), $deposito_plantilla);
        $totales_plantilla = str_replace('[TOTAL]', number_format( $servicio["desglose"]["total"], 2, ',', '.'), $totales_plantilla);
    	$totales_plantilla = str_replace('[PAGO]', number_format( $servicio["desglose"]["deposit"], 2, ',', '.'), $totales_plantilla);
    	$totales_plantilla = str_replace('[DETALLES]', $deposito_plantilla, $totales_plantilla);

    	$MONTO = number_format( $servicio["desglose"]["deposit"], 2, ',', '.');

    }else{
        $totales_plantilla = str_replace('[TOTAL]', number_format( $servicio["desglose"]["deposit"], 2, ',', '.'), $totales_plantilla);
    	$totales_plantilla = str_replace('[PAGO]', number_format( $servicio["desglose"]["deposit"]-$servicio["desglose"]["descuento"], 2, ',', '.'), $totales_plantilla);
    	$totales_plantilla = str_replace('[DETALLES]', "", $totales_plantilla);

    	$MONTO = number_format( $servicio["desglose"]["deposit"]-$servicio["desglose"]["descuento"], 2, ',', '.');
    }
	
	if( $servicio["desglose"]["descuento"]+0 > 0 ){
		$descuento_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/descuento.php';
	    $descuento_plantilla = file_get_contents($descuento_plantilla);
	    $descuento_plantilla = str_replace('[DESCUENTO]', number_format( $servicio["desglose"]["descuento"], 2, ',', '.'), $descuento_plantilla);
	    $totales_plantilla = str_replace('[DESCUENTO]', $descuento_plantilla, $totales_plantilla);
	}else{
		$totales_plantilla = str_replace('[DESCUENTO]', "", $totales_plantilla);
	}
    		

	if( $acc == ""  ){

		$status_reserva = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = ".$servicio["id_orden"]);

		if( strtolower($servicio["metodo_pago"]) == "tienda" && $status_reserva == "wc-on-hold"  ){
			include(__DIR__."/tienda.php");
		}else{
			include(__DIR__."/otro.php");
		}

	}else{

		$status = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = '".$servicio["id_reserva"]."'");

		$continuar = true;

		if(  $_SESSION['admin_sub_login'] != 'YES' ){

			$usuario = $cuidador["nombre"];
			if( $usu == "CLI" ){ $usuario = $cliente["nombre"]; }

			if( $status == "confirmed" || $status == "cancelled" || $status == "modified" ){
				$estado = array(
					"confirmed" => "Confirmada",
					"modified"  => "Modificada",
					"cancelled" => "Cancelada"
				);
				$msg = "
				<div class='msg_acciones'>
					<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
				    	Hola <strong>".$usuario."</strong>
				    </div>
					<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
				    	Te notificamos que la reserva N° <strong>".$servicio["id_reserva"]."</strong> ya ha sido <strong>".$estado[$status]."</strong> anteriormente.
				    </div>
					<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
				    	Por tal motivo ya no es posible realizar cambios en el estatus de la misma.
				    </div>
				</div>";
		   		
		   		$CONTENIDO .= $msg;
		   		$continuar = false;
			}

		}

		if( $continuar ){

			if( $acc == "CFM" ){

				$wpdb->query("UPDATE wp_posts SET post_status = 'wc-confirmed' WHERE ID = '{$servicio["id_orden"]}';");
	    		$wpdb->query("UPDATE wp_posts SET post_status = 'confirmed' WHERE ID = '{$servicio["id_reserva"]}';");

				include("confirmacion.php");

				if(  $_SESSION['admin_sub_login'] != 'YES' ){
			   		if(isset($cliente["id"])){	
				   		$user_referido = get_user_meta($cliente["id"], 'landing-referencia', true);
				   		if(!empty($user_referido)){
							$username = $cliente["nombre"];
							$http = (isset($_SERVER['HTTPS']))? 'https://' : 'http://' ;
							require_once( $PATH_TEMPLATE.'/template/mail/reservar/club-referido-primera-reserva.php');
							$user_participante = $wpdb->get_results( "SELECT ID, user_email FROM wp_users WHERE md5(user_email) = '{$user_referido}'" );
							$user_participante = (count($user_participante)>0)? $user_participante[0] : [];
							if(isset($user_participante->user_email)){
								wp_mail( $user_participante->user_email, "¡Felicidades, otro perrhijo moverá su colita de felicidad!", $html );
							}
						} 
					}
				}
			}

			if( $acc == "CCL" ){
				include(__DIR__."/cancelacion.php");
			}
		
		}


	}


?>