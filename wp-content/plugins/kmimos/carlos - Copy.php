<?php

	include_once('wlabel/wlabel.php');
	include_once('subscribe/subscribe.php');

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



	if(!function_exists('date_boooking')){
	    function date_boooking($date=''){
	        $date=strtotime($date);
	        $date=date('d/m/Y',$date);
	        //$date=substr($date, 6, 2)."/".substr($date, 4, 2)."/".substr($date, 0, 4);
	        return $date;
	    }
	}

	if(!function_exists('build_select')){
	    function build_select($args=array()){
	        $select='';
	        if(count($args)>0){
	            $select.='<select class="redirect theme_btn">';
	            $select.='<option value="" selected="selected">Seleccionar Acción</option>';
	            foreach($args as $option){
	                if(array_key_exists('text',$option)){
	                    $class='';
	                    if(array_key_exists('class',$option)){
	                        $class=$option['class'];
	                    }
	                    $select.='<option class="'.$class.'" value="'.$option['value'].'">'.$option['text'].'</option>';
	                }
	            }
	            $select.='</select>';
	        }
	        return $select;
	    }
	}




/*	if(!function_exists('get_metaUser')){
	    function get_metaUser($user_id=0, $condicion=''){
	        global $wpdb;
	        $sql = "
	            SELECT u.user_email, m.*
	            FROM wp_users as u
	                INNER JOIN wp_usermeta as m ON m.user_id = u.ID
	            WHERE
	                m.user_id = {$user_id}
	                {$condicion}
	        ";
	        $result = $wpdb->get_results($sql);
	        //$result = execute($sql);
	        return $result;
	    }
	}*/

	if(!function_exists('xGETmetaUSER')){
	    function xGETmetaUSER($user_id=0){
	        $condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_phone', 'user_mobile')";
	        $result = get_metaUser($user_id, $condicion);
	        $data = [
	            'id' =>'',
	            'email' =>'',
	            'first_name' =>'',
	            'last_name' =>'',
	            'user_phone' =>'',
	            'user_mobile' =>'',
	        ];
	        if( !empty($result) ){
	            if( $result->num_rows > 0){
	                while ($row = $result->fetch_assoc()) {
	                    $data['email'] = utf8_encode( $row['user_email'] );
	                    $data['id'] = $row['user_id'];
	                    $data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
	                }
	            }
	        }
	        return $data;
	    }
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
	global $wpdb;
	$imgs_product = array(
		"paseos"   => array("1111x","11009"),
		"hospedaje"         => array("55477","55477"),
		"guarderia"         => array("55478","55478"),
		"adiestramiento_basico"     => array("55479","55479"),
		"adiestramiento_intermedio" => array("55479","55479"),
		"adiestramiento_avanzado"   => array("55479","55479"),
	);

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



//UPDATE Addons Services
function update_addons_service(){
	global $wpdb;

	$user=0;
	$sql = "SELECT * FROM wp_posts WHERE post_name LIKE 'hospedaje-%' AND post_type = 'product'";
	$services = $wpdb->get_results($sql);

	foreach ($services as $service){
		$postID =  $service->ID;
		$postAUTHOR =  $service->post_author;
		$sql = "SELECT * FROM wp_postmeta WHERE meta_key = '_product_addons' AND post_id = '{$postID}'";
		$result = $wpdb->get_results($sql);


		//CREATE SELECT OPTIONS
		$sql = "SELECT * FROM cuidadores WHERE user_id = '{$postAUTHOR}'";
		$caregivers = $wpdb->get_results($sql);
		$hour=array();

		foreach ($caregivers as $caregiver){
			$caregiver_checkin=strtotime('1-1-2017 '.$caregiver->check_in);
			$caregiver_checkout=strtotime('1-1-2017 '.$caregiver->check_out);

			for($i=$caregiver_checkin; $i <= $caregiver_checkout; $i=$i+(60*30)){
				$ihour=date('g:i A',$i);//:s
				$hour[$ihour]=array(
					'label' => $ihour,
					'price' => '',
					'min' => '',
					'max' => ''
				);
			}
		}

		$SELECTcheckin = array(
			'name' => 'CheckIn',
			'description' => 'Hora de ingreso de tu mascota',
			'type' => 'select',
			'position' => 0,
			'options' => $hour,
			'required' => 0,
			'wc_booking_person_qty_multiplier' => 0,
			'wc_booking_block_qty_multiplier' => 0
		);

		$SELECTcheckout = array(
			'name' => 'CheckOut',
			'description' => 'Hora de Salida de tu mascota',
			'type' => 'select',
			'position' => 0,
			'options' => $hour,
			'required' => 0,
			'wc_booking_person_qty_multiplier' => 0,
			'wc_booking_block_qty_multiplier' => 0
		);

		foreach ($result as $row){
			$ID =  $row->meta_id;
			$addons =  $row->meta_value;
			$addons =  unserialize($addons);
			$checkin = 0;
			$checkout = 0;

			foreach($addons as $index => $addon){
				if($addon['name']=='CheckIn'){
					$checkin = $index;
				}else if($addon['name']=='CheckOut'){
					$checkout = $index;
				}

			}

			if($checkin!=0){
				$addons[$checkin]=$SELECTcheckin;
			}else{
				$addons[]=$SELECTcheckin;
			}

			if($checkout!=0){
				$addons[$checkout]=$SELECTcheckout;
			}else{
				$addons[]=$SELECTcheckout;
			}

			/*
			if($postAUTHOR=='8631'){
				echo '<pre>';
				var_dump($addons);
				echo '</pre>';
				exit();
			}
			*/

			$addons =  utf8_decode(serialize($addons));
			//$wpdb->update('wp_postmeta', array( 'meta_value' => $addons),array('meta_id'=>$ID));
			$sql = "UPDATE wp_postmeta SET meta_value = '{$addons}' WHERE meta_id = '{$ID}';";
			$wpdb->query($sql);
			//var_dump($sql);
		}
	}
}

//CG only unique use!!!!!!!!!
// update_additional_service();
// update_additional_service_postname();
//update_addons_service();
?>