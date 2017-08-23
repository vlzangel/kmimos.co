<?php
/*	include("wp-load.php");

	global $wpdb;

	$cuidadores = $wpdb->get_results("
		SELECT 
			c.id AS carpeta,
			c.user_id AS user,
			m.meta_value AS img
		FROM 
			cuidadores AS c
		INNER JOIN wp_usermeta AS m ON (c.user_id = m.user_id AND m.meta_key = 'name_photo')
		WHERE
			1=1
		GROUP BY c.id
	");

	// echo "<pre>";
	// 	print_r($cuidadores);
	// echo "</pre>";

	$origen = "wp-content/uploads/avatares";
	$destino = "wp-content/uploads/cuidadores/avatares";

	foreach ($cuidadores as $key => $value) {

		echo $origen."/".$value->user."/".$value->img."<br>";

		if( file_exists( $origen."/".$value->user."/".$value->img ) ){
			if( !file_exists( $destino."/".$value->carpeta."/" ) ){
				mkdir( $destino."/".$value->carpeta."/" );
			}	

			copy( 
				$origen."/".$value->user."/".$value->img, 
				$destino."/".$value->carpeta."/".$value->img
			);
		}
	}*/


	$base = "wp-content/uploads/avatares/";
	$destino = "wp-content/uploads/cuidadores/avatares/";

    if( is_dir($base) ){
    	if ($dh = opendir($base)) { 
	        while (($file = readdir($dh)) !== false) { 
	            if ( $file != "." && $file != ".." ){ 
	            	reducir($base, $file);
	            } 
	        } 
	      	closedir($dh);
        }
    }

	function reducir($base, $id){
		if( is_dir($base) ){
	    	if ($dh = opendir($base.$id)) { 
		        while (($file = readdir($dh)) !== false) { 
		            if ( $file!="." && $file != ".." ){ 
	            		echo "<img src='".$base.$id."/".$file."' height='250px;' /><br>";
		            } 
		        } 
		      	closedir($dh);
	        }
	    }
	}
?>