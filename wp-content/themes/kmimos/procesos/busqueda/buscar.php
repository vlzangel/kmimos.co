<?php
	session_start();
	include(realpath(__DIR__."/../../../../../vlz_config.php"));
	include(realpath(__DIR__."/../funciones/db.php"));

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn); 

	$ubicaciones_inner = '';
	$nombre_inner = '';
	$ubicaciones_filtro = "";
	$latitud = (isset($latitud))? $latitud: "";
	$longitud = (isset($longitud))? $longitud: "";

	// Ordenar busqueda 
	if( isset($_GET['o']) ){
		$data = [];
		if( $_SESSION['busqueda'] != '' ){
			$data = unserialize($_SESSION['busqueda']);
			$data['orderby'] = $_GET['o'];
			$_POST = $data;
		}
	}

	extract($_POST);

	$condiciones = "";

    /* Filtros por fechas */
	    if( isset($servicios) ){

	    	$servicios_extras = array(
		        "hospedaje",
		    	"guarderia",
		    	"paseos",
		    	"adiestramiento"
		    );

	    	$servicios_buscados = "";
			foreach ($servicios as $key => $value) {

				if( in_array($value, $servicios_extras) ){ 
					if( $servicios_buscados == "" ){
						$servicios_buscados .= " cupos.tipo LIKE '%{$value}%' ";
					}else{
						$servicios_buscados .= " OR cupos.tipo LIKE '%{$value}%' ";
					}
				}
				
				if( $value != "hospedaje" ){
					$condiciones .= " AND adicionales LIKE '%".$value."%'";

					if( in_array($value, $servicios_extras) ){ 
						if( strpos($value,'adiestramiento') === false){
							$condiciones .= ' AND adicionales LIKE \'%status_'.$value.'";s:1:"1%\'';
						}else{
							$condiciones .= 'AND (';
							$condiciones .= ' 	adicionales LIKE \'%status_adiestramiento_basico";s:1:"1%\' 		OR ';
							$condiciones .= ' 	adicionales LIKE \'%status_adiestramiento_intermedio";s:1:"1%\' 	OR ';
							$condiciones .= ' 	adicionales LIKE \'%status_adiestramiento_avanzado";s:1:"1%\' 			';
							$condiciones .= ')';

						}
					}

				}
			}
	    	
	    	if( $servicios_buscados != "" ){
	    		$servicios_buscados = "( ".$servicios_buscados." ) AND";
	    	}
		}

		if( isset($checkin)  && $checkin  != '' && isset($checkout) && $checkout != '' ){ 

			$checkin = date("Y-m-d", strtotime( str_replace("/", "-", $checkin) ) );
			$checkout = date("Y-m-d", strtotime( str_replace("/", "-", $checkout) ) );

	    	$condiciones .= "
	    		AND ( 
	    			SELECT 
	    				count(*) 
	    			FROM 
	    				cupos 
	    			WHERE 
	    				cupos.cuidador = cuidadores.user_id AND
	    				{$servicios_buscados} 
	    				cupos.fecha >= '{$checkin}' AND 
	    				cupos.fecha <= '{$checkout}' AND (
	    					cupos.full = 1 OR 
	    					cupos.no_disponible = 1
	    				) 
	    		) = 0"; 
	   	}


    /* Fin Filtros por fechas */

    /* Filtros por servicios y tamaños */
	  
	    if( isset($tamanos) ){
	    	foreach ($tamanos as $key => $value) {
	     		$condiciones .= " AND ( tamanos_aceptados LIKE '%\"".$value."\";i:1%' || tamanos_aceptados LIKE '%\"".$value."\";s:1:\"1\"%' ) "; 
	     	} 
	    }
    /* Fin Filtros por servicios y tamaños */

    /* Filtro nombre  */
	    if( isset($nombre) ){ 
	    	if( $nombre != "" ){ 
	    		$nombre_inner = "INNER JOIN wp_posts AS nom ON ( nom.ID = cuidadores.id_post)";
	    		$condiciones .= " AND nom.post_title LIKE '%".$nombre."%' "; 
	    	} 
	   	}
    /* Fin Filtro nombre */

    /* Filtros por rangos */
    if( isset($rangos) ){
	    if( $rangos[0] != "" ){ $condiciones .= " AND (hospedaje_desde*1.2) >= '".$rangos[0]."' "; }
	    if( $rangos[1] != "" ){ $condiciones .= " AND (hospedaje_desde*1.2) <= '".$rangos[1]."' "; }
	    if( $rangos[2] != "" ){ $anio_1 = date("Y")-$rangos[2]; $condiciones .= " AND experiencia <= '".$anio_1."' "; }
	    if( $rangos[3] != "" ){ $anio_2 = date("Y")-$rangos[3]; $condiciones .= " AND experiencia >= '".$anio_2."' "; }
	    if( $rangos[4] != "" ){ $condiciones .= " AND rating >= '".$rangos[4]."' "; }
	    if( $rangos[5] != "" ){ $condiciones .= " AND rating <= '".$rangos[5]."' "; }
	}
    /* Fin Filtros por rangos */

    /* Ordenamientos */
    	$orderby = ( isset($orderby) )? $orderby : 'rating_desc' ;
	    switch ($orderby) {
	    	case 'rating_desc':
	    		$orderby = "rating DESC, valoraciones DESC";
	    	break;
	    	case 'rating_asc':
	    		$orderby = "rating ASC, valoraciones ASC";
	    	break;
	    	case 'distance_asc':
	    		$orderby = "DISTANCIA ASC";
	    	break;
	    	case 'distance_desc':
	    		$orderby = "DISTANCIA DESC";
	    	break;
	    	case 'price_asc':
	    		$orderby = "hospedaje_desde ASC";
	    	break;
	    	case 'price_desc':
	    		$orderby = "hospedaje_desde DESC";
	    	break;
	    	case 'experience_asc':
	    		$orderby = "experiencia ASC";
	    	break;
	    	case 'experience_desc':
	    		$orderby = "experiencia DESC";
	    	break;
	    }
    /* Fin Ordenamientos */

    /* Filtro de busqueda */
    	$ubicacion = explode("_", $ubicacion);
    	$estados = (count($ubicacion)>0)? $ubicacion[0] : '';
    	$municipios = (count($ubicacion)>1)? $ubicacion[1]: '';
	    
	    if( 
	    	// $tipo_busqueda == "otra-localidad" && 
	    	$estados != "" && 
	    	$municipios != "" 
	    ){
	       /* $coordenadas 		= unserialize( $db->get_var("SELECT valor FROM kmimos_opciones WHERE clave = 'municipio_{$municipios}' ") );
	        $latitud  			= $coordenadas["referencia"]->lat;
	        $longitud 			= $coordenadas["referencia"]->lng;
	        $distancia 			= calcular_rango_de_busqueda($coordenadas["norte"], $coordenadas["sur"]);
	        $ubicacion 			= " ubi.estado LIKE '%={$estados}=%' AND ubi.municipios LIKE '%={$municipios}=%' ";
	        $calculo_distancia 	= "( 6371 * acos( cos( radians({$latitud}) ) * cos( radians(latitud) ) * cos( radians(longitud) - radians({$longitud}) ) + sin( radians({$latitud}) ) * sin( radians(latitud) ) ) )";
	        $DISTANCIA 			= ", {$calculo_distancia} as DISTANCIA";
	        $FILTRO_UBICACION 	= "HAVING DISTANCIA < ".($distancia+0);
	        $ubicaciones_inner  = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
	        $ubicaciones_filtro = "AND ( ( $ubicacion ) OR ( {$calculo_distancia} <= ".($distancia+0)." ) )"; */  


            $ubicaciones_inner = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
            $ubicaciones_filtro = "AND ( ubi.estado LIKE '%=".$estados."=%' AND ubi.municipios LIKE '%=".$municipios."=%'  )";    

	    }else{ 
	        if( 
	        	// $tipo_busqueda == "otra-localidad" && 
	        	$estados != "" 
	        ){
	            $ubicaciones_inner = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
	            $ubicaciones_filtro = "AND ( ubi.estado LIKE '%=".$estados."=%' )";
	        }else{
	            if( 
	            	// $tipo_busqueda == "mi-ubicacion" && 
	            	$latitud != "" && 
	            	$longitud != "" 
	            ){
	       			$calculo_distancia 	= "( 6371 * acos( cos( radians({$latitud}) ) * cos( radians(latitud) ) * cos( radians(longitud) - radians({$longitud}) ) + sin( radians({$latitud}) ) * sin( radians(latitud) ) ) )";
	                $DISTANCIA 			= ", {$calculo_distancia} as DISTANCIA";
	                $FILTRO_UBICACION = "HAVING DISTANCIA < 500";
	                if( $orderby == "" ){ $orderby = "DISTANCIA ASC"; }
	            }else{
	                $DISTANCIA = "";
	                $FILTRO_UBICACION = "";
	            }
	        }
	    }
    /* Fin Filtro de busqueda */

    /* Filtro predeterminado */
    	if( $orderby == "" ){ $orderby = "rating DESC, valoraciones DESC"; }
    /* Fin Filtro predeterminado */

    $home = $db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'");

    /* SQL cuidadores */

	    $sql = "
	    SELECT 
	        cuidadores.id,
	        cuidadores.id_post,
	        cuidadores.user_id,
	        cuidadores.longitud,
	        cuidadores.latitud,
	        cuidadores.adicionales,
	        (cuidadores.hospedaje_desde*1.2) AS precio,
	        cuidadores.experiencia,
	        cuidadores.valoraciones,
	        post_cuidador.post_name AS slug,
	        post_cuidador.post_title AS titulo
	        {$DISTANCIA}
	    FROM 
	        cuidadores 
	    INNER JOIN wp_posts AS post_cuidador ON ( cuidadores.id_post = post_cuidador.ID )
	    	{$ubicaciones_inner}
	    	{$nombre_inner}
	    WHERE 
	        activo = '1' and cuidadores.hospedaje_desde >= 1 {$condiciones} {$ubicaciones_filtro} {$FILTRO_UBICACION}
	    ORDER BY {$orderby}";

    /* FIN SQL cuidadores */

    $cuidadores = $db->get_results($sql);

    $pines = array(); $pines_ubicados = array();
    if( $cuidadores != false ){
	
		$rad = pi() / 180;
		$grados = 0;
		$longitud_init = 0.005;

		foreach ($cuidadores as $key => $cuidador) {
			$anios_exp = $cuidador->experiencia;
			if( $anios_exp > 1900 ){
				$anios_exp = date("Y")-$anios_exp;
			}
			$url = $home."/petsitters/".$cuidador->slug;

			$coord = $cuidador->latitud."_".$cuidador->longitud;
	    	if( array_key_exists($coord, $pines_ubicados) !== false ){
				$cuidador->latitud = $cuidador->latitud + $longitud_init*cos($grados*$rad);
				$cuidador->longitud = $cuidador->longitud + $longitud_init*sin($grados*$rad);
	    	}else{
	    		$pines_ubicados[$coord] = 0;
	    	}

	    	$grados += 20;

	    	if( $grados > 360 ){
	    		$longitud_init +=  0.002;
	    		$grados = 0;
	    	}

			$pines[] = array(
				"ID"   => $cuidador->id,
				"post_id"   => $cuidador->id_post,
				"user" => $cuidador->user_id,
				"lat"  => $cuidador->latitud,
				"lng"  => $cuidador->longitud,
				"nom"  => utf8_encode($cuidador->titulo),
				"url"  => $url,
				"exp"  => $anios_exp,
				"adi"  => $cuidador->adicionales,
				"ser"  => "",
				"pre"  => $cuidador->precio
			);

		}
    }

/*    echo "<pre>";
    	print_r($pines);
    echo "</pre>";*/

	$pines_json = json_encode($pines);
    $pines_json = "<script>var pines = eval('".$pines_json."');</script>";
	$_SESSION['pines'] = $pines_json;
	$_SESSION['pines_array'] = serialize($pines);

	$temp_rangos = @array_filter($_POST["rangos"]);
	if( count($temp_rangos) > 0 ){
		$_POST["rangos"] = $temp_rangos;
	}else{
		unset($_POST["rangos"]);
	}

	$_POST = @array_filter($_POST);
	
	$_SESSION['busqueda'] = serialize($_POST);
    $_SESSION['resultado_busqueda'] = $cuidadores;

/*	echo "<pre>";
		print_r( $sql );
		print_r( $_POST );
	echo "</pre>";*/

    if( !isset($redirect) || !$redirect ) {
		header("location: {$home}busqueda/");
	}
