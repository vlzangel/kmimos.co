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

    $mostrar_direccion = true;

	include("vlz_data_orden.php");
	include("vlz_order_funciones.php");

	echo "
		<style>
    		html, body{ margin: 0px; min-height: 100%; padding: 0px; font-size: 12px; }
    		.body{ margin: 0px; min-height: 100%; padding: 0px; }
    		body * { font-size: 12px; }
    	</style>
	";
	$styles = "
		<style>
			.undoreset div p {
			    margin: 1em 0px;
			}
		</style>
	"; 

	$status = $booking->get_status();

	if( $status == "confirmed" || $status == "cancelled" || $status == "modified" ){
		$estado = array(
			"confirmed" => "Confirmada",
			"modified"  => "Modificada",
			"cancelled" => "Cancelada"
		);
		$msg = $styles.'
				<p>Hola <strong>'.$nom_cuidador.'</strong></p>
				<p align="justify">Te notificamos que la reserva N° <strong>'.$reserva_id.'</strong> ya ha sido '.$estado[$status].' anteriormente.</p>
				<p align="justify">Por tal motivo ya no es posible realizar cambios en el estatus de la misma.</p>
		';
   		echo kmimos_get_email_html("La reserva ya fue ".$estado[$status]." anteriormente.", $msg, "", false, true);

   		exit;
	}

	if($s == "0"){
		$order->update_status('wc-cancelled');
		$booking->update_status('cancelled');

		kmimos_set_kmisaldo($cliente_id, $orden_id, $reserva_id);

		$msg = $styles.'
	    	<p><strong>Cancelación de Reserva (N°. '.$reserva_id.')</strong></p>
			<p>Hola <strong>'.$nom_cuidador.'</strong></p>
			<p align="justify">Te notificamos que la reserva N° <strong>'.$reserva_id.'</strong> ha sido cancelada exitosamente de acuerdo a tu petición.</p>
			<p align="justify">Si tienes alguna duda o comentario de la cancelación con todo gusto puedes contactarnos.</p>'
			.$detalles_cliente
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
	    
   		echo $msg_cuidador = kmimos_get_email_html("Reserva Cancelada Exitosamente!", $msg, "", true, true);
   		wp_mail( $cuidador_email, "Cancelación de Reserva", $msg_cuidador);

		$msg = $styles.'
	    	<p><strong>Cancelación de Reserva (N°. '.$reserva_id.')</strong></p>
			<p>Hola <strong>Administrador</strong>,</p>
			<p align="justify">Te notificamos que el cuidador <strong>'.$nom_cuidador.'</strong> ha cancelado la reserva N° <strong>'.$reserva_id.'</strong>.</p>'
			.$detalles_cliente
			.$detalles_cuidador
			.$detalles_mascotas
			.$detalles_servicio
			.'
				<p align="justify">
					Esta son las sugerencias que se le enviaron al cliente:
				</p>
			'
			.$lista_cercanos;
	    
   		$msg_admin = kmimos_get_email_html("Reserva Cancelada por Cuidador - ".$nom_cuidador, $msg, "", true, true);
   		wp_mail( $email_admin, "Cancelación de Reserva", $msg_admin, kmimos_mails_administradores());

   		$msg = $styles.'
   			<div>
		    	<p><strong>Cancelación de Reserva (N°. '.$reserva_id.')</strong></p>
				<p>Hola <strong>'.$nom_cliente.'</strong>,</p>
				<p align="justify">Te notificamos que el cuidador <strong>'.$nom_cuidador.'</strong> ha cancelado la reserva N° <strong>'.$reserva_id.'</strong>.</p>
				<p align="justify">
					Sin embargo, sabemos lo importante que es para ti encontrar el lugar adecuado para que cuiden a tu peludo, 
					por lo que te compartimos estas opciones con características similares a tu búsqueda original. Solo debes seguir los siguientes pasos:
				</p>
				'.$lista_cercanos.'
				<p align="justify">Si tienes alguna duda o comentario de la cancelación con todo gusto puedes contactarnos.</p>
			</div>
	    ';
	    
   		$msg_cliente = kmimos_get_email_html("Cancelación de Reserva", $msg, "", true, true);
   		wp_mail( $cliente_email, "Cancelación de Reserva", $msg_cliente);

    } else {
		$order->update_status('wc-on-hold');
		$booking->update_status('confirmed');

		$msg = $styles.'
	    	<p><strong>Confirmación de Reserva (N°. '.$reserva_id.')</strong></p>
			<p>Hola <strong>'.$nom_cuidador.'</strong></p>
			<p align="justify">Siguiendo tu solicitud hacia el Staff Kmimos, la reserva del cliente <strong>'.$nom_cliente.'</strong> ha sido confirmada para dar el servicio.</p>'
			.$detalles_cliente
			.$detalles_mascotas
			.$detalles_servicio_cuidador.'
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

   		echo $msg_cuidador = kmimos_get_email_html("Confirmación de Reserva", $msg, "", true, true);
   		wp_mail( $cuidador_email, "Confirmación de Reserva", $msg_cuidador);

		$msg_admin = $styles.'
	    	<p><strong>Confirmación de Reserva (N°. '.$reserva_id.')</strong></p>
			<p>Hola <strong>Administrador</strong>,</p>
			<p align="justify">Te notificamos que el cuidador <strong>'.$nom_cuidador.'</strong> ha <strong>Confirmado</strong> la reserva N° <strong>'.$reserva_id.'</strong>.</p>'
			.$detalles_cliente
			.$detalles_cuidador
			.$detalles_mascotas
			.$detalles_servicio_cuidador;

   		$msg_admin = kmimos_get_email_html("Confirmación de Reserva", $msg_admin, "", true, true);
   		wp_mail( $email_admin, "Confirmación de Reserva", $msg_admin, kmimos_mails_administradores());

   		$nota_importante = $styles.'
   			<p align="justify"><strong>Importante:</strong></p>
   			<p align="justify">Si necesitaras cancelar el servicio te pedimos que notifiques al cuidador y al Staff Kmimos con 48 horas de anticipación a la fecha de inicio de la reserva, de lo contrario se cobrará un monto del 20% sobre el total de la reserva por concepto de cancelación tardía.</p>';

		$msg_cliente = $styles.'
			<p align="center">¡Todo está listo <strong>'.$nom.'</strong>!</p>
			<p align="justify">Tu reserva (N°. '.$reserva_id.') ha sido confirmada por el cuidador <strong>'.$nom_cuidador.'</strong>.</p>
	    	<p>Detalles de la reserva:</p>'
			.$detalles_cuidador
			.$detalles_mascotas
			.$detalles_servicio
			.$nota_importante;

		$msg_cliente = kmimos_get_email_html("Confirmación de Reserva", $msg_cliente, "", true, true);
   		wp_mail( $cliente_email, "Confirmación de Reserva", $msg_cliente);


   		// ********************************************************************
   		// BEGIN Notificacion para usuario referidos - Landing WOM /Referidos
   		// ********************************************************************
	   		$user_info = get_user_by( 'email', $cliente_email );
	   		if(isset($user_info->ID)){	
	   			global $wpdb;
				$count_reservas = $wpdb->get_results( 
					"SELECT  
						count(ID) as cant
					FROM wp_posts
					WHERE post_type = 'wc_booking' 
						AND not post_status like '%cart%' AND post_status = 'confirmed' 
						AND post_author = {$user_info->ID}
						AND DATE_FORMAT(post_date, '%m-%d-%Y') between DATE_FORMAT('2017-05-12','%m-%d-%Y') and DATE_FORMAT(now(),'%m-%d-%Y')"
				);

		   		$user_referido = get_user_meta($user_info->ID, 'landing-referencia', true);

		   		if(!empty($user_referido)){
					$username = $nom_cliente;
					require_once('../../../landing/email_template/club-referido-primera-reserva.php');
					$user_participante = $wpdb->get_results( "
						select ID, user_email 
						from wp_users 
						where md5(user_email) = '{$user_referido}'" 
					);
					$user_participante = (count($user_participante)>0)? $user_participante[0] : [];

					if(isset($user_participante->user_email)){
						$mensaje_reserva_partitipante = kmimos_get_email_html(
							"Club de las patitas felices",
							$mensaje_reserva_partitipante,
							'', true, true);
						
						wp_mail( $user_participante->user_email, 
								"¡Felicidades, otro perrhijo moverá su colita de felicidad!", 
								$mensaje_reserva_partitipante );

					}
				} 
			}
   		// ********************************************************************
   		// END Notificacion para usuario referidos - Landing WOM /Referidos
   		// ********************************************************************

    }

?>