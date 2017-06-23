<?php
    
    require('../wp-load.php');
    require('../wp-content/themes/pointfinder/vlz/vlz_funciones.php');

    require('./cron_sqls.php');
    require('./cron_funciones.php');

    date_default_timezone_set('America/Mexico_City');

    global $wpdb;

    $inicio = "08";
    $fin    = "22";
    $rango  = 8;

    $hora_actual = strtotime("now");
    $xhora_actual = date("H", $hora_actual);

    if( ($xhora_actual-$rango) < $inicio ){
        $hoy = date("d-m-Y", $hora_actual);
        $hoy = explode("-", $hoy);
        $hoy = strtotime($hoy[0]."-".$hoy[1]."-".$hoy[2]." ".$inicio.":00:00");
        $ayer = date("d-m-Y", strtotime("-1 day"));
        $ayer = explode("-", $ayer);
        $ayer = strtotime($ayer[0]."-".$ayer[1]."-".$ayer[2]." ".$fin.":00:00");
        $exceso = $hoy-($hora_actual-($rango*3600));
        $fecha_cancelacion = $ayer-$exceso;
    }else{
        $fecha_cancelacion = ($hora_actual-($rango*3600));
    }

    if( $xhora_actual < $fin ){

        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body{
                    margin: 0px;
                }
            </style>';

        $sql = "
            SELECT 
                * 
            FROM 
                wp_posts 
            WHERE 
                post_type IN (
                    'request',
                    'shop_order'
                ) AND
                post_status IN (
                    'pending',
                    'wc-completed',
                    'wc-processing',
                    'wc-partially-paid'
                ) AND 
                post_date < '".date("Y-m-d H:i:s", $fecha_cancelacion)."'
        ";
        $r = $wpdb->get_results( $sql );

        foreach ($r as $request) {
            $metadata = get_post_meta( $request->ID );

            $cliente_id_reserva = 0;
            if( $request->post_type == "request" ){
                $id_cuidador_post = $metadata['requested_petsitter'][0];
                $email_cliente = $wpdb->get_var( "SELECT user_email FROM wp_users WHERE ID = '".$request->post_author."'" );
                $cliente = get_user_meta( $request->post_author );
                $cliente = $cliente['first_name'][0]." ".$cliente['last_name'][0];
            }else{
                $id_orden = $request->ID;
                $id_reserva = $id_orden-1;
                $cliente_id = $wpdb->get_var( "SELECT post_author FROM wp_posts WHERE ID = '".($id_reserva)."'" );
                $cliente_id_reserva = $cliente_id;
                $email_cliente = $wpdb->get_var( "SELECT user_email FROM wp_users WHERE ID = '".$cliente_id."'" );
                $cliente = get_user_meta( $cliente_id );
                $cliente = $cliente['first_name'][0]." ".$cliente['last_name'][0];

                $metadata = get_post_meta( $id_orden-1 );
                $user_id_cuidador = $wpdb->get_var( "SELECT post_author FROM wp_posts WHERE ID = '".($metadata['_booking_product_id'][0])."'" );
                $id_cuidador_post = $wpdb->get_var( "SELECT id_post FROM cuidadores WHERE user_id = '".($user_id_cuidador)."'" );
            }

            $cuidador = $wpdb->get_row( "SELECT * FROM cuidadores WHERE id_post = '".$id_cuidador_post."'" );

            $email_cuidador = $cuidador->email;

            $lat = $cuidador->latitud;
            $lon = $cuidador->longitud;

            $cuidador_post = $wpdb->get_row( "SELECT * FROM wp_posts WHERE ID = '".$id_cuidador_post."'" );

             $sql = "
                SELECT 
                    DISTINCT id,
                    ROUND ( ( 6371 * 
                        acos(
                            cos(
                                radians({$lat})
                            ) * 
                            cos(
                                radians(latitud)
                            ) * 
                            cos(
                                radians(longitud) - 
                                radians({$lon})
                            ) + 
                            sin(
                                radians({$lat})
                            ) * 
                            sin(
                                radians(latitud)
                            )
                        )
                    ), 2 ) as DISTANCIA,
                    id_post,
                    hospedaje_desde
                FROM 
                    cuidadores
                WHERE
                    id_post != {$id_cuidador_post} AND
                    portada = 1 AND
                    activo = 1
                ORDER BY DISTANCIA ASC
                LIMIT 0, 4

            ";
     
            $sugeridos = $wpdb->get_results( $sql );

            foreach ($sugeridos as $key => $cuidador) {

                $name_photo = get_user_meta($author_post_id, "name_photo", true);
                $cuidador_id = $cuidador->id;

                if( empty($name_photo)  ){ $name_photo = "0"; }
                if( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}") ){
                    $img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}";
                }elseif( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg") ){
                    $img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg";
                }else{
                    $img = get_template_directory_uri().'/images/noimg.png';
                }
                
                $post = get_post($cuidador->id_post);

                $str_sugeridos_img .= '
                    <div style="display: inline-block; width: 49%; text-align: center; min-width: 239px;">
                        <a href="'.get_home_url().'/petsitters/'.$post->post_name.'/" target="_blank" style="display: block; margin: 5px; text-decoration: none;">
                            <div style="background: #e4e4e4; border: solid 1px #CCC;">
                                <img src="'.$img.'" height="130" style="max-width: 100%; max-height: 100%; padding: 3px;" />
                            </div>
                            <div style="
                                text-align: left;
                                font-size: 16px;
                                font-weight: 600;
                                color: #00d2b7;
                                padding: 5px;
                                border: solid 1px #CCC;
                                border-top: 0;
                                border-bottom: 0;">
                                '.$post->post_title.'
                            </div>
                            <div style="
                                text-align: right;
                                font-size: 13px;
                                font-weight: 600;
                                color: #7d7d7d;
                                padding: 0px 5px 10px;
                                border: solid 1px #CCC;
                                border-top: 0;">
                                Hospedaje desde<br>
                                MXN $'.$cuidador->hospedaje_desde.'
                            </div>
                        </a>
                    </div>
                ';
            }

            $str_sugeridos = '
                <ol class="lista" style="padding-left: 10px;"> 
                    <li align="justify" style="font-size: 12px;">
                        Revisa estas recomendaciones y pícale a cualquiera de ellas para ver más detalles sobre su perfil.
                        <div style="overflow: hidden; text-align: center; margin: 0px auto; max-width: 600px;">'.$str_sugeridos_img.'</div>
                    </li>
                    <li align="justify" style="padding-bottom: 10px; font-size: 12px;">En caso de que alguna de estas opciones no se adecúe a tus necesidades, por favor ingresa a <strong><a style="text-decoration: none; color: #3d68b9;" href="'.get_home_url().'/busqueda">Kmimos México</a></strong> en donde podrás encontrar cientos de cuidadores que seguro te encantarán.</li>
                    <li align="justify" style="font-size: 12px;">Para asistencia personalizada por favor márcanos a nuestros números. +52 (55) 1791.4931.</li>
                </ol>
            ';

            require('./cron_plantilla.php'); 
            $msg_cliente = kmimos_get_email_html($title, $message, '', true, true);

            $info = kmimos_get_info_syte();
            add_filter( 'wp_mail_from_name', function( $name ) {
                global $info;
                return $info["titulo"];
            });
            add_filter( 'wp_mail_from', function( $email ) {
                global $info;
                return $info["email"]; 
            });

            $email_admin = $info["email"];

            if( $request->post_type == "request" ){
                
                wp_mail($email_cliente, $title, $msg_cliente);

                $sql = "UPDATE wp_postmeta SET meta_value='4' WHERE post_id='".$request->ID."' AND meta_key = 'request_status';";
                $wpdb->query( $sql );
                $sql = "UPDATE wp_posts SET post_status = 'draft' WHERE ID='".$request->ID."';";
                $wpdb->query( $sql );

                $msg = $styles.'
                    <p><strong>Cancelación de Solicitud para Conocer Cuidador (N°. '.$request->ID.')</strong></p>
                    <p>Hola <strong>Administrador</strong>,</p>
                    <p align="justify">Te notificamos que el sistema ha cancelado la Solicitud para Conocer Cuidador N° <strong>'.$request->ID.'</strong> por inactividad.</p>'
                    .'
                        <p align="justify">
                            Esta son las sugerencias que se le enviaron al cliente:
                        </p>
                    '
                    .$str_sugeridos;
                
                $msg_admin = kmimos_get_email_html("Solicitud para Conocer Cuidador Cancelada por el Sistema", $msg, "", true, true);
                wp_mail( $email_admin, "Solicitud para Conocer Cuidador Cancelada por el Sistema", $msg_admin, kmimos_mails_administradores());

                $msg_cuidador = $styles.'
                    <p><strong>Cancelación de Reserva (N°. '.$request->ID.')</strong></p>
                    <p>Hola <strong>'.$cuidador_post->post_title.'</strong>,</p>
                    <p align="justify">Te notificamos que el sistema ha cancelado la Solicitud para Conocerte N° <strong>'.$request->ID.'</strong> por inactividad.</p>';
                
                $msg_cuidador = kmimos_get_email_html("Solicitud para Conocerte Cancelada por el Sistema", $msg_cuidador, "", true, true);
                wp_mail( $email_cuidador, "Solicitud para Conocerte Cancelada por el Sistema", $msg_cuidador);

            }else{

                wp_mail($email_cliente, $title, $msg_cliente);

                $order = new WC_Order($id_orden);
                $booking = new WC_Booking($id_reserva);

                $order->update_status('wc-cancelled');
                $booking->update_status('cancelled');

                kmimos_set_kmisaldo($cliente_id_reserva, $id_orden, $id_reserva);

                $msg = $styles.'
                    <p><strong>Cancelación de Reserva (N°. '.$id_reserva.')</strong></p>
                    <p>Hola <strong>Administrador</strong>,</p>
                    <p align="justify">Te notificamos que el sistema ha cancelado la reserva N° <strong>'.$id_reserva.'</strong> por inactividad.</p>'
                    .'
                        <p align="justify">
                            Esta son las sugerencias que se le enviaron al cliente:
                        </p>
                    '
                    .$str_sugeridos;
                
                $msg_admin = kmimos_get_email_html("Reserva Cancelada por el Sistema", $msg, "", true, true);
                wp_mail( $email_admin, "Reserva Cancelada por el Sistema", $msg_admin, kmimos_mails_administradores());

                $msg_cuidador = $styles.'
                    <p><strong>Cancelación de Reserva (N°. '.$id_reserva.')</strong></p>
                    <p>Hola <strong>'.$cuidador_post->post_title.'</strong>,</p>
                    <p align="justify">Te notificamos que el sistema ha cancelado la reserva N° <strong>'.$id_reserva.'</strong> por inactividad.</p>';
                
                $msg_cuidador = kmimos_get_email_html("Reserva Cancelada por el Sistema", $msg_cuidador, "", true, true);
                wp_mail( $email_cuidador, "Reserva Cancelada por el Sistema", $msg_cuidador);

            }

        }

    }

?>