<?php

    /**
     *  Devuelve el contenido formateado de los correos.
     * */

    if(!function_exists('kmimos_get_email_header')){

        function kmimos_get_email_header(){        
            $html  = '
            <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <meta name="viewport" content="width=320, target-densitydpi=device-dpi">
                    
                    <style>
                        .body {margin: 0;padding: 20px 0px;background-color: #cccccc;min-height: 320px;}
                        .wrap {max-width: 640px; margin: 0px auto; background-color: #ffffff;}
                        .header {border-top: 3px solid #f25555; background-color: #00d2b7; color: #494949; padding: 30px;}
                        .container {min-height: 200px; padding: 30px; margin-top: 0px; font-family: HelveticaNeue, sans-serif; text-align: left;font-size: 13px; line-height: 18px; color: #444; }
                        .title {font-size: 18px; line-height: 24px; color: #7d7d7d; font-weight: bold; }
                        .gretting, .content {margin-top: 20px;}
                        .gretting span {font-size: 14px; line-height: 18px; color: #7d7d7d; font-weight: bold;}
                        .gretting img { margin: 10px 0; }
                        .footer {border-bottom: 3px solid #f25555; background-color: #00d2b7; font-size: 14px;font-family: HelveticaNeue, sans-serif; color: #494949; padding: 30px; }
                    </style>
                </head>
                <body>
                    <div class="body">
                        <div class="wrap">
                            <div class="header">
                                <a href="'.get_home_url().'">
                                    <img src="'.get_home_url().'/wp-content/uploads/2016/02/logo-kmimos.png" alt="Logo Kmimos">
                                </a>
                            </div>';
            return $html;
        }
    }

/**
 *  Devuelve el contenido formateado de los correos.
 * */

if(!function_exists('kmimos_get_email_html')){

    function kmimos_get_email_html($title, $content, $gretting='', $banners=false, $body=false){

        if($gretting=='') $gretting='Atentamente,';

        if($body){ 
            $html = '';
            $html .= '<!DOCTYPE html>';
            $html .= '<html>';
            $html .= '  <head>';
            $html .= '      <meta charset="UTF-8">';
            $html .= '      <meta name="viewport" content="width=device-width, initial-scale=1.0">';
            $html .= '      <title>'.$title.'</title>';
            $html .= '  </head>';
            $html .= '  <body>';
        }else{ 
            $html = '';
        }

        $html .= kmimos_get_email_header();

        $html .= '<div class="container">';
        $html .= '  <span class="title">'.$title.'</span>';
        $html .= '  <div class="content">'.$content.'</div>';
        $html .= '  <div class="gretting">';
        $html .= '      <span>'.$gretting.'</span><br>';
        $html .= '      <img src="'.get_home_url().'/wp-content/uploads/2016/03/logo-kmimos_120x30.png" alt="Firma Kmimos">';
        $html .= '  </div>';

        if($banners) $html .= kmimos_get_email_banners();

        $html .= '</div>';

        $html .= kmimos_get_email_footer();

        if($body){ $html .= '</body></html>'; }

        return $html;
    }

}

/*

*   Introduce los banners

*/

if(!function_exists('kmimos_get_email_banners')){

    function kmimos_get_email_banners(){

        $html  = '';
        $html .= '
        <div>
            <div style="font-size:0.7em; color:#cccccc;">Publicidad</div>
            <ul style="overflow: hidden; padding: 0px;">
                <li style="float:left; margin: 5px; width: 48%; list-style: none;">
                    <a style="display: block;" href="http://www.booking.com/index.html?aid=1147066&lang=es">
                        <img style="width: 100%;" src="'.get_home_url().'/wp-content/uploads/2016/03/Banner-ofertas-hoteles300x100.png" alt="Booking-Kmimos">
                    </a>
                </li>
                <li style="float:left; margin: 5px; width: 48%; list-style: none;">
                        <img style="width: 100%;" src="'.get_home_url().'/wp-content/uploads/2016/03/Banner-accesorios300x100.png" alt="Accesorios-Mascotas">                    
                </li>
                <li style="float:left; margin: 5px; width: 48%; list-style: none;">
                    <a style="display: block;" href="https://www.volaris.com/">
                        <img style="width: 100%;" src="'.get_home_url().'/wp-content/uploads/2016/03/Banner-boletos-aereos300x100.png" alt="Boletos-aereos">
                    </a>
                    
                </li>
                <li style="float:left; margin: 5px; width: 48%; list-style: none;">
                    <a style="display: block;" href="https://cabify.com/mexico/mexico-city">
                        <img style="width: 100%;" src="'.get_home_url().'/wp-content/uploads/2016/03/Banner-transporte-mascotas300x100.png" alt="Transporte-Mascotas">
                    </a>
                    
                </li>
            </ul>
        </div>';

        return $html;
    }

}

/**
 *  Devuelve el contenido formateado de los correos.
 **/

if(!function_exists('kmimos_get_email_footer')){
    function kmimos_get_email_footer(){
        $html  = '<style> .footer {border-bottom: 3px solid #f25555; background-color: #00d2b7; font-size: 14px;font-family: HelveticaNeue, sans-serif; color: #494949; padding: 30px;} </style>';
        $html .= '<div class="footer"><span>Más información en <a href="'.get_home_url().'">'.$_SERVER['HTTP_HOST'].'</a> o por nuestros teléfonos </span><span><strong>+52 (55) 1791.4931</strong><span></div></div></div>';
        return $html;
    }

}

?>