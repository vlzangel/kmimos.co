<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");
	include_once("../funciones/generales.php");

	$db = new db( new mysqli($host, $user, $pass, $db) );

	extract($_POST);

	include($accion.".php");

	echo json_encode($respuesta);

	exit;
?>