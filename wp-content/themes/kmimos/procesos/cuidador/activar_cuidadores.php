<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($raiz."/vlz_config.php");
    include_once("../funciones/db.php");
    
    $db = new db( new mysqli($host, $user, $pass, $db) );

    extract( $_GET );

    if( $a == 1 ){
    	$db->query("UPDATE wp_posts SET post_status = 'publish' WHERE post_status = 'pending' AND post_author = '{$u}';");
    	$db->query("UPDATE cuidadores SET activo = '1' WHERE user_id = '{$u}';");
    }else{
    	$db->query("UPDATE wp_posts SET post_status = 'pending' WHERE post_status = 'publish' AND post_author = '{$u}';");
    	$db->query("UPDATE cuidadores SET activo = '0' WHERE user_id = '{$u}';");
    }

	header( "location: ".$_SERVER['HTTP_REFERER'] );
?>