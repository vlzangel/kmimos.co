<?php 

	/*
		Template Name: vlz detalle Solicitud de conocer a cuidador
	*/

	get_header();

		$valores = explode("/", $_SERVER['REDIRECT_URL']);
		$orden = $valores[ count($valores)-2 ];



global $wpdb;

$sql = "
		SELECT
			p.ID as Nro_solicitud,
			DATE_FORMAT(p.post_date,'%d-%m-%Y') as Fecha_solicitud,
			p.post_status as Estatus,

			DATE_FORMAT(fd.meta_value,'%d-%m-%Y') as Servicio_desde,
			DATE_FORMAT(fh.meta_value,'%d-%m-%Y') as Servicio_hasta,
			d.meta_value as Donde,
			w.meta_value as Cuando,
			t.meta_value as Hora,

			cl.meta_value as Cliente_id,
			cu.post_author as Cuidador_id
		FROM wp_postmeta as m
			LEFT JOIN wp_posts as p  ON p.ID = m.post_id
			LEFT JOIN wp_postmeta as fd ON p.ID = fd.post_id and fd.meta_key = 'service_start'
			LEFT JOIN wp_postmeta as fh ON p.ID = fh.post_id and fh.meta_key = 'service_end'
			LEFT JOIN wp_postmeta as d  ON p.ID = d.post_id  and d.meta_key  = 'meeting_where'
			LEFT JOIN wp_postmeta as t  ON p.ID = t.post_id  and t.meta_key  = 'meeting_time'
			LEFT JOIN wp_postmeta as w  ON p.ID = w.post_id  and w.meta_key  = 'meeting_when'

			LEFT JOIN wp_postmeta as cl ON p.ID = cl.post_id and cl.meta_key = 'requester_user'
			LEFT JOIN wp_postmeta as pc ON p.ID = pc.post_id and pc.meta_key = 'requested_petsitter'
			LEFT JOIN wp_posts as cu ON cu.ID = pc.meta_value
		WHERE
			m.meta_key = 'request_status'
			AND
			p.ID = '$orden'
		ORDER BY DATE_FORMAT(p.post_date,'%d-%m-%Y') DESC
		;
	";

$result = $wpdb->get_results($sql);

?>

		<section role="main">
			<div class="pf-container clearfix">
				<div class="pf-row clearfix">
					<div style="padding: 20px 0px">


						<section>
							<div class="vlz_titulos_superior">
								<a href="<?php echo get_home_url()."/perfil-usuario/?ua=caregiver"; ?>" style="color: #00d2b7; border: solid 1px; padding: 3px 10px; margin: 0px; display: inline-block;">
									Volver
								</a> - Detalles de la Solicitud - <?php echo $orden; ?>
							</div>
						</section>

						<section>

								<?php
									if(count($result) > 0){
										foreach($result as $solicitud){


										$cuidador_meta = get_user_meta($solicitud->Cuidador_id);
										$cuidador_data = get_userdata($solicitud->Cuidador_id);

										$cliente_meta = get_user_meta($solicitud->Cliente_id);
										$cliente_data = get_userdata($solicitud->Cliente_id);
										//var_dump($cuidador_meta);
										//var_dump($cuidador_data);

								?>

											<div style="padding:10px 0; ">
												<strong># Solicitud</strong>:
												<?php echo $solicitud->Nro_solicitud; ?>
											</div>
											<div style="padding:10px;">
												<h3>Detalle de la Solicitud</h3>
												<div>
													<strong>Fecha</strong>:
													<?php echo $solicitud->Fecha_solicitud; ?>
												</div>
												<div>
													<strong>Desde</strong>:
													<?php echo $solicitud->Servicio_desde; ?>
												</div>
												<div>
													<strong>Hasta</strong>:
													<?php echo $solicitud->Servicio_hasta; ?>
												</div>
											</div>

											<div style="padding:10px;">
												<div>
													<strong>Lugar</strong>:
													<?php echo utf8_encode($solicitud->Donde); ?>
												</div>
												<div>
													<strong>Cuando</strong>:
													<?php echo $solicitud->Cuando.' '.$solicitud->Hora; ?>
												</div>
											</div>

											<!-- nombre de la(s) mascota -->
											<!-- tamaño -->
											<div style="padding:10px;">
												<h3>Detalle del Cliente</h3>
												<div>
													<strong>Nombre del cliente</strong>:
													<?php echo $cliente_meta['first_name'][0].' '. $cliente_meta['last_name'][0]; ?>
												</div>
												<div>
													<strong>Teléfono del cliente</strong>:
													<?php echo $cliente_meta['user_phone'][0];?>
												</div>
												<div>
													<strong>Correo del cliente</strong>:
													<?php echo $cliente_data->user_email;?>
												</div>
											</div>

											<div style="padding:10px;">
												<h3>Detalle del Cuidador</h3>
												<div>
													<strong>Nombre del cuidador</strong>:
													<?php echo $cuidador_meta['first_name'][0].' '. $cuidador_meta['last_name'][0]; ?>
												</div>
												<div>
													<strong>Teléfono del cuidador</strong>:
													<?php echo $cuidador_meta['user_phone'][0];?>
												</div>
												<div>
													<strong>Correo del cuidador</strong>:
													<?php echo $cuidador_data->user_email;?>
												</div>
											</div>

											<div style="padding:10px;">
												<div>
													<strong>Estatus</strong>:
													<?php
														if($solicitud->Estatus=='draft'){
															echo 'Cancelado';

														}else if($solicitud->Estatus=='publish'){
															echo 'Confirmado';

														}else{
															echo 'Pendiente';
															//echo $solicitud->Estatus;
														}
													?>
												</div>
											</div>

								<?php
										}
									}
								?>
								</tbody>
							</table>
						</section>

					</div>
				</div>
			</div>
		</section>

<?php
	get_footer(); 
?>