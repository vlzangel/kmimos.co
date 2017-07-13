<?php

	require_once("../../vlz_config.php");
	$sts = 0;

	if(!empty($_POST) ){
		if( !isset($landing_name)) { return; }
		if( empty($landing_name)) { return; }
		
		if( !isset($_POST['email'])) { return; }
		if( empty($_POST['email'])) { return; }

		$cnn = new mysqli($host, $user, $pass, $db);
		if($cnn){

			$rows = "SELECT * FROM list_subscribe WHERE source = '".$landing_name."' and email = '".$_POST['email']."'";
			$r = $cnn->query( $rows );
			if( $r->num_rows == 0){
				# Insertar registro
				$sql = "insert into list_subscribe( source, email) values ( '".$landing_name."','".$_POST['email']."' )";
				$rows = $cnn->query( $sql );
			}
			$sts = 1;
		}else{
			$sts = 0;
		}
	}else{
		$sts = 0;
	}
	print_r($sts);
