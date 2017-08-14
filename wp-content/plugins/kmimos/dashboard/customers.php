<?php

add_action('init', 'kmimos_register_customers');

add_action('add_meta_boxes', 'kmimos_box_details_of_customer');

add_action('save_post', 'kmimos_save_details_of_customers');

add_filter('manage_customers_posts_columns', 'kmimos_add_new_customers_columns');

add_action('manage_customers_posts_custom_column', 'kmimos_manage_customers_columns', 10, 2);



/**

 *	Registra el tipo de post en Wordpress para contener a los clientes

 * */

if(!function_exists('kmimos_register_customers')){

    function kmimos_register_customers() {

    	$labels = array(

            'name' => _x('Clientes', 'post type general name'),

            'singular_name' => _x('Cliente', 'post type singular name'),

            'add_new' => _x('Agregar nuevo', 'Cliente'),

            'add_new_item' => __('Agregar nuevo cliente'),

            'edit_item' => __('Editar cliente'),

            'new_item' => __('Nuevo cliente'),

//				'all_items' => __('Todos los clientes'),

            'view_item' => __('Ver cliente'),

            'search_items' => __('Buscar clientes'),

            'not_found' => __('Cliente no encontrado'),

            'not_found_in_trash' => __('Not found nothing in trash'),

			'parent_item_colon' => '',

			'menu_name' =>  __('Clientes'),

        );

        $args = array(

            'labels' => $labels,

            'public' => true,

            'hierarchical' => false,

            'show_in_menu' => 'kmimos',

            'menu_position' => 4,

            'has_archive' => true,

            'query_var' => true,

            'supports' => array('title','editor','author','comments','gallery','thumbnail'),

            'rewrite' => array('slug' => 'customers'),

        );

        register_post_type( 'customers', $args );

    }

}

/**

 *  Agrega los campos adicionales a los clientes

 * */

if(!function_exists('kmimos_box_details_of_customer')){

    function kmimos_box_details_of_customer() {

        $values = kmimos_get_fields_values(array());

        add_meta_box('details_of_customer','Detalles del Cliente','kmimos_details_of_customer','customers','normal','high',$values);

        add_meta_box('details_of_pets','Mascotas del Cliente','kmimos_details_of_pets','pets','normal','low',$values);

    }

}

/**

 *  Crea el formulario con los campos adicionales del cliente seleccionado

 * */

