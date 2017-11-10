<?php
    $photo = "/wp-content/themes/pointfinder/images/noimg.png";

    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
    $tmp_user_id = ($cuidador->id) - 5000;
	$CONTENIDO .= '
    <input type="hidden" name="accion" value="nueva_galeria" />
    <input type="hidden" name="user_id" value="'.$tmp_user_id.'" />

    <section>

        <div class="vlz_img_portada_perfil">
            <div class="vlz_img_portada_fondo vlz_rotar" style="background-image: url('.getTema().'/images/noimg.png);"></div>
            <div class="vlz_img_portada_normal vlz_rotar" style="background-image: url('.getTema().'/images/noimg.png);"></div>
            <div class="vlz_img_portada_cargando vlz_cargando" style="background-image: url('.getTema().'/images/cargando.gif);"></div>
            <div class="vlz_cambiar_portada">
                <i class="fa fa-camera" aria-hidden="true"></i>
                Cargar Foto
                <input type="file" id="portada" name="xportada" accept="image/*" />
            </div>
            <div id="rotar_i" class="btn_rotar" style="display: none;" data-orientacion="left"> <i class="fa fa-undo" aria-hidden="true"></i> </div>
            <div id="rotar_d" class="btn_rotar" style="display: none;" data-orientacion="right"> <i class="fa fa-repeat" aria-hidden="true"></i> </div>
        </div>
        <input type="hidden" class="vlz_img_portada_valor vlz_rotar_valor" id="portada" name="portada" data-valid="requerid" />

        <div class="btn_aplicar_rotar" style="display: none;"> Aplicar Cambio </div>

    </section>
    ';
?>