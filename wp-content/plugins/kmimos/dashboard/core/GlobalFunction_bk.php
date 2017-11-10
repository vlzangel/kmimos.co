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

function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}

function getEdad($fecha){
	$fecha = str_replace("/","-",$fecha);
	$hoy = date('Y/m/d');

	$diff = abs(strtotime($hoy) - strtotime($fecha) );
	$years = floor($diff / (365*60*60*24)); 
	$desc = " Años";
	$edad = $years;
	if($edad==0){
		$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
		$edad = $months;
		$desc = ($edad > 1) ? " Meses" : " Mes";
	}
	if($edad==0){
		$days  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		$edad = $days;
		$desc = " Días";
	}

	return $edad . $desc;
}

function get_razas(){
	global $wpdb;
	$sql = "SELECT * FROM razas ";
	$result = $wpdb->get_results($sql);
	$razas = [];
	foreach ($result as $raza) {
		$razas[$raza->id] = $raza->nombre;
	}
	return $razas;
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

function date_convert( $str_date, $format = 'd-m-Y H:i:s', $totime=true ){
	$fecha = $str_date;
	if(!empty($str_date)){
		if($totime){
			$time = strtotime($str_date);
		}
		$fecha = date($format,$time);
	}
	return $fecha;
}

function currency_format( $str, $signo="$ ", $signo_decimal=",", $signo_miles="." ){
	if(!empty($str)){
		$str = $signo.number_format($str, 2, $signo_decimal, $signo_miles);
	}else{
		$str = $signo."0";
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