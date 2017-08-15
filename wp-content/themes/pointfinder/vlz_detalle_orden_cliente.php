<?php 

	/*
		Template Name: vlz detalle orden cliente
	*/

	get_header();

		$orden = $page; ?>

		<section role="main">
			<div class="pf-container clearfix">
				<div class="pf-row clearfix">
					<div style="padding: 20px 0px">

						<?php 
							echo kmimos_style(
								array(
									"limpiar_tablas",
									"tablas",
									"celdas"
								)
							); 

							include("./wp-content/themes/pointfinder/vlz/admin/ver_orden.php");
						?>

						<section>
							<div class="vlz_titulos_superior">
								<a href="<?php echo get_home_url()."/perfil-usuario/?ua=invoices"; ?>" style="color: #00d2b7; border: solid 1px; padding: 3px 10px; margin: 0px; display: inline-block; border-radius: 3px;">
									Volver
								</a> - Detalles de la reserva - <?php echo $orden; ?>
							</div>
						</section>

						<section>
							<div class="cell25">
								<!-- <div class="vlz_titulos_tablas">Detalles del cliente</div>
								<div class="vlz_contenido_tablas">
									<?php echo $detalles_cliente; ?>
								</div> -->

								<div class="vlz_titulos_tablas">Detalles del cuidador</div>
								<div class="vlz_contenido_tablas">
									<?php echo $detalles_cuidador; ?>
								</div>

								<div class="vlz_titulos_tablas">Detalles del servicio</div>
								<div class="vlz_contenido_tablas">
									<?php echo $detalles_servicio; ?>
								</div>
							</div>
							<div class="cell75">
								<div class="vlz_titulos_tablas">Detalles de las mascotas</div>
								<div class="vlz_contenido_tablas">
									<?php echo $detalles_mascotas; ?>
								</div>

								<div class="vlz_titulos_tablas">Detalles de facturación</div>
								<div class="vlz_contenido_tablas">
									<?php echo $detalles_factura; ?>
								</div>

							</div>
						</section>

					</div>
				</div>
			</div>
		</section> <?php

	get_footer(); 
?>