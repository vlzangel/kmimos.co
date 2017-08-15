<?php
	echo "
		<style>
			.vlz_tabla{
				width: 100%;
			    margin-bottom: 40px;
			}
			.vlz_tabla th{
			    background: #59c9a8!important;
				color: #FFF;
				border-top: 1px solid #888;
				border-right: 1px solid #888;
				text-align: center;
			}
			.vlz_tabla td{
				border-top: 1px solid #888;
				border-right: 1px solid #888;
			}
			h1{
				line-height: 0px;
			}
			hr{
			    border-top: 2px solid #eee;
			}
			a{
			    color: #005796;
				font-weight: 600;
			    opacity: 0.8;
			}
			.vlz_h1{
				background: #59c9a8;
			    display: inline-block;
			    padding: 5px 50px 5px 5px;
			    line-height: 1 !important;
			    margin: 0px;
			    border: solid 1px #888;
			    border-bottom: 0;
			    border-radius: 0px 5px 0px 0px;
			    color: #FFF;
			}
			a:hover{
			    opacity: 1;
			}
		</style>
	";

	global $wpdb;

	$sql = "SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_author = {$user_id} AND post_status NOT LIKE '%cart%'";

	$reservas = $wpdb->get_results($sql);


	$completadas = array();
	$confirmadas = array();
	$canceladas  = array();
	$pendientes = array();

	if( count($reservas) > 0 ){

		$estatus = array(
			"paid" => "Pagada",
			"unpaid" => "No Pagada",
			"cancelled" => "Cancelada",
			"confirmed" => "Confirmada"
		);

		foreach ($reservas as $key => $reserva) {

			$id_reserva = $reserva->ID;

			$metas_orden   = get_post_meta($id_reserva+1);
			$metas_reserva = get_post_meta($id_reserva);

			$ini_str = $metas_reserva['_booking_start'][0];
			$fin_str = $metas_reserva['_booking_end'][0];

			$ini = substr($ini_str, 6, 2)."/".substr($ini_str, 4, 2)."/".substr($ini_str, 0, 4);
			$fin = substr($fin_str, 6, 2)."/".substr($fin_str, 4, 2)."/".substr($fin_str, 0, 4);

			$servicio = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$metas_reserva['_booking_product_id'][0]);

			$datos = $wpdb->get_results("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = ".$metas_reserva['_booking_order_item_id'][0]);

			
			$sta = $reserva->post_status; $no_tomada = true;

			if( $metas_orden['_payment_method'][0] == 'openpay_stores' &&  $sta == "unpaid" ){
				$pdf = "<a class='btn_pagar'
						href='{$metas_orden['_openpay_pdf'][0]}' 
						target='_blank' 
						title='Ver código para pagar en tienda por conveniencia'
						style='   
							font-weight: 600;
						    background: #54c8a7;
						    padding: 3px 5px;
						    border-radius: 2px;
						    color: #FFF;
						'
					>Pagar</a>";
			}else{
				$pdf = "";
			}

			if( $sta == "confirmed" ){

				$completadas[] = array(
					"ID" 		=> $reserva->ID,
					"URL" 		=> $servicio->post_name,
					"SERVICIO" 	=> $servicio->post_title,
					"INICIO" 	=> $ini,
					"FIN" 		=> $fin
				);
				$no_tomada = false;
			}

			if( $sta == "cancelled" ){

				$canceladas[] = array(
					"ID" 		=> $reserva->ID,
					"URL" 		=> $servicio->post_name,
					"SERVICIO" 	=> $servicio->post_title,
					"INICIO" 	=> $ini,
					"FIN" 		=> $fin
				);
				$no_tomada = false;
			}

			if( $no_tomada ){

				$pendientes[] = array(
					"ID" 		=> $reserva->ID,
					"ORDEN" 	=> $reserva->post_parent,
					"URL" 		=> $servicio->post_name,
					"SERVICIO" 	=> $servicio->post_title,
					"INICIO" 	=> $ini,
					"FIN" 		=> $fin,
					"PDF" 		=> $pdf,
					"STA" 		=> $estatus[$reserva->post_status]
				);

			}

		}

		if( count($pendientes) > 0 ){

			echo "<h1 class='vlz_h1 jj_h1'>Reservas Pendientes</h1>";

			$resultados = "
				<table class='vlz_tabla jj_tabla table table-striped table-responsive'>
					<tr>
						<th>RESERVA</th>
						<th style='text-align: left;'>SERVICIO</th>
						<th class='td_responsive'>INICIO</th>
						<th class='td_responsive'>FIN</th>
						<th>ACCIONES</th>
					</tr>";

				foreach ($pendientes as $key => $value) {
					$resultados .= "
						<tr>
							<td style='width: 85px; text-align: center;'>{$value['ID']}</td>
							<td><a href='".get_home_url()."/producto/{$value['URL']}' target='_blank' >{$value['SERVICIO']}</a></td>
							<td class='td_responsive' style='text-align: center; width: 85px;'>{$value['INICIO']}</td>
							<td class='td_responsive' style='text-align: center; width: 85px;'>{$value['FIN']}</td>
							<td style='text-align: right; width: 166px;'>
								{$value['PDF']}
								<a class='btn_ver'
									href='".get_home_url()."/ver/".($value['ORDEN'])."/'
									style='   
										font-weight: 600;
									    background: #54c8a7;
									    padding: 3px 5px;
									    border-radius: 2px;
									    color: #FFF;
									'
								>Ver</a>
								<!-- <a class='btn_cancelar'
									href='".get_home_url()."/wp-content/plugins/kmimos/orden.php?o={$value['ORDEN']}&s=0&r={$value['ID']}'
									style='   
										font-weight: 600;
									    background: #e80000;
									    padding: 3px 5px;
									    border-radius: 2px;
									    color: #FFF;
									'
								>Cancelar</a> -->
							</td>
						</tr>
					";
				}
			$resultados .= "</table>";

			echo $resultados;
		}

		if( count($completadas) > 0 ){
			echo "<h1 class='vlz_h1 jj_h1'>Reservas Completadas</h1>";

			$resultados = "
				<table class='vlz_tabla jj_tabla table table-striped'>
					<tr>
						<th>RESERVA</th>
						<th style='text-align: left;'>SERVICIO</th>
						<th class='td_responsive'>INICIO</th>
						<th class='td_responsive'>FIN</th>
						<th class='td_responsive'>ACCIONES</th>
					</tr>";

				foreach ($completadas as $key => $value) {
					$resultados .= "
						<tr>
							<td style='width: 85px; center;'>{$value['ID']}</td>
							<td><a href='".get_home_url()."/producto/{$value['URL']}' target='_blank' >{$value['SERVICIO']}</a></td>
							<td class='td_responsive' style='text-align: center; width: 85px;'>{$value['INICIO']}</td>
							<td class='td_responsive' style='text-align: center; width: 85px;'>{$value['FIN']}</td>
							<td class='td_responsive' style='text-align: center; width: 85px;'>
								<a class='btn_ver'
									href='".get_home_url()."/valorar-cuidador/?id={$value['ID']}'
									style='   
										font-weight: 600;
									    background: #54c8a7;
									    padding: 3px 5px;
									    border-radius: 2px;
									    color: #FFF;
									'
								>
									Valorar
								</a>
							</td>
						</tr>
					";
				}
			$resultados .= "</table>";

			echo $resultados;
		}

		if( count($canceladas) > 0 ){
			echo "<h1 class='vlz_h1 jj_h1'>Reservas Canceladas</h1>";

			$resultados = "
				<table class='vlz_tabla jj_tabla'>
					<tr>
						<th>RESERVA</th>
						<th style='text-align: left;'>SERVICIO</th>
						<th class='td_responsive'>INICIO</th>
						<th class='td_responsive'>FIN</th>
					</tr>";

				foreach ($canceladas as $key => $value) {
					$resultados .= "
						<tr>
							<td style='width: 85px; center;'>{$value['ID']}</td>
							<td><a href='".get_home_url()."/producto/{$value['URL']}' target='_blank' >{$value['SERVICIO']}</a></td>
							<td class='td_responsive' style='text-align: center; width: 85px;'>{$value['INICIO']}</td>
							<td class='td_responsive' style='text-align: center; width: 85px;'>{$value['FIN']}</td>
						</tr>
					";
				}
			$resultados .= "</table>";

			echo $resultados;
		}
	
	}else{
		echo "<h1 style='line-height: normal;'>Usted aún no tiene reservas.</h1><hr>";
	}
?>