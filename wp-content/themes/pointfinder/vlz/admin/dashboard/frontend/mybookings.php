<?php

global $wpdb;

$user = wp_get_current_user();
$user_id = $user->ID;

$bookings = explode(",",$params['list']);

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
			    line-height: 1;
			}
			hr{
			    border-top: 2px solid #eee;
			    margin: 10px 0px !important;
			}
			a{
			    color: #005796;
				font-weight: 600;
			    opacity: 0.8;
			}
			.vlz_h1{
			    display: inline-block;
			    padding: 5px 20px 5px 5px;
			    background: #59c9a8;
			    border-radius: 0px 3px 0px 0px;
			    border: solid 1px #888;
			    border-bottom: 0;
			    color: #FFF;
			    font-size: 17px;
			    font-weight: 600;
			}
			a:hover{
			    opacity: 1;
			}
		</style>
	";



$this->FieldOutput .= '<div class="lista_reservas" data-user="'.$user_id.'">';

$sql = "
			SELECT 
				posts.post_status AS status,
				producto.post_title AS titulo,
				posts.post_parent AS orden,
				posts.ID AS ID
			FROM 
				wp_posts AS posts
			LEFT JOIN wp_postmeta AS metas_reserva ON ( posts.ID = metas_reserva.post_id AND metas_reserva.meta_key='_booking_product_id' )
			LEFT JOIN wp_posts AS producto ON ( producto.ID = metas_reserva.meta_value )
			LEFT JOIN wp_posts AS orden ON ( orden.ID = posts.post_parent )
			WHERE 
				posts.post_type = 'wc_booking' AND
				(
					posts.post_status = 'cancelled' OR 
					posts.post_status = 'paid' OR 
					posts.post_status = 'confirmed' OR 
					posts.post_status = 'complete' OR 
					( posts.post_status = 'unpaid' AND orden.post_status = 'wc-partially-paid' )					
				) AND
				producto.post_author = '{$user_id}'
		";

$reservas = $wpdb->get_results($sql);

$completadas = "";
$confirmadas = "";
$canceladas  = "";
$pendientes  = "";

