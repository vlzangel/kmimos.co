<?php

add_action('init', 'kmimos_register_request');

add_action('add_meta_boxes', 'kmimos_box_details_of_request');

add_action('save_post', 'kmimos_save_details_of_request');

/*add_filter('manage_request_posts_columns', 'kmimos_add_new_request_columns');

add_action('manage_request_posts_custom_column', 'kmimos_manage_request_columns', 10, 2);

/**

 *	Registra el tipo de post en Wordpress para contener a los requerimientos

 * */

if(!function_exists('kmimos_register_request')){

    function kmimos_register_request() {

    	$labels = array(

            'name' => _x('Solicitudes', 'post type general name'),

            'singular_name' => _x('Solicitud', 'post type singular name'),

            'add_new' => _x('Agregar nueva', 'Solicitud'),

            'add_new_item' => __('Agregar nueva solicitud'),

            'edit_item' => __('Editar Solicitud'),

            'new_item' => __('Nueva solicitud'),

//				'all_items' => __('Todos los requerimientos'),

            'view_item' => __('Ver solicitud'),

            'search_items' => __('Buscar solicitudes'),

            'not_found' => __('Solicitud no encontrada'),

            'not_found_in_trash' => __('No se ha encontrado nada en la papelera'),

			'parent_item_colon' => '',

			'menu_name' => __('Solicitudes'),

        );

        $args = array(

            'labels' => $labels,

            'public' => true,

            'hierarchical' => false,

            'show_in_menu' => 'kmimos',

            'menu_position' => 4,

            'has_archive' => true,

            'query_var' => true,

            'supports' => array('title','editor','author'),

            'rewrite' => array('slug' => 'request'),

        );

        register_post_type( 'request', $args );

    }

}

/**

 *  Agrega los campos adicionales a los requerimientos

 * */

if(!function_exists('kmimos_box_details_of_request')){

    function kmimos_box_details_of_request() {

//        $values = kmimos_details_of_request_load_fields();

        //$values = kmimos_get_fields_values(array());

        add_meta_box('details_of_request','Detalles de la Solicitud', 'kmimos_details_of_request', 'request', 'normal', 'high', $values);

    }

}

/**

 *  Carga los valores de los campos adicionales del requerimiento seleccionado

 * */

/*

if(!function_exists('kmimos_details_of_request_load_fields')){

    function kmimos_details_of_request_load_fields() {

        $values = get_post_custom($post->ID);

        print_r($values);

        $fields = json_decode($values['details_of_request'][0],true);

        return $fields;

    }

}

/**

 *  Crea el formulario con los campos adicionales del requerimiento seleccionado

 * */

