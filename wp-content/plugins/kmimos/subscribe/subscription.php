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




function mail_validate($mail){
	if($mail!='' && strpos($mail,'@')!==FALSE){
		return true;

	}else{
		return false;

	}
}


$file='subscription.csv';
$mail=$_POST['mail'];
$mail=$_POST['mail'];
$section=$_POST['section'];
$mail_exist='';
$datos=array();

$return=array();
$return['result']=true;
$return['message']='';
$return['data']='';


if(mail_validate($mail)){
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


	//BD
	include_once(__DIR__.'/subscribe.php');
	$table = $_subscribe->table;
	$result = $_subscribe->result("SELECT * FROM $table WHERE email = '$mail'");
	if(count($result)==0){
		$_subscribe->insert(array('name' => $name  ,'email' => $mail , 'source' => $section,'time' => date('Y-m-d H:i:s', time())));
		$return['message']='Ha sido Registrado';
	}else{
		$return['message']='Ya se encuentra registrado';
	}

}else{
	$return['result']=false;
	$return['message']='El email es incorrecto';
}



$return['result']=true;
$return['data']=$datos;
echo json_encode($return);
//echo 'SU CORREO ELECTRÓNICO HA SIDO GUARDADO';



?>