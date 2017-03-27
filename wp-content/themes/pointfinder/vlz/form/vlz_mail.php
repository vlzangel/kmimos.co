<?php

    define('WP_USE_THEMES', false);
    require('../../../../../wp-blog-header.php');

    extract($_POST);
    add_filter( 'wp_mail_from_name', function( $name ) {
       return 'Kmimos Colombia';
   });
   add_filter( 'wp_mail_from', function( $email ) {
       return 'kmimos@kmimos.la';
   });
    /*$mensaje = '
        <style>
            p{
                text-align: justify;
            }
            a:hover{
                background: #038063;
            }
        </style>
        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
        <p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>
        <p>
            Felicidades, has logrado crear tu perfil exitosamente en kmimos, por los momentos el mismo se encuentra inactivo, para activarlo deberás
            aprobar satisfactoriamente el proceso de certificación.
        </p>
        <p>
            Abajo encontrarás tus credenciales para que tengas acceso como cuidador a Kmimos, estos mismos los deberás usar en la plataforma de certificación.
        </p>
        <p>
            <table>
                <tr> <td> <strong>Usuario:</strong> </td><td>'.$usuario.'</td> </tr>
                <tr> <td> <strong>Contraseña:</strong> </td><td>'.$clave.'</td> </tr>
            </table>
        </p>
        <p>
            Para iniciar el proceso de certificación, por favor has click en el siguiente botón:
        </p>
        <p style="text-align: center;">
            <a 
                href="http://www.ilernus.com/kmimos/lms/login/index.php"
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
            >Iniciar Proceso de Certificación</a>
        </p>
    ';*/

    $mensaje_mail = '
        <style>
            p{
                text-align: justify;
            }
            a:hover{
                background: #038063;
            }
        </style>
        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
        <p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>
        <p style="text-align: justify;">
            Estimado Kmiamigo, tu perfil ha sido creado con éxito.  El mismo permanecerá inactivo en la página hasta que completes los siguientes pasos listados abajo.
        </p>
        <p style="text-align: justify;">
            "Dichos pasos han sido diseñados para cumplir con un estricto perfil de seguridad, que garantice que cualquier persona que se convierta en Cuidador asociado Kmimos presente un perfil apto para cuidar y consentir a nuestros peludos amigos"
        </p>
 
        <p style="text-align: justify;">
            <strong>Siguientes Pasos para activar tu perfil</strong>
        </p>
        <p style="text-align: justify;">
            <ul>
                <li>Compártenos por Mensaje Directo a nuestro Facebook @KmimosCO tu nombre y apellido completo, email, teléfono de casa y celular.</li>
                <li>Una vez que nos envíes dichos datos, en menos de 24 horas recibirás en el correo que registraste las Pruebas Psicométricas y Pruebas de Conceptos Veterinarios básicos.  Por favor respóndelas, y nos llegará a nosotros un mensaje de completadas.</li>
                <li>En menos de 24 horas después de completadas las pruebas recibirás un correo por parte de Certificación Kmimos, notificando tus resultados.  NO TE OLVIDES DE MIRAR SIEMPRE LA BANDEJA DE ENTRADA O EL CORREO NO DESEADO, ya que a veces llegan allí los correos.</li>
                <li>En caso de haber aprobado, lee el archivo adjunto al correo que te muestra las políticas operativas.</li>
                <li>Por último, recibirás una llamada para entrevista telefónica y notificación para la auditoría a tu hogar.</li>
            </ul>
        </p>
        <p style="text-align: justify;">
            <strong>Abajo encontrarás tus credenciales para que tengas acceso como cuidador a Kmimos, estos mismos los deberás usar en la plataforma de certificación.</strong>
        </p>
        <p>
            <table>
                <tr> <td> <strong>Usuario: </strong> </td><td>'.$usuario.'</td> </tr>
                <tr> <td> <strong>Contraseña: </strong> </td><td>'.$clave.'</td> </tr>
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

    $mensaje_web = '
        <style>
            p{
                text-align: justify;
            }
            a:hover{
                background: #038063;
            }
        </style>
        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
        <p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>
        <p style="text-align: justify;">
            Estimado Kmiamigo, tu perfil ha sido creado con éxito.  El mismo permanecerá inactivo en la página hasta que completes los siguientes pasos listados abajo.
        </p>
        <p style="text-align: justify;">
            "Dichos pasos han sido diseñados para cumplir con un estricto perfil de seguridad, que garantice que cualquier persona que se convierta en Cuidador asociado Kmimos presente un perfil apto para cuidar y consentir a nuestros peludos amigos"
        </p>
        <p style="text-align: justify;">
            <strong>Siguientes Pasos para activar tu perfil</strong>
        </p>
        <p style="text-align: justify;">
            <ul>
                <li style="text-align: justify;">Compártenos por Mensaje Directo a nuestro Facebook @Kmimosco tu nombre y apellido completo, email, teléfono de casa y celular.</li>
                <li style="text-align: justify;">Una vez que nos envíes dichos datos, en menos de 24 horas recibirás en el correo que registraste las Pruebas Psicométricas y Pruebas de Conceptos Veterinarios básicos.  Por favor respóndelas, y nos llegará a nosotros un mensaje de completadas.</li>
                <li style="text-align: justify;">En menos de 24 horas después de completadas las pruebas recibirás un correo por parte de Certificación Kmimos, notificando tus resultados.  NO TE OLVIDES DE REVISAR SIEMPRE LA BANDEJA DE ENTRADA O EL CORREO NO DESEADO, ya que a veces llegan allí los correos.</li>
                <li style="text-align: justify;">En caso de haber aprobado, lee el archivo adjunto al correo que te muestra las políticas operativas.</li>
                <li style="text-align: justify;">Por último, recibirás una llamada para entrevista telefónica y notificación para la auditoría a tu hogar.</li>
            </ul>
        </p>
    ';

    $mail_msg = kmimos_get_email_html("Gracias por registrarte como cuidador.", $mensaje_mail, 'Registro de Nuevo Cuidador.', true, true);

    wp_mail( $email, "Kmimos Colombia – Gracias por registrarte como cuidador! Kmimos la NUEVA forma de cuidar a tu perro!", $mail_msg);

    echo $mensaje_web;
?>