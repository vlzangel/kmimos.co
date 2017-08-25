<?php

	try{
     	if(isset($_GET['p'])){
     		$fullpath = "../../../../../../wp-content/uploads/cuidadores/galerias/".$_GET['user_id']."/".$_GET['p'];
     		if(file_exists($fullpath)){
     			unlink($fullpath);
     		}
     	}
 	}catch(Exception $e){}

	header("location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/perfil-usuario/?ua=mypictures");
?>