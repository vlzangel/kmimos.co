<?php global $wpdb;
// PagoCuidador 
require_once('core/ControllerSaldoCuidador.php');

$date = getRangoFechas();

$desde = $date['ini'];
$hasta = $date['fin'];

if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}

// Buscar PagoCuidador
$PagoCuidador = getPagoCuidador( $desde, $hasta );
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
			<h2>Panel de Control <small>PagoCuidador</small></h2>
			<hr>
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
			</div>
		</div>
  	</div>
	<div class="clearfix"></div>
  	<div class="col-sm-12">  	
  	<div class="alert ">Busqueda realizada, Desde: <strong><?php echo $desde; ?></strong>, Hasta: <strong><?php echo $hasta; ?></strong></div>
  	<?php if( empty($PagoCuidador) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row">
	    	<div class="col-sm-12">
	    		<div class="alert alert-info">
		    		No existen registros.
	    		</div>
		</div>
	    </div> 
  	<?php }else{ ?>  		
	    <div class="row"> 
	    	<div class="col-sm-12" id="table-container" 
	    		style="font-size: 10px!important;">
	  		<!-- Listado de PagoCuidador -->
			<table id="tblPagoCuidador" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th>#</th>
			      <th>ID</th>
			      <th>Nombre</th>
			      <th>Apellido</th>
			      <th>Total a pagar</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0; ?>
			  	<?php foreach( $PagoCuidador as $ID => $row ){ ?>
				    <tr>

				    	<th class="text-center"><?php echo ++$count; ?></th>
				    	<th class="text-center"><?php echo $ID; ?></th>
				    	<th class="text-center"><?php echo $row['nombre']; ?></th>
				    	<th class="text-center"><?php echo $row['apellido']; ?></th>
				    	<th class="text-center"><?php echo $row['total']; ?></th>

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