if(!function_exists('kmimos_details_of_request')){

    function kmimos_details_of_request($post, $params) {

        $values=$params['args'];

        $status= array('1'=>'Esperando Respuesta del Cuidador','2'=>'Cuidador Aceptó la Solicitud','3'=>'Cuidador NO Aceptó la Solicitud','4'=>'Tiempo Expirado',);

        $types= array('1'=>'Solicitud Conocer Cuidador','2'=>'Solicitud Confirmar Reserva',);

        $next= array(6=>'6 Horas hábiles',12=>'12 Horas hábiles',);

        $requester_id=$values['requester_user'];

        $requester_name=get_user_meta($requester_id,'first_name',true).' '.get_user_meta($requester_id,'last_name',true);

        $petsitter_id=$values['requested_petsitter'];

        $petsitter_name=get_post_meta($petsitter_id,'firstname_petsitter',true).' '.get_post_meta($petsitter_id,'lastname_petsitter',true);

        ?>

        <table class="form-table">

            <tr class="valign: top">

                <th scope="row">

                    <label for="request_status">Estatus del Requerimiento</label>

                </th>

                <td>

                    <select id="request_status" name="request_status">

                        <option value="">Favor seleccione</option><?php

foreach($status as $key=>$value){ ?>

                        <option value="<?php echo $key; ?>"<?php if($key==$values['request_status']) echo ' selected'; ?>><?php echo $value; ?></option><?php

} ?>

                    </select>

                </td>

            </tr>

            <tr valign="top">

                <th scope="row">

                    <label for="request_type">Tipo de Requerimiento</label>

                </th>

                <td>

                    <select id="request_type" name="request_type">

                        <option value="">Favor seleccione</option><?php

foreach($types as $key=>$value){ ?>

                        <option value="<?php echo $key; ?>"<?php if($key==$values['request_type']) echo ' selected'; ?>><?php echo $value; ?></option><?php

} ?>

                    </select>

                </td>

            </tr>

            <tr valign="top">

                <th scope="row">

                    <label for="requester_user">Usuario Solicitante</label>

                </th>

                <td>

                    <input type="hidden" name="requester_user" value="<?php echo $requester_id;?>">

                    <input type="text" value="<?php echo $requester_name;?>" style="width: 280px;" readonly>

                </td>

            </tr>

            <tr valign="top">

                <th scope="row">

                    <label for="requested_petsitter">Cuidador Solicitado</label>

                </th>

                <td>

                    <input type="hidden" name="requested_petsitter" value="<?php echo $petsitter_id;?>">

                    <input type="text" value="<?php echo $petsitter_name;?>" style="width: 280px;" readonly>

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="request_time">Fecha y Hora de la Solicitud</label>

                </th>

                <td>

                    <input type="date" id="request_date" name="request_date" value="<?php echo $values['request_date']; ?>" /><input type="time" id="request_time" name="request_time" value="<?php echo $values['request_time']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="request_next">Fecha y Hora Próxima Revisión</label>

                </th>

                <td>

                    <select id="request_next" name="request_next">

                        <option value=0>Favor seleccione</option><?php

foreach($next as $key=>$value){ ?>

                        <option value=<?php echo $key; ?><?php if($key==$values['request_next']) echo ' selected'; ?>><?php echo $value; ?></option><?php

} ?>

                    </select> -> <input type="text" id="next_time_formated" value="<?php echo date("d/m/Y h:ia",strtotime ($values['next_time'])); ?>" disabled >

                    <input type="hidden" id="next_time" name="next_time" value="<?php echo $values['next_time']; ?>">

                 </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="request_time">Fecha y Hora del Encuentro</label>

                </th>

                <td>

                    <input type="date" id="meeting_when" name="meeting_when" value="<?php echo $values['meeting_when']; ?>" /><input type="time" id="meeting_time" name="meeting_time" value="<?php echo $values['meeting_time']; ?>" />

                </td>

            </tr>

        </table>

<script>

jQuery.noConflict(); 

jQuery(document).ready(document).ready(function() {

    var hora_desde = 7;     // Se comienza a trabajar desde las 7:00am

    var hora_hasta = 21;    // Se trabaja hasta las 9:00pm

    jQuery("#request_date, #request_time, #request_next").on('change',function(){

        console.log('Date: '+jQuery("#request_date").val());

        var d = jQuery("#request_date").val().split('-');

        console.log('Fecha: '+d[2]+'/'+d[1]+'/'+d[0]);

        var h  = jQuery("#request_time").val().split(':');

        if (typeof h[1] !== "undefined") {

            console.log('Hora: '+h[0]+':'+h[1]);

            var lapso = jQuery("#request_next").val(); // Cantidad de horas

            console.log('Lapso: '+lapso);

            // Verifica que la hora más el lapso sea antes de las 9:00pm, sino le suma 10 horas

            var hora = parseInt(lapso)+parseInt(h[0]);

            if(hora>=hora_hasta) hora += 24 - hora_hasta + hora_desde;

            var f = new Date(d[0],d[1]-1,d[2],hora,h[1],0,0);

            var time = f.getFullYear()+'-'+('0'+(f.getMonth()+1)).slice(-2)+'-'+('0'+f.getDate()).slice(-2)+' '+('0'+f.getHours()).slice(-2)+':'+('0'+f.getMinutes()).slice(-2);

            if(f.getHours()<=12){

                hora = ('0'+f.getDate()).slice(-2)+'/'+('0'+(f.getMonth()+1)).slice(-2)+'/'+f.getFullYear()+' '+('0'+f.getHours()).slice(-2)+':'+('0'+f.getMinutes()).slice(-2);

            }

            else {

                hora = ('0'+f.getDate()).slice(-2)+'/'+('0'+(f.getMonth()+1)).slice(-2)+'/'+f.getFullYear()+' '+('0'+(f.getHours()-12)).slice(-2)+':'+('0'+f.getMinutes()).slice(-2);                

            }

            var ampm = (f.getHours()<12)? 'a.m.':'p.m.';

            console.log(hora+ampm);

            jQuery("#next_time").val(time);

            jQuery("#next_time_formated").val(hora+ampm);

        }

    });

});

</script>

        <?php

    }

}

/**

 *  Guarda los campos adicionales del requerimiento seleccionado

 * */

if(!function_exists('kmimos_save_details_of_request')){

    function kmimos_save_details_of_request($post_id) {

        /*

         * If this is an autosave, our form has not been submitted,

         * so we don't want to do anything.

         */

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  return;

 

        if ( 'request' != $_POST['post_type'] ) return;

 

        // Check the user's permissions.

        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

 

        update_post_meta($post_id, 'request_status', sanitize_text_field($_POST['request_status']));

        update_post_meta($post_id, 'requester_user', sanitize_text_field($_POST['requester_user']));

        update_post_meta($post_id, 'request_type', sanitize_text_field($_POST['request_type']));

        update_post_meta($post_id, 'requested_petsitter', sanitize_text_field($_POST['requested_petsitter']));

        update_post_meta($post_id, 'request_date', sanitize_text_field($_POST['request_date']));

        update_post_meta($post_id, 'request_time', sanitize_text_field($_POST['request_time']));

        update_post_meta($post_id, 'request_next', sanitize_text_field($_POST['request_next']));

        update_post_meta($post_id, 'next_time', sanitize_text_field($_POST['next_time']));

        update_post_meta($post_id, 'meeting_when', sanitize_text_field($_POST['meeting_when']));

        update_post_meta($post_id, 'meeting_time', sanitize_text_field($_POST['meeting_time']));

    }

}

/**

 *  Redefine las columnas en la tabla de la lista de requerimientos

 * */

if(!function_exists('kmimos_add_new_request_columns')){

    function kmimos_add_new_request_columns($columns) {     

        $new_columns['cb'] = '<input type="checkbox" />';

         

        $new_columns['thumbnail'] = __('Player Image');

        $new_columns['title'] = _x('Player Name', 'column name');

        $new_columns['positions'] = __('Positions','column categories');     

        $new_columns['date'] = _x('Date', 'column name');

     

        return $new_columns;

    }

}

/**

 *  Define el contenido a mostrar en las nuevas columna de la lista de requerimientos

 * */

if(!function_exists('kmimos_manage_request_columns')){

    function kmimos_manage_request_columns($column_name, $id) {

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