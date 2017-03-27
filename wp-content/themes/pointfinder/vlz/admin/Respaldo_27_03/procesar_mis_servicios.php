<?php
    
    date_default_timezone_set('America/Bogota');

	$tams = array(
		"pequenos",
		"medianos",
		"grandes",
		"gigantes"
	);

	$hospedaje = array();
	$base_hospedaje = array();
	foreach ($tams as $value) {
		$hospedaje[$value] = $_POST['hospedaje_'.$value]+0;
		if( ($_POST['hospedaje_'.$value]+0) > 0 ){
			$base_hospedaje[$value] = $_POST['hospedaje_'.$value]+0;
		}
	}
	if( count($base_hospedaje) > 0 ){
		$base_hospedaje = min($base_hospedaje);
	}else{
		$base_hospedaje = 0;
	}
  	
	$adicionales = array();

	$transportacion_sencilla = false;
    $adicionales['transportacion_sencilla'] = array(
        "corto" => "Cortas",
        "medio" => "Medias",
        "largo" => "Largas"
    );
	foreach ($adicionales['transportacion_sencilla'] as $key => $value) {
		if( $_POST["transportacion_sencilla_".$key]+0 > 0 ){
			$transportacion_sencilla = true;
		}
		$adicionales['transportacion_sencilla'][$key] = $_POST["transportacion_sencilla_".$key]+0;
	}

	$transportacion_redonda = false;
    $adicionales['transportacion_redonda'] = array(
        "corto" => "Cortas",
        "medio" => "Medias",
        "largo" => "Largas"
    );
	foreach ($adicionales['transportacion_redonda'] as $key => $value) {
		if( $_POST["transportacion_sencilla_".$key]+0 > 0 ){
			$transportacion_redonda = true;
		}
		$adicionales['transportacion_redonda'][$key] = $_POST["transportacion_redonda_".$key]+0;
	}


    $adicionales_extra = array(
        "bano"                      => $_POST["adicional_bano"]+0,
        "corte"                     => $_POST["adicional_corte"]+0,
        "limpieza_dental"           => $_POST["adicional_limpieza_dental"]+0,
        "acupuntura"                => $_POST["adicional_acupuntura"]+0,
        "visita_al_veterinario"     => $_POST["adicional_visita_al_veterinario"]+0
    );

    foreach ($adicionales_extra as $key => $value) {
    	if( $value+0 > 0 ){
    		$adicionales[$key] = $value+0;
    	}
    }
	
	$adicionales['corte'] 					= $_POST["adicional_corte"]+0;
	$adicionales['limpieza_dental'] 		= $_POST["adicional_limpieza_dental"]+0;
	$adicionales['acupuntura'] 				= $_POST["adicional_acupuntura"]+0;
	$adicionales['visita_al_veterinario'] 	= $_POST["adicional_visita_al_veterinario"]+0;

  	$servicios_extras = array(
    	"guarderia"						=> "Precios de Guardería",
    	"paseos"						=> "Precios de Paseos",
    	"adiestramiento_basico"			=> "Precios de Entrenamiento Básico",
    	"adiestramiento_intermedio"		=> "Precios de Entrenamiento Intermedio",
    	"adiestramiento_avanzado"		=> "Precios de Entrenamiento Avanzado"
    );

  	$bases = array();
    foreach ($servicios_extras as $key => $value) {
    	
		$temp = array();
		$base_temp = array();
		foreach ($tams as $value) {
			$temp[$value] = $_POST[$key.'_'.$value]+0;
			if( ($_POST[$key.'_'.$value]+0) > 0 ){
				$base_temp[$value] = $_POST[$key.'_'.$value]+0;
			}
		}
		if( count($base_temp) > 0 ){
			$base_temp = min($base_temp);
		}else{
			$base_temp = 0;
		}

		if( max($temp) > 0 ){
    		$adicionales[$key] = $temp;
			$bases[$key] = $base_temp;
		}

    }

    echo "
    	<style>
    		body{
    			font-size: 12px;
    		}
    	</style>
    ";

    $imgs_product = array(
        "hospedaje"                 => "8370",
        "guarderia"                 => "8371",
        "adiestramiento_basico"     => "8372",
        "adiestramiento_intermedio" => "8372",
        "adiestramiento_avanzado"   => "8372",
        "paseos"                    => "8372"
    );

	global $wpdb;

	$sql = "UPDATE cuidadores SET adicionales = '".serialize($adicionales)."', hospedaje = '".serialize($hospedaje)."', hospedaje_desde = '".$base_hospedaje."' WHERE user_id = ".$user_id.";";
	$wpdb->query($sql);

	$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
	$status_global = "pending";
	if( $cuidador->activo == 1 ){
		$status_global = "publish";
	}
    $cuidador_post = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = {$cuidador->id_post}");

	$hospedaje = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_author = '{$user_id}' AND post_name LIKE '{$user_id}-hospedaje'");
  	if( $hospedaje != "" ){
  		$base_hospedaje *= 1.2;
  		$sql = "UPDATE wp_postmeta SET meta_value = '{$base_hospedaje}' WHERE post_id = '{$hospedaje}' AND (meta_key = '_price' OR meta_key = '_wc_booking_base_cost');";
  		$wpdb->query($sql);

  		foreach ($tams as $tamano) {
  			$valor = ($_POST['hospedaje_'.$tamano]+0);
  			$valor *= 1.2;
  			if( $valor > 0 ){
  				$status = $status_global;
  				$base_variante = $valor-$base_hospedaje;
  			}else{
  				$status = "unpublish";
  				$base_variante = 0;
  			}
  			$sql = "UPDATE wp_posts SET post_excerpt = 'Precio: \${$valor} c/u', post_status = '{$status}' WHERE post_parent = '{$hospedaje}' AND post_name = '{$user_id}-hospedaje-{$tamano}' AND post_type = 'bookable_person';";
  			$wpdb->query($sql);
  			$id_variante = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = '{$hospedaje}' AND post_name = '{$user_id}-hospedaje-{$tamano}' AND post_type = 'bookable_person';");
  			
  			$sql = "UPDATE wp_postmeta SET meta_value = '{$base_variante}' WHERE post_id = '{$id_variante}' AND meta_key = 'block_cost';";
  			$wpdb->query($sql);
  		}

        $adicionales['comision'] = 1.2;
        $addons = utf8_encode( sql_addons($adicionales) );

        $sql = "UPDATE wp_postmeta SET meta_value = '{$addons}' WHERE post_id = {$hospedaje} AND meta_key = '_product_addons';";
        $wpdb->query($sql);

  	}else{
  		echo "Crear el hospedaje";
  	}

    $servicios_extras_titulos = array(
        "guarderia"                     => "Guardería",
        "paseos"                        => "Paseos",
        "adiestramiento_basico"         => "Entrenamiento Básico",
        "adiestramiento_intermedio"     => "Entrenamiento Intermedio",
        "adiestramiento_avanzado"       => "Entrenamiento Avanzado"
    );
    $order_menu = array(
        "pequenos"  => "0",
        "medianos"  => "1",
        "grandes"   => "2",
        "gigantes"  => "3"
    );
    $cats = array(
        "paseos"                    => 2601,
        "adiestramiento_basico"     => 2602,
        "adiestramiento_intermedio" => 2606,
        "adiestramiento_avanzado"   => 2607,
        "guarderia"                 => 2599,
        "hospedaje"                 => 2598
    );
    $tamanos = array(
        "pequenos"  => "Pequeñas",
        "medianos"  => "Medianas",
        "grandes"   => "Grandes",
        "gigantes"  => "Gigantes"
    );

  /*	foreach ($servicios_extras as $nombre => $value) {
  		$extra = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_author = '{$user_id}' AND post_name LIKE '{$user_id}-{$nombre}'");
	  	if( $extra != "" ){
	  		$bases[$nombre] *= 1.2;
	  		$sql = "UPDATE wp_postmeta SET meta_value = '{$bases[$nombre]}' WHERE post_id = '{$extra}' AND (meta_key = '_price' OR meta_key = '_wc_booking_base_cost');";
	  		$wpdb->query($sql);

	  		foreach ($tams as $tamano) {
	  			$valor = ($_POST[$nombre.'_'.$tamano]+0);
  				$valor *= 1.2;
	  			if( $valor > 0 ){
	  				$status = $status_global;
	  				$base_variante = $valor-$bases[$nombre];
	  			}else{
	  				$status = "unpublish";
	  				$base_variante = 0;
	  			}

	  			$sql = "UPDATE wp_posts SET post_excerpt = 'Precio: \${$valor} c/u', post_status = '{$status}', post_title = 'xxxMascotas {$tamanos[$tamano]}' WHERE post_parent = '{$extra}' AND post_name = '{$user_id}-{$nombre}-{$tamano}' AND post_type = 'bookable_person';";
	  			$wpdb->query($sql);

	  			$id_variante = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = '{$extra}' AND post_name = '{$user_id}-{$nombre}-{$tamano}' AND post_type = 'bookable_person';");
	  			
	  			$sql = "UPDATE wp_postmeta SET meta_value = '{$base_variante}' WHERE post_id = '{$id_variante}' AND meta_key = 'block_cost';";
	  			$wpdb->query($sql);
	  		}
	  	}else{

            $hoy = date("Y-m-d H:i:s");
            $nom = $cuidador_post->post_title;

            $bases[$nombre] *= 1.2;

            if( $bases[$nombre] > 0 ){
                $status = $status_global;
            }else{
                $status = "unpublish";
            }

            $sql = sql_producto(array(
                "user"          => $user_id,
                "hoy"           => $hoy,
                "titulo"        => $servicios_extras_titulos[$nombre]." - ".$nom,
                "descripcion"   => descripciones($nombre),
                "slug"          => $user_id."-".$nombre,
                "cuidador"      => $cuidador->id_post,
                "status"        => $status
            ));

            $wpdb->query( utf8_encode($sql) );
            $servicio_id = $wpdb->insert_id; 

            $sql = sql_cats(array(
                "servicio"   => $servicio_id,
                "categoria"  => $cats[$nombre]
            ));
            $wpdb->query( $sql );

            $sql_metas = sql_meta_producto(array(
                "id_servicio"   => $servicio_id,
                "precio"        => $bases[$nombre],
                "cuidador_post" => $cuidador->id_post,
                "cantidad"      => $cuidador->mascotas_permitidas,
                "img"           => $imgs_product[$nombre],
                "slug"          => $nombre
            ));

            $wpdb->query( utf8_encode($sql_metas) );

            foreach ($tams as $tamano) {
                $valor = ($_POST[$nombre.'_'.$tamano]+0);
                $valor *= 1.2;
                if( $valor > 0 ){
                    $status = $status_global;
                    $base_variante = $valor-$bases[$nombre];
                }else{
                    $status = "unpublish";
                    $base_variante = 0;
                }

                $sql = sql_variante(array(
                    "user"      => $user_id,
                    "hoy"       => $hoy,
                    "titulo"    => $tamanos[$tamano].":",
                    "precio"    => $valor,
                    "slug"      => $user_id."-".$key."-".$tamano,
                    "servicio"  => $servicio_id,
                    "menu"      => $order_menu[$tamano],
                    "status"    => $status                
                ));
                $wpdb->query( utf8_encode($sql) );

                $sql = sql_meta_variante(array(
                    "bookable_person_id" => $wpdb->insert_id,
                    "bloque"             => $base_variante
                ));
                $wpdb->query( utf8_encode($sql) );
            }

	  	}

  	}*/

    echo "
        <h1>Servicios actualizados exitosamente!</h1>
        <script> location.href = '?ua=myservices'; </script>
    ";

	function sql_addons($data){

        $transporte = array(
            'name' => 'Servicios de Transportación (precio por grupo)',
            'description' => 'Rutas Cortas de 0 a 5Km
                              Rutas Medias de 5 a 10Km
                              Rutas Largas de 10 a 15Km',
            'type' => 'select',
            'position' => 0,
            'options' => array(
                '0' => array(
                    'label' => 'Transp. Sencillo - Rutas Cortas',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '1' => array(
                    'label' => 'Transp. Sencillo - Rutas Medias',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '2' => array(
                    'label' => 'Transp. Sencillo - Rutas Largas',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '3' => array(
                    'label' => 'Transp. Redondo - Rutas Cortas',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '4' => array(
                    'label' => 'Transp. Redondo - Rutas Medias',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '5' => array(
                    'label' => 'Transp. Redondo - Rutas Largas',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                )
            ),
            'required' => 0,
            'wc_booking_person_qty_multiplier' => 0,
            'wc_booking_block_qty_multiplier' => 0
        );

        $adicionales = array(
            'name' => 'Servicios Adicionales (precio por mascota)',
            'description' => '',
            'type' => 'checkbox',
            'position' => 1,
            'options' => array (
                '0' => array (
                    'label' => 'Baño (precio por mascota)',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '1' => array (
                    'label' => 'Corte de Pelo y Uñas (precio por mascota)',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '2' => array (
                    'label' => 'Visita al Veterinario (precio por mascota)',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '3' => array (
                    'label' => 'Limpieza Dental (precio por mascota)',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                ),
                '4' => array (
                    'label' => 'Acupuntura (precio por mascota)',
                    'price' => '',
                    'min' => '',
                    'max' => ''
                )
            ),
            'required' => 0,
            'wc_booking_person_qty_multiplier' => 0,
            'wc_booking_block_qty_multiplier' => 0
        );

        $adicionales_extra = array(
            "bano"                      => "Baño",
            "corte"                     => "Corte de pelo y uñas",
            "visita_al_veterinario"     => "Visita al Veterinario",
            "limpieza_dental"           => "Limpieza Dental",
            "acupuntura"                => "Acupuntura"
        );

        $addons = array(
            '0'=>$transporte,
            '1'=>$adicionales
        );

        $comision = $data["comision"];

        $rutas = array(
            "corto",
            "medio",
            "largo"
        );

        if( isset($data["transportacion_sencilla"]) ){
            for($i=0; $i<3; $i++){
                if( $data["transportacion_sencilla"][$rutas[$i]]+0 > 0 ){
                    $addons[0]['options'][$i]['price'] = $data["transportacion_sencilla"][$rutas[$i]]*$comision;
                }else{
                    unset($addons[0]['options'][$i]);
                }
            }
        }else{
            for($i=0; $i<3; $i++){
                unset($addons[0]['options'][$i]);
            }
        }

        if( isset($data["transportacion_redonda"]) ){
            for($i=3; $i<6; $i++){
                if( $data["transportacion_redonda"][$rutas[$i-3]]+0 > 0 ){
                    $addons[0]['options'][$i]['price'] = $data["transportacion_redonda"][$rutas[$i-3]]*$comision;
                }else{
                    unset($addons[0]['options'][$i]);
                }
            }
        }else{
            for($i=3; $i<6; $i++){
                unset($addons[0]['options'][$i]);
            }
        }

        if( count($addons[0]['options']) == 0 ){
            $addons[0] = array();
        }

        $i=0; $x=0;
        foreach ($adicionales_extra as $servicio => $precio) {

            if( $data[$servicio]+0 > 0 ){
                $addons[1]['options'][$i]['price'] = ($data[$servicio]*$comision);
                $x++;
            }else{
                unset($addons[1]['options'][$i]);
            }

            $i++;
        }

        if( $x == 0 ){
            $addons[1] = array();
        }

        return utf8_decode( serialize($addons) );

    }

    function sql_producto($data){
        return utf8_decode("
            INSERT INTO wp_posts VALUES (
                NULL,
                '".$data['user']."',
                '".$data['hoy']."',
                '".$data['hoy']."',
                '',
                '".$data['titulo']."',
                '".$data['descripcion']."',
                '".$data['status']."',
                'closed',
                'closed',
                '',
                '".$data['slug']."',
                '',
                '',
                '".$data['hoy']."',
                '".$data['hoy']."',
                '',
                '".$data['cuidador']."',
                '/producto/".$data['slug']."/',
                '0',
                'product',
                '',
                '0'
            );
        ");
    }

    function sql_meta_producto($data){

        switch ($data['slug']) {
            case 'guarderia':
                $sku = "GUAR";
            break;
            case 'adiestramiento_basico':
                $sku = "ADIB";
            break;
            case 'adiestramiento_intermedio':
                $sku = "ADII";
            break;
            case 'adiestramiento_avanzado':
                $sku = "ADIA";
            break;
        }

        $_wc_booking_min_duration = 1;

        return utf8_decode("
            INSERT INTO wp_postmeta VALUES 
                (NULL, '".$data['id_servicio']."', '_price', '".$data['precio']."'),
                (NULL, '".$data['id_servicio']."', '_wc_booking_buffer_period', ''),
                (NULL, '".$data['id_servicio']."', '_wc_booking_count_nights', '".$_wc_booking_count_nights."'),
                (NULL, '".$data['id_servicio']."', '_resource_block_costs', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', '_resource_base_costs', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', '_has_additional_costs', 'no'), 
                (NULL, '".$data['id_servicio']."', '_vc_post_settings', 'a:1:{s:10:\"vc_grid_id\";a:0:{}}'), 
                (NULL, '".$data['id_servicio']."', '_edit_lock', '1471267584:1'), 
                (NULL, '".$data['id_servicio']."', '_edit_last', '1'), 
                (NULL, '".$data['id_servicio']."', '_visibility', 'visible'), 
                (NULL, '".$data['id_servicio']."', '_stock_status', 'instock'), 
                (NULL, '".$data['id_servicio']."', '_downloadable', 'no'), 
                (NULL, '".$data['id_servicio']."', '_virtual', 'yes'), 
                (NULL, '".$data['id_servicio']."', '_tax_status', 'taxable'), 
                (NULL, '".$data['id_servicio']."', '_tax_class', ''), 
                (NULL, '".$data['id_servicio']."', '_purchase_note', ''), 
                (NULL, '".$data['id_servicio']."', '_featured', 'no'), 
                (NULL, '".$data['id_servicio']."', '_weight', ''), 
                (NULL, '".$data['id_servicio']."', '_length', ''), 
                (NULL, '".$data['id_servicio']."', '_width', ''), 
                (NULL, '".$data['id_servicio']."', '_height', ''), 
                (NULL, '".$data['id_servicio']."', '_sku', '".$sku."-CO-".$data['cuidador_post']."'),
                (NULL, '".$data['id_servicio']."', '_product_attributes', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', '_regular_price', ''), 
                (NULL, '".$data['id_servicio']."', '_sale_price', ''), 
                (NULL, '".$data['id_servicio']."', '_sale_price_dates_from', ''), 
                (NULL, '".$data['id_servicio']."', '_sale_price_dates_to', ''), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_resources_assignment', 'customer'), 
                (NULL, '".$data['id_servicio']."', '_sold_individually', ''), 
                (NULL, '".$data['id_servicio']."', '_manage_stock', 'no'), 
                (NULL, '".$data['id_servicio']."', '_backorders', 'no'), 
                (NULL, '".$data['id_servicio']."', '_stock', ''), 
                (NULL, '".$data['id_servicio']."', '_upsell_ids', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', '_crosssell_ids', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', '_product_version', '2.5.5'), 
                (NULL, '".$data['id_servicio']."', '_product_image_gallery', ''), 
                (NULL, '".$data['id_servicio']."', 'slide_template', 'default'), 
                (NULL, '".$data['id_servicio']."', '_yith_wcbm_product_meta', 'a:3:{s:8:\"id_badge\";s:0:\"\";s:10:\"start_date\";s:0:\"\";s:8:\"end_date\";s:0:\"\";}'), 
                (NULL, '".$data['id_servicio']."', '_wpb_vc_js_status', 'false'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_base_cost', '".$data['precio']."'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_cost', ''), 
                (NULL, '".$data['id_servicio']."', '_wc_display_cost', ''), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_min_duration', '".$_wc_booking_min_duration."'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_max_duration', '365'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_enable_range_picker', 'yes'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_calendar_display_mode', 'always_visible'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_qty', '".$data['cantidad']."'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_has_persons', 'yes'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_person_qty_multiplier', 'yes'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_person_cost_multiplier', 'yes'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_min_persons_group', '1'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_max_persons_group', '".$data['cantidad']."'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_has_person_types', 'yes'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_has_resources', 'no'), 
                (NULL, '".$data['id_servicio']."', '_wc_deposits_deposit_amount', '20'), 
                (NULL, '".$data['id_servicio']."', '_wc_deposits_amount_type', 'percent'), 
                (NULL, '".$data['id_servicio']."', '_wc_deposits_force_deposit', 'no'), 
                (NULL, '".$data['id_servicio']."', '_wc_deposits_enable_deposit', 'yes'), 
                (NULL, '".$data['id_servicio']."', '_product_addons_exclude_global', '0'), 
                (NULL, '".$data['id_servicio']."', '_product_addons', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', 'pv_commission_rate', ''), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_pricing', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_availability', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_resouce_label', ''), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_check_availability_against', ''), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_default_date_availability', 'available'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_requires_confirmation', 'no'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_first_block_time', ''), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_min_date_unit', 'day'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_min_date', '0'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_max_date_unit', 'month'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_max_date', '12'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_cancel_limit_unit', 'day'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_cancel_limit', '1'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_user_can_cancel', 'yes'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_duration_unit', 'day'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_duration', '1'), 
                (NULL, '".$data['id_servicio']."', '_wc_booking_duration_type', 'customer'), 
                (NULL, '".$data['id_servicio']."', '_wc_deposits_enable_per_person', 'no'), 
                (NULL, '".$data['id_servicio']."', 'total_sales', '0'), 
                (NULL, '".$data['id_servicio']."', '_wc_rating_count', 'a:0:{}'), 
                (NULL, '".$data['id_servicio']."', '_wc_review_count', '0'), 
                (NULL, '".$data['id_servicio']."', '_thumbnail_id', '".$data['img']."'), 
                (NULL, '".$data['id_servicio']."', '_wc_average_rating', '0');
        ");
    }

    function sql_cats($data){
        return "INSERT INTO wp_term_relationships VALUES 
                    ('".$data['servicio']."', '28', '0'),
                    ('".$data['servicio']."', '".$data['categoria']."', '0');";
    }

    function sql_variante($data){
        return ("
            INSERT INTO wp_posts VALUES (
                NULL,
                '".$data['user']."',
                '".$data['hoy']."',
                '".$data['hoy']."',
                '',
                'Mascotas ".$data['titulo']."',
                'Precio: $".$data['precio'].".00 c/u',
                '".$data['status']."',
                'closed',
                'closed',
                '',
                '".$data['slug']."',
                '',
                '',
                '".$data['hoy']."',
                '".$data['hoy']."',
                '',
                '".$data['servicio']."',
                '/bookable_person/".$data['slug']."/',
                '".$data['menu']."',
                'bookable_person',
                '',
                '0'
            );
        ");
    }

    function sql_meta_variante($data){
        $bookable_person_id = $data['bookable_person_id'];
        $bloque             = $data['bloque'];
        return ("
            INSERT INTO wp_postmeta VALUES
                (NULL, {$bookable_person_id}, '_vc_post_settings', 'a:1:{s:10:\"vc_grid_id\";a:0:{}}'),
                (NULL, {$bookable_person_id}, 'max', ''),
                (NULL, {$bookable_person_id}, 'min', ''),
                (NULL, {$bookable_person_id}, 'block_cost', '{$bloque}'),
                (NULL, {$bookable_person_id}, 'cost', '');
        ");
    }

    function precios($data, $comision){

        $precios = $data['precios'];

        $temp = array();
        foreach ($precios as $key => $value) {
            $precios[$key] = $value*$comision;
            if($value+0 > 0){
                $temp[$key] = $value*$comision;
            }
        }

        if( count($temp) > 0 ){
            $base = min($temp);
        }else{
            $base = 0;
        }

        return array(
            "precios"   => $precios,
            "base"      => $base
        );
    }

    function descripciones($servicio){

        switch ($servicio) {
            case 'adiestramiento_basico':
                return "<strong>Obediencia Básica</strong> todos los ejercicios son con correa, el objetivo es tener control sobre el perro y&nbsp;poder pasearlo sin que jale de la correa.<br><br>
                -Ejercicios:<br>
                <ul>
                    <li>Caminado junto (en todas direcciones y velocidades)
                        <ul>
                            <li>Sentado automático o a la voz</li>
                        </ul>
                    </li>
                    <li>Atención a las siguientes ordenes
                        <ul>
                            <li>Sentado</li>
                            <li>Echado</li>
                            <li>Quieto sentado (a distancia de correa 1.70 m.)</li>
                            <li>Quieto echado (a distancia de correa 1.70 m.)</li>
                            <li>Cambio de posiciones (sentado, echado y viceversa)</li>
                        </ul>
                    </li>
                    <li>Venir al llamado (a distancia de correa 1.70 m.)</li>
                </ul>";
            break;

            case 'adiestramiento_intermedio':
                return "<strong>Obediencia Intermedia</strong> el objetivo es practicar el control del perro y los ejercicios básicos son<br><br>
                -Ejercicios:<br>
                <ul>
                    <li>Caminado junto 50% con correa y 50% sin correa</li>
                    <li>Sentado automático</li>
                    <li>Quieto sentado a 10 m. de distancia (sin correa)</li>
                    <li>Quieto echado a 10 m. de distancia (sin correa)</li>
                    <li>Venir al llamado a 10 m. de distancia (sin correa)</li>
                </ul>";
            break;
            
            case 'adiestramiento_avanzado':
                return "<strong>Obediencia Avanzada</strong> el objetivo es introducir nuevas habilidades ya que en esta etapa el perro nos obedece al 100%<br><br>
                <ul>
                    <li>Todos los ejercicios de los niveles previos de obediencias son ejecutados sin correa.</li>
                    <li>Quieto durante la marcha y mientras acude al llamado</li>
                </ul>";
            break;
            
            case 'guarderia':
                return "Cuidado de tu mascota todo el día.<br><br>
                <small>* Precio final (incluye cobertura veterinaria y gastos administrativos; no incluye servicios adicionales)</small>";
            break;
            
            case 'hospedaje':
                return "Cuidado día y noche de tu mascota.<br><br>
                <small>* Precio final (incluye cobertura veterinaria y gastos administrativos; no incluye servicios adicionales)</small>";
            break;
            
            case 'paseos':
                return "Paseos de tu mascota.<br><br>
                <small>* Precio final (incluye cobertura veterinaria y gastos administrativos; no incluye servicios adicionales)</small>";
            break;
        }
        
    }
?>