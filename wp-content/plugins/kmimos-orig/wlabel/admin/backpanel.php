<?php 
// wlabel 
require_once('function/db.php');
require_once('controller/admin.php');

// Parametros: Filtro por fecha
$date = getdate();
$desde = date("Y-m-01", $date[0] );
$hasta = date("Y-m-d", $date[0]);
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}
// Buscar Reservas
$wlabel = []; //Wlabel_FetchAll($desde, $hasta);


?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
			<h2>Panel de Control <small> WhiteLabel</small></h2>
			<hr>
			<div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row"> 
			<!-- BEGIN Option -->
			<div class="col-sm-12 col-md-4">
				<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#nuevo" aria-expanded="false" aria-controls="nuevo"><i class="fa fa-plus"></i> Nuevo</button>
			</div>
			<!-- END Option -->
			<!-- BEGIN Filtro -->
			<div class="col-sm-12 col-md-8 text-right">
		    	<form class="form-inline" action="/wp-admin/admin.php?page=bp_wlabel" method="POST">
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
			<!-- END Filtro -->
		</div>
		<div class="row"> <br>
			<!-- BEGIN Nuevo -->
			<div class="col-sm-12 col-md-12">
				<div class="collapse" id="nuevo">
					<div class="well">
				    	<form id="frm-wlabel" class="form-inline" action="/wp-content/plugins/kmimos/wlabel/admin/function/ajax.php?p=save" method="POST">
				    		<div class="row">			    			
					    		<div class="col-sm-8">
						    		<div class="row">
										<div class="form-group col-sm-6">
											<label for="titulo">Titulo</label>
											<input type="text" class="form-control" id="titulo" placeholder="Titulo del WLabel">
										</div>
										<div class="form-group col-sm-6">
											<label for="nombre">Nombre</label>
											<input type="text" class="form-control" id="nombre" placeholder="Nombre WLabel Ej: Volaris">
										</div>
										<div class="form-group col-sm-6">
											<label for="vigencia">Vigencia</label>
											<input type="text" class="form-control" id="vigencia" placeholder="Vigencia del WLabel">
										</div>
										<div class="form-group col-sm-2">
											<label for="color">Base</label>
											<input type="text" class="form-control" id="color" placeholder="#000000">
										</div>
										<div class="form-group col-sm-2">
											<label for="color">Botones</label>
											<input type="text" class="form-control" id="color_botones" placeholder="#000000">
										</div>
										<div class="form-group col-sm-2">
											<label for="color">Fuente</label>
											<input type="text" class="form-control" id="color_fuentes" placeholder="#000000">
										</div>
									</div>
					    		</div>	
					    		<div class="col-sm-4">
									<div class="form-group">
										<label for="logo" data-target="imgload">Logo</label>
										<img src="https://mx.kmimos/wp-content/uploads/2017/02/mural-360x150-360x150.png" alt="" 
										class="img-responsive" data-target="imgload">
										<input type="file" class="hidden" id="file">
									</div>
								</div>
							</div>
				    		<br />
							<div class="row">
								<div class="col-md-12">								
									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active"><a href="#CSS" aria-controls="CSS" role="tab" data-toggle="tab">CSS</a></li>
										<li role="presentation"><a href="#SCRIPT" aria-controls="SCRIPT" role="tab" data-toggle="tab">SCRIPT</a></li>
										<li role="presentation"><a href="#HeaderHTML" aria-controls="HTML (Header)" role="tab" data-toggle="tab">HTML (Header)</a></li>
										<li role="presentation"><a href="#FooterHTML" aria-controls="HTML (Footer)" role="tab" data-toggle="tab">HTML (Footer)</a></li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content">
										<div role="tabpanel" class="tab-pane active" id="CSS">
											<textarea id="css" class="form-control" rows="5">aaaa</textarea>
										</div>
										<div role="tabpanel" class="tab-pane" id="SCRIPT">
											<textarea id="js" class="form-control" rows="5">ssss</textarea>
										</div>
										<div role="tabpanel" class="tab-pane" id="HeaderHTML">
											<textarea id="hhtml" class="form-control" rows="5">dddd</textarea>
										</div>
										<div role="tabpanel" class="tab-pane" id="FooterHTML">
											<textarea  id="fhtml" class="form-control" rows="5">ffff</textarea>
										</div>
									</div>
								</div>
							</div>	
							<br/>
							<div class=" row">
								<div class="col-md-12">
									<span id="loading" class="hidden">
										<i style="font-size: 16px;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
										<span id="msg">Guardando datos...</span> 
									</span>

									<button type="button" id="btnsave" class="btn btn-success pull-right"><i class="fa fa-save"></i> Guardar</button>
									<button type="button" class="btn btn-default pull-right" data-toggle="collapse" data-target="#nuevo" aria-expanded="false" aria-controls="nuevo"><i class="fa fa-ban"></i> Cancelar</button>			  
								</div>
							</div>
					    </form>
					</div>
				</div>
			</div>
			<!-- END Nuevo -->
		</div>
	</div>
  	<div class="col-sm-12">  	
	<hr>  

  	<?php if( empty($wlabel) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row alert alert-info"> No existen registros </div>
  	<?php }else{ ?>  		
	    <div class="row"> 
	    	<div class="col-sm-12" id="table-container" 
	    		style="font-size: 10px!important;">
	  		<!-- Listado de wlabel -->
			<table id="tblwlabel" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" 
					cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th width="5%">#</th>
			      <th>Titulo</th>
			      <th>Nombre</th>
			      <th>Color</th>
			      <th>Vigencia</th>
			      <th width="15%">Opciones</th>
			    </tr>
			  </thead>
			  <tbody>
				  	<?php $count=0; ?>
				    <tr>	
				    	<th class="text-center">#</th>
						<th> -- </th>
						<th> -- </th>
						<th> -- </th>
						<th> -- </th>
						<th class="text-center">
							<button type="button" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Editar</button>
							<button type="button" class="btn btn-xs btn-danger"><i class="fa fa-ban"></i> Eliminar</button>
						</th>
				    </tr>
			  </tbody>
			</table>
			</div>
		</div>
	<?php } ?>	
  </div>
</div>
</div>
<div class="clearfix"></div>	
