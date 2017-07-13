<?php
	include("../../../../vlz_config.php");

	extract($_POST);
	
	$c = new mysqli($host, $user,  $pass, $db);

	if( isset($_POST['estado']) ){

		$sql = "SELECT * FROM kmimos_opciones WHERE clave = 'estado_{$estado}'";
		$r = $c->query( $sql );

		$sql = "SELECT * FROM locations WHERE state_id = '{$estado}' ORDER BY name ASC";
		$r2 = $c->query( $sql );

		if( $r->num_rows > 0){
			$datos = $r->fetch_assoc();

			$municipios = array();
			while ( $municipio = $r2->fetch_assoc() ) {
				$municipios[] = array(
					"id" 	=> $municipio['id'],
					"name" 	=> $municipio['name']
				);
			}

			$res = array(
				"geo" => unserialize( $datos['valor'] ),
				"mun" => $municipios
			);

			echo "(".json_encode($res).")";

		}

	}else{

		$sql = "SELECT * FROM kmimos_opciones WHERE clave = 'municipio_{$municipio}'";
		$r = $c->query( $sql );

		if( $r->num_rows > 0){
			$datos = $r->fetch_assoc();

			$res = array(
				"geo" => unserialize( $datos['valor'] )
			);

			echo "(".json_encode($res).")";

		}

	}
?>