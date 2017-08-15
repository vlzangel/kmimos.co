<?php

	function vlz_servicios($adicionales){
		$r = ""; $adiestramiento = false;

		$r .= '<span class="tooltip icono-servicios"><span class="tooltiptext">Hospedaje</span><i class="icon-hospedaje"></i></span>';

		$adicionales = unserialize($adicionales);
		
		if( $adicionales != "" ){
			if( count($adicionales) > 0 ){
				foreach($adicionales as $key => $value){
					switch ($key) {
						case 'guarderia':
							$r .= '<span class="tooltip icono-servicios"><span class="tooltiptext">Guardería</span><i class="icon-guarderia"></i></span>';
						break;
						case 'adiestramiento_basico':
							$adiestramiento = true;
						break;
						case 'adiestramiento_intermedio':
							$adiestramiento = true;
						break;
						case 'adiestramiento_avanzado':
							$adiestramiento = true;
						break;
						case 'corte':
							$r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Corte de pelo y uñas</span><i class="icon-peluqueria"></i></div>';
						break;
						case 'bano':
							$r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Baño y secado</span><i class="icon-bano"></i></div>';
						break;
						case 'transportacion_sencilla':
							$r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Transporte Sencillo</span><i class="icon-transporte"></i></div>';
						break;
						case 'transportacion_redonda':
							$r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Transporte Redondo</span><i class="icon-transporte2"></i></div>';
						break;
						case 'visita_al_veterinario':
							$r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Visita al Veterinario</span><i class="icon-veterinario"></i></div>';
						break;
						case 'limpieza_dental':
							$r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Limpieza dental</span><i class="icon-limpieza"></i></div>';
						break;
						case 'acupuntura':
							$r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Acupuntura</span><i class="icon-acupuntura"></i></div>';
						break;
					}
				}
			}
		}
		if($adiestramiento){
			$r .= '<div class="tooltip icono-servicios" ><span class="tooltiptext">Adiestramiento de Obediencia</span><i class="icon-adiestramiento"></i></div>';
		}
		return $r;
	}

	function vlz_sql_busqueda($param, $pagina){

		// echo "<pre>";
		// 	print_r($param);
		// echo "</pre>";

		$condiciones = "";
		if( isset($param["servicios"]) ){
			foreach ($param["servicios"] as $key => $value) {
				if( $value != "hospedaje" ){
					$condiciones .= " AND adicionales LIKE '%".$value."%'";
				}
			}
		}

		if( isset($param['tamanos']) ){
			foreach ($param['tamanos'] as $key => $value) {
				$condiciones .= " AND ( tamanos_aceptados LIKE '%\"".$value."\";i:1%' || tamanos_aceptados LIKE '%\"".$value."\";s:1:\"1\"%' ) ";
			}
		}

		if( isset($param['n']) ){
			if( $param['n'] != "" ){
				$name_inner = "INNER JOIN wp_posts AS cuidador ON ( cuidadores.id_post = cuidador.ID )";
				$condiciones .= " AND cuidador.post_title LIKE '%".$param['n']."%' ";
			}
		}else{
			$name_inner = "";
		}

		if( $param['rangos'][0] != "" ){
			$condiciones .= " AND (hospedaje_desde*1.2) >= '".$param['rangos'][0]."' ";
		}

		if( $param['rangos'][1] != "" ){
			$condiciones .= " AND (hospedaje_desde*1.2) <= '".$param['rangos'][1]."' ";
		}

		if( $param['rangos'][2] != "" ){
			$anio_1 = date("Y")-$param['rangos'][2];
			$condiciones .= " AND experiencia <= '".$anio_1."' ";
		}

		if( $param['rangos'][3] != "" ){
			$anio_2 = date("Y")-$param['rangos'][3];
			$condiciones .= " AND experiencia >= '".$anio_2."' ";
		}

		if( $param['rangos'][4] != "" ){
			$condiciones .= " AND rating >= '".$param['rangos'][4]."' ";
		}

		if( $param['rangos'][5] != "" ){
			$condiciones .= " AND rating <= '".$param['rangos'][5]."' ";
		}

		// Ordenamiento

		$orderby = $param['orderby'];

		if( $orderby == "rating_desc" ){
			$orderby = "rating DESC, valoraciones DESC";
		}

		if( $orderby == "rating_asc" ){
			$orderby = "rating ASC, valoraciones ASC";
		}

		if( $orderby == "distance_asc" ){
			$orderby = "DISTANCIA ASC";
		}

		if( $orderby == "distance_desc" ){
			$orderby = "DISTANCIA DESC";
		}

		if( $orderby == "price_asc" ){
			$orderby = "hospedaje_desde ASC";
		}

		if( $orderby == "price_desc" ){
			$orderby = "hospedaje_desde DESC";
		}

		if( $orderby == "experience_asc" ){
			$orderby = "experiencia ASC";
		}

		if( $orderby == "experience_desc" ){
			$orderby = "experiencia DESC";
		}

		if( $param['tipo_busqueda'] == "otra-localidad" ){

			if( $param['estados'] != "" ){

				if($param['municipios'] != ""){
					$municipio = "AND ubi.municipios LIKE '%=".$param['municipios']."=%'";
				}

				$DISTANCIA = ",
					( 6371 * 
						acos(
					    	cos(
					    		radians({$param['otra_latitud']})
					    	) * 
					    	cos(
					    		radians(latitud)
					    	) * 
					    	cos(
					    		radians(longitud) - 
					    		radians({$param['otra_longitud']})
					    	) + 
					    	sin(
					    		radians({$param['otra_latitud']})
					    	) * 
					    	sin(
					    		radians(latitud)
					    	)
					    )
				    ) as DISTANCIA 
				";

				$ubicaciones_inner = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
				$ubicaciones_filtro = "
					AND (
						(
							ubi.estado LIKE '%=".$param['estados']."=%'
							".$municipio."
						) OR (
							( 6371 * 
								acos(
							    	cos(
							    		radians({$param['otra_latitud']})
							    	) * 
							    	cos(
							    		radians(latitud)
							    	) * 
							    	cos(
							    		radians(longitud) - 
							    		radians({$param['otra_longitud']})
							    	) + 
							    	sin(
							    		radians({$param['otra_latitud']})
							    	) * 
							    	sin(
							    		radians(latitud)
							    	)
							    )
						    ) <= 100
						)
					)";
				if( $orderby == "" ){
					$orderby = "DISTANCIA ASC";
				}

			}else{
				$ubicaciones_inner = "";
				if( $orderby == "" ){
					$orderby = "rating DESC, valoraciones DESC";
				}
			}

			// if( $param['otra_latitud'] != "" && $param['otra_longitud'] != "" ){
			// 	$DISTANCIA = ",
			// 		( 6371 * 
			// 			acos(
			// 		    	cos(
			// 		    		radians({$param['otra_latitud']})
			// 		    	) * 
			// 		    	cos(
			// 		    		radians(latitud)
			// 		    	) * 
			// 		    	cos(
			// 		    		radians(longitud) - 
			// 		    		radians({$param['otra_longitud']})
			// 		    	) + 
			// 		    	sin(
			// 		    		radians({$param['otra_latitud']})
			// 		    	) * 
			// 		    	sin(
			// 		    		radians(latitud)
			// 		    	)
			// 		    )
			// 	    ) as DISTANCIA 
			// 	";

			// 	if( ($param['otra_distancia']+0) < 50 ){
			// 		$param['otra_distancia'] = 100;
			// 	}
					
			// 	if( ($param['otra_distancia']+0 > 0) && ($tipo_busqueda != 'otra-localidad') ){$FILTRO_UBICACION = "HAVING DISTANCIA < ".($param['otra_distancia']+0);}

			// }else{
			// 	$DISTANCIA = "";
			// 	$FILTRO_UBICACION = "";
			// }

			

			// if( $param['estados'] != "" && $param['municipios'] != "" ){
			// 	$ubicaciones_filtro = "
			// 		AND (
			// 			(
			// 				ubi.estado LIKE '%=".$param['estados']."=%' AND 
			// 				ubi.municipios LIKE '%=".$param['municipios']."=%'
			// 			) OR (
			// 				( 6371 * 
			// 					acos(
			// 				    	cos(
			// 				    		radians({$param['latitud']})
			// 				    	) * 
			// 				    	cos(
			// 				    		radians(latitud)
			// 				    	) * 
			// 				    	cos(
			// 				    		radians(longitud) - 
			// 				    		radians({$param['longitud']})
			// 				    	) + 
			// 				    	sin(
			// 				    		radians({$param['latitud']})
			// 				    	) * 
			// 				    	sin(
			// 				    		radians(latitud)
			// 				    	)
			// 				    )
			// 			    ) <= 100
			// 			)
			// 		)";

			// }else{
				
			// 	if( $param['estados'] != "" ){
			// 		$ubicaciones_filtro = "AND ( ubi.estado LIKE '%=".$param['estados']."=%' )";
			// 	}

			// 	if( $param['municipios'] != "" ){
			// 		$ubicaciones_filtro = "AND ( ubi.municipios LIKE '%=".$param['municipios']."=%' )";
			// 	}

			// }

		}else{

			if( $param['latitud'] != "" && $param['longitud'] != "" ){

				$DISTANCIA = ",
					( 6371 * 
						acos(
					    	cos(
					    		radians({$param['latitud']})
					    	) * 
					    	cos(
					    		radians(latitud)
					    	) * 
					    	cos(
					    		radians(longitud) - 
					    		radians({$param['longitud']})
					    	) + 
					    	sin(
					    		radians({$param['latitud']})
					    	) * 
					    	sin(
					    		radians(latitud)
					    	)
					    )
				    ) as DISTANCIA 
				";

				$FILTRO_UBICACION = "HAVING DISTANCIA < ".($param['distancia']+0);

			}else{
				$DISTANCIA = "";
				$FILTRO_UBICACION = "";
			}

			if( $orderby == "" ){
				$orderby = "DISTANCIA ASC";
			}

		}

		
		$sql = "
			SELECT 
				SQL_CALC_FOUND_ROWS  
				cuidadores.*
				{$DISTANCIA}
			FROM 
				cuidadores
				{$ubicaciones_inner}
				{$name_inner}
			WHERE 
				activo = '1' {$condiciones}
				{$ubicaciones_filtro}
			{$FILTRO_UBICACION}
			ORDER BY {$orderby}
			LIMIT {$pagina}, 15
		";

		return $sql;
	}

?>