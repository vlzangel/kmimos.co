<?php
	
	function geo($valor, $ref = null){

		$vlz_geo = array(
			'ref' => array(
				"lat" => "4.718366708838099",
	          	"lng" => "-74.0674491609375"
			),
			'limites' => array(
				'norte' => array(
					"lat" => "12.457630186215619",
	              	"lng" => "-72.260198184375"
				),
				'sur' => array(
					"lat" => "-4.226310796692682",
	              	"lng" => "-69.997014590625"
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