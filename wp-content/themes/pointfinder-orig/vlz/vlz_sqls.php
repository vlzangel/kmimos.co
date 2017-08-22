<?php
include("vlz_geo.php");
$sql = "SELECT 
	SQL_CALC_FOUND_ROWS  
	p.ID,
	p.post_author,
	p.post_title,
	p.post_name{$DISTANCIA}
FROM 
	wp_posts AS p 
INNER JOIN wp_postmeta AS mt0 ON ( p.ID = mt0.post_id )  
INNER JOIN wp_postmeta AS mt00 ON ( p.ID = mt00.post_id )  
INNER JOIN wp_postmeta AS mt01 ON ( p.ID = mt01.post_id )  
INNER JOIN wp_postmeta AS mt02 ON ( p.ID = mt02.post_id )   
{$inners}
WHERE 1=1  
	AND ( 
 		mt0.meta_key = 'rating_petsitter' AND
 		( mt00.meta_key = 'active_petsitter' AND mt00.meta_value = '1' ) AND
 		( mt01.meta_key = 'latitude_petsitter' AND mt01.meta_value >= ".$S['lat']." AND mt01.meta_value <= ".$N['lat']." ) AND
 		( mt02.meta_key = 'longitude_petsitter' AND mt02.meta_value >= ".$S['lng']." AND mt02.meta_value <= ".$N['lng']." )
  		{$nombre} 
  		{$META_KEYS} 
  		{$servicios_cuidador} 
  		{$servicios_adicionales} 
  		{$tamanos_mascotas} 
  		{$rangos_str} 
	) 
	AND p.post_type = 'petsitters' 
	AND p.post_status = 'publish'
GROUP BY 
	p.ID
$FILTRO_UBICACION
ORDER BY
	$ORDEN 
$LIMITE";
?>