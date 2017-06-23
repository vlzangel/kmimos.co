<?php

	add_action('admin_init','kmimos_load_language'); 
	add_action('init','ingeredes_kmimos');
	add_action('admin_menu','kmimos_admin_menu');
	add_action('admin_init','kmimos_admin_init');
	add_action('admin_enqueue_scripts','kmimos_include_admin_scripts');
	add_action('wp_enqueue_scripts','kmimos_include_scripts');

	require_once('assets/class/class.filters.php');
	require_once('assets/class/class.featured.php');

	add_action('widgets_init','kmimos_widget_filters');
	add_action('widgets_init','kmimos_widget_featured');

	include_once('dashboard/petsitters.php');
	include_once('dashboard/pets.php');
	include_once('dashboard/requests.php');

	add_action('pre_get_posts', 'kmimos_filter_bookings_when_petsitters_login');

    /**
     *  Devuelve la cantidad de sobreprecio aplicada a los servicios de los cuidadores.
     * */
    if(!function_exists('get_referred_list_options')){
        function get_referred_list_options(){
            $opciones = array(
                'Volaris'       =>  'Volaris',
                'Facebook'      =>  'Facebook',
                'Adwords'       =>  'Buscador de Google',
                'Instagram'     =>  'Instagram',
                'Twitter'       =>  'Twitter',
                'Booking.com'   =>  'Booking.com',
                'Cabify'        =>  'Cabify',
                'Bancomer'      =>  'Bancomer',
                'Mexcovery'     =>  'Mexcovery',
                'Totems'        =>  'Totems',
                'Groupon'       =>  'Groupon',
                'Agencia IQPR'  =>  'Agencia IQPR',
                'Revistas o periodicos' =>  'Revistas o periodicos',
                'Vintermex'             =>  'Viajes Intermex',
                'Amigo/Familiar'        =>  'Recomendación de amigo o familiar',
                'Youtube'               =>  'Youtube',
                'Otros'                 =>  'Otros'
            );
            return $opciones;
        }
    }

	if(!function_exists('viejos_include_script')){
        function viejos_include_script(){
            wp_enqueue_script( 'kmimos_jqueryui_script',    get_home_url().'/wp-content/plugins/kmimos/javascript/jquery-ui.min.js', array(), '1.11.1', true  );
            wp_enqueue_script( 'kmimos_filters_script',     get_home_url().'/wp-content/plugins/kmimos/javascript/kmimos-filters.js', array(), '1.0.0', true );
            // wp_enqueue_script( 'kmimos_script',             get_home_url().'/wp-content/plugins/kmimos/javascript/kmimos.js', array(), '1.0.0', true );
            wp_enqueue_script( 'kmimos_fancy',              get_home_url().'/wp-content/plugins/kmimos/javascript/jquery.fancybox.pack.js', array(), '2.1.5', true );
            wp_enqueue_style( 'kmimos_style',               get_home_url().'/wp-content/plugins/kmimos/css/kmimos.css' );
            wp_enqueue_style( 'kmimos_filters_style',       get_home_url().'/wp-content/plugins/kmimos/css/kmimos-filters.css' );
            wp_enqueue_style( 'kmimos_fancy_style',         get_home_url().'/wp-content/plugins/kmimos/css/jquery.fancybox.css?v=2.1.5' );
        }
    }

	if(!function_exists('viejos_include_admin_script')){
	    function viejos_include_admin_script(){
            wp_enqueue_script( 'kmimos_script', get_home_url().'/wp-content/plugins/kmimos/javascript/kmimos-admin.js', array(), '1.0.0', true );
            wp_enqueue_style( 'kmimos_style', get_home_url().'/wp-content/plugins/kmimos/css/kmimos-admin.css' );
	    }
	}

	if(!function_exists('kmimos_load_language')){
	    function kmimos_load_language() { 
	        load_plugin_textdomain( 'kmimos', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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
 *  Devuelve la lista de usuarios que tienen asignado el rol pasado como parámetro
 * */

if(!function_exists('get_users_by_role')){

    function get_users_by_role($search) {
        $users = get_users_of_blog();
        $result = array();
        foreach ( (array) $users as $user ) {
            $roles = unserialize($user->meta_value);
            foreach ( (array) $roles as $role => $val ) {
                if($role==$search){
                    array_push($result,$user->user_id);
                }
            }
        }
        return $result;
    }

}

/**
 *  Carga los valores de los campos adicionales del post seleccionado, 
 *  el parámetro arrays contiene los campos almacenados como arreglos
 * */

if(!function_exists('kmimos_get_fields_values')){

    function kmimos_get_fields_values($arrays) {

        $fields = array();
        $id = get_the_id();
        $values = get_post_meta ($id);

        if( count($values) > 0){
            foreach ($values as $key => $value) {
                if(substr($key, 0, 1) != '_'){
                    if(in_array($key, $arrays)){
                        $fields[$key]= json_decode($value[0], true);
                    } else {
                        $fields[$key]=$value[0];
                    }
                }
            }
        }
        return $fields;

    }

}

/**
 *  Guarda los valores de los campos adicionales del post seleccionado, 
 *  el parámetro $fields contiene los campos almacenados como arreglos $name=>$type
 * */

if(!function_exists('kmimos_set_fields_values')){

    function kmimos_set_fields_values($post_id, $fields) {
        foreach ($fields as $name=>$type) {
            if(isset($_POST[$name]) || $type=='checkbox' ){
                switch($type){
                    case 'uppercase':
                        update_post_meta($post_id, $name, sanitize_text_field(strtoupper($_POST[$name])));
                    break;
                    case 'array':
                        $values=array();
                        foreach($_POST[$name] as $key=>$value){ $values[$key]=$value; }
                        update_post_meta($post_id, $name,json_encode($values));
                    break;

                    case 'serial':
                        $values=array();
                        foreach($_POST[$name] as $key=>$value){ $values[$key]=$value; }
                        update_post_meta($post_id, $name,serialize($values));
                    break;

                    case 'checkbox':
                        update_post_meta($post_id, $name, (($_POST[$name]=='yes' || $_POST[$name]=='1')?'1':'0') );
                    break;

                    default:
                        update_post_meta($post_id, $name, sanitize_text_field($_POST[$name]));
                    break;
                }
            }
        }

    }

}

/**
 *  Crea el widget con los filtros para el buscador de cuidadores
 * */

if(!function_exists('kmimos_widget_filters')){

    function kmimos_widget_filters() {
        register_widget('kmimos_filters_class');
    }

}

/**
 *  Crea el widget con los cuidadores destacados
 * */

if(!function_exists('kmimos_widget_featured')){

    function kmimos_widget_featured() {

        register_widget('kmimos_featured_class');

    }

}

/**
 *  Crea un producto agendable (bookable) y lo asocia al cuidador
 * */

if(!function_exists('kmimos_add_service_to_cart')){

    function kmimos_add_bookable_product($petsitter, $service) {
        // Verifica si existe en la tabla de productos uno para el servicio indicado asociado al cuidador
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
 *  Crea o actualiza el registro del cuidador en la tabla auxiliar (wp_petsitters)
 * */

if(!function_exists('kmimos_uptate_petsitter_table')){
    function kmimos_uptate_petsitter_table($petsitter_id) { }
}

/**
 *  Crea una función que permite agregar o actualizar un producto del cuidador
 * */

if(!function_exists('kmimos_product_of_petsitter')){

    function kmimos_product_of_petsitter($owner_id, $product_type = 0, $country = 'co') {

        global $wpdb;

        $paises = array(
            'co'=>array('name'=>'Colombia', 'currency'=>'COL $','iso'=>'COL', 'symbol'=>'$', 'decimals'=>2)
        );

        $tipos = array(
            array('code'=>'HOST','desc'=>'hospedaje'),
            array('code'=>'GUAR','desc'=>'guarderia'),
        );

        $tamanos = array('Pequeñas', 'Medianas', 'Grandes', 'Gigantes');
        $rutas = array('Cortas', 'Medias', 'Largas');

        // Taxonomías de hospedaje, guarderia, paseos, adiestramiento, baño y secado y corte de pelo y uñas

        $categorias= "2598,2599,2601,2602,2603,2604"; 

        $owner = get_post($owner_id);
        $desc_owner = str_replace("'","\'",$owner->post_content);
        $image_petsitter = get_post_thumbnail_id($owner_id);
        $code_petsitter = get_post_meta($owner_id,'code_petsitter',true);
        $express_booking = get_post_meta($owner_id,'express_booking',true);
        $moneda = $paises[$country]['currency'];
        $simbolo = $paises[$country]['symbol'];
        $decimal = $paises[$country]['decimals'];

        /*
        *   Busca todos los productos que pertenezcan a la categoría de servicios del cuidador y que sus SKUs contengan el 
        *   código del cuidador expandido a 4 dígitos (____-pp-cccc) donde pp es el código del país, y le asigna como descripción
        *   larga del producto la descripción del cuidador y como parent_id el ID del cuidador.
        */

        $sql  = "
            SELECT 
                GROUP_CONCAT(pr.ID separator ',') AS ids, 
                GROUP_CONCAT(
                    (
                        SELECT 
                            GROUP_CONCAT(ID separator '-') 
                        FROM 
                            wp_posts 
                        WHERE 
                            post_type='bookable_person' AND 
                            post_parent=pr.ID 
                        ORDER BY 
                            menu_order ASC
                    ) separator ','
                ) AS mascotas 
            FROM $wpdb->posts AS pr
            LEFT JOIN $wpdb->postmeta AS pm ON (pr.ID=pm.post_id AND pm.meta_key='_sku') ";

        $sql .= "LEFT JOIN $wpdb->term_relationships AS ct ON pr.ID=ct.object_id ";

        $sql .= "WHERE pr.post_type='product' AND ct.term_taxonomy_id IN (".$categorias.") ";

        $sql .= "AND pm.meta_value LIKE '____-".strtoupper($country)."-".str_pad($code_petsitter,4,'0',STR_PAD_LEFT)."'";

        

//        return $sql;

        $descripcion ='';

        

        $servicios = $wpdb->get_row($sql);

        $ids = explode(',',$servicios->ids);

        $pet_sizes = explode(',',$servicios->mascotas);

        $wpdb->flush();

        $disabled = array();

        

        for($s=0; $s<count($ids); $s++){

            $service_id = $ids[$s];

            $service = get_post($service_id);

            $name = substr($service->post_name,0,strpos($service->post_name,'-'));

            $descripcion .= $name;

            if($name=='adiestramiento') {

                $level = substr($service->post_name,strpos($service->post_name,'-')+1);

                $level = substr($level,0, strpos($level,'-'));

                $descripcion  .= " ".$level;

            }

            /* 

            *   Actualiza la descripción del producto con la descripción del cuidador y asigna el ID del cuidador como parent_id

            */

            $sql = "UPDATE $wpdb->posts SET post_content='".$desc_owner."', post_parent=".$owner_id." WHERE ID=".$service_id;

            $wpdb->query($sql);

            $wpdb->flush();

            // Asigna al producto la imagen del vendedor

            set_post_thumbnail($service_id, $image_petsitter);

            $product_sku = get_post_meta($service_id,'_sku',true);

            // Lee la capacidad de mascotas a las que se le puede prestar el servicio

            if($name=='adiestramiento'){

                $capacidad = get_post_meta($owner_id,'mascotas_adiestramiento',false);

                $capacidad = $capacidad[0];

            }

            else $capacidad = get_post_meta($owner_id,'mascotas_'.$name,true);

            // Lee los precios por prestación del servicio según los distintos tamaños de mascotas

            $precios = get_post_meta($owner_id,'precio_'.$name,false);

            $precios = $precios[0];

            if($name=='adiestramiento') {

                switch($level){

                case 'basico':

                    $precios = $precios[0];

                    $capacidad = $capacidad[0];

                    break;

                case 'intermedio':

                    $precios = $precios[1];

                    $capacidad = $capacidad[1];

                    break;

                case 'avanzado':

                    $precios = $precios[2];

                    $capacidad = $capacidad[2];

                    break;

                }

            }

            update_post_meta($service_id, '_wc_booking_max_persons_group', $capacidad);

            update_post_meta($service_id, '_wc_booking_qty', $capacidad);

            $precio_base = min($precios);

            // Actualiza el precio base del servicio

            update_post_meta($service_id, '_price', $precio_base);

            update_post_meta($service_id, '_wc_booking_base_cost', $precio_base);

            $descripcion  .= ": ID Producto: ".$service_id.", Express: ".$express_booking;

            $descripcion .= ", Codigo: ".$product_sku;

            $pet_size = explode('-',$pet_sizes[$s]);

            for($i=0; $i<count($tamanos); $i++){

                $pet_size_id = $pet_size[$i];

                if(isset($precios[$i])){

                    update_post_meta($pet_size[$i], 'block_cost', $precios[$i]-$precio_base);

                    $sql = "UPDATE $wpdb->posts SET post_status='publish', post_excerpt='Precio por Mascotas ".$tamanos[$i].": ".$simbolo.number_format($precios[$i], $decimal)." c/u' WHERE ID =".$pet_size_id;

//        return $pet_sizes[$s].'::'.$sql;

                    $wpdb->query($sql);

                    $wpdb->flush();

//                   set_post_thumbnail( $servicio->ID, $image_petsitter );

                    $descripcion .= ", Mascotas ".$tamanos[$i].": ".$moneda." ".$precios[$i]." (".$pet_size_id.")";

                }

                else {  // se desactiva la opción del tamaño de mascono no ofrecido por el cuidador

                    $disabled[]=$pet_size[$i];  // Agrega el ID del tamaño de la mascota en la lista a deshabilitar

                    $descripcion .= ", No Acepta Mascotas ".$tamanos[$i]." (".$pet_size_id.")";

                }

            }

            $adicionales = array();

            $otros = array();

            $transporte = array();

            $opciones= array();

            // Pregunta si el usuario ofrece el servicio de transporte

            $simple = get_post_meta($owner_id,'transportacion_s',false);

            for($i=0; $i<count($rutas); $i++){

                if(isset($simple[0][$i])){

                    $opciones[]=array(

                        "label"=>"Transp. Sencillo - Rutas ".$rutas[$i],

                        "price"=>$simple[0][$i],

                        "min"=>"",

                        "max"=>""

                    );

                }

            }

            $doble = get_post_meta($owner_id,'transportacion_r',false);

            for($i=0; $i<count($rutas); $i++){

                if(isset($doble[0][$i])){

                    $opciones[]=array(

                        "label"=>"Transp. Redondo - Rutas ".$rutas[$i],

                        "price"=>$doble[0][$i],

                        "min"=>"",

                        "max"=>""

                    );

                }

            }

            if(count($opciones)>0){

                $transporte['name']="Servicios de Transportación (precio por grupo)";

                $transporte['description']="Rutas Cortas de 0 a 5Km

Rutas Medias de 5 a 10Km

Rutas Largas de 10 a 15Km";

                $transporte['type']="select";

                $transporte['position']=0;

                $transporte['options']=$opciones;

                $transporte['required']=0;

                $transporte['wc_booking_person_qty_multiplier']=0;

                $transporte['wc_booking_block_qty_multiplier']=0;

            }

            // Pregunta si el usuario ofrece el servicio de transporte

            $opciones= array();

            $bano = get_post_meta($owner_id,'bano_adicional',true);

            if($bano!='' && $bano>0){

                  $opciones[]=array(

                    "label"=>"Baño (precio por mascota)",

                    "price"=>$bano,

                    "min"=>"",

                    "max"=>""

                );

            }

            $corte = get_post_meta($owner_id,'corte_adicional',true);

            if($corte!='' && $corte>0){

                  $opciones[]=array(

                    "label"=>"Corte de Pelo y Uñas (precio por mascotas)",

                    "price"=>$corte,

                    "min"=>"",

                    "max"=>""

                );

            }

            $limpieza = get_post_meta($owner_id,'limpieza_dental',true);

            if($limpieza!='' && $limpieza>0){

                  $opciones[]=array(

                    "label"=>"Limpieza Dental (precio por mascota)",

                    "price"=>$limpieza,

                    "min"=>"",

                    "max"=>""

                );

            }

            $visita = get_post_meta($owner_id,'visita_veterinario',true);

            if($visita!='' && $visita>0){

                  $opciones[]=array(

                    "label"=>"Visita al Veterinario (precio por mascota)",

                    "price"=>$visita,

                    "min"=>"",

                    "max"=>""

                );

            }

            $acupuntura = get_post_meta($owner_id,'precio_acupuntura',true);

            if($acupuntura!='' && $acupuntura>0){

                  $opciones[]=array(

                    "label"=>"Acupuntura (precio por mascota)",

                    "price"=>$acupuntura,

                    "min"=>"",

                    "max"=>""

                );

            }

            if(count($opciones)>0){

                $otros['name']="Servicios Adicionales (precio por mascota)";

                $otros['description']="";

                $otros['type']="checkbox";

                $otros['position']=1;

                $otros['options']=$opciones;

                $otros['required']=0;

                $otros['wc_booking_person_qty_multiplier']=1;

                $otros['wc_booking_block_qty_multiplier']=0;

            }

            if(count($transporte)>0) $adicionales[]=$transporte;

            if(count($otros)>0) $adicionales[]=$otros;

            update_post_meta($service_id, '_product_addons', $adicionales);

            // Indica si se cuentan reservas por días o por noches

            $reserva_por_noches =($name=='hospedaje')? 'yes':'no';

            update_post_meta($service_id, '_wc_booking_count_nights', $reserva_por_noches);

            // Activa la reservación express dependeindo si el usuario tiene activada la bandera 

//            $requiere_confirmar = ($express_booking==1)? 'no': 'yes';

            $requiere_confirmar = 'no';

            update_post_meta($service_id, '_wc_booking_requires_confirmation', $requiere_confirmar);

            $descripcion .= '

';

        }

        $descripcion .= implode(',',$disabled);



        // Deshabilita los tamaños de mascotas que no son aceptados por el cuidador

        $sql = "UPDATE $wpdb->posts SET post_status='unpublish' WHERE ID IN (".implode(',',$disabled).")";

        $wpdb->query($sql);

        $wpdb->flush();

        return $descripcion;

    }

}

/**

 *  Crea un nuevo servicio del tipo indicado y se lo asigna al usuario indicado

 * */

if(!function_exists('kmimos_add_service_to_petsitter')){

    function kmimos_add_service_to_petsitter($user_id, $service_type) {

        $types = array(

            0 => array('name'=>'Hospedaje de Mascotas', 'model'=>4355, 'sizes'=>array(4356,4357,4358,4359)),

            1 => array('name'=>'Guardería de Mascotas', 'model'=>5522, 'sizes'=>array(5523,5524,5525,5526)),

            2 => array('name'=>'Baño y Secado de Mascotas', 'model'=>5968, 'sizes'=>array(5969,5970,5971,5972)),

            3 => array('name'=>'Adiestramiento Básico Obediencia', 'model'=>5997, 'sizes'=>array(5998,5999,6000,6001)),

            4 => array('name'=>'Adiestramiento Intermedio Obediencia', 'model'=>6002, 'sizes'=>array(6003,6004,6005,6006)),

            5 => array('name'=>'Adiestramiento Avanzado Obediencia', 'model'=>6007, 'sizes'=>array(6008,6009,6010,6011)),

        );

    }

}

/**

 *  Crea una función que permite agregar una reserva al carrito de compras

 * */

if(!function_exists('kmimos_add_service_to_cart')){

    function kmimos_add_service_to_cart($product_id) {

/*      if ( ! is_admin() ) {

            global $woocommerce;

//          $product_id = 15;

            $found = false;

            if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {

                foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {

                    $_product = $values['data'];

                    if ( $_product->id == $product_id )

                        $found = true;

                }

                if ( ! $found )

                    $woocommerce->cart->add_to_cart( $product_id );

            } else {

                $woocommerce->cart->add_to_cart( $product_id );

            }

        }*/

    }

}

/**

 *  Crea una función que permite mostrar en la tienda solo los productos asociados a la Kmitienda

 * */

if(!function_exists('kmimos_shop_filter_cat')){

    function kmimos_shop_filter_cat($query) {



//      if (!is_admin() && is_post_type_archive( 'product' ) && $query->is_main_query()) {

        if (!is_admin() && $query->query_vars['post_type']== 'product' && is_shop()) {

//          $query->query_vars['category__in'] = array('2589'); // Solo categoría Kmitienda

            $query->query_vars['category__not_in'] = array(2588);   // Excluye categoría de cuidadores

//print_r($query->query_vars);

//echo 'TIENDA '.get_query_var( 'category' );

        }



    }

}



if(!function_exists('kmimos_servicio_principal')){

    function kmimos_servicio_principal($servicios, $precios=0){

        $valores = array(

            'hospedaje'=>'hospedaje_desde', 'guarderia'=>'guarderia_desde', 'paseos'=>'paseos_desde', 'adiestramiento'=>'adiestramiento_desde', 'peluqueria'=>'peluqueria_desde', 'bano'=>'bano_desde', 'transporte'=>'simple_desde', 'transporte2'=>'doble_desde', 'veterinario'=>'visita_veterinario', 'bano2'=>'bano_adicional', 'peluqueria2'=>'corte_adicional','limpieza'=>'limpieza_dental', 'acupuntura'=>'precio_acupuntura');

        if(!isset($servicios) || count($servicios)==0) return 'precio_desde';

        foreach($valores as $key=>$value){

            if(in_array($key,$servicios)) {

                if ($precios==0) return $key;

                else return $value;

            }

        }

        return '';

    }

}

/**

 *  Crea una función que permite filtrar los cuidadores según un criterio especificado. 

 *  Devuelve un objeto con el total de cuidadores cuidadores y la lista de los IDs resultantes.

 * */

if(!function_exists('kmimos_filtra_cuidadores')){

    function kmimos_filtra_cuidadores($params, $destacados = 0){

        global $wpdb;

        $tipo = $params['tipo_busqueda'];

        $servicios = $params['servicio_cuidador'];

        if($params['servicio_adicional']!='') foreach($params['servicio_adicional'] as $adicional) $servicios[]=$adicional;

        $par = array();

        $par['servicios']= serializa_arreglo($servicios); // Servicios seleccionados

        $par['ubicacion']=($tipo=='mi-ubicacion')?'':serializa_arreglo($params['ubicacion_cuidador']); // Ubicaciones  seleccionadas

        $par['rango_precio']= (isset($params['precio_minimo']) && isset($params['precio_maximo']))? 

            $params['precio_minimo'].','. $params['precio_maximo']:''; // Rango de precio seleccionado

        $par['rango_exper']= (isset($params['experiencia_minima']) && isset($params['experiencia_maxima']))?

            $params['experiencia_minima'].','. $params['experiencia_maxima']:''; // Rango de experiencia seleccionado

        $par['rango_valor']= (isset($params['valoracion_minima']) && isset($params['valoracion_maxima']))?

            $params['valoracion_minima'].','. $params['valoracion_maxima']:''; // Rango de valoración del cuidador seleccionado

        $par['rango_rank']= (isset($params['ranking_minimo']) && isset($params['ranking_maximo']))?

            $params['ranking_minimo'].','. $params['ranking_maximo']:''; // Ranking del cuidador seleccionado

        $par['acepta']= serializa_arreglo($params['acepta_mascotas']); // Tamaños de mascotas aceptadas por el cuidador

        $par['tiene']= serializa_arreglo($params['tiene_mascotas']); // Tamaños de mascotas que tiene el cuidador

        $par['conductas']= serializa_arreglo($params['acepta_conductas']); // Conductas de mascotas aceptadas por el cuidador

        $par['latitud'] = $params['latitud'];

        $par['longitud'] = $params['longitud'];

        $par['distancia'] = ($tipo=='mi-ubicacion')? $params['distancia']:0;

//        $par['orden'] = $params['orderby'];

        if($destacados==0) $par['orden'] = (substr($params['orderby'],-5)=='_desc')? 'distance_desc': 'distance_asc';

        else $par['orden'] = 'random';

        $sql = "CALL listOfPetsitters('".$par['servicios']."','".$par['ubicacion']."','". $par['rango_precio']."','". $par['rango_exper']."','".$par['rango_valor']."','".$par['rango_rank']."','".$par['acepta']."','".$par['tiene']. "','".$par['conductas']."','".$par['orden']."','".$par['latitud']."','".$par['longitud']."','".$par['distancia']. "','".$destacados."',@total,@ids)";

        $result = $wpdb->get_results($sql);

        return $result[0];

    }

    function serializa_arreglo($arr){

        if(is_array($arr)) return implode(',',$arr);

        return $arr;

    }

}

/**

 *  Devuelve la cantidad de sobreprecio aplicada a los servicios de los cuidadores.

 * */

if(!function_exists('kmimos_get_over_price')){

    function kmimos_get_over_price(){

        return 1.2;

    }

}

/**

 *  Devuelve la cantidad y la lista de servicios que posee el usuario como cuidador.

 * */

if(!function_exists('kmimos_get_my_services')){

    function kmimos_get_my_services($user_id){

        global $wpdb;

        

        // $sql = "SELECT COUNT(*) AS count, GROUP_CONCAT(pr.ID SEPARATOR ',') AS list ";

        // $sql .= "FROM $wpdb->posts AS pr LEFT JOIN $wpdb->posts AS ps ON pr.post_parent=ps.ID ";

        // $sql .= "LEFT JOIN $wpdb->postmeta AS pm ON (ps.ID=pm.post_id AND pm.meta_key='user_petsitter') ";

        // $sql .= "WHERE pr.post_type = 'product' AND pr.post_status = 'publish' ";

        // $sql .= "AND ps.post_type = 'petsitters' AND ps.post_status = 'publish' ";

        // $sql .= "AND pm.meta_value = ".$user_id;

        $sql = "SELECT COUNT(*) AS count, GROUP_CONCAT(ID SEPARATOR ',') AS list FROM wp_posts WHERE post_type = 'product' AND post_author='{$user_id}' ";

        return $wpdb->get_row($sql, ARRAY_A);

    }

}

/**

 *  Devuelve la cantidad y la lista de servicios que posee el usuario como cuidador.

 * */
//Jaurgeui
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
 *  Devuelve la cantidad y la lista de servicios que posee el usuario como cuidador.
 * */

if(!function_exists('kmimos_get_petsitter_services_categories')){

    function kmimos_get_petsitter_services_categories($petsitter_id){

        global $wpdb;

        

        $sql = "SELECT COUNT(*) AS count, GROUP_CONCAT(t.term_id SEPARATOR ',') AS list, ";

        $sql .= "GROUP_CONCAT(t.name SEPARATOR ',') AS services ";

        $sql .= "FROM $wpdb->posts AS pr LEFT JOIN $wpdb->posts AS ps ON pr.post_parent=ps.ID ";

        $sql .= "LEFT JOIN $wpdb->term_relationships AS tr ON pr.ID=tr.object_id ";

        $sql .= "LEFT JOIN $wpdb->terms AS t ON t.term_id=tr.term_taxonomy_id ";

        $sql .= "LEFT JOIN $wpdb->term_taxonomy AS tt ON tt.term_taxonomy_id=tr.term_taxonomy_id ";

        $sql .= "WHERE pr.post_type = 'product' AND pr.post_status = 'publish' ";

        $sql .= "AND tt.taxonomy = 'product_cat' AND ps.ID = ".$petsitter_id;

//return $sql;        

        return $wpdb->get_row($sql, ARRAY_A);

    }

}

/**

 *  Devuelve los detalles del servicio indicado.

 * */

if(!function_exists('kmimos_get_service_info')){

    function kmimos_get_service_info($service_id){

        global $wpdb;

        

        $transporte = array(

            'name' => 'Servicios de Transportación (precio por grupo)',

            'description' => 'Rutas Cortas de 0 a 5Km

Rutas Medias de 5 a 10Km

Rutas Largas de 10 a 15Km',

            'type' => 'select',

            'position' => 0,

            'options' => array(

                '0' => array(

                    'label' => 'Transp. Sencillo - Rutas Cortas',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '1' => array(

                    'label' => 'Transp. Sencillo - Rutas Medias',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '2' => array(

                    'label' => 'Transp. Sencillo - Rutas Largas',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '3' => array(

                    'label' => 'Transp. Redondo - Rutas Cortas',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '4' => array(

                    'label' => 'Transp. Redondo - Rutas Medias',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '5' => array(

                    'label' => 'Transp. Redondo - Rutas Largas',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                )

            ),

            'required' => 0,

            'wc_booking_person_qty_multiplier' => 0,

            'wc_booking_block_qty_multiplier' => 0

        );

        $adicionales = array(

            'name' => 'Servicios Adicionales (precio por mascota)',

            'description' => '',

            'type' => 'checkbox',

            'position' => 1,

            'options' => array (

                '0' => array (

                    'label' => 'Baño (precio por mascota)',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '1' => array (

                    'label' => 'Corte de Pelo y Uñas (precio por mascota)',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '2' => array (

                    'label' => 'Visita al Veterinario (precio por mascota)',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '3' => array (

                    'label' => 'Limpieza Dental (precio por mascota)',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                ),

                '4' => array (

                    'label' => 'Acupuntura (precio por mascota)',

                    'price' => '',

                    'min' => '',

                    'max' => ''

                )

            ),

            'required' => 0,

            'wc_booking_person_qty_multiplier' => 1,

            'wc_booking_block_qty_multiplier' => 0

        );



        if($service_id!='') {

            $parent_cat = 2588;

            $sql = "SELECT sv.ID, GROUP_CONCAT(tr.term_taxonomy_id SEPARATOR ',') AS category, sv.post_title AS title, ";

            $sql .= "sv.post_excerpt AS short, (SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM $wpdb->posts ";

            $sql .= "WHERE post_type='bookable_person' AND post_parent=sv.ID) AS sizes, cp.meta_value AS capacity, ";

            $sql .= "ad.meta_value AS addons ";

            $sql .= "FROM $wpdb->posts AS sv LEFT JOIN $wpdb->term_relationships AS tr ON tr.object_id=sv.ID ";

            $sql .= "LEFT JOIN $wpdb->term_taxonomy AS tt ON tt.term_taxonomy_id=tr.term_taxonomy_id ";

            $sql .= "LEFT JOIN $wpdb->postmeta AS cp ON (sv.ID=cp.post_id AND cp.meta_key='_wc_booking_max_persons_group') ";

            $sql .= "LEFT JOIN $wpdb->postmeta AS ad ON (sv.ID=ad.post_id AND ad.meta_key='_product_addons') ";

            $sql .= "WHERE tt.parent = $parent_cat AND sv.ID = ".$service_id;



            $services = $wpdb->get_row($sql, ARRAY_A);

        

            $addons = unserialize($services['addons']);

        

            foreach($addons as $addon){

                switch($addon['name']){

                case 'Servicios de Transportación (precio por grupo)':

                    foreach($addon['options'] as $key=>$option){

                        switch($option['label']){

                        case 'Transp. Sencillo - Rutas Cortas':

                            $transporte['options'][0]['price']=$option['price'];

                            break;

                        case 'Transp. Sencillo - Rutas Medias':

                            $transporte['options'][1]['price']=$option['price'];

                            break;

                        case 'Transp. Sencillo - Rutas Largas':

                            $transporte['options'][2]['price']=$option['price'];

                            break;

                        case 'Transp. Redondo - Rutas Cortas':

                            $transporte['options'][3]['price']=$option['price'];

                            break;

                        case 'Transp. Redondo - Rutas Medias':

                            $transporte['options'][4]['price']=$option['price'];

                            break;

                        case 'Transp. Redondo - Rutas Largas':

                            $transporte['options'][5]['price']=$option['price'];

                            break;

                        }

                    }

                    break;

                case 'Servicios Adicionales (precio por mascota)':

                    foreach($addon['options'] as $key=>$option){

                        switch($option['label']){

                        case 'Baño (precio por mascota)':

                            $adicionales['options'][0]['price']=$option['price'];

                            break;

                        case 'Corte de Pelo y Uñas (precio por mascota)':

                            $adicionales['options'][1]['price']=$option['price'];

                            break;

                        case 'Visita al Veterinario (precio por mascota)':

                            $adicionales['options'][2]['price']=$option['price'];

                            break;

                        case 'Limpieza Dental (precio por mascota)':

                            $adicionales['options'][3]['price']=$option['price'];

                            break;

                        case 'Acupuntura (precio por mascota)':

                            $adicionales['options'][4]['price']=$option['price'];

                            break;

                        }

                    }

                    break;

                }

            }

        }

        $services['addons']=serialize(array('0'=>$transporte,'1'=>$adicionales));

        return $services;

    }

}

/**

 *  Devuelve los detalles del precio del servicio para el tamaño de mascota indicado.

 * */

if(!function_exists('kmimos_get_detail_for_size')){

    function kmimos_get_detail_for_size($size_id){

        global $wpdb;

        

        $sql = "SELECT sz.ID, (pb.meta_value+pz.meta_value) AS price, sz.post_title AS title, sz.post_status AS status ";

        $sql .= "FROM $wpdb->posts AS sz LEFT JOIN $wpdb->postmeta AS pz ON (sz.ID=pz.post_id AND pz.meta_key='block_cost') ";

        $sql .= "LEFT JOIN $wpdb->postmeta AS pb ON (sz.post_parent=pb.post_id AND pb.meta_key='_wc_booking_base_cost') ";

        $sql .= "WHERE sz.ID = ".$size_id;

        return $wpdb->get_row($sql);

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

/**

 *  Devuelve la cantidad y la lista de cuidadores favoritos que posee el usuario.

 * */

if(!function_exists('kmimos_get_my_favorites')){

    function kmimos_get_my_favorites($user_id){

        global $wpdb;

        

        $sql = "SELECT ";

        $sql .= "IFNULL((SELECT IF(LENGTH(f.meta_value)>2,LENGTH(f.meta_value)-LENGTH(REPLACE(f.meta_value,',',''))+1,0) ";

        $sql .= "FROM $wpdb->usermeta AS f WHERE f.meta_key='user_favorites' AND f.user_id = $user_id),0) AS count, ";

        $sql .= "IFNULL((SELECT IF(LENGTH(u.meta_value)>2,SUBSTRING(REPLACE(SUBSTRING_INDEX(u.meta_value,']',1),'\"',''),2),'') ";

        $sql .= "FROM $wpdb->usermeta AS u WHERE u.meta_key='user_favorites' AND u.user_id = $user_id),'') AS list";



        return $wpdb->get_row($sql, ARRAY_A);

    }

}

/**

 *  Devuelve la lista de las categorías de servicios ofrecidas por los cuidadores.

 * */

if(!function_exists('kmimos_get_categories_of_services')){

    function kmimos_get_categories_of_services(){

        global $wpdb;

        

        $parent_cat = 2588;

        

        $sql = "SELECT tm.term_id AS ID, tm.name ";

        $sql .= "FROM $wpdb->term_taxonomy AS tx  ";

        $sql .= "LEFT JOIN $wpdb->terms AS tm ON tx.term_id=tm.term_id ";

        $sql .= "WHERE tx.parent = ".$parent_cat;

        

        return $wpdb->get_results($sql);

    }

}

/**

 *  Devuelve la lista de productos modelo de los servicios ofrecidas por los cuidadores.

 * */

if(!function_exists('kmimos_get_product_models_of_services')){

    function kmimos_get_product_models_of_services(){

        $args = array(

            'posts_per_page' => -1,

            'post_type' =>'product_model',

            'orderby' => 'title',

            'order' => 'ASC',

            'post_status' => 'publish'

        );

        return get_posts( $args );

    }

}

/**

 *  Devuelve la lista de tipos de mascotas.

 * */

if(!function_exists('kmimos_get_types_of_pets')){

    function kmimos_get_types_of_pets(){

        global $wpdb;

        

        $sql = "SELECT tm.term_id AS ID, tm.name ";

        $sql .= "FROM $wpdb->term_taxonomy AS tx  ";

        $sql .= "LEFT JOIN $wpdb->terms AS tm ON tx.term_id=tm.term_id ";

        $sql .= "WHERE tx.taxonomy = 'pets-types'";

        

        return $wpdb->get_results($sql);

    }

}

/**

 *  Devuelve la lista de tamaños de mascotas.

 * */

if(!function_exists('kmimos_get_sizes_of_pets')){

    function kmimos_get_sizes_of_pets(){
        $sizes =array(
            0=> array('ID'=>0,'name'=>'Pequeñas','desc'=>'Menos de 25.4cm'),
            1=> array('ID'=>1,'name'=>'Medianas','desc'=>'Más de 25.4cm y menos de 50.8cm'),
            2=> array('ID'=>2,'name'=>'Grandes','desc'=>'Más de 50.8cm y menos de 76.2cm'),
            3=> array('ID'=>3,'name'=>'Gigantes','desc'=>'Más de 76.2cm')
        );
        return $sizes;

    }

}

/**

 *  Devuelve la lista de status de postulaciones.

 * */

// if(!function_exists('kmimos_get_states_of_postulations')){

//     function kmimos_get_states_of_postulations(){

//         return array('Enviada', 'Aceptada', 'Rechazada', 'Cancelada');

//     }

// }

/**

 *  Devuelve la lista de géneros de mascotas.

 * */

if(!function_exists('kmimos_get_genders_of_pets')){

    function kmimos_get_genders_of_pets(){



        $genders =array(

            array('ID'=>1,'name'=>'Machos','singular'=>'Macho'),

            array('ID'=>2,'name'=>'Hembras','singular'=>'Hembra')

        );

        

        return $genders;

    }

}

/**
 *  Devuelve la valoración del cuidador.
 * */

if(!function_exists('kmimos_draw_rating')){
    function kmimos_draw_rating($rating, $votes){
        $html = '';
        if($votes =='' || $votes == 0 || $rating ==''){ 
            $html .= '<div id="rating">';
            for ($i=0; $i<5; $i++){ 
                $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/vacio.png">';
            }
            $html .= '</div>';
            $html .= '<div style="clear:both"><sup>Este cuidador no ha sido valorado</sup></div>';
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
                        case 3: // 75% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/75.png">';
                        break;
                        case 2: // 50% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/50.png">';
                        break;
                        case 3: // 25% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/25.png">';
                        break;
                        default: // 0% 
                            $html .= '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/0.png">';
                        break;
                    }
                }
            }
            $html .= '</div>';
        }
        return $html;
    }
}

/**

 *  Devuelve la valoración del cuidador.

 * */

if(!function_exists('kmimos_petsitter_rating_and_votes')){

    function kmimos_petsitter_rating_and_votes($post_id){

        /*$valoracion=array();

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

            update_post_meta($post_id, 'rating_petsitter', $rating);
            update_post_meta($post_id, 'votes_petsitter', $votes);

        } else {
            $rating = '';
            $votes = 0;

            update_post_meta($post_id, 'rating_petsitter', 0);
            update_post_meta($post_id, 'votes_petsitter',  0);
        }*/

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



//kmimos_get_options_for_select



/**
 *  Devuelve la información de la mascota seleccionada.
 * */

if(!function_exists('kmimos_get_pet_info')){
    function kmimos_get_pet_info($pet_id){
        global $wpdb;
        $sql = "SELECT 
        pt.ID AS pet_id, GROUP_CONCAT(ty.term_taxonomy_id SEPARATOR ',') AS type, br.meta_value AS breed, ";
        $sql .= "ph.meta_value as photo, nm.meta_value AS name, gr.meta_value AS gender, co.meta_value AS colors, bd.meta_value AS birthdate, ";
        $sql .= "sz.meta_value AS size, st.meta_value AS strerilized, ps.meta_value AS sociable, ";
        $sql .= "ah.meta_value AS aggresive_humans, ap.meta_value AS aggresive_pets, ob.meta_value AS observations, ";
        $sql .= "ow.meta_value AS owner_id ";
        $sql .= "FROM $wpdb->posts AS pt ";
        $sql .= "LEFT JOIN $wpdb->term_relationships AS ty ON pt.ID =ty.object_id ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS br ON (pt.ID =br.post_id AND br.meta_key='breed_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS ph ON (pt.ID =ph.post_id AND ph.meta_key='photo_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS nm ON (pt.ID =nm.post_id AND nm.meta_key='name_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS gr ON (pt.ID =gr.post_id AND gr.meta_key='gender_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS co ON (pt.ID =co.post_id AND co.meta_key='colors_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS bd ON (pt.ID =bd.post_id AND bd.meta_key='birthdate_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS sz ON (pt.ID =sz.post_id AND sz.meta_key='size_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS ob ON (pt.ID =ob.post_id AND ob.meta_key='about_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS ow ON (pt.ID =ow.post_id AND ow.meta_key='owner_pet') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS st ON (pt.ID =st.post_id AND st.meta_key='pet_sterilized') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS ps ON (pt.ID =ps.post_id AND ps.meta_key='pet_sociable') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS ah ON (pt.ID =ah.post_id AND ah.meta_key='aggressive_with_humans') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS ap ON (pt.ID =ap.post_id AND ap.meta_key='aggressive_with_pets') ";
        $sql .= "WHERE pt.post_type='pets' AND post_status='publish' AND pt.ID = ".$pet_id;
        return $wpdb->get_row($sql, ARRAY_A);
    }
}



/**

 *  Devuelve la información del cuidador asociado al usuario.

 * */

if(!function_exists('kmimos_get_petsitter_info')){

    function kmimos_get_petsitter_info($user_id){



        $values = get_user_meta($user_id);

        $meta_user=array();

        foreach($values as $key=>$value) $meta_user[$key]=$value[0];

        //

        // Primero verifica si existe algún cuidador asignado al usuario

        //

        $args = array(

            'posts_per_page' => 1,

            'post_type' =>'petsitters',

            'meta_key' => 'user_petsitter',

            'meta_value' => $user_id,

            'post_status' => 'publish'

        );

        $posts = get_posts( $args );

        if(count($posts)==0){

            // Si no existe ningún cuidador, lo crea con los valores de la postulación

            $petsitter_id = 0;

            $postulacion_id = $meta_user['petsitter_postulation'];

            $values = get_post_meta($postulacion_id);

            $meta_postulacion=array();

            foreach($values as $key=>$value) $meta_postulacion[$key]=$value[0];

            

            $metas = array(

                'user_petsitter' => $user_id,

                'code_petsitter' => kmimos_get_next_petsitter_code(),

                'firstname_petsitter' => $meta_postulacion['first_name'],

                'lastname_petsitter' => $meta_postulacion['last_name'],

                'dni_petsitter' => $meta_postulacion['dni'],

                'birthdate_petsitter' => $meta_postulacion['birthdate'],

                'gender_petsitter' => $meta_postulacion['gender'],

                'starting_petsitter' => $meta_postulacion['startdate'],

                'email_petsitter' => $meta_postulacion['email'],

                'phone_petsitter' => $meta_postulacion['phone'],

                'mobile_petsitter' => $meta_postulacion['mobile'],

                'mobile_petsitter' => $meta_postulacion['description'],

            );

            $petsitter_name = $meta_postulacion['first_name'].' '.$meta_postulacion['last_name'];

            $args = array(

                'ID'            => $petsitter_id,

                'post_content'  => $meta_postulacion['description'],

                'post_title'    => $petsitter_name,

                'post_status'   => 'publish',

                'post_author'   => $user_id,

                'post_type'     => 'petsitters',

                'meta_input'    => $metas

            );

            $petsitter_id = wp_insert_post( $args );

        }

        else {

            $petsitter_id = $posts[0]->ID;

        }

        $values = get_post($petsitter_id);

        $meta_petsitter=array('petsitter_id'=>$petsitter_id,'description'=>$values->post_content);

        $values = get_post_meta($petsitter_id);

        foreach($values as $key=>$value) $meta_petsitter[$key]=$value[0];

        return $meta_petsitter;

    }

}

/**

 *  Devuelve la lista de asuntos pendientes del usuario.

 * */

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


if(!function_exists('kmimos_upload_and_process_image')){

    function kmimos_upload_and_process_image($ancho,$alto,$photo_name,$args){

        $ext = explode('/',$args['type']);
        $path = '/wp-content/plugins/kmimos/assets/images/';
        $raiz = getcwd();

        $img_original = $path.'cuidadores/'.$photo_name.'.'.$ext[1];
        $img_reducida = $path.'petsitters/'.$photo_name.'.jpg';


        if( move_uploaded_file($args['tmp_name'], $raiz.$img_original) ){


            $image = new imagick($raiz.$img_original);
            $image->cropThumbnailImage(800, 600);
            $image->writeImage ($raiz.$img_reducida);

            $url = get_home_url().$img_reducida;
            $tmp = download_url( $url );    

            if ( !function_exists('media_handle_upload') ) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            } 
            $file_array = array(
                'name'      =>  $photo_name,
                'type'      =>  "image/jpg",
                'tmp_name'  =>  $url,
                'error'     =>  0,
                'size'      =>  filesize($raiz.$img_reducida)
            );   

            if ( is_wp_error( $tmp ) ) {
                @unlink($file_array['tmp_name']);
                $file_array['tmp_name'] = '';
            } else {

                $id_img = media_handle_sideload( $file_array, $args['ID'] );

                @unlink($raiz.$img_original);
                @unlink($raiz.$img_reducida);

                echo "<pre>";
                    print_r($file_array);
                    print_r($id_img);
                echo "</pre>"; 

                if ( is_wp_error( $id_img ) ) {
                    $id_img = "";
                }  

            }
            
            return $id_img;

        }else{
            return "Error, moviendo la imagen a: ".$raiz;
        }
    }

}



        /******************************************* JAUREGUI **********************************************************************
        *Se comento esta seccion para poder generar la carga de las imagenes en las fotos cargadas para la portada 
        ****************************************************************************************************************************/
        // $radio = 15;
        // $sigma = 20;
        //        // $imagen = new Imagick($name);   // Crea la imagen para ser procesada /*Jauregui*/
        //         $w=$imagen->getImageWidth();    // Obtiene el ancho original de la imagen
        //         $h=$imagen->getImageHeight();   // Obtiene el alto original de la imagen
        //         $fondo = $imagen->clone();      // Crea una copia como lienzo 
        //         // Compara el aspecto original de la imagen con el aspecto deseado
        //         $aspecto_final =$ancho/$alto;
        //         $aspecto_original = $w/$h;
        // //die("Aspactos: final=".$aspecto_final.", original=".$aspecto_original);
        //         if( $aspecto_final > $aspecto_original ){ 
        //             // el aspecto deseado es mayor que el de la imagen, por tanto expande la imagen hacia los lados
        //             $fondo->adaptiveResizeImage($ancho,$ancho/$aspecto_original);
        // //die('Origen:w='.$ancho.', h='.$alto.'<br>Fondo: w='.$fondo->getImageWidth().', h='.$fondo->getImageHeight().'<br>Imagen:w='.$imagen->getImageWidth().', h='.$imagen->getImageHeight());
        //             $fondo->cropImage($ancho,$alto,0,($ancho/$aspecto_original-$alto)/2);
        //             $fondo->gaussianBlurImage($radio,$sigma); // Distorsiona la imagen para crear el fondo
        //             $imagen->adaptiveResizeImage($alto*$aspecto_original,$alto);
        // //die('Origen:w='.$ancho.', h='.$alto.'<br>Fondo: w='.$fondo->getImageWidth().', h='.$fondo->getImageHeight().'<br>Imagen:w='.$imagen->getImageWidth().', h='.$imagen->getImageHeight());
        //             $fondo->compositeImage( $imagen, Imagick::COMPOSITE_OVER, ($ancho-$alto*$aspecto_original)/2, 0 );
        //         }
        //         else if( $aspecto_final < $aspecto_original ){ 
        // //echo("Aspactos: final=".$aspecto_final.", original=".$aspecto_original);
        //         // el aspecto deseado es menor que el de la imagen, por tanto expande la imagen hacia arriba y abajo
        //         if( $imagen->getImageHeight()/$alto < $imagen->getImageWidth()/$ancho ) {
        //             $fondo->adaptiveResizeImage($alto*$aspecto_original,$alto);
        //             $fondo->cropImage($ancho,$alto,($alto*$aspecto_original-$ancho)/2,0);
        //         }
        //         else {
        //             $fondo->adaptiveResizeImage($alto*$aspecto_original,$alto);
        //             $fondo->cropImage($ancho,$alto,($alto*$aspecto_original-$alto)/2,0);
        //         }
        // //die('<br>Origen:w='.$ancho.', h='.$alto.'<br>Fondo: w='.$fondo->getImageWidth().', h='.$fondo->getImageHeight().'<br>Imagen:w='.$imagen->getImageWidth().', h='.$imagen->getImageHeight());
        //             $fondo->gaussianBlurImage($radio,$sigma); // Distorsiona la imagen para crear el fondo
        //             $imagen->adaptiveResizeImage($ancho,$ancho/$aspecto_original);
        //             $fondo->compositeImage( $imagen, Imagick::COMPOSITE_OVER, 0, ($alto-$ancho/$aspecto_original)/2 );
        //         }
        //         else $fondo->adaptiveResizeImage($ancho,$alto);
        //         // verifica si existe la imágen de salida
        //         $filename = $root.'petsitters/'.$photo_name.'.jpg';
        //         $fondo->writeImage ($filename);
        /******************************************* /JAUREGUI ***********************************************************************/

        /*
        $url = 'https://kmimos.com.mx/wp-content/plugins/kmimos/assets/images/petsitters/'.$name.'.jpg';

        $tmp = download_url( $url );                           

        if ( !function_exists('media_handle_upload') ) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        } 

        // $file_array = array('name'=>$photo_name,'type'=>$args['type'],'tmp_name'=>$tmp,'error'=>0,'size'=>$args['size']);
        $file_array = array('name'=>$photo_name,'type'=>$args['type'],'tmp_name'=>$name,'error'=>0,'size'=>$args['size']);      

        $newuploadphoto = media_handle_sideload( $file_array, $petsitter_id );

        if ( is_wp_error( $newuploadphoto ) ) {
            @unlink( $file_array['tmp_name'] );
            return $newuploadphoto;
        }
        return $newuploadphoto;
        */

/**

 *  Devuelve la lista de asuntos pendientes del usuario.

 * */

if(!function_exists('kmimos_get_next_petsitter_code')){

    function kmimos_get_next_petsitter_code(){

        global $wpdb;

        

        $sql = "SELECT IFNULL( (SELECT pm.meta_value+1 FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS pm ON (p.ID =pm.post_id AND pm.meta_key='code_petsitter') WHERE p.post_status = 'publish' ORDER BY CAST(pm.meta_value AS SIGNED) DESC LIMIT 1),1) as next_code";

        

        return $wpdb->get_var($sql);

    }

}

/**

 *  Devuelve la lista de asuntos pendientes del cuidador.

 * */

if(!function_exists('kmimos_get_my_pending_issues')){

    function kmimos_get_my_pending_issues($user_id){
        global $wpdb;
        $sql  = "SELECT COUNT(*) AS count, GROUP_CONCAT(p.ID SEPARATOR ',') AS list ";
        $sql .= "FROM $wpdb->posts AS p  ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS pm ON (p.ID=pm.post_id AND pm.meta_key='requested_petsitter') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS rs ON (p.ID=rs.post_id AND rs.meta_key='request_status') ";
        $sql .= "LEFT JOIN $wpdb->postmeta AS pn ON (pm.meta_value=pn.meta_value AND pn.meta_key='user_petsitter') ";
        $sql .= "WHERE p.post_type = 'request' AND p.post_status = 'publish' ";
        $sql .= "AND rs.meta_value = '1' AND pn.meta_value = ".$user_id;
        return $wpdb->get_row($sql, ARRAY_A);
    }
}

/**
 *  Devuelve la dirección georeferenciada del usuario.
 * */

if(!function_exists('kmimos_get_geo_address')){

    function kmimos_get_geo_address($user_id, $map_widht, $map_heigth){
        global $wpdb;
        return array('count'=>0,'list'=>'');
    }

}

/**

 *  Devuelve la cantidad de fotos que posee el usuario como cuidador.

 * */

if(!function_exists('kmimos_get_my_pictures')){

    function kmimos_get_my_pictures($user_id){

        global $wpdb;

        

        $sql = "SELECT IFNULL(";

        $sql .= "(SELECT IF(LENGTH(f.meta_value)>0,LENGTH(f.meta_value)-LENGTH(REPLACE(f.meta_value,',',''))+1,0) ";

        $sql .= "FROM $wpdb->postmeta AS u ";

        $sql .= "LEFT JOIN $wpdb->postmeta AS f ON (f.post_id =u.post_id AND f.meta_key='carousel_petsitter') ";

        $sql .= "WHERE u.meta_key='user_petsitter' AND u.meta_value = us.meta_value),0) AS count, p.meta_value AS list ";

        $sql .= "FROM $wpdb->postmeta AS us ";

        $sql .= "LEFT JOIN $wpdb->postmeta AS p ON (p.post_id =us.post_id AND p.meta_key='carousel_petsitter') ";

        $sql .= "WHERE us.meta_key='user_petsitter' AND us.meta_value = ".$user_id;



        return $wpdb->get_row($sql, ARRAY_A);

    }

}

/**

 *  Devuelve las reservas a los servicios que ofrece el cuidador.

 * */

if(!function_exists('kmimos_get_my_bookings')){

    function kmimos_get_my_bookings($user_id){

        global $wpdb;

        

        $services = kmimos_get_my_services($user_id);

//return $services;

        // $sql = "SELECT COUNT(*) AS count, GROUP_CONCAT(bk.ID SEPARATOR ',') AS list ";

        // $sql .= "FROM $wpdb->posts AS bk ";

        // $sql .= "LEFT JOIN $wpdb->postmeta AS sv ON (bk.ID =sv.post_id AND sv.meta_key='_booking_product_id') ";

        // $sql .= "WHERE bk.post_type='wc_booking' AND bk.post_status IN ('confirmed', 'unpaid', 'paid') ";

        // $sql .= "AND sv.meta_value IN (".$services['list'].") ";

        $sql = "
            SELECT 
                COUNT(*) AS count
            FROM 
                $wpdb->posts AS posts
            LEFT JOIN $wpdb->postmeta AS metas    ON (metas.meta_key='_booking_product_id' AND metas.meta_value=posts.ID)
            LEFT JOIN $wpdb->posts    AS reservas ON (reservas.ID=metas.post_id)
            LEFT JOIN $wpdb->posts    AS orden ON (orden.ID=reservas.post_parent)
            WHERE 
                posts.post_author       = {$user_id} AND 
                posts.post_type         = 'product' AND
                reservas.post_status    != 'was-in-cart' AND
                orden.post_status       != 'wc-pending'";

//return $sql;

        return $wpdb->get_row($sql, ARRAY_A);

    }

}

/**

 *  Devuelve la cantidad de mascotas que posee el usuario.

 * */

if(!function_exists('kmimos_get_my_sales_count')){

    function kmimos_get_my_sales_count($user_id){

        global $wpdb;

        $my_sales =0;

/*        

        $sql = "SELECT COUNT(*) AS pets ";

        $sql .= "FROM $wpdb->posts AS p  ";

        $sql .= "LEFT JOIN $wpdb->postmeta AS pm ON (p.ID=pm.post_id AND pm.meta_key='owner_pet') ";

        $sql .= "WHERE p.post_type = 'pets' AND p.post_status = 'publish' ";

        $sql .= "AND pm.meta_value = ".$user_id;

        

        $my_sales = $wpdb->get_var($sql);*/

        return $my_sales;

    }

}

/**

 *  Devuelve un arreglo con los valores máximos y mínimos de cuidadores cuidadores filtrados según los criterios de búsqueda.

 * */

if(!function_exists('kmimos_rangos_valores')){

    function kmimos_rangos_valores($params){

        global $wpdb;

        

        // busca la lista de los IDs de los cuidadores que cumplen con los criterios de la búsqueda

        $cuidadores = kmimos_filtra_cuidadores($params);

        

        // busca el servicio principal para el cálculo de los rango de precios

        $servppal = kmimos_servicio_principal($params['servicio_cuidador']);

        

        $pais = substr($params['ubicacion_cuidador'][0],0,2);

        

        $sql = "CALL rangeOfPetsitters('".$cuidadores->ids."', '".$servppal."', '".$pais."', @hospedajebase, @hospedajemin, @hospedajemax, @hospedajetop, @guarderiabase, @guarderiamin, @guarderiamax, @guarderiatop, @paseosbase, @paseosmin, @paseosmax, @paseostop, @adiestramientobase, @adiestramientomin, @adiestramientomax, @adiestramientotop, @peluqueriabase, @peluqueriamin, @peluqueriamax, @peluqueriatop, @banobase, @banomin, @banomax, @banotop, @experbase, @expermin, @expermax, @expertop, @valorbase, @valormin, @valormax, @valortop)";

        $result = $wpdb->get_results($sql);

        return $result[0];

    }

}

/**

 *  Filtra las mascotas en la lista cuando un vendedor inicia sesión en el backpanel

 **/

if(!function_exists('kmimos_filter_bookings_when_petsitters_login')){

    function kmimos_filter_bookings_when_petsitters_login($query) {

        global $wpdb;



        $user = wp_get_current_user();

        $is_vendor=in_array('vendor', $user->roles);

        // retorna si no hay ningún filtro seleccionado o no es un cuidador

        if (!is_admin() || $_GET['post_type']!='wc_booking' || $is_vendor!=1) return;

            

//echo ">>>>>>>>>>>>>>>>>>>>>>  cuidador=".$user->ID;

//print_r($user);

        

//        $bookings = kmimos_get_my_bookings($user->ID);

//print_r($bookings);

        // Busca los ID de las reservas del cuidador que inició sesión

        $str = "SELECT GROUP_CONCAT(bk.ID separator ',') as reservas ";

        $str .= "FROM wp_posts AS bk ";

        $str .= "LEFT JOIN wp_postmeta AS it ON bk.ID=it.post_id AND it.meta_key='_booking_product_id' ";

        $str .= "LEFT JOIN wp_posts AS sv ON sv.ID=it.meta_value ";

        $str .= "LEFT JOIN  wp_postmeta AS ps ON ps.post_id=sv.post_parent AND ps.meta_key='user_petsitter'";

        $str .= "WHERE  ps.meta_value= ".$user->ID;

        $reservas = $wpdb->get_var($str);

//echo ">>>>>>>>>>>>>>>>>>>>>>  reservas: ".$reservas;

        $query->query_vars['post__in'] = explode(",",$reservas);

    }

}







include_once('kmimos-email.php');



class WP_Query_Geo extends WP_Query {

  var $lat;

  var $lng;

  var $distance;

 

  function __construct($args=array()) {

    if(!empty($args['lat'])) {

      $this->lat = $args['lat'];

      $this->lng = $args['lng'];

      $this->distance = $args['distance'];

      $this->lat_meta_name = $args[ 'lat_meta_name' ];

      $this->lng_meta_name = $args[ 'lng_meta_name' ];

      $this->orderby = $args[ 'orderby' ];

      $this->order = $args[ 'order' ];

//      $this->unit_of_measure = 3959;  // Units in miles

      $this->unit_of_measure = 6371;    // Units in Kilometers



      add_filter('posts_fields', array($this, 'posts_fields'));

      add_filter('posts_join', array($this, 'posts_join'));

      add_filter('posts_where', array($this, 'posts_where'));

      add_filter('posts_orderby', array($this, 'posts_orderby'));

      add_filter('posts_groupby', array($this, 'posts_groupby'));

    }

 

    parent::query($args);

 

    remove_filter('posts_fields', array($this, 'posts_fields'));

    remove_filter('posts_join', array($this, 'posts_join'));

    remove_filter('posts_where', array($this, 'posts_where'));

    remove_filter('posts_orderby', array($this, 'posts_orderby'));

  }

 

  function posts_fields($fields) {

    global $wpdb;

    $fields = $wpdb->prepare(" $wpdb->posts.*, pm1.meta_value AS latitud, pm2.meta_value AS longitud,

    ACOS(SIN(RADIANS(%f))*SIN(RADIANS(pm1.meta_value))+COS(RADIANS(%f))*COS(RADIANS(pm1.meta_value))*COS(RADIANS(pm2.meta_value)-RADIANS(%f))) * %d AS distance ", $this->lat, $this->lat, $this->lng, $this->unit_of_measure);

    return $fields;

  }

 

  function posts_join($join) {

    global $wpdb;

    $join .= " INNER JOIN $wpdb->postmeta pm1 ON ($wpdb->posts.id = pm1.post_id AND pm1.meta_key = '".$this->lat_meta_name."')";

    $join .= " INNER JOIN $wpdb->postmeta pm2 ON ($wpdb->posts.id = pm2.post_id AND pm2.meta_key = '".$this->lng_meta_name."')";

    return $join;

  }



  function posts_where($where) {

    global $wpdb;

    if($this->distance > 0) $where .= $wpdb->prepare(" HAVING distance < %d ", $this->distance);

    return $where;

  }



  function posts_orderby($orderby) {

    global $wpdb;

    if($this->orderby == 'distance') $orderby = " distance ".$this->order.", " . $orderby;

    elseif($this->orderby == 'rating_petsitter') $orderby = " rating_petsitter ".$this->order.", votes_petsitter ".$this->order.", distance ";

    else $orderby = $orderby.", distance ".$this->order;

    return $orderby;

  }



  function posts_groupby($groupby) {

    global $wpdb;

    $groupby = "";

    return $groupby;

  }

}



class WP_Query_Kmimos extends WP_Query {

/*

*   Variables públicas de la clase

*/

  var $lat;                         // Latitud donde se encuentra el usuario

  var $lng;                         // Longitud donde se encuentra el usuario

  var $distance;                    // Distancia máxima del cuidador al usuario

  var $units;                       // Unidad de medida en que se expresa las distancias

  var $factor;                      // Factor de conversión de la unidad de medida con respecto al Km

 

  function __construct($args=array()) {

    if(!empty($args['lat'])) {

    // Inicializa las variables públicas con los valores pasados como parámetros

      $this->lat = $args['lat'];

      $this->lng = $args['lng'];

      $this->distance = $args['distance'];

      if($args['units']!='')$this->units=$args['units'];    // Unidad de medida definida por el usuario

      else $this->units = 'Km';                             // Unidad de medida = Kilómetro (por defecto)

      if($args['factor']!='')$this->factor=$args['factor']; // Factor de conversión definid0 por el usuario

      else $this->factor = 1;                                // Factor de conversión = 1 (por defecto)

    // Inicializa las variables privadas con los valores pasados como parámetros

      $this->lat_meta_name = $args[ 'lat_meta_name' ];      // Meta utilizado para indicar la latitud del cuidador

      $this->lng_meta_name = $args[ 'lng_meta_name' ];      // Meta utilizado para indicar la lonitud del cuidador

      $this->orderby = $args[ 'orderby' ];

      $this->order = $args[ 'order' ];

      $this->meta_key = $args[ 'meta_key' ];

//      $this->unit_of_measure = 3959;  // Units in miles => Factor=1.60924475877469 Km/mi

      if($this->factor!=0)

        $this->unit_of_measure = 6371 / $this->factor;        // Unidades en Kilometros / Factor de conversión

    // Agrega los filtros que permitirán modificar los parámetros preestablecidos de la cadena de búsqueda

      add_filter('posts_fields', array($this, 'posts_fields'));

      add_filter('posts_join', array($this, 'posts_join'));

      add_filter('posts_where', array($this, 'posts_where'));

      add_filter('posts_orderby', array($this, 'posts_orderby'));

      add_filter('posts_groupby', array($this, 'posts_groupby'));

    }

 

    parent::query($args);

 

    remove_filter('posts_fields', array($this, 'posts_fields'));

    remove_filter('posts_join', array($this, 'posts_join'));

    remove_filter('posts_where', array($this, 'posts_where'));

    remove_filter('posts_orderby', array($this, 'posts_orderby'));

  }

 

  function posts_fields($fields) {

    global $wpdb;

    $fields = $wpdb->prepare(" $wpdb->posts.*, ps.latitude_petsitter AS latitud, ps.longitude_petsitter AS longitud,

    geodistance(%f,%f,ps.latitude_petsitter,ps.longitude_petsitter,%d) AS distance, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ps.starting_petsitter)), '%s') AS experience ", $this->lat, $this->lng, $this->unit_of_measure,'%y');

    return $fields;

  }

 

  function posts_join($join) {

    global $wpdb;

    $join .= " INNER JOIN wp_petsitters AS ps ON ($wpdb->posts.id = ps.ID)";

    return $join;

  }



  function posts_where($where) {

    global $wpdb;

    if($this->distance > 0) $where .= $wpdb->prepare(" HAVING distance < %d ", $this->distance);

    return $where;

  }



  function posts_orderby($orderby) {

    global $wpdb;

    switch($this->orderby){

      case 'distance':

        $orderby = " distance ".$this->order.", " . $orderby;

        break;

      case 'meta_value_num':

        if($this->meta_key=='rating_petsitter')  $orderby = " wp_postmeta.meta_value ".$this->order.", distance ASC, " . $orderby;

        break;

      case 'experience':

        $orderby = " experience ".$this->order.", distance ".$this->order;

        break;

/*      case 'rating_petsitter':

        $orderby = " rating_petsitter ".$this->order.", votes_petsitter ".$this->order;

        break;*/

      default:

        $orderby = $orderby.", distance ".$this->order;

        break;

    }

    return $orderby;

  }



  function posts_groupby($groupby) {

    global $wpdb;

    $groupby = "";

    return $groupby;

  }

}

?>