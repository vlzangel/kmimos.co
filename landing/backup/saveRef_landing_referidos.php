<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../vlz_config.php");
	$result['data'] = [];
	$result['sts'] = 0;

	$cnn = new mysqli($host, $user, $pass, $db);
	if($cnn){
		if( !empty($_POST['ref']) ){
			$result['ref'] = $_POST['ref'];
			foreach($_POST['list'] as $email){
				$rows = "
					update list_subscribe set 
						referencia = {$_POST['ref']} ,
						fecha_referencia = {$_POST['fec']} 
					where email = '{$email}'";
				$r = $cnn->query( $rows );
				if( $r == 1 ){
					$result['data'][] = $email;
					$result['sts'] = 1;
				}
			}
		}
	}

	print_r( json_encode($result) );