<?php
wp_enqueue_script( 'kmimos_bootstrap_js', get_home_url()."/panel/assets/vendor/bootstrap/dist/js/bootstrap.min.js",
	array(), '1.0.0', true );

$order = "10"; // Orden por defecto
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

		// Cargar IDs de Servicios
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
			// Cargar orden
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
			// Cargar URL del producto
			$url_servicio[ "{$order}-{$row->ID}" ] = [
				'icon' => $icon_service, 
				'url' =>  get_home_url().'/producto/'.$row->post_name,
				'name' => $row->post_title,
			];
		}
		ksort($url_servicio);

		// echo '<pre>';
		// print_r( $url_servicio );
		// echo '</pre>';
}
if( count($url_servicio) > 1 ){

	// Buscar solo productos relacionados a la busqueda por woocomerce
	// $servicios_comando = '[products ids="'.$ids.'"]';
	// do_shortcode($servicios_comando)

	$content_modal .= '<!-- Button trigger modal -->
		<button type="button" class="button reservar" data-toggle="modal" data-target="#selector_servicios">
		  Reservar
		</button>

		<!-- Modal -->
		<div class="modal" id="selector_servicios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div>
		      <div class="modal-content">
		        
		        	<strong class="modal_title">Cual servicio deseas?</stronge>
		        	<button style="float:right;" class="btn btn-default btn-sm close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        
		      </div>
		      <div class="modal-body">
		      	<div  class="row">
		        ';
			    foreach($url_servicio as $url){
					$content_modal .= '
					<a href="'.$url['url'].'">
						<div class="row text-left modal-items">	
							<i class="'.$url['icon'].'"></i>
							<span style="margin-left: 5px;">'.$url['name'].'</span>
						</div>
					</a>
					';
			    }
		$content_modal .= '

			      </div>
		      </div>

		    </div>
		  </div>
		</div>';

		echo $content_modal;

}else{
	if( count($url_servicio) == 1){
		// Buscar url del servicio
		foreach ($url_servicio as $item) {
			echo '<a class="button reservar" href="'.$item['url'].'">Reservar</a>';
			break;
		}
	}
	else{				
		echo '<a class="button reservar" href="'.get_home_url().'/producto/'.$slug.'/'.'">Reservar</a>';
	}
}

?>

<style type="text/css">
 #selector_servicios{
 	position: absolute;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1050;
    display: none;
    overflow: hidden;
    -webkit-overflow-scrolling: touch;
    outline: 0;	
    max-width: 400px;
    margin:auto auto;
 }
 .modal-dialog{
	background-color: #fff;
	border: 1px solid #ccc;
	padding-bottom:20px!important;
	border-radius: 10px;
 }
 .modal-content{
 	padding-left:0px;
 }
 .modal_title{
 	line-height: 22px;
	font-size: 16px;
 }
 .modal-body{
 	margin: 0 auto;padding:0px;
 }
 .modal-items{
 	border-radius:10px; 
 	border:1px solid #ccc; 
	font-weight: bold;
 	max-width: 400px;
 	margin:5px;
 	text-align: left!important;
 	padding-bottom:10px!important; 
 }
 .modal-items:hover{
	background: #0ab7a1;
	color:#fff;
 }
.modal-items-none{
	background: #0ab7a1;
	color:#fff;
 }
</style>