<?php

    add_action('init',              'kmimos_register_petsitters');
    add_action('add_meta_boxes',    'kmimos_box_details_of_petsitter');
    add_action('save_post',         'kmimos_save_details_of_petsitter');

    if(!function_exists('kmimos_register_petsitters')){
        function kmimos_register_petsitters() {
        	$labels = array(
                'name' => _x('Cuidadores', 'post type general name'),
                'singular_name' => _x('Cuidador', 'post type singular name'),
                'add_new' => _x('Agregar nuevo', 'Cuidador'),
                'add_new_item' => __('Agregar nuevo cuidador'),
                'edit_item' => __('Editar cuidador'),
                'new_item' => __('Nuevo cuidador'),
                'view_item' => __('Ver cuidador'),
                'search_items' => __('Buscar cuidadores'),
                'not_found' => __('Cuidador no encontrado'),
                'not_found_in_trash' => __('Not found nothing in trash'),
    			'parent_item_colon' => '',
    			'menu_name' =>  __('Cuidadores')
            );

            $args = array(
                'labels'        => $labels,
                'public'        => true,
                'hierarchical'  => false,
                'show_in_menu'  => 'kmimos',
                'menu_position' => 4,
                'has_archive'   => true,
                'query_var'     => true,
                'supports'      => array('title'),
                'rewrite'       => array(
                    'slug' => 'petsitters'
                )
            );

            register_post_type( 'petsitters', $args );
        }
    }

    if(!function_exists('kmimos_box_details_of_petsitter')){
        function kmimos_box_details_of_petsitter() {
            $values = kmimos_get_fields_values(array());
            add_meta_box(
                'active_petsitter',
                'Datos Cuidador',
                'kmimos_active_petsitter',
                'petsitters'
            );
        }
    }

    if(!function_exists('kmimos_active_petsitter')){
        function kmimos_active_petsitter($post, $params) {
            $values=$params['args'];

            global $wpdb;

            $usuario = $wpdb->get_row("SELECT * FROM wp_users WHERE ID = ".$post->post_author);
            $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = ".$post->ID);

            if( $cuidador->hospedaje_desde > 0 ){
                if( $post->post_status == 'pending' ){
                    $link = "<a class='vlz_activar' href='".get_home_url()."/wp-content/themes/pointfinder"."/vlz/admin/activar_cuidadores.php?p=".$post->ID."&a=1&u=".$post->post_author."'>Activar Cuidador</a>";
                }else{
                    $link = "<a class='vlz_desactivar' href='".get_home_url()."/wp-content/themes/pointfinder"."/vlz/admin/activar_cuidadores.php?p=".$post->ID."&a=0&u=".$post->post_author."'>Desactivar Cuidador</a>";
                }
            }else{
                $link = "Este cuidador no tiene precios de hospedaje, no puede ser activado";
            }
               

            $fecha = strtotime($usuario->user_registered);
            $hora = date("H:i", $fecha);
            $fecha = "El ".date("d/m/Y", $fecha)." a las ".$hora;

            echo "
                <style>
                    .vlz_contenedor_datos_cuidador *{
                        font-size: 14px;
                    }

                    .vlz_contenedor_datos_cuidador div{
                        padding: 4px 0px;
                    }
                    .vlz_contenedor_datos_cuidador strong{
                        width: 80px;
                        display: inline-block;
                    }
                    .vlz_activar{
                        background: #59c9a8;
                        padding: 5px 20px;
                        border-radius: 4px;
                        color: #FFF;
                        text-decoration: none;
                    }
                    .vlz_desactivar{
                        background: #ca4e4e;
                        padding: 5px 20px;
                        border-radius: 4px;
                        color: #FFF;
                        text-decoration: none;
                    }
                    .vlz_contenedor_botones{
                        text-align: right;
                        padding: 13px 0px 0px !important;
                        border-top: solid 1px #CCC;
                        margin-top: 10px;
                    }
                    #edit-slug-box,
                    #post-body-content,
                    .page-title-action,
                    #admin-post-nav{
                        display: none;
                    }
                </style>
                <div class='vlz_contenedor_datos_cuidador'>
                    <div><strong>Nombre:</strong> {$cuidador->nombre} {$cuidador->apellido}</div>
                    <div><strong>IFE:</strong> {$cuidador->dni}</div>
                    <div><strong>E-Mail:</strong> {$cuidador->email}</div>
                    <div><strong>Tel&eacute;fono:</strong> {$cuidador->telefono}</div>
                    <div><strong>Registrado:</strong> {$fecha}</div>
                    <div class='vlz_contenedor_botones'>{$link}</div>
                </div>
            ";
        }
    }

    if(!function_exists('kmimos_save_details_of_petsitter')){
        function kmimos_save_details_of_petsitter($post_id) {
            global $wpdb;
            $post_id    = get_the_ID();
            $user_id    = get_the_author();

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  return;
            if ( 'petsitters' != $_POST['post_type'] ) return;
            if ( ! current_user_can( 'edit_post', $post_id ) )  return;

        }
    }

?>

