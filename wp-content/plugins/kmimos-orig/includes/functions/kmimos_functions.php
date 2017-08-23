<?php


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
