<?php

    wp_enqueue_style('perfil_cuidador', getTema()."/css/perfil_cuidador.css", array(), '1.0.0');
	wp_enqueue_style('perfil_cuidador_responsive', getTema()."/css/responsive/perfil_cuidador_responsive.css", array(), '1.0.0');
	
    wp_enqueue_style('conocer', getTema()."/css/conocer.css", array(), '1.0.0');
    wp_enqueue_style('conocer_responsive', getTema()."/css/responsive/conocer_responsive.css", array(), '1.0.0');

	wp_enqueue_script('perfil_cuidadores', getTema()."/js/perfil_cuidadores.js", array("jquery"), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

	get_header();

	$post_id = get_the_id();
	$meta = get_post_meta( $post_id );

	global $wpdb;
	global $post;

	$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = ".$post->ID);
	$descripcion = $wpdb->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$cuidador->user_id} AND meta_key = 'description'");

	$user_id = get_current_user_id();
	$favoritos = get_favoritos();
	$fav_check = 'false';
        $fav_del = '';
        if (in_array($cuidador->id_post, $favoritos)) {
            $fav_check = 'true'; 
            $favtitle_text = esc_html__('Quitar de mis favoritos','kmimos');
            $fav_del = 'favoritos_delete';
        }
        $favoritos_link = 
        '<span href="javascript:;" 
            data-reload="false"
            data-user="'.$user_id.'" 
            data-num="'.$cuidador->id_post.'" 
            data-active="'.$fav_check.'"
            data-favorito="'.$fav_check.'"
            class="km-link-favorito '.$fav_del.'" '.$style_icono.'
            style="background-image: url('.getTema().'/images/new/bg-foto-resultados.png) !important;"
            >
            <i class="fa fa-heart" aria-hidden="true"></i>
        </span>';

	$slug = $wpdb->get_var("SELECT post_name FROM wp_posts WHERE post_type = 'product' AND post_author = '{$cuidador->user_id}' AND post_name LIKE '%hospedaje%' ");

	$latitud 	= $cuidador->latitud;
	$longitud 	= $cuidador->longitud;

	$HTML = '
		<script>
			var lat = '.$latitud.';
			var lng = '.$longitud.';
		</script>
	';

	echo comprimir_styles($HTML);

	$foto = kmimos_get_foto($cuidador->user_id);

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

	$housings = array(
		'1' => 'Casa',
		'2' => 'Departamento'
	);

	$acepto = ""; $t = count($aceptados);
	if( $t > 0 && $t < 4 ){
		$acepto .= implode(', ',$aceptados);
	}else{
		if( $t == 0 ){
			$acepto = "Ninguno";
		}else{
			$acepto = "Todos";
		}
	}
	$num_masc = "";
	if($cuidador->num_mascotas+0 > 0){ 
		if( count($mascotas_cuidador) > 0 ){
			$tams = '<br>('.implode(', ',$mascotas_cuidador).')';
		}else{
			$tams = "";
		} 
		if( $cuidador->num_mascotas > 1 ){
			$num_masc = $cuidador->num_mascotas.' Perro '.$tams;
		}else{
			$num_masc = $cuidador->num_mascotas.' Perros '.$tams;
		}
	}else{
		$num_masc = 'No tiene mascotas';
	}
	$num_masc = strtoupper($num_masc);

	$patio = ( $atributos['yard'] == 1 ) ? 'TIENE PATIO' : 'NO TIENE PATIO';
	$areas = ( $atributos['green'] == 1 ) ? 'TIENE ÁREAS VERDES' : 'NO TIENE ÁREAS VERDES';

	if( $cuidador->mascotas_permitidas > 1 ){
		$cuidador->mascotas_permitidas .= ' PERROS';
	}else{
		$cuidador->mascotas_permitidas .= ' PERRO';
	}

	/* Galeria */
	$id_cuidador = ($cuidador->id)-5000;
	$path_galeria = "wp-content/uploads/cuidadores/galerias/".$id_cuidador."/";

	$galeria_array = array();

	if( is_dir($path_galeria) ){

		if ($dh = opendir($path_galeria)) { 
			$imagenes = array();
	        while (($file = readdir($dh)) !== false) { 
	            if (!is_dir($path_galeria.$file) && $file!="." && $file!=".."){ 
	               $imagenes[] = $path_galeria.$file;
	      			
	      			$galeria_array[] = $id_cuidador."/".$file;
	            } 
	        } 
	      	closedir($dh);

	      	$cant_imgs = count($imagenes);
	      	if( $cant_imgs > 0 ){
	      		$items = array(); 
	      		$home = get_home_url()."/";
	      		foreach ($imagenes as $value) {

	      			$items[] = "
	      				<div class='slide' data-scale='small' data-position='top' onclick=\"vlz_galeria_ver('".$home.$value."')\">
	      					<div class='vlz_item_fondo' style='background-image: url(".$home.$value."); filter:blur(2px);'></div>
	      					<div class='vlz_item_imagen' style='background-image: url(".$home.$value.");'></div>
	      				</div>
	      			";

	      		}
/*	      		$galeria = '
	      			<p class="km-tit-ficha">MIRA MIS FOTOS Y CONÓCEME</p>
					<div class="km-galeria-cuidador">
						<div class="km-galeria-cuidador-slider">
							'.implode("", $items).'
						</div>
					</div>
	      		'.
	      		"
	      			<div class='vlz_modal_galeria' onclick='vlz_galeria_cerrar()'>
	      				<span onclick='vlz_galeria_cerrar()' class='close' style='position:absolute;top:10px;right:10px;color:white;z-index:999;'><i class='fa fa-times' aria-hidden='true'></i></span>
	      				<div class='vlz_modal_galeria_interna'></div>
	      			</div>
	      		";*/

	      		$galeria = '
	      			<p class="km-tit-ficha">MIRA MIS FOTOS Y CONÓCEME</p>
					<div class="km-galeria-cuidador">
						<div class="km-galeria-cuidador-slider">
							<div class="perfil_cuidador_cargando">
								<div style="background-image: url('.getTema().'/images/cargando.gif);" ></div> Cangando Galer&iacute;a...
							</div>
						</div>
					</div>
	      		'.
	      		"
	      			<div class='vlz_modal_galeria' onclick='vlz_galeria_cerrar()'>
	      				<span onclick='vlz_galeria_cerrar()' class='close' style='position:absolute;top:10px;right:10px;color:white;z-index:999;'><i class='fa fa-times' aria-hidden='true'></i></span>
	      				<div class='vlz_modal_galeria_interna'></div>
	      			</div>
	      		";


	      	}else{
	      		$galeria = "";
	      	}
  		} 
	}

	$busqueda = getBusqueda();

	$precios_hospedaje = unserialize($cuidador->hospedaje);
	$precios_adicionales = unserialize($cuidador->adicionales);


	$id_hospedaje = 0;
	$servicios = $wpdb->get_results("
		SELECT * 
			FROM wp_posts 
			WHERE post_author = {$cuidador->user_id} AND post_type = 'product' AND post_status = 'publish' 
		");

	$productos = '<div class="row">';
	foreach ($servicios as $servicio) {
		$tipo = $wpdb->get_var("
            SELECT
                tipo_servicio.slug AS slug
            FROM 
                wp_term_relationships AS relacion
            LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
            WHERE 
                relacion.object_id = '{$servicio->ID}' AND
                relacion.term_taxonomy_id != 28
        ");

        $titulo = get_servicio_cuidador($tipo);

        $tamanos_precios = array();
        $precios = $precios_hospedaje;
        if( $tipo != "hospedaje" ){
        	$precios = $precios_adicionales[ str_replace('-', '_',  $tipo) ];
        }else{
        	$id_hospedaje = $servicio->ID;
        }

        if( !empty($precios) ){

	        $tamanos_servicio = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_parent = '{$servicio->ID}' AND post_type = 'bookable_person' ");// AND post_status = 'publish'

	        foreach ($tamanos_servicio as $tamano ) {
	        	$activo = false;
	        	if( isset($busqueda["servicios"]) ){
		        	if( in_array($tipo, $busqueda["servicios"]) ){
		        		$activo = true;
		        	}
		        	preg_match_all("#adiestramiento#", $tipo, $matches);

		        	if( in_array("adiestramiento", $busqueda["servicios"]) && count( $matches ) > 0 ){
		        		$activo = true;
		        	}
	        	}

	        	$temp_tamanos = get_tamano($tamano->post_title, $precios, $activo, $busqueda["tamanos"],$tamano->post_status);

	        	$tamanos_precios[ $temp_tamanos[0] ] = $temp_tamanos[1];
	        }

	        $tamanos_txt = "";
	        foreach ($tamanos as $key => $value) {
	        	$tamanos_txt .= $tamanos_precios[$key];
	        }


			$productos .= '
			<div class="col-xs-12 col-md-6">
				<div class="km-ficha-servicio">
					<a href="'.get_home_url().'/reservar/'.$servicio->ID.'" class="">
						'.$titulo.'
						<!--p>SELECCIÓN SEGÚN TAMAÑO</p-->
						'.$tamanos_txt.'
					</a>
				</div>
			</div>';
		}
	}
	$productos .= '</div>';

	if(is_user_logged_in()){
		include('partes/seleccion_boton_reserva.php');

		$BOTON_RESERVAR = '

			<a href="javascript:;"
				id="btn_conocer"
	            data-target="#popup-conoce-cuidador"
	            data-name="'.strtoupper( get_the_title() ).'" 
	            data-id="'.$cuidador->id_post.'"
				class="km-btn-secondary" 
			>CON&Oacute;CELO +</a>

		'.$BOTON_RESERVAR;


	}else{
		$BOTON_RESERVAR .= '

			<a href="javascript:;"
				id="btn_conocer"
				data-target="#popup-iniciar-sesion"
				class="km-btn-secondary" 
			>CON&Oacute;CELO +</a>

			<a href="javascript:;"
				id="btn_reservar"
				data-target="#popup-iniciar-sesion"
				class="km-btn-secondary" 
			>RESERVAR</a>

		';
	}

    include ('partes/cuidador/conocelo.php');

 	$HTML .= '
 		<script> 
 			var SERVICIO_ID = "'.$cuidador->id_post.'"; 
 			var GALERIA = jQuery.parseJSON(\''.json_encode($galeria_array).'\'); 
 		</script>
 		<div class="km-ficha-bg" style="background-image:url('.getTema().'/images/new/km-ficha/km-bg-ficha.jpg);">
			<div class="overlay"></div>
		</div>
		<div class="km-ficha-info-cuidador">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-3">
						<div class="img_cuidador">
							'.$favoritos_link.'
							<img src="'.$foto.'" />
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="km-tit-cuidador">'.strtoupper( get_the_title() ).'</div>
						<div class="km-ficha-icon">
							<div class="km-ranking">
								'.kmimos_petsitter_rating($cuidador->id_post).'
							</div>
							<a class="km-link-comentarios" href="#km-comentario">VER COMENTARIOS</a>
						</div>
					</div>
					<div class="km-costo hidden-xs">
						<form id="form_cuidador" method="POST" action="'.getTema().'/procesos/reservar/redirigir_reserva.php">
							<div class="servicio_desde">
								<p>SERVICIOS DESDE</p>
								<div class="km-tit-costo">MXN $'.($cuidador->hospedaje_desde*1.2).'</div>
							</div>
							<div class="km-ficha-fechas">
								<input type="text" id="checkin" data-error="reset" data-valid="requerid" name="checkin" placeholder="DESDE" value="'.$busqueda["checkin"].'" class="date_from" readonly>
								<input type="text" id="checkout" data-error="reset" name="checkout" data-valid="requerid" placeholder="HASTA" value="'.$busqueda["checkout"].'" class="date_to" readonly>
								<small class="validacion_fechas">Debe seleccionar las fechas</small>
							</div>
							'.$BOTON_RESERVAR.'
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="km-ficha-info">
			<div class="container">
					<div class="col-xs-12 col-sm-3 hidden-xs">
						<p class="km-tit-ficha">DATOS DEL CUIDADOR</p>
						<div class="km-desc-ficha-text">
							<img src="'.getTema().'/images/new/icon/icon-experiencia-morado.svg">
							<div class="km-desc-ficha">
								<p>EXPERIENCIA</p>
								<p>'.$anios_exp.' AÑO(S)</p>
							</div>
						</div>
						<div class="km-desc-ficha-text">
							<img src="'.getTema().'/images/new/icon/icon-propiedad.svg">
							<div class="km-desc-ficha">
								<p>TIPO DE PROPIEDAD</p>
								<p>'.$housings[ $atributos['propiedad'] ].'</p>
							</div>
						</div>
						<div class="km-desc-ficha-text">
							<img src="'.getTema().'/images/new/icon/icon-propiedad.svg">
							<div class="km-desc-ficha">
								<p>TAMAÑOS ACEPTADOS</p>
								<p>'.$acepto.'</p>
							</div>
						</div>
						<div class="km-desc-ficha-text">
							<img src="'.getTema().'/images/new/icon/icon-edades.svg">
							<div class="km-desc-ficha">
								<p>EDADES ACEPTADAS</p>
								<p>'.implode(', ',$edades_aceptadas).'</p>
							</div>
						</div>
						<p class="km-tit-ficha">DATOS DE PROPIEDAD</p>
						<div class="km-desc-ficha-text">
							<img src="'.getTema().'/images/new/icon/icon-mascotas.svg">
							<div class="km-desc-ficha">
								<p>MASCOTAS EN CASA</p>
								<p>'.$num_masc.'</p>
							</div>
						</div>
						<div class="km-desc-ficha-text">
							<img src="'.getTema().'/images/new/icon/icon-propiedad.svg">
							<div class="km-desc-ficha">
								<p>DETALLES DE PROPIEDAD</p>
								<p>'.$patio.'</p>
							</div>
						</div>
						<div class="km-desc-ficha-text">
							<img src="'.getTema().'/images/new/icon/icon-propiedad.svg">
							<div class="km-desc-ficha">
								<p>DETALLES DE PROPIEDAD</p>
								<p>'.$areas.'</p>
							</div>
						</div>
						<div class="km-desc-ficha-text">
							<img src="'.getTema().'/images/new/icon/icon-mascotas.svg">
							<div class="km-desc-ficha">
								<p>CANTIDAD MÁX. ACEPTADA</p>
								<p>'.$cuidador->mascotas_permitidas.'</p>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="km-ficha-datos hidden-sm hidden-md hidden-lg">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab1" data-toggle="tab">DATOS DEL <br>CUIDADOR</a></li>
									<li><a href="#tab2" data-toggle="tab">DATOS DE <br>PROPIEDAD</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab1">
										
										<div class="km-desc-ficha-text">
											<img src="'.getTema().'/images/new/icon/icon-experiencia-morado.svg">
											<div class="km-desc-ficha">
												<p>EXPERIENCIA</p>
												<p>'.$anios_exp.' AÑO(S)</p>
											</div>
										</div>
										<div class="km-desc-ficha-text">
											<img src="'.getTema().'/images/new/icon/icon-propiedad.svg">
											<div class="km-desc-ficha">
												<p>TIPO DE PROPIEDAD</p>
												<p>'.$housings[ $atributos['propiedad'] ].'</p>
											</div>
										</div>
										<div class="km-desc-ficha-text">
											<img src="'.getTema().'/images/new/icon/icon-propiedad.svg">
											<div class="km-desc-ficha">
												<p>TAMAÑOS ACEPTADOS</p>
												<p>'.$acepto.'</p>
											</div>
										</div>
										<div class="km-desc-ficha-text">
											<img src="'.getTema().'/images/new/icon/icon-edades.svg">
											<div class="km-desc-ficha">
												<p>EDADES ACEPTADAS</p>
												<p>'.implode(', ',$edades_aceptadas).'</p>
											</div>
										</div>

									</div>
									<div class="tab-pane" id="tab2">

										<div class="km-desc-ficha-text">
											<img src="'.getTema().'/images/new/icon/icon-mascotas.svg">
											<div class="km-desc-ficha">
												<p>MASCOTAS EN CASA</p>
												<p>'.$num_masc.'</p>
											</div>
										</div>
										<div class="km-desc-ficha-text">
											<img src="'.getTema().'/images/new/icon/icon-propiedad.svg">
											<div class="km-desc-ficha">
												<p>DETALLES DE PROPIEDAD</p>
												<p>'.$patio.'</p>
											</div>
										</div>
										<div class="km-desc-ficha-text">
											<img src="'.getTema().'/images/new/icon/icon-propiedad.svg">
											<div class="km-desc-ficha">
												<p>DETALLES DE PROPIEDAD</p>
												<p>'.$areas.'</p>
											</div>
										</div>
										<div class="km-desc-ficha-text">
											<img src="'.getTema().'/images/new/icon/icon-mascotas.svg">
											<div class="km-desc-ficha">
												<p>CANTIDAD MÁX. ACEPTADA</p>
												<p>'.$cuidador->mascotas_permitidas.'</p>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
						<div class="km-ficha-datos hidden-sm hidden-md hidden-lg">
							<a href="javascript:;" class="km-btn-primary show-map-mobile">VER UBICACIÓN EN MAPA</a>
						</div>
						<p style="text-align: justify;">'.$descripcion.'</p>
						'.$galeria.'
						<p class="km-tit-ficha">SERVICIOS QUE OFREZCO</p>
						'.$productos.'
					</div>
					<div class="hidden-xs col-sm-3 km-map-content">
						<a href="#" class="km-map-close">Cerrar</a>
						<p class="km-tit-ficha">UBICACIÓN</p>

						<div id="mapa" class="km-ficha-mapa"></div>

					</div>
				</div>
			</div>
		</div>
 	';

	echo comprimir_styles($HTML);
?>

<div id="km-comentario" class="km-ficha-info">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-offset-3 col-sm-6">
				<div class="km-review">
					<p class="km-tit-ficha">COMENTARIOS</p>
					<div class="km-calificacion">0</div>
					<div class="km-calificacion-icono">
						<div class="km-calificacion-bond"></div>
						<p>0% Lo recomienda</p>
					</div>
				</div>

				<a href="javascript:;" class="km-btn-comentario" onclick="jQuery('.BoxComment').fadeToggle();">ESCRIBE UN COMENTARIO</a>
				<div class="BoxComment"><?php comments_template('/template/comment.php'); ?></div>
				<div id="comentarios_box"> </div>
			</div>
		</div>
	</div>
</div>

<?php global $margin_extra_footer; ?>
<?php $margin_extra_footer = "footer-petsitter"; ?>
<?php get_footer(); ?>

<script>
	(function(d, s){
	    $ = d.createElement(s), e = d.getElementsByTagName(s)[0];
	    $.async=!0;
	    $.setAttribute('charset','utf-8');
	    $.src='//www.google.com/recaptcha/api.js?hl=es';
	    $.type='text/javascript';
	    e.parentNode.insertBefore($, e)
	})(document, 'script');
</script>