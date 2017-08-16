<?php
	$sql = "
		SELECT 
			c.id AS ID,
			c.latitud AS lat,
			c.longitud AS lng,
			c.nombre AS nombre,
			post.post_name AS url,
			c.portada
		FROM
			cuidadores AS c
		INNER JOIN wp_posts AS post ON ( post.ID = c.id_post )
		WHERE
			activo = '1'
	";

	$coordenadas_all = $wpdb->get_results($sql);
?>