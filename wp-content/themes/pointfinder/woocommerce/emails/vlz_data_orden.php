<?php
    global $wpdb;

	$id = $order->id;

	$datos_generales = kmimos_datos_generales_desglose($id, true, true);

	$detalles_cliente = $datos_generales["cliente"];
	$detalles_cuidador = $datos_generales["cuidador"];
	$detalles_mascotas = $datos_generales["mascotas"];

	$cliente_email  = $datos_generales["cliente_email"];
	$cuidador_email = $datos_generales["cuidador_email"];

    $orden_id = $datos_generales["orden"];
    $reserva_id = $datos_generales["booking"];

    $order = new WC_Order($orden_id);
	$booking = new WC_Booking($reserva_id);

    $nom_cliente  = $datos_generales["nombre_cliente"];
    $nom_cuidador = $datos_generales["nombre_cuidador"];

    $producto  = $datos_generales["producto_name"];
    $tipo_servicio = $datos_generales["tipo_servicio"];

	/* Detalles del servicio */

	$detalles = kmimos_desglose_reserva($id, true);

	$msg_id_reserva = $detalles["msg_id_reserva"];
	$aceptar_rechazar = $detalles["aceptar_rechazar"];
	$detalles_servicio = $detalles["detalles_servicio"];
	$detalles_servicio_cuidador = $detalles["detalles_servicio_cuidador"];

	$metodo_pago = $detalles["metodo_pago"];

	$pdf = $detalles["pdf"];

?>