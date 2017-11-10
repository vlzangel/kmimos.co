<?php
	class db{

		private $conn;

		function db($con){
			$this->conn = $con;
		}

		function query($sql){
			return $this->conn->query($sql);
		}

		function query_multiple($sql){
			return $this->conn->multi_query($sql);
		}

		function multi_query($sql){
			return $this->conn->multi_query($sql);
		}

		function get_var($sql, $campo = ""){
			$result = $this->query($sql);
			if( $result->num_rows > 0 ){
				if($campo == ""){
					$temp = $result->fetch_array(MYSQLI_NUM);
		            return $temp[0];
				}else{
		            $temp = $result->fetch_assoc();
		            return $temp[$campo];
				}
	        }else{
	        	return false;
	        }
		}

		function get_row($sql){
			$result = $this->query($sql);
			if( $result->num_rows > 0 ){
	            return (object) $result->fetch_assoc();
	        }else{
	        	return false;
	        }
		}

		function get_results($sql){
			$result = $this->query($sql);
			if( $result->num_rows > 0 ){
				$resultados = array();
				while ( $f = $result->fetch_assoc() ) {
					$resultados[] = (object) $f;
				}
	            return $resultados;
	        }else{
	        	return false;
	        }
		}

		function insert_id(){
			return $this->conn->insert_id;
		}
	}

	$db = new db( new mysqli("localhost", "root", "kmimos", "qa_temp") );
	
    header('HTTP/1.1 200 OK');
    $obj = file_get_contents('php://input');
    $json = json_decode($obj);

    //$db->query("INSERT INTO log VALUES (NULL, '".$json->verification_code."')");
    $db->query("INSERT INTO log VALUES (NULL, 'Llego')");
    $db->query("INSERT INTO log VALUES (NULL, '".$json->transaction->status." - ".$json->transaction->order_id."')");


?>