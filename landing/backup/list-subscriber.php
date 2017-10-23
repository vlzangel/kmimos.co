<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../vlz_config.php");
	$sts = 0;

	if(!empty($_GET) ){
		if( !isset($_GET['source'])) { return; }
		if( empty($_GET['source'])) { return; }
		
		if( !isset($_GET['email'])) { return; }
		if( empty($_GET['email'])) { return; }

		if( !isset($_GET['phone'])) { 
			$_GET['phone'] = "";
		}

		// Validar Email
		if(preg_match("/[\+]{1,}/", $_GET['email'])){ return; }
		$result = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

		if( $result ){
			$cnn = new mysqli($host, $user, $pass, $db);
			if($cnn){

				$rows = "SELECT * FROM list_subscribe WHERE source = '".$_GET['source']."' and email = '".$_GET['email']."'";
				$r = $cnn->query( $rows );
				if( $r->num_rows == 0){
					# Insertar registro
				$sql = "insert into list_subscribe( source, email, phone) 
					values ( '".$_GET['source']."','".$_GET['email']."','".$_GET['phone']."' )";
					$rows = $cnn->query( $sql );
				}
				$sts = 1;
			}else{
				$sts = 0;
			}
		}else{
			$sts = 2;
		}
	}else{
		$sts = 0;
	}
	print_r($sts);
