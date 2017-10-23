<?php
	
	class db{

		private $conn;

		function db($con){
			$this->conn = $con;
		}

		function query($sql){
			return $this->conn->query($sql);
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

	include("vlz_config.php");

	$db = new db( new mysqli($host, $user, $pass, $db) );

	$actual = date( 'YmdHis', time() );

	$sql = "
		SELECT 
			reserva.ID 				 AS id, 
			servicio.post_author 	 AS autor, 
			servicio.ID 		 	 AS servicio_id, 
			tipo.slug 		 	 	 AS servicio_tipo, 
			servicio.post_name 		 AS servicio, 
			startmeta.meta_value 	 AS inicio, 
			endmeta.meta_value		 AS fin,
			acepta.meta_value		 AS acepta,
			mascotas.meta_value 	 AS mascotas,
			reserva.post_status		 AS status

		FROM wp_posts AS reserva

		LEFT JOIN wp_postmeta as startmeta     ON ( reserva.ID 		= startmeta.post_id 		)
		LEFT JOIN wp_postmeta as endmeta   	   ON ( reserva.ID 		= endmeta.post_id 			)
		LEFT JOIN wp_postmeta as mascotas  	   ON ( reserva.ID 		= mascotas.post_id 			)
		LEFT JOIN wp_postmeta as servicio_id   ON ( reserva.ID 		= servicio_id.post_id 		)
		LEFT JOIN wp_posts    as servicio  	   ON ( servicio.ID 	= servicio_id.meta_value 	)
		LEFT JOIN wp_postmeta as acepta  	   ON ( acepta.post_id 	= servicio.ID 				)

		LEFT JOIN wp_term_relationships as relacion ON ( relacion.object_id = servicio.ID )
		LEFT JOIN wp_terms as tipo ON ( tipo.term_id = relacion.term_taxonomy_id )

		WHERE 
			reserva.post_type  		= 'wc_booking' 				AND 
			startmeta.meta_key   	= '_booking_start' 			AND 
			endmeta.meta_key   		= '_booking_end' 			AND 
			servicio_id.meta_key   	= '_booking_product_id' 	AND 
			acepta.meta_key   		= '_wc_booking_qty' 		AND 
			mascotas.meta_key  		= '_booking_persons' 		AND 
			(
				reserva.post_status NOT LIKE '%cancelled%' AND
				reserva.post_status     != 	 'was-in-cart'  AND
				reserva.post_status     != 	 'modified' 
			) AND  (
				endmeta.meta_value >= '{$actual}'
			) AND
			relacion.term_taxonomy_id != 28
		GROUP BY reserva.ID
	";

	$resultados = $db->get_results($sql);

	/*  echo "<pre>";
			echo $sql."<br><br>";
			print_r($resultados);
		echo "</pre>";
	*/
	
	foreach ($resultados as $reserva) {
		update_cupos($reserva);
	}

	function update_cupos($reserva){
		global $db;
		$inicio = strtotime($reserva->inicio);
		$fin 	= strtotime($reserva->fin);

		for ($i=$inicio; $i < $fin; $i+=86400) { 
			$fecha = date("Y-m-d H:i:s", $i);

			$sql = "
			SELECT 
				*
			FROM 
				cupos 
			WHERE 
				cuidador = '{$reserva->autor}' AND
				servicio = '{$reserva->servicio_id}' AND
				fecha    = '{$fecha}'";

			$cupos = $db->get_row($sql);

			$total = 0;
			$mascotas = unserialize($reserva->mascotas);
			foreach ($mascotas as $key => $value) {
				$total += $value;
			}

			$full = 0;
			if( !isset($cupos->cupos) ){
				$full = 0;
				if($total >= $reserva->acepta){
					$full = 1;
				}
				$sql = "
					INSERT INTO cupos VALUES (
						NULL,
						{$reserva->autor},
						{$reserva->servicio_id},
						'{$reserva->servicio_tipo}',
						'{$fecha}',
						{$total},
						{$reserva->acepta},
						{$full},
						'0'		
					);
				";

			 	$db->query($sql);

				echo $sql."<br>";
			}else{
				$total += $cupos->cupos;
				if( $total >= $reserva->acepta ){
					$full = 1;
				}
				
				$sql = "
					UPDATE 
						cupos 
					SET 
						cupos = {$total},
						full  = {$full} 
					WHERE 
						id = {$cupos->id};
				";

				$db->query($sql);

				echo $sql."<br>";
			}
			
		}
	}
?>