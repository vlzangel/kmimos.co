<?php

	if(!function_exists('vlz_get_paginacion')){
        function vlz_get_paginacion($t, $pagina){
            $home = get_home_url();
            $paginacion = ""; $h = 12; $inicio = $pagina*$h; 
            $fin = $inicio+$h; if( $fin > $t){ $fin = $t; }
            if($t > $h){

                $ps = ceil($t/$h);

                if( $ps < 5 ){
                    for( $i=0; $i<=2; $i++){
                        $active = ( $pagina == $i ) ? " class='active'" : "";
                        $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($i)."'> ".($i+1)." </a> </li>";
                    }
                }else{
                    if( $pagina < ($ps-3)){
                        if( $pagina > 0){
                            $in = $pagina-1;
                            $fi = $pagina+1;
                        }else{
                            $in = $pagina;
                            $fi = $pagina+2;
                        }

                        if( $pagina > 1){
                            $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/0'> 1 </a> </li>";
                        }

                        for( $i=$in; $i<=$fi; $i++){
                            $active = ( $pagina == $i ) ? " class='active'" : "";
                            $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($i)."'> ".($i+1)." </a> </li>";
                        }
                        $paginacion .= "<li> <a href='#'> ... </a> </li>";
                        $active = ( $pagina == ($ps-1) ) ? " class='active'" : "";
                        $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($ps-1)."'> ".($ps)." </a> </li>";

                        if( $pagina > 0 ){
                            $atras = '
                                <li>
                                    <a href="'.$home.'/busqueda/'.($in).'">
                                        <img src="'.getTema().'/images/new/arrow-left-nav.png" width="8">
                                    </a>
                                </li>
                            ';
                        }else{
                            $fi -= 1;
                        }

                        $paginacion = '
                            '.$atras.'
                            '.$paginacion.'
                            <li>
                                <a href="'.$home.'/busqueda/'.($fi).'">
                                    <img src="'.getTema().'/images/new/arrow-right-nav.png" width="8">
                                </a>
                            </li>
                        ';
                    }else{

                        $in = $pagina-2;
                        $fi = ($ps-1);
                        $active = ( $pagina == ($in) ) ? " class='active'" : "";
                        $paginacion = "
                            <li> <a href='".$home."/busqueda/".($in)."'> <img src='".getTema()."/images/new/arrow-left-nav.png' width='8'> </a> </li>
                            <li ".$active."> <a href='".$home."/busqueda/0'> 1 </a> </li>
                            <li> <a href='#'> ... </a> </li>
                        ";
                        for( $i=$in; $i<=$fi; $i++){
                            $active = ( $pagina == $i ) ? " class='active'" : "";
                            $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($i)."'> ".($i+1)." </a> </li>";
                        }
                        $paginacion .= '<li> <a href="'.$home.'/busqueda/'.($fi).'"> <img src="'.getTema().'/images/new/arrow-right-nav.png" width="8"> </a> </li>';

                    }
                }

            }
            return array(
                "inicio" => $inicio,
                "fin" => $fin,
                "html" => $paginacion,
            );
        }
    }

    if(!function_exists('update_cupos')){
        function update_cupos($data, $accion){
            global $wpdb;
            $db = $wpdb;

            if( is_array($data) ){
                extract($data);
            }else{
                $id_orden = $data;

                $reserva = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_parent = '".$id_orden."'");
                $id_reserva = $reserva->ID;

                $metas_orden = get_post_meta($id_orden);
                $metas_reserva = get_post_meta( $id_reserva );

                $servicio = $metas_reserva['_booking_product_id'][0];

                $inicio = strtotime($metas_reserva['_booking_start'][0]);
                $fin    = strtotime($metas_reserva['_booking_end'][0]);

                $producto = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '".$metas_reserva['_booking_product_id'][0]."'");
                $tipo = explode("-", $producto->post_title);
                $tipo = $tipo[0];

                $cantidad = 0; $variaciones = unserialize( $metas_reserva["_booking_persons"][0] );
                foreach ($variaciones as $key => $variacion) {
                    $cantidad += $variacion;
                }
            }

            for ($i=$inicio; $i < ($fin-86399); $i+=86400) { 
                $fecha = date("Y-m-d", $i);
                $full = 0;
                $existe = $db->get_var("SELECT * FROM cupos WHERE servicio = '{$servicio}' AND fecha = '{$fecha}'");
                if( $existe !== null ){
                    $db->query("UPDATE cupos SET cupos = cupos {$accion} {$cantidad} WHERE servicio = '{$servicio}' AND fecha = '{$fecha}' ");
                    $db->query("UPDATE cupos SET full = 1 WHERE servicio = '{$servicio}' AND ( fecha = '{$fecha}' AND cupos >= acepta )");
                    $db->query("UPDATE cupos SET full = 0 WHERE servicio = '{$servicio}' AND ( fecha = '{$fecha}' AND cupos < acepta )");
                }else{
                    $acepta = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_qty'");
                    if( $cantidad >= $acepta ){ $full = 1; }
                    $sql = "
                        INSERT INTO cupos VALUES (
                            NULL,
                            '{$autor}',
                            '{$servicio}',
                            '{$tipo}',
                            '{$fecha}',
                            '{$cantidad}',
                            '{$acepta}',
                            '{$full}',        
                            '0'        
                        );
                    ";

                    $db->query($sql);
                }
            }
        }
    }

    if(!function_exists('get_destacados')){
        function get_destacados(){
            if( !isset($_SESSION) ){ session_start(); }
            $_POST = unserialize( $_SESSION['busqueda'] );
            $ubicacion = explode("_", $_POST["ubicacion"]);
            if( count($ubicacion) > 0 ){ $estado = $ubicacion[0]; }
            global $wpdb;
            $estado_des = $wpdb->get_var("SELECT name FROM states WHERE id = ".$estado);
            $sql_top = "SELECT * FROM destacados WHERE estado = '{$estado}'";
            $tops = $wpdb->get_results($sql_top);
            $top_destacados = ""; $cont = 0;
            foreach ($tops as $value) {
                $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$value->cuidador}");
                $data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
                $nombre = $data->nom;
                $img_url = kmimos_get_foto($cuidador->user_id);
                $url = get_home_url() . "/petsitters/" . $data->url;
                $anios_exp = $cuidador->experiencia;
                if( $anios_exp > 1900 ){
                    $anios_exp = date("Y")-$anios_exp;
                }
                $top_destacados .= '
                    <div class="slide">
                        <div class="item-slide" style="background-image: url('.getTema().'/images/new/km-buscador/slide-01.jpg);">
                            <div class="slide-mask"></div>
                            <div class="slide-content">
                                <div class="slide-price-distance">
                                    <div class="slide-price">
                                        Desde <span>MXN $'.($cuidador->hospedaje_desde*1.2).'</span>
                                    </div>
                                    <!--
                                    <div class="slide-distance">
                                        A 96 km de tu búsqueda
                                    </div>
                                    -->
                                </div>

                                <div class="slide-profile">
                                    <div class="slide-profile-image" style="background-image: url('.$img_url.');"></div>
                                </div>

                                <div class="slide-name">
                                    '.$nombre.'
                                </div>

                                <div class="slide-expertice">
                                    '.$anios_exp.' año(s) de experiencia
                                </div>

                                <div class="slide-ranking">
                                    <div class="km-ranking">
                                        '.kmimos_petsitter_rating($cuidador->id_post).'
                                    </div>
                                </div>

                                <div class="slide-buttons">
                                    <a href="#" role="button" data-name="'.$nombre.'" data-id="'.$cuidador->id_post.'" data-target="#popup-conoce-cuidador" class="km-btn-primary-new stroke" style="height:auto;">CONÓCELO +</a>
                                    <a href="'.$url.'">RESERVAR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
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

    if(!function_exists('vlz_num_resultados')){
        function vlz_num_resultados(){
            if( !isset($_SESSION)){ session_start(); }
            if( $_SESSION['resultado_busqueda'] ){
                return count($_SESSION['resultado_busqueda'])+0;
            }else{
                return 0;
            }
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

            // return $styles;
        }
    }
    
    if(!function_exists('get_favoritos')){
        function get_favoritos(){
            $favoritos = array();
            global $wpdb;
            $id_user = get_current_user_id()+0;
            if( $id_user > 0 ){
                $rf = $wpdb->get_row("SELECT * FROM wp_usermeta WHERE user_id = $id_user AND meta_key = 'user_favorites' ");
                // preg_match_all('#"(.*?)"#i', $rf->favoritos, $favoritos);
                // if( isset($favoritos[1]) ){
                //     $favoritos = $favoritos[1];
                // }

                $rows = str_replace( '"",', '', $rf->meta_value );
                $rows = str_replace( '"', '', $rows );
                $rows = str_replace( '[', '', $rows );
                $rows = str_replace( ']', '', $rows );
                if( !empty($rows) ){
                    $favoritos = explode(',', $rows);
                }
            }
            return $favoritos;
        }
    }
    
    if(!function_exists('get_menu_header')){
        function get_menu_header( $menu_principal = false ){

            if( is_user_logged_in() ){

                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                $user = new WP_User( $user_id );
                $salir = wp_logout_url( home_url() );

                $MENUS = array(
                    "vendor" => array(
                        array(
                            "url"   => get_home_url()."/perfil-usuario/",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/mascotas",
                            "name"  => "Mis Mascotas",
                            "icono" => "871",
                            "ocultar_menu_principal"  => 'true'
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/favoritos",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375",
                            "ocultar_menu_principal"  => 'true'
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/historial",
                            "name"  => "Historial",
                            "icono" => "33",
                            "ocultar_menu_principal"  => 'true'
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/descripcion",
                            "name"  => "Descripción",
                            "icono" => "664",
                            "ocultar_menu_principal"  => 'true'
                        ),                        
                        array(
                            "url"   => get_home_url()."/perfil-usuario/servicios",
                            "name"  => "Mis Servicios",
                            "icono" => "453"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/disponibilidad",
                            "name"  => "Disponibilidad",
                            "icono_2" => "fa fa-calendar"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/galeria",
                            "name"  => "Mis Fotos",
                            "icono" => "82",
                            "ocultar_menu_principal"  => 'true'
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/reservas",
                            "name"  => "Mis Reservas",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/solicitudes",
                            "name"  => "Mis Solicitudes",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => $salir,
                            "name"  => "Cerrar Sesión",
                            "icono" => "476"
                        ),
                    ),
                    "subscriber" => array(
                        array(
                            "url"   => get_home_url()."/perfil-usuario/",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/mascotas",
                            "name"  => "Mis Mascotas",
                            "icono" => "871"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/favoritos",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/historial",
                            "name"  => "Historial",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/solicitudes",
                            "name"  => "Mis Solicitudes",
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
                            "url"   => get_home_url()."/perfil-usuario/",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/mascotas",
                            "name"  => "Mis Mascotas",
                            "icono" => "871"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/favoritos",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/historial",
                            "name"  => "Historial",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/solicitudes",
                            "name"  => "Mis Solicitudes",
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

                $MENU["head"] = '<li><a href="#" class="km-nav-link"> <i class="pfadmicon-glyph-632"></i> '.$user->data->display_name.' </a></li>';
                $MENU["head_movil"] = '<li><a href="#" class="km-nav-link"> <i class="pfadmicon-glyph-632"></i> '.$user->data->display_name.' </a></li>';
                $MENU["body"] = "";

                if( $MENUS[ $user->roles[0] ] != "" ){
                    foreach ($MENUS[ $user->roles[0] ] as $key => $value) {
                        $sts = "";
                        if( $menu_principal ){
                            if( array_key_exists('ocultar_menu_principal', $value) ){
                                $sts = "vlz_ocultar";
                            }
                        }
                        
                        if( isset($value["icono"]) ){ $icono = '<i class="pfadmicon-glyph-'.$value["icono"].'"></i> '; }
                        if( isset($value["icono_2"]) ){ $icono = '<i class="'.$value["icono_2"].'"></i> '; }
                        $MENU["body"] .=
                            '<li class="'.$sts.'">
                                <a href="'.$value["url"].'" class="pd-tb11 menu-link">
                                    '.$icono.'
                                    '.$value["name"].'
                                </a>
                            </li>';
                    }
                }

                $MENU["footer"] = '';

            }else{
                $MENU["body"] = 
                '<li id="separador"></li>'.
                '<li id="login"><a><i class="pfadmicon-glyph-584"></i> Iniciar Sesión</a></li>'.
                '<li id="registrar"><a><i class="pfadmicon-glyph-365"></i> Registrarse</a></li>'.
                '<li id="recuperar"><a><i class="pfadmicon-glyph-889"></i> Contraseña Olvidada</a></li>';
            }

            return $MENU;
        }
    }

    if(!function_exists('kmimos_petsitter_rating')){

        function kmimos_petsitter_rating($post_id, $is_data = false){
            $valoracion = kmimos_petsitter_rating_and_votes($post_id);
            $votes = $valoracion['votes'];
            $rating = $valoracion['rating'];
            if( $is_data ){
                $data = array();
                if($votes =='' || $votes == 0 || $rating ==''){ 
                    for ($i=0; $i<5; $i++){ 
                        $data[] = 0;
                    }
                } else { 
                    $n = ceil($rating);
                    for ($i=0; $i<$n; $i++){ 
                        $data[] = 1;
                    }
                    if($n <= 4){
                        for ($i=$n; $i<5; $i++){ 
                            $data[] = 0;
                        }
                    }
                }
                return $data;
            }else{
                $html = '<div class="rating">';
                if($votes =='' || $votes == 0 || $rating ==''){ 
                    for ($i=0; $i<5; $i++){ 
                        $html .= "<a href='#'></a>";
                    }
                } else { 
                    $n = ceil($rating);
                    for ($i=0; $i<$n; $i++){ 
                        $html .= "<a href='#' class='active'></a>";
                    }
                    if($n <= 4){
                        for ($i=$n; $i<5; $i++){ 
                            $html .= "<a href='#' class='no_active'></a>";
                        }
                    }
                }
                $html .= '</div>';
                return $html;
            }
            return "";
        }
    }

    function get_ficha_cuidador($cuidador, $i, $favoritos, $disenio, $reload='false', $listado_favorito = false){
        global $current_user, $wpdb;
        $img        = kmimos_get_foto($cuidador->user_id);
        $anios_exp  = $cuidador->experiencia; if( $anios_exp > 1900 ){ $anios_exp = date("Y")-$anios_exp; }
        $url        = get_home_url()."/petsitters/".$cuidador->slug;
        $user_id = $current_user->ID;

        $distancia = '';
        if( isset($cuidador->DISTANCIA) ){ $distancia   = 'A '.floor($cuidador->DISTANCIA).' km de tu busqueda'; }

        $anios_exp = $cuidador->experiencia;
        if( $anios_exp > 1900 ){
            $anios_exp = date("Y")-$anios_exp;
        }

        /* Atributos del cuidador */
        $atributos_cuidador = $wpdb->get_results( "SELECT atributos FROM cuidadores WHERE user_id=".$cuidador->user_id );

        /* BEGIN Cuidadores destacados */
        $style_icono = '';
        $marca_destacado = 'style="background-image: url('.getTema().'/images/new/bg-foto-resultados.png)!important;"';
        if( count($atributos_cuidador)>0 ){
            $atributos = unserialize($atributos_cuidador[0]->atributos);
            if( $atributos['destacado'] == 1 ){
                $marca_destacado = 'style="background-image: url('.getTema().'/images/new/bg-foto-resultados-destacado.png)!important;width: 69px!important;height: 69px!important;"';
                $style_icono = 'style="margin: 8px 0px 0px 37px"!important';
            }
        }
        /* FIN Cuidadores destacados */

        $fav_check = 'false';
        $fav_del = '';
        if (in_array($cuidador->id_post, $favoritos)) {
            $fav_check = 'true'; 
            $favtitle_text = esc_html__('Quitar de mis favoritos','kmimos');
            $fav_del = 'favoritos_delete';
        }
        $favoritos_link = 
        '<span href="javascript:;" 
            data-reload="'.$reload.'"
            data-user="'.$user_id.'" 
            data-num="'.$cuidador->id_post.'" 
            data-active="'.$fav_check.'"
            data-favorito="'.$fav_check.'"
            class="km-link-favorito '.$fav_del.'" '.$style_icono.'>
            <i class="fa fa-heart" aria-hidden="true"></i>
        </span>';

        // // validaciones para el link de conocer al cuidador
        // $attr_link_conocer_cuidador = get_attr_link_conocer_cuidador( utf8_encode($cuidador->titulo), $cuidador->id_post);

        $titulo = utf8_encode($cuidador->titulo);
        if( $listado_favorito ){
            $titulo = ($cuidador->titulo);
        }

        $valoraciones = "No tiene valoraciones";
        if( $cuidador->valoraciones+0 > 0 ){
            $plural = "&oacute;n";
            if( $cuidador->valoraciones+0 > 1 ){ $plural = "ones"; }
            $valoraciones = $cuidador->valoraciones." Valoraci".$plural;
        }

        switch ($disenio) {
            case 'list':
                $ficha = '
                    <div class="km-item-resultado active">
                        <a href="'.$url.'" class="km-foto">
                            <div class="km-img">
                                <div class="km-fondo-img" style="background-image: url('.$img.');"></div>
                                <div class="km-subimg" style="background-image: url('.$img.');"></div>
                            </div>
                            <span class="km-contenedor-favorito" '.$marca_destacado.'>'.$favoritos_link.'</span>
                        </a>

                        <div class="km-contenedor-descripcion-opciones">
                            <div class="km-descripcion">
                                <h1><a href="'.$url.'">'.$titulo.'</a></h1>

                                <p>'.$anios_exp.' año(s) años de experiencia</p>

                                <div class="km-ranking">
                                    '.kmimos_petsitter_rating($cuidador->id_post).'
                                </div>

                                <div class="km-valoraciones">
                                    '.$valoraciones.'
                                </div>

                                <div class="km-sellos">
                                    '.vlz_servicios($cuidador->adicionales).'
                                </div>
                            </div>

                            <div class="km-opciones">
                                <div class="precio">MXN $ '.$cuidador->precio.'</div>
                                <div class="distancia">'.$distancia.'</div>
                                <a role="button" href="#" 
                                    data-name="'.$titulo.'" 
                                    data-id="'.$cuidador->id_post.'" 
                                    data-target="#popup-conoce-cuidador"
                                    class="km-btn-primary-new stroke">CONÓCELO +</a>
                                <a href="'.get_home_url()."/petsitters/".$cuidador->slug.'" class="km-btn-primary-new basic">RESERVA</a>
                            </div>
                        </div>
                    </div>
                ';
            break;
            case 'grid':
                $ficha = '
                    <div class="km-item-resultado">
                        <a href="'.$url.'" class="km-foto">
                            <div class="km-img">
                                <div class="km-fondo-img" style="background-image: url('.$img.');"></div>
                                <div class="km-subimg" style="background-image: url('.$img.');"></div>
                            </div>
                            <span class="km-contenedor-favorito" '.$marca_destacado.'>'.$favoritos_link.'</span>
                        </a>
                        <div class="km-descripcion">
                            <h1><a href="'.$url.'">'.$titulo.'</a></h1>
                            <p>'.$anios_exp.' año(s) de experiencia
                                <br><b>MXN $ '.$cuidador->precio.'</b>
                                <br><small>'.$distancia.'</small>
                            </p>
                            <div class="km-ranking">
                                '.kmimos_petsitter_rating($cuidador->id_post).'
                            </div>

                            <div class="km-valoraciones">
                                '.$valoraciones.'
                            </div>

                            <div class="km-sellos">
                                '.vlz_servicios($cuidador->adicionales).'
                            </div>
                            <div class="km-buttons">
                                <a role="button" href="#" 
                                    data-name="'.$titulo.'" 
                                    data-id="'.$cuidador->id_post.'" 
                                    data-target="#popup-conoce-cuidador"
                                    class="km-btn-primary-new stroke">CONÓCELO +</a>
                                <a href="'.get_home_url()."/petsitters/".$cuidador->slug.'" class="active">RESERVAR</a>
                            </div>
                        </div>
                    </div>
                ';
            break;
        }
    
        return $ficha;
    }

?>