if(!function_exists('kmimos_details_of_customer')){

    function kmimos_details_of_customer($post, $params) {

        $genders = array('1'=>'Masculino','2'=>'Femenino',);

        $values=$params['args'];

        $user_customer = $values['user_customer'];

        $tamanos= array('Pequeñas','Medianas','Grandes','Gigantes');

        if($user_customer!=''){

            $user_info = get_userdata($user_customer);

            $firstname_customer = ($user_info->firstname != '')? $user_info->firstname: $values['firstname_customer'];

            $lastname_customer = ($user_info->lastname != '')? $user_info->lastname: $values['lastname_customer'];

            $email_customer = ($user_info->lastname != '')? $user_info->email: $values['email_customer'];

        }

        $users = get_users_by_role('customer');

        $args = array(

            'orderby'               => 'display_name',

            'order'                 => 'ASC',

            'show_option_none'      => __('Seleccione Usuario'),

            'multi'                 => false,

            'include'               => $users,

            'show'                  => 'display_name',

            'name'                  => 'user_customer',

            'selected'              => $user_customer

        );

        ?>

        <table class="form-table">

            <tr valign="top">

                <th scope="row">

                    <label for="user_customer">Usuario Asociado al Cliente</label>

                </th>

                <td>

                    <?php wp_dropdown_users($args); ?>

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="firstname_customer">Nombres del Cliente</label>

                </th>

                <td>

                    <input type="text" id="firstname_customer" name="firstname_customer" value="<?php echo firstname_customer; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="lastname_customer">Apellidos del Cliente</label>

                </th>

                <td>

                    <input type="text" id="lastname_customer" name="lastname_customer" value="<?php echo lastname_customer; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="dni_customer">Documento de Identidad (DNI)</label>

                </th>

                <td>

                    <input type="text" id="dni_customer" name="dni_customer" value="<?php echo $values['dni_customer']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="birthdate_customer">Fecha de Nacimiento</label>

                </th>

                <td>

                    <input type="date" id="birthdate_customer" name="birthdate_customer" value="<?php echo $values['birthdate_customer']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="gender_customer">Genero del Cliente</label>

                </th>

                <td>

                    <select id="gender_customer" name="gender_customer">

                        <option value="">Favor seleccione</option><?php

foreach($genders as $key=>$value){ ?>

                        <option value="<?php echo $key; ?>"<?php if($key==$values['gender_customer']) echo ' selected'; ?>><?php echo $value; ?></option><?php

} ?>

                    </select>

                </td>

            </tr>

            <tr valign="top">

                <td colspan="2">

                    <h3>Medios de Contacto del Cliente</h3><hr/>

                </td>

            </tr>

            <tr valign="top">

                <th scope="row">

                    <label for="email_customer">Email del Cliente</label>

                </th>

                <td>

                    <input type="email" id="email_customer" name="email_customer" value="<?php echo $email_customer; ?>" style="width: 240px;" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="phone_customer">Teléfono Fijo Cliente</label>

                </th>

                <td>

                    <input type="phone" id="phone_customer" name="phone_customer" value="<?php echo $values['phone_customer']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="mobile_customer">Teléfono Móvil Cliente</label>

                </th>

                <td>

                    <input type="phone" id="mobile_customer" name="mobile_customer" value="<?php echo $values['mobile_customer']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="address_customer">Dirección del Cliente</label>

                </th>

                <td>

                    <textarea type="text" id="address_customer" name="address_customer" style="width: 98%;"><?php echo $values['address_customer']; ?></textarea>

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="sector_customer">Colonia</label>

                </th>

                <td>

                    <input type="text" id="sector_customer" name="sector_customer" value="<?php echo $values['sector_customer']; ?>" style="width: 240px;" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="zip_customer">Zona Postal</label>

                </th>

                <td>

                    <input type="text" id="zip_customer" name="zip_customer" value="<?php echo $values['zip_customer']; ?>" style="width: 100px;" />

                </td>

            </tr>

            <tr valign="top">

                <th scope="row">

                    <label for="location_customer">País, Estado y Municipio</label>

                </th>

                <td>

                    <div id="location_customer_container">

                    <input type="hidden" class="location" data-countries='{"mx":"México"}' id="location_customer" name="location_customer" value="<?php echo $values['location_customer']; ?>" />

                    </div>

                </td>

            </tr>

        </table>

<script>

jQuery.noConflict(); 

jQuery(document).ready(document).ready(function() {

    jQuery("#location_customer").on('change',set_location('location_customer'));

});

function set_location(id){

    var ajaxurl = 'https://kmimos.com.mx/wp-content/plugins/kmimos/app-server.php';

    console.log('Actualizando ubicacion '+id);

    var container = jQuery('#'+id).parent();

    var countries = JSON.parse(jQuery('#'+id).attr('data-countries'));

    var location = jQuery('#'+id).val();

    console.log('Actualizando ubicacion '+id+', valor:'+location+', largo:'+location.length);

    if(location.length==0 || (location.length>=2 && jQuery('#'+id+'-country').length==0)){

        container.append('<select id="'+id+'-country" OnChange="javascript: document.getElementById(\'location_customer\').value=this.value; set_location(\'location_customer\');"></select>');

        var selector = jQuery('#'+id+'-country');

        selector.append('<option value="">Seleccione país</option>');

        var selected = location.substr(0,2);

        for(country in countries){

            selector.append('<option value="'+country+'"'+((selected==country)? ' selected="selected"':'')+'>'+countries[country]+'</option>');

        };

    }

    if(location.length==2 || (location.length>=5 && jQuery('#'+id+'-estate').length==0)){

        if(jQuery('#'+id+'-estate').length==0) container.append('<select id="'+id+'-estate" OnChange="javascript: document.getElementById(\'location_customer\').value=this.value; set_location(\'location_customer\');"></select>');

        var estates = jQuery('#'+id+'-estate');

        if(jQuery('#'+id+'-estate > option').length>0) estates.empty();

        estates.append('<option vale="">Seleccione estado</option>');

        jQuery.post(ajaxurl,{action: 'get-location', location: location.substr(0,2) },function(){

            estates.prop('disabled',true);

        }, "json")

        .success(function(data){

            jQuery.each(data, function(key,value){

                estates.append('<option value="'+key+'"'+((location.substr(0,5)==key)? ' selected="selected"':'')+'>'+value+'</option>');

            });

            estates.prop('disabled',false);

            console.log('Actualizado selector de estados');

            if(location.length>=9 && jQuery('#'+id+'-county').length==0){

                console.log('Se procede a crear selector de municipios del estado '+location.substr(0,5));

                if(jQuery('#'+id+'-county').length==0) container.append('<select id="'+id+'-county" OnChange="javascript: document.getElementById(\'location_customer\').value=this.value; set_location(\'location_customer\');"></select>');

                var counties = jQuery('#'+id+'-county');

                if(jQuery('#'+id+'-county > option').length>0) counties.empty();

                counties.append('<option vale="">Seleccione municipio</option>');

                jQuery.post(ajaxurl,{action: 'get-location', location: location.substr(0,5) },function(){

                    counties.prop('disabled',true);

                }, "json").success(function(data){

                    jQuery.each(data, function(key,value){

                        counties.append('<option value="'+key+'"'+((location.substr(0,9)==key)? ' selected="selected"':'')+'>'+value+'</option>');

                    });

                    counties.prop('disabled',false);

                });/**/

            }

        });

    }

//    if(location.length==5 || (location.length>=9 && jQuery('#'+id+'-county').length==0)){

    if(location.length==5){

        if(jQuery('#'+id+'-county').length==0) container.append('<select id="'+id+'-county" OnChange="javascript: document.getElementById(\'location_customer\').value=this.value; set_location(\'location_customer\');"></select>');

        var selector = jQuery('#'+id+'-county');

        if(jQuery('#'+id+'-county > option').length>0) selector.empty();

        selector.append('<option vale="">Seleccione municipio</option>');

        var selected = location.substr(0,9);

        jQuery.post(ajaxurl,{action: 'get-location', location: location.substr(0,5) },function(){

            selector.prop('disabled',true);

        }, "json").success(function(data){

            jQuery.each(data, function(key,value){

                selector.append('<option value="'+key+'"'+((selected==key)? ' selected="selected"':'')+'>'+value+'</option>');

            });

            selector.prop('disabled',false);

        });

    }

}

</script>

        <?php

    }

}

