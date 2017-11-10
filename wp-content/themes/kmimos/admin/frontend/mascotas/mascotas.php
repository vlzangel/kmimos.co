<?php
    $mascotas = kmimos_get_my_pets($user_id);

    if( count($mascotas) > 0 ) {
        $CONTENIDO .= '
        <div style="padding-top: 10px; text-align: justify;">
            <p>
                En esta sección podrás identificar a las mascotas de tu propiedad.
            </p>
            <p>
                Si piensas contratar un servicio a través de Kmimos, es importante que las identifiques ya que solo las identificadas en tu perfil estarán amparadas por la cobertura de servicios veterinarios Kmimos.
            </p>
            <p>
                Si además te interesa formar parte de la familia de Cuidadores asociados a Kmimos, es importante también que tus mascotas estén identificadas. Muchas personas prefieren contratar a cuidadores que tengan perritos similares a los suyos, mientras que hay otros que buscan cuidadores que tengan mascotas de determinadas razas y tamaños.
            </p>
        </div>
        <h1 style="margin: 0px; padding: 0px;">Mis Mascotas</h1><hr style="margin: 5px 0px 10px;"><ul class="mascotas_container">';
        foreach($mascotas as $pet){
            $pet_detail = get_post_meta($pet->ID);

            $photo = (!empty($pet_detail['photo_pet'][0])) ? get_home_url().'/'.$pet_detail['photo_pet'][0] : get_home_url().'/wp-content/themes/pointfinder/images/default.jpg';
            $CONTENIDO .= '
                <li class="mascotas_box">
                    <div class="mascotas_item">
                        <a class="mascotas_content" href="'. get_home_url()."/perfil-usuario/mascotas/ver/".$pet->ID.'">
                            <div class="vlz_img_portada_perfil">
                                <div class="vlz_img_portada_fondo" style="background-image: url('.$photo.');"></div>
                                <div class="vlz_img_portada_normal" style="background-image: url('.$photo.');"></div>
                            </div>
                            <div class="mascotas_data">
                                <h3 class="kmi_link">'.$pet->post_title.'</h3>
                                <br>
                                <img src="'.get_home_url().'/wp-content/themes/kmimos/images/new/icon/icon-hueso-color.svg" width="50px">
                            </div>
                        </a>
                        <div class="mascotas_delete" data-img="'.$pet->ID.'">
                            Eliminar
                        </div>
                    </div>
                </li>';
        }
        $CONTENIDO .= '</ul>';
    }else{
        $CONTENIDO .=  '
            <h1 class="mascotas_vacio">
                <img src="'.get_home_url().'/wp-content/themes/kmimos/images/new/icon/icon-hueso-color.svg" width="50px">
                No tienes ninguna mascota cargada
            </h1>';
    }

?>