<?php
	function vlz_addons($petsitter_id, $sevice, $info){

		update_post_meta($petsitter_id, "vlz_servicio_{$sevice}_categoria", $info['service_category']);
		update_post_meta($petsitter_id, "vlz_servicio_{$sevice}_capacity", $info['service_capacity']);

		for ($i=0; $i < 6; $i++) { 
			update_post_meta($petsitter_id, "vlz_precio_{$sevice}_{$i}", $info['price_size_'.$i]);
		}

		for ($i=0; $i < 6; $i++) { 
			update_post_meta($petsitter_id, "vlz_transportacion_{$sevice}_{$i}", $info['price_transportation_'.$i]);
		}

		for ($i=0; $i < 6; $i++) { 
			update_post_meta($petsitter_id, "vlz_aditional_{$sevice}_{$i}", $info['price_aditional_'.$i]);
		}
	}
?>