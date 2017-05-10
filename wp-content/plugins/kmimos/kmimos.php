<?php
/**
 * @package    WordPress
 * @subpackage KMIMOS
 * @author     Ing. Eduardo Allan D. <eallan@ingeredes.net>
 *
 *
 * Plugin Name: Kmimos - We consent your pets.
 * Plugin URI:  https://ingeredes.net/plugins/kmimos/
 * Description: <a href="https://ingeredes.net/plugins/kmimos/">Kmimos</a> is a full-featured system for Kmimos written in PHP by <a href="https://ingeredes.net" title="Business Engineering in the Net">Ingeredes, Inc.</a>. This plugin include this tool in WordPress for a fast management of all operations of the enterprise.
 * Author:      Eng. Eduardo Allan D. <eallan@ingeredes.net>
 * Author URI:  https://ingeredes.net/
 * Text Domain: kmimos  
 * Version:     1.0.0
 * License:     GPL2
 */
include_once('includes/class/class_kmimos_map.php');
include_once('includes/functions/kmimos_functions.php');
include_once('plugins/woocommerce.php');

if(!function_exists('get_estados_municipios')){
    return get_estados_municipios();
}

if(!function_exists('kmimos_mails_administradores')){
    function kmimos_mails_administradores(){

        $headers[] = 'BCC: n.deligny@kmimos.la';
        $headers[] = 'BCC: a.pedroza@kmimos.la';

        /*        
        $headers[] = 'BCC: vlzangel91@gmail.com';
        $headers[] = 'BCC: angelveloz91@gmail.com';
        */

        return $headers;
    }
}

if(!function_exists('vlz_servicios')){
    function vlz_servicios($adicionales){
        $r = ""; $adiestramiento = false;

        $r .= '<span class="tooltip icono-servicios"><span class="tooltiptext">Hospedaje</span><i class="icon-hospedaje"></i></span>';

        $adicionales = unserialize($adicionales);
        
        if( $adicionales != "" ){
            if( count($adicionales) > 0 ){
                foreach($adicionales as $key => $value){
                    switch ($key) {
                        case 'guarderia':
                            $r .= '<span class="tooltip icono-servicios"><span class="tooltiptext">Guardería</span><i class="icon-guarderia"></i></span>';
                        break;
                        case 'adiestramiento_basico':
                            $adiestramiento = true;
                        break;
                        case 'adiestramiento_intermedio':
                            $adiestramiento = true;
                        break;
                        case 'adiestramiento_avanzado':
                            $adiestramiento = true;
                        break;
                        case 'corte':
                            $r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Corte de pelo y uñas</span><i class="icon-peluqueria"></i></div>';
                        break;
                        case 'bano':
                            $r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Baño y secado</span><i class="icon-bano"></i></div>';
                        break;
                        case 'transportacion_sencilla':
                            $r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Transporte Sencillo</span><i class="icon-transporte"></i></div>';
                        break;
                        case 'transportacion_redonda':
                            $r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Transporte Redondo</span><i class="icon-transporte2"></i></div>';
                        break;
                        case 'visita_al_veterinario':
                            $r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Visita al Veterinario</span><i class="icon-veterinario"></i></div>';
                        break;
                        case 'limpieza_dental':
                            $r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Limpieza dental</span><i class="icon-limpieza"></i></div>';
                        break;
                        case 'acupuntura':
                            $r .= '<div class="tooltip icono-servicios"><span class="tooltiptext">Acupuntura</span><i class="icon-acupuntura"></i></div>';
                        break;
                    }
                }
            }
        }
        if($adiestramiento){
            $r .= '<div class="tooltip icono-servicios" ><span class="tooltiptext">Adiestramiento de Obediencia</span><i class="icon-adiestramiento"></i></div>';
        }
        return $r;
    }
}

