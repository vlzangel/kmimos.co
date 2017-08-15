<?php
	// Modificacion Ángel Veloz
	include("../../../../../../vlz_config.php");

	extract($_GET);

	session_start();

	$conn = new mysqli($host, $user, $pass, $db);

/*	session_destroy();

	session_start();*/

	if( isset($a) ){
		$param = explode("_", $a);

		$r = $conn->query("SELECT * FROM wp_posts WHERE md5(ID) = '{$param[2]}'"); $data = $r->fetch_assoc();	
		$home = $conn->query("SELECT option_value AS server FROM wp_options WHERE option_name = 'siteurl'"); $home = $home->fetch_assoc();

		$r2 = $conn->query("SELECT * FROM wp_postmeta WHERE md5(post_id) = '{$param[0]}'"); 
		$metas_reserva = array(); $id_reserva = 0;
		while ( $f = $r2->fetch_assoc() ) {
			$metas_reserva[ $f['meta_key'] ] = $f['meta_value'];
			if( $id_reserva == 0 ){
				$id_reserva = $f['post_id'];
			}
		}

		$orden = $conn->query("SELECT * FROM wp_posts WHERE ID = '{$id_reserva}'"); 
		$orden = $orden->fetch_assoc();
		$orden_id = $orden['post_parent'];

		$order = $conn->query("SELECT * FROM wp_posts WHERE ID = '{$orden_id}'"); $order = $order->fetch_assoc();

		$meta_orden = $conn->query("SELECT * FROM wp_postmeta WHERE post_id = '{$orden_id}'"); 
		$metas_orden = array();
		while ( $f = $meta_orden->fetch_assoc() ) {
			$metas_orden[ $f['meta_key'] ] = $f['meta_value'];
		}

		$descuento = 0;
		if( isset( $metas_orden[ "_cart_discount" ] ) ){
			$descuento = $metas_orden[ "_cart_discount" ];
		}

		$r3 = $conn->query("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '{$metas_reserva['_booking_order_item_id']}'"); 

		$items = array();
		while ( $f = $r3->fetch_assoc() ) {
			$items[ $f['meta_key'] ] = $f['meta_value'];
		}

		if( $order['post_status'] == 'wc-on-hold' && $metas_orden['_payment_method'] == 'openpay_stores'){ }else{
			$deposito = unserialize( $items['_wc_deposit_meta'] );
			$saldo = 0;
			if( $deposito['enable'] == 'yes' ){
				$saldo = $deposito['deposit'];
			}else{
				$saldo = $items['_line_total'];
			}
		}

		$variaciones = unserialize( $metas_reserva['_booking_persons'] );

		$fechas = array(
			"inicio" => date('d-m-Y', strtotime( $metas_reserva['_booking_start'] ) ),
			"fin" 	 => date('d-m-Y', strtotime( $metas_reserva['_booking_end']   ) )
		);

		$trans = array(
            "transp-sencillo-rutas-cortas" => 'Transp. Sencillo - Rutas Cortas',
            "transp-sencillo-rutas-medias" => 'Transp. Sencillo - Rutas Medias',
            "transp-sencillo-rutas-largas" => 'Transp. Sencillo - Rutas Largas',
            "transp-redondo-rutas-cortas" => 'Transp. Redondo - Rutas Cortas',
            "transp-redondo-rutas-medias" => 'Transp. Redondo - Rutas Medias',
            "transp-redondo-rutas-largas" => 'Transp. Redondo - Rutas Largas'
        );

		$adic = array(
            "bano" => 'Baño (precio por mascota)',
            "corte" => 'Corte de Pelo y Uñas (precio por mascota)',
            "visita" => 'Visita al Veterinario (precio por mascota)',
            "limpieza" => 'Limpieza Dental (precio por mascota)',
            "acupuntura" => 'Acupuntura (precio por mascota)'
        );

		$transporte = array();
		$adicionales = array();

		foreach ($items as $key => $value) {
			$retorno = array_search(utf8_encode($value), $trans);

			if( $retorno ){
				$transporte[] = $retorno;
			}
			$retorno = array_search(utf8_encode($value), $adic);
			if( $retorno ){
			
				echo utf8_encode($value)."<br>";
				$adicionales[] = $retorno;
			}
		}

		$sql = "SELECT meta_value FROM wp_usermeta WHERE md5(user_id) = '{$param[1]}' AND meta_key = 'kmisaldo'";
		$kmisaldo = $conn->query($sql);
		if( $kmisaldo->num_rows > 0 ){
			$kmisaldo = $kmisaldo->fetch_assoc();
			$kmisaldo = $kmisaldo["meta_value"];
		}

		$parametros = array( 
			"reserva"         => $id_reserva,
			"servicio"        => $data['ID'],
			"saldo"	          => $saldo+$descuento+$kmisaldo,
			"saldo_temporal"  => $saldo+$descuento,
			"variaciones"     => $variaciones,
			"fechas"          => $fechas,
			"transporte"      => $transporte,
			"adicionales"     => $adicionales
		);

		$_SESSION["MR_".$param[1]] = $parametros;
		header("location: ".$home['server']."producto/".$data['post_name']."/");
	}

	if( isset($b) ){
		$home = $conn->query("SELECT option_value AS server FROM wp_options WHERE option_name = 'siteurl'"); $home = $home->fetch_assoc();
		foreach ($_SESSION as $key => $value) {
			if(	substr($key, 0, 3) == "MR_" ){
				unset($_SESSION[$key]);
			}
		}
		header("location: ".$home['server']."perfil-usuario/?ua=invoices&fm=_");
	}

?>