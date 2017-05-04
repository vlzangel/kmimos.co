<?php
require_once('base_db.php');

function getUserByEmail($user_email=""){
	$sql = "
		SELECT *
		FROM wp_users
		WHERE user_email = '{$user_email}'
	";
	$result = get_fetch_assoc($sql);
	return $result;	
}

function get_metaUser($user_id=0, $condicion=''){
	$sql = "
		SELECT u.user_email, m.*
		FROM wp_users as u 
			INNER JOIN wp_usermeta as m ON m.user_id = u.ID
		WHERE 
			m.user_id = {$user_id} 
			{$condicion}
	";
	$result = get_fetch_assoc($sql);
	return $result;	
}

function date_convert( $str_date, $format = 'd-m-Y H:i:s' ){
	return date($format,strtotime($str_date));
}

function currency_format( $str, $signo="$ " ){
	if(!empty($str)){
		$str = $signo.number_format($str, 2, ',', '.');
	}
	return $str;
}

function get_metaPost($post_id=0, $condicion=''){
	$sql = "
		SELECT u.meta_key, u.meta_value, u.post_id
		FROM wp_postmeta as u 
		WHERE 
			u.post_id = {$post_id} 
			{$condicion}
	";
	$result = get_fetch_assoc($sql);
	return $result;	
}

function merge_phone($param, $separador=' / '){
	$param['phone'] = isset($param['user_phone']) ? 
		$param['user_phone'] : ''; 
	if(isset($param['user_mobile'])){ 
		$param['phone'] .= (!empty($param['phone']))? $separador : '' ;
		$param['phone'] .= $param['user_mobile'];
	}

	return $param;
}