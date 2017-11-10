<?php
	function get_coordenadas(){
		//COORDENADAS
	    global $wpdb;
	    $result_coord = $wpdb->get_results("
	        SELECT
	            locations.clave AS clave,
	            locations.valor AS valor
	        FROM
	            kmimos_opciones AS locations
	        WHERE
	            locations.clave LIKE 'municipio%'
	            OR
	            locations.clave LIKE 'estado%'
	        ORDER BY
	            locations.id ASC"
	    );

	    $state=array();
	    $locale=array();
	    foreach($result_coord as $data){
	        $clave = $data->clave;
	        $valor = unserialize($data->valor);
	        //var_dump(strpos($clave,'estado'));

	        if(strpos($clave,'estado_') !== false){
	            $id=str_replace('estado_','',$clave);
	            $state[$id]=array(
	                'lat'=>$valor['referencia']->lat,
	                'lng'=>$valor['referencia']->lng
	            );
	        }

	        if(strpos($clave,'municipio_') !== false){
	            $id=str_replace('municipio_','',$clave);
	            $locale[$id]=array(
	                'lat'=>$valor['referencia']->lat,
	                'lng'=>$valor['referencia']->lng
	            );
	        }
	    }

	    $coordenadas=[];
	    $coordenadas['state']=$state;
	    $coordenadas['locale']=$locale;

	    return json_encode( $coordenadas );
	}

	function get_estados_municipios(){
		require_once('vlz_config.php');
		if( $host == "" ){
			global $host, $user, $pass, $db;
		}
		$conn_my = new mysqli($host, $user, $pass, $db);
		if (!$conn_my) {
		  	exit;
		}

		$result = $conn_my->query("
			SELECT 
				s.id AS id,
				s.name AS esta
			FROM 
				states AS s
			WHERE 
				country_id = 1
			ORDER BY 
				name ASC");
		$datos = array();
		if( $result->num_rows > 0  ){
			while ($row = $result->fetch_assoc()){
				extract($row);

				$municipios = array();

				$result2 = $conn_my->query("
					SELECT 
						l.id AS id,
						l.name AS muni
					FROM 
						locations AS l
					WHERE 
						state_id = {$id}
					ORDER BY 
						name ASC"
				);

				if( $result2->num_rows > 0  ){
					while ($row2 = $result2->fetch_assoc()){
						$municipios[] = array(
							"id" => $row2['id'],
							"nombre" => $row2['muni']
						);
					}
				}

				$datos[$id] = array(
					"nombre" => $esta,
					"municipios" => $municipios
				);

			}
		}
		$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE );
		return "<script>
				var objectEstados = jQuery.makeArray(
					eval(
						'(".$datos_json.")'
						)
					);
				var estados_municipios = objectEstados[0] ;
			</script>";
	}
