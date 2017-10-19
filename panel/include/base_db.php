<?php

function execute($sql){
	global $host, $user, $pass, $db;
	include("../vlz_config.php");
	$cnn = new mysqli($host, $user, $pass, $db);
	$data = [];
	if($cnn){
		$rows = $cnn->query( $sql );
		if(isset($rows->num_rows)){
			if( $rows->num_rows > 0){
				return $rows;
			}
		}
	}
	return $data;
}

function get_fetch_assoc($sql){
	global $host, $user, $pass, $db;
	include("../vlz_config.php");
	$cnn = new mysqli($host, $user, $pass, $db);
	$data = [];
	if($cnn){
		$rows = $cnn->query( $sql );
		if(isset($rows->num_rows)){
			if( $rows->num_rows > 0){
				$data['info'] = $rows;
				$data['rows'] = mysqli_fetch_all($rows,MYSQLI_ASSOC);
			}
		}
	}
	return $data;
}
