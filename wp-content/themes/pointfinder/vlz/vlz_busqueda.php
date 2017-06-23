<?php 
/*
	Template Name: vlz busqueda
*/

/* Funciones */
	
	session_start();



	if( count($_POST) > 0 ){
		$_SESSION['busqueda'] = serialize($_POST);		
		$_SESSION['busqueda_token'] = 1;		
		unset($_SESSION['busqueda_resultado']);

		$home = get_home_url();
		header("location: {$home}/busqueda/");
	}

	if( isset($_SESSION['busqueda'])){
		$_POST = unserialize($_SESSION['busqueda']);
	}

	get_header();

	$valores = explode("/", $_SERVER['REDIRECT_URL']);
	$page = $valores[ count($valores)-2 ];

	$xpagina = $page+0;
	$pagina = $page+0;
	$item_by_page = 15;
	if( $pagina < 0 ){ $pagina = 0; }
	$pagina_row_fin = $pagina*$item_by_page;

	include("vlz_style.php");

	echo "<style> .pflist-item .pfHoverButtonStyle > a{line-height: 1px;} </style>";
	
	if(function_exists('PFGetHeaderBar')){PFGetHeaderBar();} ?>

		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('a#boton-izquierda').click(function(e){
					e.preventDefault();
					enlace  = jQuery(this).attr('href');

					if( enlace == '#mapa' ){
						jQuery(this).attr('href', "#lista");
						jQuery(this).html('<span id="icono-izquierda" class="dashicons dashicons-id"></span><div id="titulo-izquierda">Lista</div>');
					}else{
						jQuery(this).attr('href', "#mapa");
						jQuery(this).html('<span id="icono-izquierda" class="dashicons dashicons-location-alt"></span><div id="titulo-izquierda">Mapa</div>');
					}

					jQuery('html, body').animate({
						scrollTop: parseInt(jQuery(enlace).offset().top)-85
					}, 500);
				});

				jQuery('a#boton-derecha').click(function(e){
					e.preventDefault();
					enlace  = jQuery(this).attr('href');

					if( enlace == '#mapa' ){
						jQuery(this).attr('href', "#filtros");
						jQuery(this).html('<span id="icono-derecha" class="dashicons dashicons-admin-settings"></span><div id="titulo-derecha">Filtros</div>');
					}else{
						jQuery(this).attr('href', "#mapa");
						jQuery(this).html('<span id="icono-derecha" class="dashicons dashicons-location-alt"></span><div id="titulo-derecha">Mapa</div>');
					}

					jQuery('html, body').animate({
						scrollTop: parseInt(jQuery(enlace).offset().top)-85
					}, 500);
				});
			});
		</script>

		<div id="sticky">
		    <a id="boton-izquierda" href="#lista">
		    	<span id="icono-izquierda" class="dashicons dashicons-id"></span><div id="titulo-izquierda">Lista</div>
		    </a>
		    <div id="contenido-centro">
		    	<span id="icono-centro" class="vc_icon_element-icon fa fa-map-marker"></span>
		    	<div id="ubicacion-actual">M&eacute;xico</div>
		    </div>
		    <a id="boton-derecha" href="#filtros">
		    	<span id="icono-derecha" class="dashicons dashicons-admin-settings"></span><div id="titulo-derecha">Filtros</div>
		    </a>
		</div>

		<div class="vlz_contenedor_mapa">
			<div id="mapa" ></div>
			<div class="vlz_bloquear_map" onclick='jQuery(".vlz_bloquear_map").css("display", "none");'><p style="color: #fff; z-index: 1000; margin-top: 40%;text-align: center;"></p></div>
		</div>

		<script type="text/javascript">
			jQuery("#mapa").mouseleave(function(){
			    jQuery(".vlz_bloquear_map").css("display", "block");
			});
			if (jQuery(window).width() < 550) {
			jQuery('div.vlz_bloquear_map>p').text('Toca la pantalla para ver en el mapa');
		}
		</script>

	<?php


	global $wpdb;

	$depuracion = array();

	// Cargar valores de session
	$rows = [];
	$r2 = 0;

	$sql = vlz_sql_busqueda($_POST, $pagina_row_fin);
	$rows = $wpdb->get_results($sql);
	$r2 = $wpdb->get_results('SELECT FOUND_ROWS() AS cantidad');

	$depuracion[] = $sql;
	$depuracion[] = $_POST;

	// $r = array_slice($rows, $pagina_row_ini, $item_by_page);
	$r = $rows;

	//IC: Se guarda el historico de busqueda en session para la paginacion
	// $_SESSION['cuidadores_search'] = $r; 

	$depuracion[] = [
		"wp-page"=>$page,
		"pag"=>$pagina,
		"item"=>$item_by_page,
		"ini"=>$pagina_row_ini,
		"fin"=>$pagina_row_fin,
		"post" => $_POST,
	];


	$total_registros = ($r2[0]->cantidad+0);
	$depuracion[] = $total_registros;

	$id_user = get_current_user_id();
	if( $id_user != 0 ){
		$rf = $wpdb->get_row("SELECT * FROM wp_usermeta WHERE ( user_id = $id_user AND meta_key = 'user_favorites'");
		preg_match_all('#"(.*?)"#i', $rf->favoritos, $favoritos);
		if( isset($favoritos[1]) ){
			$favoritos = $favoritos[1];
		}
	}

	$coordenadas_all_2 = array();
	// Cargar todos los metas de la busqueda para Puntos en el MAPA
	foreach ($rows as $key => $cuidador) {
		$ID = $cuidador->id;

		$data2 = get_post($cuidador->id_post);
		$cuidador->nombre = explode(" ", $cuidador->nombre);
		$cuidador->nombre = $cuidador->nombre[0];
		$url = get_home_url() . "/petsitters/" . $data2->post_name;		
		
		$coordenadas_all_2[] = array(
			"ID" 		=> $cuidador->id,
			"USER" 		=> $cuidador->user_id,
			"lat" 		=> $cuidador->latitud,
			"lng" 		=> $cuidador->longitud,
			"nombre" 	=> $cuidador->nombre,
			"url" 		=> $url,
			"portada" 	=> $cuidador->portada
		);
	}

	$top_destacados = "";

	if( $_POST['estados'] != ''){

		$estado_des = $wpdb->get_var("SELECT name FROM states WHERE id = ".$_POST['estados']);

		/*if( $_POST['municipios'] != ''){
			$top_municipio = " AND municipio = '{$_POST['municipios']}'";
			$municipio_des = " - ".$wpdb->get_var("SELECT name FROM locations WHERE id = ".$_POST['municipios']);
		}*/

		$sql_top = "SELECT * FROM destacados WHERE estado = '{$_POST['estados']}' {$top_municipio}";

		$tops = $wpdb->get_results($sql_top);
		$top_destacados = ""; $cont = 0;
		foreach ($tops as $value) {
			$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$value->cuidador}");
			$data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
			$nombre = $data->nom;
			$img_url = kmimos_get_foto_cuidador($value->cuidador);
			$url = get_home_url() . "/petsitters/" . $data->url;
			$top_destacados .= "
				<a class='vlz_destacados_contenedor' href='{$url}'>
					<div class='vlz_destacados_contenedor_interno'>
						<div class='vlz_destacados_img'>
							<div class='vlz_descado_img_fondo' style='background-image: url({$img_url});'></div>
							<div class='vlz_descado_img_normal' style='background-image: url({$img_url});'></div>
							<div class='vlz_destacados_precio'><sub style='bottom: 0px;'>Hospedaje desde</sub><br>MXN $".($cuidador->hospedaje_desde*1.2)."</div>
						</div>
						<div class='vlz_destacados_data' >
							<div class='vlz_destacados_nombre'>{$nombre}</div>
							<div class='vlz_destacados_adicionales'>".vlz_servicios($cuidador->adicionales)."</div>
						</div>
					</div>
				</a>
			";
			$cont++;
		}
		if( $cont > 0 ){
			if( $top_destacados != '' ){
				$top_destacados = $top_destacados."</div>";	
			}
			$top_destacados = utf8_decode( '<div class="pfwidgettitle"> <div class="widgetheader">Destacados Kmimos en: '.$estado_des.' '.$municipio_des.'</div> </div> <div class="row" style="margin: 10px auto 20px;">').$top_destacados;
		}
		
	}

	// $depuracion[] = $favoritos;

	$msg = "";
	if( $_POST['estados'] != "" || $_POST['municipios'] != "" ){
			
	}

	echo '<div id="lista" class="pf-blogpage-spacing pfb-top"></div>';
	echo '<section role="main" class="blog-full-width"> 
			<div class="pf-container"> 
				<div class="pf-row"> 
					<div class="col-lg-9">

					'.$top_destacados.'

						<div class="pfwidgettitle">
							<div class="widgetheader">Listado: '.$total_registros.' cuidador(es)</div>
						</div>

							<ul class="pfitemlists-content-elements pf3col" data-layout-mode="fitRows" style="position: relative; margin: 20px -15px 20px 0px;">
								<li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item" style="position: absolute; left: 0px; top: 0px;">
									<div class="pflist-item" style="background-color:#ffffff;"></div>
								</li>';

									if( ($total_registros+0) > 0 ){

						        		foreach ($r as $key => $cuidador) {
						        			$ID = $cuidador->id;
						        			
						        			include("vlz_plantilla_listado.php");
										}
										echo "	<script>
													jQuery(document).resize(function(){
														if (jQuery(window).width() < 550) {
															console.log('cambio de pantalla')
															jQuery('.vlz_contenedor_mapa').removeClass('ocultarMapa');
														}

													});
													jQuery(document).ready(function(){
														if (jQuery(window).width() < 550) {
															console.log('cambio de pantalla')
															jQuery('.vlz_contenedor_mapa').removeClass('ocultarMapa');
														}

													});
												</script>";

									}else{
										echo "<li align='justify'><h2 style='padding-right: 20px!important;'>No tenemos resultados para esta búsqueda, si quieres intentarlo de nuevo pícale <a  style='color: #00b69d; font-weight: 600;' href='".get_home_url()."/#jj-landing-page'>aquí,</a> o aplica otro filtro de búsqueda.</h2></li>";
										echo "	<script>
													jQuery(document).resize(function(){
														if (jQuery(window).width() < 550) {
															console.log('cambio de pantalla')
															jQuery('.vlz_contenedor_mapa').addClass('ocultarMapa');
														}

													});
													jQuery(document).ready(function(){
														if (jQuery(window).width() < 550) {
															console.log('cambio de pantalla')
															jQuery('.vlz_contenedor_mapa').addClass('ocultarMapa');
														}

													});
												</script>";
									}
							
							echo '</ul>';

							include("vlz_style.php"); ?>

							<div class="vlz_nav_cont">

								<div class="vlz_nav_cont_interno">

				 					<?php
										$t = $total_registros+0;
										$h = 15;
										if($t > $h){
											$ps = ceil($t/$h);
											for( $i=0; $i<$ps; $i++){
												$active = ( $xpagina == $i ) ? "class='vlz_activa'" : "";
												echo "<a href='".get_home_url()."/busqueda/".($i)."' ".$active.">".($i+1)."</a>";
											}
										}
										$w = 40*$ps;
										echo "
											<style>
												.vlz_nav_cont_interno{
													width: {$w}px;
												}
											</style>
										";
									?>

								</div>

							</div> <?php

						foreach ($depuracion as $key => $value) {
							echo "<pre style='display: none;'>";
								print_r($value);
							echo "</pre>";
						}
				
					echo '</div>';

					echo '<div class="col-lg-3" style="position: relative;"> <div class="pfwidgettitle"><div class="widgetheader">Filtrar Cuidadores</div></div>';

						include("vlz_formulario.php");
						include("vlz_sql_marcadores.php");
						include("vlz_scripts.php");

					echo '</div> 
				</div> 
			</div> 
		</section>';
		echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';

	get_footer();
?>