<?php
	$load = realpath('../../../../../wp-load.php');
	if(file_exists($load)){
		include_once($load);
	}

	extract($_POST);

	global $wpdb;
	$sql = "
		SELECT 
			m.user_id
		FROM wp_usermeta AS m  
		WHERE 
			m.meta_value = '{$id}' 
			AND m.meta_key like '".$social."_auth_id'
	";
	$data = $wpdb->get_row($sql);

	if( $data->user_id > 0 ){
		echo json_encode([ 'sts'=>0, "msg"=>'El usuario ya se encuentra registrado', 'sql'=>$sql ]);
	}else{
		echo json_encode([ 'sts'=>1, "msg"=>'' ]);
	}