<?php
// require_once('../include/base_db.php');
// require_once('../include/GlobalFunction.php');

function getListsuscribe($landing="", $desde="", $hasta=""){

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
		SELECT s.*,
				CASE WHEN u.user_registered THEN u.user_registered ELSE '---' END as fecha_registro,
				CASE WHEN u.user_email = s.email THEN 'Registrado' ELSE 'No Registrado' END as estatus,
				CASE WHEN u.ID = c.user_id THEN 'Cuidador'
						 WHEN u.ID IS NULL THEN '---'
						 ELSE 'Cliente' END as 'tipo' 
			FROM list_subscribe as s
				LEFT JOIN wp_users as u ON u.user_email = s.email 
				LEFT JOIN cuidadores as c ON u.ID = c.user_id
		{$filtro_adicional}
	";

	$result = execute($sql);
	return $result;
}

function getListlanding(){
	$sql = "SELECT DISTINCT source FROM list_subscribe";
	$result = get_fetch_assoc($sql);
	return $result;
}

function get_total_reservas( $participante_email = 0 ){
	$sql = "
		SELECT count(DISTINCT p.post_author) as total_reservas
		FROM wp_usermeta as m
			inner join wp_posts as p ON p.post_author = m.user_id 
				and p.post_type = 'wc_booking'
				and p.post_status = 'confirmed'
				and not p.post_status like '%cart%'
				and p.post_date > '2017-05-12'
		WHERE 
			m.meta_value = md5('{$participante_email}')
			 
	";

	$result = get_fetch_assoc($sql);
	return $result;
}


function getTracking($email=""){

	$result = [];
	$sql = "
		SELECT 
			lt.`user_email`,
			lt.`option`,
			lt.`value`
		FROM
			list_subscriber_tracking as lt
		WHERE
			lt.`user_email` = '".$email."'
			and lt.`option` like 'referidos_%'
	";

	$result = get_fetch_assoc($sql);
	return $result;
}