<?php global $wpdb;
// Usuarios 
require_once('core/ControllerMascotas.php');

$pets = getMascotas();
$razas = get_razas();
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
			<h2>Panel de Control <small>Lista de mascotas</small></h2>
			<hr>
			<div class="clearfix"></div>
		</div>
	</div>
  	<div class="col-sm-12">  	

  	<?php if( empty($pets) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row alert alert-info"> No existen datos para mostrar</div>
  	<?php }else{ ?>
	    <div class="row">
	    	<div class="col-sm-12" id="table-container" 
	    		style="font-size: 10px!important;">
	  		<!-- Listado de users -->
			<table id="tblusers" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" 
					cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th>#</th>
			      <th>Foto</th>
			      <th>Nombre</th>
			      <th>Raza</th>
			      <th>Edad</th>
			      <th>Genero</th>
			      <th>Propietario</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0; ?>
			  	<?php foreach( $pets['rows'] as $rows ){ ?>
			  		<?php
			  			$row = getmetaMascotas( $rows['ID'] );
			  			$row['breed_pet'] = $razas[ $row['breed_pet'] ];
			  			$user = getMetaCliente($row['owner_pet']);	

			  		?>
				    <tr>
				    	<th class="text-center"><?php echo ++$count; ?></th>
				    	<th class="text-center">
					<?php 
						if( file_exists( get_home_url()."/".$row['photo_pet']) ){
							echo '<img src="'.get_home_url()."/".$row['photo_pet'].'" >'; 
						}
					?>
			    		</th>
				    	<th class="text-center"><?php echo $row['name_pet']; ?></th>
				    	<th class="text-center"><?php echo $row['breed_pet']; ?></th>
				    	<th class="text-center"><?php echo getEdad($row['birthdate_pet']); ?></th>
				    	<th class="text-center"><?php echo kmimos_manage_pets_columns('gender', $rows["ID"]); ?></th>
				    	<th class="text-center"><?php echo "{$user['first_name']} {$user['last_name']}"; ?></th>
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
