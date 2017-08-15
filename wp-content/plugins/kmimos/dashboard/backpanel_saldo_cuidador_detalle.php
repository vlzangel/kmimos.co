<?php global $wpdb;
// Reservas 
require_once('core/ControllerSaldoCuidadorDetalle.php');
// Parametros: Filtro por fecha
$date = getdate(); 
$desde = date("Y-m-01", $date[0] );
$hasta = date("Y-m-d", $date[0]);
$_desde = "";
$_hasta = "";
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$_desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$_hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}
$razas = get_razas();
// Buscar Reservas
$reservas = getReservas($_desde, $_hasta);


?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
		<h2>Panel de Control <small>Reservas</small></h2>
		<hr>
		<div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row text-right"> 
			<div class="col-sm-12">
		    	<form class="form-inline" action="/wp-admin/admin.php?page=<?php echo $_GET['page']; ?>" method="POST">
				  <label>Filtrar:</label>
				  <div class="form-group">
				    <div class="input-group">
				      <div class="input-group-addon">Desde</div>
				      <input type="date" class="form-control" name="desde" value="<?php echo $desde; ?>">
				    </div>
				  </div>
				  <div class="form-group">
				    <div class="input-group">
				      <div class="input-group-addon">Hasta</div>
				      <input type="date" class="form-control" name="hasta" value="<?php echo $hasta ?>">
				    </div>
				  </div>
					<button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>			  
			    </form>
				<hr>  
		  		<div class="clearfix"></div>
			</div>
		</div>
  	</div>
	<div class="clearfix"></div>
  	<div class="col-sm-12">  	

  	<?php if( empty($reservas) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row">
	    	<div class="col-sm-12">
	    		<div class="alert alert-info">
		    		No existen registros
	    		</div>
		    </div>
	    </div> 
  	<?php }else{ ?>  		
	    <div class="row"> 
	    	<div class="col-sm-12" id="table-container" 
	    		style="font-size: 10px!important;">
	  		<!-- Listado de Reservas -->
			<table id="tblReservas" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" 
					cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th>#</th>
			      <th># Reserva</th>
			      <th>Estatus</th>
			      <th>Fecha Reservacion</th>
			      <th>Cliente</th>

			      <th>Cuidador</th>
			      <th>Servicio Principal</th> 
			      <th>Forma de Pago</th>
			      <th>Total a pagar</th>
			      <th>Monto Pagado</th>
			      <th>Monto Remanente</th>
			      <th>Pago Cuidador</th>
			      <th># Pedido</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
			  		$total_a_pagar=0;
			  		$total_pagado=0;
			  		$total_remanente=0;
			  	 ?>
			  	<?php $count=0; ?>
			  	<?php foreach( $reservas as $reserva ){ ?>
				  	<?php 
				  		// *************************************
				  		// Cargar Metadatos
				  		// *************************************
				  		# MetaDatos del Cuidador
				  		$meta_cuidador = getMetaCuidador($reserva->cuidador_id);
				  		# MetaDatos del Cliente
				  		$cliente = getMetaCliente($reserva->cliente_id);

				  		# MetaDatos del Reserva
				  		$meta_reserva = getMetaReserva($reserva->nro_reserva);
				  		# MetaDatos del Pedido
				  		$meta_Pedido = getMetaPedido($reserva->nro_pedido);
				  		# Status
				  		$estatus = get_status(
				  			$reserva->estatus_reserva, 
				  			$reserva->estatus_pago, 
				  			$meta_Pedido['_payment_method'],
				  			$reserva->nro_reserva // Modificacion Ángel Veloz
				  		);

				  		if($estatus['addTotal'] == 1){
							$total_a_pagar += currency_format($meta_reserva['_booking_cost'], "");
					  		$total_pagado += currency_format($meta_Pedido['_order_total'], "");
					  		$total_remanente += currency_format($meta_Pedido['_wc_deposits_remaining'], "");
				  		}

						if(!in_array('hospedaje', explode("-", $reserva->post_name))){
							$nro_noches += 1;
						}

						$Day = "";
						$list_service = [ 'hospedaje' ]; // Excluir los servicios del Signo "D"
						$temp_option = explode("-", $reserva->producto_name);
						if( count($temp_option) > 0 ){
							$key = strtolower($temp_option[0]);
							if( !in_array($key, $list_service) ){
								$Day = "-D";
							}
						}

						$pago_cuidador = calculo_pago_cuidador( 
							$reserva->nro_reserva,
							$meta_reserva['_booking_cost'],
							$meta_Pedido['_order_total'],
							$meta_Pedido['_wc_deposits_remaining']
							);
				  	?>
				    <tr>
				    	<th class="text-center"><?php echo ++$count; ?></th>
					<th><?php echo $reserva->nro_reserva; ?></th>
					<th class="text-center"><?php echo $estatus['sts_corto']; ?></th>
					<th class="text-center"><?php echo $reserva->fecha_solicitud; ?></th>

					<th><?php echo "<a href='".get_home_url()."/?i=".md5($reserva->cliente_id)."'>".$cliente['first_name'].' '.$cliente['last_name']; ?></a></th>

					<th><?php echo $meta_cuidador['first_name'] . ' ' . $meta_cuidador['last_name']; ?></th>
					<th><?php echo $reserva->producto_title; ?></th>
					<th><?php
						if( !empty($meta_Pedido['_payment_method_title']) ){
							echo $meta_Pedido['_payment_method_title']; 
						}else{
							if( !empty($meta_reserva['modificacion_de']) ){
								echo 'Saldo a favor' ; 
							}else{
								echo 'Manual'; 
							}
						} ?>
					</th>
					<th><?php echo currency_format($meta_reserva['_booking_cost']); ?></th>
					<th><?php echo currency_format($meta_Pedido['_order_total']); ?></th>
					<th><?php echo currency_format($meta_Pedido['_wc_deposits_remaining']); ?></th>
				    	<th class="text-center"><?php echo $pago_cuidador; ?></th>
					<th><?php echo $reserva->nro_pedido; ?></th>

				    </tr>
			   	<?php } ?>
			  </tbody>
			</table>
			</div>
		</div>
	<?php } ?>

	<div class="hidden">	
		<div class="col-xs-12 col-sm-12 col-md-2" style="margin:5px; padding:10px; ">
			<strong>Reservas Confirmadas</strong>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3" style="margin:5px;background: #e8e8e8; padding:10px; ">
			<span>Total a pagar: <?php echo currency_format($total_a_pagar); ?> </span>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3" style="margin:5px;background: #e8e8e8; padding:10px; ">
			<span>Total pagado: <?php echo currency_format($total_pagado); ?></span>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3" style="margin:5px;background: #e8e8e8; padding:10px; ">
			<span>Total Remanente: <?php echo currency_format($total_remanente); ?></span>
		</div>	
	</div>
  </div>
</div>
</div>
<div class="clearfix"></div>	
