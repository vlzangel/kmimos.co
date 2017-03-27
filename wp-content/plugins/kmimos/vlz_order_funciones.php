<?php
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

				case 'Ba&tilde;o (precio por mascota)':
					$data = explode(" ", $value->meta_key);
					$data[5] = str_replace("(", "", $data[5]);
					$data[5] = str_replace(")", "", $data[5]);
					$data[5] = substr($data[5], 5);
					$adicionales_array[] = array(
						'Baño',
						$data[5]+0
					);
				break;
				
				case 'Corte de Pelo y U&tilde;as (precio por mascota)':
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
			"gigantes" => "Mascotas Gigantes"
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
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7;"> '.$txts[$key].' </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" align="center"> '.$detalles_reserva[$value].' </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" align="center"> '.$dias.' '.$dias_noches.' </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" align="right"> '.number_format( $unitario, 2, ',', '.').'$ </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right"> '.number_format( ($unitario*$detalles_reserva[$value]*$dias), 2, ',', '.').'$ </td>
				</tr>';

				$grupo += $detalles_reserva[$value];

			}
		}

		if( count($adicionales_array) > 0 ){

			$adicionales = '<tr> <td style="padding: 3px; background: #00d2b7; border-left: solid 1px #00d2b7; font-weight: 800;" colspan=5> Servicios Adicionales </td> </tr>';
			foreach ($adicionales_array as $key => $value) {
				$servicio = $value[0];
				$costo = ($value[1]);
				$adicionales .= '
				<tr>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7;" colspan=2> '.$servicio.' </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" align="center"> '.$grupo.' Mascota(s) </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" align="right"> '.number_format( $costo, 2, ',', '.').'$ </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right"> '.number_format( ($costo*$grupo), 2, ',', '.').'$ </td>
				</tr>';
			}

		}

		if( count($transporte) > 0 ){
			
			$transporte_str = '<tr> <td style="padding: 3px; background: #00d2b7; border-left: solid 1px #00d2b7; font-weight: 800;" colspan=5> Servicio de Transporte </td> </tr>';
			foreach ($transporte as $key => $value) {
				$servicio = $value[0];
				$costo = ($value[1]);
				$transporte_str .= '
				<tr>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7;" colspan=2> '.$servicio.' </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" align="center"> Precio por Grupo </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" align="right"> '.number_format( $costo, 2, ',', '.').'$ </td>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right"> '.number_format( $costo, 2, ',', '.').'$ </td>
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
						<td></td>
						<th style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7; text-align: left;">Descuento</th>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right">'.number_format( $metas_orden["_cart_discount"][0], 2, ',', '.').'$</td>
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
						<td></td>
						<th style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7; text-align: left;">Descuento</th>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right">'.number_format( $metas_orden["_cart_discount"][0], 2, ',', '.').'$</td>
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
					<td></td>
					<th style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7;">Total</th>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right">'.number_format( $pago, 2, ',', '.').'$</td>
				</tr>
				'.$descuento_total.'
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<th style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7;">Pago en Tienda</th>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right">'.number_format( $remanente['deposit'], 2, ',', '.').'$</td>
				</tr>
				'.$descuento_parcial.'
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<th style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7;">Pago al Cuidador</th>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right">'.number_format( $remanente['remaining'], 2, ',', '.').'$</td>
				</tr>
			';
		}else{

			$totales = '
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<th style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7; text-align: left;">Total</th>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right">'.number_format( $pago, 2, ',', '.').'$</td>
				</tr>
				'.$descuento_total.'
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<th style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7; text-align: left;">Pagado</th>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right">'.number_format( $remanente['deposit'], 2, ',', '.').'$</td>
				</tr>
				'.$descuento_parcial.'
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<th style="padding: 3px; border-bottom: solid 1px #00d2b7; border-left: solid 1px #00d2b7; text-align: left;">Pago al Cuidador</th>
					<td style="padding: 3px; border-bottom: solid 1px #00d2b7; border-right: solid 1px #00d2b7;" align="right">'.number_format( $remanente['remaining'], 2, ',', '.').'$</td>
				</tr>
			';

		}

		$detalles_servicio .= '
			<h2 style="color:#557da1;font-size: 16px;font-weight: 600; font-size: 16px;">Detalles del Servicio Reservado</h2>
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
			</table>
			<br>
			<table style="width:100%" cellspacing=0 cellpadding=0>
				<tr>
					<th style="padding: 3px; background: #00d2b7; border-left: solid 1px #00d2b7;"> Tamaño </th>
					<th style="padding: 3px; background: #00d2b7;"> Num. Mascotas </th>
					<th style="padding: 3px; background: #00d2b7;"> Tiempo </th>
					<th style="padding: 3px; background: #00d2b7;"> Precio Unitario </th>
					<th style="padding: 3px; background: #00d2b7; border-right: solid 1px #00d2b7;"> Precio Total </th>
				</tr>
				'.$variaciones.'
				'.$transporte_str.'
				'.$adicionales.'
				'.$totales.'
			</table>
		';
?>