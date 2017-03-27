<?php
    
    function cron_solicitudes_vencidas(){
        return "
            SELECT 
                SQL_CALC_FOUND_ROWS  
                p.ID,
                p.post_author,
                p.post_title,
                p.post_date,
                mt3.meta_value AS cliente_id,
                CONCAT (mt5.meta_value, ' ', mt6.meta_value ) AS cliente,
                mt2.meta_value AS cuidador_id,
                mt4.post_title AS cuidador,
                mt7.meta_value AS tipo,

                mt10.user_email AS email
            FROM 

                wp_posts AS p 

            INNER JOIN wp_postmeta AS mt0 ON ( p.ID = mt0.post_id ) 
            INNER JOIN wp_postmeta AS mt1 ON ( p.ID = mt1.post_id AND mt1.meta_key = 'request_status' ) 
            INNER JOIN wp_postmeta AS mt2 ON ( p.ID = mt2.post_id AND mt2.meta_key = 'requested_petsitter' ) 
            INNER JOIN wp_postmeta AS mt3 ON ( p.ID = mt3.post_id AND mt3.meta_key = 'requester_user' ) 
            INNER JOIN wp_posts    AS mt4 ON ( mt4.ID = mt2.meta_value ) 
            INNER JOIN wp_usermeta AS mt5 ON ( mt5.user_id = mt3.meta_value AND mt5.meta_key = 'first_name' ) 
            INNER JOIN wp_usermeta AS mt6 ON ( mt6.user_id = mt3.meta_value AND mt6.meta_key = 'last_name' ) 
            INNER JOIN wp_postmeta AS mt7 ON ( p.ID = mt7.post_id AND mt7.meta_key = 'request_type' ) 

            INNER JOIN wp_users AS mt10 ON ( mt3.meta_value = mt10.ID) 

            WHERE 1=1  
                AND ( 
                    mt0.meta_key = 'next_time'
                    AND 
                    mt0.meta_value < NOW()
                ) 
                AND p.post_type = 'request'
                AND mt1.meta_value = 1
            GROUP BY 
                p.ID
            ORDER BY
                p.post_date DESC
        ";
    }

    function cron_servicios($cuidador){
        return "
            SELECT 
                latitud, longitud, adicionales
            FROM 
                cuidadores
            WHERE
                id_post = {$cuidador}
        ";
    }

    function cron_armar_sql_cercanos($servicios, $param){

        $xservicios  = unserialize( $servicios->adicionales );
        
        $sql_servicios = "";
        foreach ($xservicios as $key => $valor) {
            $sql_servicios .= "AND adicionales LIKE '%".$key."%' ";
        }

        $sql = "
            SELECT 
                DISTINCT id,
                ROUND ( ( 6371 * 
                    acos(
                        cos(
                            radians({$param['lati']})
                        ) * 
                        cos(
                            radians(latitud)
                        ) * 
                        cos(
                            radians(longitud) - 
                            radians({$param['long']})
                        ) + 
                        sin(
                            radians({$param['lati']})
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
                id_post != {$param['id']} {$sql_servicios}
            ORDER BY DISTANCIA ASC
            LIMIT 0, 3
        ";
        
        return $sql;
    }

    function cron_armar_sql_cercanos_sino($param){
        $menos = "";
        if( count($param['cuid']) > 0 ){
            foreach ( $param['cuid'] as $key => $value) {
               $menos .= "AND id != {$value} ";
            }
        }
        $sql = "
            SELECT 
                DISTINCT id,
                ROUND ( ( 6371 * 
                    acos(
                        cos(
                            radians({$param['lati']})
                        ) * 
                        cos(
                            radians(latitud)
                        ) * 
                        cos(
                            radians(longitud) - 
                            radians({$param['long']})
                        ) + 
                        sin(
                            radians({$param['lati']})
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
                id_post != {$param['id']} {$menos}
            ORDER BY DISTANCIA ASC
            LIMIT 0, {$param['cant']}
        ";
        
        return $sql;
    }

?>