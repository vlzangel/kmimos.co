<?php
$mensaje_mail = '
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>CLUB DE LAS PATITAS FELICES</title>
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> 
        <style>
          body {
            font-family: "Lato", sans-serif;
          }
        </style>
    </head>
    <body style="min-width: 600px; max-width: 600px; border:1px solid #ccc; margin:auto auto; font-size: 18px;  ">
        <div style="clear:both;background:#881C9B;">
            <h1 style="margin:0px;padding:30px;text-align: center;color:#fff;">
                <span style="font-size: 44px;">¡Gracias por unirte a nuestra familia Kmimos!</span>
            </h1>
        </div>
        <section style="text-align: center;">
            <img src="/landing/img/logo_patitas.png" alt="" width="25%">
            <div style="margin: auto; width: 70%; text-align: justify; padding-bottom:10px;color:#757575;">
                <p>Hola <strong>'.$name.'</strong>,</p>
                <p style="text-align: justify;">
                    Abajo encontrarás tus credenciales para que tengas acceso a Kmimos.
                </p>
                <p>
                    <table>
                        <tr> <td> <strong>E-mail:</strong> </td><td>'.$email.'</td> </tr>
                        <tr> <td> <strong>Contraseña:</strong> </td><td> Debes recuperar tu contraseña </td> </tr>
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
                <span>Atentamente;</span><br><br>   
                <img src="/landing/img/logo-kmimos_120x30.png" alt="">
            </div>
            <br>
            <div style="color:#fff;width: 70%; text-align: justify; padding:15px 60px 15px 60px; background:#881C9B; margin:auto;">
                <p>Recuerda que por cada uno de tus referidos que reserve una noche con kmimos, recibir&aacute;s $150 de regalo en reservaciones.</p>
            </div>
            <div style="background: orange; font-size: 30px; color:#fff; font-weight: bold;padding:20px;">
                <div style="text-align: left; padding-bottom:30px;">
                    <span>Cuando sales de viajes, &iquest;En donde dejas a tu perro?</span>
                </div>
                <div style="text-align: right; font-size: 22px;">
                    <img src="/landing/img/logo-kmimos-blanco.png" alt="" width="120px"><br>
                    <span>LIBRES DE JAULAS Y ENCIERROS</span>
                </div>
            </div>
            <div style="padding:30px;text-align:center; background: url("/landing/img/background-section.png");">
                imagen de casa
            </div>
            <div style="padding:20px;font-size: 22px; text-align: center;color:#fff; background: #00b190; ">
                <span>La mascota se hospeda en la propia casa de cuidadores certificados</span>
            </div>

        </section>
        <div style="padding:40px;font-size: 22px; text-align: center;color:#fff; background: #00D8B5; ">
            <span>&iquest;YA CONOCES KMIMOS? Te invitamos</span>
            <span>a ingresar a www.kmimos.com.mx o por nuestros teléfonos +52 (55) 1791.4931</span>
        </div>
    </body>
    </html>   
';