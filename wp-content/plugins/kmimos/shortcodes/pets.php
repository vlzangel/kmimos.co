<?php

add_action('init', 'kmimos_register_pets');

add_action('init', 'kmimos_pets_taxonomies', 0 );

add_action('add_meta_boxes', 'kmimos_box_details_of_pet');

add_action('restrict_manage_posts', 'kmimos_restrict_pets_by_owner');

//add_action('pre_get_posts', 'pvs_filter_doctor_by_parameters');

add_action('save_post', 'kmimos_save_details_of_pet');

add_filter('manage_pets_posts_columns', 'kmimos_add_new_pets_columns');

add_action('manage_pets_posts_custom_column', 'kmimos_manage_pets_columns', 10, 2);



/**

 *	Registra el tipo de post en Wordpress para contener a las mascotas

 * */

if(!function_exists('kmimos_register_pets')){

    function kmimos_register_pets() {

    	$labels = array(

            'name' => _x('Mascotas', 'post type general name'),

            'singular_name' => _x('Mascota', 'post type singular name'),

            'add_new' => _x('Agregar nueva', 'Cuidador'),

            'add_new_item' => __('Agregar nueva mascota'),

            'edit_item' => __('Editar mascota'),

            'new_item' => __('Nueva mascota'),

//				'all_items' => __('Todas las mascotas'),

            'view_item' => __('Ver mascota'),

            'search_items' => __('Buscar mascotas'),

            'not_found' => __('Mascota no encontrada'),

            'not_found_in_trash' => __('No se ha encontrado nada en la papelera'),

			'parent_item_colon' => '',

			'menu_name' =>  __('Mascotas'),

        );

        $args = array(

            'labels' => $labels,

            'public' => true,

            'hierarchical' => false,

            'show_in_menu' => 'kmimos',

            'menu_position' => 4,

            'has_archive' => true,

            'query_var' => true,

            'supports' => array('title','thumbnail'),

            'rewrite' => array('slug' => 'pets'),

        );

        register_post_type( 'pets', $args );

    }

}

/**

 *	Registra los tipos de mascotas   

 * */

if(!function_exists('kmimos_pets_taxonomies')){

    function kmimos_pets_taxonomies() {

    	$labels = array(

            'name' => _x( 'Tipos de Mascotas', 'taxonomy general name' ),

            'singular_name' => _x( 'Tipo de Mascota', 'taxonomy singular name' ),

            'search_items' =>  __( 'Buscar Tipo de Mascota' ),

            'all_items' => __( 'Todos los Tipos de Mascotas' ),

            'parent_item' => __( 'Padre del Tipo de Mascota' ),

            'parent_item_colon' => __( 'Padre del Tipo de Mascota:' ),

            'edit_item' => __( 'Editar Tipo de Mascota' ),

            'update_item' => __( 'Actualizar Tipo de Mascota' ),

            'add_new_item' => __( 'Agregar Nuevo Tipo de Mascota' ),

            'new_item_name' => __( 'Nombre del Nuevo Tipo de Mascota' ),

            'menu_name' => __( 'Tipos de Mascotas' ),

        );

        $args = array(

            'hierarchical' => true,

            'labels' => $labels,

            'show_ui'           => true,

            'show_admin_column' => true,

            'query_var'         => true,

            'rewrite'           => array( 'slug' => 'pets-types' ),

        );

        register_taxonomy('pets-types','pets',$args);

    }

}

/**

 *  Agrega los campos adicionales a las mascotas

 * */

if(!function_exists('kmimos_box_details_of_pet')){

    function kmimos_box_details_of_pet() {

        //$values = kmimos_get_fields_values(array());

        add_meta_box('details_of_pet','Detalles de la Mascota','kmimos_details_of_pet','pets','normal','high',$values);

    }

}

/**

 *  Crea el formulario con los campos adicionales de la mascota seleccionada

 * */

