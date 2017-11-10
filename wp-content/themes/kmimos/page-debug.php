<?php 
    /*
        Template Name: Debug
    */

	get_header();

		global $wpdb;

		$disponibilidad = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '150448' AND meta_key = '_wc_booking_availability'");

		$dis = unserialize($disponibilidad);

		echo "<pre>";
			print_r($dis);
		echo "</pre>";

	get_footer(); 
?>
