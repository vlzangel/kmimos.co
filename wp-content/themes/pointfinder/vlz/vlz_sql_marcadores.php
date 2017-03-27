<?php
	$sql = "SELECT 
		SQL_CALC_FOUND_ROWS  
		p.ID AS ID,
		mt0.meta_value AS lat,
		mt1.meta_value AS lng,
		p.post_title AS nombre,
		p.post_name AS url
	FROM 
		wp_posts AS p 
	INNER JOIN wp_postmeta AS mt0 ON ( p.ID = mt0.post_id AND mt0.meta_key='latitude_petsitter'  AND mt0.meta_value != '')
	INNER JOIN wp_postmeta AS mt1 ON ( p.ID = mt1.post_id AND mt1.meta_key='longitude_petsitter' AND mt1.meta_value != '' )
	INNER JOIN wp_postmeta AS mt3 ON ( p.ID = mt3.post_id ) 
	WHERE 1=1
		AND p.post_type = 'petsitters' 
		AND p.post_status = 'publish'
	 	AND ( mt3.meta_key = 'active_petsitter' AND mt3.meta_value = '1' )
	GROUP BY 
		p.ID
	";

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