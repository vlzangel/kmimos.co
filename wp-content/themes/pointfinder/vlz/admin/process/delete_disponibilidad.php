<?php
    include("../../../../../../vlz_config.php");
    include("../funciones/kmimos_funciones_db.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    extract($_POST);

    $rangos = $db->get_var(" SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_availability' ", "meta_value");
    $rangos = unserialize($rangos);

    $db->query("UPDATE cupos SET no_disponible = 0 WHERE servicio = '{$servicio}' AND fecha >= '{$inicio}' AND fecha <= '{$fin}'");

    $rangos_2 = array();

    foreach ($rangos as $key => $value) {

        if( $value["from"] != $inicio && $value["to"] != $fin ){

            $temp = array(
                "type" => "custom",
                "bookable" => "no",
                "priority" => "10",
                "from" => $value["from"],
                "to" => $value["to"]
            );

            $rangos_2[] = $temp;

            $db->query("UPDATE cupos SET no_disponible = 1 WHERE servicio = '{$servicio}' AND fecha >= '{$value["from"]}' AND fecha <= '{$value["to"]}'");

        }

    }
    
    $rangos = serialize($rangos_2);
    $db->query(" UPDATE wp_postmeta SET meta_value = '{$rangos}' WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_availability' ");
?>