<?php
    include("../../../../../vlz_config.php");
	$conn = new mysqli($host, $user, $pass, $db);
	$errores = array();
    extract($_POST);
	if ($conn->connect_error) {
        echo 'false';
	}else{
        $existen = $conn->query( "SELECT * FROM wp_users WHERE user_email = '{$email}'" );
        if( $existen->num_rows > 0 ){
            $datos = $existen->fetch_assoc();
            $error = array(
                "error" => "SI",
                "msg" => "Este E-mail ya esta en uso"
            );
            echo "(".json_encode( $error ).")";
        }else{
            $error = array(
                "error" => "NO",
                "msg" => ""
            );
            echo "(".json_encode( $error ).")";
        }
	}
?>