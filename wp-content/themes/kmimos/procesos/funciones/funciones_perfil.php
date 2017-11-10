<?php
	if(!function_exists('construir_botones')){
	    function construir_botones($botones){
	    	$respuesta = "";
	    	foreach ($botones as $boton => $accion) {
	    		switch ($boton) {
	    			case 'ver':
	    				$respuesta .= '<a data-accion="ver/'.$accion.'" class="vlz_accion vlz_ver"> <i class="fa fa-info" aria-hidden="true"></i> Ver</a>';
    				break;
	    			case 'confirmar':
	    				$respuesta .= '<a data-accion="confirmar/'.$accion.'" class="vlz_accion vlz_confirmar"> <i class="fa fa-check" aria-hidden="true"></i> Confirmar </a>';
    				break;
	    			case 'cancelar':
	    				$respuesta .= '<a data-accion="cancelar/'.$accion.'" class="vlz_accion vlz_cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i> Cancelar</a>';
    				break;
	    			case 'modificar':
	    				$respuesta .= '<a data-accion="'.$accion.'" class="vlz_accion vlz_modificar"> <i class="fa fa-pencil" aria-hidden="true"></i> Modificar </a>';
    				break;
	    			case 'pdf':
	    				$respuesta .= '<a data-accion="'.$accion.'" class="vlz_accion vlz_pdf"> <i class="fa fa-download" aria-hidden="true"></i> ¿Com&oacute; pagar? </a>';
    				break;
	    			case 'valorar':
	    			    $respuesta .= '<a href="'.get_home_url().'/valorar/'.$accion.'" class="vlz_accion vlz_valorar"> <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Valorar </a>';
    				break;


	    			case 'ver_s':
	    				$respuesta .= '<a data-accion="'.$accion.'" class="vlz_accion vlz_ver"> <i class="fa fa-info" aria-hidden="true"></i> Ver</a>';
    				break;
	    			case 'confirmar_s':
	    				$respuesta .= '<a data-accion="confirmar/'.$accion.'" class="vlz_accion vlz_confirmar"> <i class="fa fa-check" aria-hidden="true"></i> Confirmar </a>';
    				break;
	    			case 'cancelar_s':
	    				$respuesta .= '<a data-accion="cancelar/'.$accion.'" class="vlz_accion vlz_cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i> Cancelar</a>';
    				break;
	    			
	    		}
	    	}
	    	return $respuesta;
	    }
	}

	if(!function_exists('construir_listado')){
	    function construir_listado($args = array()){
	        $table='';
	        $avatar_img = get_home_url()."/wp-content/themes/kmimos/images/noimg.png";
	        foreach($args as $reservas){
	        	if( count($reservas['reservas']) > 0 ){

	        		$table.='
	                	<h1 class="titulo">'.$reservas['titulo'].'</h1>
	                	<div class="vlz_tabla_box">
	                ';

	                foreach ($reservas['reservas'] as $reserva) {

	                	$cancelar = '';
	                	if( isset($reserva["acciones"]["cancelar"]) ){
	                		//$cancelar = '<a data-accion="'.get_home_url().'/wp-content/plugins/kmimos/'.$reserva["acciones"]["cancelar"].'" class="vlz_accion vlz_cancelar cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i></a>';
	                	}

	                	$botones = construir_botones($reserva["acciones"]);

	                	$vlz_tabla_inferior = "";

	                	$descuento = "";
	                	if( $reserva["desglose"]["descuento"]+0 > 0){
	                		$descuento = '
	                			<div class="item_desglose">
		                			<div>Descuento</div>
		                			<span>$'.number_format( $reserva["desglose"]["descuento"], 2, ',', '.' ).'</span>
		                		</div>
	                		';
	                	}

	                	if( isset($reserva["desglose"]["remaining"]) ){
		                	$remanente = '
		                		<div class="item_desglose vlz_bold">
		                			<div style="color: #6b1c9b;" >Monto Restante a Pagar en EFECTIVO al cuidador</div>
		                			<span style="color: #6b1c9b;">$'.number_format( ($reserva["desglose"]["remaining"]-$reserva["desglose"]["descuento"]), 2, ',', '.').'</span>
		                		</div>
		                	';
		                	$pago = '
		                		<div class="item_desglose">
		                			<div>Pagó</div>
		                			<span>$'.number_format( $reserva["desglose"]["deposit"], 2, ',', '.').'</span>
		                		</div>
		                	';
	                	}else{
	                		$remanente = '';
		                	$pago = '
		                		<div class="item_desglose">
		                			<div>Pagó</div>
		                			<span>$'.number_format( $reserva["desglose"]["subtotal"], 2, ',', '.').'</span>
		                		</div>
		                	';

	                	}

		                $table.='
		                <div class="vlz_tabla">
		                	<div class="vlz_img">
		                		<span style="background-image: url('.$reserva["foto"].');"></span>
		                	</div>
		                	<div class="vlz_tabla_superior">
			                	<div class="vlz_tabla_cuidador vlz_celda">
			                		<span>Servicio</span>
			                		<div><a href="'.get_home_url().'/reservar/'.$reserva["servicio_id"].'/">'.$reserva["servicio"].'</a></div>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_celda">
			                		<span>Fecha</span>
			                		<div>'.$reserva["inicio"].' <b> > </b> '.$reserva["fin"].'</div>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_interno">
			                		'.$cancelar.'
			                		<a class="ver_reserva_init">Ver Reserva</a>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_cerrar">
			                		<span>Reserva</span>
			                		<div>'.$reserva["id"].'</div>
			                	</div>
		                	</div>
	                		<i class="fa fa-times ver_reserva_init_closet" aria-hidden="true"></i>
		                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_fuera">
		                		<a class="ver_reserva_init_fuera">Ver Reserva</a>
		                	</div>
		                	<div class="vlz_tabla_inferior">
		                		
		                		<div class="desglose_reserva">
			                		<div class="item_desglose vlz_bold vlz_solo_movil">
			                			<div>RESERVA</div>
			                			<span>'.$reserva["id"].'</span>
			                		</div>
			                		<div class="item_desglose vlz_bold">
			                			<div>MÉTODO DE PAGO</div>
			                			<span>'.$reserva["desglose"]["tipo"].'</span>
			                		</div>
			                		'.$remanente.'
			                		'.$descuento.'
			                		'.$pago.'
		                		</div>
		                		<div class="total_reserva">
			                		<div class="item_desglose">
			                			<div>TOTAL</div>
			                			<span>$'.number_format( $reserva["desglose"]["total"], 2, ',', '.').'</span>
			                		</div>
		                		</div>

		                		<div class="ver_reserva_botones">
			                		'.$botones.'
		                		</div>
		                	</div>
		                </div>';
	                }

	                $table.='</div>';
	        	}
	        }

	        foreach($args as $reservas){
	        	if( count($reservas['solicitudes']) > 0 ){
	        		$table.='<h1 class="titulo titulo_pequenio">'.$reservas['titulo'].'</h1><div class="vlz_tabla_box">';
		                foreach ($reservas['solicitudes'] as $reserva) {

		                	$cancelar = '';
		                	if( isset($reserva["acciones"]["cancelar"]) ){
		                		//$cancelar = '<a data-accion="'.get_home_url().'/wp-content/plugins/kmimos/'.$reserva["acciones"]["cancelar"].'" class="vlz_accion vlz_cancelar cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i></a>';
		                	}

		                	$botones = construir_botones($reserva["acciones"]);

		                	$title_registro = "Cuidador seleccionado";

	                		$informacion = "
	                			<div class='info_solicitud'>
	                				<div class='info_titulo'>Importante</div>
	                				<ul>
	                					<li>
	                						<span>Dentro de las siguientes 12 horas recibir&aacute; una llamada o correo electr&oacute;nico por parte del Cuidador y/o de un asesor Kmimos para confirmar tu cita o brindarte soporte con este proceso.</span>
	                					</li>
	                					<li>
	                						<span>Tambi&eacute;n podr&aacute;s contactar al cuidador a partir de este momento, a los tel&eacute;fonos y/o correos mostrados arriba para acelerar el proceso si as&iacute; lo deseas.</span>
	                					</li>
	                					<li>
	                						<span>Para cualquier duda y/o comentario puedes contactar al staff Kmimos:</span>
	                					</li>
	                				</ul>
	                				<div class='datos_de_contacto'>
	                					<ul>
		                					<li>
		                						<span><img src='".getTema()."/images/new/icon/km-redes/icon-wsp.svg' style='' /></span> +52 (55) 6892 2182
		                					</li>
		                					<li>
		                						<span><img src='".getTema()."/images/new/icon/km-redes/icon-cel.svg' style='' /></span> (01) 800 056 4667
		                					</li>
		                					<li>
		                						<span><img src='".getTema()."/images/new/icon/km-redes/icon-mail.svg' style='height: 13px;' /></span> contactomex@kmimos.la
		                					</li>
		                				</ul>
	                				</div>
	                			</div>
	                		";
		                	if( $reserva["detalle"]["quien_soy"] == "DATOS DEL CLIENTE" ){
		                		$title_registro = "Cliente";
		                		$informacion = "";
		                	}

			                $table.='
			                <div class="vlz_tabla">
			                	<div class="vlz_img">
			                		<span style="background-image: url('.$reserva["foto"].');"></span>
			                	</div>
			                	<div class="vlz_tabla_superior">
				                	<div class="vlz_tabla_cuidador vlz_celda">
				                		<span>'.$title_registro.'</span>
				                		<div>'.$reserva["servicio"].'</div>
				                	</div>
				                	<div class="vlz_tabla_cuidador vlz_celda">
				                		<span>Fecha</span>
				                		<div style="text-transform: lowercase;" >'.$reserva["detalle"]["desde"].' > '.$reserva["detalle"]["hasta"].'</div>
				                	</div>
				                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_interno">
				                		'.$cancelar.'
				                		<a class="ver_reserva_init">Ver Solicitud</a>
				                	</div>
				                	<div class="vlz_tabla_cuidador vlz_cerrar">
				                		<span>Solicitud</span>
				                		<div>'.$reserva["id"].'</div>
				                	</div>
			                	</div>
		                		<i class="fa fa-times ver_reserva_init_closet" aria-hidden="true"></i>
			                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_fuera">
			                		<a class="ver_reserva_init_fuera">Ver Solicitud</a>
			                	</div>
			                	<div class="vlz_tabla_inferior">

			                		<div class="desglose_reserva desglose_sin_borde">
				                		<div class="desglose_columna">
				                			<div class="desglose_titulo">'.$reserva["detalle"]["quien_soy"].'</diV>
					                		<div class="item_desglose">
					                			<div>Nombre: </div>
					                			<span>'.$reserva["servicio"].'</span>
					                		</div>
					                		<div class="item_desglose">
					                			<div>Tel&eacute;fono: </div>
					                			<span>'.$reserva["detalle"]["telefono"].'</span>
					                		</div>
					                		<div class="item_desglose">
					                			<div>Correo: </div>
					                			<span>'.$reserva["detalle"]["correo"].'</span>
					                		</div>
				                		</div>
				                		<div class="desglose_columna">
				                			<div class="desglose_titulo">DATOS DE LA REUNI&Oacute;N</diV>
					                		<div class="item_desglose">
					                			<div>Fecha: </div>
					                			<span>'.$reserva["inicio"].'</span>
					                		</div>
					                		<div class="item_desglose">
					                			<div>Hora: </div>
					                			<span>'.$reserva["fin"].'</span>
					                		</div>
					                		<div class="item_desglose">
					                			<div>Lugar: </div>
					                			<span>'.$reserva["detalle"]["donde"].'</span>
					                		</div>
				                		</div>
			                		</div>

			                		'.$informacion.'

			                		<div class="ver_reserva_botones">
				                		'.$botones.'
			                		</div>
			                	</div>
			                </div>';
		                }

	                $table.='</div>';
	        	}
	        }

	        return $table;
	    }
	}
?>