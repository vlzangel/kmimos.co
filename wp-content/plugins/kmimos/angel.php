<?php
	
    include_once('includes/functions/vlz_functions.php');
    include_once('angel/funciones.php');
	include_once('angel/admin.php');

	if(!function_exists('angel_include_script')){
	    function angel_include_script(){
	        
	    }
	}

	if(!function_exists('angel_include_admin_script')){
	    function angel_include_admin_script(){
	        global $current_user;

	        $tipo = get_usermeta( $current_user->ID, "tipo_usuario", true );   

	        switch ($tipo) {
	            case 'Customer Service':
	                global $post;
	                echo kmimos_style(array(
	                    "quitar_edicion",
	                    "menu_kmimos",
	                    "menu_reservas"
	                ));
                    echo kmimos_style($styles = array("customer_services"));
                    echo "<script> window.onload = function(){ jQuery('#toplevel_page_kmimos > a').attr('href', 'admin.php?page=bp_reservas'); }; </script>";
	            break;

                case 'Teleoperador':
                    echo kmimos_style($styles = array("teleoperadores"));
                    echo "
                        <script>
                            window.onload = function(){
                                jQuery('#toplevel_page_kmimos > a').attr('href', 'admin.php?page=bp_reservas');
                            };
                        </script>
                    ";
                break;

                case 'Auditores':
                    echo kmimos_style($styles = array("auditores"));
                break;

                case 'Supervisor':
                    echo kmimos_style($styles = array("supervisores"));
                    echo "
                        <script>
                            window.onload = function(){
                                jQuery('#toplevel_page_woocommerce > a').attr('href', 'edit.php?post_type=shop_coupon');
                                jQuery('#toplevel_page_kmimos > a').attr('href', 'admin.php?page=bp_reservas');
                            };
                        </script>
                    ";
                break;
	        }

            echo kmimos_style($styles = array("no_update"));

	    }
	}

	if(!function_exists('angel_menus')){
	    function angel_menus($menus){
	        


	        return $menus;
	    }
	}

	if(!function_exists('kmimos_get_info_syte')){
	    function kmimos_get_info_syte(){
	        return array(
	            "pais"      => "México",
	            "titulo"    => "Kmimos México",
	            "email"     => "contactomex@kmimos.la",

                "telefono" => "(01) 55 4742 3162",
                "telefono_sincosto" => "(01) 800 056 4667",
                "whatsapp"=> "+52 (55) 6892 2182", 

	            "twitter"   => "kmimosmx",
	            "facebook"  => "Kmimosmx",
	            "instagram" => "kmimosmx",
	            "mon_izq" => "",
	            "mon_der" => "$"
	        );
	    }
	}

	if(!function_exists('kmimos_mails_administradores_new')){       
        function kmimos_mails_administradores_new($titulo, $mensaje){     
      
            // $info = kmimos_get_info_syte();
            // $email_admin = $info["email"];

            // $headers_admins = array(
            //     'BCC: e.celli@kmimos.la',
            //     'BCC: a.lazaro@kmimos.la',
            //     'BCC: r.cuevas@kmimos.la',
            //     'BCC: r.gonzalez@kmimos.la',
            //     'BCC: m.castellon@kmimos.la',
            //     'BCC: y.chaudary@kmimos.la'
            // );

            // wp_mail( $email_admin, $titulo, $mensaje, $headers_admins);

            // $headers_call_center = array(
            //     'BCC: operador01sincola@gmail.com',
            //     'BCC: operador04sincola@gmail.com',
            //     'BCC: Operador05sincola@gmail.com',
            //     'BCC: Operador06sincola@gmail.com',
            //     'BCC: robertomadridcisneros@gmail.com',
            //     'BCC: jordiballarin@gmail.com',
            //     'BCC: supervisor01sincola@gmail.com',
            //     'BCC: supervisor02sincola@gmail.com' 
            // );

            // wp_mail( "a.veloz@kmimos.la", $titulo, $mensaje, $headers_call_center);       
            wp_mail( "a.veloz@kmimos.la", $titulo, $mensaje);       
        
        }     
    }

    if(!function_exists('vlz_servicios')){
        function vlz_servicios($adicionales, $is_data = false){
            $r = "";
                
            $adicionales = unserialize($adicionales);

            if( $is_data ){
                $data = array();
                
                if( $adicionales != "" ){
                    if( count($adicionales) > 0 ){
                        foreach($adicionales as $key => $value){
                            switch ($key) {
                                case 'guarderia':
                                    // $r .= '<img src="'.getTema().'/images/new/icon/icon-guarderia.svg" height="40">';
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
                                    if( $value > 0){
                                        $data[] = array(
                                            "img" => "icon-sello-4.svg",
                                            "titulo" => "Corte de pelo y u&ntilde;as"
                                        );
                                    }
                                break;
                                case 'bano':
                                    if( $value > 0){
                                        $data[] = array(
                                            "img" => "icon-sello-6.svg",
                                            "titulo" => "Ba&ntilde;o"
                                        );
                                    }
                                break;
                                case 'transportacion_sencilla':
                                    //$r .= '<span><i title="Transporte Sencillo" class="icon-transporte"></i></span>';
                                break;
                                case 'transportacion_redonda':
                                    //$r .= '<span><i title="Transporte Redondo" class="icon-transporte2"></i></span>';
                                break;
                                case 'visita_al_veterinario':
                                    if( $value > 0){
                                        $data[] = array(
                                            "img" => "icon-sello-1.svg",
                                            "titulo" => "Visita al Veterinario"
                                        );
                                    }
                                break;
                                case 'limpieza_dental':
                                    if( $value > 0){
                                        $data[] = array(
                                            "img" => "icon-sello-5.svg",
                                            "titulo" => "Limpieza Dental"
                                        );
                                    }
                                break;
                                case 'acupuntura':
                                    //$r .= '<span><i title="Acupuntura" class="icon-acupuntura"></i></span>';
                                break;
                                case 'paseos':
                                    //$r .= "<img src='".getTema()."/images/new/icon/icon-sello-3.svg' height='40' title='Paseos'> ";
                                    $data[] = array(
                                        "img" => "icon-sello-3.svg",
                                        "titulo" => "Paseos"
                                    );
                                break;
                            }
                        }

                        return $data;
                    }
                }

            }else{
                if( $adicionales != "" ){
                    if( count($adicionales) > 0 ){
                        foreach($adicionales as $key => $value){
                            switch ($key) {
                                case 'guarderia':
                                    // $r .= '<img src="'.getTema().'/images/new/icon/icon-guarderia.svg" height="40">';
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
                                    if( $value > 0){
                                        $r .= "<img src='".getTema()."/images/new/icon/icon-sello-4.svg' height='40' title='Corte de pelo y u&ntilde;as'> ";
                                    }
                                break;
                                case 'bano':
                                    if( $value > 0){
                                        $r .= "<img src='".getTema()."/images/new/icon/icon-sello-6.svg' height='40' title='Ba&ntilde;o'> ";
                                    }
                                break;
                                case 'transportacion_sencilla':
                                    //$r .= '<span><i title="Transporte Sencillo" class="icon-transporte"></i></span>';
                                break;
                                case 'transportacion_redonda':
                                    //$r .= '<span><i title="Transporte Redondo" class="icon-transporte2"></i></span>';
                                break;
                                case 'visita_al_veterinario':
                                    if( $value > 0){
                                        $r .= "<img src='".getTema()."/images/new/icon/icon-sello-1.svg' height='40' title='Visita al Veterinario'> ";
                                    }
                                break;
                                case 'limpieza_dental':
                                    if( $value > 0){
                                        $r .= "<img src='".getTema()."/images/new/icon/icon-sello-5.svg' height='40' title='Limpieza Dental'> ";
                                    }
                                break;
                                case 'acupuntura':
                                    //$r .= '<span><i title="Acupuntura" class="icon-acupuntura"></i></span>';
                                break;
                                case 'paseos':
                                    $r .= "<img src='".getTema()."/images/new/icon/icon-sello-3.svg' height='40' title='Paseos'> ";
                                break;
                            }
                        }

                        return $r;
                    }
                }
            }
            return "";
        }
    }

    if(!function_exists('servicios_adicionales')){
        function servicios_adicionales(){

            $extras = array(
                'transportacion_sencilla' => array( 
                    'label'=>'Transporte Sencillo',
                    'icon' => 'transporte'
                ),
                'transportacion_redonda' => array( 
                    'label'=>'Transporte Redondo',
                    'icon' => 'transporte2'
                ),
                'corte' => array( 
                    'label'=>'Corte de Pelo y Uñas',
                    'icon' => 'peluqueria'
                ),
                'bano' => array( 
                    'label'=>'Baño y Secado',
                    'icon' => 'bano'
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

    if(!function_exists('path_base')){
        function path_base(){
            return dirname(dirname(dirname(__DIR__)));
        }
    }

    if(!function_exists('kmimos_get_foto')){
        function kmimos_get_foto($user_id, $get_sub_path = false){
            global $wpdb;

            $user = new WP_User( $user_id );
            if( $user->roles[0] == "vendor" ){
                $id = $wpdb->get_var("SELECT id FROM cuidadores WHERE user_id = {$user_id}");
                $sub_path = "cuidadores/avatares/{$id}/";
            }else{
                $sub_path = "avatares_clientes/{$user_id}/";
            }

            $name_photo = get_user_meta($user_id, "name_photo", true);
            if( empty($name_photo)  ){ $name_photo = "0"; }
            
            if( count(explode(".", $name_photo)) == 1 ){ $name_photo .= ".jpg"; }
            
            $base = path_base();
            if( file_exists($base."/wp-content/uploads/{$sub_path}{$name_photo}") ){
                $aSize = getImageSize( $base."/wp-content/uploads/{$sub_path}/{$name_photo}" );
                if( $aSize[0] > 0 ){
                    $img = get_home_url()."/wp-content/uploads/{$sub_path}{$name_photo}";
                }else{
                    $img = get_home_url()."/wp-content/themes/kmimos/images/noimg.png";
                }
            }else{
                $img = get_home_url()."/wp-content/themes/kmimos/images/noimg.png";
            }

            if($get_sub_path){
                return array(
                    "img" => $img,
                    "sub_path" => $sub_path
                );
            }else{
                return $img;
            }
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

                if( in_array("teleoperadores", $styles) ){
                    $salida .= "
                        .menu-top,
                        #toplevel_page_kmimos li{
                            display: none;
                        }

                        #toplevel_page_kmimos
                        {
                            display: block;
                        }
                        #adminmenu li.wp-menu-separator {
                            display: none;
                        }

                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(6),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(7),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(9),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(10)
                        {
                            display: block !important;
                        }

                        table.dataTable thead *{
                            font-size: 12px !important;
                        }
                        table.dataTable tbody *{
                            font-weight: 600 !important;
                            font-size: 10px !important;
                        }
                    ";
                }

                if( in_array("supervisores", $styles) ){
                    $salida .= "
                        .menu-top,
                        #toplevel_page_kmimos li,
                        #toplevel_page_woocommerce li{
                            display: none;
                        }

                        #toplevel_page_kmimos,
                        #toplevel_page_woocommerce
                        {
                            display: block;
                        }
                        #adminmenu li.wp-menu-separator {
                            display: none;
                        }

                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(6),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(7),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(9),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(10),

                        #toplevel_page_woocommerce ul.wp-submenu li:nth-child(3)
                        {
                            display: block !important;
                        }

                        table.dataTable thead *{
                            font-size: 12px !important;
                        }
                        table.dataTable tbody *{
                            font-weight: 600 !important;
                            font-size: 10px !important;
                        }                     
                    ";
                }

                if( in_array("auditores", $styles) ){
                    $salida .= "
                        .menu-top,
                        #toplevel_page_kmimos li{
                            display: none;
                        }

                        #toplevel_page_kmimos
                        {
                            display: block;
                        }
                        #adminmenu li.wp-menu-separator {
                            display: none;
                        }

                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(6),
                        /*
                            #toplevel_page_kmimos ul.wp-submenu li:nth-child(7),
                            #toplevel_page_kmimos ul.wp-submenu li:nth-child(9),
                        */
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(10)
                        {
                            display: block !important;
                        }

                        table.dataTable thead *{
                            font-size: 12px !important;
                        }
                        table.dataTable tbody *{
                            font-weight: 600 !important;
                            font-size: 10px !important;
                        }
                    ";
                }

                if( in_array("customer_services", $styles) ){
                    $salida .= "
                        .menu-top,
                        #toplevel_page_kmimos li{
                            display: none;
                        }

                        #toplevel_page_kmimos
                        {
                            display: block;
                        }
                        #adminmenu li.wp-menu-separator {
                            display: none;
                        }

                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(6),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(7),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(9),
                        #toplevel_page_kmimos ul.wp-submenu li:nth-child(10)
                        {
                            display: block !important;
                        }

                        table.dataTable thead *{
                            font-size: 12px !important;
                        }
                        table.dataTable tbody *{
                            font-weight: 600 !important;
                            font-size: 10px !important;
                        }                    
                    ";
                }

                if( in_array("no_update", $styles) ){
                    $salida .= "
                        .update-nag,
                        .updated,
                        .wc_plugin_upgrade_notice,
                        .update-message,
                        .menu-icon-dashboard .wp-submenu,
                        .upgrade {
                            display: none !important;
                        }   

                        .plugins .active.update td,
                        .plugins .active.update th, 
                        tr.active.update+tr.plugin-update-tr .plugin-update,
                        .plugins .active td, .plugins .active th{
                            background: #FFF !important;
                        }

                        .plugins .active.update th.check-column, .plugins .active.update+.plugin-update-tr .plugin-update,
                        .plugin-update-tr.active td, .plugins .active th.check-column {
                            border-left: 4px solid #ffffff !important;
                        }    

                    ";
                }


            $salida .= "</style>";

            return $salida;
            
        }
    }

?>