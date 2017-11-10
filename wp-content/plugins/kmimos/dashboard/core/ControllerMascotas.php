<?php
require_once('base_db.php');
require_once('GlobalFunction.php');

function getmetaMascotas($post_id=0){
	$result = get_metaPost($post_id);
	$data = [
		'first_name' =>'', 
		'last_name' =>'', 
		'user_phone' =>'', 
		'user_mobile' =>'',
		'user_referred' =>'',
		'nickname' => '',
	];
	if( !empty($result) ){
		foreach ( $result['rows'] as $row ) {
			$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
		}
	}
	return $data;
}

function getMascotas(){
	$sql = "
		SELECT p.*
		FROM wp_posts as p
		WHERE p.post_type = 'pets' and p.post_status = 'publish'
	";	
	$result = get_fetch_assoc($sql);
	return $result;
}

function getMetaCliente( $user_id ){
	$condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_referred')";
	$result = get_metaUser($user_id, $condicion);
	$data = [
		'first_name' =>'', 
		'last_name' =>'', 
		'user_referred' =>'', 
	];
	if( !empty($result) ){
		foreach ($result['rows'] as $row) {
			$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
		}
	}
	return $data;
}
