<?php
	
	function geo($valor, $ref = null){

		$vlz_geo = array(
			'ref' => array(
				"lat" => "23.634501",
	          	"lng" => "-102.552784"
			),
			'limites' => array(
				'norte' => array(
					"lat" => "32.7187629",
	              	"lng" => "-86.5887"
				),
				'sur' => array(
					"lat" => "14.3895",
	              	"lng" => "-118.6523001"
				)
			)
		);

		switch ($valor) {

			case 'L':
				return $vlz_geo['ref'];
			break;

			case 'N':
				return $vlz_geo['limites']['norte'];
			break;

			case 'S':
				return $vlz_geo['limites']['sur'];
			break;

			case 'C':
				
				if( ($ref['lat'] >= $vlz_geo['limites']['sur']['lat']) && ($ref['lat'] <= $vlz_geo['limites']['norte']['lat']) ){
					if( ($ref['lng'] >= $vlz_geo['limites']['sur']['lng']) && ($ref['lng'] <= $vlz_geo['limites']['norte']['lng']) ){
						return true;
					}else{
						return false;
					}
				}

			break;
			
			default:
				return array(
					"L",
					"N",
					"S"
				);
			break;

		}
		
	}

	$L = geo("L");
	$N = geo("N");
	$S = geo("S");

?>