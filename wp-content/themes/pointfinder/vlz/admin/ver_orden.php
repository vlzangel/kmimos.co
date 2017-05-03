<?php
	global $wpdb;

	$id = $orden;

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
		$metas_cuidador = get_user_meta($producto->post_author);

		$telf = $metas_cuidador["user_phone"][0];
		if( $telf == "" ){
			$telf = $metas_cuidador["user_mobile"][0];
		}
		if( $telf == "" ){
			$telf = "No registrado";
		}
		if( $dir == "" ){
			$dir = "No registrada";
		}

		$detalles_cuidador = '
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td style="width: 70px;"> <strong>Nombre:</strong> </td>
					<td>'.$cuidador_post->post_title.'</td>
				</tr>
				<tr>
					<td> <strong>Teléfono:</strong> </td>
					<td>'.$telf.'</td>
				</tr>
				<tr>
					<td> <strong>Correo:</strong> </td>
					<td>'.$cuidador->email.'</td>
				</tr>
			</table>
		';

	/* Clientes */
		$cliente = $metas_orden["_customer_user"][0];
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

		$user = get_user_by( 'id', $cliente );

		$detalles_cliente = '
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
			</table>
		';

		$cliente_email = $user->data->user_email;

	/* Mascotas */

		$mascotas = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_author = '".$cliente."' AND post_type='pets'");
		$detalles_mascotas = "";
		$detalles_mascotas .= '
			<table style="width:100%" cellspacing=0 cellpadding=0>
				<tr>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> <strong>Nombre</strong> </th>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> <strong>Raza</strong> </th>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> <strong>Edad</strong> </th>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> <strong>Tamaño</strong> </th>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> <strong>Comportamiento</strong> </th> 
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
						<td style=" padding: 3px;" valign="top"> '.$data_mascota['name_pet'][0].'</td>
						<td style="padding: 3px; " valign="top"> '.$raza.'</td>
						<td style="padding: 3px; " valign="top"> '.$edad.' año(s)</td>
						<td style="padding: 3px; " valign="top"> '.$tamanos_array[ $data_mascota['size_pet'][0] ].'</td>
						<td style="padding: 3px; " valign="top"> '.implode("<br><br>", $temp).'</td>
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

		function vlz_limpiar($txt){
			$txt = str_replace("(", "", $txt);
			$txt = str_replace(")", "", $txt);
			return $txt = substr($txt, 5, -3);
		}

		$inicio = $metas_reserva['_booking_start'][0];
		$fin    = $metas_reserva['_booking_end'][0];

		$xini = strtotime( substr($inicio, 0, 4)."-".substr($inicio, 4, 2)."-".substr($inicio, 6, 2) );
		$xfin = strtotime( substr($fin, 0, 4)."-".substr($fin, 4, 2)."-".substr($fin, 6, 2) );

		$inicio = substr($inicio, 6, 2)	."/".substr($inicio, 4, 2)."/".substr($inicio, 0, 4);
		$fin    = substr($fin, 6, 2)	."/".substr($fin, 4, 2)	  ."/".substr($fin, 0, 4);

		$id_orden_item = $metas_reserva['_booking_order_item_id'][0];

		$orden_item = $wpdb->get_results("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '".$id_orden_item."'");

		$adicionales_array = array();
		$transporte  = array();
		foreach ($orden_item as $key => $value) {

			switch ($value->meta_value) {

				case 'Transp. Sencillo - Rutas Cortas':

					$data = explode(" ", $value->meta_key);
					$data[6] = str_replace("(", "", $data[6]);
					$data[6] = str_replace(")", "", $data[6]);
					$data[6] = substr($data[6], 5);
					$transporte[] = array(
						$value->meta_value,
						$data[6]+0
					);

                break;

                case 'Transp. Sencillo - Rutas Medias':

					$data = explode(" ", $value->meta_key);
					$data[6] = str_replace("(", "", $data[6]);
					$data[6] = str_replace(")", "", $data[6]);
					$data[6] = substr($data[6], 5);
					$transporte[] = array(
						$value->meta_value,
						$data[6]+0
					);

                break;

                case 'Transp. Sencillo - Rutas Largas':

					$data = explode(" ", $value->meta_key);
					$data[6] = str_replace("(", "", $data[6]);
					$data[6] = str_replace(")", "", $data[6]);
					$data[6] = substr($data[6], 5);
					$transporte[] = array(
						$value->meta_value,
						$data[6]+0
					);

                break;

                case 'Transp. Redondo - Rutas Cortas':

					$data = explode(" ", $value->meta_key);
					$data[6] = str_replace("(", "", $data[6]);
					$data[6] = str_replace(")", "", $data[6]);
					$data[6] = substr($data[6], 5);
					$transporte[] = array(
						$value->meta_value,
						$data[6]+0
					);

                break;

                case 'Transp. Redondo - Rutas Medias':

					$data = explode(" ", $value->meta_key);
					$data[6] = str_replace("(", "", $data[6]);
					$data[6] = str_replace(")", "", $data[6]);
					$data[6] = substr($data[6], 5);
					$transporte[] = array(
						$value->meta_value,
						$data[6]+0
					);

                break;

                case 'Transp. Redondo - Rutas Largas':

					$data = explode(" ", $value->meta_key);
					$data[6] = str_replace("(", "", $data[6]);
					$data[6] = str_replace(")", "", $data[6]);
					$data[6] = substr($data[6], 5);
					$transporte[] = array(
						$value->meta_value,
						$data[6]+0
					);

                break;

				case 'Baño (precio por mascota)':
					$data = explode(" ", $value->meta_key);
					$data[5] = str_replace("(", "", $data[5]);
					$data[5] = str_replace(")", "", $data[5]);
					$data[5] = substr($data[5], 5);
					$adicionales_array[] = array(
						'Baño',
						$data[5]+0
					);
				break;

				case 'Ba&ntilde;o (precio por mascota)':
					$data = explode(" ", $value->meta_key);
					$data[5] = str_replace("(", "", $data[5]);
					$data[5] = str_replace(")", "", $data[5]);
					$data[5] = substr($data[5], 5);
					$adicionales_array[] = array(
						'Baño',
						$data[5]+0
					);
				break;
				
				case 'Corte de Pelo y U&ntilde;as (precio por mascota)':
					$data = explode(" ", $value->meta_key);
					$data[5] = str_replace("(", "", $data[5]);
					$data[5] = str_replace(")", "", $data[5]);
					$data[5] = substr($data[5], 5);
					$adicionales_array[] = array(
						'Corte de Pelo y Uñas',
						$data[5]+0
					);
				break;
				
				case 'Corte de Pelo y Uñas (precio por mascota)':
					$data = explode(" ", $value->meta_key);
					$data[5] = str_replace("(", "", $data[5]);
					$data[5] = str_replace(")", "", $data[5]);
					$data[5] = substr($data[5], 5);
					$adicionales_array[] = array(
						'Corte de Pelo y Uñas',
						$data[5]+0
					);
				break;
				
				case 'Visita al Veterinario (precio por mascota)':
					$data = explode(" ", $value->meta_key);
					$data[5] = str_replace("(", "", $data[5]);
					$data[5] = str_replace(")", "", $data[5]);
					$data[5] = substr($data[5], 5);
					$adicionales_array[] = array(
						'Visita al Veterinario',
						$data[5]+0
					);
				break;
				
				case 'Limpieza Dental (precio por mascota)':
					$data = explode(" ", $value->meta_key);
					$data[5] = str_replace("(", "", $data[5]);
					$data[5] = str_replace(")", "", $data[5]);
					$data[5] = substr($data[5], 5);
					$adicionales_array[] = array(
						'Limpieza Dental',
						$data[5]+0
					);
				break;
				
				case 'Acupuntura (precio por mascota)':
					$data = explode(" ", $value->meta_key);
					$data[5] = str_replace("(", "", $data[5]);
					$data[5] = str_replace(")", "", $data[5]);
					$data[5] = substr($data[5], 5);
					$adicionales_array[] = array(
						'Acupuntura',
						$data[5]+0
					);
				break;

			}
		}

		$detalles_reserva = array();
		foreach ($orden_item as $key => $value) {
			$detalles_reserva[$value->meta_key] = $value->meta_value;
		}

		$variaciones_array = array(
			"pequenos" => "Mascotas Pequeños", 
			"medianos" => "Mascotas Medianos", 
			"grandes"  => "Mascotas Grandes", 
			"gigantes" => "Mascotas Gigantes",
			"pequenos" => "Mascotas Pequeñas", 
			"medianos" => "Mascotas Medianas"
		);

		$txts = array(
			"pequenos" => "Mascotas Pequeñas", 
			"medianos" => "Mascotas Medianas", 
			"grandes"  => "Mascotas Grandes", 
			"gigantes" => "Mascotas Gigantes"
		);

		$dias = (((($xfin - $xini)/60)/60)/24);

		$dias_noches = "Noches";
		if( trim($tipo_servicio) != "Hospedaje" ){
			$dias_noches = "Días";
			$dias++;
		}

		$variaciones = ''; $grupo = 0;
		foreach ($variaciones_array as $key => $value) {
			if( isset( $detalles_reserva[$value] ) ){

				$variacion_ID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_parent={$producto->ID} AND post_title='{$value}' ");
				$metas_variacion = get_post_meta($variacion_ID);

				$unitario = $metas_producto['_price'][0]+$metas_variacion['block_cost'][0];

				$variaciones .= '
				<tr>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;"> '.$txts[$key].' </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;" align="center"> '.$detalles_reserva[$value].' </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;" align="center"> '.$dias.' '.$dias_noches.' </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;" align="right"> '.number_format( $unitario, 2, ',', '.').'$ </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;" align="right"> '.number_format( ($unitario*$detalles_reserva[$value]*$dias), 2, ',', '.').'$ </td>
				</tr>';

				$grupo += $detalles_reserva[$value];

			}
		}

		if( count($adicionales_array) > 0 ){

			$adicionales = '<tr> <td style="padding: 3px; border-bottom: solid 1px #cccccc;  font-weight: 600;" colspan=5> Servicios Adicionales </td> </tr>';
			foreach ($adicionales_array as $key => $value) {
				$servicio = $value[0];
				$costo = ($value[1]);
				$adicionales .= '
				<tr>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " colspan=2> '.$servicio.' </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;" align="center"> '.$grupo.' Mascota(s) </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;" align="right"> '.number_format( $costo, 2, ',', '.').'$ </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right"> '.number_format( ($costo*$grupo), 2, ',', '.').'$ </td>
				</tr>';
			}

		}

		if( count($transporte) > 0 ){
			
			$transporte_str = '<tr> <td style="padding: 3px; border-bottom: solid 1px #cccccc;  font-weight: 600;" colspan=5> Servicio de Transporte </td> </tr>';
			foreach ($transporte as $key => $value) {
				$servicio = $value[0];
				$costo = ($value[1]);
				$transporte_str .= '
				<tr>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " colspan=2> '.$servicio.' </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;" align="center"> Precio por Grupo </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc;" align="right"> '.number_format( $costo, 2, ',', '.').'$ </td>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right"> '.number_format( $costo, 2, ',', '.').'$ </td>
				</tr>';
			}

		}

		$pago = ($detalles_reserva['_line_subtotal']);
		$remanente = unserialize($detalles_reserva['_wc_deposit_meta']);

		$descuento = "";

		if( $remanente['enable'] == "no" ){

			$remanente['deposit'] = $pago;

			if( $metas_orden["_cart_discount"][0] != "0" ){
				$descuento_total = '
					<tr>
						<td></td>
						<td></td>
						<th colspan=2 style="padding: 3px; border-bottom: solid 1px #cccccc; ">Descuento</th>
						<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right">'.number_format( $metas_orden["_cart_discount"][0], 2, ',', '.').'$</td>
					</tr>
				';

				$remanente['deposit'] = $remanente['deposit']-$metas_orden["_cart_discount"][0];
			}
			
		}else{

			if( $metas_orden["_cart_discount"][0] != "0" ){
				$descuento_parcial = '
					<tr>
						<td></td>
						<td></td>
						<th style="padding: 3px; border-bottom: solid 1px #cccccc; ">Descuento</th>
						<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right">'.number_format( $metas_orden["_cart_discount"][0], 2, ',', '.').'$</td>
					</tr>
				';

				$remanente['remaining'] = $remanente['remaining']-$metas_orden["_cart_discount"][0];
			}

		}

		if( $metas_orden["_payment_method"][0] == "openpay_stores" ){
			$totales = '
				<tr>
					<td></td>
					<td></td>
					<th colspan=2 style="padding: 3px; border-bottom: solid 1px #cccccc; ">Total</th>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right">'.number_format( $pago, 2, ',', '.').'$</td>
				</tr>
				'.$descuento_total.'
				<tr>
					<td></td>
					<td></td>
					<th colspan=2 style="padding: 3px; border-bottom: solid 1px #cccccc; ">Pago en Tienda</th>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right">'.number_format( $remanente['deposit'], 2, ',', '.').'$</td>
				</tr>
				'.$descuento_parcial.'
				<tr>
					<td></td>
					<td></td>
					<th colspan=2 style="padding: 3px; border-bottom: solid 1px #cccccc;  text-align: left;">Cliente debe pagar al Cuidador:<div style="color: red;">en efectivo, al llevar a la mascota</div></th>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right">'.number_format( $remanente['remaining'], 2, ',', '.').'$</td>
				</tr>
			';
		}else{

			$totales = '
				<tr>
					<td></td>
					<td></td>
					<th colspan=2 style="padding: 3px; border-bottom: solid 1px #cccccc;  text-align: left;">Total</th>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right">'.number_format( $pago, 2, ',', '.').'$</td>
				</tr>
				'.$descuento_total.'
				<tr>
					<td></td>
					<td></td>
					<th colspan=2 style="padding: 3px; border-bottom: solid 1px #cccccc;  text-align: left;">Pagado</th>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right">'.number_format( $remanente['deposit'], 2, ',', '.').'$</td>
				</tr>
				'.$descuento_parcial.'
				<tr>
					<td></td>
					<td></td>
					<th colspan=2 style="padding: 3px; border-bottom: solid 1px #cccccc;  text-align: left;">Cliente debe pagar al Cuidador:<div style="color: red;">en efectivo, al llevar a la mascota</div></th>
					<td style="padding: 3px; border-bottom: solid 1px #cccccc; " align="right">'.number_format( $remanente['remaining'], 2, ',', '.').'$</td>
				</tr>
			';

		}

		$detalles_servicio .= '
			<table>
				<tr>
					<td> <strong>Servicio:</strong> </td> <td> '.$tipo_servicio.' </td>
				</tr>
				<tr>
					<td> <strong>Desde:</strong> </td> <td> '.$inicio.' </td>
				</tr>
				<tr>
					<td> <strong>Hasta:</strong> </td> <td> '.$fin.' </td>
				</tr>
				<tr>
					<td> <strong>Duración:</strong> </td> <td> '.$dias.' '.$dias_noches.' </td>
				</tr>
				<tr>
					<td> <strong>Pagado con:</strong> </td> <td> '.$metas_orden['_payment_method_title'][0].' </td>
				</tr>
			</table>
		';

		$detalles_factura .= '
			<table style="width:100%" cellspacing=0 cellpadding=0>
				<tr>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> Tamaño </th>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> Num. Mascotas </th>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> Tiempo </th>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc; width: 150px;"> Precio Unitario </th>
					<th style="padding: 3px; border-bottom: solid 1px #cccccc;"> Precio Total </th>
				</tr>
				'.$variaciones.'
				'.$transporte_str.'
				'.$adicionales.'
				'.$totales.'
			</table>
		';

		$msg_id_reserva ='<p>Reserva #: <strong>'.$reserva->ID.'</strong> </p>';

		$aceptar_rechazar = '
			<center>
				<p><strong>¿ACEPTAS ESTA RESERVA?</strong></p>
				<table>
					<tr>
						<td>
							<a href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$id.'&s=1&t=1" style="text-decoration: none; padding: 7px 0px; border-bottom: solid 1px #cccccc; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">Aceptar</a>
						</td>
						<td>
							<a href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$id.'&s=0&t=1" style="text-decoration: none; padding: 7px 0px; background: #dc2222; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">Rechazar</a>
						</td>
					</tr>
				</table>
			</center>
		';

		$titulo = '<h2>Detalles de la solicitud:</h2>';
?>