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
            //$values = kmimos_get_fields_values(array());
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

            $HTML = "<style>
                .vlz_contenedor_datos_cuidador *{
                    font-size: 14px;
                }

                .vlz_contenedor_datos_cuidador div{
                    padding: 4px 0px;
                }
                .vlz_contenedor_datos_cuidador strong{
                    width: 155px;
                    display: inline-block;
                }
                .vlz_activar{
                    background: #59c9a8;
                    padding: 5px 20px;
                    border-radius: 4px;
                    color: #FFF;
                    text-decoration: none;
                    border: 0px;
                    cursor: pointer;
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

                .info_container{
                    overflow: hidden;
                }

                .info_box{
                    float: left;
                    width: 50%;
                }
            </style>";

            $values=$params['args'];

            global $wpdb;

            $usuario = $wpdb->get_row("SELECT * FROM wp_users WHERE ID = ".$post->post_author);
            $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = ".$post->ID);
            $ubicacion = $wpdb->get_row("SELECT * FROM ubicaciones WHERE cuidador = ".$cuidador->id);

            if( $cuidador->hospedaje_desde > 0  || $cuidador->activo == 1 ){
                if( $post->post_status == 'pending' ){
                    $link = "<a class='vlz_activar' href='".getTema()."/procesos/cuidador/activar_cuidadores.php?p=".$post->ID."&a=1&u=".$post->post_author."'>Activar Cuidador</a>";
                }else{
                    $link = "<a class='vlz_desactivar' href='".getTema()."/procesos/cuidador/activar_cuidadores.php?p=".$post->ID."&a=0&u=".$post->post_author."'>Desactivar Cuidador</a>";
                }
            }else{
                $link = "Este cuidador no tiene precios de hospedaje, no puede ser activado";
            }
            
            $fecha = strtotime($usuario->user_registered);
            $hora = date("H:i", $fecha);
            $fecha = "El ".date("d/m/Y", $fecha)." a las ".$hora;
            $captacion = get_user_meta($cuidador->user_id, "user_referred", true);
            $direccion = get_user_meta($cuidador->user_id, "user_address", true);
            if( $captacion == "" ){ $captacion = "Otro"; }

            $estado = array_filter( explode("=", $ubicacion->estado) );
            $municipio = array_filter( explode("=", $ubicacion->municipios) );

            $estado = utf8_decode($wpdb->get_var("SELECT name FROM states WHERE id = ".$estado[1]));
            $municipio = utf8_decode($wpdb->get_var("SELECT name FROM locations WHERE id = ".$municipio[1]));

            $destacado = "";
            $atributos = unserialize($cuidador->atributos);
            if( isset($atributos["destacado"]) && $atributos["destacado"] == "1" ){
                $destacado_opt = '
                    <option value=1>Si</option>
                    <option value=0>No</option>
                ';
            }else{
                $destacado_opt = '
                    <option value=0>No</option>
                    <option value=1>Si</option>
                ';
            }

            $destacado = "<select id='destacado' name='destacado'>{$destacado_opt}</select>";

            $HTML .= "
                <div class='vlz_contenedor_datos_cuidador'>

                    <div class='info_container'>
                        <div class='info_box'>
                            <div><strong>ID:</strong> {$cuidador->user_id}</div>
                            
                            <div><strong>Nombre:</strong> {$cuidador->nombre} {$cuidador->apellido}</div>
                            <div><strong>IFE:</strong> {$cuidador->dni}</div>

                            <div><strong>Correo Electr&oacute;nico:</strong> {$cuidador->email}</div>
                            <div><strong>Tel&eacute;fono:</strong> {$cuidador->telefono}</div>

                            <div><strong>Estado:</strong> {$estado}</div>
                            <div><strong>Municipio:</strong> {$municipio}</div>
                            <div><strong>Direcci&oacute;n:</strong> {$direccion}</div>

                            <div><strong>Método de captación:</strong> {$captacion}</div>

                            <div><strong>Registrado:</strong> {$fecha}</div>
                        </div>

                        <div class='info_box'>
                            <input type='hidden' id='cuidador' name='cuidador' value='{$cuidador->id}' />
                            <div><strong>Destacado:</strong> {$destacado}</div>

                            <div class='vlz_contenedor_botones'>
                                <span id='actualizar_btn' class='vlz_activar'>Actualizar</span>
                            </div>
                        </div>
                    </div>

                    <div class='vlz_contenedor_botones'>{$link}</div>
                </div>

                <script>
                    jQuery( document ).ready(function() {

                        jQuery('#actualizar_btn').on('click', function(e){
                            
                            jQuery('#actualizar_btn').html('Procesando...');

                            jQuery.post( 
                                '".get_home_url()."/wp-content/plugins/kmimos/dashboard/setup/php/update_cuidador.php', 
                                {
                                    cuidador: jQuery('#cuidador').val(),
                                    destacado: jQuery('#destacado').val()
                                },
                                function( data ) {
                                    jQuery('#actualizar_btn').html('Actualizar');
                                }
                            );
                        });

                    });
                </script>
            ";

            echo comprimir_styles($HTML);
        }
    }

/*    if(!function_exists('kmimos_active_petsitter')){
        function kmimos_active_petsitter($post, $params) {
            $values=$params['args'];

            global $wpdb;

            $usuario = $wpdb->get_row("SELECT * FROM wp_users WHERE ID = ".$post->post_author);
            $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = ".$post->ID);

            if( $cuidador->hospedaje_desde > 0 ){
                if( $post->post_status == 'pending' ){
                    $link = "<a class='vlz_activar' href='".getTema()."/procesos/cuidador/activar_cuidadores.php?p=".$post->ID."&a=1&u=".$post->post_author."'>Activar Cuidador</a>";
                }else{
                    $link = "<a class='vlz_desactivar' href='".getTema()."/procesos/cuidador/activar_cuidadores.php?p=".$post->ID."&a=0&u=".$post->post_author."'>Desactivar Cuidador</a>";
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
    }*/

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