/**

 *  Crea el formulario con las mascotas del cliente seleccionado

 * */

if(!function_exists('kmimos_details_of_pets')){

    function kmimos_details_of_pets($post, $params) {

    }

}

/**

 *  Guarda los campos adicionales del cliente seleccionado

 * */

if(!function_exists('kmimos_save_details_of_customer')){

    function kmimos_save_details_of_customer($post_id) {

         $post_id=get_the_ID();

        /*

         * If this is an autosave, our form has not been submitted,

         * so we don't want to do anything.

         */

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  return;

 

        if ( 'customers' != $_POST['post_type'] ) return;

 

        // Check the user's permissions.

        if ( ! current_user_can( 'edit_post', $post_id ) ) return;



        $fields = array(

            'user_customer'=>'user',

            'firstname_customer'=>'text',

            'lastname_customer'=>'text',

            'dni_customer'=>'text',

            'birthdate_customer'=>'date',

            'gender_customer'=>'select',

            'email_customer'=>'email',

            'phone_customer'=>'phone',

            'mobile_customer'=>'phone',

            'address_customer'=>'textarea',

            'sector_customer'=>'text',

            'zip_customer'=>'text',

            'location_customer'=>'hidden',

         );

        kmimos_set_fields_values( $post_id, $fields);

    }

}

/**

 *  Redefine las columnas en la tabla de la lista de clientes

 * */

if(!function_exists('kmimos_add_new_customers_columns')){

    function kmimos_add_new_customers_columns($columns) {     

        $new_columns['cb'] = '<input type="checkbox" />';

         

        $new_columns['title'] = _x('Nombre del Cliente');

        $new_columns['pets'] = __('Mascotas');     

        $new_columns['estado'] = __('Estado');     

        $new_columns['municipio'] = __('Delegación');     

        $new_columns['sector'] = __('Colonia');     

        $new_columns['thumbnail'] = __('Foto');

     

        return $new_columns;

    }

}

/**

 *  Define el contenido a mostrar en las nuevas columna de la lista de clientes

 * */

if(!function_exists('kmimos_manage_customers_columns')){

    function kmimos_manage_customers_columns($column_name, $id) {

        global $wpdb;

        switch ($column_name) {

        case 'id':

            echo $id;

            break;



        case 'thumbnail':

            echo get_the_post_thumbnail( $id, array( 75, 75) );

            break;

     

        case 'pets':

            echo get_post_meta( $id , 'pets_customer' , true );

            break;



        case 'sector':

            echo get_post_meta( $id , 'sector_customer' , true );

            break;

     

        case 'estado':

        case 'municipio':

            // Get number of images in gallery

            $location = get_post_meta( $id , 'location_customer' , true );

            if($column_name=='estado'){

                $where = "t.slug LIKE '".substr($location,0,5)."-%' AND NOT (SUBSTRING(t.slug,7,3) REGEXP '[0-9]+')";

                $where .= " AND tt.taxonomy='pointfinderlocations'";

            }

            else {

                $where = "slug LIKE '".substr($location,0,9)."-%' AND (SUBSTRING(slug,7,3) REGEXP '[0-9]+')";

                $where .= " AND tt.taxonomy='pointfinderlocations'";

            }

            $sql = "SELECT t.name ";

            $sql .= "FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id=tt.term_id ";

            $sql .= "WHERE ".$where." ORDER BY t.name";



            $result = $wpdb->get_row($sql);

            echo $result->name;

//            echo $sql;

            break;



        default:

            echo $column_name;

            break;

        } // end switch

    }

}

/**

 *  Fin del archivo de clientes

 * */





?>