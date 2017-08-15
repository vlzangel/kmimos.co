<?php
	include("../../../../wp-config.php");

	extract($_POST);

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$r = $conn->query( "SELECT * FROM locations WHERE state_id = ".$estado." ORDER BY name ASC" );

	$municipios = "";
	while( $f = $r->fetch_assoc() ) {
		$municipios .= "<option value='".$f['id']."'>".$f['name']."</option>";
	}

	echo $municipios;
?>