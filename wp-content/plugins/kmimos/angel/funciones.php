<?php

    if(!function_exists('angel_log')){
        function angel_log($salida){
            global $wpdb;
            $wpdb->query("INSERT INTO log VALUES(NULL, \"".$salida."\")");
        }
    }

    if(!function_exists('get_data_booking')){
        function get_data_booking($id){
            global $wpdb;

            $metas      = get_post_meta($id);
            $servicio   = $metas['_booking_product_id'][0];
            $mascotas   = unserialize($metas['_booking_persons'][0]);

            $cantidad_mascotas = 0;
            if( $mascotas != "" && count($mascotas) > 0 ){
                foreach ($mascotas as $key => $value) {
                    $cantidad_mascotas += $value;
                }
            }

            return array(
                "inicio"   => strtotime($metas['_booking_start'][0]),
                "fin"      => strtotime($metas['_booking_end'][0]),
                "servicio" => $servicio,
                "mascotas" => $cantidad_mascotas,
                "acepta"   => get_post_meta($servicio, "_wc_booking_qty", true),
                "autor"    => $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = {$servicio}")
            );
        }
    }
    
    if(!function_exists('update_cupos')){
        function update_cupos($id, $accion){
            global $wpdb;
            $data = get_data_booking($id);
            extract($data);

            for ($i=$inicio; $i < $fin; $i+=86400) { 
                $fecha = date("Y-m-d", $i);
                $full = 0;

                $existe = $wpdb->get_var("SELECT * FROM cupos WHERE servicio = '{$servicio}' AND fecha = '{$fecha}'");

                if( isset($existe) ){
                    $wpdb->query("UPDATE cupos SET cupos = cupos {$accion} {$mascotas} WHERE servicio = '{$servicio}' AND fecha = '{$fecha}' ");
                    $wpdb->query("UPDATE cupos SET full = 1 WHERE servicio = '{$servicio}' AND ( fecha = '{$fecha}' AND cupos >= acepta )");
                    $wpdb->query("UPDATE cupos SET full = 0 WHERE servicio = '{$servicio}' AND ( fecha = '{$fecha}' AND cupos < acepta )");
                }else{

                    $tipo = $wpdb->get_var(
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

                    if( $mascotas >= $acepta ){ $full = 1; }
                    $sql = "
                        INSERT INTO cupos VALUES (
                            NULL,
                            '{$autor}',
                            '{$servicio}',
                            '{$tipo}',
                            '{$fecha}',
                            '{$mascotas}',
                            '{$acepta}',
                            '{$full}',        
                            '0'        
                        );
                    ";
                    $wpdb->query($sql);

                }

            }

        }
    }

	if(!function_exists('vlz_get_paginacion')){
        function vlz_get_paginacion($t, $pagina){
            $paginacion = ""; $h = 15; $inicio = $pagina*$h; 
            $fin = $inicio+$h; if( $fin > $t){ $fin = $t; }
            if($t > $h){
                $ps = ceil($t/$h);
                for( $i=0; $i<$ps; $i++){
                    $active = ( $pagina == $i ) ? "class='vlz_activa'" : "";
                    $paginacion .= "<a href='".get_home_url()."/busqueda/".($i)."' ".$active.">".($i+1)."</a>";
                }
            }
            $w = 40*$ps;
            $paginacion .= "<style> .vlz_nav_cont_interno{ width: {$w}px; } </style>";
            return array(
                "inicio" => $inicio,
                "fin" => $fin,
                "html" => $paginacion,
            );
        }
    }
    if(!function_exists('get_destacados')){
        function get_destacados($estado){
            global $wpdb;
            $estado_des = $wpdb->get_var("SELECT name FROM states WHERE id = ".$estado);
            $sql_top = "SELECT * FROM destacados WHERE estado = '{$estado}'";
            $tops = $wpdb->get_results($sql_top);
            $top_destacados = ""; $cont = 0;
            foreach ($tops as $value) {
                $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$value->cuidador}");
                $data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
                $nombre = $data->nom;
                $img_url = kmimos_get_foto_cuidador($value->cuidador);
                $url = get_home_url() . "/petsitters/" . $data->url;
                $top_destacados .= "
                    <a class='vlz_destacados_contenedor' href='{$url}'>
                        <div class='vlz_destacados_contenedor_interno'>
                            <div class='vlz_destacados_img'>
                                <div class='vlz_descado_img_fondo' style='background-image: url({$img_url});'></div>
                                <div class='vlz_descado_img_normal' style='background-image: url({$img_url});'></div>
                                <div class='vlz_destacados_precio'><sub style='bottom: 0px;'>Hospedaje desde</sub><br>S./ $".($cuidador->hospedaje_desde*1.2)."</div>
                            </div>
                            <div class='vlz_destacados_data' >
                                <div class='vlz_destacados_nombre'>{$nombre}</div>
                                <div class='vlz_destacados_adicionales'>".vlz_servicios($cuidador->adicionales)."</div>
                            </div>
                        </div>
                    </a>
                ";
                $cont++;
            }
            if( $cont > 0 ){
                if( $top_destacados != '' ){
                    $top_destacados = $top_destacados."</div>"; 
                }
                $top_destacados = utf8_decode( '<div class="pfwidgettitle"> <div class="widgetheader">Destacados Kmimos en: '.$estado_des.' '.$municipio_des.'</div> </div> <div class="row" style="margin: 10px auto 20px;">').$top_destacados;
            }
            return comprimir_styles($top_destacados);
        }
    }
    if(!function_exists('vlz_get_page')){
        function vlz_get_page(){
            $valores = explode("/", $_SERVER['REDIRECT_URL']);
            return $valores[ count($valores)-2 ]+0;
        }
    }
    if(!function_exists('comprimir_styles')){
        function comprimir_styles($styles){
            $styles = str_replace("\t", "", $styles);
            $styles = str_replace("      ", " ", $styles);
            $styles = str_replace("     ", " ", $styles);
            $styles = str_replace("    ", " ", $styles);
            $styles = str_replace("   ", " ", $styles);
            $styles = str_replace("  ", " ", $styles);
            return $styles = str_replace("\n", " ", $styles);
        }
    }
    
    if(!function_exists('get_favoritos')){
        function get_favoritos(){
            $favoritos = array();
            global $wpdb;
            $id_user = get_current_user_id()+0;
            if( $id_user > 0 ){
                $rf = $wpdb->get_row("SELECT * FROM wp_usermeta WHERE ( user_id = $id_user AND meta_key = 'user_favorites'");
                preg_match_all('#"(.*?)"#i', $rf->favoritos, $favoritos);
                if( isset($favoritos[1]) ){
                    $favoritos = $favoritos[1];
                }
            }
            return $favoritos;
        }
    }
    
    if(!function_exists('get_menu_header')){
        function get_menu_header(){

            if( is_user_logged_in() ){

                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                $user = new WP_User( $user_id );
                $salir = wp_logout_url( home_url() );

                $MENU["head"] = '
                    <li class="pf-my-account pfloggedin"></li>
                    <li class="pf-my-account pfloggedin" style="min-width: 200px; text-align: right;">
                        <a href="#"> <i class="pfadmicon-glyph-632"></i> '.$user->data->display_name.' </a>
                        <ul class="pfnavsub-menu sub-menu menu-odd  menu-depth-1 hidden-xs hidden-sm">';

                $MENU["body"] = "";

                $MENUS = array(
                    "vendor" => array(
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=profile",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=mypets",
                            "name"  => "Mis Mascotas",
                            "icono" => "871"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=favorites",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=invoices",
                            "name"  => "Historial",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=myshop",
                            "name"  => "Descripción del cuidador",
                            "icono" => "664"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=myservices",
                            "name"  => "Mis Servicios",
                            "icono" => "453"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=disponibilidad",
                            "name"  => "Disponibilidad",
                            "icono" => "28"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=mypictures",
                            "name"  => "Mis Fotos",
                            "icono" => "82"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=mybookings",
                            "name"  => "Mis Reservas",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=caregiver",
                            "name"  => "Mis Solicitudes",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => $salir,
                            "name"  => "Cerrar Sesión",
                            "icono" => "476"
                        )
                    ),
                    "subscriber" => array(
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=profile",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=mypets",
                            "name"  => "Mis Mascotas",
                            "icono" => "871"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=favorites",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=invoices",
                            "name"  => "Historial",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => $salir,
                            "name"  => "Cerrar Sesión",
                            "icono" => "476"
                        )
                    ),
                    "administrator" => array(
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=profile",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=mypets",
                            "name"  => "Mis Mascotas",
                            "icono" => "871"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=favorites",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/?ua=invoices",
                            "name"  => "Historial",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/wp-admin/",
                            "name"  => "Panel de Control",
                            "icono" => "421"
                        ),
                        array(
                            "url"   => $salir,
                            "name"  => "Cerrar Sesión",
                            "icono" => "476"
                        )
                    )
                );

                foreach ($MENUS[ $user->roles[0] ] as $key => $value) {
                    $MENU["body"] .= '
                        <li>
                            <a href="'.$value["url"].'">
                                <i class="pfadmicon-glyph-'.$value["icono"].'"></i> 
                                '.$value["name"].'
                            </a>
                        </li>';
                }

                $MENU["body_movil"] = $MENU["body"];

                $MENU["footer"] = '</ul></li>';

            }else{
                $MENU["body"] = '
                    <li class="pf-login-register" id="pf-login-trigger-button"><a href="#"><i class="pfadmicon-glyph-584"></i> Iniciar Sesión</a></li>
                    <li class="pf-login-register"><a href="'.get_home_url().'/registrar/"><i class="pfadmicon-glyph-365"></i> Registrarse</a></li>
                    <li class="pf-login-register" id="pf-lp-trigger-button"><a href="#"><i class="pfadmicon-glyph-889"></i> Contraseña Olvidada</a></li>
                ';
                $MENU["body_movil"] = '
                    <li class="pf-login-register" id="pf-login-trigger-button-mobi"><a href="#"><i class="pfadmicon-glyph-584"></i> Iniciar Sesión</a></li>
                    <li class="pf-login-register"><a href="'.get_home_url().'/registrar/"><i class="pfadmicon-glyph-365"></i> Registrarse</a></li>
                    <li class="pf-login-register" id="pf-lp-trigger-button-mobi"><a href="#"><i class="pfadmicon-glyph-889"></i> Contraseña Olvidada</a></li>
                ';
            }

            return $MENU;
        }
    }
?>