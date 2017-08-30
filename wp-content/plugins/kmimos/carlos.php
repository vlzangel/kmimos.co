<?php

	include_once('wlabel/wlabel.php');
	include_once('subscribe/subscribe.php');

	include_once('includes/class/class_kmimos_booking.php');
	include_once('includes/class/class_kmimos_tables.php');
	include_once('includes/class/class_kmimos_script.php');
	//include_once('plugins/woocommerce.php');

	if(!function_exists('carlos_include_script')){
	    function carlos_include_script(){
			wp_enqueue_style('theme_style',plugins_url('/css/style.css',__FILE__));
			wp_enqueue_style('theme_woocmmerce',plugins_url('/css/woocommerce.css',__FILE__));

			//wp_enqueue_script('theme_jquerymobile',plugins_url('includes/js/jquery/jquery.mobile-1.4.5.min.js',__FILE__));
			wp_enqueue_style('theme_jquerydatepick',plugins_url('includes/js/jquery/datapicker/datapicker/jquery.datepick.css',__FILE__));
			//wp_enqueue_script('theme_jquerydatepick',plugins_url('includes/js/jquery/datapicker/jquery.js',__FILE__));
			wp_enqueue_script('theme_jquerydatepick',plugins_url('includes/js/jquery/datapicker/datapicker/jquery.datepick.js',__FILE__));
			wp_enqueue_script('theme_jqueryplugin',plugins_url('includes/js/jquery/datapicker/datapicker//jquery.plugin.js',__FILE__));
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



	////MORE VIEWED
	remove_action('wp_head','adjacent_posts_rel_link_wp_head', 10, 0);

	function theme_set_post_views($postid) {
		$count_key = 'post_views_count';
		$count = get_post_meta($postid, $count_key, true);
		if($count==''){
			$count = 0;
			delete_post_meta($postid, $count_key);
			add_post_meta($postid, $count_key, '0');
		}else{
			$count++;
			update_post_meta($postid, $count_key, $count);
		}
	}

	add_action('wp_head','theme_track_post_views');
	function theme_track_post_views($postid){
		if (!is_single()){
			return;
		}else if(empty($postid)){
			global $post;
			$postid = $post->ID;
		}
		theme_set_post_views($postid);
	}

	function theme_get_post_views($postid){
		$count_key = 'post_views_count';
		$count = get_post_meta($postid, $count_key, true);
		if($count==''){
			delete_post_meta($postid, $count_key);
			add_post_meta($postid, $count_key, '0');
			return 0;
		}
		return $count;
	}



	//UPDATE Additional Services
	function update_additional_service(){
		global $wpdb;

		$sql = "SELECT * FROM cuidadores";
		$cuidadores = $wpdb->get_results($sql);
		foreach ($cuidadores as $cuidador) {
			$ID =  $cuidador->id;
			$ID_user =  $cuidador->user_id;
			$adicionales = unserialize($cuidador->adicionales);
			//var_dump($ID);
			//var_dump($adicionales);

			$status_servicios = array();
			$sql = "SELECT * FROM wp_posts WHERE post_author = {$ID_user} AND post_type = 'product'";
			$productos = $wpdb->get_results($sql);
			foreach ($productos as $producto) {
				$servicio = explode("-", $producto->post_name);
				$status_servicios[$servicio[0]] = $producto->post_status;

				if(isset($adicionales[$servicio[0]]) && $producto->post_status=='publish'){
					//var_dump($servicio[0]);
					if(!isset($adicionales['status_'.$servicio[0]])){
						$adicionales['status_'.$servicio[0]]='1';
					}
				}
			}

			$service_extra=array('bano','corte','limpieza_dental','visita_al_veterinario','acupuntura');
			$service_transportation=array('transportacion_sencilla','transportacion_redonda');
			foreach($adicionales as $service => $amount){
				if(in_array($service,$service_extra)){
					if($amount>0){
						$adicionales['status_'.$service]='1';
					}
				}

				if(in_array($service,$service_transportation)){
					foreach($service_transportation as $transportation => $amount){
						if($amount>0){
							$adicionales['status_'.$service]='1';
						}
					}
				}
			}

			$sql = "UPDATE cuidadores SET adicionales = '".serialize($adicionales)."' WHERE user_id = ".$ID_user.";";
			$wpdb->query($sql);
			//var_dump($sql);
		}
	}



//UPDATE Post-Name Additional Services
function update_additional_service_postname(){
	global $wpdb;
	$sql = "SELECT * FROM wp_posts WHERE post_name REGEXP '^[0-9]' AND post_type = 'product'";
	$services = $wpdb->get_results($sql);
	foreach ($services as $service){
		$ID =  $service->ID;
		$post_name =  $service->post_name;
		$post_author =  $service->post_author;

		//if(strpos($post_name,$post_author,0)!==false){
		if(preg_match("/^$post_author-/",$post_name,$matches)) {
			$post_name=str_replace($post_author.'-','',$post_name).'-'.$post_author;
		}

		$sql = "UPDATE wp_posts SET post_name = '$post_name' WHERE id = '$ID';";
		$wpdb->query($sql);
		//var_dump($sql);
	}
}



//UPDATE Image Services
function update_image_service(){
	$imgs_product = array(
		"paseos"   => array("1111x","11009"),
		"hospedaje"         => array("55477","8370"),
		"guarderia"         => array("55478","8371"),
		"adiestramiento_basico"     => array("55479","8372"),
		"adiestramiento_intermedio" => array("55479","8372"),
		"adiestramiento_avanzado"   => array("55479","8372"),
	);

	global $wpdb;
	foreach($imgs_product as $service => $imag){
		$img_old=$imag[0];
		$img_new=$imag[1];

		$sql = "SELECT * FROM wp_postmeta WHERE meta_key = '_thumbnail_id' AND meta_value = '$img_old'";
		$result = $wpdb->get_results($sql);
		foreach ($result as $row){
			$ID =  $row->meta_id;
			$sql = "UPDATE wp_postmeta SET meta_value = '$img_new' WHERE meta_id = '$ID';";
			$wpdb->query($sql);
			//var_dump($sql);
		}
	}
}

?>