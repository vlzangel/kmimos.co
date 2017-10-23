<?php
$mensaje_mail = '
   <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
        <p>Hola <strong>'.$name.'</strong>,</p>
        <p style="text-align: justify;">
            Abajo encontrarás tus credenciales para que tengas acceso a Kmimos.
        </p>
        <p>
            <table>
                <tr> <td> <strong>E-mail:</strong> </td><td>'.$email.'</td> </tr>
                <tr> <td> <strong>Contraseña:</strong> </td><td> Debes generar tu clave en Olvido Contraseña </td> </tr>
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