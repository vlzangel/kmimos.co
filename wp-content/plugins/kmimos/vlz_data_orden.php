<?php
	
	extract($_GET);

    $email_admin = $info["email"];

    global $wpdb;

	$datos_generales = kmimos_datos_generales_desglose($o, true);

	$detalles_cliente = $datos_generales["cliente"];
	$detalles_cuidador = $datos_generales["cuidador"];
	$detalles_mascotas = $datos_generales["mascotas"];

	$cliente_email  = $datos_generales["cliente_email"];
	$cuidador_email = $datos_generales["cuidador_email"];

	$booking = new WC_Booking($datos_generales["booking"]);
    $order = new WC_Order($datos_generales["orden"]);

    $reserva_id = $datos_generales["booking"];

    $nom_cliente  = $datos_generales["nombre_cliente"];
    $nom_cuidador = $datos_generales["nombre_cuidador"];

	/* Sugeridos */

		$cuidador = $datos_generales["cuidador_obj"];

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
	            activo = 1
	        ORDER BY DISTANCIA ASC
	        LIMIT 0, 4
	    ";

	    $sugeridos = $wpdb->get_results($sql);

	    $str_sugeridos = "";

	    foreach ($sugeridos as $key => $cuidador) {
	        $name_photo = get_user_meta($cuidador->user_id, "name_photo", true);
			$cuidador_id = $cuidador->id;
			$img = kmimos_get_foto($cuidador->user_id);
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
                <li align="justify" style="padding-bottom: 10px; font-size: 12px;">En caso de que alguna de estas opciones no se adecúe a tus necesidades, por favor ingresa a <strong><a style="text-decoration: none; color: #3d68b9;" href="'.get_home_url().'/busqueda">'.$info["titulo"].'</a></strong> en donde podrás encontrar cientos de cuidadores que seguro te encantarán.</li>
                <li align="justify" style="font-size: 12px;">Para asistencia personalizada por favor márcanos a nuestros números. '.$info["telefono"].'.</li>
            </ol>
	    ';

?>