if(!function_exists('vlz_sql_busqueda')){
    function vlz_sql_busqueda($param, $pagina, $actual = false){

        $condiciones = "";
        if( isset($param["servicios"]) ){
            foreach ($param["servicios"] as $key => $value) {
                if( $value != "hospedaje" ){
                    $condiciones .= " AND adicionales LIKE '%".$value."%'";
                }
            }
        }

        if( isset($param['tamanos']) ){
            foreach ($param['tamanos'] as $key => $value) {
                $condiciones .= " AND ( tamanos_aceptados LIKE '%\"".$value."\";i:1%' || tamanos_aceptados LIKE '%\"".$value."\";s:1:\"1\"%' ) ";
            }
        }

        if( isset($param['n']) ){
            if( $param['n'] != "" ){
                $name_inner = "INNER JOIN wp_posts AS cuidador ON ( cuidadores.id_post = cuidador.ID )";
                $condiciones .= " AND cuidador.post_title LIKE '%".$param['n']."%' ";
            }
        }

        if( $param['rangos'][0] != "" ){
            $condiciones .= " AND (hospedaje_desde*1.2) >= '".$param['rangos'][0]."' ";
        }

        if( $param['rangos'][1] != "" ){
            $condiciones .= " AND (hospedaje_desde*1.2) <= '".$param['rangos'][1]."' ";
        }

        if( $param['rangos'][2] != "" ){
            $anio_1 = date("Y")-$param['rangos'][2];
            $condiciones .= " AND experiencia <= '".$anio_1."' ";
        }

        if( $param['rangos'][3] != "" ){
            $anio_2 = date("Y")-$param['rangos'][3];
            $condiciones .= " AND experiencia >= '".$anio_2."' ";
        }

        if( $param['rangos'][4] != "" ){
            $condiciones .= " AND rating >= '".$param['rangos'][4]."' ";
        }

        if( $param['rangos'][5] != "" ){
            $condiciones .= " AND rating <= '".$param['rangos'][5]."' ";
        }

        // Ordenamiento

        $orderby = $param['orderby'];

        if( $orderby == "rating_desc" ){
            $orderby = "rating DESC, valoraciones DESC";
        }

        if( $orderby == "rating_asc" ){
            $orderby = "rating ASC, valoraciones ASC";
        }

        if( $orderby == "distance_asc" ){
            $orderby = "DISTANCIA ASC";
        }

        if( $orderby == "distance_desc" ){
            $orderby = "DISTANCIA DESC";
        }

        if( $orderby == "price_asc" ){
            $orderby = "hospedaje_desde ASC";
        }

        if( $orderby == "price_desc" ){
            $orderby = "hospedaje_desde DESC";
        }

        if( $orderby == "experience_asc" ){
            $orderby = "experiencia ASC";
        }

        if( $orderby == "experience_desc" ){
            $orderby = "experiencia DESC";
        }

        if( $param['tipo_busqueda'] == "otra-localidad" ){

            if( $param['estados'] != "" ){

                if($param['municipios'] != ""){
                    $municipio = "AND ubi.municipios LIKE '%=".$param['municipios']."=%'";
                }

                $DISTANCIA = ",
                    ( 6371 * 
                        acos(
                            cos(
                                radians({$param['otra_latitud']})
                            ) * 
                            cos(
                                radians(latitud)
                            ) * 
                            cos(
                                radians(longitud) - 
                                radians({$param['otra_longitud']})
                            ) + 
                            sin(
                                radians({$param['otra_latitud']})
                            ) * 
                            sin(
                                radians(latitud)
                            )
                        )
                    ) as DISTANCIA 
                ";

                $ubicaciones_inner = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
                $ubicaciones_filtro = "
                    AND (
                        (
                            ubi.estado LIKE '%=".$param['estados']."=%'
                            ".$municipio."
                        ) OR (
                            ( 6371 * 
                                acos(
                                    cos(
                                        radians({$param['otra_latitud']})
                                    ) * 
                                    cos(
                                        radians(latitud)
                                    ) * 
                                    cos(
                                        radians(longitud) - 
                                        radians({$param['otra_longitud']})
                                    ) + 
                                    sin(
                                        radians({$param['otra_latitud']})
                                    ) * 
                                    sin(
                                        radians(latitud)
                                    )
                                )
                            ) <= 100
                        )
                    )";

                if( $orderby == "" ){
                    $orderby = "DISTANCIA ASC";
                }

            }else{
                $ubicaciones_inner = "";
                if( $orderby == "" ){
                    $orderby = "rating DESC, valoraciones DESC";
                }
            }

        }else{

            if( $param['latitud'] != "" && $param['longitud'] != "" ){

                $DISTANCIA = ",
                    ( 6371 * 
                        acos(
                            cos(
                                radians({$param['latitud']})
                            ) * 
                            cos(
                                radians(latitud)
                            ) * 
                            cos(
                                radians(longitud) - 
                                radians({$param['longitud']})
                            ) + 
                            sin(
                                radians({$param['latitud']})
                            ) * 
                            sin(
                                radians(latitud)
                            )
                        )
                    ) as DISTANCIA 
                ";

                $FILTRO_UBICACION = "HAVING DISTANCIA < ".($param['distancia']+0);

                if( $orderby == "" ){
                    $orderby = "DISTANCIA ASC";
                }

            }else{
                $DISTANCIA = "";
                $FILTRO_UBICACION = "";
            }

        }

        if( $orderby == "" ){
            $orderby = "rating DESC, valoraciones DESC";
        }

        
        $sql = "
            SELECT 
                SQL_CALC_FOUND_ROWS  
                cuidadores.*
                {$DISTANCIA}
            FROM 
                cuidadores
                {$ubicaciones_inner}
                {$name_inner}
            WHERE 
                activo = '1' {$condiciones}
                {$ubicaciones_filtro}
            {$FILTRO_UBICACION}
            ORDER BY {$orderby}
            LIMIT {$pagina}, 15
        ";

        return $sql;
    }
}

