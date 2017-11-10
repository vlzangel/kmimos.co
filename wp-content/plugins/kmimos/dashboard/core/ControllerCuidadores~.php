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

function getEstadoMunicipio($estados, $municipios){
	// buscar estados
	$resultado['estado'] = '';
	$resultado['municipio'] = '';
	if(!empty($estados)){
		$e = explode('=', $estados);
		$estado = '';
		foreach ($e as $value) {
			if( $value > 0 ){
				$sql_e = 'select * from states where country_id = 1 and id = '.$value;
				$result = get_fetch_assoc($sql_e);
				$resultado['estado'] = (isset($result['rows'][0]['name'])) ? $result['rows'][0]['name'] : '';
				break;
			}
		}
	}
	//buscar municipios
	if(!empty($municipios)){
		$e = explode('=', $municipios);
		$muni = '';
		foreach ($e as $value) {
			if( !empty( trim( $value) ) ){
				$sql_m = 'select * from locations where id = '.$value;
				$result = get_fetch_assoc($sql_m);
				$resultado['municipio'] = (isset($result['rows'][0]['name'])) ? $result['rows'][0]['name'] : '';
				break;
			}
		}
	}
	return $resultado;
}

function getUsers($desde="", $hasta=""){
	$filtro_adicional = "";
	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " 
			DATE_FORMAT(u.user_registered, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}

	$filtro_adicional = (!empty($filtro_adicional))? ' WHERE '.$filtro_adicional : $filtro_adicional ;
	$sql = "
		SELECT u.*, b.*, c.activo as 'estatus'
		FROM wp_users as u
			INNER JOIN cuidadores as c ON c.user_id = u.ID
			INNER JOIN ubicaciones as b ON b.cuidador = c.id
		{$filtro_adicional}
		ORDER BY DATE_FORMAT(u.user_registered,'%d-%m-%Y') DESC;
	";
	
	$result = get_fetch_assoc($sql);
	return $result;
}

