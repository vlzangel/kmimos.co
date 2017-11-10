<?php
    $fullpath = "../../../../uploads/cuidadores/galerias/".$tmp_user_id."/".$img;
    echo $fullpath."\n";
    if(file_exists($fullpath)){
        unlink($fullpath);
        echo "Eliminado!\n";
    }else{
        echo "Error!\n";
    }
	$respuesta = array(
		"status" => "OK"
	);

?>