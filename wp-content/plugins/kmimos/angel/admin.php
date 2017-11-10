<?php
		
	/**
	 *  Devuelve la cantidad y la lista de mascotas que posee el usuario.
	 * */
	if(!function_exists('kmimos_get_my_pets')){
	    function kmimos_get_my_pets($user_id){
	        global $wpdb;
	        $sql  = "SELECT * FROM $wpdb->posts WHERE post_type = 'pets' AND post_status = 'publish' AND post_author = {$user_id}";
	        return $wpdb->get_results($sql);
	    }
	}
		
	/**
	 *  Devuelve la lista de cuidadores favoritos de un cliente
	 * */
	if(!function_exists('kmimos_get_favoritos')){
	    function kmimos_get_favoritos($user_id){
	        global $wpdb;
	        $sql  = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = {$user_id} AND meta_key = 'user_favorites'";
	        $favoritos = $wpdb->get_var($sql);

	        if( $favoritos ){
	        	$favoritos = str_replace('"', "", $favoritos);
		        $favoritos = str_replace('[', "", $favoritos);
		        $favoritos = str_replace(']', "", $favoritos);

		        return explode(",", $favoritos);
	        }else{
	        	return false;
	        }

		        
	    }
	}

	

?>