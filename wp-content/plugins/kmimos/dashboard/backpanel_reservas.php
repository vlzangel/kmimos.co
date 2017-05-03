<?php global $wpdb;
// Reservas 
require_once('core/ControllerReservas.php');
// Parametros: Filtro por fecha
$date = getdate();
$desde = date("Y-m-01", $date[0] );
$hasta = date("Y-m-d", $date[0]);
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}
$razas = get_razas();
// Buscar Reservas
$reservas = getReservas($desde, $hasta);

function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}

?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
		<h2>Panel de Control <small>Reservas</small></h2>
		<hr>
		<div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row text-right"> 
			<div class="col-sm-12">
		    	<form class="form-inline" action="/wp-admin/admin.php?page=bp_reservas" method="POST">
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
			</div>
		</div>
	</div>
  	<div class="col-sm-12">  	

  	<?php if( empty($reservas) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row alert alert-info"> No existen registros </div>
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
			      <th>Check-In</th>
			      <th>Check-Out</th>
			      <th>Noches</th>
			      <th># Mascotas</th>
			      <th># Noches Totales</th>
			      <th>Cliente</th>
			      <th>Donde nos conocio?</th>
			      <th>Mascotas</th>
			      <th>Razas</th>
			      <th>Edad</th>
			      <th>Cuidador</th>
			      <th>Servicio Principal</th> 
			      <th>Servicios Especiales</th> <!-- Servicios adicionales -->
			      <th>Estado</th>
			      <th>Municipio</th>
			      <th>Forma de Pago</th>
			      <th>Total a pagar</th>
			      <th>Monto Pagado</th>
			      <th>Monto Remanente</th>
			      <th># Pedido</th>
			      <th>Observaci&oacute;n</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0; ?>
			  	<?php foreach( $reservas as $reserva ){ ?>
 
				  	<?php 
				  		// *************************************
				  		// Cargar Metadatos
				  		// *************************************
				  		# MetaDatos del Cliente
				  		$cliente = getMetaCliente($reserva->cliente_id);
				  		# MetaDatos del Reserva
				  		$meta_reserva = getMetaReserva($reserva->nro_reserva);
				  		# MetaDatos del Pedido
				  		$meta_Pedido = getMetaPedido($reserva->nro_pedido);
				  		# Mascotas del Cliente
				  		$mypets = getMascotas($reserva->cliente_id); 
				  		# Estado y Municipio del cuidador
				  		$ubicacion = get_ubicacion_cuidador($reserva->cuidador_id);
				  		# Servicios de la Reserva
				  		$services = getServices($reserva->nro_reserva);
				  		# Status
				  		$estatus = get_status(
				  			$reserva->estatus_reserva, 
				  			$reserva->estatus_pago, 
				  			$reserva->metodo_pago_pk 
				  		);
				  		$pets_nombre = "";
				  		$pets_razas  = "";
				  		$pets_edad	 = "";
						$separador   = ", " ;
						foreach( $mypets as $pet_id => $pet)
						{ 
							$pets_nombre .= ($pets_nombre!="")? $separador :"";
							$pets_nombre .= $pet['name'];
							
							$pets_razas .= ($pets_razas!="")? $separador :"";
							//$pets_razas .= getRazaDescripcion( $pet['raza_nombre'] );
							$pets_razas .= getRazaDescripcion( $pet['breed'], $razas );
							
							$pets_edad .= ($pets_edad!="")? $separador :"";
							$pets_edad .= getEdad( $pet['birthdate'] );
						} 

						$nro_noches = dias_transcurridos(date_convert($meta_reserva['_booking_end'], 'd-m-Y'), date_convert($meta_reserva['_booking_start'], 'd-m-Y') );

				  	?>
				    <tr>
			    	<th class="text-center"><?php echo ++$count; ?></th>
					<th><?php echo $reserva->nro_reserva; ?></th>
					<th class="text-center"><?php echo $estatus['sts_corto']; ?></th>
					<th class="text-center"><?php echo $reserva->fecha_solicitud; ?></th>
					<th><?php echo date_convert($meta_reserva['_booking_start'], 'd-m-Y'); ?></th>
					<th><?php echo date_convert($meta_reserva['_booking_end'], 'd-m-Y'); ?></th>
					<th class="text-center"><?php echo $nro_noches; //$reserva->nro_noches; ?></th>
					<th class="text-center"><?php echo $reserva->nro_mascotas; ?></th>
					<th><?php echo $nro_noches * $reserva->nro_mascotas; ?></th>
					<th><?php echo $cliente['first_name'].' '.$cliente['last_name']; ?></th>
					<th><?php echo (empty($cliente['user_referred']))? 'Otros' : $cliente['user_referred'] ; ?></th>
					<th><?php echo $pets_nombre; ?></th>
					<th><?php echo $pets_razas; ?></th>
					<th><?php echo $pets_edad; ?></th>
					<th><?php echo $reserva->cuidador_nombre; ?></th>
					<th><?php echo $reserva->producto_title; ?></th>
					<th>
					<?php foreach( $services as $service ){ ?>
						<?php echo str_replace("(precio por mascota)", "", $service->descripcion); ?> 
						<?php echo str_replace("Servicios Adicionales", "", $service->servicio); ?><br>
					<?php } ?>
					</th>
					<th><?php echo utf8_decode( $ubicacion['estado'] ); ?></th>
					<th><?php echo utf8_decode( $ubicacion['municipio'] ); ?></th>
					<th><?php echo $meta_Pedido['_payment_method_title']; ?></th>
					<th><?php echo currency_format($meta_reserva['_booking_cost']); ?></th>
					<th><?php echo currency_format($meta_reserva['_order_total']); ?></th>
					<th><?php echo currency_format($meta_reserva['_wc_deposits_remaining']); ?></th>
					<th><?php echo $reserva->nro_pedido; ?></th>
					<th><?php echo $estatus['sts_largo']; ?></th>

				    </tr>
			   	<?php } ?>
			  </tbody>
			</table>
			</div>
		</div>
	<?php } ?>	
  </div>
</div>
</div>
<div class="clearfix"></div>	
