<?php
	extract($_POST);

	$path = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
    require($path.'/wp-config.php');
    require($path.'/wp-content/themes/kmimos/procesos/funciones/db.php');

	print_r("SELECT atributos FROM cuidadores WHERE id = {$cuidador}");

	$db = new db( new mysqli($host, $user, $pass, $db) );

	$atributos = unserialize( $db->get_var("SELECT atributos FROM cuidadores WHERE id = {$cuidador}") );

	print_r($atributos);

	$atributos['destacado'] = $destacado;

	print_r($atributos);
	
	$atributos = serialize($atributos);
	$db->query("UPDATE cuidadores SET atributos = '{$atributos}' WHERE id = {$cuidador};");

	exit;
