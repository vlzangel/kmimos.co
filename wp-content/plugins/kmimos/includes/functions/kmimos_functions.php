<?php
	function get_estados_municipios(){
		require_once('vlz_config.php');
		if( $host == "" ){
			global $host, $user, $pass, $db;
		}
		$conn_my = new mysqli($host, $user, $pass, $db);
		$result = $conn_my->query("
			SELECT 
				s.id AS id,
				s.name AS esta,
				ko.valor AS coord
			FROM 
				states AS s
			INNER JOIN kmimos_opciones AS ko ON  ( ko.clave = CONCAT('estado_', s.id) )
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
						l.name AS muni,
						ko.valor AS coord
					FROM 
						locations AS l
					INNER JOIN kmimos_opciones AS ko ON  ( ko.clave = CONCAT('municipio_', l.id) )
					WHERE 
						state_id = {$id}
					ORDER BY 
						name ASC"
				);

				if( $result2->num_rows > 0  ){
					while ($row2 = $result2->fetch_assoc()){
						$coordenadas = unserialize( $row2['coord'] );
						$coordenadas["referencia"]->lng = trim($coordenadas["referencia"]->lng)+0;
						$municipios[] = array(
							"id" => $row2['id'],
							"nombre" => ($row2['muni']),
							"coordenadas" => $coordenadas
						);
					}
				}

				$datos[$id] = array(
					"nombre" => ($esta),
					"coordenadas" => unserialize( str_replace("\r", "", $coord) ),
					"municipios" => $municipios
				);

			}
		}
		$datos_json = json_encode($datos );
		return "<script>
				var objectEstados = jQuery.makeArray(
					eval(
						'(".$datos_json.")'
						)
					);
				var estados_municipios = objectEstados[0] ;
			</script>";
	}
