<?php

    session_start();
    
    require('../wp-load.php');

    date_default_timezone_set('America/Mexico_City');

    global $wpdb;

    $inicio = "08";
    $fin    = "22";
    $rango  = 8;

    $hora_actual = strtotime("now");
    $xhora_actual = date("H", $hora_actual);

    $acc = "CCL"; $usu = "STM";

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
        $sql = "
            SELECT ID, post_type FROM wp_posts WHERE 
            post_type IN (
                'request',
                'shop_order'
            ) AND
            post_status IN (
                'pending',
                'wc-completed',
                'wc-processing',
                'wc-partially-paid'
            ) AND post_date < '".date("Y-m-d H:i:s", $fecha_cancelacion)."'
        ";
        $r = $wpdb->get_results( $sql );

        foreach ($r as $request) {

            if( $request->post_type == "request" ){
                $id_orden = $request->ID;

                include( "../wp-content/themes/kmimos/procesos/conocer/index.php");
            }else{
                $id_orden = $request->ID;

                include( "../wp-content/themes/kmimos/procesos/reservar/emails/index.php");
            }

        }

    }

?>