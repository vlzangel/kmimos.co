<?php 
/*
	Template Name: vlz busqueda
*/

/* Funciones */
	
	session_start();


	if( count($_POST) > 0 ){
		$_SESSION['busqueda'] = serialize($_POST);
		$home = get_home_url();
		header("location: {$home}/busqueda/");
	}

	if( isset($_SESSION['busqueda'])){
		$_POST = unserialize($_SESSION['busqueda']);
	}

	$pagina = $_GET['pagina']+0;
	
	if( $pagina < 0 ){
		$pagina = 0;
	}

	$xpagina = $pagina*15;

	include("vlz_style.php");

	get_header();
	
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
		    	<div id="ubicacion-actual">Colombia</div>
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

	$sql = vlz_sql_busqueda($_POST, $xpagina);
	$depuracion[] = $sql;
	$depuracion[] = $_POST;

	$r = $wpdb->get_results($sql);
	// $depuracion[] = $r;

	$r2 = $wpdb->get_results('SELECT FOUND_ROWS() AS cantidad');

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

	// $depuracion[] = $favoritos;

	$msg = "";
	if( $_POST['estados'] != "" || $_POST['municipios'] != "" ){
			
	}

	echo '<div id="lista" class="pf-blogpage-spacing pfb-top"></div>';
	echo '<section role="main" class="blog-full-width"> <div class="pf-container"> <div class="pf-row"> <div class="col-lg-9">

			<div class="pfwidgettitle"><div class="widgetheader">Listado: '.$total_registros.' cuidador(es)</div></div>

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
						echo "<li align='justify'><h2 style='padding-right: 20px!important;'>No tenemos resultados para esta búsqueda, si quieres intentarlo de nuevo dale click <a  style='color: #00b69d; font-weight: 600;' href='".get_home_url()."/#jj-landing-page'>aquí,</a> o aplica otro filtro de búsqueda.</h2></li>";
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
								if( $pagina == $i ){
									echo "<a href='?pagina={$i}' class='vlz_activa'>".($i+1)."</a>";
								}else{
									echo "<a href='?pagina={$i}'>".($i+1)."</a>";
								}
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

			</div>

			<?php

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

	echo '</div> </div> </div> </section>';
	echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';

	get_footer();
?>