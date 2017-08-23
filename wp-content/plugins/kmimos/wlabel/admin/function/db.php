<?php

	// *****************************************
	//	DB Access
	// *****************************************
	function get_fetch_assoc($sql){
		require_once("../../../../../../vlz_config.php");
		global $host, $user, $pass, $db ;
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

			print_r($rows);
		}
		return $data;
	}
