<?php 

	if($portada != ""){
		$tmp_user_id = $user_id;
	    $sub_path = "/wp-content/uploads/cuidadores/galerias/{$tmp_user_id}/";
	    $dir = $raiz.$sub_path;
	    @mkdir($dir);
	    $path_origen = $raiz."/imgs/Temp/".$portada;
	    $path_destino = $dir.$portada;
	    if( file_exists($path_origen) ){
	        copy($path_origen, $path_destino);
	        unlink($path_origen);
	    }
	}

	$respuesta = array(
		"status" => "OK"
	);
?>