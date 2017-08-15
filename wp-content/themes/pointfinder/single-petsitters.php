<?php

	get_header();

	$post_id = get_the_id();
	$meta = get_post_meta( $post_id );

	global $wpdb;
	global $post;

	$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = ".$post->ID);
	$descripcion = $wpdb->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$cuidador->user_id} AND meta_key = 'description'");

	$slug = $wpdb->get_var("SELECT post_name FROM wp_posts WHERE post_type = 'product' AND post_author = '{$cuidador->user_id}' AND post_name LIKE '%hospedaje%' ");

	$latitud 	= $cuidador->latitud;
	$longitud 	= $cuidador->longitud;

	$foto = kmimos_get_foto_cuidador($cuidador->id);

	$tama_aceptados = unserialize( $cuidador->tamanos_aceptados );
	$tamanos = array(
		'pequenos' => 'Pequeños',
		'medianos' => 'Medianos',
		'grandes'  => 'Grandes',
		'gigantes' => 'Gigantes'
	);

	$aceptados = array();
	foreach ($tama_aceptados as $key => $value) {
		if( $value == 1){
			$aceptados[] = $tamanos[$key];
		}
	} 

	$edad_aceptada = unserialize( $cuidador->edades_aceptadas );
	$edades = array(
		'cachorros' => 'Cachorros',
		'adultos' => 'Adultos'
	);
	$edades_aceptadas = array();
	foreach ($edad_aceptada as $key => $value) {
		if( $value == 1){
			$edades_aceptadas[] = $edades[$key];
		}
	} 

	$atributos = unserialize( $cuidador->atributos );

	$anios_exp = $cuidador->experiencia;
	if( $anios_exp > 1900 ){
		$anios_exp = date("Y")-$anios_exp;
	}

	$mascota_cuidador = unserialize( $cuidador->mascotas_cuidador );
	$mascotas_cuidador = array();
	foreach ($mascota_cuidador as $key => $value) {
		if( $value == 1){
			$mascotas_cuidador[] = $tamanos[$key];
		}
	}

	/* Galeria */

	$id_cuidador = ($cuidador->id)-5000;
	$path_galeria = "wp-content/uploads/cuidadores/galerias/".$id_cuidador."/";

	if( is_dir($path_galeria) ){

		if ($dh = opendir($path_galeria)) { 
			$imagenes = array();
	        while (($file = readdir($dh)) !== false) { 
	            if (!is_dir($path_galeria.$file) && $file!="." && $file!=".."){ 
	               $imagenes[] = $path_galeria.$file;
	            } 
	        } 
	      	closedir($dh);

	      	$cant_imgs = count($imagenes);
	      	if( $cant_imgs > 0 ){
	      		$items = array(); $home = get_home_url()."/";
	      		foreach ($imagenes as $value) {//
	      			$items[] = "
	      				<div class='vlz_item scroll_animate' data-scale='small' data-position='top' onclick=\"vlz_galeria_ver('".$home.$value."')\">
	      					<div class='vlz_item_fondo easyload' data-original='".$home.$value."'  style='background-image: url(); filter:blur(2px);'></div>
	      					<div class='vlz_item_imagen easyload' data-original='".$home.$value."' style='background-image: url();'></div>
	      				</div>
	      			";
	      		}
	      		$galeria = "
	      			<div class='vlz_contenedor_galeria'>
	      				<div class='vlz_contenedor_galeria_interno' style='width: ".($cant_imgs*300)."px;'>
		      				".implode("", $items)."
		      			</div>
	      			</div>
	      			<div class='vlz_modal_galeria' onclick='vlz_galeria_cerrar()'>
	      				<div class='vlz_modal_galeria_interna'></div>
	      			</div>
	      			<script>
	      				function vlz_galeria_ver(url){
	      					jQuery('.vlz_modal_galeria_interna').css('background-image', 'url('+url+')');
	      					jQuery('.vlz_modal_galeria').css('display', 'table');
	      				}
	      				function vlz_galeria_cerrar(){
	      					jQuery('.vlz_modal_galeria').css('display', 'none');
	      					jQuery('.vlz_modal_galeria_interna').css('background-image', '');
	      				}
	      			</script>
	      		";
	      	}else{
	      		$galeria = "";
	      	}
  		} 
	}

	include("vlz/vlz_style_perfil.php");

 	$HTML .= "
	<div class='vlz_contenedor'>

		<div class='vlz_contenedor_header'>

			<div class='vlz_lados'>
				<div class='vlz_img_portada'>
	                <div class='vlz_img_portada_fondo easyload' data-original='".$foto."' style='background-image: url(); filter:blur(2px);'></div>
	                <div class='vlz_img_portada_normal easyload' data-original='".$foto."' style='background-image: url();'></div>
	            </div>
			</div>

			<div class='vlz_lados'>
				<h1 class='center-white'>".get_the_title()."</h1>
				".kmimos_petsitter_rating($post_id);
				if(is_user_logged_in()){
					$HTML .= "<a id='btn_conocer' class='theme_button button conocer-cuidador' href='".get_home_url()."/conocer-al-cuidador/?id=".$post_id."'>Conocer al Cuidador</a>";
					include('vlz/seleccion_boton_reserva.php');
				}else{
					$HTML .= "
					<span 
						id='btn_conocer'
						class='theme_button button conocer-cuidador' 
						onclick=\"perfil_login('btn_conocer');\"
					>Conocer al Cuidador</span>
					<span 
						id='btn_reservar'
						class='button reservar' 
						onclick=\"perfil_login('btn_reservar');\"
					>Reservar</span>";
				} $HTML .= "
			</div>
		</div>";

		if( $descripcion != "" ){
			$HTML .= '<div class="vlz_separador"></div>
			<h3 class="vlz_titulo">Descripción del Cuidador</h3>
			<div class="vlz_seccion vlz_descripcion">
				<p> '.$descripcion.' </p>
			</div>';
		}

		if( $galeria != "" ){
			$HTML .= '<div class="vlz_separador"></div>
			<h3 class="vlz_titulo">Mi Galería</h3>
			<div class="vlz_seccion vlz_descripcion">
				'.$galeria.'
			</div>';
		}

		$housings = array('1'=>'Casa','2'=>'Departamento');
		$patio = ( $atributos['yard'] == 1 ) ? 'Tiene patio' : 'No tiene patio';
		$areas = ( $atributos['green'] == 1 ) ? 'Tiene áreas verdes' : 'No tiene áreas verdes';

		$HTML .= '
		<div class="vlz_separador"></div>

		<h3 class="vlz_titulo">Detalles del Cuidador</h3>
		<div class="vlz_seccion">

			<div class="vlz_detalles">
				<div class="vlz_item_detalles">
					<p class="label text-gray">Tipo de propiedad</p>
					<div class="icon">
						<img alt="Detalles casa" height="32px" src="'.get_home_url().'/wp-content/plugins/kmimos/assets/images/casa.png">
					</div>
					<p class="label-small">
						<b>'.$housings[ $atributos['propiedad'] ].'</b>
					</p>
				</div>

				<div class="vlz_item_detalles">
					<p class="label text-gray">Tamaños aceptados</p>
					<div class="icon"><img alt="Detalles perro grande" height="32px" src="'.get_home_url().'/wp-content/plugins/kmimos/assets/images/detalles-perro-grande.png"></div>
					<p class="label-small">';
						if( count($aceptados) > 0 ){
							$HTML .= '<br>('.implode(', ',$aceptados).')';
						}else{
							$HTML .= "Todos";
						}
						$HTML .= '<b>'.$tams_acep.'</b>
					</p>
				</div>

				<div class="vlz_separador_item"></div>

				<div class="vlz_item_detalles">
					<p class="label text-gray">Edades aceptadas</p>
					<div class="icon">
						<img alt="Detalles edad perro cachorro" height="32px" src="'.get_home_url().'/wp-content/plugins/kmimos/assets/images/detalles-edad-perro-cachorro.png">
					</div>
					<p class="label-small">
						<b>'.implode(', ',$edades_aceptadas).'</b>
					</p>
				</div>

				<div class="vlz_item_detalles">
					<p class="label text-gray">Años de experiencia</p>
					<div class="icon">
						<img alt="Detalles experiencia" height="32px" src="'.get_home_url().'/wp-content/plugins/kmimos/assets/images/detalles-experiencia.png">
					</div>
					<p class="label-small"> <b>'.$anios_exp.'</b> </p>
				</div>
			</div>

		</div>

		<div class="vlz_separador"></div>

			<h3 class="vlz_titulo">Otros Detalles</h3>
			<div class="vlz_seccion">

				<div class="vlz_detalles">
					<div class="vlz_item_detalles">
						<p class="label text-gray">Mascotas en casa</p>
						<div class="icon">
							<img alt="Otros detalles otros perros" height="32px" src="'.get_home_url().'/wp-content/plugins/kmimos/assets/images/otros-detalles-otros-perros.png">
						</div>';
						if($cuidador->num_mascotas+0 > 0){ 
							if( count($mascotas_cuidador) > 0 ){
								$tams = '<br>('.implode(', ',$mascotas_cuidador).')';
							}else{
								$tams = "";
							} 
							$HTML .= '<p class="label-small"> <b>'.$cuidador->num_mascotas.' Perro(s) en casa '.$tams.'</b> </p>';
						}else{
							$HTML .= '<p class="label-small"> <b>No tiene mascotas propias</b> </p>';
						} $HTML .= '
					</div>

					<div class="vlz_item_detalles">
						<p class="label text-gray">Mi propiedad</p>
						<div class="icon"><img alt="Otros detalles patio" height="32px" src="'.get_home_url().'/wp-content/plugins/kmimos/assets/images/otros-detalles-patio.png"></div>
						<p class="label-small">
							<b>'.$patio.'</b>
						</p>
					</div>

					<div class="vlz_separador_item"></div>

					<div class="vlz_item_detalles">
						<p class="label text-gray">Mi propiedad</p>
						<div class="icon"><img alt="Otros detalles areas verdes" height="32px" src="'.get_home_url().'/wp-content/plugins/kmimos/assets/images/otros-detalles-areas-verdes.png"></div>
						<p class="label-small">
							<b> '.$areas.'</b>
						</p>
					</div>

					<div class="vlz_item_detalles">
						<p class="label text-gray"># Perros aceptados</p>
						<div class="icon"><img alt="Otros detalles cantidad perros" height="32px" src="'.get_home_url().'/wp-content/plugins/kmimos/assets/images/otros-detalles-cantidad-perros.png"></div>
						<p class="label-small">
							<b>'.$cuidador->mascotas_permitidas.' </b>
						</p>
					</div>

				</div>

			</div>

		<div class="vlz_separador"></div>

		<h3 class="vlz_titulo">Mi Ubicaci&oacute;n</h3>
		<div class="vlz_seccion">
			<div id="mapa" style="height: 300px;"></div>
		</div>

		<div class="vlz_separador"></div>
		<h3 class="vlz_titulo">Estos son mis servicios</h3>
		<div class="vlz_seccion">';
			$args = array(
				"post_type" => "product",
		        "post_status" => "publish",
		        "author" => $cuidador->user_id
		    );

		    $products = get_posts( $args );

		    $ids = "";
		    foreach($products as $product){
		        if( $ids != "") $ids .= ",";
		        $ids .= $product->ID;
		    }

		    if($ids != ""){
		        $comando = "[products ids='".$ids."']";
		        $HTML .= do_shortcode($comando);
		    } $HTML .= "
		</div>";

		if( $atributos['video_youtube'][0] != ''){
			$video = $atributos['video_youtube'];
			preg_match_all('#v=(.*?)#', $video, $encontrados);
			$HTML .= '
				<div class="vlz_separador"></div>
				<h3 class="vlz_titulo">Este es el video que el cuidador subió a Youtube.</h3>
				<div class="vlz_seccion">
					<iframe id="video_youtube" width="100%" src="https://www.youtube.com/embed/'.$video.'" frameborder="0" allowfullscreen></iframe>
				</div>
			';
		}

		echo comprimir_styles($HTML);
			
			$comments = count( get_comments('post_id='.$post->ID) ); ?>
				<div class="vlz_separador"></div>
				<h3 class="vlz_titulo">Valoraciones</h3>
				<div class="vlz_seccion">
					<?php  comments_template(); ?>
				</div> <?php			
		$HTML = '</div>

		<script>
			function perfil_login(accion){
				jQuery.cookie("POST_LOGIN", accion);
				jQuery("#pf-login-trigger-button").click();
			}

			jQuery( document ).ready(function() {
			  	var POST_LOGIN = jQuery.cookie("POST_LOGIN");
				if( POST_LOGIN != undefined ){
					jQuery.removeCookie("POST_LOGIN");
					document.getElementById(POST_LOGIN).click();
				}
			});

			var map;
			function initMap() {
				var latitud = '.$latitud.';
				var longitud = '.$longitud.';
				map = new google.maps.Map(document.getElementById("mapa"), {
					zoom: 10,
					center:  new google.maps.LatLng(latitud, longitud), 
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				marker = new google.maps.Marker({
					map: map,
					draggable: false,
					animation: google.maps.Animation.DROP,
					position: new google.maps.LatLng(latitud, longitud),
					icon: "https://www.kmimos.com.mx/wp-content/themes/pointfinder/vlz/img/pin.png"
				});
			}

			(function(d, s){
				$ = d.createElement(s), e = d.getElementsByTagName(s)[0];
				$.async=!0;
				$.setAttribute("charset","utf-8");
				$.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&callback=initMap";
				$.type="text/javascript";
				e.parentNode.insertBefore($, e)
			})(document, "script");

		</script>';

		echo comprimir_styles($HTML);

	get_footer(); 
?>