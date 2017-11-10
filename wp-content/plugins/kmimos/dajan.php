<?php 
	

	if(!function_exists('dajan_include_script')){
	    function dajan_include_script(){
	        
	    }
	}

	if(!function_exists('dajan_include_admin_script')){
	    function dajan_include_admin_script(){

	    }	
	}

	function get_region($key){
		if (!empty($key)) {
			if (!empty(REGION)) {
				$ruta = realpath(realpath(__DIR__).'/regionalizacion/'.strtolower(REGION).'.php');
				if (file_exists($ruta)) {
					include($ruta);
					if (array_key_exists($key, $region)) {
						return $region[$key];
					}
				}
			}
		}
		trigger_error("La clave o el archivo de regionalizacion no existe:", E_USER_ERROR);
	}
?>