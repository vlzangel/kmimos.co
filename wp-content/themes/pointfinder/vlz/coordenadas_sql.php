<?php

	function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
		// Cálculo de la distancia en grados
		$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
	 
		// Conversión de la distancia en grados a la unidad escogida (kilómetros, millas o millas naúticas)
		switch($unit) {
			case 'km':
				$distance = $degrees * 111.13384; // 1 grado = 111.13384 km, basándose en el diametro promedio de la Tierra (12.735 km)
				break;
			case 'mi':
				$distance = $degrees * 69.05482; // 1 grado = 69.05482 millas, basándose en el diametro promedio de la Tierra (7.913,1 millas)
				break;
			case 'nmi':
				$distance =  $degrees * 59.97662; // 1 grado = 59.97662 millas naúticas, basándose en el diametro promedio de la Tierra (6,876.3 millas naúticas)
		}
		return round($distance, $decimals);
	}

		SELECT desc, 
			( 6371 * acos(
			    	cos(
			    		radians(LATITUD_ACTUAL)
			    	) * 
			    	cos(
			    		radians(lat)
			    	) * 
			    	cos(
			    		radians(lon) - 
			    		radians(LONGITUD_ACTUAL)
			    	) + 
			    	sin(
			    		radians(LATITUD_ACTUAL)
			    	) * 
			    	sin(
			    		radians(lat)
			    	)
			    )
		    ) as distancia 
		FROM 
			punto 
		WHERE 
		    lat between (LATITUD_ACTUAL-0.5) and (LATITUD_ACTUAL+0.5) and 
		    lon between (LONGITUD_ACTUAL-0.5) and (LONGITUD_ACTUAL+0.5) 
		HAVING distancia < (D/1000);

?>

SELECT 
	SQL_CALC_FOUND_ROWS  
	p.ID,
	p.post_author,
	p.post_title,
	p.post_name,
				( 6371 * 
					acos(
				    	cos(
				    		radians(10.4970477)
				    	) * 
				    	cos(
				    		radians(mt1.meta_value)
				    	) * 
				    	cos(
				    		radians(mt2.meta_value) - 
				    		radians(-66.8379881)
				    	) + 
				    	sin(
				    		radians(10.4970477)
				    	) * 
				    	sin(
				    		radians(mt1.meta_value)
				    	)
				    )
			    ) as DISTANCIA 
			
FROM 
	wp_posts AS p 
INNER JOIN wp_postmeta AS mt0 ON ( p.ID = mt0.post_id )  
INNER JOIN wp_postmeta AS mt1 ON ( p.ID = mt1.post_id )
INNER JOIN wp_postmeta AS mt2 ON ( p.ID = mt2.post_id )

WHERE 1=1  
	AND ( 
 		mt0.meta_key = 'rating_petsitter'
  		 
  		AND mt1.meta_key = 'latitude_petsitter'AND mt2.meta_key = 'longitude_petsitter' 
  		 
  		 
  		 
  		 
	) 
	AND p.post_type = 'petsitters' 
	AND p.post_status = 'publish'
HAVING DISTANCIA < 1400
GROUP BY 
	p.ID
ORDER BY
	DISTANCIA ASC 
LIMIT 0, 12