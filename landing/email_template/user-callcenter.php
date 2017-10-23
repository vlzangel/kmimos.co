<?php
$mensaje_mail_admin = '
    <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
    <p>Hola <strong>'.$username.'</strong>,</p>
    <p style="text-align: justify;">
        Abajo encontrarás tus credenciales para que tengas acceso a Kmimos.
    </p>
    <p>
        <table>
            <tr> <td> <strong>Nombre:</strong> </td><td>'.$name.'</td> </tr>
            <tr> <td> <strong>Apellido:</strong> </td><td>'.$lastname.'</td> </tr>
            <tr> <td> <strong>Tel&eacute;fono:</strong> </td><td>'.$phone.'</td> </tr>

            <tr> <td> <strong>'.$notificaciones.'</strong></td><td></td> </tr>
            <tr> <td> <strong>Correo:</strong> </td><td>'.$email.'</td> </tr>
            <tr> <td> <strong>Usuario:</strong> </td><td>'.$username.'</td> </tr>
            <tr> <td> <strong>Contraseña:</strong> </td><td>'.$password.'</td> </tr>
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