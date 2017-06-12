<?php
    global $wpdb;

	$id = $order->id;

	$datos_generales = kmimos_datos_generales_desglose($id, true);

	$detalles_cliente = $datos_generales["cliente"];
	$detalles_cuidador = $datos_generales["cuidador"];
	$detalles_mascotas = $datos_generales["mascotas"];

	$cliente_email  = $datos_generales["cliente_email"];
	$cuidador_email = $datos_generales["cuidador_email"];

	$booking = new WC_Booking($datos_generales["booking"]);
    $order = new WC_Order($datos_generales["orden"]);

    $reserva_id = $datos_generales["booking"];

    $nom_cliente  = $datos_generales["nombre_cliente"];
    $nom_cuidador = $datos_generales["nombre_cuidador"];

    $producto  = $datos_generales["producto_name"];
    $tipo_servicio = $datos_generales["tipo_servicio"];

	/* Detalles del servicio */

	$detalles = kmimos_desglose_reserva($id, true);

	$msg_id_reserva = $detalles["msg_id_reserva"];
	$aceptar_rechazar = $detalles["aceptar_rechazar"];
	$detalles_servicio = $detalles["detalles_servicio"];

?>