if(!function_exists('servicios_adicionales')){
    function servicios_adicionales(){

        $extras = array(
            'corte' => array( 
                'label'=>'Corte de Pelo y Uñas',
                'icon' => 'peluqueria'
            ),
            'bano' => array( 
                'label'=>'Baño y Secado',
                'icon' => 'bano'
            ),
            'transportacion_sencilla' => array( 
                'label'=>'Transporte Sencillo',
                'icon' => 'transporte'
            ),
            'transportacion_redonda' => array( 
                'label'=>'Transporte Redondo',
                'icon' => 'transporte2'
            ),
            'visita_al_veterinario' => array( 
                'label'=>'Visita al Veterinario',
                'icon' => 'veterinario'
            ),
            'limpieza_dental' => array( 
                'label'=>'Limpieza Dental',
                'icon' => 'limpieza'
            ),
            'acupuntura' => array( 
                'label'=>'Acupuntura',
                'icon' => 'acupuntura'
            )
        );

        return $extras;
    }
}

if(!function_exists('kmimos_get_foto')){
    function kmimos_get_foto($user_id){
        global $wpdb;
        $name_photo = get_user_meta($user_id, "name_photo", true);
        if( empty($name_photo)  ){ $name_photo = "0"; }
        $path_avatar = "avatares";
        if( file_exists("wp-content/uploads/avatares/".$user_id."/{$name_photo}") ){
            $img = get_home_url()."/wp-content/uploads/avatares/".$user_id."/{$name_photo}";
        }else{
            if( file_exists("wp-content/uploads/avatares/".$user_id."/{$name_photo}.jpg") ){
                $img = get_home_url()."/wp-content/uploads/avatares/".$user_id."/{$name_photo}.jpg";
            }else{
                if( file_exists("wp-content/uploads/avatares/".$user_id."/0.jpg") ){
                    $img = get_home_url()."/wp-content/uploads/avatares/".$user_id."/0.jpg";
                }else{
                    $img = get_home_url().'/wp-content/themes/pointfinder/images/noimg.png';
                }
            }
        }
        return $img;
    }
}

if(!function_exists('kmimos_get_foto_cuidador')){
    function kmimos_get_foto_cuidador($id){
        return kmimos_get_foto($id);
    }
}

