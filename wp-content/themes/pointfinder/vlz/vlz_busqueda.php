<?php
	/*
		Template Name: vlz busqueda
	*/

	//CG only unique use!!!!!!!!!
	update_additional_service();
	update_additional_service_postname();
	update_image_service();

	session_start();
	if( isset($_SESSION['busqueda'])){ $_POST = unserialize($_SESSION['busqueda']); }
	$home = get_home_url();
	$pagina = vlz_get_page();
	if( $_SESSION['resultado_busqueda'] ){
		$TR = count($_SESSION['resultado_busqueda'])+0;
	}else{
		$TR = 0;
	}

	$paginacion = vlz_get_paginacion($TR, $pagina);
	$resultados = $_SESSION['resultado_busqueda'];
	get_header();
	include("vlz_style.php"); 
	include("header_busqueda.php");
	global $wpdb;
	$depuracion = array();
	// $depuracion[] = $_POST;
	// $depuracion[] = $resultados;
	$favoritos = get_favoritos();
	
	$pines = unserialize($_SESSION['pines_array']);

	$top_destacados = get_destacados( $_POST['estados'] );
	$HTML .= '
		<div id="lista" class="pf-blogpage-spacing pfb-top"></div>
		<section role="main" class="blog-full-width"> 
			<div class="pf-container"> 
				<div class="pf-row"> 
					<div class="col-lg-9">
						'.$top_destacados.'
						<div class="pfwidgettitle">
							<div class="widgetheader">Listado: '.$TR.' cuidador(es)</div>
						</div>
						<ul class="pfitemlists-content-elements pf3col" data-layout-mode="fitRows" style="position: relative; margin: 20px -15px 20px 0px;">
							<li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item" style="position: absolute; left: 0px; top: 0px;">
								<div class="pflist-item" style="background-color:#ffffff;"></div>
							</li>';
							if( $TR > 0 ){
			        			include("vlz_plantilla_listado.php");
				        		for ($i=$paginacion["inicio"]; $i < $paginacion["fin"]; $i++) {
				        			$cuidador = $resultados[$i];
				        			$HTML .= get_ficha_cuidador($cuidador, $i, $favoritos);
								}
							}else{
								$HTML .= "<li align='justify'><h2 style='padding-right: 20px!important;'>No tenemos resultados para esta búsqueda, si quieres intentarlo de nuevo pícale <a  style='color: #00b69d; font-weight: 600;' href='".$home."/#jj-landing-page'>aquí,</a> o aplica otro filtro de búsqueda.</h2></li>";
							} $HTML .= '
						</ul>
						<div class="vlz_nav_cont">
							<div class="vlz_nav_cont_interno">'.$paginacion["html"].'</div>
						</div>
					</div>
					<div class="col-lg-3" style="position: relative;"> <div class="pfwidgettitle"><div class="widgetheader">Filtrar Cuidadores</div></div>';
						include("vlz_formulario.php");
						include("vlz_scripts.php");
						$pines = json_encode($pines);
    					$HTML .= $FORMULARIO.$SCRIPTS.'<script>var pines = eval(\''.$pines.'\');</script>
					</div> 
				</div> 
			</div> 
		</section>
		<div class="pf-blogpage-spacing pfb-bottom"></div>
		<script>
			jQuery( document ).ready(function() {

				if( jQuery("#pf-login-trigger-button").html() != undefined ){
					jQuery(".pf-favorites-link").on("click", function(e){
						jQuery.cookie("POST_LOGIN", jQuery(this).attr("id") );
					});
				}

			  	var POST_LOGIN = jQuery.cookie("POST_LOGIN");
				if( POST_LOGIN != undefined ){
					jQuery.removeCookie("POST_LOGIN");
					setTimeout(function(){
						jQuery("#"+POST_LOGIN).click();
					}, 5000);
				}
			});
		</script>';

		echo comprimir_styles($HTML);
		//echo ($HTML);

        //$depuracion[] = $resultados;

        foreach ($depuracion as $key => $value) {
			echo "<pre style='display: block;'>";
				print_r($value);
			echo "</pre>";
		}

	get_footer();
?>