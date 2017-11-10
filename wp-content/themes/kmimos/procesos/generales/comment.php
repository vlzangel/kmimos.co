<?php

$i=0;
$return = array();
$return['result']='success';

$load = dirname(__DIR__,5).'/wp-load.php';
if(file_exists($load)){
	include_once($load);
}

$bloquear = false;
preg_match_all("#<a(.*?)href(.*?)>#", $_POST["comment"], $links);
if( count($links[0]) > 0 ){
	$return['result']='error';
	$return['message']='Mensajes poseen enlaces';
	$bloquear = true;
}

preg_match_all("#http.*?//#", $_POST["comment"], $links);
if( count($links[0]) > 0 ){
	$return['result']='error';
	$return['message']='Mensajes poseen enlaces http';
	$bloquear = true;
}

preg_match_all("#www\.(.*?)\.[a-zA-Z]{1,2}#", $_POST["comment"], $links);
if( count($links[0]) > 0 ){
	$return['result']='error';
	$return['message']='Mensajes poseen sitios web';
	$bloquear = true;
}


$load = dirname(__DIR__,2).'/lib/recaptchalib.php';
if(file_exists($load)){
	include_once($load);
}

$response = null;
$secret = "6LeQPysUAAAAAMop3Acdau8NZVZuWHKfs1bclgV-";
$reCaptcha = new ReCaptcha($secret);

if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

if ($response != null && $response->success) {}else{
    $error = 'Debes marcar el CAPTCHA para poder enviar tu comentario.';
	$return['result']='error';
	$return['message']=$error;
}

$xip = $_SERVER['REMOTE_ADDR'];
if( $xip != "" ){

	$ip = explode(".", $xip);
	unset($ip[ count($ip)-1 ]);
	$ip = implode(".", $ip);

	$ips = $wpdb->get_results("SELECT * FROM ips WHERE ip LIKE '%$ip%'");
	if( $ips !== false && $ips[0]->intentos >= 3 ){
		$error = 'Has sido marcado como SPAM,\n
					si eres una persona por favor comunicate con el Staff Kmimos a trav&eacute;s del Mail: contactomex@kmimos.la enviando el
					siguiente código ['.$ips[0]->token.'] y atenderemos tu solicitud a la brevedad posible.';

		$return['result'] = 'error';
		$return['message'] = $error;
	}else{
		if( $bloquear ){
			if( $ips == false ){
				$token = md5(time());
				$wpdb->query("INSERT INTO ips VALUES ( NULL, '{$xip}', 1, '{$token}')");

			}else{
				$wpdb->query("UPDATE ips SET intentos = intentos + 1 WHERE id = ".$ips[0]->id);
			}

			$error = 'El comentario no debe incluir links, de seguir intentando incluirlos será marcado como SPAM.';
			$return['result']='error';
			$return['message']=$error;
			exit;
		}
	}

}else{
	$error = 'No hemos podido detectar tu direcci&oacute;n IP, esta representa tu identificador en internet, por seguridad no podemos permitir comentarios de fuentes anónimas.';
	$return['result'] = 'error';
	$return['message'] = $error;
}

if($return['result']!='error'){
	$comment = wp_handle_comment_submission(wp_unslash($_POST));
	if ( is_wp_error( $comment ) ) {
		if ( ! empty( $data ) ) {
			$return['result']='success';
			$return['message']='Comentario enviado';

		} else {
			$return['result']='error';
			$return['message']='Error enviando comentario';

		}
	}

	global $wpdb;
	$wpdb->query("UPDATE wp_comments SET comment_approved = '0' WHERE comment_ID=".$comment->comment_ID);
}

$user = wp_get_current_user();
do_action( 'set_comment_cookies', $comment, $user );

echo json_encode($return);

exit();