if(!function_exists('kmimos_style')){
    function kmimos_style($styles = array()){
        
        $salida = "<style type='text/css'>";

            if( in_array("limpiar_tablas", $styles)){
                $salida .= "
                    table{
                        border: 0;
                        background-color: transparent !important;
                    }
                    table >thead >tr >th, table >tbody >tr >th, table >tfoot >tr >th, table >thead >tr >td, table >tbody >tr >td, table >tfoot >tr >td {
                        padding: 0px 10px 0px 0px;
                        line-height: 1.42857143;
                        vertical-align: top;
                        border-top: 0;
                        border-right: 0;
                        background: #FFF;
                    }
                ";
            }

            if( in_array("tablas", $styles)){
                $salida .= "
                    .vlz_titulos_superior{
                        font-size: 14px;
                        font-weight: 600;
                        padding: 5px 0px;
                        margin-bottom: 10px;
                        max-width: 350px;
                    }
                    .vlz_titulos_tablas{
                        background: #00d2b7;
                        font-size: 13px;
                        font-weight: 600;
                        padding: 5px;
                        color: #FFF;
                    }
                    .vlz_contenido_tablas{
                        padding: 5px;
                        border: solid 1px #CCC;
                        border-top: 0;
                        margin-bottom: 10px;
                    }
                    .vlz_tabla{
                        width: 100%;
                        margin-bottom: 40px;
                    }
                    .vlz_tabla strong{
                        font-weight: 600;
                    }
                    .vlz_tabla > th{
                        background: #59c9a8!important;
                        color: #FFF;
                        border-top: 1px solid #888;
                        border-right: 1px solid #888;
                        text-align: center;
                        vertical-align: top;
                    }
                    .vlz_tabla > tr > td{
                        border-top: 1px solid #888;
                        border-right: 1px solid #888;
                        vertical-align: top;
                    }
                ";
            }

            if( in_array("celdas", $styles)){
                $salida .= "
                    .cell25  {vertical-align: top; width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                    .cell33  {vertical-align: top; width: 33.333333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                    .cell50  {vertical-align: top; width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                    .cell66  {vertical-align: top; width: 66.666666666%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                    .cell75  {vertical-align: top; width: 75%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                    .cell100 {vertical-align: top; width: 100%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                    @media screen and (max-width: 700px){
                        .cell25 { width: 50%; }
                    }
                    @media screen and (max-width: 500px){
                        .cell25, .cell33, .cell50, .cell66, .cell75{ width: 100%; }
                    }
                ";
            }

            if( in_array("formularios", $styles)){
                $salida .= "
                    .kmimos_boton{
                        border: solid 1px #59c9a8;
                        background: #59c9a8;
                        padding: 10px 20px;
                        display: inline-block;
                        margin: 20px 0px 0px;
                        color: #FFF;
                        font-weight: 600;
                    }
                ";
            }

            if( in_array("quitar_edicion", $styles)){
                $salida .= "
                    .menu-top,
                    .wp-menu-separator,
                    #dashboard-widgets-wrap{
                        display: none;
                    }

                    #wp-admin-bar-wp-logo,
                    #wp-admin-bar-updates,
                    #wp-admin-bar-comments,
                    #wp-admin-bar-new-content,
                    #wp-admin-bar-wpseo-menu,
                    #wp-admin-bar-ngg-menu,
                    .updated,
                    #wpseo_meta,
                    #mymetabox_revslider_0,
                    .vlz_contenedor_botones,
                    .wpseo-score,
                    .wpseo-score-readability,
                    .ratings,
                    #wpseo-score,
                    #wpseo-score-readability,
                    #ratings,
                    .column-wpseo-score,
                    .column-wpseo-score-readability,
                    .column-ratings,
                    #toplevel_page_kmimos li:last-child,
                    #menu-posts-wc_booking li:nth-child(3),
                    #menu-posts-wc_booking li:nth-child(6),
                    #menu-posts-wc_booking li:nth-child(7),
                    #screen-meta-links,
                    #wp-admin-bar-site-name-default,
                    #postcustom,
                    #woocommerce-order-downloads,
                    #wpfooter,
                    #postbox-container-1,
                    .page-title-action,
                    .row-actions,
                    .bulkactions,
                    #commentstatusdiv,
                    #edit-slug-box,
                    #postdivrich,
                    #authordiv,
                    #wpseo-filter,
                    .booking_actions button,

                    #actions optgroup option,
                    #actions option[value='regenerate_download_permissions']

                    {
                        display: none;
                    }

                    #poststuff #post-body.columns-2{
                        margin-right: 0px !important;
                    }

                    #normal-sortables{
                        min-height: 0px !important;
                    }

                    .booking_actions view,
                    #actions optgroup > option[value='send_email_new_order']
                    {
                        display: block;
                    }

                    .wc-order-status a,
                    .wc-customer-user a,
                    .wc-order-bulk-actions,
                    .wc-order-totals tr:nth-child(2),
                    .wc-order-totals tr:nth-child(5)
                    {
                        display: none;
                    }
                ";
            }

            if( in_array("habilitar_edicion_reservas", $styles)){
                $salida .= "

                    #poststuff #post-body.columns-2{
                        margin-right: 300px !important;
                    }

                    #postbox-container-1{
                        display: block;
                    }
                ";
            }

            if( in_array("menu_kmimos", $styles)){
                $salida .= "
                    #toplevel_page_kmimos{
                        display: block;
                    }
                ";
            }

            if( in_array("menu_reservas", $styles)){
                $salida .= "
                    #menu-posts-wc_booking{
                        display: block;
                    }
                ";
            }

            if( in_array("form_errores", $styles)){
                $salida .= "
                    .no_error{
                        display: none;
                    }

                    .error{
                        display: block;
                        font-size: 10px;
                        border: solid 1px #CCC;
                        padding: 3px;
                        border-radius: 0px 0px 3px 3px;
                        background: #ffdcdc;
                        line-height: 1.2;
                        font-weight: 600;
                    }

                    .vlz_input_error{
                        border-radius: 3px 3px 0px 0px !important;
                        border-bottom: 0px !important;
                    }
                ";
            }

        $salida .= "</style>";

        return $salida;
        
    }
}

add_action('admin_init','kmimos_load_language'); 
add_action('init','ingeredes_kmimos');
add_action('admin_menu','kmimos_admin_menu');
add_action('admin_init','kmimos_admin_init');
add_action('admin_enqueue_scripts','kmimos_include_admin_scripts');
add_action('wp_enqueue_scripts','kmimos_include_scripts');

