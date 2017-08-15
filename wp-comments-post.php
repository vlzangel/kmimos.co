<?php
/**
 * Handles Comment Post to WordPress and prevents duplicate comment posting.
 *
 * @package WordPress
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

$bloquear = false;
preg_match_all("#<a(.*?)href(.*?)>#", $_POST["comment"], $links);
if( count($links[0]) > 0 ){
	$bloquear = true;
}
preg_match_all("#http.*?//#", $_POST["comment"], $links);
if( count($links[0]) > 0 ){
	$bloquear = true;
}
preg_match_all("#www\.(.*?)\.[a-zA-Z]{1,2}#", $_POST["comment"], $links);
if( count($links[0]) > 0 ){
	$bloquear = true;
}

/** Sets up the WordPress Environment. */
require( dirname(__FILE__) . '/wp-load.php' );

$xip = $_SERVER['REMOTE_ADDR'];
if( $xip != "" ){

	$ip = explode(".", $xip);
	unset($ip[ count($ip)-1 ]);
	$ip = implode(".", $ip);

	include("vlz_config.php");
	include("db.php");
	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);
	$ips = $db->get_results("SELECT * FROM ips WHERE ip LIKE '%$ip%'");
	if( $ips !== false && $ips[0]->intentos >= 3 ){
		$error = new WP_Error( 
			'require_valid_comment', 
			__( '
				<strong>AVISO</strong>
				<p style="text-align: justify;">
					Has sido marcado como <b>SPAM</b>, 
					si eres una persona por favor comunicate con el Staff Kmimos a trav&eacute;s del Mail: <b>contactomex@kmimos.la</b> enviando el
					siguiente c&oacute;digo <b>['.$ips[0]->token.']</b> y atenderemos tu solicitud a la brevedad posible.
				</p>' 
			), 200 );
		$data = $error->get_error_data();
		wp_die( $error->get_error_message(), $data );
		exit;
	}else{
		if( $bloquear ){
			if( $ips == false ){
				$token = md5(time());
				$db->query("INSERT INTO ips VALUES ( NULL, '{$xip}', 1, '{$token}')");
			}else{
				$db->query("UPDATE ips SET intentos = intentos + 1 WHERE id = ".$ips[0]->id);
			}
			$error = new WP_Error( 'require_valid_comment', __( '
				<strong>AVISO</strong>
				<p style="text-align: justify;">El comentario no debe incluir links, de seguir intentando incluirlos ser&aacute; marcado como <b>SPAM</b>.</p>' 
			), 200 );
			$data = $error->get_error_data();
			wp_die( $error->get_error_message(), $data );
			exit;
		}
	}

}else{
	$error = new WP_Error( 'require_valid_comment', __( '
		<strong>AVISO</strong>
		<p style="text-align: justify;">
			No hemos podido detectar tu direcci&oacute;n IP, esta representa tu identificador en internet, por seguridad no podemos permitir comentarios de fuentes an&oacute;nimas.
		</p>' 
	), 200 );
	$data = $error->get_error_data();
	wp_die( $error->get_error_message(), $data );
	exit;
}

nocache_headers();
$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
if ( is_wp_error( $comment ) ) {
	$data = $comment->get_error_data();
	if ( ! empty( $data ) ) {
		wp_die( $comment->get_error_message(), $data );
	} else {
		exit;
	}
}
$user = wp_get_current_user();
do_action( 'set_comment_cookies', $comment, $user );
$location = empty( $_POST['redirect_to'] ) ? get_comment_link( $comment ) : $_POST['redirect_to'] . '#comment-' . $comment->comment_ID;
$location = apply_filters( 'comment_post_redirect', $location, $comment );
wp_safe_redirect( $location );

exit;
