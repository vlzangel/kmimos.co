<?php
    
    /* Correo Cliente */


        $cuidador_file = $PATH_TEMPLATE.'/template/mail/reservar/cliente/confirmacion.php';
        $mensaje_cliente = file_get_contents($cuidador_file);


        $datos_cuidador = $PATH_TEMPLATE.'/template/mail/reservar/partes/datos_cuidador.php';
        $datos_cuidador = file_get_contents($datos_cuidador);
        $mensaje_cliente = str_replace('[DATOS_CUIDADOR]', $datos_cuidador, $mensaje_cliente);


        $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );

        $mensaje_cliente = str_replace('[mascotas]', $mascotas, $mensaje_cliente);
        $mensaje_cliente = str_replace('[desglose]', $desglose, $mensaje_cliente);
        
        $mensaje_cliente = str_replace('[ADICIONALES]', $adicionales, $mensaje_cliente);
        $mensaje_cliente = str_replace('[TRANSPORTE]', $transporte, $mensaje_cliente);
        
        $mensaje_cliente = str_replace('[MODIFICACION]', $modificacion, $mensaje_cliente);
        

        $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);

        $mensaje_cliente = str_replace('[tipo_servicio]', trim($servicio["tipo"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[inicio]', date("d/m", $servicio["inicio"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[fin]', date("d/m", $servicio["fin"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[anio]', date("Y", $servicio["fin"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[tiempo]', $servicio["duracion"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[tipo_pago]', $servicio["metodo_pago"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[avatar]', kmimos_get_foto($cuidador["id"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[telefonos_cuidador]', $cuidador["telefono"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[correo_cuidador]', $cuidador["email"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[direccion_cuidador]', $cuidador["direccion"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[TOTALES]', $totales_plantilla, $mensaje_cliente);

        $mensaje_cliente = get_email_html($mensaje_cliente);

        wp_mail( $cliente["email"], "Confirmación de Reserva", $mensaje_cliente);
    
    /* Correo Cliente */


        $cuidador_file = $PATH_TEMPLATE.'/template/mail/reservar/cuidador/confirmacion.php';
        $mensaje_cuidador = file_get_contents($cuidador_file);


        $datos_cliente = $PATH_TEMPLATE.'/template/mail/reservar/partes/datos_cliente.php';
        $datos_cliente = file_get_contents($datos_cliente);
        $mensaje_cuidador = str_replace('[DATOS_CLIENTE]', $datos_cliente, $mensaje_cuidador);
        

        $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );

        $mensaje_cuidador = str_replace('[mascotas]', $mascotas, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[desglose]', $desglose, $mensaje_cuidador);
        
        $mensaje_cuidador = str_replace('[ADICIONALES]', $adicionales, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[TRANSPORTE]', $transporte, $mensaje_cuidador);
        
        $mensaje_cuidador = str_replace('[MODIFICACION]', $modificacion, $mensaje_cuidador);
        

        $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[tipo_servicio]', trim($servicio["tipo"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[inicio]', date("d/m", $servicio["inicio"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[fin]', date("d/m", $servicio["fin"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[anio]', date("Y", $servicio["fin"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[tiempo]', $servicio["duracion"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[tipo_pago]', $servicio["metodo_pago"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[avatar]', kmimos_get_foto($cliente["id"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[telefonos_cliente]', $cliente["telefono"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[correo_cliente]', $cliente["email"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[TOTALES]', $totales_plantilla, $mensaje_cuidador);

        $mensaje_cuidador = get_email_html($mensaje_cuidador);

        wp_mail( $cuidador["email"], "Confirmación de Reserva", $mensaje_cuidador);



        $admin_file = $PATH_TEMPLATE.'/template/mail/reservar/admin/confirmacion.php';
        $mensaje_admin = file_get_contents($admin_file);

        $mensaje_admin = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_admin);
        $mensaje_admin = str_replace('[avatar_cliente]', kmimos_get_foto($cliente["id"]), $mensaje_admin);
        $mensaje_admin = str_replace('[telefonos_cliente]', $cliente["telefono"], $mensaje_admin);
        $mensaje_admin = str_replace('[correo_cliente]', $cliente["email"], $mensaje_admin);

        $mensaje_admin = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_admin);
        $mensaje_admin = str_replace('[avatar_cuidador]', kmimos_get_foto($cuidador["id"]), $mensaje_admin);
        $mensaje_admin = str_replace('[telefonos_cuidador]', $cuidador["telefono"], $mensaje_admin);
        $mensaje_admin = str_replace('[correo_cuidador]', $cuidador["email"], $mensaje_admin);
        $mensaje_admin = str_replace('[direccion_cuidador]', $cuidador["direccion"], $mensaje_admin);
        
        $mensaje_admin = str_replace('[mascotas]', $mascotas, $mensaje_admin);
        $mensaje_admin = str_replace('[desglose]', $desglose, $mensaje_admin);
        
        $mensaje_admin = str_replace('[ADICIONALES]', $adicionales, $mensaje_admin);
        $mensaje_admin = str_replace('[TRANSPORTE]', $transporte, $mensaje_admin);
        
        $mensaje_admin = str_replace('[MODIFICACION]', $modificacion, $mensaje_admin);
        

        $mensaje_admin = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_admin);

        $mensaje_admin = str_replace('[tipo_servicio]', trim($servicio["tipo"]), $mensaje_admin);
        $mensaje_admin = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_admin);

        $mensaje_admin = str_replace('[inicio]', date("d/m", $servicio["inicio"]), $mensaje_admin);
        $mensaje_admin = str_replace('[fin]', date("d/m", $servicio["fin"]), $mensaje_admin);
        $mensaje_admin = str_replace('[anio]', date("Y", $servicio["fin"]), $mensaje_admin);
        $mensaje_admin = str_replace('[tiempo]', $servicio["duracion"], $mensaje_admin);
        $mensaje_admin = str_replace('[tipo_pago]', $servicio["metodo_pago"], $mensaje_admin);

        $mensaje_admin = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_admin);

        $mensaje_admin = str_replace('[TOTALES]', $totales_plantilla, $mensaje_admin);

        $mensaje_admin = get_email_html($mensaje_admin);

        kmimos_mails_administradores_new("Confirmación de Reserva", $mensaje_admin);
        
        $CONTENIDO .= "<div class='msg_acciones'>
            <strong>¡Todo esta listo!</strong><br>
            La reserva #".$servicio["id_reserva"].", ha sido confirmada exitosamente de acuerdo a tu petición.
        </div>";
?>