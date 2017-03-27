<?php
	function cron_nombre_servicios($servicio){
		$r = '';
		switch ($servicio) {
			case 'precio_hospedaje':
				$r = 'Hospedaje';
			break;
			case 'precio_guarderia':
				$r = 'Guardería';
			break;
			case 'adiestramiento_o':
				$r = 'Adiestramiento';
			break;
			case 'precio_corte':
				$r = 'Corte de pelo y uñas';
			break;
			case 'bano_adicional':
				$r = 'Baño y secado';
			break;
			case 'precio_paseo':
				$r = 'Paseos';
			break;
			case 'transportacion_s':
				$r = 'Transporte Sencillo';
			break;
			case 'transportacion_r':
				$r = 'Transporte Redondo';
			break;
			case 'visita_veterinario':
				$r = 'Visita al Veterinario';
			break;
			case 'limpieza_dental':
				$r = 'Limpieza dental';
			break;
			case 'precio_acupuntura':
				$r = 'Acupuntura';
			break;
			case 'precio_adiestramiento':
				$r = 'Adiestramiento de Obediencia';
			break;
		}
		return $r;
	}
?>