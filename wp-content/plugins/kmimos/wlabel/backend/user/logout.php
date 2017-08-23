<?php

include_once(dirname(dirname(dirname(__FILE__))).'/wlabel.php');

$return=array();
$return['message']='Salir';
$return['message']=$_wlabel_user->LogOut();
echo json_encode($return);

?>