<?php

	extract($_POST);

	session_start();

	$busqueda = unserialize($_SESSION["busqueda"]);

	$busqueda["checkin"] = $checkin;
    $busqueda["checkout"] = $checkout;

    $_SESSION["busqueda"] = serialize( $busqueda );

	header("location: ".$redirigir);
?>