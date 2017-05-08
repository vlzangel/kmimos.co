<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<?php echo kmimos_style( array("celdas") ); ?>

<?php
	include("setup/php/scripts.php");
	paginas();
	administradores();
	usuarios();
?>

<link rel="stylesheet" href="<?php echo get_home_url()."/wp-content/plugins/kmimos/dashboard/setup/css/setup_css.css"; ?>">
<script type="text/javascript"> var SETUP_URL_AJAX = "<?php echo get_home_url()."/wp-content/plugins/kmimos/dashboard/ajaxs.php"; ?>"; </script>
<script type="text/javascript"> var URL_HOME = "<?php echo get_home_url()."/"; ?>"; </script>
<script type="text/javascript" src="<?php echo get_home_url()."/wp-content/plugins/kmimos/dashboard/setup/js/setup_scripts.js"; ?>"></script>

<div class="wrap">

	<?php 
		settings_fields('kmimos_group');
		@do_settings_fields('kmimos_group');
	?>

	<div>

	    <div class="row">
	        <div class="col-md-12">
				<h2 class="kmimos_titulos">Listado de Usuarios</h2>
			</div>
		</div>

	    <div class="row" style="overflow: hidden;">
	        <div class="col-md-12">
	        	<div style='position: relative;'> 

	        		<div class="row">
	        			<div class="col-md-2 kmimos_md2">
				        	<SELECT id="filtrar_usuarios" class="kmimos_buscador">
				        		<OPTION value='subscriber'>Cliente</OPTION>
				        		<OPTION value='vendor'>Cuidador</OPTION>
				        		<OPTION value='author'>Editor</OPTION>
				        	</SELECT>
				        </div>
	        			<div class="col-md-10 kmimos_md10">
	        				<input type='text' id='buscar_usuarios' class="kmimos_buscador" placeholder="Ingrese texto a buscar">
	        			</div>
	        		</div>

					<table width="100%" class='table_head' cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th style="width: 50%;"> Usuario </th>
								<th style="width: 50%;"> Email </th>
							</tr>
						</thead>
					</table>
					<div class='kmimos_contenedor_table'>
						<table width="100%" class='' cellpadding="0" cellspacing="0">
							<tbody id="kmimos_panel_usuarios"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	    <div class="row">
	        <div class="col-md-12">
				<h2 class="kmimos_titulos">Listado de Administradores</h2>
			</div>
		</div>

	    <div class="row" style="overflow: hidden;">

	        <div class="col-md-12">
	        	<div style='position: relative;'> 
	        		<div class='kmimos_modal_interno' id='editar_tipo_usuario_modal'>
		        		<div class='kmimos_modal_interno_celda'>
		        			<div class='kmimos_modal_interno_area'>

			        			<select id='editar_tipo_usuario_modal_tipo_usuario' class='kmimos_select' style='display: block; width: 500px;'>
									<option value='Administrador'>Administrador</option>
									<option value='Customer Service'>Customer Service</option>
								</select>
								<input type='hidden' id='editar_tipo_usuario_modal_user_id'>
								<input type='hidden' id='editar_tipo_usuario_modal_index'>

								<div class="kmimos_botonera">
									<input type="button" value="Cerrar" onclick='jQuery(".kmimos_modal_interno").css("display", "NONE");' >
									<input type="button" value="Guardar" onclick="update_tipo_usuario()" >
								</div>
			        		</div>
		        		</div>
	        		</div>

	        		<input type='text' id='buscar_administrador' class="kmimos_buscador" placeholder="Ingrese texto a buscar">
					<table width="100%" class='table_head' cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th style="width: 20%;"> Usuario </th>
								<th style="width: 50%;"> Email </th>
								<th style="width: 30%;"> Tipo </th>
							</tr>
						</thead>
					</table>
					<div class='kmimos_contenedor_table'>
						<table width="100%" class='' cellpadding="0" cellspacing="0">
							<tbody id="kmimos_panel_setup"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	    <div class="row">
	        <div class="col-md-12">
				<h2 class="kmimos_titulos">Editor de Descripciones</h2>
			</div>
		</div>

	    <div class="row" style="overflow: hidden;">
	        <div class="col-md-12">
				<div class='kmimos_modal_interno' id='editar_descripcion_modal'>
	        		<div class='kmimos_modal_interno_celda'>
	        			<div class='kmimos_modal_interno_area'>
		        			<textarea id='descripcion' style='display: block; width: 500px; height: 200px;'></textarea>
							<input type='hidden' id='page_id'>
							<input type='hidden' id='index'>
							<div class="kmimos_botonera">
								<input type="button" value="Cerrar" onclick='jQuery(".kmimos_modal_interno").css("display", "NONE");' >
								<input type="button" value="Guardar" onclick="update_descripcion()" >
							</div>
		        		</div>
	        		</div>
	    		</div>

        		<input type='text' id='buscar_pagina' class="kmimos_buscador" placeholder="Ingrese texto a buscar">
				<table width="100%" class='table_head' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th style="width: 40%;"> Pagina </th>
							<th style="width: 60%;"> Descripción </th>
						</tr>
					</thead>
				</table>
				<div class='kmimos_contenedor_table'>
					<table width="100%" class='' cellpadding="0" cellspacing="0">
						<tbody id="kmimos_setup_descripciones"></tbody>
					</table>
				</div>
			</div>

		</div>

</div>

