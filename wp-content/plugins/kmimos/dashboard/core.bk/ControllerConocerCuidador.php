<?php
require_once('base_db.php');
require_once('GlobalFunction.php');


function get_metaCuidador($user_id=0){
	$condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_phone', 'user_mobile')";
	$result = get_metaUser($user_id, $condicion);
	$data = [
		'id' =>'',
		'email' =>'',
		'first_name' =>'', 
		'last_name' =>'', 
		'user_phone' =>'', 
		'user_mobile' =>'',
	];
	if( !empty($result) ){
		if( $result->num_rows > 0){
			while ($row = $result->fetch_assoc()) {
				$data['email'] = utf8_encode( $row['user_email'] );
				$data['id'] = $row['user_id'];
				$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
			}
		}
	}
	return $data;
}
function get_metaCliente($user_id=0){
	$condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_phone', 'user_mobile')";
	$result = get_metaUser($user_id, $condicion);
	$data = [
		'id' =>'',
		'email' =>'',
		'first_name' =>'', 
		'last_name' =>'', 
		'user_phone' =>'', 
		'user_mobile' =>'',
	];
	if( !empty($result) ){
		if( $result->num_rows > 0){
			while ($row = $result->fetch_assoc()) {
				$data['email'] = utf8_encode( $row['user_email'] );
				$data['id'] = $row['user_id'];
				$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
			}
		}
	}
	return $data;
}

function getSolicitud($desde="", $hasta=""){

	$filtro_adicional = "";

	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional = " 
			AND DATE_FORMAT(p.post_date, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}else{
		$filtro_adicional = " AND MONTH(p.post_date) = MONTH(NOW()) AND YEAR(p.post_date) = YEAR(NOW()) ";
	}

	$sql = "
		SELECT 
			p.ID as Nro_solicitud,
			DATE_FORMAT(p.post_date,'%d-%m-%Y') as Fecha_solicitud,
			p.post_status as Estatus,

			DATE_FORMAT(fd.meta_value,'%d-%m-%Y') as Servicio_desde,
			DATE_FORMAT(fh.meta_value,'%d-%m-%Y') as Servicio_hasta,
			d.meta_value as Donde,
			w.meta_value as Cuando,
			t.meta_value as Hora,

			cl.meta_value as Cliente_id,
			cu.post_author as Cuidador_id
		FROM wp_postmeta as m
			LEFT JOIN wp_posts as p  ON p.ID = m.post_id 
			LEFT JOIN wp_postmeta as fd ON p.ID = fd.post_id and fd.meta_key = 'service_start' 	
			LEFT JOIN wp_postmeta as fh ON p.ID = fh.post_id and fh.meta_key = 'service_end' 		
			LEFT JOIN wp_postmeta as d  ON p.ID = d.post_id  and d.meta_key  = 'meeting_where' 	
			LEFT JOIN wp_postmeta as t  ON p.ID = t.post_id  and t.meta_key  = 'meeting_time' 	
			LEFT JOIN wp_postmeta as w  ON p.ID = w.post_id  and w.meta_key  = 'meeting_when' 	

			LEFT JOIN wp_postmeta as cl ON p.ID = cl.post_id and cl.meta_key = 'requester_user' 
			LEFT JOIN wp_postmeta as pc ON p.ID = pc.post_id and pc.meta_key = 'requested_petsitter' 
			LEFT JOIN wp_posts as cu ON cu.ID = pc.meta_value 
		WHERE 
			m.meta_key = 'request_status'
			{$filtro_adicional}
		ORDER BY DATE_FORMAT(p.post_date,'%d-%m-%Y') DESC
		;
	";

	$result = execute($sql);
	return $result;
}

