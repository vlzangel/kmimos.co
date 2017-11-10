<?php

    if(!function_exists('get_email_html')){
        
        function get_email_html($content, $dudas = true, $beneficios = true){

            $ayuda = "";
            if( $dudas ){
                $ayuda = "
                    <div style='float:left; width:600px; margin-bottom: 31px;'>   
                        <div style='text-align:center;'>
                            <p style='font-family: Arial; font-size:14px; color:#666; text-align: center; '>
                                En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono (01) 55 4742 3162, Whatsapp <a href='telf:+525568922182' target='_blank' style='text-decoration: none;'>+52 (55) 6892 2182</a>, o al correo 
                                <a href='mailto:contactomex@kmimos.la' target='_blank' style='text-decoration: none; '>contactomex@kmimos.la</a>
                            </p>
                            <div  style='clear:both;'></div>
                        </div>
                        <div  style='clear:both;'></div>
                    </div>
                ";
            }

            $beneficios_txt = "";
            if( $beneficios ){
                $beneficios_txt = "
                    <div style='font-family: Arial; font-size: 12px; font-weight: bold; letter-spacing: 0.2px; color: #6b1c9b; margin-bottom: 10px;'>
                        CON LA CONFIANZA Y SEGURIDAD QUE NECESITAS
                    </div>

                    <img style='margin-bottom: 16px;' src='".get_home_url()."/wp-content/themes/kmimos/images/emails/caracteristicas.png' >
                ";
            }

            $html = "
            <html>
                <head> </head>
                <body>
                    <div style='font-family: Arial;'>
                        <div style='margin: 0px auto; padding: 0px; width: 600px;'>
                            <div style='text-align:center;'>
                                <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/bitmap.png' style='margin-bottom: 14px;' />
                            </div>

                            ".$content."

                            <div style='text-align:center;'>
                                
                                ".$ayuda."

                                ".$beneficios_txt."

                                <img style='margin-bottom: 30px;' src='".get_home_url()."/wp-content/themes/kmimos/images/emails/dog_footer.png' >

                                <div style='background-color:#000000; color: #fff; display: table; width: 100%; height: 62px; font-size: 11px; letter-spacing: 0.2px; padding: 0px; box-sizing: border-box;'>

                                    <div style='display: table-cell; width: 240px; vertical-align: middle; text-align: left; padding-right: 15px; padding-left: 30px;'>
                                        <a href='".get_home_url()."' style='color: #FFF; text-decoration: none;'>
                                            <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/kamimos_footer.png' style='height: 21px;float:left;'> 
                                        </a>
                                    </div>

                                    <div style='display: table-cell; width: 240px; vertical-align: middle; text-align: right; padding-left:15px; padding-right: 30px;'>
                                        <span style='display: inline-block; padding: 0px 5px 2px 0px; float:right'>
                                            Síguenos en 
                                            <a href='https://www.facebook.com/Kmimosmx/'>
                                                <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/icono_facebook.png' style='vertical-align: bottom;' align='center'>
                                            </a>
                                        </span> 
                                    </div>

                                </div>

                                <p style='text-align: center; font-family: Arial; font-size: 11px; line-height: 1.73; padding: 10px;'>
                                    ¿Tienes dudas? | <a href='".get_home_url()."/contacta-con-nosotros/'>Contáctanos</a>
                                </p>
                            </div>
                        </div>      
                    </div>
                </body>
            </html>";

            return $html;
        }
    }

?>