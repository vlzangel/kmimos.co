<?php
/*
$mensaje_mail = '<style>
					p{
                                text-align: justify;		
                            }		
                            a:hover{		
                                background: #038063;		
                            }

                                .vlz_modal_ventana{max-width: 800px;}
                            	.vlz_modal_contenido{padding: 20px !important;}
                        </style>		
                        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>		
                        <p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>		
		
                        <p style="text-align: justify;">		
                            Estimado Kmiamigo, tu perfil ha sido creado con éxito.  El mismo permanecerá inactivo en la página hasta que completes los siguientes pasos listados abajo"		
                        </p>		
                        <p style="text-align: justify;">		
                            "Dichos pasos han sido diseñados para cumplir con un estricto perfil de seguridad, que garantice que cualquier persona que se convierta en Cuidador asociado Kmimos presente un perfil apto para cuidar y apapachar a nuestros peludos amigos"		
                        </p>		
                        <p style="text-align: justify;">		
                            <strong>Siguientes Pasos para activar tu perfil</strong>		
                        </p>		
                        <p style="text-align: justify;">		
                            <ul style="text-align: justify;">
                                <li>Compártenos por Mensaje Directo a nuestro Facebook @Kmimosmx tu nombre y apellido completo, email, teléfono de casa y celular</li>		
                                <li>Una vez que nos envíes dichos datos, en menos de 24 horas recibirás en el correo que registraste las Pruebas Psicométricas y Pruebas de Conceptos Veterinarios básicos.  Por favor respóndelas, y nos llegará a nosotros un mensaje de completadas.</li>		
                                <li>En menos de 24 horas después de completadas las pruebas recibirás un correo por parte de Certificación Kmimos, notificando tus resultados.  NO TE OLVIDES DE CHECAR SIEMPRE LA BANDEJA DE ENTRADA O EL CORREO NO DESEADO, ya que a veces llegan allí los correos.</li>		
                                <li>En caso de haber aprobado, lee el archivo adjunto al correo que te muestra las políticas operativas.</li>		
                                <li>Por último, recibirás una llamada para entrevista telefónica y notificación para la auditoría a tu hogar.</li>		
                            </ul>		
                        </p>		
                        <p style="text-align: justify;">		
                            <strong>Abajo encontrarás tus credenciales para que tengas acceso como cuidador a Kmimos, estos mismos los deberás usar en la plataforma de certificación.</strong>		
                        </p>		
                        <p>		
                            <table>		
                                <tr> <td> <strong>Usuario:</strong> </td><td>'.$username.'</td> </tr>		
                                <tr> <td> <strong>Contraseña:</strong> </td><td>La clave que ingresaste</td> </tr>		
                            </table>		
                        </p>		
                        <p style="text-align: center;">		
                            <a 		
                                href="https://kmimos.ilernus.com"
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
                    ';//'.get_home_url().'/?a=inicio

*/

$mensaje_mail = '
<style>
    p{
        text-align: justify;
    }
    a:hover{
        background: #038063;
    }
    .vlz_modal_ventana{max-width: 800px;}
    .vlz_modal_contenido{padding: 20px !important;}
</style>
<h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
<p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>

<p style="text-align: justify;">
    Estimado Kmiamigo, tu perfil ha sido creado con éxito.  El mismo permanecerá <strong>INACTIVO</strong>  en la página hasta que completes tu proceso de certificación que consta:
</p>
<p style="text-align: justify;">
    <ul style="text-align: justify;">
        <li><strong>Pruebas de conocimiento veterinario.</strong></li>
        <li><strong>Pruebas Psicométricas.</strong></li>
        <li><strong>Documentación (IFE, Comprobante de domicilio y Datos Bancarios).</strong></li>
    </ul>
</p>
<p style="text-align: justify;">
   <strong>Link para continuar es: http://kmimos.ilernus.com</strong>
</p>
<p style="text-align: justify;">
    Dicho proceso ha sido diseñado por expertos veterinarios para seleccionar a las personas más adecuadas para recibir, cuidar y apapachar a nuestros peludos amigos.
</p>
<p style="text-align: justify;">
    Guarda el siguiente link, ahí puedes continuar con las pruebas en caso de no terminarlas por algún imprevisto y/o para cargar documentos.
</p>
<p style="text-align: justify; color: #f00;">
    <strong>INGRESA CON EL NOMBRE DE USUARIO Y CONTRASEÑA:</strong>
</p>
<p>
    <table>
        <tr> <td> <strong>Usuario:</strong> </td><td>'.$username.'</td> </tr>
        <tr> <td> <strong>Contraseña:</strong> </td><td>'.$clave.'</td> </tr>
    </table>
</p>

<p>
    Revisa tu correo, ahí te llegarán las segundas pruebas, PRUEBAS PSICOMETRICAS.
</p>
<p>
    ¡EXITO!
</p>
<p style="text-align: center;">
    <a
        href="https://kmimos.ilernus.com"
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
    >CONTINUAR</a>
</p>
';