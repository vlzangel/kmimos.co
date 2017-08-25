<?php
	function paginas(){
		global $wpdb;
		$sql = "SELECT * FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish'";
		$paginas = $wpdb->get_results($sql);
		$resultados = [];
		if( count($paginas) > 0 ){
			foreach ($paginas as $key => $value) {
				
				$descripcion = $wpdb->get_var("SELECT meta_value AS descripcion FROM wp_postmeta WHERE post_id = '{$value->ID}' AND meta_key = 'kmimos_descripcion'");
				if( $descripcion  == null){
					$descripcion  = "";
				}

				$resultados["registros"][] = [
					$value->ID,
					($descripcion),
					($value->post_title),
					$value->post_name
				];
			}
		}
		
		$json = json_encode($resultados);
		echo ( "<script> var paginas = jQuery.parseJSON( '".$json."' ); </script>" );
	}

	function administradores(){
		global $wpdb;
		$sql = "
			SELECT 
				SQL_CALC_FOUND_ROWS
				U.ID			AS id,
				U.user_email 	AS email,
				U.display_name 	AS nombre
			FROM 
				wp_users AS U 
			INNER JOIN wp_usermeta AS UM_1 ON ( U.ID = UM_1.user_id ) 
			WHERE 
				1=1 AND 
				( UM_1.meta_key = 'wp_capabilities' AND UM_1.meta_value = 'a:1:{s:13:\"administrator\";b:1;}' ) 
			GROUP BY 
				U.ID
		";

		$result = $wpdb->get_results($sql);

		$resultados = [];
		if( count($result) > 0 ){
			foreach ($result as $admin) {

				$tipo = $wpdb->get_var("SELECT meta_value AS tipo FROM wp_usermeta WHERE user_id = {$admin->id} AND meta_key = 'tipo_usuario'");
				if($tipo == "" ){ $tipo = "Administrador"; }

				$resultados["registros"][] = [
					$admin->id,
					$admin->email,
					$admin->nombre,
					$tipo,
					md5($admin->id)
				];

			}

		}
		
		$json = json_encode($resultados);
		echo ( "<script> var administradores = jQuery.parseJSON( '".$json."' ); </script>" );
	}

	function usuarios(){
		global $wpdb;
		$sql = "
			SELECT 
				U.ID			AS id,
				U.user_email 	AS email,
				U.display_name 	AS nombre,
				UM_1.meta_value AS tipo
			FROM 
				wp_users AS U 
			INNER JOIN wp_usermeta AS UM_1 ON ( U.ID = UM_1.user_id ) 
			WHERE 
				1=1 AND 
				( UM_1.meta_key = 'wp_capabilities' AND UM_1.meta_value != 'a:1:{s:13:\"administrator\";b:1;}' ) 
			GROUP BY 
				U.ID
		";

		$result = $wpdb->get_results($sql);
		$resultados = [];
		if( count($result) > 0 ){
			foreach ($result as $admin) {
				$tipo = explode('"', $admin->tipo);

				if( $tipo[1] == "vendor" ){
					$sql = "
					SELECT 
						CONCAT(C.nombre, ' ', C.apellido, ' (', P.post_title, ')') As nombre
					FROM 
						wp_posts AS P 
					INNER JOIN cuidadores AS C ON ( P.ID = C.id_post ) 
					WHERE 
						1=1 AND 
						P.post_author = '{$admin->id}' AND
						P.post_type='petsitters'
					GROUP BY 
						P.ID;";
					$admin->nombre = "".$wpdb->get_var($sql);
				}

				$resultados["registros"][$tipo[1]][] = [
					$admin->id,
					$admin->nombre,
					$admin->email,
					md5($admin->id)
				];
			}
		}
		
		$json = json_encode($resultados);
		echo ( "
			<script> 
				var kmimos_usuarios = jQuery.parseJSON( '".$json."' ); 
				var kmimos_tipos_usuarios = [];
				jQuery.each( kmimos_usuarios.registros, function( key, value ) {
				  	kmimos_tipos_usuarios.push(key);
				});
			</script>" );
	}
?>