<?php
    $orden = vlz_get_page();
	$sql = "
		SELECT
			p.ID as Nro_solicitud,
			DATE_FORMAT(p.post_date,'%d/%m/%Y') as Fecha_solicitud,
			p.post_status as Estatus,

			fd.meta_value as Servicio_desde,
			fh.meta_value as Servicio_hasta,
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

	$CONTENIDO .= '
		<section role="main" class="left">
			<div class="pf-container clearfix">
				<div class="pf-row clearfix">
					<div style="padding: 20px 0px">
						<section class="left">
							<div class="vlz_titulos_superior">
								<a href="'.get_home_url().'/perfil-usuario/solicitudes/" class="volver">
									Volver
								</a> - Detalles de la Solicitud - '.$orden.'
							</div>
						</section>
						<section class="left">';

							if(count($result) > 0){
								foreach($result as $solicitud){

									$cuidador_meta = get_user_meta($solicitud->Cuidador_id);
									$cuidador_data = get_userdata($solicitud->Cuidador_id);

									$cliente_meta = get_user_meta($solicitud->Cliente_id);
									$cliente_data = get_userdata($solicitud->Cliente_id);

									$CONTENIDO .= '
										<div style="padding:10px 0; ">
											<strong># Solicitud</strong>:
											'.$solicitud->Nro_solicitud.'
										</div>
										<div style="padding:10px;">
											<h3>Detalle de la Solicitud</h3>
											<div>
												<strong>Fecha</strong>:
												'.$solicitud->Fecha_solicitud.'
											</div>
											<div>
												<strong>Desde</strong>:
												'.$solicitud->Servicio_desde.'
											</div>
											<div>
												<strong>Hasta</strong>:
												'.$solicitud->Servicio_hasta.'
											</div>
										</div>
										<div style="padding:10px;">
											<div>
												<strong>Lugar</strong>:
												'.utf8_encode($solicitud->Donde).'
											</div>
											<div>
												<strong>Cuando</strong>:
												'.$solicitud->Cuando.' '.$solicitud->Hora.'
											</div>
										</div>
										<div style="padding:10px;">
											<h3>Detalle del Cliente</h3>
											<div>
												<strong>Nombre del cliente</strong>:
												'.$cliente_meta['first_name'][0].' '. $cliente_meta['last_name'][0].'
											</div>
											<div>
												<strong>Teléfono del cliente</strong>:
												'.$cliente_meta['user_phone'][0].'
											</div>
											<div>
												<strong>Correo del cliente</strong>:
												'.$cliente_data->user_email.'
											</div>
										</div>
										<div style="padding:10px;">
											<h3>Detalle del Cuidador</h3>
											<div>
												<strong>Nombre del cuidador</strong>:
												'.$cuidador_meta['first_name'][0].' '. $cuidador_meta['last_name'][0].'
											</div>
											<div>
												<strong>Teléfono del cuidador</strong>:
												'.$cuidador_meta['user_phone'][0].'
											</div>
											<div>
												<strong>Correo del cuidador</strong>:
												'.$cuidador_data->user_email.'
											</div>
										</div>
										<div style="padding:10px;">
											<div>
												<strong>Estatus</strong>: ';
													if($solicitud->Estatus == 'draft'){
														$CONTENIDO .= 'Cancelado';
													}else if($solicitud->Estatus=='publish'){
														$CONTENIDO .= 'Confirmado';
													}else{
														$CONTENIDO .= 'Pendiente';
													} $CONTENIDO .= '
											</div>
										</div>';
									}
								} $CONTENIDO .= '
								</tbody>
							</table>
						</section>

						<section class="left">
							<div class="vlz_titulos_superior bottom">
								<a href="'.get_home_url().'/perfil-usuario/solicitudes/" class="volver">
									Volver
								</a> - Detalles de la Solicitud - '.$orden.'
							</div>
						</section>
					</div>
				</div>
			</div>
		</section>';
?>