<?php
	if(!function_exists('get_home_url')){
        function get_home_url(){
        	global $db;
        	return $db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'");
        }
    }

	if(!function_exists('path_base')){
        function path_base(){
            return dirname(dirname(dirname(dirname(dirname(__DIR__)))));
        }
    }

    if(!function_exists('kmimos_get_foto')){
        function kmimos_get_foto($user_id, $get_sub_path = false){
            global $db;

            $HOME = get_home_url();

            $tipo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'wp_capabilities' ");
            
            if( strpos($tipo, "vendor") === false ){
                $sub_path = "avatares_clientes/miniatura/{$user_id}_";
                $sub_path = "avatares_clientes/{$user_id}/";
            }else{
                $id = $db->get_var("SELECT id FROM cuidadores WHERE user_id = {$user_id}");
                $sub_path = "cuidadores/avatares/miniatura/{$id}_";
                $sub_path = "cuidadores/avatares/{$id}/";
            }
            
            $name_photo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'name_photo' ");
            if( empty($name_photo)  ){ $name_photo = "0"; }
            $base = path_base();

            if( file_exists($base."/wp-content/uploads/{$sub_path}{$name_photo}") ){
                $img = $HOME."/wp-content/uploads/{$sub_path}{$name_photo}";
            }else{
                if( file_exists($base."/wp-content/uploads/{$sub_path}0.jpg") ){
                    $img = $HOME."/wp-content/uploads/{$sub_path}0.jpg";
                }else{
                    $img = $HOME."/wp-content/themes/kmimos/images/noimg.png";
                }
            }

            return $img;
        }
    }

    if(!function_exists('ya_aplicado')){
        function ya_aplicado($cupon, $cupones){
            foreach ($cupones as $key => $valor) {
                if( $cupon == $valor[0] ){
                    return true;
                }
            }
            return false;
        }
    }

    function kmimos_get_user_meta($user_id, $key){
        global $db;
        return $db->get_var("SELECT meta_key FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = '{$key}';");
    }
    
    function kmimos_update_user_meta($user_id, $key, $valor){
        global $db;
        if( kmimos_get_user_meta($user_id, $key) !== false ){
            $db->query("UPDATE wp_usermeta SET meta_value = '{$valor}' WHERE user_id = {$user_id} AND meta_key = '{$key}';");
        }else{
            $db->query("INSERT INTO wp_usermeta VALUES ( NULL, {$user_id}, '{$key}', '{$valor}');");
        }
    }

    function kmimos_get_post_meta($post_id, $key){
        global $db;
        return $db->get_var("SELECT meta_key FROM wp_postmeta WHERE post_id = {$user_id} AND meta_key = '{$key}';");
    }
    
    function kmimos_update_post_meta($post_id, $key, $valor){
        global $db;
        if( kmimos_get_post_meta($post_id, $key) !== false ){
            $db->query("UPDATE wp_postmeta SET meta_value = '{$valor}' WHERE post_id = {$post_id} AND meta_key = '{$key}';");
        }else{
            $db->query("INSERT INTO wp_postmeta VALUES ( NULL, {$post_id}, '{$key}', '{$valor}');");
        }
    }
    
?>