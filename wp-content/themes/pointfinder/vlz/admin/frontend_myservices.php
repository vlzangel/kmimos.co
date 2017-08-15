<?php
	
    global $current_user;

    $formaction = 'updateservices';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-add-service-button';
    $buttontext = esc_html__('ACTUALIZAR SERVICIOS','pointfindert2d');
    $user_id = $current_user->ID;
    $count = ($params['count']!='') ? $params['count'] : 0;

    $this->ScriptOutput .= "
        jQuery('#pf-ajax-add-service-button').on('click',function(e){
            e.preventDefault();
            // jQuery('#pfuaprofileform').attr('action','?ua=updateservices');
            jQuery('#pfuaprofileform').attr('action','".get_home_url()."/wp-content/themes/pointfinder/vlz/admin/procesar_mis_servicios.php');
            jQuery('#pfuaprofileform').submit();
        });
    ";

    echo "
		<style>
			h1{ 
				margin-top: 5px; 
				margin-bottom: 0px; 
				line-height: 15px; 
			}
			.vlz_seccion{
				position: relative;
			    margin-bottom: 20px;
			    border: solid 1px #b1b1b1;
			    box-sizing: border-box;
			    padding: 5px;
			    border-radius: 0px 3px 3px 3px;
				font-weight: 600;
				background-color: rgb(249, 249, 249)
			}
			.vlz_seccion label{
				display: block;
				font-weight: 600;
			    font-size: 11px;
		        margin: 3px 0px;
			}
			.vlz_titulo_seccion {
			    border-bottom: solid 2px #59c9a8;
			    color: #000000;
			    padding: 8px 0px;
			    border-radius: 0px 3px 0px 0px;
			    margin: 0px 3px 3px;
			    font-size: 14px;
			}
			.vlz_seccion_interna{
				position: relative;
				overflow: hidden;
				margin-bottom: 5px;
			}
			.vlz_input{
				width: 100%;
			    box-sizing: border-box;
			    padding: 5px 10px;
			    border-radius: 3px;
			    border: solid 1px #d0d0d0;
			}
    		.vlz_celda_20 {
			    width: 20%;
			    box-sizing: border-box;
			    display: inline-block;
			    float: left;
			    padding: 0px 2px;
			}
    		.vlz_celda_25 {
			    width: 25%;
			    box-sizing: border-box;
			    display: inline-block;
			    float: left;
			    padding: 0px 2px;
			}
    		.vlz_celda_33 {
			    width: 33.3333333%;
			    box-sizing: border-box;
			    display: inline-block;
			    float: left;
			    padding: 0px 2px;
			}
    		.vlz_celda_50 {
			    width: 50%;
			    box-sizing: border-box;
			    display: inline-block;
			    float: left;
			    padding: 0px 2px;
			}

			.vlz_activado{
				float: right;
			    border: solid 1px #59c9a8;
			    padding: 2px 5px;
			    background: #59c9a8;
			    color: #FFF !important;
			    font-weight: 600;
			}

			.vlz_desactivado{
			    float: right;
			    border: solid 1px #c95959;
			    padding: 2px 5px;
			    background: #c95959;
			    color: #FFF !important;
			    font-weight: 600;
			}

			@media screen and (max-width: 800px) {
				.vlz_celda_20{
				    width: 33.3333333%;
				    padding: 0px 2px 5px;
				}
				.vlz_celda_25{
				    width: 50%;
				    padding: 0px 2px 5px;
				}
			}
			@media screen and (max-width: 600px) {
				.vlz_celda_50{
				    width: 100%;
				    padding: 0px 2px 5px;
				}
			}
			@media screen and (max-width: 480px) {
				.vlz_celda_20,
				.vlz_celda_25{
				    width: 50%;
				    padding: 0px 2px 5px;
				}
				.vlz_celda_25_x,
				.vlz_celda_33,
				.vlz_celda_50{
				    width: 100%;
				    padding: 0px 2px 5px;
				}
				.vlz_titulo_seccion {
				    border-bottom: solid 2px #59c9a8;
				    padding: 8px 0px;
				    border-radius: 0px 3px 0px 0px;
				    margin: 0px 3px 3px;
				    font-size: 14px;
				}
			}
    	</style>
    ";

    $adicionales_principales = array(
        "guarderia"                 => "Guarder&iacute;a",
        "adiestramiento_basico"     => "Adiestramiento B&aacute;sico",
        "adiestramiento_intermedio" => "Adiestramiento Intermedio",
        "adiestramiento_avanzado"   => "Adiestramiento Avanzado"
    );
    
    $adicionales_extra = array(
        "bano"                      => "Ba&ntilde;o",
        "corte"                     => "Corte de pelo y u&ntilde;as",
        "limpieza_dental"           => "Limpieza Dental",
        "acupuntura"                => "Acupuntura",
        "visita_al_veterinario"     => "Visita al Veterinario"
    );

    $tam = array(
		"pequenos" => "Peque&ntilde;os",
		"medianos" => "Medianos",
		"grandes"  => "Grandes",
		"gigantes" => "Gigantes",
	);

    global $wpdb;

    $sql = "SELECT * FROM cuidadores WHERE user_id = ".$user_id;

    $cuidador = $wpdb->get_row($sql);

    $hospedaje = "";
    $precios_hospedaje = unserialize($cuidador->hospedaje);

    foreach ($precios_hospedaje as $key => $value) {
    	$hospedaje .= "
    		<div class='vlz_celda_25'>
    			<label>".$tam[$key]."</label>
    			<input type='number' min=0 data-minvalue=0 data-charset='num' class='vlz_input' id='hospedaje_".$key."' name='hospedaje_".$key."' value='".$value."' />
			</div>
    	";
    }


	$status_servicios = array();
	$sql = "SELECT * FROM wp_posts WHERE post_author = {$user_id} AND post_type = 'product'";
    $productos = $wpdb->get_results($sql);
    foreach ($productos as $producto) {
    	$servicio = explode("-", $producto->post_name);
    	$status_servicios[ $servicio[0] ] = $producto->post_status;
    }

    $precios_adicionales_cuidador = unserialize($cuidador->adicionales);

    $adicionales = array(
    	"guarderia"						=> "Guardería",
    	"paseos"						=> "Paseos",
    	"adiestramiento_basico"			=> "Entrenamiento Básico",
    	"adiestramiento_intermedio"		=> "Entrenamiento Intermedio",
    	"adiestramiento_avanzado"		=> "Entrenamiento Avanzado"
    );
    $precios_adicionales = "";
    foreach ($adicionales as $key => $value) {
    	$temp = "";
    	foreach ($tam as $key2 => $value2) {
    		if( isset($precios_adicionales_cuidador[$key] ) ){
    			$precio = $precios_adicionales_cuidador[$key][$key2];
    		}else{
    			$precio = "";
    		}
	    	$temp .= "
	    		<div class='vlz_celda_25'>
	    			<label>".$value2."</label>
	    			<input type='number' min=0 data-minvalue=0 data-charset='num' class='vlz_input' id='".$key."_".$key2."' name='".$key."_".$key2."' value='".$precio."' />
				</div>
	    	";
    	}

    	//if( $status_servicios[ $key ] == 'publish' ){
		if(isset($precios_adicionales_cuidador['status_'.$key]) && $precios_adicionales_cuidador['status_'.$key]==1){
    		$boton = "<input type='button' value='Activado' class='vlz_activador vlz_activado' id='status_{$key}' > <input type='hidden' id='oculto_status_{$key}' name='status_{$key}' value='1' >";
		}else{
    		$boton = "<input type='button' value='Desactivado' class='vlz_activador vlz_desactivado' id='status_{$key}' > <input type='hidden' id='oculto_status_{$key}' name='status_{$key}' value='0' >";
    	}

    	$precios_adicionales .= "
    		<div class='vlz_seccion'>
    			<div class='vlz_titulo_seccion'>".$value."  {$boton} </div>
    			<div class='vlz_seccion_interna'>
    				".$temp."
    			</div>
			</div>
    	";
    }

    $adicionales = unserialize($cuidador->adicionales);

    $adicionales_extra_str = "";
    foreach ($adicionales_extra as $key => $value) {
    	if( $adicionales[$key]+0 == 0 ){ $adicionales[$key] = 0; }
    	$adicionales_extra_str .= "
    		<div class='vlz_celda_20'>
    			<label>".$value."</label>
    			<input type='number' min=0 data-minvalue=0 data-charset='num'  class='vlz_input' id='adicional_".$key."' name='adicional_".$key."' value='".$adicionales[$key]."' />
			</div>
    	";
    }

    $rutas = array(
        "corto" => "Cortas",
        "medio" => "Medias",
        "largo" => "Largas"
    );

    $transporte_sencillo_str = "";
	$temp = "";
	foreach ($rutas as $slug => $valor) {
		$temp .= "
    		<div class='vlz_celda_33'>
    			<label>".$valor."</label>
    			<input type='number' min=0 data-minvalue=0 data-charset='num'  class='vlz_input' id='transportacion_sencilla_".$slug."' name='transportacion_sencilla_".$slug."' value='".$adicionales['transportacion_sencilla'][$slug]."' />
			</div>
		";
	}
	$transporte_sencillo_str .= "
		<div class='vlz_celda_50'>
			<div class='vlz_titulo_seccion'>Transportación Sencilla</div>
			<div class='vlz_seccion_interna'>
    			".$temp."
    		</div>
		</div>
	";

    $transporte_redondo_str = "";
	$temp = "";
	foreach ($rutas as $slug => $valor) {
		$temp .= "
    		<div class='vlz_celda_33'>
    			<label>".$valor."</label>
    			<input type='number' class='vlz_input' min=0 data-minvalue=0 data-charset='num'  id='transportacion_redonda_".$slug."' name='transportacion_redonda_".$slug."' value='".$adicionales['transportacion_redonda'][$slug]."' />
			</div>
		";
	}
	$transporte_redondo_str .= "
		<div class='vlz_celda_50'>
			<div class='vlz_titulo_seccion'>Transportación Redonda</div>
			<div class='vlz_seccion_interna'>
    			".$temp."
    		</div>
		</div>
	";

	if( $status_servicios[ "hospedaje" ] == 'publish' ){
		$boton = "<input type='button' value='Activado' class='vlz_activador vlz_activado' id='status_hospedaje' > <input type='hidden' id='oculto_status_hospedaje' name='status_hospedaje' value='1' >";
	}else{
		$boton = "<input type='button' value='Desactivado' class='vlz_activador vlz_desactivado' id='status_hospedaje' > <input type='hidden' id='oculto_status_hospedaje' name='status_hospedaje' value='0' >";
	}

	// echo "<pre>";
	// 	print_r($status_servicios);
	// echo "</pre>";

	$stylos = kmimos_style( array("form_errores") );

	$this->FieldOutput .= $stylos."
		<style>
			.alertas{
				padding: 10px;
			    margin-bottom: 10px;
			    margin-top: 10px;
			    border: 1px solid transparent;
			 	border-radius: 4px;
			}
			.alertas-info{
				color: #31708f;
			    background-color: #d9edf7;
			    border-color: #bce8f1;
			}
			.alertas-success{
				color: #468847;
			    background-color: #dff0d8;
			    border-color: #d6e9c6;
			}
			.alertas-warning{
			    color: #c09853;
			    background-color: #fcf8e3;
			    border-color: #faebcc;
			}
			.alertas-error{
				color: #b94a48;
			    background-color: #f2dede;
			    border-color: #ebccd1;
			}
			.error{
				color: #b94a48;
			    background-color: #f2dede;
			    border-color: #ebccd1;

			}
    	</style>
    	<input type='hidden' name='user_id' value='{$user_id}'>
    	<div>
    		<div class='vlz_seccion'>
    			<div class='vlz_titulo_seccion'>Tamaños de Mascotas</div>
    			<div class='vlz_seccion_interna'>
    				<div class='vlz_celda_25 vlz_celda_25_x'>
	    				<strong>Pequeños</strong> (0.0 - 25.0 cm)
	    			</div>
    				<div class='vlz_celda_25 vlz_celda_25_x'>
						<strong>Medianos</strong> (25.0 - 58.0 cm)
	    			</div>
    				<div class='vlz_celda_25 vlz_celda_25_x'>
						<strong>Grandes</strong> (58.0 - 73.0 cm)
	    			</div>
    				<div class='vlz_celda_25 vlz_celda_25_x'>
						<strong>Gigantes</strong> (73.0 - 200.0 cm)
	    			</div>
    			</div>
    		</div>
    		<div class='vlz_seccion'>
    			<div class='vlz_titulo_seccion'>Hospedaje <!-- {$boton} --> </div>
    			<div class='vlz_seccion_interna' id='precios_hospedaje'>
    				".$hospedaje."
    			</div>
				<div class='no_error' id='error_hospedaje'>Debe llenar al menos uno de los campos</div>
    		</div>
    		
    		".$precios_adicionales."

    		<div class='vlz_seccion'>
    			<div class='vlz_seccion_interna'>
        			".$transporte_sencillo_str."
        			".$transporte_redondo_str."
    			</div>

    			<div class='vlz_titulo_seccion'>Servicos Extras</div>
    			<div class='vlz_seccion_interna'>
    				".$adicionales_extra_str."
    			</div>
    		</div>
    		
    	</div>

    	<script>
    		jQuery( '.vlz_activador' ).each(function( index ) {
			  	jQuery( this ).on('click', function(e){
			  		var status = jQuery('#oculto_'+e.target.id).val();
			  		if(status == 1){
			  			jQuery('#'+e.target.id).removeClass('vlz_activado');
			  			jQuery('#'+e.target.id).addClass('vlz_desactivado');
			  			jQuery('#'+e.target.id).attr('value', 'Desactivado');
			  			jQuery('#oculto_'+e.target.id).val(0);
			  		}else{
			  			jQuery('#'+e.target.id).addClass('vlz_activado');
			  			jQuery('#'+e.target.id).removeClass('vlz_desactivado');
			  			jQuery('#'+e.target.id).attr('value', 'Activado');
			  			jQuery('#oculto_'+e.target.id).val(1);
			  		}
			  	});
			});
			jQuery( document ).ready(function(){
				jQuery('#pfuaprofileform').submit(function(e){

					var z = 0;
					var t = [
						'pequenos',
						'medianos',
						'grandes',
						'gigantes'
					];

					jQuery.each(t, function( index, value ) {
						var temp = jQuery('#hospedaje_'+value).attr('value');
						if( temp == '' ){ temp = 0; }
						z += parseInt( temp );
					});

					if( z == 0 ){
						jQuery('#error_hospedaje').attr('class', 'error');
						jQuery('html, body').animate({ scrollTop: jQuery('#precios_hospedaje').offset().top-75 }, 2000);
						e.preventDefault();
					}else{
						jQuery('#error_hospedaje').attr('class', 'no_error');
					}
				});
			});
    	</script>
    ";

?>