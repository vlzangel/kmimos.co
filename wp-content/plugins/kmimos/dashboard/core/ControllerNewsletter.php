<?php
require_once('base_db.php');
require_once('GlobalFunction.php');

function getNewsletter($desde="", $hasta=""){

$dev['desde'] = $desde;
$dev['hasta']=$hasta;

	$filtro_adicional = "";
	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " 
			DATE_FORMAT(time, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}else{
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " MONTH(time) = MONTH(NOW()) AND YEAR(time) = YEAR(NOW()) ";
	}

	$filtro_adicional = ( !empty($filtro_adicional) )? " WHERE {$filtro_adicional}" : $filtro_adicional ;

	$result = [];
	$sql = "
		SELECT  id, `name`, email, source, SUBTIME(time, '5:00:00') as time
		FROM `wp_kmimos_subscribe`
		{$filtro_adicional}		
	";

$dev['sql']=$sql;
echo '<pre style="display:none;">';
print_r($dev);
echo '</pre>';

	$result = get_fetch_assoc($sql);
	return $result;
}