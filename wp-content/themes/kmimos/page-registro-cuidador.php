<?php 
    /*
        Template Name: Registro del cuidador
    */

	wp_enqueue_script('registro_cuidadores', getTema()."/js/registro_cuidadores.js", array("jquery"), '1.0.0');

	$config_link_registro = 'href="#" role="button" data-target="#popup-registro-cuidador1"' ;

	wp_enqueue_style('registro_cuidador', getTema()."/css/registro_cuidador.css", array("kmimos_style"), '1.0.0');
	wp_enqueue_style('registro_cuidador_responsive', getTema()."/css/responsive/registro_cuidador_responsive.css", array("kmimos_style"), '1.0.0');

    get_header(); ?>

	<!-- SECCIÓN BG CUIDADOR-->
		<div class="km-hero-bg" style="background-image:url(<?php echo getTema(); ?>/images/new/km-cuidador/km-hero-cuidador.jpg);">
			<div class="overlay"></div>
			<!-- SECCIÓN TEXTO CUIDADOR -->
			<div class="km-titular-cuidador">
				<div class="container">
					<h2>Kmimos necesita doglovers como tú</h2>
					<p>Cada mascota llega como un huésped y consigue a un nuevo amigo. Convierte tu hobbie en dinero extra, con kmimos te ayudamos a alcanzarlo.</p>
					<!-- <a href="#popup-registro-cuidador1" class="km-btn" role="button" data-toggle="modal">EMPIEZA A CUIDAR</a> -->
					<a class="km-btn" <?php echo $config_link_registro; ?> >EMPIEZA A CUIDAR</a>
				</div>
			</div>
			<!-- FIN SECCIÓN TEXTO CUIDADOR -->
		</div>
		<!-- FIN SECCIÓN BG CUIDADOR-->
		<!-- SECCIÓN BENEFICIOS CUIDADOR -->
		<section class="km-beneficios-cuidador">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-8">
						<div><img src="<?php echo getTema(); ?>/images/new/km-certificado.svg"></div>
						<h4>GANA DINERO CON TU HOBBIE</h4>
						<p>En Kmimos siempre podrás colocar el precio que a ti mejor se te acomode, no te obligamos a fijar un precio, sin embargo, quisiéramos este rango de precios que está creada en base a las tendencias de mercado actuales.</p>
						<ol>
							<li>Tamaño pequeño: 120 pesos por noche.</li>
							<li>Tamaño mediano: 180 pesos por noche.</li>
							<li>Tamaño grande: 220 pesos por noche.</li>
							<li>Tamaño gigante: 250 pesos por noche.</li>
						</ol>
					</div>
					<div class="col-xs-12 col-sm-4">
						<div><img src="<?php echo getTema(); ?>/images/new/km-certificado.svg"></div>
						<h4>ELIGE TUS PROPIOS HORARIOS</h4>
						<p>Cuida a los perros que quieras y cuando tú quieras, es 100% flexible.</p>
					</div>
				</div>
			</div>
		</section>
		<!-- FIN SECCIÓN BENEFICIOS CUIDADOR -->
		
		<!-- SECCIÓN CÓMO SOY CUIDADOR -->
		<section class="km-comosoycuidador">
			<div class="container">
				<h4>¿CÓMO SOY CUIDADOR?</h4>
				<div class="row">
					<div class="col-xs-12 col-sm-4">
						<p>Regístrate con tus datos.</p>
					</div>
					<div class="col-xs-12 col-sm-4">
						<p>Una persona del equipo de Kmimos te visitará para una entrevista personal.</p>
					</div>
					<div class="col-xs-12 col-sm-4">
						<p>Gana dinero con tu hobbie y en tus propios horarios.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<!-- <a href="#popup-registro-cuidador1" class="km-btn-borderw" role="button" data-toggle="modal">EMPIEZA A CUIDAR</a> -->

						<a class="km-btn-borderw" <?php echo $config_link_registro; ?>>EMPIEZA A CUIDAR</a>
					</div>
				</div>
			</div>
		</section>
		<!-- SECCIÓN CÓMO SOY CUIDADOR -->
		
		<?php // Modal Registro Cuidador ?>
		<?php include_once( 'partes/cuidador/registro.php' ); ?>
		
		<!-- POPUPS TIPS  -->
		<div id="km-registro-tip1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<div class="km-registro-tip1">
						SDSD
					</div>
				</div>
			</div>
		</div>
		
<?php get_footer(); ?>