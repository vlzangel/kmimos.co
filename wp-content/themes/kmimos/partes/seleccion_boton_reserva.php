<?php
	$order = "10";
	$order_service = [
		'hospedaje' => [ 
			"order" => 1, 
			"icon" => "icon-hospedaje" 
		],
		'guarderia' => [ 
			"order" => 2, 
			"icon" => "icon-guarderia" 
		],
		'paseos' => [
			"order" => 3, 
			"icon" => "icon-paseos",
		],
		'entrenamiento' => [ 
			"order"=> 4,
			"icon" => "icon-adiestramiento",
		]
	];
	$url_servicio = [];
	$ids = "";
	if( isset($_SESSION['busqueda']) ){
		$busqueda = unserialize($_SESSION['busqueda']); 
		$busqueda_servicios = $busqueda['servicios']; 
		$condicion = "";
		if( $busqueda_servicios>0 ){
			$where = implode( "%' or  post_name like '", $busqueda_servicios);
			$where = "post_name like '{$where}%'";
		}
		$sql = "
			SELECT * 
			FROM wp_posts 
			WHERE post_author = {$cuidador->user_id} 
				AND post_status = 'publish'
				AND ( {$where} )
		";
		$rows = $wpdb->get_results($sql);
		foreach ($rows as $row) {
			$separador = (!empty($ids))? ",": "";
			$ids .= $separador.$row->ID;
			$icon_service = "icon-sentado";
			$temp_option = explode("-", $row->post_name);
			if( count($temp_option) > 0 ){
				$key = strtolower($temp_option[0]);
				if( array_key_exists($key, $order_service) ){
					$i = $order_service[$key];
					$icon_service = $i['icon'];
					$order = $i['order'];
				}
			}
			$url_servicio[ "{$order}-{$row->ID}" ] = [
				'icon' => $icon_service, 
				'url' =>  get_home_url().'/reservar/'.$row->ID,
				'name' => $row->post_title,
			];
		}
		ksort($url_servicio);
	}
	if( count($url_servicio) > 1 ){

		$content_modal .= '
		<a href="#" id="servicios" name="redirigir" class="km-btn-secondary">
		  	RESERVAR
		</a>

		<div id="modal_servicio" class="modal_login">
	        <div class="modal_container">
	            <div class="modal_box" style="padding: 40px 0px 20px;">
	                <img id="close_login" src="'.getTema().'/images/closebl.png" />';
				    foreach($url_servicio as $url){
						$content_modal .= '
						<button name="redirigir" value="'.$url['url'].'" class="modal-items">
							<i class="'.$url['icon'].'"></i>
							<span style="margin-left: 5px;">'.$url['name'].'</span>
						</button>
						';
				    } $content_modal .= '
	            </div>
	        </div>
	    </div>';

		$BOTON_RESERVAR .= $content_modal;

	}else{
		if( count($url_servicio) == 1){
			foreach ($url_servicio as $item) {
				$BOTON_RESERVAR .= '<button id="btn_reservar" name="redirigir" class="km-btn-secondary" value="'.$item['url'].'">RESERVAR</button>';
				break;
			}
		}else{				
			$BOTON_RESERVAR .= '<button id="btn_reservar" name="redirigir" class="km-btn-secondary" value="'.get_home_url().'/reservar/'.$id_hospedaje.'/'.'">RESERVAR</button>';
		}
	}
?>