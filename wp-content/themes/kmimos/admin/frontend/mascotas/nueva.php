<?php
	$pet_id = 0;
    $current_pet = kmimos_get_pet_info($pet_id);
    $photo_pet = getTema()."/images/popups/registro-cuidador-foto.png";

    $tipos = kmimos_get_types_of_pets();
    $tipos_str = "";
    foreach ( $tipos as $tipo ) {
        $tipos_str .= '<option value="'.$tipo->ID.'">'.esc_html( $tipo->name ).'</option>';
    }

    global $wpdb;
    $razas = $wpdb->get_results("SELECT * FROM razas ORDER BY nombre ASC");
    $razas_str = "<option value=''>Favor Seleccione</option>";
    foreach ($razas as $value) {
        $razas_str .= '<option value="'.$value->id.'">'.esc_html( $value->nombre ).'</option>';
    }
    $razas_str_gatos = "<option value=1>Gato</option>";

    $generos = kmimos_get_genders_of_pets();
    $generos_str = "";
    foreach ( $generos as $genero ) {
        $generos_str .= '<option value="'.$genero['ID'].'">'.esc_html( $genero['singular'] ).'</option>';
    }

    $tamanos = kmimos_get_sizes_of_pets();
    $tamanos_str = "";
    foreach ( $tamanos as $tamano ) {
        $tamanos_str .= '<option value="'.$tamano['ID'].'">'.esc_html( $tamano['name'].' ('.$tamano['desc'].')' ).'</option>';
    }

    $si_no = array('no','si');
    $esterilizado_str = "";
    for ( $i=0; $i<2; $i++ ) {
        $esterilizado_str .= '<option value="'.$i.'">'.strtoupper($si_no[$i]).'</option>';
    }

    $sociable_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $sociable_str .= '<option value="'.$i.'">'.strtoupper($si_no[$i]).'</option>';
    }

    $aggresive_humans_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $aggresive_humans_str .= '<option value="'.$i.'">'.strtoupper($si_no[$i]).'</option>';
    }

    $aggresive_pets_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $aggresive_pets_str .= '<option value="'.$i.'">'.strtoupper($si_no[$i]).'</option>';
    }

    $razas = $razas_str;
    if( $current_pet['type'] == 2608 ){
        $razas = $razas_str_gatos;
    }

	$CONTENIDO .= '
    <input type="hidden" name="accion" value="nueva_mascota" />
    <input type="hidden" name="pet_id" value="'.$pet_id.'" />
    <input type="hidden" name="user_id" value="'.$user_id.'" />

    <div id="razas_perros" style="display: none;">'.$razas_str.'</div>
    <div id="razas_gatos" style="display: none;">'.$razas_str_gatos.'</div>

    <h1 style="margin: 0px; padding: 0px;">Agregar nueva mascota</h1><hr style="margin: 5px 0px 10px;">

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

    <div class="inputs_containers" style="padding-bottom: 0px;">
        <section>
            <label for="pet_name" class="lbl-text">'.esc_html__('Nombre de la Mascota','kmimos').':</label>
            <label class="lbl-ui">
                <input type="text" name="pet_name" class="input" />
            </label>
        </section>

        <section>
            <label for="pet_birthdate" class="lbl-text">'.esc_html__('Fecha de nacimiento','kmimos').':</label>
            <label class="lbl-ui">
                <input 
                    type="text" 
                    id="pet_birthdate" 
                    name="pet_birthdate" 
                    placeholder="dd/mm/aaaa" 
                    value="" 
                    class="km-input-custom km-input-date date_from" 
                    readonly
                />
            </label>
        </section>
    </div>

    <div class="inputs_containers row_3" style="padding-bottom: 0px;">
   
        <section>
            <label for="pet_type" class="lbl-text">'.esc_html__('Tipo de Mascota','kmimos').':</label>
            <label class="lbl-ui">
                <select name="pet_type" class="input" id="pet_type" />
                    <option value="">Favor Seleccione</option>
                    '.$tipos_str.'
                </select>
            </label>
        </section>
    
        <section>
            <label for="pet_breed" class="lbl-text">'.esc_html__('Raza de la Mascota','kmimos').':</label>
            <label class="lbl-ui">
                <select id="pet_breed" name="pet_breed" class="input" />
                    '.$razas.'
                </select>
            </label>
        </section>
   
        <section>
            <label for="pet_colors" class="lbl-text">'.esc_html__('Colores de la Mascota','kmimos').':</label>
            <label class="lbl-ui">
                <input type="text" name="pet_colors" class="input" />
            </label>
        </section>

        <section>
            <label for="pet_gender" class="lbl-text">'.esc_html__('Género de la mascota','kmimos').':</label>
            <label class="lbl-ui">
                <select name="pet_gender" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$generos_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="pet_size" class="lbl-text">'.esc_html__('Tamaño de la Mascota','kmimos').':</label>
            <label class="lbl-ui">
                <select name="pet_size" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$tamanos_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="pet_sterilized" class="lbl-text">'.esc_html__('Mascota Esterilizada','kmimos').':</label>
            <label class="lbl-ui">
                <select name="pet_sterilized" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$esterilizado_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="pet_sociable" class="lbl-text">'.esc_html__('Mascota Sociable','kmimos').':</label>
            <label class="lbl-ui">
                <select name="pet_sociable" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$sociable_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="aggresive_humans" class="lbl-text">'.esc_html__('Agresiva con Humanos','kmimos').':</label>
            <label class="lbl-ui">
                <select name="aggresive_humans" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$aggresive_humans_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="aggresive_pets" class="lbl-text">'.esc_html__('Agresiva c/otras Mascotas','kmimos').':</label>
            <label class="lbl-ui">
                <select name="aggresive_pets" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$aggresive_pets_str.'
                </select>
            </label>
        </section>
    </div>

    <section style="padding: 0px 5px 10px;">
        <label for="pet_observations" class="lbl-text">'.esc_html__('Observaciones','kmimos').':</label>
        <label class="lbl-ui">
            <textarea name="pet_observations" class="textarea"></textarea>
        </label>
    </section>';
?>