$contenedor = array();
if( count($reservas) > 0 ){
	foreach ($reservas as $reserva) {

		$temp = explode("-", $reserva->titulo);
		$tipo_servicio = trim( $temp[0] );

		$metas = get_post_meta($reserva->ID);

		$inicio = $metas['_booking_start'][0];
		$fin    = $metas['_booking_end'][0];

		$inicio = substr($inicio, 6, 2)."/".substr($inicio, 4, 2)."/".substr($inicio, 0, 4);
		$fin    = substr($fin, 6, 2)."/".substr($fin, 4, 2)."/".substr($fin, 0, 4);

		$user = $wpdb->get_row( "SELECT * FROM wp_users WHERE ID = {$metas['_booking_customer_id'][0]}" );
		$metas_user = get_user_meta($metas['_booking_customer_id'][0]);

		$nom = $metas_user['first_name'][0]." ".$metas_user['last_name'][0];

		$no_tomada = true;

		if( $reserva->status == "complete" ){
			$completadas .= "
					<tr>
						<td>".$nom."</td>
						<td style='width: 100px; font-weight: bold;'>".trim($tipo_servicio)."</td>
						<td class='td_responsive' style='text-align: center; width: 35px;'>".$inicio."</td>
						<td class='td_responsive' style='text-align: center; width: 35px;'>".$fin."</td>

						<td style='text-align: center; width: 35px;'>
							<a class='btn_ver'
								href='".get_home_url()."/detalle/".($reserva->orden)."/'
								style='   
									font-weight: 600;
								    background: #54c8a7;
								    padding: 3px 5px;
								    border-radius: 2px;
								    color: #FFF;
								'
							>Ver</a>
						</td>
					</tr>";
			$no_tomada = false;
		}

		if( $reserva->status == "cancelled" ){
			$canceladas .= "
					<tr>
						<td>".$nom."</td>
						<td style='width: 100px; font-weight: bold;'>".trim($tipo_servicio)."</td>
						<td class='td_responsive' style='text-align: center; width: 35px;'>".$inicio."</td>
						<td class='td_responsive' style='text-align: center; width: 35px;'>".$fin."</td>

						<td style='text-align: center; width: 35px;'>
							<a class='btn_ver'
								href='".get_home_url()."/detalle/".($reserva->orden)."/'
								style='   
									font-weight: 600;
								    background: #54c8a7;
								    padding: 3px 5px;
								    border-radius: 2px;
								    color: #FFF;
								'
							>Ver</a>
						</td>
					</tr>";
			$no_tomada = false;
		}

		if( $reserva->status == "confirmed" ){
			$confirmadas .= "
					<tr>
						<td>".$nom."</td>
						<td style='width: 100px; font-weight: bold;'>".trim($tipo_servicio)."</td>
						<td class='td_responsive' style='text-align: center; width: 35px;'>".$inicio."</td>
						<td class='td_responsive' style='text-align: center; width: 35px;'>".$fin."</td>

						<td style='text-align: center; width: 35px;'>
							<a class='btn_ver'
								href='".get_home_url()."/detalle/".($reserva->orden)."/'
								style='   
									font-weight: 600;
								    background: #54c8a7;
								    padding: 3px 5px;
								    border-radius: 2px;
								    color: #FFF;
								'
							>Ver</a>
						</td>
					</tr>";
			$no_tomada = false;
		}

		if( $no_tomada ){

			$pendientes .= "
						<tr>
							<td>".$nom."</td>
							<td style='width: 100px; font-weight: bold;'>".trim($tipo_servicio)."</td>
							<td class='td_responsive' style='text-align: center; width: 35px;'>".$inicio."</td>
							<td class='td_responsive' style='text-align: center; width: 35px;'>".$fin."</td>

							<td style='text-align: right; width: 194px;'>
								<a 
									href='".get_home_url()."/detalle/".($reserva->orden)."/'
									style='   
									    font-weight: 600;
									    background: #54c8a7;
									    padding: 3px 5px;
									    border-radius: 2px;
									    color: #FFF;
									    display: block;
									    margin: 2px 0px;
									    text-align: center;
									'
								>Ver</a>
								<a class='btn_ver'
									href='".get_home_url()."/wp-content/plugins/kmimos/order.php?o=".($reserva->orden)."&s=1'
									style='   
										font-weight: 600;
									    background: #54c8a7;
									    padding: 3px 5px;
									    border-radius: 2px;
									    color: #FFF;
									'
								>Confirmar</a>
								<a class='btn_cancelar'
									href='".get_home_url()."/wp-content/plugins/kmimos/order.php?o=".($reserva->orden)."&s=0'
									style='   
										font-weight: 600;
									    background: #e80000;
									    padding: 3px 5px;
									    border-radius: 2px;
									    color: #FFF;
									'
								>Cancelar</a>
							</td>
						</tr>";

		}
	}
	/*
        $txts = array(
            "Pendientes"  => $pendientes,
            "Confirmadas"  => $confirmadas,
            "Completadas" => $completadas,
            "Canceladas"  => $canceladas
        );

        foreach ($txts as $key => $valor) {
            if( $valor != "" ){
                $this->FieldOutput .= '<h1 class="vlz_h1">Reservas '.$key.'</h1>';
                $this->FieldOutput .= "
                    <table class='vlz_tabla table table-striped'>
                        <tr>
                            <th style='text-align: left;'>CLIENTE</th>
                            <th>SERVICIO</th>
                            <th class='td_responsive'>DESDE</th>
                            <th class='td_responsive'>HASTA</th>
                            <th style='text-align: rigth;'>ACCIONES</th>
                        </tr>
                        ".$valor."
                    </table>";
            }
        }
    */




	//NUEVO
	$booking_coming=array();
	$booking_coming['pending']=array();
	$booking_coming['pending']['title']='Reservas Pendientes';
	$booking_coming['pending']['th']=array();
	$booking_coming['pending']['tr']=array();

	$booking_coming['confirmed']=array();
	$booking_coming['confirmed']['title']='Reservas Confirmadas';
	$booking_coming['confirmed']['th']=array();
	$booking_coming['confirmed']['tr']=array();

	$booking_coming['complete']=array();
	$booking_coming['complete']['title']='Reservas Completadas';
	$booking_coming['complete']['th']=array();
	$booking_coming['complete']['tr']=array();

	$booking_coming['cancelled']=array();
	$booking_coming['cancelled']['title']='Reservas Canceladas';
	$booking_coming['cancelled']['th']=array();
	$booking_coming['cancelled']['tr']=array();

	foreach($reservas as $key => $reserva){
		//var_dump($reserva);

		$_metas_reserva=get_post_meta($reserva->ID);
		$_metas=get_post_meta($reserva->post_parent);
		//var_dump($_metas);

		//CLIENTE
		$_metas_user = get_user_meta($_metas_reserva['_booking_customer_id'][0]);
		$_cliente = $_metas_user['first_name'][0]." ".$_metas_user['last_name'][0];

		$pedido = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);
		//var_dump($pedido);


		//RESERVAS CONFIRMADAS
		if($reserva->status=='confirmed' && (strtotime($_metas_reserva['_booking_end'][0])>time())){

			$options='<a class="theme_btn" href="'.get_home_url().'/detalle/'.$reserva->orden.'">Ver</a>';
			//$options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$reserva->orden.'&s=0">Cancelar</a>';


			$booking_th=array();
			$booking_th[]=array('class'=>'','data'=>'RESERVA');
			$booking_th[]=array('class'=>'','data'=>'CLIENTE');
			$booking_th[]=array('class'=>'','data'=>'SERVICIO');
			$booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
			$booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
			$booking_th[]=array('class'=>'','data'=>'ACCIONES');
			$booking_coming['confirmed']['th']=$booking_th;

			$booking_td=array();
			$booking_td[]=array('class'=>'','data'=>$reserva->ID);
			$booking_td[]=array('class'=>'','data'=>$_cliente);
			$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
			$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
			$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
			$booking_td[]=array('class'=>'','data'=>$options);
			$booking_coming['confirmed']['tr'][]=$booking_td;


			//RESERVAS COMPLETADAS
		}else if($reserva->status=='complete' || ($reserva->status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])<=time())){

			$options='<a class="theme_btn" href="'.get_home_url().'/detalle/'.$reserva->orden.'">Ver</a>';

			$booking_th=array();
			$booking_th[]=array('class'=>'','data'=>'RESERVA');
			$booking_th[]=array('class'=>'','data'=>'CLIENTE');
			$booking_th[]=array('class'=>'','data'=>'SERVICIO');
			$booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
			$booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
			$booking_th[]=array('class'=>'','data'=>'ACCIONES');
			$booking_coming['complete']['th']=$booking_th;

			$booking_td=array();
			$booking_td[]=array('class'=>'','data'=>$reserva->ID);
			$booking_td[]=array('class'=>'','data'=>$_cliente);
			$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
			$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
			$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
			$booking_td[]=array('class'=>'','data'=>$options);
			$booking_coming['complete']['tr'][]=$booking_td;



			//RESERVAS CANCELADAS
		}else if(($reserva->status=='cancelled' || $reserva->post_status=='wc_cancelled') && $_metas['_show'][0]!='noshow'){

			$options='<a class="theme_btn" href="'.get_home_url().'/detalle/'.$reserva->orden.'">Ver</a>';

			$booking_th=array();
			$booking_th[]=array('class'=>'','data'=>'RESERVA');
			$booking_th[]=array('class'=>'','data'=>'CLIENTE');
			$booking_th[]=array('class'=>'','data'=>'SERVICIO');
			$booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
			$booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
			$booking_th[]=array('class'=>'','data'=>'ACCIONES');
			$booking_coming['cancelled']['th']=$booking_th;

			$booking_td=array();
			$booking_td[]=array('class'=>'','data'=>$reserva->ID);
			$booking_td[]=array('class'=>'','data'=>$_cliente);
			$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
			$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
			$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
			$booking_td[]=array('class'=>'','data'=>$options);
			$booking_coming['cancelled']['tr'][]=$booking_td;


			//RESERVAS PENDIENTES
		}else if($_metas['_show'][0]!='noshow'){

			$options='<a class="theme_btn" href="'.get_home_url().'/detalle/'.$reserva->orden.'">Ver</a>';
			$options.='<a class="theme_btn" href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$reserva->orden.'&s=1">Confirmar</a>';
			$options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$reserva->orden.'&s=0">Cancelar</a>';
			$options=build_select(
				array(
					array(
						'text'=>'Ver',
						'value'=>get_home_url().'/detalle/'.$reserva->orden
					),
					array(
						'text'=>'Confirmar',
						'value'=>get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$reserva->orden.'&s=1'
					),
					array(
						'text'=>'Cancelar',
						'class'=>'cancelled action_confirmed',
						'value'=>get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$reserva->orden.'&s=0'
					)
				));

			$booking_th=array();
			$booking_th[]=array('class'=>'','data'=>'RESERVA');
			$booking_th[]=array('class'=>'','data'=>'CLIENTE');
			$booking_th[]=array('class'=>'','data'=>'SERVICIO');
			$booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
			$booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
			$booking_th[]=array('class'=>'','data'=>'ACCIONES');
			$booking_coming['pending']['th']=$booking_th;

			$booking_td=array();
			$booking_td[]=array('class'=>'','data'=>$reserva->ID);
			$booking_td[]=array('class'=>'','data'=>$_cliente);
			$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
			$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
			$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
			$booking_td[]=array('class'=>'','data'=>$options);
			$booking_coming['pending']['tr'][]=$booking_td;

		}
	}

	//BUILD TABLE
	echo build_table($booking_coming);

}else{
	$this->FieldOutput .= "<h2 style='line-height: normal;' class='vlz_no_aun'>Usted no tiene reservas pendientes</h2>";
}

$this->FieldOutput .= '</div>';