<?php 
// Subscribe 
require_once('controller/ControllerReferidos.php');
// Parametros: Filtro por fecha
$date = getdate();
$hoy = date("Y-m-d", $date[0]);
$desde = date("Y-05-09", $date[0] );
$hasta = date("Y-m-d", $date[0]);
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}

$referido = '';
if(	isset($_GET['ref']) ){
	$referido = ( !empty($_GET['ref']) )? $_GET['ref']: "";
}

// Buscar Reservas
$landing = 'kmimos-mx-clientes-referidos';
$subscribe = getListsuscribe($landing, $referido, $desde, $hasta);
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
			<h1>Panel de Control <small>Lista de referidos</small></h1>
			<div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row text-right"> 
			<div class="col-sm-12">
		    	<form class="form-inline" action="/panel/?p=referidos&ref=<?php echo $referido; ?>" method="POST">
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
  	<div class="col-sm-10">
  		<h3><?php echo (!empty($referido))? "Referidos por: $referido" : ""; ?></h3>
  	</div>
  	<div class="col-sm-2 text-right">
		<a class="btn btn-default pull-rigth" href="/panel/?p=suscriptores"><< Volver al listado</a>
  	</div>
  	<div class="col-sm-12">
  	<hr>
  	<?php if( empty($subscribe) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row alert alert-info"> No existen usuarios referidos </div>
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
			      <th>Email Participante</th>
			      <th>Email Referidos</th>
			      <th>Nombre Referido</th>
			      <th>Cant. Reservas</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0;?>
			  	<?php while( $row = $subscribe->fetch_assoc() ){
			  		// Cargar datos del usuario referido
			  		$metaReferido = getmetaUser($row['referido_id']);
			  		// Cargar datos de reservas generadas
			  		$metaReservasResult = getCountReservas($row['referido_id'] );
			  		$metaReservas = $metaReservasResult['rows'][0];
			  	 ?>
				    <tr>
				    	<th class="text-center"><?php echo ++$count; ?></th>
						<th><?php echo $row['source']; ?></th>
						<th><?php echo $row['user_parent']; ?></th>
						<th><?php echo $row['user_email']; ?></th>
						<th><?php echo $metaReferido['first_name']." ".$metaReferido['last_name']; ?></th>
						<th><?php echo $metaReservas['cant']; ?></th>
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

