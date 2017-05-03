<?php
	
	extract($_GET);

    global $wpdb;

	$ID_ORDEN = $o;
	$ID_RESERVA   = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$o} AND post_type = 'wc_booking'");

    $booking = new WC_Booking($ID_RESERVA);
    $order = new WC_Order($ID_ORDEN);

    $url = get_home_url();

    $email_admin = "contactomex@kmimos.la";

	/* Orden y Reserva  */

		$reserva_id = $ID_RESERVA;
		$orden_id   = $ID_ORDEN;

	    $metas_orden 	= get_post_meta($orden_id);
	    $metas_reserva 	= get_post_meta( $reserva_id );


	/* Producto  */

		$producto 		= $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '".$metas_reserva['_booking_product_id'][0]."'");
		$metas_producto = get_post_meta( $producto->ID );

		$tipo_servicio = explode("-", $producto->post_title);
		$tipo_servicio = $tipo_servicio[0];

	/* Cuidador  */

		$cuidador_post 	= $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '".$producto->post_parent."'");
		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = '".$producto->post_author."'");


		$metas_cuidador = get_user_meta($cuidador->user_id);
		$telf = $metas_cuidador["user_phone"][0];
		$dir = $metas_cuidador["user_address"][0];
		$dir = $cuidador->direccion;
		$email_cuidador = $cuidador->email;

		if( $telf == "" ){
			$telf = $metas_cuidador["user_mobile"][0];
		}
		if( $telf == "" ){
			$telf = "No registrado";
		}

		if( $dir == "" || $dir == 0 ){
			$dir = "No registrada";
		}

		$detalles_cuidador = '
			<p style="color:#557da1;font-size: 16px;font-weight: 600; font-size: 16px;">Datos Cuidador</p>
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td style="width: 70px;" valign="top"> <strong>Nombre:</strong> </td>
					<td valign="top">'.$cuidador_post->post_title.'</td>
				</tr>
				<tr>
					<td valign="top"> <strong>Teléfono:</strong> </td>
					<td valign="top">'.$telf.'</td>
				</tr>
				<tr>
					<td valign="top"> <strong>Correo:</strong> </td>
					<td valign="top">'.$email_cuidador.'</td>
				</tr>
				<tr>
					<td valign="top"> <strong>Dirección: </strong> </td>
					<td valign="top"> '.$dir.'</td>
				</tr>
			</table>
		';

	/* Cliente */

		$cliente = $metas_orden["_customer_user"][0];

		if( $cliente == 0 ){
			$temp_email = $metas_orden["_billing_email"][0];
			$cliente = get_var("SELECT ID FROM wp_users WHERE user_email = '{$temp_email}'");
		}

		$metas_cliente = get_user_meta($cliente);

		$nombre = $metas_cliente["first_name"][0];
		$apellido = $metas_cliente["last_name"][0];

		$nom = $nombre." ".$apellido;
		$dir = $metas_cliente["user_address"][0];
		$telf = $metas_cliente["user_phone"][0];

		if( $telf == "" ){
			$telf = $metas_cliente["user_mobile"][0];
		}
		if( $telf == "" ){
			$telf = "No registrado";
		}

		if( $dir == "" || $dir == 0 ){
			$dir = "No registrada";
		}

		$user = get_user_by( 'id', $cliente );

		$email_cliente = $user->data->user_email;

		$detalles_cliente = '
			<p align="justify" style="color:#557da1; font-size: 16px;font-weight: 600;">Datos Cliente</p>
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td style="width: 70px;" valign="top"> <strong>Nombre:</strong> </td>
					<td valign="top">'.$nom.'</td>
				</tr>
				<tr>
					<td valign="top"> <strong>Teléfono:</strong> </td>
					<td valign="top">'.$telf.'</td>
				</tr>
				<tr>
					<td valign="top"> <strong>Correo:</strong> </td>
					<td valign="top">'.$user->data->user_email.'</td>
				</tr>
				<tr>
					<td valign="top"> <strong>Dirección: </strong> </td>
					<td valign="top"> '.$dir.'</td>
				</tr>
			</table>
		';

	/*	Mascotas	*/

		$mascotas = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_author = '".$cliente."' AND post_type='pets'");
		$detalles_mascotas = "";

		/*
		$detalles_mascotas .= '
			<h2 style="color: #557da1; font-size: 16px;">Detalles de las mascotas: </h2>
			<table style="width:100%" cellspacing=0 cellpadding=0>
				<tr>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Nombre</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Raza</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Edad</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Tamaño</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Comportamiento</strong> </th> 
				</tr>';
		*/
		$detalles_mascotas .= '
			<h2 style="color: #557da1; font-size: 16px;">Detalles de las mascotas: </h2>
			<table style="width:100%" cellspacing=0 cellpadding=0>
				<tr>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Nombre</strong> </th>
					<th style="padding: 3px; background: #00d2b7;"> <strong>Detalles</strong> </th>
				</tr>';


		$comportamientos_array = array(
			"pet_sociable" 			 => "Sociables",
			"pet_sociable2" 		 => "No sociables",
			"aggressive_with_pets"   => "Agresivos con perros",
			"aggressive_with_humans" => "Agresivos con humanos",
		);
		$tamanos_array = array(
			"Pequeño",
			"Mediano",
			"Grande",
			"Gigante"
		);
		if( count($mascotas) > 0 ){
			foreach ($mascotas as $key => $mascota) {
				$data_mascota = get_post_meta($mascota->ID);

				$temp = array();
				foreach ($data_mascota as $key => $value) {

					switch ($key) {
						case 'pet_sociable':
							if( $value[0] == 1 ){
								$temp[] = "Sociable";
							}else{
								$temp[] = "No sociable";
							}
						break;
						case 'aggressive_with_pets':
							if( $value[0] == 1 ){
								$temp[] = "Agresivo con perros";
							}
						break;
						case 'aggressive_with_humans':
							if( $value[0] == 1 ){
								$temp[] = "Agresivo con humanos";
							}
						break;
					}

				}

				$anio = explode("-", $data_mascota['birthdate_pet'][0]);
				$edad = date("Y")-$anio[0];

				$raza = $wpdb->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet'][0]);
				/*
				$detalles_mascotas .= '
					<tr>
						<td style="border-bottom: solid 1px #00d2b7; padding: 3px;" valign="top"> '.$data_mascota['name_pet'][0].'</td>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$raza.'</td>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$edad.' año(s)</td>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$tamanos_array[ $data_mascota['size_pet'][0] ].'</td>
						<td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.implode("<br>", $temp).'</td>
					</tr>
				';
				*/

				$detalles_mascotas .= '
					<tr>
						<td style="border-bottom: solid 1px #00d2b7; padding: 3px;" valign="top"> '.$data_mascota['name_pet'][0].'</td>
						<td style="padding: 5px; border-bottom: solid 1px #00d2b7;" valign="top">
							<strong>Raza:</strong> '.$raza.'<br>
							<strong>Edad:</strong> '.$edad.' año(s)<br>
							<strong>Tamaño:</strong> '.$tamanos_array[ $data_mascota['size_pet'][0] ].'<br>
							<strong>Comportamiento:</strong> '.implode("<br>", $temp).'<br>
						</td>
					</tr>
				';
			}
		}else{
			/*
			$detalles_mascotas .= '
				<tr>
					<td colspan="5">No tiene mascotas registradas.</td>
				</tr>
			';
			*/
			$detalles_mascotas .= '
				<tr>
					<td colspan="2">No tiene mascotas registradas.</td>
				</tr>
			';
		}
		$detalles_mascotas .= '</table>';

	/* Sugeridos */

		$sql = "
	        SELECT 
	            DISTINCT id,
	            ROUND ( ( 6371 * 
	                acos(
	                    cos(
	                        radians({$cuidador->latitud})
	                    ) * 
	                    cos(
	                        radians(latitud)
	                    ) * 
	                    cos(
	                        radians(longitud) - 
	                        radians({$cuidador->longitud})
	                    ) + 
	                    sin(
	                        radians({$cuidador->latitud})
	                    ) * 
	                    sin(
	                        radians(latitud)
	                    )
	                )
	            ), 2 ) as DISTANCIA,
	            id_post,
	            user_id,
	            hospedaje_desde
	        FROM 
	            cuidadores
	        WHERE
	            id_post != $cuidador->id_post AND 
	            portada = 1 AND 
	            activo = 1
	        ORDER BY DISTANCIA ASC
	        LIMIT 0, 4
	    ";

	    $sugeridos = $wpdb->get_results($sql);

	    $str_sugeridos = "";

	    foreach ($sugeridos as $key => $cuidador) {

	        $name_photo = get_user_meta($cuidador->user_id, "name_photo", true);
			$cuidador_id = $cuidador->id;

			if( empty($name_photo)  ){ $name_photo = "0"; }
			if( file_exists("../../uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}") ){
				$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}";
			}elseif( file_exists("../../uploads/cuidadores/avatares/".$cuidador_id."/0.jpg") ){
				$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg";
			}else{
				$img = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
			}

	        $post = get_post($cuidador->id_post);

	        $str_sugeridos_img .= '
	        	<div style="display: inline-block; width: 49%; text-align: center; min-width: 239px;">
                    <a href="'.get_home_url().'/petsitters/'.$post->post_name.'/" target="_blank" style="display: block; margin: 5px; text-decoration: none;">
                        <div style="background: #e4e4e4; border: solid 1px #CCC;">
                            <img src="'.$img.'" height="130" style="max-width: 100%; max-height: 100%; padding: 3px;" />
                        </div>
                        <div style="
                            text-align: left;
                            font-size: 16px;
                            font-weight: 600;
                            color: #00d2b7;
                            padding: 5px;
                            border: solid 1px #CCC;
                            border-top: 0;
                            border-bottom: 0;">
                            '.$post->post_title.'
                        </div>
                        <div style="
                            text-align: right;
                            font-size: 13px;
                            font-weight: 600;
                            color: #7d7d7d;
                            padding: 0px 5px 10px;
                            border: solid 1px #CCC;
                            border-top: 0;">
                            Hospedaje desde<br>
                            MXN $'.($cuidador->hospedaje_desde*1.2).'
                        </div>
                    </a>
                </div>
	        ';
	        
	    }

	    $lista_cercanos = '
	    	<ol class="lista" style="padding-left: 10px;"> 
                <li align="justify" style="font-size: 12px;">
                    Revisa estas recomendaciones y pícale a cualquiera de ellas para ver más detalles sobre su perfil.
                    <div style="overflow: hidden; text-align: center; margin: 0px auto; max-width: 600px;">'.$str_sugeridos_img.'</div>
                </li>
                <li align="justify" style="padding-bottom: 10px; font-size: 12px;">En caso de que alguna de estas opciones no se adecúe a tus necesidades, por favor ingresa a <strong><a style="text-decoration: none; color: #3d68b9;" href="'.get_home_url().'/busqueda">Kmimos México</a></strong> en donde podrás encontrar cientos de cuidadores que seguro te encantarán.</li>
                <li align="justify" style="font-size: 12px;">Para asistencia personalizada por favor márcanos a nuestros números. +52 (55) 1791.4931.</li>
            </ol>
	    ';

?>