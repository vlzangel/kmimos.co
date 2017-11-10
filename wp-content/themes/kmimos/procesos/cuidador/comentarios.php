<?php

//$conn = new mysqli($host, $user, $pass, $db);
//$db = new db($conn);

	$load = realpath('../../../../../wp-load.php');
	if(file_exists($load)){
		include_once($load);
	}

	include_once(realpath('../../../../../vlz_config.php'));
	include_once(dirname(__DIR__).'/funciones/db.php');
	include_once(dirname(__DIR__).'/funciones/generales.php');
	extract($_POST);

/*	$sql = "
		SELECT
			comentario.user_id AS cliente_id,
			comentario.comment_author_email AS cliente_email,
			comentario.comment_author AS cliente,
			comentario.comment_content AS contenido,
			comentario.comment_date AS fecha,
			puntualidad.meta_value AS puntualidad_valor ,
			confianza.meta_value AS confianza_valor,
			limpieza.meta_value AS limpieza_valor,
			cuidado.meta_value AS cuidado_valor
		FROM
			wp_comments	AS comentario
		INNER JOIN wp_commentmeta AS puntualidad 	ON ( comentario.comment_ID = puntualidad.comment_id AND puntualidad.meta_key = 'punctuality')
		INNER JOIN wp_commentmeta AS confianza 		ON ( comentario.comment_ID = confianza.comment_id 	AND confianza.meta_key = 'trust')
		INNER JOIN wp_commentmeta AS limpieza 		ON ( comentario.comment_ID = limpieza.comment_id  	AND limpieza.meta_key = 'cleanliness')
		INNER JOIN wp_commentmeta AS cuidado 		ON ( comentario.comment_ID = cuidado.comment_id 	AND cuidado.meta_key = 'care')
		WHERE
			comentario.comment_post_ID = '{$servicio}'
		ORDER BY comentario.comment_ID DESC
	";*/

	$sql = "
		SELECT
			comentario.user_id AS cliente_id,
			comentario.comment_ID AS comment_ID,
			comentario.comment_author_email AS cliente_email,
			comentario.comment_author AS cliente,
			comentario.comment_content AS contenido,
			comentario.comment_date AS fecha
		FROM
			wp_comments	AS comentario
		WHERE
			comentario.comment_post_ID = '{$servicio}'
		ORDER BY comentario.comment_ID DESC
	";

	$resultado = array();
	$comentarios = $wpdb->get_results($sql);
	foreach ($comentarios as $comentario) {

		$puntuaciones = array(
			"punctuality" => 0,
			"trust" => 0,
			"cleanliness" => 0,
			"care" => 0
		);

		$metas = $wpdb->get_results("SELECT * FROM wp_commentmeta WHERE comment_id = ".$comentario->comment_ID);

		if( $metas !== false ){
			foreach ($metas as  $meta) {
				$puntuaciones[$meta->meta_key] = $meta->meta_value;
			}
		}

		$user_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$comentario->cliente_email}' ");

		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $inicio = strtotime( $comentario->fecha );
	    $fecha = date('d', $inicio)." ".$meses[date('n', $inicio)-1]. ", ".date('Y', $inicio) ;

		$resultado[] = array(
			"cliente"	=> ($comentario->cliente),
			"img"	=> kmimos_get_foto($user_id),
			"contenido" => ($comentario->contenido),
			"fecha" => ($fecha),
			"puntualidad" => ($puntuaciones["punctuality"]),
			"confianza" => ($puntuaciones["trust"]),
			"limpieza" => ($puntuaciones["cleanliness"]),
			"cuidado" => ($puntuaciones["care"])
		);
	}

	echo json_encode($resultado);
?>


