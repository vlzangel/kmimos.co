<?php

include_once(dirname(dirname(dirname(__FILE__))).'/wlabel.php');

$return=array();
$return['message']='';
$return['message']=$_wlabel_user->LogIn();
echo json_encode($return);

?>