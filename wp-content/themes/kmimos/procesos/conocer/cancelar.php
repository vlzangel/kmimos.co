<?php
    
    $wpdb->query("UPDATE wp_postmeta SET meta_value = '3' WHERE post_id = $id_orden AND meta_key = 'request_status';");
    $wpdb->query("UPDATE wp_posts SET post_status = 'draft' WHERE ID = '{$id_orden}';");

	$cuidador_info = $cuidador;

	$sql = "
        SELECT 
            DISTINCT id,
            ROUND ( ( 6371 * 
                acos(
                    cos(
                        radians({$cuidador_info->latitud})
                    ) * 
                    cos(
                        radians(latitud)
                    ) * 
                    cos(
                        radians(longitud) - 
                        radians({$cuidador_info->longitud})
                    ) + 
                    sin(
                        radians({$cuidador_info->latitud})
                    ) * 
                    sin(
                        radians(latitud)
                    )
                )
            ), 2 ) as DISTANCIA,
            id_post,
            user_id,
            hospedaje_desde,
            adicionales
        FROM 
            cuidadores
        WHERE
            id_post != {$cuidador_info->id_post} AND 
            activo = 1
        ORDER BY DISTANCIA ASC
        LIMIT 0, 4
    ";

    $sugeridos = $wpdb->get_results($sql);

    $str_sugeridos = "";

    $file_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/cuidadores.php';
    $plantilla_cuidador = file_get_contents($file_plantilla);

    foreach ($sugeridos as $valor) {
    	$nombre = $wpdb->get_row("SELECT post_title, post_name FROM wp_posts WHERE ID = ".$valor->id_post);

    	$rating = kmimos_petsitter_rating($valor->id_post, true);
    	$rating_txt = "";
    	foreach ($rating as $key => $value) {
    		if( $value == 1 ){
    			$rating_txt .= "<img style='width: 15px; padding: 0px 1px;' src='[URL_IMGS]/huesito.png' >";
    		}else{
    			$rating_txt .= "<img style='width: 15px; padding: 0px 1px;' src='[URL_IMGS]/huesito_vacio.png' >";
    		}
    	}
    	$servicios = vlz_servicios($valor->adicionales, true);
    	$servicios_txt = "";
        if( $servicios != "" ){
        	foreach ($servicios as $key => $value) {
        		$servicios_txt .= "<img style='' src='[URL_IMGS]/servicios/".$value["img"]."' height='100%' >";
        	}
        }
    	$temp = str_replace("[MONTO]", number_format( ($valor->hospedaje_desde*1.2), 2, ',', '.'), $plantilla_cuidador);
    	$temp = str_replace("[AVATAR]", kmimos_get_foto($valor->user_id), $temp);
    	$temp = str_replace("[NAME_CUIDADOR]", $nombre->post_title, $temp);
    	$temp = str_replace("[HUESOS]", $rating_txt, $temp);
    	$temp = str_replace("[SERVICIOS]", $servicios_txt, $temp);
    	$temp = str_replace('[LIKS]', get_home_url()."/petsitters/".$nombre->post_name."/", $temp);

    	$str_sugeridos .= $temp;
    }

    $msg_cliente = "";
    $msg_cuidador = "";

    if( $usu == "STM" ){
        $msg_cliente = "Te notificamos que el sistema ha cancelado la solicitud para conocer al cuidador <strong>[name_cuidador]</strong> debido a que se venció el plazo de confirmación.";
        $msg_cuidador = "Te notificamos que el sistema ha cancelado la solicitud para conocer cuidador realizada por <strong>[name_cliente]</strong> debido a que se venció el plazo de confirmación.";

        $msg_admin = "Te notificamos que el sistema ha cancelado la solicitud para conocer al cuidador <strong>[name_cuidador]</strong> realizada por el cliente <strong>[name_cliente]</strong> debido a que se venció el plazo de confirmación.";

    }else{
        if( $usu == "CLI" ){
            $msg_cliente = "Te notificamos que la solicitud para conocer cuidador ha sido cancelada exitosamente.";
            $msg_cuidador = "Te notificamos que el cliente <strong>[name_cliente]</strong> ha cancelado la solicitud para conocerte.";

            $msg_admin = "Te notificamos que el cliente <strong>[name_cliente]</strong> ha cancelado la solicitud para conocer al cuidador <strong>[name_cuidador]</strong>.";
        }else{
            $msg_cliente = "Te notificamos que el cuidador <strong>[name_cuidador]</strong> ha cancelado la solicitud para conocerle.";
            $msg_cuidador = "Te notificamos que la solicitud para conocerte ha sido cancelada exitosamente.";

            $msg_admin = "Te notificamos que el cuidador <strong>[name_cuidador]</strong> ha cancelado la solicitud para conocerle, realizado por el cliente <strong>[name_cliente]</strong>.";
        }
    }

        $file = $PATH_TEMPLATE.'/template/mail/conocer/cliente/cancelar.php';
        $mensaje_cliente = file_get_contents($file);

        $mensaje_cliente = str_replace('[mensaje]', $msg_cliente, $mensaje_cliente);
        $mensaje_cliente = str_replace('[id_solicitud]', $id_orden, $mensaje_cliente);
        $mensaje_cliente = str_replace('[name_cliente]', $cliente_name, $mensaje_cliente);
        $mensaje_cliente = str_replace('[name_cuidador]', $cuidador_name, $mensaje_cliente);
        $mensaje_cliente = str_replace('[CUIDADORES]', $str_sugeridos, $mensaje_cliente);
        $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);


    	$mensaje_cliente = get_email_html( $mensaje_cliente );	
        wp_mail( $email_cliente, "Cancelación de Solicitud para conocer cuidador", $mensaje_cliente);

        $file = $PATH_TEMPLATE.'/template/mail/conocer/cuidador/cancelar.php';
        $mensaje_cuidador = file_get_contents($file);

        $mensaje_cuidador = str_replace('[mensaje]', $msg_cuidador, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[id_solicitud]', $id_orden, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[name_cliente]', $cliente_name, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[name_cuidador]', $cuidador_name, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);

        $mensaje_cuidador = get_email_html( $mensaje_cuidador );  
        wp_mail( $email_cuidador, "Cancelación de Solicitud para conocer cuidador", $mensaje_cuidador);



        $file = $PATH_TEMPLATE.'/template/mail/conocer/admin/cancelar.php';
        $mensaje_admin = file_get_contents($file);

        $mensaje_admin = str_replace('[mensaje]', $msg_admin, $mensaje_admin);
        $mensaje_admin = str_replace('[id_solicitud]', $id_orden, $mensaje_admin);
        $mensaje_admin = str_replace('[name_cliente]', $cliente_name, $mensaje_admin);
        $mensaje_admin = str_replace('[name_cuidador]', $cuidador_name, $mensaje_admin);
        $mensaje_admin = str_replace('[CUIDADORES]', $str_sugeridos, $mensaje_admin);
        $mensaje_admin = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_admin);

        $mensaje_admin = get_email_html( $mensaje_admin );  

        kmimos_mails_administradores_new("Cancelación de Solicitud para conocer cuidador", $mensaje_admin);
    
    if( $usu != "STM" ){
        $CONTENIDO .= "<div class='msg_acciones'>Te notificamos que la solicitud para conocer cuidador <strong>#".$id_orden."</strong>, ha sido cancelada exitosamente.</div>";
    }

?>