require_once('assets/class/class.filters.php');
require_once('assets/class/class.featured.php');

include_once('dashboard/petsitters.php');
include_once('dashboard/pets.php');
include_once('dashboard/requests.php');


if(!function_exists('kmimos_load_language')){
    function kmimos_load_language() { 
        load_plugin_textdomain( 'kmimos', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
}

/**
 *  Incluye las funciones de javascript en la página WEB bajo Wordpress
 * */

if(!function_exists('kmimos_include_scripts')){

    function kmimos_include_scripts(){
        wp_enqueue_script( 'kmimos_jqueryui_script', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array(), '1.11.1', true  );
        wp_enqueue_script( 'kmimos_filters_script',     get_home_url()."/wp-content/plugins/kmimos/".'javascript/kmimos-filters.js', array(), '1.0.0', true );
        wp_enqueue_script( 'kmimos_script',             get_home_url()."/wp-content/plugins/kmimos/".'javascript/kmimos.js', array(), '1.0.0', true );
        wp_enqueue_script( 'kmimos_fancy',              get_home_url()."/wp-content/plugins/kmimos/".'javascript/jquery.fancybox.pack.js', array(), '2.1.5', true );
        
        wp_enqueue_style( 'kmimos_style',               get_home_url()."/wp-content/plugins/kmimos/".'css/kmimos.css' );
        wp_enqueue_style( 'kmimos_filters_style',       get_home_url()."/wp-content/plugins/kmimos/".'css/kmimos-filters.css' );
        wp_enqueue_style( 'kmimos_fancy_style',         get_home_url()."/wp-content/plugins/kmimos/".'css/jquery.fancybox.css?v=2.1.5' );
    }

}

if(!function_exists('kmimos_include_admin_scripts')){

    function kmimos_include_admin_scripts(){

        wp_enqueue_script( 'kmimos_script', get_home_url()."/wp-content/plugins/kmimos/".'javascript/kmimos-admin.js', array(), '1.0.0', true );
        wp_enqueue_style( 'kmimos_style', get_home_url()."/wp-content/plugins/kmimos/".'css/kmimos-admin.css' );

        include_once('dashboard/assets/config_backpanel.php');

        global $current_user;

        $tipo = get_usermeta( $current_user->ID, "tipo_usuario", true );   

        switch ($tipo) {
            case 'Customer Service':

                global $post;
                $types = array(
                    'petsitters',
                    'pets',
                    'request',
                    'wc_booking',
                    'shop_order'
                );
                $pages = array(
                    'kmimos',
                    'create_booking'
                );

                if( count($_GET) == 0 || (!in_array($post->post_type, $types) && !in_array($_GET['page'], $pages)) ){
                    header("location: edit.php?post_type=petsitters");
                }

                echo kmimos_style(array(
                    "quitar_edicion",
                    "menu_kmimos",
                    "menu_reservas"
                ));

                if( $post->post_type == 'shop_order' || $post->post_type == 'wc_booking' ){
                    echo kmimos_style(array(
                        'habilitar_edicion_reservas'
                    )); 
                }

            break;
        }
    }

}

/**
 *  Define la estructura de los menúes en el área administrativa
 * */

if(!function_exists('kmimos_admin_menu')){

    function kmimos_admin_menu(){

        $opciones_menu_admin = array(
            array(
                'title'=>'Kmimos',
                'short-title'=>'Kmimos',
                'parent'=>'',
                'slug'=>'kmimos',
                'access'=>'manage_options',
                'page'=>'kmimos_panel',
                'icon'=>get_home_url()."/wp-content/plugins/kmimos/".'/assets/images/icon.png',
                'position'=>4,
            ),

            array(
                'title'=> __('Dashboard'),
                'short-title'=> __('Dashboard'),
                'parent'=>'kmimos',
                'slug'=>'kmimos',
                'access'=>'manage_options',
                'page'=>'kmimos_panel',
                'icon'=>'',
            ),

            array(
                'title'=>'Control de Reservas',
                'short-title'=>'Control de Reservas',
                'parent'=>'kmimos',
                'slug'=>'bp_reservas',
                'access'=>'manage_options',
                'page'=>'backpanel_reservas',
                'icon'=>plugins_url('/assets/images/icon.png', __FILE__),
            ),
            array(
                'title'=>'Control Conocer a Cuidador',
                'short-title'=>'Control Conocer a Cuidador',
                'parent'=>'kmimos',
                'slug'=>'bp_conocer_cuidador',
                'access'=>'manage_options',
                'page'=>'backpanel_conocer_cuidador',
                'icon'=>plugins_url('/assets/images/icon.png', __FILE__),
            ),
            array(
                'title'=>'Listado de Suscriptores',
                'short-title'=>'Listado de Suscriptores',
                'parent'=>'kmimos',
                'slug'=>'bp_suscriptores',
                'access'=>'manage_options',
                'page'=>'backpanel_subscribe',
                'icon'=>plugins_url('/assets/images/icon.png', __FILE__),
            ),
            array(
                'title'=>'Listado de Usuarios',
                'short-title'=>'Listado de Usuarios',
                'parent'=>'kmimos',
                'slug'=>'bp_usuarios',
                'access'=>'manage_options',
                'page'=>'backpanel_usuarios',
                'icon'=>plugins_url('/assets/images/icon.png', __FILE__),
            ),

            array(
                'title'=> __('Settings'),
                'short-title'=> __('Settings'),
                'parent'=>'kmimos',
                'slug'=>'kmimos-setup',
                'access'=>'manage_options',
                'page'=>'kmimos_setup',
                'icon'=>'',
            ),

        );

        // Crea los links en el menú del panel de control
        foreach($opciones_menu_admin as $opcion){
            if($opcion['parent']==''){
                add_menu_page($opcion['title'],$opcion['short-title'],$opcion['access'],$opcion['slug'],$opcion['page'],$opcion['icon'],$opcion['position']);
            } else{
                add_submenu_page($opcion['parent'],$opcion['title'],$opcion['short-title'],$opcion['access'],$opcion['slug'],$opcion['page']);
            }
        }

    }

}

/**
 *  Se registran los campos a usar
 * */

if(!function_exists('kmimos_admin_init')){

    function kmimos_admin_init(){
        register_setting('kmimos_group','kmimos_title_plugin');
        register_setting('kmimos_group','kmimos_description_plugin');
        register_setting('kmimos_group','kmimos_redirect_by_ip','intval');
        register_setting('kmimos_group','kmimos_notificar_por_email','intval');
    }

}

/**
 *  Inicializa el Panel Principal del menú en el área administrativa de Wordpress
 * */

if(!function_exists('kmimos_panel')){

    function kmimos_panel(){
        /*if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }*/
        include_once('dashboard/kmimos_panel.php');
    }

}

if(!function_exists('backpanel_conocer_cuidador')){
    function backpanel_conocer_cuidador(){
        include_once('dashboard/backpanel_conocer_cuidador.php');
    }
}

if(!function_exists('backpanel_reservas')){
    function backpanel_reservas(){
        include_once('dashboard/backpanel_reservas.php');
    }
}

if(!function_exists('backpanel_subscribe')){
    function backpanel_subscribe(){
        include_once('dashboard/backpanel_subscribe.php');
    }
}

if(!function_exists('backpanel_usuarios')){
    function backpanel_usuarios(){
        include_once('dashboard/backpanel_usuarios.php');
    }
}

/**
 *  Inicializa el Panel Principal del menú en el área administrativa de Wordpress
 * */

if(!function_exists('kmimos_setup')){

    function kmimos_setup(){
        /*if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }*/
        include_once('dashboard/kmimos_setup.php');
    }

}

if(!function_exists('ingeredes_kmimos')){

    function ingeredes_kmimos(){
        /*
        add_shortcode('kmimos_list','kmimos_list_shortcode');
        add_shortcode('kmimos_test','kmimos_test_shortcode');
        */
        add_shortcode('kmimos_search','kmimos_search_shortcode');
        add_shortcode('kmimos_rating','kmimos_rate_petsitter');
        add_shortcode('kmimos_request','kmimos_request_shortcode');
    }

}

if(!function_exists('kmimos_search_shortcode')){
    function kmimos_search_shortcode($args,$content){
        include_once('shortcodes/kmimos_search.php');
        return $content;
    }
}

if(!function_exists('kmimos_list_shortcode')){
    function kmimos_list_shortcode($args,$content){
        include_once('shortcodes/kmimos_list.php');
        return $content;
    }
}

if(!function_exists('kmimos_request_shortcode')){
    function kmimos_request_shortcode($args,$content){
        include_once('shortcodes/kmimos_request.php');
        return $content;
    }
}

if(!function_exists('kmimos_test_shortcode')){
    function kmimos_test_shortcode($args,$content){
        include_once('shortcodes/kmimos_test.php');
        return $content;
    }
}

if(!function_exists('kmimos_rate_petsitter')){
    function kmimos_rate_petsitter($args,$content){
        include_once('shortcodes/kmimos_rating.php');
        return $content;
    }
}

/**
 *  Agrega las valuaciones del cuidador en el comentario
 * */

if(!function_exists('kmimos_get_valuations_of_petsitter')){

    function kmimos_get_valuations_of_petsitter($comment,$petsitter) {
        $html = '<div class="comments_valuations" style="display: inline-block; width: 100%;">';
        $html .= '  <div class="comment_valuation" style="width: 140px; float: left; margin:10px;">';
        $html .= '      <label style="margin-left:5px;"><strong>Cuidado</strong></label>';
        $html .=        kmimos_draw_rating(get_comment_meta( $comment, 'care', true ),1);
        $html .= '  </div>';
        $html .= '  <div class="comment_valuation" style="width: 140px; float: left; margin:10px;">';
        $html .= '      <label style="margin-left:5px;"><strong>Puntualidad</strong></label>';
        $html .=        kmimos_draw_rating(get_comment_meta( $comment, 'punctuality', true ),1);
        $html .= '  </div>';
        $html .= '  <div class="comment_valuation" style="width: 140px; float: left; margin:10px;">';
        $html .= '      <label style="margin-left:5px;"><strong>Limpieza</strong></label>';
        $html .=        kmimos_draw_rating(get_comment_meta( $comment, 'cleanliness', true ),1);
        $html .= '  </div>';
        $html .= '  <div class="comment_valuation" style="width: 140px; float: left; margin:10px;">';
        $html .= '      <label style="margin-left:5px;"><strong>Confianza</strong></label>';
        $html .=        kmimos_draw_rating(get_comment_meta( $comment, 'trust', true ),1);
        $html .= '  </div>';
        $html .= '</div>';
        $html .= '<div class="clr"></div>';
        return $html;
    }

}

/**

 *  Devuelve la cantidad de sobreprecio aplicada a los servicios de los cuidadores.

 * */

if(!function_exists('get_referred_list_options')){
    function get_referred_list_options(){
        $opciones = array(
            'Facebook'      =>  'Facebook',
            'Adwords'       =>  'Buscador de Google',
            'Twitter'       =>  'Twitter',
            'Amigo/Familiar'        =>  'Recomendación de amigo o familiar',
            'Youtube'               =>  'Youtube',
            'Otros'                 =>  'Otros'
        );
        return $opciones;
    }
}

/**
 *  Devuelve la valoración del cuidador.
 * */
if(!function_exists('kmimos_petsitter_rating_and_votes')){

    function kmimos_petsitter_rating_and_votes($post_id){
        global $wpdb;
        $r = $wpdb->get_row("SELECT rating, valoraciones FROM cuidadores WHERE id_post = ".$post_id);
        $rating = $r->rating;
        $votes  = $r->valoraciones;
        return array('rating'=>$rating, 'votes'=>$votes);
    }

}

if(!function_exists('vlz_actualizar_ratings')){

    function vlz_actualizar_ratings($post_id){

        $valoracion=array();

        $comments = get_comments(array( 'post_id' => $post_id ) );

        $rating=0;

        $votes=0;

        if(count($comments)>0){

            $list = array();

            foreach($comments as $comment){

                $care = get_comment_meta( $comment->comment_ID, 'care', true );

                $punctuality = get_comment_meta( $comment->comment_ID, 'punctuality', true );

                $cleanliness = get_comment_meta( $comment->comment_ID, 'cleanliness', true );

                $trust = get_comment_meta( $comment->comment_ID, 'trust', true );

                if($care != 0 || $punctuality != 0 || $cleanliness != 0 || $trust != 0) {

                    $votes++;

                    $items = 0;

                    $mean = 0;

                    if($care != 0){

                        $items++;

                        $mean += $care;

                    }

                    if($punctuality != 0){

                        $items++;

                        $mean += $punctuality;

                    }

                    if($cleanliness != 0){

                        $items++;

                        $mean += $cleanliness;

                    }

                    if($trust != 0){

                        $items++;

                        $mean += $trust;

                    }

                    $rating += $mean/$items;

                }

            }

            if( $votes > 0){
                $rating = $rating/$votes;
            }else{
                $rating = 0;
            }

        } else {
            $rating = 0;
            $votes = 0;
        }

        global $wpdb;

        $wpdb->query("UPDATE cuidadores SET rating = '".$rating."', valoraciones = '".$votes."' WHERE id_post = ".$post_id);

    }

}

/**
 *  Devuelve la valoración del cuidador.
 * */

if(!function_exists('kmimos_petsitter_rating')){

    function kmimos_petsitter_rating($post_id){
        $html = '<div class="text-center rating">';
        $valoracion = kmimos_petsitter_rating_and_votes($post_id);
        $votes = $valoracion['votes'];
        $rating = $valoracion['rating'];
        if($votes =='' || $votes == 0 || $rating ==''){ 
            $html .= '<div id="rating">';
            for ($i=0; $i<5; $i++){ 
                $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/vacio.png">';
            }
            $html .= '</div>';
            $html .= '<div class="vlz_valoraciones">Este cuidador no ha sido valorado</div>';
        } else { 
            $html .= '<div id="rating">';
            for ($i=0; $i<5; $i++){ 
                if(intval($rating)>$i) { 
                    $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/100.png">';
                } else if(intval($rating)<$i) {
                    $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/0.png">';
                } else {
                    $residuo = ($rating-$i)*100+12.5;
                    $residuo = intval($residuo/25);
                    switch($residuo){
                        case 4: // 100% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/100.png">';
                        break;
                        case 3: // 75% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/75.png">';
                        break;
                        case 2: // 50% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/50.png">';
                        break;
                        case 1: // 25% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/25.png">';
                        break;
                        default: // 0% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/0.png">';
                        break;
                    }
                }
            }
            $html .= '</div>';
            $valoracion = ($votes==1)? ' Valoración':' Valoraciones';
            $html .= '<div class="vlz_valoraciones">('. number_format($rating,2).') '.$votes .$valoracion. '</div>';
        }
        $html .= '</div>';
        return $html;
    }
}

if(!function_exists('kmimos_user_info_ready')){

    function kmimos_user_info_ready($user_id){
        $nombre = get_user_meta($user_id,'first_name',true);
        $apellido = get_user_meta($user_id,'last_name',true);
        $local = get_user_meta($user_id,'user_phone',true);
        $movil = get_user_meta($user_id,'user_mobile',true);
        if ($local!='' || $movil!='') {
            $telefono= true;
        }else{
            $telefono= false;
        }
        $ready = ($nombre!='' && $apellido!='' && $telefono==true );
        return $ready;
    }
}

/**

 *  Devuelve la cantidad y la lista de mascotas que posee el usuario.

 * */

if(!function_exists('kmimos_get_my_pets')){

    function kmimos_get_my_pets($user_id){
        global $wpdb;

        $sql  = "SELECT COUNT(*) AS count, GROUP_CONCAT(p.ID SEPARATOR ',') AS list, ";
        $sql .= "GROUP_CONCAT(pn.meta_value SEPARATOR ',') AS names, ";
        $sql .= "pr.nombre AS breed_name ";
        $sql .= "FROM $wpdb->posts AS p  ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS pm ON (p.ID=pm.post_id AND pm.meta_key='owner_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS pn ON (p.ID=pn.post_id AND pn.meta_key='name_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS pb ON (p.ID=pb.post_id AND pb.meta_key='breed_pet') ";
        $sql .= "LEFT JOIN razas AS pr ON pr.id=pb.meta_value ";
        $sql .= "WHERE p.post_type = 'pets' AND p.post_status = 'publish' ";
        $sql .= "AND pm.meta_value = ".$user_id;
        return $wpdb->get_row($sql, ARRAY_A);
    }
}

if(!function_exists('kmimos_upload_photo')){
    function kmimos_upload_photo( $name, $pathDestino, $fieldName, $file, $width=800, $heigth=600 ) {

        $file = $_FILES;
        $ext = pathinfo($file[$fieldName]['name'], PATHINFO_EXTENSION);
        $size = $file[$fieldName]['size'];
        $fullpath = "{$pathDestino}{$name}.{$ext}";

        if( move_uploaded_file($file[$fieldName]['tmp_name'], $fullpath) ) { 

            $gis = getimagesize( $fullpath );
            $type = $gis[2];              
            switch($type){
                case "1": $imorig = @imagecreatefromgif($fullpath); break;
                case "2": $imorig = @imagecreatefromjpeg($fullpath);break;
                case "3": $imorig = @imagecreatefrompng($fullpath); break;   
                default:  $imorig = @imagecreatefromjpeg($fullpath);
            }

            $x = imagesx($imorig);
            $y = imagesy($imorig);

            $aw = $width;
            $ah = $heigth;
            $im = imagecreatetruecolor($aw,$ah);

            if (imagecopyresampled($im, $imorig, 0, 0, 0, 0, $aw, $ah, $x, $y)){
                imagejpeg($im, $fullpath);
            }

            return [
                'path'=>$fullpath, 
                'name'=>"{$name}.{$ext}", 
                'sts'=>true
            ];
        }else{
            return ['sts'=>false];
        }
    }
}

include_once('kmimos-email.php');

?>

