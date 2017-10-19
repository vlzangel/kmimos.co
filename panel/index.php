<?php 
session_start();
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

// *********************************
// Includes
// *********************************
require_once('include/base_db.php');
require_once('include/GlobalFunction.php');
require_once('include/auth.php');

$auth = 0;
if(array_key_exists('auth', $_SESSION)){
	if($_SESSION['auth'] == 1){
		$p = ( isset($_GET['p']) )? strtolower($_GET['p']) : '' ; 
		$auth = 1;
		if($p=='login' || empty($p) ){
			header('location:/panel/?p='.$_SESSION['url']);
		}
	}
	if($_SESSION['auth'] == 0){
		$p = 'login';
		if($_POST){
			if(auth() == 1){
				$p = $_SESSION['url'];
				$auth = 1;
				header('location:/panel/?p='.$_SESSION['url']);
			}
		}
	}
}else{
	$_SESSION['auth'] = 0;
	$p = 'login';
}

$rutas = [
	'logout' => 'salir',
	'suscriptores' => 'backpanel_ctr_participantes',
	'referidos' => 'backpanel_ctr_referidos',
];
$view = ( array_key_exists($p, $rutas) )? $rutas[$p] : 'login' ;

$debug[] = $p;
$debug[] = $_SESSION;
$debug[] = $view;

// *********************************
// Views
// *********************************
include_once('views/header.php');
$filename = "views/{$view}.php";
if( file_exists($filename)){
	include_once($filename);
}else{
	include_once("views/404.php");	
}
include_once('views/footer.php');


// echo '<pre>';
// print_r($debug);
// echo '</pre>';