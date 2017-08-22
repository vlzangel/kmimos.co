<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
header("Content-Type: application/json; charset=utf-8");

/*
//echo 'algo: '.$JSONstar;
$fo = fopen("suscripcion.csv","w"); 
fwrite($fo,$JSONvariable);
fclose($fo);
*/

$file='subscription.csv';
$mail=$_POST['mail'];
$mail_exist='';
$datos=array();

$fo = fopen($file, "r");
while($data = fgetcsv ($fo,0,";")) {
	$datos[]=$data[0];
	if($data[0]==$mail){
		$mail_exist='y';
		//break;
	}
}
fclose($fo);


if($mail_exist==''){
	$datos[]=$mail;
}

$fo = fopen($file, "w");
foreach($datos as $dato){
	fwrite($fo,$dato."\n");
}
fclose($fo);

echo json_encode($datos);
//echo 'SU CORREO ELECTRÓNICO HA SIDO GUARDADO';



?>