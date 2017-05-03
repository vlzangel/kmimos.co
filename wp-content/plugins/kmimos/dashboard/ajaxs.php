<?php
	require_once("../../../../vlz_config.php");

	$conn_my = new mysqli($host, $user, $pass, $db);

	extract($_POST);

	switch ($action) {

		case "update_tipo_usuario":

			$xtipo = $conn_my->query("SELECT meta_value AS tipo FROM wp_usermeta WHERE user_id = {$id} AND meta_key = 'tipo_usuario'");
			if($xtipo->num_rows == 0 ){
				$conn_my->query("INSERT INTO wp_usermeta VALUES (NULL, {$id}, 'tipo_usuario', '{$tipo}')");
			}else{
				$conn_my->query("UPDATE wp_usermeta SET meta_value = '{$tipo}' WHERE user_id = '{$id}' AND meta_key = 'tipo_usuario';");
			}

		break;

		case "update_descripcion":
			$xtipo = $conn_my->query("SELECT meta_value AS descripcion FROM wp_postmeta WHERE post_id = {$id} AND meta_key = 'kmimos_descripcion'");
			$desc = utf8_decode($desc);
			if($xtipo->num_rows == 0 ){
				$conn_my->query("INSERT INTO wp_postmeta VALUES (NULL, {$id}, 'kmimos_descripcion', '{$desc}')");
			}else{
				$conn_my->query("UPDATE wp_postmeta SET meta_value = '{$desc}' WHERE post_id = '{$id}' AND meta_key = 'kmimos_descripcion';");
			}
		break;
		
	}

	return;
?>