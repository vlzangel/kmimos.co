<?php

function execute($sql){

	$cnn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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
	$cnn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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
