<?php
	
	function normaliza($cadena){
	    $originales = 'ÁáÉéÍíÓóÚúÑñ';
	    $modificadas = 'aaeeiioouunn';
	    $cadena = utf8_decode($cadena);
	    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	    $cadena = strtolower($cadena);
	    return utf8_encode($cadena);
	}

	include("../../../../../vlz_config.php");
	include("../funciones/db.php");

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

	// $tag_start = ( array_key_exists('tag', $_POST) )? "li data-action='tag-list' " : 'div' ;
	// $tag_end = ( array_key_exists('tag', $_POST) )? "li" : 'div' ;

	$tag_start = "li";
	$tag_end = "li";

	$estados_str = "";
    $estados = $db->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY `order`, `name` ASC");

    $ubicaciones = array();

    foreach ($estados as $key => $value) {
		$municipios = $db->get_results("SELECT * FROM locations WHERE state_id = ".$value->id." ORDER BY `order`, `name` ASC ");

		$estado_value = normaliza( ($value->name) );
    	$estados_str .= ("<".$tag_start." value='".$value->id."' data-value='".$estado_value."' >".$value->name."</".$tag_end.">");
    	
    	$ubicaciones[] = array(
    		"id" => $value->id,
    		"valor" => $estado_value,
    		"txt" => $value->name
    	);

		if( count($municipios) > 1 ){
			$cont = 0;
    		foreach ($municipios as $key => $municipio) {
    			$municipio_value = normaliza( ($municipio->name) );
    			$estados_str .= ("<".$tag_start." value='".$value->id."_".$municipio->id."' data-value='".$estado_value." ".$municipio_value."' >".$value->name.", ".$municipio->name."</".$tag_end.">");


    			$ubicaciones[] = array(
		    		"id" => $value->id."_".$municipio->id,
		    		"valor" => $estado_value.", ".$municipio_value,
		    		"txt" => $value->name.", ".$municipio->name
		    	);
				//$cont++;

				//if( $cont == 10 ){ break; }
    		}
		}

		//break;
		

    }

    // echo json_encode($ubicaciones);
    echo $estados_str;
