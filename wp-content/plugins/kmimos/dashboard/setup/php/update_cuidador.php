<?php
	extract($_POST);

	$path = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
    require($path.'/vlz_config.php');
    require($path.'/db.php');

	$db = new db( new mysqli($host, $user, $pass, $db) );

	$atributos = unserialize( $db->get_var("SELECT atributos FROM cuidadores WHERE id = {$cuidador}") );
	$atributos['destacado'] = $destacado;

	$atributos = serialize($atributos);
	$db->query("UPDATE cuidadores SET atributos = '{$atributos}' WHERE id = {$cuidador};");

	exit;
?>