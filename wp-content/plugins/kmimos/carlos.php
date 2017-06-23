<?php

	include_once('wlabel/wlabel.php');

	include_once('includes/class/class_kmimos_booking.php');
	include_once('includes/class/class_kmimos_tables.php');
	include_once('includes/class/class_kmimos_script.php');
	// include_once('plugins/woocommerce.php');

	if(!function_exists('carlos_include_script')){
	    function carlos_include_script(){
	        
	    }
	}

	if(!function_exists('carlos_include_admin_script')){
	    function carlos_include_admin_script(){
	        
	    }
	}

	if(!function_exists('carlos_menus')){
	    function carlos_menus($menus){
	        


	        return $menus;
	    }
	}

	add_action( 'send_headers', 'add_header_seguridad' );
	function add_header_seguridad() {
	    header( 'X-Content-Type-Options: nosniff' );
	    header( 'X-Frame-Options: SAMEORIGIN' );
	    header( 'X-XSS-Protection: 1' );
	    header( 'Cache-Control: no-cache, no-store, must-revalidate');

	    //Prevent Cache-control http
	    //header('Access-Control-Allow-Origin: *', false);
	}

?>