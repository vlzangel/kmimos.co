<?php
	
	/* Administrador */

		$saludo = "<p>Hola <strong>Administrador</strong>,</p>";
		$msg_id_reserva ='<p>Se ha pagado la Reserva <strong># '.$reserva_id.'</strong> </p>';
		
		$mensaje_admin 	= 
			$saludo.
	  		$msg_id_reserva.
	  		"<br>".$aceptar_rechazar.
  			$detalles_cliente.
	  		$detalles_cuidador.
	  		$detalles_mascotas.
	  		$detalles_servicio_cuidador.
	  		"<br>".$aceptar_rechazar
		;

		if( $modificacion == "" ){
			$titulo_mail = 'Nueva Reserva - '.$producto;
		}else{
			$titulo_mail = $modificacion;
		}

		$mensaje_admin = kmimos_get_email_html($titulo_mail, $mensaje_admin, 'Nueva Reserva - '.$producto, true, true);

		//wp_mail( $email_admin, "Solicitud de reserva #".$reserva_id, $mensaje_admin);

		kmimos_mails_administradores_new("Solicitud de reserva #".$reserva_id, $mensaje_admin);

	/* Correo Cliente */

		$msg_id_reserva ='<p>Solicitud de reserva de servicio <strong>(N° '.$reserva_id.')</strong> </p>';

		$saludo = '
			<center style="font-size: 16px; font-weight: 600;">¡Gracias '.$nom_cliente.'!</center>
			<p>Recibimos tu solicitud de reserva de <strong>'.$tipo_servicio.'</strong>, para que <strong>'.$nom_cuidador.'</strong> atienda a tu(s) peludo(s).</p>
			<p align="justify">
				Dentro de las próximas 12 horas el cuidador confirmará la disponibilidad para tu solicitud. 
				Posterior a que el cuidador confirme, te llegará un correo indicando que está <u>confirmada</u> tu reserva (el período de 12 horas es referencial, 
				normalmente los cuidadores contactan al clientes con mucha anterioridad).
			</p>
			<p align="justify">
				Si no puedes o decides no esperar las 12 horas no te preocupes, puedes contactar directamente al cuidador a través del teléfono o correo mostrados más abajo.
			</p>
			<p align="justify">
				En caso de que el cuidador no esté disponible, recibirás un correo de notificación con instrucciones para que puedas solicitar el reembolso del importe realizado.
			</p>
			<h2>Detalles de tu reservación pendiente a confirmar:</h2>
		';

		$mensaje_cliente = 	
			$msg_id_reserva.
			$saludo.
			$detalles_cuidador.
			$detalles_mascotas.
			$detalles_servicio.
	  		$dudas
		;

		if( $modificacion == "" ){
			$titulo_mail = 'Solicitud de reserva';
		}else{
			$titulo_mail = $modificacion;
		}
		
		$mensaje_cliente = kmimos_get_email_html($titulo_mail, $mensaje_cliente, 'Solicitud de reserva', true, true);

		wp_mail( $cliente_email, "Solicitud de reserva", $mensaje_cliente);

	/*
		Correo Cuidador
	*/

		$msg_id_reserva ='<p>Reserva #: <strong>'.$reserva_id.'</strong> </p>';

		$saludo = '
			<p>Hola <strong>'.$nom_cuidador.'</strong>,</p>
			<p>El cliente <strong>'.$nom_cliente.'</strong> te ha enviado una solicitud de Reserva.</p>
			'.$aceptar_rechazar.'
			<h2>Detalles de la solicitud:</h2>
		';

		$siguientes_pasos = '
			<br><p align="center"><strong><u>SIGUIENTES PASOS - Importante</u></strong></p>
			<ul>
				<li> <p align="justify">Es necesario que en las 8 horas a partir de que recibiste esta solicitud confirmes si aceptas el servicio.  En caso de no llevarlo a cabo, el sistema cancelará esta solicitud y enviará automáticamente una recomendación al cliente sobre otros cuidadores sugeridos para atenderlo.</p> </li>
				<li> <p align="justify">Si existe algún cambio en la reserva relacionada a horarios, precios o servicios adicionales por favor asegúrate que el cliente está enterado y que está de acuerdo con los cambios,  posteriormente contacta al Staff Kmimos a la brevedad para realizar los ajustes.</p> </li>
			</ul>
			<br>
			<p align="justify"><strong><u>Puntos a considerar para tu reservación</u></strong></p>
			<br>
			<ul>
				<li><p align="justify">Preséntate con el cliente de una manera cordial, formal y cuidando tu imagen. (Vestimenta casual).</p></li>
				<li><p align="justify">Coméntale la experiencia que tienes cuidando perros y cuál es la rutina que utilizas.</p></li>
				<li><p align="justify">Verifica que el perro del dueño tenga sus vacunas y te compartan su cartilla de vacunación.</p></li>
				<br>
				<li><p align="justify"><strong>IMPORTANTE: </strong>Sin cartilla de vacunación, no estarán amparados ni tú ni el perro ante los beneficios veterinarios de Kmimos.</p></li>
				<br>
				<li><p align="justify">En caso de no conocerse personalmente, asegúrate de que te envíen fotos del perro que llegará a tu casa para confirmar que el tamaño se adecúa a lo declarado por el cliente.</p></li>
			</ul>
			<br>
			<p align="justify">Recuerda que cada perro tiene un comportamiento diferente, por lo que deberás tener la mayor información posible sobre los mismos:</p>
			<ul>
				<li><p>¿Cómo es su rutina diaria? </p>
					<ul> <li> <p>¿Sale a pasear?, ¿A qué hora come y hace del baño?, etc.</p> </li> </ul>
				</li>
				<li><p>¿Cómo se comporta con otros seres vivos? </p>
					<ul> <li> <p>¿Cómo interactúa con otros perros y personas?, ¿Cómo reacciona con un extraño?, etc.</p> </li> </ul>
				</li>
				<li><p>¿Cómo identifica su animó?  </p>
					<ul> <li> <p>¿Cómo se comporta cuando esta triste o estresado?, ¿Qué hace su dueño cuando esta triste o estresado?, etc.</p> </li> </ul>
				</li>
			</ul>
		'; 

		$mensaje_cuidador  	= 	
			$saludo.
			$msg_id_reserva.
	  		$detalles_cuidador.
	  		$detalles_cliente.
	  		$detalles_mascotas.
	  		$detalles_servicio_cuidador.
	  		"<br>".$aceptar_rechazar.
	  		$siguientes_pasos.
	  		$dudas
		;

		if( $modificacion == "" ){
			$titulo_mail = 'Nueva Reserva - '.$tipo_servicio.' por: '.$nom_cliente;
		}else{
			$titulo_mail = $modificacion;
		}
		
		$mensaje_cuidador = kmimos_get_email_html($titulo_mail, $mensaje_cuidador, 'Nueva Reserva - '.$tipo_servicio.' por: '.$nom_cliente, true, true);

		wp_mail( $cuidador_email, 'Nueva Reserva - '.$tipo_servicio.' por: '.$nom_cliente, $mensaje_cuidador);

		//kmimos_mails_administradores_new('Nueva Reserva - '.$tipo_servicio.' por: '.$nom_cliente, $mensaje_cuidador);

?>