<?php
    include("../../../../../../vlz_config.php");
    include("../funciones/kmimos_funciones_db.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    extract($_POST);

    if( $servicio != 'Todos' ){

        $rangos = $db->get_var(" SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_availability' ", "meta_value");
        $rangos = unserialize($rangos);

        $temp = array(
        	"type" => "custom",
            "bookable" => "no",
            "priority" => "10",
            "from" => $inicio,
            "to" => $fin
        );

        $rangos[] = $temp;
        
        $rangos = serialize($rangos);
        $db->query(" UPDATE wp_postmeta SET meta_value = '{$rangos}' WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_availability' ");

        $autor = $db->get_var("SELECT post_author FROM wp_posts WHERE ID = '{$servicio}' ", "post_author");
        $acepta = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_qty' ", "meta_value");

        $inicio = strtotime($inicio);
        $fin = strtotime($fin);

        for ($i=$inicio; $i <= $fin; $i+=86400) { 
            $fecha = date("Y-m-d", $i);

            $existe = $db->get_var("SELECT id FROM cupos WHERE servicio = '{$servicio}' AND fecha = '{$fecha}'");

            if( $existe != false ){
                $db->query("UPDATE cupos SET no_disponible = 1 WHERE servicio = '{$servicio}' AND fecha = '{$fecha}'");
            }else{
            
                $tipo = $db->get_var(
                    "
                        SELECT
                            tipo_servicio.slug AS tipo
                        FROM 
                            wp_term_relationships AS relacion
                        LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
                        WHERE 
                            relacion.object_id = '{$servicio}' AND
                            relacion.term_taxonomy_id != 28
                    "
                );

                $sql = "
                    INSERT INTO cupos VALUES (
                        NULL,
                        '{$autor}',
                        '{$servicio}',
                        '{$tipo}',
                        '{$fecha}',
                        '0',
                        '{$acepta}',
                        '0',
                        '1'
                    );
                ";
                $db->query($sql);
            }
  
        }

    }else{

        $sql = "SELECT ID FROM wp_posts WHERE post_author = '{$user_id}' AND post_type='product'";

        $servicios = $db->get_results($sql);

        foreach ($servicios as $servicio) {

            $rangos = $db->get_var(" SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio->ID}' AND meta_key = '_wc_booking_availability' ", "meta_value");
            $rangos = unserialize($rangos);

            $temp = array(
                "type" => "custom",
                "bookable" => "no",
                "priority" => "10",
                "from" => $inicio,
                "to" => $fin
            );

            $rangos[] = $temp;
            
            $rangos = serialize($rangos);
            $db->query(" UPDATE wp_postmeta SET meta_value = '{$rangos}' WHERE post_id = '{$servicio->ID}' AND meta_key = '_wc_booking_availability' ");

            $autor = $db->get_var("SELECT post_author FROM wp_posts WHERE ID = '{$servicio->ID}' ", "post_author");
            $acepta = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio->ID}' AND meta_key = '_wc_booking_qty' ", "meta_value");

            $inicio = strtotime($inicio);
            $fin = strtotime($fin);

            for ($i=$inicio; $i <= $fin; $i+=86400) {
                $fecha = date("Y-m-d", $i);

                $existe = $db->get_var("SELECT id FROM cupos WHERE servicio = '{$servicio->ID}' AND fecha = '{$fecha}'");

                if( $existe != false ){
                    $db->query("UPDATE cupos SET no_disponible = 1 WHERE servicio = '{$servicio->ID}' AND fecha = '{$fecha}'");
                }else{
                    $sql = "
                        INSERT INTO cupos VALUES (
                            NULL,
                            '{$autor}',
                            '{$servicio->ID}',
                            '{$fecha}',
                            '0',
                            '{$acepta}',
                            '0',
                            '1'
                        );
                    ";
                    $db->query($sql);
                }
      
            }

        }

    }
?>