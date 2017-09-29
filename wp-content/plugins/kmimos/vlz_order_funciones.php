<?php
	/* Detalles del servicio */

		$detalles = kmimos_desglose_reserva($o, true);

		$msg_id_reserva = $detalles["msg_id_reserva"];
		$aceptar_rechazar = $detalles["aceptar_rechazar"];
		$detalles_servicio = $detalles["detalles_servicio"];

		$detalles_servicio_cuidador = $detalles["detalles_servicio_cuidador"];
		
		$metodo_pago = $detalles["metodo_pago"];
?>