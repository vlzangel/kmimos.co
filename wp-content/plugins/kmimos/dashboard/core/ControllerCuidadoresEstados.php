<?php
require_once('base_db.php');
require_once('GlobalFunction.php');


function getServicesPaseo($user_id){
	$sql = "SELECT m.meta_value as price, p.* 
	FROM wp_posts as p
		inner join wp_postmeta as m on m.post_id = p.ID and m.meta_key = '_price'
	WHERE post_author = {$user_id} 
		AND post_status = 'publish'
		AND post_name like 'paseo%'";
	$result = get_fetch_assoc($sql);
	return $result;
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
				$sql_e = 'select * from states where country_id = 1 and ( id = '.$value." or id like '%=11=%')" ;
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

function getmetaUser($user_id=0){
	$condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_phone')";
	$result = get_metaUser($user_id, $condicion);
	$data = [
		'first_name' =>'', 
		'last_name' =>'', 
		'user_phone' =>'', 
	];
	if( !empty($result) ){
		foreach ( $result['rows'] as $row ) {
			$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
		}
	}
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
	$filtro_adicional = (!empty($filtro_adicional))? ' WHERE '.$filtro_adicional : $filtro_adicional ;
	$sql = "
		SELECT b.*, u.ID as user_id, u.user_email as email
		FROM wp_users as u
			INNER JOIN cuidadores as c ON c.user_id = u.ID
			INNER JOIN ubicaciones as b ON b.cuidador = c.id
		{$filtro_adicional}
		ORDER BY u.ID DESC;
	";
	
	$result = get_fetch_assoc($sql);
	return $result;
}

