<?php

	global $wpdb;
	$sql = "SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_author = {$user_id} AND post_status NOT LIKE '%cart%' ORDER BY id DESC";
	$reservas = $wpdb->get_results($sql);

	if( count($reservas) > 0 ){

		$reservas_array = array(
			"pendientes_tienda" => array(
				"titulo" => 'Reservas pendientes por pagar en tienda por conveniencia',
				"reservas" => array()
			),
			"pendientes_confirmar" => array(
				"titulo" => 'Reservas Pendientes por Confirmar',
				"reservas" => array()
			),
			"confirmadas" => array(
				"titulo" => 'Reservas Confirmadas',
				"reservas" => array()
			),
			"completadas" => array(
				"titulo" => 'Reservas Completadas',
				"reservas" => array()
			),
			"canceladas" => array(
				"titulo" => 'Reservas Canceladas',
				"reservas" => array()
			),
			"modificadas" => array(
				"titulo" => 'Reservas Modificadas',
				"reservas" => array()
			),
			"error" => array(
				"titulo" => 'Reservas en error en tarjetas de credito',
				"reservas" => array()
			),
			"otros" => array(
				"titulo" => 'Otras Reservas',
				"reservas" => array()
			)
		);

		//PENDIENTE POR PAGO EN TIENDA DE CONVENINCIA
		foreach($reservas as $key => $reserva){

			$_metas_reserva = get_post_meta($reserva->ID);
			$_metas_orden = get_post_meta($reserva->post_parent);

			$servicio = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);

			$reserva_status = $reserva->post_status;
			$orden_status = $wpdb->get_var("SELECT post_status FROM $wpdb->posts WHERE ID = ".$reserva->post_parent);

			$creada = strtotime( $reserva->post_date );
			$inicio = strtotime( $_metas_reserva['_booking_start'][0] );
			$fin    = strtotime( $_metas_reserva['_booking_end'][0] );

			$foto = kmimos_get_foto( $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]) ) ;

			$pdf = $_metas_orden['_openpay_pdf'][0];
			$ver = $reserva->post_parent;
			$cancelar = $reserva->post_parent;
			$modificar = md5($reserva->ID)."_".md5($user_id)."_".md5($servicio->ID);
			$valorar = $reserva->ID;

			$xitems = $wpdb->get_results( "SELECT meta_key, meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = ".$_metas_reserva["_booking_order_item_id"][0] );
			$items = array();
			foreach ($xitems as $item) {
				$items[ $item->meta_key ] = $item->meta_value;
			}

			$pago = unserialize($items["_wc_deposit_meta"]);

			$desglose = $pago;
			if( $pago["enable"] == "yes" ){
				$desglose["descuento"] = $_metas_orden["_cart_discount"][0];
				$desglose["tipo"] = "DEPÓSITO DEL 17%";
			}else{
				$desglose["subtotal"] = $items["_line_subtotal"]-$_metas_orden["_cart_discount"][0];
				$desglose["total"] = $items["_line_subtotal"];
				$desglose["descuento"] = $_metas_orden["_cart_discount"][0];
				$desglose["tipo"] = "PAGO TOTAL";
			}

			//RESERVAS PENDIENTES POR ERROR DE PAGOS DE TARJETAS
			if($orden_status == 'wc-pending') {

			}else if($orden_status == 'wc-on-hold' && ( $_metas_orden['_payment_method'][0] == 'openpay_stores' || $_metas_orden['_payment_method'][0] == 'tienda' ) ){

				$reservas_array["pendientes_tienda"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"modificar" => $modificar,
						"cancelar" => $cancelar,
						"pdf" => $pdf
					),
					"desglose" => $desglose
				);

				//RESERVAS CONFIRMADAS
			}else if($reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])>time()){
				
				$reservas_array["confirmadas"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"modificar" => $modificar
					),
					"desglose" => $desglose
				);

				//RESERVAS COMPLETADAS
			}else if($reserva->post_status=='complete' || ($reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])<time())){

				$reservas_array["completadas"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"valorar" => $valorar
					),
					"desglose" => $desglose
				);

				//RESERVAS CANCELADAS
			}else if($reserva->post_status=='cancelled' || $reserva->post_status=='wc_cancelled'){

				$reservas_array["canceladas"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver
					),
					"desglose" => $desglose
				);

			//RESERVAS MODIFICADAS
			}else if($reserva->post_status=='modified'){

				$reservas_array["modificadas"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver
					),
					"desglose" => $desglose
				);

			//RESERVAS PNDIENTES POR CONFIRMAR
			}else if($reserva->post_status!='confirmed'){

				$reservas_array["pendientes_confirmar"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"modificar" => $modificar,
						"cancelar" => $cancelar,
					),
					"desglose" => $desglose
				);

			}else{

				$reservas_array["otros"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver
					),
					"desglose" => $desglose
				);

			}
		}
		
		//BUILD TABLE
		$CONTENIDO .= '
			<h1 style="margin: 0px; padding: 0px;">Mi Historial de Reservas</h1><hr style="margin: 5px 0px 10px;">
			<div class="kmisaldo">
			<strong>'.kmimos_saldo_titulo().':</strong> MXN $'.kmimos_get_kmisaldo().'
		</div>'.
		construir_listado($reservas_array);
	}else{
		$CONTENIDO .= "<h1 style='line-height: normal;'>Usted aún no tiene reservas.</h1><hr>";
	}

?>