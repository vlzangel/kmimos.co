<?php
require_once('base_db.php');
require_once('GlobalFunction.php');


function getListsubscribe($landing="", $desde="", $hasta=""){

	$filtro_adicional = "";
	if( !empty($landing) ){
		$filtro_adicional = " source = '{$landing}'";
	}
	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " 
			DATE_FORMAT(fecha, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}else{
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " MONTH(fecha) = MONTH(NOW()) AND YEAR(fecha) = YEAR(NOW()) ";
	}


	$filtro_adicional = ( !empty($filtro_adicional) )? " WHERE {$filtro_adicional}" : $filtro_adicional ;

	$result = [];
	$sql = "
		SELECT * 
		FROM list_subscriber
		{$filtro_adicional}
	";
	$result = execute($sql);
	return $result;
}

function getListlanding(){
	$sql = "SELECT DISTINCT source FROM list_subscribe";
	$result = execute($sql);
	return $result;
}