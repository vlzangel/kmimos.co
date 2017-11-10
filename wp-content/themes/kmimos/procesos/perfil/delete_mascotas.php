<?php
	$img_anterior = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$pet_id} AND meta_key = 'photo_pet';", "meta_value");
	if( $img_anterior != "" ){
		if( file_exists($raiz.$img_anterior) ){
	        unlink($raiz.$img_anterior);
	    }
	}

	$sql  = "DELETE FROM wp_posts WHERE ID = '{$pet_id}'; ";
	$sql .= "DELETE FROM wp_postmeta WHERE post_id = '{$pet_id}'; ";
	$sql .= "DELETE FROM wp_term_relationships WHERE object_id = '{$pet_id}'; ";

	$db->query_multiple( utf8_decode($sql) );

	$respuesta = array(
		"status" => "OK",
		"sql"	 => $sql
	);

?>