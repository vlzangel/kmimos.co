<?php
require_once('base_db.php');
require_once('GlobalFunction.php');

function getmetaUser($user_id=0){
	$condicion = " AND m.meta_key IN ( 'nickname', 'first_name', 'last_name' )";
	$result = get_metaUser($user_id, $condicion);
	$data = [
		'first_name' =>'',
		'last_name' =>'',
		'nickname' => '',
	];
	if( !empty($result) ){
		foreach ( $result['rows'] as $row ) {
			$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
		}
	}
	
	return $data;
}

function getReferencia( $email = '', $landing = '' ){

	$result = [];
	$sql = "
		SELECT 
			l.*
		FROM
			list_subscribe as l
		WHERE l.email = '{$email}' and l.source = '{$landing}'
	";
	$result = get_fetch_assoc($sql);
	return $result;
}

 function getCountReservas( $author_id=0, $desde="", $hasta=""){

	$filtro_adicional = "";
	if( !empty($landing) ){
		$filtro_adicional = " source = '{$landing}'";
	}
	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " 
			DATE_FORMAT(post_date, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}else{
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " MONTH(post_date) = MONTH(NOW()) AND YEAR(post_date) = YEAR(NOW()) ";
	}


	$filtro_adicional = ( !empty($filtro_adicional) )? " WHERE {$filtro_adicional}" : $filtro_adicional ;

	$result = [];
	$sql = "
		SELECT  
			count(ID) as cant
		FROM wp_posts
		WHERE post_type = 'wc_booking' 
			AND not post_status like '%cart%'
			AND post_status = 'confirmed' 
			AND post_author = {$author_id}
			AND DATE_FORMAT(post_date, '%m-%d-%Y') between DATE_FORMAT('2017-05-12','%m-%d-%Y') and DATE_FORMAT(now(),'%m-%d-%Y')
	";
	$result = get_fetch_assoc($sql);
	return $result;
}

 

function getListsuscribe($landing="", $referido="", $desde="", $hasta=""){

	$filtro_adicional = "";
	if( !empty($landing) ){
		$filtro_adicional = " source = '{$landing}'";
	}
	if( !empty($referido) ){
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " l.email = '{$referido}'";
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
		SELECT 
			l.source, l.email as user_parent, 
			r.user_id as referido_id,
			u.user_email
		FROM
			list_subscribe as l
			INNER JOIN wp_usermeta as r ON (r.meta_key = 'landing-referencia' and r.meta_value=md5(l.email))
			INNER JOIN wp_users as u ON u.ID = r.user_id
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