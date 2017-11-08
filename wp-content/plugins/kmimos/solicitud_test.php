<?php
    require('../../../wp-load.php');
    global $wpdb;
	$cuidador = 1440;
        $sql = "
	        SELECT 
                id_post,
                user_id,
                hospedaje_desde
            FROM 
                cuidadores
            WHERE
            	id_post != {$cuidador} AND
                portada = 1 AND
                activo = 1
            LIMIT 0, 100
	    ";
	    $sugeridos = $wpdb->get_results($sql); 

	    foreach ($sugeridos as $key => $cuidador) {
            $img= '';
			$name_photo = get_user_meta($cuidador->user_id, "name_photo", true);
			// $cuidador_id = $cuidador->id;
			$cuidador_id = "{$cuidador->user_id}";

			if( empty($name_photo)  ){ $name_photo = "0"; }
			if( file_exists("../../../wp-content/uploads/avatares/".$cuidador_id."/{$name_photo}") ){
                $img = get_home_url()."/wp-content/uploads/avatares/".$cuidador_id."/{$name_photo}";
            }elseif( file_exists("../../../wp-content/uploads/avatares/".$cuidador_id."/0.jpg") ){
                $img = get_home_url()."/wp-content/uploads/avatares/".$cuidador_id."/0.jpg";
            }else{
                $img = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
            }

            echo '<img src="'.$img.'">';
                $img = get_home_url()."/wp-content/uploads/avatares/".$cuidador_id."/0.jpg";
            echo '<img src="'.$img.'">';
                $img = get_home_url()."/wp-content/uploads/avatares/".$cuidador_id."/{$name_photo}";
            echo '<img src="'.$img.'">';
        }
