<?php
	
    $file = $PATH_TEMPLATE.'/template/mail/conocer/cliente/confirmar.php';
    $mensaje_cliente = file_get_contents($file);
    
	$wpdb->query("UPDATE wp_postmeta SET meta_value = '2' WHERE post_id = $id_orden AND meta_key = 'request_status';");
	$wpdb->query("UPDATE wp_posts SET post_status = 'publish' WHERE ID = '{$id_orden}';");

    $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);
    $mensaje_cliente = str_replace('[name_cuidador]', $cuidador_name, $mensaje_cliente);
    $mensaje_cliente = str_replace('[name_cliente]', $cliente_name, $mensaje_cliente);


    $mensaje_cliente = get_email_html($mensaje_cliente, true, false);

    wp_mail( $email_cliente, "Confirmación de Solicitud para Conocer Cuidador", $mensaje_cliente);



    $file = $PATH_TEMPLATE.'/template/mail/conocer/cuidador/confirmar.php';
    $mensaje_cuidador = file_get_contents($file);

    $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);
    $mensaje_cuidador = str_replace('[name_cuidador]', $cuidador_name, $mensaje_cuidador);
    $mensaje_cuidador = str_replace('[name_cliente]', $cliente_name, $mensaje_cuidador);

    $mensaje_cuidador = get_email_html($mensaje_cuidador, true, false);

    wp_mail( $email_cuidador, "Confirmación de Solicitud para Conocerte", $mensaje_cuidador);



	$file = $PATH_TEMPLATE.'/template/mail/conocer/admin/confirmar.php';
    $mensaje_admin = file_get_contents($file);

    $mensaje_admin = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_admin);
    $mensaje_admin = str_replace('[id_solicitud]', $id_orden, $mensaje_admin);
    $mensaje_admin = str_replace('[name_cuidador]', $cuidador_name, $mensaje_admin);
    $mensaje_admin = str_replace('[name_cliente]', $cliente_name, $mensaje_admin);

    $mensaje_admin = get_email_html($mensaje_admin, true, false);

    kmimos_mails_administradores_new("Confirmación de Solicitud para Conocer a ".$cuidador_name, $mensaje_admin);

    $CONTENIDO .= "<div class='msg_acciones'>
        <strong>¡Todo esta listo!</strong><br>
        La solicitud para conocer cuidador <strong>#".$id_orden."</strong>, ha sido confirmada exitosamente de acuerdo a tu petición.
    </div>";
?>