<?php
	// Modificacion Ángel Veloz
	session_start();

	if( isset($data) ){
		$param = explode("_", $data);

		$data = $db->get_row("SELECT * FROM wp_posts WHERE md5(ID) = '{$param[2]}'");

		$sql = "SELECT ID FROM wp_users WHERE md5(ID) = '{$param[1]}'";
		$user_id = $db->get_var($sql);

		$metas_reserva = $db->get_results("SELECT * FROM wp_postmeta WHERE md5(post_id) = '{$param[0]}'"); 
		$id_reserva = $metas_reserva[0]->post_id;

		$status =$db->get_var("SELECT post_status FROM wp_posts WHERE ID = '{$id_reserva}'");

		if( $status == "cancelled" || $status == "modified" ){ //  || $status == "confirmed"
			$respuesta = array(
				"error" => $status
			);
		}else{

			$metas_reservas = array();
			foreach ($metas_reserva as $key => $value) { $metas_reservas[ $value->meta_key ] = $value->meta_value; }
			
			$orden_id = $db->get_var("SELECT post_parent FROM wp_posts WHERE ID = '{$id_reserva}'"); 

			$order_status = $db->get_var("SELECT post_status FROM wp_posts WHERE ID = '{$orden_id}'");

			$m_orden = $db->get_results("SELECT * FROM wp_postmeta WHERE post_id = '{$orden_id}'"); 
			$metas_orden = array();
			foreach ($m_orden as $key => $value) { $metas_orden[ $value->meta_key ] = $value->meta_value; }

			$descuento = 0;
	        $order_item_id = $db->get_var("SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_id = '{$orden_id}' AND order_item_type = 'coupon' AND order_item_name LIKE '%saldo-%'"); 
	        if( $order_item_id !== false ){
	            $descuento = $db->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '{$order_item_id}' AND meta_key = 'discount_amount' ");
	        }

	        $sql = "SELECT * FROM wp_woocommerce_order_items WHERE order_id = '{$orden_id}' AND order_item_type = 'coupon' AND order_item_name NOT LIKE '%saldo-%'";
	        $otros_cupones = $db->get_results($sql);
	        if( $otros_cupones !== false ){
		        foreach ($otros_cupones as $cupon) {
		        	$cupon_id = $db->get_var("SELECT ID FROM wp_posts WHERE post_title = '{$cupon->order_item_name}'");

		            $db->query("DELETE FROM wp_postmeta WHERE post_id = '{$cupon_id}' AND meta_key = '_used_by' AND meta_value = '{$user_id}'");
		        }
	        }
	    	
			$r3 = $db->get_results("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '{$metas_reservas['_booking_order_item_id']}'"); 
			if( $r3 !== false ){
				$items = array();
				$items2 = array();
				foreach ($r3 as $key => $value) { 
					$items[ $key.$value->meta_key ] = $value->meta_value; 
					$items2[ $value->meta_key ] = $value->meta_value; 
				}
			}

			if( $order_status == 'wc-on-hold' && strtolower($metas_orden['_payment_method']) == 'tienda'){ }else{

				$deposito = unserialize( $items2['_wc_deposit_meta'] );
				$saldo = 0;
				if( $deposito['enable'] == 'yes' ){
					$saldo = $deposito['deposit'];
				}else{
	                $saldo = $items2['_line_subtotal'];
	                $saldo -= $metas_orden['_cart_discount'];
				}
			}

			//echo "Saldo: ".$saldo;

			$variaciones = array();

			$tamanos = array(
		    	"Peque" => "pequenos",
		    	"Media" => "medianos",
		    	"Grand"  => "grandes",
		    	"Gigan" => "gigantes"
		    );
		    $mascotas = array();
            foreach ( $items as $key => $value ) {
                if( strpos($key, "Mascotas") > -1 ){
                    $mascota = substr(end(explode(" ", $key)), 0, 5);
                    $variaciones[ $tamanos[$mascota] ] = $value;
                }
            }

			$fechas = array(
				"inicio" => date('d-m-Y', strtotime( $metas_reservas['_booking_start'] ) ),
				"fin" 	 => date('d-m-Y', strtotime( $metas_reservas['_booking_end']   ) )
			);

			$trans = array(
	            'Transp. Sencillo - Rutas Cortas',
	            'Transp. Sencillo - Rutas Medias',
	            'Transp. Sencillo - Rutas Largas',
	            'Transp. Redondo - Rutas Cortas',
	            'Transp. Redondo - Rutas Medias',
	            'Transp. Redondo - Rutas Largas'
	        );

			$adic = array(
	            "bano" => 'Baño (precio por mascota)',
	            "corte" => 'Corte de Pelo y Uñas (precio por mascota)',
	            "visita_al_veterinario" => 'Visita al Veterinario (precio por mascota)',
	            "limpieza_dental" => 'Limpieza Dental (precio por mascota)',
	            "acupuntura" => 'Acupuntura (precio por mascota)'
	        );

			$transporte = array();
			$adicionales = array();

			if( $r3 !== false ){
				foreach ($items as $key => $value) {

					if ( strpos( strtoupper($value) , "TRANSP.") !== false) {
					    $transporte[] = strtoupper($value);
					}

					$retorno = array_search(utf8_encode($value), $adic);
					if( $retorno ){
						$adicionales[$retorno] = $retorno;
					}
				}
			}

			$sql = "SELECT meta_value FROM wp_usermeta WHERE md5(user_id) = '{$param[1]}' AND meta_key = 'kmisaldo'";
			$kmisaldo = $db->get_var($sql, "meta_value");

			$cupos_menos = 0;
	    	foreach ($variaciones as $key => $value) {
	    		$cupos_menos += $value;
	    	}

	    	$variaciones["cupos"] = $cupos_menos;

			$parametros = array( 
				"reserva"         => $id_reserva,
				"servicio"        => $data->ID,
				"saldo"	          => $saldo+$descuento+$kmisaldo,
				"saldo_temporal"  => $saldo+$descuento,
				"variaciones"     => $variaciones,
				"fechas"          => $fechas,
				"transporte"      => $transporte,
				"adicionales"     => $adicionales
			);

			$_SESSION["MR_".$data->ID."_".$param[1]] = $parametros;

			$respuesta = array(
				"url" => "reservar/".$data->ID."/",
				"error" => ""
			);

		}
	}else{
		extract($_GET);

		if( isset($b) ){
			include("../../../../../vlz_config.php");
			$conn = new mysqli($host, $user, $pass, $db);

			$home = $conn->query("SELECT option_value AS server FROM wp_options WHERE option_name = 'siteurl'"); 
			$home = $home->fetch_assoc();
			
			$_SESSION["MR_".$b] = "";
			
			unset($_SESSION["MR_".$b]);

			header("location: ".$home['server']."/perfil-usuario/historial/");
		}

	}

?>