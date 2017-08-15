<?php
	include("../../../../vlz_config.php");
	include("admin/funciones/kmimos_funciones_db.php");


	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

	extract($_POST);

	$condiciones = "";
	$servicios_buscados = "";

    /* Filtros por servicios y tamaños */
	    if( isset($servicios) ){
	    	$servicios_buscados .= "(";
			foreach ($servicios as $key => $value) {
				if( $servicios_buscados == "(" ){
					$servicios_buscados .= "cupos.tipo LIKE '%{$value}%' ";
				}else{
					$servicios_buscados .= " OR cupos.tipo LIKE '%{$value}%' ";
				}
				
				if( $value != "hospedaje" ){
					$condiciones .= " AND adicionales LIKE '%".$value."%'";

					if(strpos($value,'adiestramiento')===false){
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
	    	$servicios_buscados .= " ) AND";
		}


	    if( isset($tamanos) ){ foreach ($tamanos as $key => $value) { $condiciones .= " AND ( tamanos_aceptados LIKE '%\"".$value."\";i:1%' || tamanos_aceptados LIKE '%\"".$value."\";s:1:\"1\"%' ) "; } }
    /* Fin Filtros por servicios y tamaños */

    /* Filtros por fechas */
	    if( isset($checkin)  && $checkin  != '' && isset($checkout) && $checkout != '' ){ 
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

    /* Filtros por rangos */
	    if( isset($n) ){ if( $n != "" ){ $condiciones .= " AND nombre LIKE '".$n."%' "; } }
	    if( $rangos[0] != "" ){ $condiciones .= " AND (hospedaje_desde*1.2) >= '".$rangos[0]."' "; }
	    if( $rangos[1] != "" ){ $condiciones .= " AND (hospedaje_desde*1.2) <= '".$rangos[1]."' "; }
	    if( $rangos[2] != "" ){ $anio_1 = date("Y")-$rangos[2]; $condiciones .= " AND experiencia <= '".$anio_1."' "; }
	    if( $rangos[3] != "" ){ $anio_2 = date("Y")-$rangos[3]; $condiciones .= " AND experiencia >= '".$anio_2."' "; }
	    if( $rangos[4] != "" ){ $condiciones .= " AND rating >= '".$rangos[4]."' "; }
	    if( $rangos[5] != "" ){ $condiciones .= " AND rating <= '".$rangos[5]."' "; }
    /* Fin Filtros por rangos */

    /* Ordenamientos */
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
	    if( $tipo_busqueda == "otra-localidad" && $estados != "" && $municipios != "" ){
	        $coordenadas 		= unserialize( $db->get_var("SELECT valor FROM kmimos_opciones WHERE clave = 'municipio_{$municipios}' ") );
	        $latitud  			= $coordenadas["referencia"]->lat;
	        $longitud 			= $coordenadas["referencia"]->lng;
	        $distancia 			= calcular_rango_de_busqueda($coordenadas["norte"], $coordenadas["sur"]);
	        $ubicacion 			= " ubi.estado LIKE '%={$estados}=%' AND ubi.municipios LIKE '%={$municipios}=%' ";
	        $calculo_distancia 	= "( 6371 * acos( cos( radians({$latitud}) ) * cos( radians(latitud) ) * cos( radians(longitud) - radians({$longitud}) ) + sin( radians({$latitud}) ) * sin( radians(latitud) ) ) )";
	        $DISTANCIA 			= ", {$calculo_distancia} as DISTANCIA";
	        $FILTRO_UBICACION 	= "HAVING DISTANCIA < ".($distancia+0);
	        $ubicaciones_inner  = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
	        $ubicaciones_filtro = "AND ( ( $ubicacion ) OR ( {$calculo_distancia} <= ".($distancia+0)." ) )";
	        //if( $orderby == "" ){ $orderby = "DISTANCIA ASC"; }
	    }else{ 
	        if( $tipo_busqueda == "otra-localidad" && $estados != "" ){
	            $ubicaciones_inner = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
	            $ubicaciones_filtro = "AND ( ubi.estado LIKE '%=".$estados."=%' )";
	        }else{
	            if( $tipo_busqueda == "mi-ubicacion" && $latitud != "" && $longitud != "" ){
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

    $sql = "
    SELECT 
        cuidadores.id,
        cuidadores.id_post,
        cuidadores.user_id,
        cuidadores.longitud,
        cuidadores.latitud,
        cuidadores.adicionales,
        cuidadores.atributos,
        (cuidadores.hospedaje_desde*1.2) AS precio,
        cuidadores.experiencia,
        post_cuidador.post_name AS slug,
        post_cuidador.post_title AS titulo
        {$DISTANCIA}
    FROM 
        cuidadores 
    INNER JOIN wp_posts AS post_cuidador ON ( cuidadores.id_post = post_cuidador.ID )
    {$ubicaciones_inner}
    WHERE 
        activo = '1' {$condiciones} {$ubicaciones_filtro} {$FILTRO_UBICACION}
    ORDER BY {$orderby}";

    // echo "<pre>";
    // 	print_r($sql);
    // echo "</pre>";

    $cuidadores = $db->get_results($sql);

    $pines = array();
    if( $cuidadores != false ){
		foreach ($cuidadores as $key => $cuidador) {
			$url = $_SERVER["HTTP_ORIGIN"] . "/petsitters/" . $cuidador->slug;
			$img = kmimos_get_foto_cuidador($cuidador->id);		
			$pines[] = array(
				"ID"   => $cuidador->id,
				"user" => $cuidador->user_id,
				"lat"  => $cuidador->latitud,
				"lng"  => $cuidador->longitud,
				"nom"  => utf8_encode($cuidador->titulo),
				"url"  => $url,
				"img"  => kmimos_get_foto_cuidador($cuidador->id)
			);
		}
    }

	session_start();

	$pines_json = json_encode($pines);
    $pines_json = "<script>var pines = eval('".$pines_json."');</script>";
	$_SESSION['pines'] = $pines_json;
	$_SESSION['pines_array'] = serialize($pines);
	
	$_SESSION['busqueda'] = serialize($_POST);
    $_SESSION['resultado_busqueda'] = $cuidadores;

    function toRadian($deg) { return $deg * pi() / 180; };

	function calcular_rango_de_busqueda($norte, $sur){
	    return ( 6371 * acos( cos( toRadian($norte->lat) ) * cos( toRadian($sur->lat) ) * cos( toRadian($sur->lng) - toRadian($norte->lng) ) + sin( toRadian($norte->lat) ) * sin( toRadian($sur->lat) ) ) );
	}

	function kmimos_get_foto_cuidador($id){
        global $db;
        $cuidador = $db->get_row("SELECT * FROM cuidadores WHERE id = ".$id);
        $home = $db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'", "option_value");
        $name_photo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$cuidador->user_id} AND meta_key = '{name_photo}'", "meta_value");
        if( empty($name_photo)  ){ $name_photo = "0"; }
        if( file_exists("../../../uploads/cuidadores/avatares/{$id}/{$name_photo}") ){
            $img = $home."/wp-content/uploads/cuidadores/avatares/{$id}/{$name_photo}";
        }else{
            if( file_exists("../../../uploads/cuidadores/avatares/{$id}/0.jpg") ){
                $img = $home."/wp-content/uploads/cuidadores/avatares/{$id}/0.jpg";
            }else{
                $img = $home.'/wp-content/themes/pointfinder/images/noimg.png';
            }
        }
        return $img;
    }
       
    $home = $db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'", "option_value");

	header("location: {$home}/busqueda/");

?>