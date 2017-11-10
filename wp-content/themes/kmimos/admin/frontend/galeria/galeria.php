<?php
    global $wpdb;
    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
    $CONTENIDO .= '<h1 style="margin: 0px; padding: 0px;">Mis Fotos</h1><hr style="margin: 5px 0px 10px;"><ul class="mascotas_container">';
    $exist_file = false;
    $tmp_user_id = ($cuidador->id) - 5000;
    $path_galeria = "wp-content/uploads/cuidadores/galerias/".$tmp_user_id."/";
    $count_picture =0;
    if( is_dir($path_galeria) ){
        if ($dh = opendir($path_galeria)) {
            $imagenes = array();
            while (($file = readdir($dh)) !== false) {
                if (!is_dir($path_galeria.$file) && $file!="." && $file!=".."){
                    $exist_file = true;
                    $count_picture++;
                    $CONTENIDO .= '
                    <li class="mascotas_box">
                        <div class="mascotas_item">
                            <a class="mascotas_content" href="#">
                                <div class="vlz_img_portada_perfil">
                                    <div class="vlz_img_portada_fondo" style="background-image: url('.get_home_url().'/'.$path_galeria.$file.');"></div>
                                    <div class="vlz_img_portada_normal" style="background-image: url('.get_home_url().'/'.$path_galeria.$file.');"></div>
                                </div>
                            </a>
                            <div class="mascotas_delete" data-usu="'.$tmp_user_id.'" data-img="'.$file.'">
                                Eliminar
                            </div>
                        </div>
                    </li>';
                }
            }
            closedir($dh);
        }
    }
    $CONTENIDO .= "</ul>";
    if(!$exist_file){
        $CONTENIDO .=  '
        <h1 class="mascotas_vacio">
            No tienes ninguna foto cargada
        </h1>';
    }
?>