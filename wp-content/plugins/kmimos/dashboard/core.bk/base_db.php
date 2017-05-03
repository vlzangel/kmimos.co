<?php
function execute($sql){

	$cnn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$data = [];
	if($cnn){
		$rows = $cnn->query( $sql );
		if( $rows->num_rows > 0){
			return $rows;
		}
	}
	return $data;
}