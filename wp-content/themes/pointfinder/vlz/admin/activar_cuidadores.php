<?php
    require('../../../../../wp-config.php');

    $conn_my = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    extract( $_GET );

    if( $a == 1 ){
    	$conn_my->query("UPDATE wp_posts SET post_status = 'publish' WHERE post_status = 'pending' AND post_author = '{$u}';");
    	$conn_my->query("UPDATE cuidadores SET activo = '1' WHERE user_id = '{$u}';");
    }else{
    	$conn_my->query("UPDATE wp_posts SET post_status = 'pending' WHERE post_status = 'publish' AND post_author = '{$u}';");
    	$conn_my->query("UPDATE cuidadores SET activo = '0' WHERE user_id = '{$u}';");
    }

	header( "location: ".$_SERVER['HTTP_REFERER'] );
?>