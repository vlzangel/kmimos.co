<?php
	// Modificacion Ángel Veloz

	extract($_GET);

	session_start();


/*	session_destroy();

	session_start();*/

	if( isset($a) ){
		include("../../../../../../vlz_config.php");
		$conn = new mysqli($host, $user, $pass, $db);

		$param = explode("_", $a);

		$sql = "SELECT ID FROM wp_users WHERE md5(ID) = '{$param[1]}'";
		$usuario = $conn->query($sql);
		if( $usuario->num_rows > 0 ){
			$usuario = $usuario->fetch_assoc();
			$user_id = $usuario["ID"];
		}


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

		// $descuento = 0;
		// if( isset( $metas_orden[ "_cart_discount" ] ) ){
		// 	$descuento = $metas_orden[ "_cart_discount" ];
		// }

		// Saldo
		$descuento = 0;
        $order_item_id = $conn->query("SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_id = '{$orden_id}' AND order_item_type = 'coupon' AND order_item_name LIKE '%saldo-%'"); 
        if( $order_item_id->num_rows > 0 ){
	        $order_item_id = $order_item_id->fetch_assoc();
	        $order_item_id = $order_item_id['order_item_id'];

            $descuento = $conn->query("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '{$order_item_id}' AND meta_key = 'discount_amount' ");
            if( $descuento->num_rows > 0 ){
		        $descuento = $descuento->fetch_assoc();
		        $descuento = $descuento['meta_value']; 

		        echo $descuento."<br>";
		    }
        }

        $sql = "SELECT * FROM wp_woocommerce_order_items WHERE order_id = '{$orden_id}' AND order_item_type = 'coupon' AND order_item_name NOT LIKE '%saldo-%'";
        $otros_cupones = $conn->query($sql);
    	while ( $cupon = $otros_cupones->fetch_assoc() ) {

            $cupon_id = $conn->query("SELECT ID FROM wp_posts WHERE post_title = '{$cupon['order_item_name']}'");
            $cupon_id = $cupon_id->fetch_assoc();
            $cupon_id = $cupon_id["ID"];

            $conn->query("DELETE FROM wp_postmeta WHERE post_id = '{$cupon_id}' AND meta_key = '_used_by' AND meta_value = '{$user_id}'");

        }

		$r3 = $conn->query("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '{$metas_reserva['_booking_order_item_id']}'");

		$items = array();
		while ( $f = $r3->fetch_assoc() ) {
			$items[ $f['meta_key'] ] = $f['meta_value'];
		}

		if( $orden['post_status'] == 'unpaid' && $order['post_status'] != 'wc-partially-paid' && $metas_orden['_payment_method'] == 'openpay_stores'){ }else{
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
			"user_id"         => $user_id,
			"orden_id"        => $orden_id,
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

		include("../../../../../../wp-load.php");

		$conn = new mysqli($host, $user, $pass, $db);

		global $wpdb;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$wpdb->query( "DELETE FROM wp_posts WHERE ID = ".$cart_item["booking"]["_booking_id"] );
			$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id = ".$cart_item["booking"]["_booking_id"] );
		}
		WC()->cart->empty_cart();

		$data = "";
		foreach ($_SESSION as $key => $value) {
			if(	substr($key, 0, 3) == "MR_" ){
				$data = $value;
			}
		}

		extract($data);

		$sql = "SELECT * FROM wp_woocommerce_order_items WHERE order_id = '{$orden_id}' AND order_item_type = 'coupon' AND order_item_name NOT LIKE '%saldo-%'";
        $otros_cupones = $conn->query($sql);
    	while ( $cupon = $otros_cupones->fetch_assoc() ) {
            $cupon_id = $conn->query("SELECT ID FROM wp_posts WHERE post_title = '{$cupon['order_item_name']}'");
            $cupon_id = $cupon_id->fetch_assoc();
            $cupon_id = $cupon_id["ID"];

            $conn->query("INSERT INTO wp_postmeta VALUES (NULL, '{$cupon_id}', '_used_by', '{$user_id}' )");
        }

		$home = $conn->query("SELECT option_value AS server FROM wp_options WHERE option_name = 'siteurl'"); $home = $home->fetch_assoc();
		foreach ($_SESSION as $key => $value) {
			if(	substr($key, 0, 3) == "MR_" ){
				unset($_SESSION[$key]);
			}
		}

		header("location: ".$home['server']."perfil-usuario/?ua=invoices");
	}

?>