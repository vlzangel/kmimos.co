<?php
    require('../../../wp-load.php');

    $info = kmimos_get_info_syte();

    add_filter( 'wp_mail_from_name', function( $name ) {
        global $info;
        return $info["titulo"];
    });
    add_filter( 'wp_mail_from', function( $email ) {
        global $info;
        return $info["email"]; 
    });

	include("vlz_data_orden.php");
	include("vlz_order_funciones.php");

	echo "
		<style>
    		html, body{ margin: 0px; min-height: 100%; padding: 0px; font-size: 12px; }
    		.body{ margin: 0px; min-height: 100%; padding: 0px; }
    		body * { font-size: 12px; }
    	</style>
	";

	if($booking->get_status() == "cancelled" ){

		$msg_a_mostrar = $styles.'
			<p>Hola <strong>'.$nom_cliente.',</strong></p>
			<p align="justify">La reserva N° <strong>'.$reserva_id.'</strong> ya ha sido cancelada previamente.</p>
			<p style="text-align: center;">
	            <a 
	            	href="'.get_home_url().'/perfil-usuario/?ua=invoices"
	            	style="
	            		padding: 10px;
					    background: #59c9a8;
					    color: #fff;
					    font-weight: 400;
					    font-size: 17px;
					    font-family: Roboto;
					    border-radius: 3px;
					    border: solid 1px #1f906e;
					    display: block;
					    width: 200px;
					    margin: 0px auto;
					    text-align: center;
					    text-decoration: none;
	            	"
	            >Volver</a>
	        </p>
	    ';
	    
   		echo $msg_cliente = kmimos_get_email_html("", $msg_a_mostrar, "", true, true);

		exit;
	}

	if($s == "0"){
		$styles = "
			<style>
				.undoreset div p {
				    margin: 1em 0px;
				}
			</style>
		";
		$order->update_status('wc-cancelled');
		$booking->update_status('cancelled');

		$msg_cliente = $styles.'
	    	<p><strong>Cancelación de Reserva (N°. '.$reserva_id.')</strong></p>
			<p>Hola <strong>'.$nom_cliente.',</strong></p>
			<p align="justify">Te notificamos que la reserva N° <strong>'.$reserva_id.'</strong> ha sido cancelada exitosamente de acuerdo a tu petición.</p>
			<p align="justify">Si tienes alguna duda o comentario de la cancelación con todo gusto puedes contactarnos.</p>'
			.$detalles_cuidador
			.$detalles_mascotas
			.$detalles_servicio.'
			<p style="text-align: center;">
	            <a 
	            	href="'.get_home_url().'/"
	            	style="
	            		padding: 10px;
					    background: #59c9a8;
					    color: #fff;
					    font-weight: 400;
					    font-size: 17px;
					    font-family: Roboto;
					    border-radius: 3px;
					    border: solid 1px #1f906e;
					    display: block;
					    width: 200px;
					    margin: 0px auto;
					    text-align: center;
					    text-decoration: none;
	            	"
	            >Ir a Kmimos</a>
	        </p>
	    ';

   		$msg_cliente = kmimos_get_email_html("Reserva Cancelada Exitosamente!", $msg_cliente, "", true, true);
   		wp_mail( $email_cliente, "Cancelación de Reserva", $msg_cliente);

		$msg = $styles.'
	    	<p><strong>Cancelación de Reserva (N°. '.$reserva_id.')</strong></p>
			<p>Hola <strong>Administrador</strong>,</p>
			<p align="justify">Te notificamos que el cliente <strong>'.$nom_cliente.'</strong> ha cancelado la reserva N° <strong>'.$reserva_id.'</strong>.</p>'
			.$detalles_cliente
			.$detalles_cuidador
			.$detalles_mascotas
			.$detalles_servicio;
	    
   		$msg_admin = kmimos_get_email_html("Reserva Cancelada por Cliente - ".$nom_cliente_cuidador, $msg, "", true, true);
		wp_mail( $email_admin, "Cancelación de Reserva", $msg_admin, kmimos_mails_administradores());

   		$msg_cuidador = $styles.'
	    	<p><strong>Cancelación de Reserva (N°. '.$reserva_id.')</strong></p>
			<p>Hola <strong>'.$nom_cliente_cuidador.'</strong>,</p>
			<p align="justify">Te notificamos que el cliente <strong>'.$nom_cliente.'</strong> ha cancelado la reserva N° <strong>'.$reserva_id.'</strong>.</p>'
			.$detalles_cliente
			.$detalles_mascotas
			.$detalles_servicio;


   		$msg_cuidador = kmimos_get_email_html("Cancelación de Reserva", $msg_cuidador, "", true, true);
		if($action !='noaction'){
   			wp_mail( $email_cuidador, "Cancelación de Reserva", $msg_cuidador);
		}

		if($show =='noshow'){
			if(!add_post_meta($o, '_show', 'noshow', true)){ 
				update_post_meta($o, '_show', 'noshow');
			}
		}

	    $msg_a_mostrar = $styles.'
	    	<p><strong>Cancelación de Reserva (N°. '.$reserva_id.')</strong></p>
			<p>Hola <strong>'.$nom_cliente.',</strong></p>
			<p align="justify">Te notificamos que la reserva N° <strong>'.$reserva_id.'</strong> ha sido cancelada exitosamente de acuerdo a tu petición.</p>
			<p align="justify">Si tienes alguna duda o comentario de la cancelación con todo gusto puedes contactarnos.</p>'
			.$detalles_cuidador
			.$detalles_mascotas
			.$detalles_servicio.'
			<p style="text-align: center;">
	            <a 
	            	href="'.get_home_url().'/perfil-usuario/?ua=invoices"
	            	style="
	            		padding: 10px;
					    background: #59c9a8;
					    color: #fff;
					    font-weight: 400;
					    font-size: 17px;
					    font-family: Roboto;
					    border-radius: 3px;
					    border: solid 1px #1f906e;
					    display: block;
					    width: 200px;
					    margin: 0px auto;
					    text-align: center;
					    text-decoration: none;
	            	"
	            >Volver</a>
	        </p>
	    ';
	    
   		echo $msg_cliente = kmimos_get_email_html("Reserva Cancelada Exitosamente!", $msg_a_mostrar, "", true, true);

    }

?>