if(!function_exists('kmimos_details_of_pet')){

    function kmimos_details_of_pet($post, $params) {

        $values=$params['args'];

        $owner_pet = $values['owner_pet'];

        $tamanos= array('Pequeño (menos de 25.4cm)','Mediano (entre 25.5cm y 50.8cm)','Grande (entre 50.9cm y 76.2cm)','Gigante (más de 76.2cm)');

        $genders = array('1'=>'Masculino','2'=>'Femenino',);

        $users = get_users_by_role(array('customer','petsitter'));

        $args = array(

            'orderby'               => 'display_name',

            'order'                 => 'ASC',

            'show_option_none'      => __('Seleccione Usuario'),

            'multi'                 => false,

            'include'               => $users,

            'show'                  => 'display_name',

            'name'                  => 'owner_pet',

            'selected'              => $owner_pet

        );

        ?>

        <table class="form-table">

            <tr valign="top">

                <th scope="row">

                    <label for="owner_pet">Propietario de la Mascota</label>

                </th>

                <td>

                    <?php wp_dropdown_users($args); ?>

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="name_pet">Nombres de la Mascota</label>

                </th>

                <td>

                    <input type="text" id="name_pet" name="name_pet" value="<?php echo $values['name_pet']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="breed_pet">Raza de la Mascota</label>

                </th>

                <td>

                    <input type="text" id="breed_pet" name="breed_pet" value="<?php echo $values['breed_pet']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="colors_pet">Colores de la Mascota</label>

                </th>

                <td>

                    <input type="text" id="colors_pet" name="colors_pet" value="<?php echo $values['colors_pet']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="birthdate_pet">Fecha de Nacimiento</label>

                </th>

                <td>

                    <input type="date" id="birthdate_pet" name="birthdate_pet" value="<?php echo $values['birthdate_pet']; ?>" />

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="gender_pet">Género de la Mascota</label>

                </th>

                <td>

                    <select id="gender_pet" name="gender_pet">

                        <option value="">Favor seleccione</option><?php

foreach($genders as $key=>$value){ ?>

                        <option value="<?php echo $key; ?>"<?php if($key==$values['gender_pet']) echo ' selected'; ?>><?php echo $value; ?></option><?php

} ?>

                    </select>

                </td>

            </tr>

           <tr class="valign: top">

                <th scope="row">

                    <label for="size_pet">Tamaño de la Mascota</label>

                </th>

                <td>

                    <select id="size_pet" name="size_pet">

                        <option value="">Favor seleccione</option><?php

foreach($tamanos as $key=>$value){ ?>

                        <option value="<?php echo $key; ?>"<?php if($key==$values['size_pet']) echo ' selected'; ?>><?php echo $value; ?></option><?php

} ?>

                    </select>

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="features_petsitter">Mascota Esterilizada</label>

                </th>

                <td>

                    <div style="float: left; width: 480px">

                        <input type="checkbox" id="pet_sterilized" name="pet_sterilized" value="1" <?php if($values['pet_sterilized']=='1') echo ' checked="checked"'; ?> >

                        <label for="pet_sterilized"> Seleccione si su mascota ha sido esterilizada </label>

                    </div>

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="features_petsitter">Sociabilidad de la Mascota</label>

                </th>

                <td>

                    <div style="float: left; width: 120px">

                        <input type="radio" id="pet_sociable" name="pet_sociable" value="1" <?php if($values['pet_sociable']=='1') echo ' checked="checked"'; ?> >

                        <label for="pet_sociable"> Sociables </label>

                    </div>

                    <div style="float: left; width: 120px">

                        <input type="radio" id="pet_unsociable" name="pet_sociable" value="0" <?php if($values['pet_unsociable']=='1') echo ' checked="checked"'; ?> >

                        <label for="pet_unsociable"> No sociables </label>

                    </div>

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="features_petsitter">Conducta de la Mascota</label>

                </th>

                <td>

                    <div style="float: left; width: 240px">

                        <input type="checkbox" id="aggressive_with_pets" name="aggressive_with_pets" value="1" <?php if($values['aggressive_with_pets']=='1') echo ' checked="checked"'; ?> >

                        <label for="aggressive_with_pets"> Agresivos con otras mascotas </label>

                    </div>

                    <div style="float: left; width: 240px">

                        <input type="checkbox" id="aggressive_with_humans" name="aggressive_with_humans" value="1" <?php if($values['aggressive_with_humans']=='1') echo ' checked="checked"'; ?> >

                        <label for="aggressive_with_humans"> Agresivos con humanos </label>

                    </div>

                </td>

            </tr>

            <tr class="valign: top">

                <th scope="row">

                    <label for="about_pet">Observaciones sobre la Mascota</label>

                </th>

                <td>

                    <textarea type="text" id="about_pet" name="about_pet" style="width: 98%;"><?php echo $values['about_pet']; ?></textarea>

                </td>

            </tr>

        </table>

        <?php

    }

}

/**

 *	Crea la lista desplegable para filtrar los propietarios de las mascotas

 **/

