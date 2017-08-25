<?php
	define('WP_USE_THEMES', false);
    require('../../../../wp-blog-header.php');

    extract($_POST);

    global $wpdb;

	wp_set_password( $clave_1, $user_id );

	$sql = "SELECT ID FROM wp_users WHERE ID = '{$user_id}'";
	$data = $wpdb->get_row($sql);

    $user_id = $data->ID;
	$user = get_user_by( 'id', $user_id ); 
	if( $user ) {
	    wp_set_current_user( $user_id, $user->user_login );
	    wp_set_auth_cookie( $user_id );
	}

	header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
?>