<?php global $wpdb;
// Subscribe 
require_once('core/ControllerSubscriber.php');
// Parametros: Filtro por fecha
$landing = '';
$date = getdate();
$desde = date("Y-m-01", $date[0] );
$hasta = date("Y-m-d", $date[0]);
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}
// Buscar Reservas
$subscribe = getListsuscribe($landing, $desde, $hasta);
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
			<h2>Panel de Control <small>Lista de suscriptores</small></h2>
			<hr>
			<div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row text-right"> 
			<div class="col-sm-12">
		    	<form class="form-inline" action="/wp-admin/admin.php?page=bp_suscriptores" method="POST">
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

  	<?php if( empty($subscribe) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row alert alert-info"> No existen registros </div>
  	<?php }else{ ?>  		
	    <div class="row"> 
	    	<div class="col-sm-12" id="table-container" 
	    		style="font-size: 10px!important;">
	  		<!-- Listado de subscribe -->
			<table id="tblsubscribe" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" 
					cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th>#</th>
			      <th>Landing</th>
			      <th>Email</th>
			      <th>Fecha Suscripción</th>
			      <th>Fecha Registro</th>
			      <th>Tipo de Usuario</th>
			      <th>Estatus</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0; ?>
			  	<?php while( $row = $subscribe->fetch_assoc() ){ ?>
				    <tr>
				    	<th class="text-center"><?php echo ++$count; ?></th>
						<th><?php echo $row['source']; ?></th>
						<th><?php echo $row['email']; ?></th>
						<th class="text-center"><?php echo $row['fecha']; ?></th>
						<th class="text-center"><?php echo $row['fecha_registro']; ?></th>
						<th class="text-center"><?php echo $row['tipo']; ?></th>
						<th class="text-center"><?php echo $row['estatus']; ?></th>
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