if(!function_exists('kmimos_restrict_pets_by_owner')){

	function kmimos_restrict_pets_by_owner() {

        global $wpdb;

		$post_type = 'pets';

		$filter = 'filter_owner';

		if ($_GET['post_type'] == $post_type) {

			$selected = isset($_GET[$filter]) ? $_GET[$filter] : '';

            // Obtiene la lista de propietarios

            $owners = array();

            $sql = "SELECT DISTINCT pm.meta_value AS user_id, CONCAT(fn.meta_value, ' ', ln.meta_value) AS name  ";

            $sql .= "FROM wp_postmeta AS pm ";

            $sql .= "LEFT JOIN wp_usermeta AS fn ON (fn.user_id=pm.meta_value AND fn.meta_key = 'first_name') ";

            $sql .= "LEFT JOIN wp_usermeta AS ln ON (ln.user_id=pm.meta_value AND ln.meta_key = 'last_name') ";

            $sql .= "WHERE pm.meta_key = 'owner_pet' AND pm.meta_value <> '' ";

            $sql .= "ORDER BY name";

            $owners = $wpdb->get_results($sql);

//            $owners = get_terms( array( 'taxonomy' => 'specialties', 'hide_empty' => false,) );

//print_r($specialties);

			echo '<select id="'.$filter.'" name="'.$filter.'">';

			echo '<option value="0">'.__('Todos los propietarios').'</option>';

            if(count($owners)>0){

                foreach ($owners as $owner) {

?>

				<option value="<?php echo $owner->user_id;?>"<?php if($owner->user_id==$selected) echo ' selected="selected"';?>><?php echo $owner->name;?></option>

<?php

                }

            }

			echo '</select>';

		};

	}

}

/**

 *  Guarda los campos adicionales de la mascota seleccionada

 * */

if(!function_exists('kmimos_save_details_of_pet')){

    function kmimos_save_details_of_pet($post_id) {

         $post_id=get_the_ID();

        /*

         * If this is an autosave, our form has not been submitted,

         * so we don't want to do anything.

         */

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  return;

 

        if ( 'pets' != $_POST['post_type'] ) return;

 

        // Check the user's permissions.

        if ( ! current_user_can( 'edit_post', $post_id ) ) return;



        $fields = array(

            'owner_pet'=>'user',

            'name_pet'=>'text',

            'breed_pet'=>'text',

            'colors_pet'=>'text',

            'birthdate_pet'=>'date',

            'gender_pet'=>'select',

            'size_pet'=>'select',

            'pet_sterilized'=>'checkbox',

            'pet_sociable'=>'checkbox',

            'pet_unsociable'=>'checkbox',

            'aggressive_with_pets'=>'checkbox',

            'aggressive_with_humans'=>'checkbox',

            'about_pet'=>'textarea',

        );

        kmimos_set_fields_values( $post_id, $fields);

        $user_id = $_POST['owner_pet'];

        $pets = get_user_meta( $user_id, 'user_pets', true );

        if($pets!='') $pets = explode(',', $pets);

        else $pets = array();

        if(!in_array($post_id, $pets)) $pets[]=$post_id;

        update_user_meta( $user_id, 'user_pets', implode(',',$pets) );

    }

}

/**

 *  Redefine las columnas en la tabla de la lista de mascotas

 * */

if(!function_exists('kmimos_add_new_pets_columns')){

    function kmimos_add_new_pets_columns($columns) {     

        $new_columns['cb'] = '<input type="checkbox" />';

         

        $new_columns['title'] = _x('Nombre de la Mascota');

//        $new_columns['type'] = __('Tipo');     

        $new_columns['breed'] = __('Raza');     

        $new_columns['age'] = __('Edad');     

        $new_columns['owner'] = __('Nombre del propietario');     

        $new_columns['thumbnail'] = __('Foto');

     

        return $new_columns;

    }

}

/**

 *  Define el contenido a mostrar en las nuevas columna de la lista de mascotas

 * */

if(!function_exists('kmimos_manage_pets_columns')){

    function kmimos_manage_pets_columns($column_name, $id) {

        global $wpdb;

        switch ($column_name) {

        case 'id':

            echo $id;

            break;



        case 'thumbnail':

            echo get_the_post_thumbnail( $id, array( 75, 75) );

            break;

     

        case 'type':

            echo get_the_term_list($id,'pets-types','',',','');

            break;

     

        case 'gender':

            echo (get_post_meta( $id , 'gender_pet' , true )=='1')? 'Macho':'Hembra';

            break;



        case 'breed':

            echo get_post_meta( $id , 'breed_pet' , true );

            break;

     

        case 'age':

            $fecha = time() - strtotime(get_post_meta( $id , 'birthdate_pet' , true ));

            echo floor((($fecha / 3600) / 24) / 360).' años';

            break;



        case 'owner':

            $owner = get_userdata( get_post_meta( $id , 'owner_pet' , true ) );

            echo $owner->user_firstname.' '.$owner->user_lastname;

            break;



        default:

            echo $column_name;

            break;

        } // end switch

    }

}

/**

 *  Fin del archivo de cuidadores

 * */

?>