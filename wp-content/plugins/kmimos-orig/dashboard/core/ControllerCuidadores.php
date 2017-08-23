<?php
require_once('base_db.php');
require_once('GlobalFunction.php');

function getmetaUser($user_id=0){
	$condicion = " AND m.meta_key IN ( 'nickname', 'first_name', 'last_name', 'user_phone', 'user_mobile', 'user_referred')";
	$result = get_metaUser($user_id, $condicion);
	$data = [
		'first_name' =>'', 
		'last_name' =>'', 
		'user_phone' =>'', 
		'user_mobile' =>'',
		'user_referred' =>'',
		'nickname' =>'',
	];
	if( !empty($result) ){
		foreach ( $result['rows'] as $row ) {
			$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
		}
	}
	$data = merge_phone($data);
	return $data;
}

function getUsers($desde="", $hasta=""){
	$filtro_adicional = "";
	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " 
			DATE_FORMAT(u.user_registered, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}
	// else{
	// 	$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
	// 	$filtro_adicional .= "MONTH(u.user_registered) = MONTH(NOW()) AND YEAR(u.user_registered) = YEAR(NOW()) ";
	// }
	$filtro_adicional = (!empty($filtro_adicional))? ' WHERE '.$filtro_adicional : $filtro_adicional ;
	$sql = "
		SELECT u.*, c.activo as 'estatus'
		FROM wp_users as u
			INNER JOIN cuidadores as c ON c.user_id = u.ID
		{$filtro_adicional}
		ORDER BY DATE_FORMAT(u.user_registered,'%d-%m-%Y') DESC;
	";
	
	$result = get_fetch_assoc($sql);
	return $result;
}

