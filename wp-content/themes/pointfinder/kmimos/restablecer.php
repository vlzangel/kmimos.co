<?php
	define('WP_USE_THEMES', false);
    require('../../../../wp-blog-header.php');

    extract($_POST);

    $info = kmimos_get_info_syte();

    add_filter( 'wp_mail_from_name', function( $name ) {
        global $info;
        return $info["titulo"];
    });
    add_filter( 'wp_mail_from', function( $email ) {
        global $info;
        return $info["email"]; 
    });

    global $wpdb;

    $user = $wpdb->get_row("SELECT * FROM wp_users WHERE user_email = '{$email}'");

    if( $user->ID == "" ){
    	$respuesta = array(
	    	"code" => 2,
	    	"msg"  => "El email ingresado no se encuentra registrado."
	    );
    }else{
        update_user_meta( $user->ID, 'clave_temp', $clave );

        $mensaje = '
            <h1>¡Nueva Contraseña Temporal!</h1>
            <p style="text-align: justify;">Hola <strong>'.$user->display_name.'</strong>,</p>
            <p style="text-align: justify;">
                Hemos recibido tu solicitud para restablecer tu contraseña en Kmimos.
            </p>
            <p style="text-align: justify;">
                Para restablecer tu contraseña por favor haz click en el botón de abajo.
            </p>
            <p style="text-align: center;">
                <a  target="_blank"
                    href="'.get_home_url().'/restablecer/?r='.md5($user->ID).'" 
                    style="
                        padding: 10px;
                        background: #59c9a8;
                        color: #fff;
                        font-weight: 400;
                        font-size: 17px;
                        font-family: Roboto;
                        border-radius: 3px;
                        border: solid 1px #1f906e;
                        display: block;
                        max-width: 300px;
                        margin: 0px auto;
                        text-align: center;
                        text-decoration: none;
                    "
                >Click para restablecer la contraseña</a>
            </p>
            <p style="text-align: justify;">
                <strong>Si no has solicitado cambiar tu contraseña, no te preocupes, solo ignora este correo y tu actual contraseña permanecerá activa.</strong>
            </p>
        ';

        $send = kmimos_get_email_html("", $mensaje, '', true, true);

        wp_mail( $user->user_email, "Kmimos ".$info["pais"]." – Restablecimiento de Contraseña! Kmimos la NUEVA forma de cuidar a tu perro!", $send);

        $respuesta = array(
	    	"code" => 1,
	    	"msg"  => "<div class='pfrevoverlaytext pfoverlayapprove'><i class='pfadmicon-glyph-62'></i><span>Hemos enviado los pasos para restablecer la contraseña a tu correo.</span></div>"
	    );
    }

    echo "(".json_encode( $respuesta ).")";
?>