<?php
	global $wpdb;

	$id = $order->id;

	/* Reservas */
		$reserva = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_parent = '".$id."'");

		$metas_orden = get_post_meta($id);
		$metas_reserva = get_post_meta( $reserva->ID );

		$id_reserva = $reserva->ID;

	/* Producto */
		$producto = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '".$metas_reserva['_booking_product_id'][0]."'");

		$tipo_servicio = explode("-", $producto->post_title);
		$tipo_servicio = $tipo_servicio[0];

		$metas_producto = get_post_meta( $producto->ID );

	/* Cuidador */

		$cuidador_post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '".$producto->post_parent."'");
		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = '".$producto->post_author."'");

		$detalles_cuidador = '
			<p style="color:#557da1;font-size: 16px;font-weight: 600;">Datos del Cuidador</p>
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td style="width: 70px;"> <strong>Nombre:</strong> </td>
					<td>'.$cuidador_post->post_title.'</td>
				</tr>
				<tr>
					<td> <strong>Teléfono:</strong> </td>
					<td>'.$cuidador->telefono.'</td>
				</tr>
				<tr>
					<td> <strong>Correo:</strong> </td>
					<td>'.$cuidador->email.'</td>
				</tr>
				<tr>
					<td> <strong>Dirección: </strong> </td>
					<td> '.$cuidador->direccion.'</td>
				</tr>
			</table>
		';

	/* Clientes */
		$cliente = $metas_orden["_customer_user"][0];

		if( $cliente == 0 ){
			$temp_email = $metas_orden["_billing_email"][0];
			$cliente = get_var("SELECT ID FROM wp_users WHERE user_email = '{$temp_email}'");
		}
		
		$metas_cliente = get_user_meta($cliente);

		$nombre = $metas_cliente["first_name"][0];
		$apellido = $metas_cliente["last_name"][0];
		$dir = $metas_cliente["user_address"][0];

		$telf = $metas_cliente["user_phone"][0];
		if( $telf == "" ){
			$telf = $metas_cliente["user_mobile"][0];
		}
		if( $telf == "" ){
			$telf = "No registrado";
		}

		if( $dir == "" ){
			$dir = "No registrada";
		}

		$user = get_user_by( 'id', $cliente );

		$detalles_cliente = '
			<p align="justify" style="color:#557da1;font-size: 16px;font-weight: 600;">Datos del Cliente</p>
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td style="width: 70px;"> <strong>Nombre:</strong> </td>
					<td>'.$nombre.' '.$apellido.'</td>
				</tr>
				<tr>
					<td> <strong>Teléfono:</strong> </td>
					<td>'.$telf.'</td>
				</tr>
				<tr>
					<td> <strong>Correo:</strong> </td>
					<td>'.$user->data->user_email.'</td>
				</tr>
				<tr>
					<td> <strong>Dirección: </strong> </td>
					<td> '.$dir.'</td>
				</tr>
			</table>
		';

		$cliente_email = $user->data->user_email;

	/* Mascotas */

		$mascotas = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_author = '".$cliente."' AND post_type='pets'");
		$detalles_mascotas = "";
		$detalles_mascotas .= '
			<h2 style="color: #557da1; font-size: 16px;">Detalles de las mascotas: </h2>
			<table style="width:100%" cellspacing=0 cellpadding=0>
				<tr>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Nombre</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Raza</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Edad</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Tamaño</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Comportamiento</strong> </th> 
				</tr>';

		$comportamientos_array = array(
			"pet_sociable" 			 => "Sociables",
			"pet_sociable2" 		 => "No sociables",
			"aggressive_with_pets"   => "Agresivos con perros",
			"aggressive_with_humans" => "Agresivos con humanos",
		);
		$tamanos_array = array(
			"Pequeño",
			"Mediano",
			"Grande",
			"Gigante"
		);
		if( count($mascotas) > 0 ){
			foreach ($mascotas as $key => $mascota) {
				$data_mascota = get_post_meta($mascota->ID);

				$temp = array();
				foreach ($data_mascota as $key => $value) {

					switch ($key) {
						case 'pet_sociable':
							if( $value[0] == 1 ){
								$temp[] = "Sociable";
							}else{
								$temp[] = "No sociable";
							}
						break;
						case 'aggressive_with_pets':
							if( $value[0] == 1 ){
								$temp[] = "Agresivo con perros";
							}
						break;
						case 'aggressive_with_humans':
							if( $value[0] == 1 ){
								$temp[] = "Agresivo con humanos";
							}
						break;
					}

				}

				$anio = explode("-", $data_mascota['birthdate_pet'][0]);
				$edad = date("Y") - $anio[0];

				$raza = $wpdb->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet'][0]);

				$detalles_mascotas .= '
					<tr>
						<td style="border-bottom: solid 1px #00d2b7; padding: 3px;" valign="top"> '.$data_mascota['name_pet'][0].'</td>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$raza.'</td>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$edad.' año(s)</td>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$tamanos_array[ $data_mascota['size_pet'][0] ].'</td>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.implode("<br>", $temp).'</td>
					</tr>
				';
			}
		}else{
			$detalles_mascotas .= '
				<tr>
					<td colspan="5">No tiene mascotas registradas.</td>
				</tr>
			';
		}
		$detalles_mascotas .= '</table>';

	/* Detalles del servicio */

		$detalles = kmimos_desglose_reserva($id, true);

		$msg_id_reserva = $detalles["msg_id_reserva"];
		$aceptar_rechazar = $detalles["aceptar_rechazar"];
		$detalles_servicio = $detalles["detalles_servicio"];

?>