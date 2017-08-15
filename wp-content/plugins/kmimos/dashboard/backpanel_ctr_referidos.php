<?php 
// Subscribe 
require_once('core/ControllerCtrReferidos.php');
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
			<h1>Panel de Control <small>Lista de referidos  ( Club Patitas Felices )</small></h1>
			<div class="clearfix"></div>
			<hr>
		</div>
		<!-- Filtros -->
		<div class="row">
		  	<div class="col-sm-4">
			  	<ul class="list-inline">
					<li>
						<a class="btn btn-default pull-rigth" href="/wp-admin/admin.php?page=bp_participantes_club_patitas_felices"><< </a>
					</li>
					<?php if(!empty($referido)) { ?>
					<li>
						<button type="button" id="referencia" class="btn btn-primary" >
						  <i class="fa fa-plus"></i> Referencia
						</button>
					</li>
					<?php } ?>
				</ul>		
		  	</div>
			<div class="col-sm-8  text-right">
		    	<form class="form-inline" action="/wp-admin/admin.php?page=bp_referidos&ref=<?php echo $referido; ?>" method="POST">
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
			</div>
			<hr>  
		</div>
	</div>
	<div class="clearfix"></div>
	<?php if(!empty($referido)) { ?>
	  	<div class="col-sm-12">
	  		<h3><?php echo "Referidos por: $referido"; ?></h3>
	  	</div>
	<?php } ?>
  	<div class="col-sm-12">
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
			      <th width="5%">#</th>
					<?php if( !empty($referido) ) { ?>					    	
			      <th width="5%" class="<?php echo (empty($referido))? 'hidden' : ''; ?>">Opt.</th>
			    	<?php } ?>
			      <th>Email Referidos</th>
			      <th>Nombre Referido</th>
			      <th>Cant. Reservas</th>
			      <th>Referencia</th>
			      <th>Landing</th>
			      <th>Referido por.</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0;?>
			  	<?php $showReferencia=0;?>
			  	<?php while( $row = $subscribe->fetch_assoc() ){
			  		// Cargar datos del usuario referido
			  		$metaReferido = getmetaUser($row['referido_id']);

			  		// Cargar datos de referencia
			  		$referencia = getReferencia($row['user_email'], $landing);
			  		$referencia = $referencia['rows'][0];
			  		$metaReservasResult = getCountReservas($row['referido_id'] );
			  		$metaReservas = $metaReservasResult['rows'][0];
		    		if( $metaReservas['cant'] > 0 ){ 
			    		if(empty($referencia['referencia'])){ 
			    			$showReferencia++;
			    		}
			    	}
			  	 ?>
				    <tr id="<?php echo $row['user_email']; ?>">
				    	<th class="text-center"><?php echo ++$count; ?></th>
					
						<?php if( !empty($referido) ) { ?>					    	
					    	<th data-target="check" class="text-center">	
				    		<?php if( $metaReservas['cant'] > 0 ){ ?>
					    		<?php if(empty($referencia['referencia'])){ ?>
						    		<input type="checkbox" name="opt[]" value="<?php echo $row['user_email']; ?>">
					    		<?php }else{ echo '<i class="fa fa-2x fa-check" aria-hidden="true"></i>'; } ?>
				    		<?php }else{ echo '<i class="fa fa-minus-square-o fa-2x" aria-hidden="true"></i>'; } ?>
					    	</th>
				    	<?php } ?>

						<th class="bg-info"><?php echo $row['user_email']; ?></th>
						<th><?php echo $metaReferido['first_name']." ".$metaReferido['last_name']; ?></th>
						<th><?php echo ($metaReservas['cant']>0)? "1": "0" ; ?></th>
				    	<th data-target="ref"><?php echo $referencia['referencia']; ?></th>
						<th><?php echo $row['source']; ?></th>
						<th><?php echo $row['user_parent']; ?></th>
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

<!-- Modal Control de pagos a participantes -->
<div class="modal" id="modalRef" tabindex="-1" role="dialog" aria-labelledby="modalRef">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		    <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="modalRef">Registrar premio</h4>
		    </div>
		    <form action="">
		      	<div class="modal-body row">
					<div class="form-group col-xs-12 col-md-6 col-lg-6">
						<label for="fecha">Fecha de creaci&oacute;n</label>
						<input type="date" class="form-control" id="fecha" value="<?php echo $hoy; ?>" placeholder="fecha">
					</div>
					<div class="form-group col-xs-12 col-md-6 col-lg-6">
						<label for="ref">Referencia</label>
						<input type="text" class="form-control" id="ref" placeholder="Referencia">
					</div>
		      	</div>
		      	<div class="modal-footer">
		      		<span id="loading" class="hidden"><i class="fa-circle  fa-spin fa-2x fa-fw"></i> Guardando datos...</span><span id="msg" class="hidden"></span>

		        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" id="saveRef" class="btn btn-success">Guardar</button>
		      	</div>
		    </form>
	    </div>
	</div>
</div>

<div class="clearfix"></div>

<?php if( $showReferencia >= 5 ){ ?>
<script>
	jQuery(function($){
		//$("#referencia").addClass('disabled');
		var disabled = $('[data-target="ref"]:not(:empty)').size(); 
	    if( disabled >= 5 ){
	      $("#referencia").addClass('disabled');
	    }
	});
</script>
<?php } ?>
