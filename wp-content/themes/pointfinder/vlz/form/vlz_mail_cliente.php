<?php

    define('WP_USE_THEMES', false);
    require('../../../../../wp-blog-header.php');

    extract($_POST);

    $mensaje_mail = '
        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
        <p>Hola <strong>'.$nombre.'</strong>,</p>
        <p style="text-align: justify;">
            Abajo encontrarás tus credenciales para que tengas acceso a Kmimos.
        </p>
        <p>
            <table>
                <tr> <td> <strong>E-mail:</strong> </td><td>'.$email.'</td> </tr>
                <tr> <td> <strong>Contraseña:</strong> </td><td>'.$clave.'</td> </tr>
            </table>
        </p>
        <p style="text-align: center;">
            <a 
                href="'.get_home_url().'/?a=inicio"
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
            >Iniciar Sesión</a>
        </p>
    ';

    add_filter( 'wp_mail_from_name', function( $name ) {
        return 'Kmimos México';
    });
    add_filter( 'wp_mail_from', function( $email ) {
        return 'contactomex@kmimos.la';
    });

    $mail_msg = kmimos_get_email_html("Registro de Nuevo Usuario.", $mensaje_mail, '', true, true);

    if ( wp_mail( $email, "Kmimos México Gracias por registrarte! Kmimos la NUEVA forma de cuidar a tu perro!", $mail_msg) ) {

        $error = array(
            "error" => "NO",
            "msg" => ""
        );
        echo "(".json_encode( $error ).")";

    } else {

        $error = array(
            "error" => "SI",
            "msg" => "No se ha podido enviar el mail."
        );
        echo "(".json_encode( $error ).")";

    }

?>