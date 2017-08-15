<?php
	global $wpdb;

	$datos_generales = kmimos_datos_generales_desglose($orden, false, false);

	$detalles_cliente = $datos_generales["cliente"];
	$detalles_cuidador = $datos_generales["cuidador"];
	$detalles_mascotas = $datos_generales["mascotas"];

	$cliente_email  = $datos_generales["cliente_email"];
	$cuidador_email = $datos_generales["cuidador_email"];

	/* Detalles del servicio */

	$detalles = kmimos_desglose_reserva($orden);

	$msg_id_reserva = $detalles["msg_id_reserva"];
	$aceptar_rechazar = $detalles["aceptar_rechazar"];
	$detalles_servicio = $detalles["detalles_servicio"];
	$detalles_factura = $detalles["detalles_factura"];

	$titulo = '<h2>Detalles de la solicitud:</h2>';
?>