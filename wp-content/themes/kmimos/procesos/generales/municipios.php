<?php
	include("../../../../../vlz_config.php");
	include("../funciones/db.php");

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

	if( isset($_GET['estado']) ){

		extract($_GET);

		$sql = "SELECT * FROM locations WHERE state_id = '{$estado}' ORDER BY name ASC";
		$r = $db->get_results( $sql );

		foreach ($r as $key => $value) {
			$municipios[] = array(
				"id" 	=> $value->id,
				"name" 	=> $value->name
			);
		}

		echo json_encode($municipios);

	}
?>