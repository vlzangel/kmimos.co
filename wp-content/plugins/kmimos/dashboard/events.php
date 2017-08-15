<?php
add_action('init', 'kmimos_register_events');
add_action('add_meta_boxes', 'kmimos_box_details_of_event');
add_action('save_post', 'kmimos_save_details_of_event');
add_filter('manage_players_posts_columns', 'kmimos_add_new_events_columns');
add_action('manage_players_posts_custom_column', 'kmimos_manage_events_columns', 10, 2);
/**
 *	Registra el tipo de post en Wordpress para contener a los eventos
 * */
if(!function_exists('kmimos_register_events')){
    function kmimos_register_events() {
    	$labels = array(
            'name' => _x('Eventos', 'post type general name'),
            'singular_name' => _x('Evento', 'post type singular name'),
            'add_new' => _x('Agregar nuevo', 'Evento'),
            'add_new_item' => __('Agregar nuevo evento'),
            'edit_item' => __('Editar evento'),
            'new_item' => __('Nuevo evento'),
//				'all_items' => __('Todos los eventos'),
            'view_item' => __('Ver evento'),
            'search_items' => __('Buscar eventos'),
            'not_found' => __('Evento no encontrado'),
            'not_found_in_trash' => __('No se ha encontrado nada en la papelera'),
			'parent_item_colon' => '',
			'menu_name' => __('Eventos'),
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'hierarchical' => false,
            'show_in_menu' => 'kmimos',
            'menu_position' => 4,
            'has_archive' => true,
            'query_var' => true,
            'supports' => array('title','author'),
            'rewrite' => array('slug' => 'events'),
        );
        register_post_type( 'events', $args );
    }
}
/**
 *  Agrega los campos adicionales a los eventos
 * */
if(!function_exists('kmimos_box_details_of_event')){
    function kmimos_box_details_of_event() {
        $values = kmimos_details_of_event_load_fields();
        add_meta_box('details_of_event','Detalles del Evento','kmimos_box_details_of_event','events','normal','high',$values);
    }
}
/**
 *  Carga los valores de los campos adicionales del evento seleccionado
 * */
if(!function_exists('kmimos_details_of_event_load_fields')){
    function kmimos_details_of_event_load_fields() {
        $values = get_post_custom($post->ID);
//        print_r($values);
        $fields = json_decode($values['details_of_event'][0],true);
        return $fields;
    }
}
//include_once('views/player-details.php');
/**
 *  Guarda los campos adicionales del evento seleccionado
 * */
if(!function_exists('kmimos_save_details_of_event')){
    function kmimos_save_details_of_event($post_id) {
         $post_id=get_the_ID();
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  return;
 
        if ( 'events' != $_POST['post_type'] ) return;
 
        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;
/* 
        update_post_meta($post_id, 'player_user', sanitize_text_field($_POST['player_user']));
        update_post_meta($post_id, 'player_borndate', sanitize_text_field($_POST['player_borndate']));
        update_post_meta($post_id, 'player_borncountry', sanitize_text_field($_POST['player_borncountry']));
        foreach($_POST['player_teams'] as $key=>$value){ $teams[$key]=$value; }
        update_post_meta($post_id, 'player_teams',json_encode($teams));*/
    }
}
/**
 *  Redefine las columnas en la tabla de la lista de eventos
 * */
if(!function_exists('kmimos_add_new_events_columns')){
    function kmimos_add_new_events_columns($columns) {     
        $new_columns['cb'] = '<input type="checkbox" />';
         
        $new_columns['thumbnail'] = __('Player Image');
        $new_columns['title'] = _x('Player Name', 'column name');
        $new_columns['positions'] = __('Positions','column categories');     
        $new_columns['date'] = _x('Date', 'column name');
     
        return $new_columns;
    }
}
/**
 *  Define el contenido a mostrar en las nuevas columna de la lista de eventos
 * */
if(!function_exists('kmimos_manage_events_columns')){
    function kmimos_manage_events_columns($column_name, $id) {
        global $wpdb;
        switch ($column_name) {
        case 'id':
            echo $id;
            break;

        case 'thumbnail':
            echo get_the_post_thumbnail( $id, array( 75, 75) );
            break;
     
        case 'positions':
            echo get_the_term_list( $id , 'positions' , '' , ',' , '' );
            break;
     
        case 'images':
            // Get number of images in gallery
            $num_images = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_parent = %d;", $id));
            echo $num_images; 
            break;

        default:
            echo $column_name;
            break;
        } // end switch
    }
}
/**
 *  Fin del archivo
 * */
?>