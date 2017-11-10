<?php  
	include(__DIR__."../../../../../../vlz_config.php");
	
    $q = 0;
    if(isset($_GET['q'])){
        $q = intval($_GET['q']);
	}
    $conn = new mysqli($host, $user, $pass, $db);
	
    $errores = array();

    if ($conn->connect_error) {
        echo 'false';
        echo "No se conecto";
	}else{
        $razas = $conn->query("SELECT * FROM razas ORDER BY nombre ASC");
        	if ($razas->num_rows > 0) {
    			$datos = $razas->fetch_assoc();
    			echo utf8_encode ("<option id='select_mascota' value=''>Raza de la Mascota</option>");
                foreach ($razas as $valores) {
    			 	// $data["data"][] = $valores;
    			 	echo utf8_encode ("<option id='select_mascota' value='".$valores['id']."'>".$valores['nombre']."</option>");
    			 	// echo json_encode(array(['id'=>$valores['id'],'nombre'=>$valores['nombre']]));
    			}
     		}       	
    }
?>