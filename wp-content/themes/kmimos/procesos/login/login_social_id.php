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
			m.meta_value = '{$_GET['init']}' 
			AND m.meta_key like '%_auth_id'
	";
	$data = $wpdb->get_row($sql);

	if( $data->user_id > 0 ){
		$sql = "
			SELECT 
				u.user_email AS mail,
				m.meta_value AS clave
			FROM 
				wp_users AS u
			INNER JOIN wp_usermeta AS m ON (m.user_id = u.ID)
			WHERE 
				u.ID = '{$data->user_id}' AND 
				m.meta_key = 'user_pass'
			GROUP BY 
				u.ID
		";
		$user = $wpdb->get_row($sql);
		if( $user->mail != '' ){
			$info = array();
		    $info['user_login']     = sanitize_user($user->mail, true);
		    $info['user_password']  = sanitize_text_field($user->clave);
		    $user_signon = wp_signon( $info, true );
		    wp_set_auth_cookie($user_signon->ID);
			echo json_encode(['status'=>'true', 'msg'=>'Autenticado']);
		}else{
			echo json_encode(['status'=>'false', 'msg'=>'No se puede iniciar sesion']);
		}
	}else{
		echo json_encode(['status'=>'false', 'msg'=>'La cuenta no esta asociada a ningún usuario']);
	}

?>