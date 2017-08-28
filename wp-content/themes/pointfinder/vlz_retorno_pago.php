<?php 

	/*
		Template Name: vlz retorno de pago
	*/

	if( !isset($_SESSION) ){ session_start(); }

	$order_id = $_SESSION["orden_actual"];

	global $wpdb;

	$key = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$order_id}' AND meta_key = '_order_key' ");

	$_SESSION["orden_actual"] = "";
	unset( $_SESSION["orden_actual"] );

	header("location: ".get_home_url()."/finalizar-comprar/order-received/".$order_id."/?key=".$key);

?>