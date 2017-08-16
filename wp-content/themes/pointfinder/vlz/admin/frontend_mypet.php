<?php
    $formaction = 'pfupdate_my_pet';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-update-pet-button';
    $buttontext = esc_html__('ACTUALIZAR MASCOTA','pointfindert2d');
    $pet_id = $params['pet_id']+0;
    $current_pet = kmimos_get_pet_info($pet_id);

    $photo_pet = (!empty($current_pet['photo']))? "/".$current_pet['photo']: "/wp-content/themes/pointfinder/images/noimg.png";

    $tipos = kmimos_get_types_of_pets();
    $tipos_str = "";
    foreach ( $tipos as $tipo ) {
        $tipos_str .= '<option value="'.$tipo->ID.'"';
        if($tipo->ID == $current_pet['type']) $tipos_str .= ' selected';
        $tipos_str .= '>'.esc_html( $tipo->name ).'</option>';
    }

    global $wpdb;
    $razas = $wpdb->get_results("SELECT * FROM razas ORDER BY nombre ASC");
    $razas_str = "<option value=''>Favor Seleccione</option>";
    foreach ($razas as $value) {
        $razas_str .= '<option value="'.$value->id.'"';
        if($value->id == $current_pet['breed']) $razas_str .= ' selected';
        $razas_str .= '>'.esc_html( $value->nombre ).'</option>';
    }
    $razas_str_gatos = "<option value=1>Gato</option>";

    $generos = kmimos_get_genders_of_pets();
    $generos_str = "";
    foreach ( $generos as $genero ) {
        $generos_str .= '<option value="'.$genero['ID'].'"';
        if($genero['ID'] == $current_pet['gender']) $generos_str .= ' selected';
        $generos_str .= '>'.esc_html( $genero['singular'] ).'</option>';
    }

    $tamanos = kmimos_get_sizes_of_pets();
    $tamanos_str = "";
    foreach ( $tamanos as $tamano ) {
        $tamanos_str .= '<option value="'.$tamano['ID'].'"';
        if($tamano['ID'] == $current_pet['size']) $tamanos_str .= ' selected';
        $tamanos_str .= '>'.esc_html( $tamano['name'].' ('.$tamano['desc'].')' ).'</option>';
    }

    $si_no = array('no','si');
    $esterilizado_str = "";
    for ( $i=0; $i<2; $i++ ) {
        $esterilizado_str .= '<option value="'.$i.'"';
        if( isset($current_pet['strerilized']) ){
            if($i == (int)$current_pet['strerilized']) $esterilizado_str .= ' selected';
        }else{
            if($i == (int)$current_pet['sterilized']) $esterilizado_str .= ' selected';
        }
        
        $esterilizado_str .= '>'.$si_no[$i].'</option>';
    }

    $sociable_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $sociable_str .= '<option value="'.$i.'"';
        if($i == (int)$current_pet['sociable']) $sociable_str .= ' selected';
        $sociable_str .= '>'.$si_no[$i].'</option>';
    }

    $aggresive_humans_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $aggresive_humans_str .= '<option value="'.$i.'"';
        if($i == (int)$current_pet['aggresive_humans']) $aggresive_humans_str .= ' selected';
        $aggresive_humans_str .= '>'.$si_no[$i].'</option>';
    }

    $aggresive_pets_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $aggresive_pets_str .= '<option value="'.$i.'"';
        if($i == (int)$current_pet['aggresive_pets']) $aggresive_pets_str .= ' selected';
        $aggresive_pets_str .= '>'.$si_no[$i].'</option>';
    }

    if( $pet_id > 0 ){
        $eliminar = '
            <label for="delete_pet" class="lbl-text" style="float: right; padding: 10px 0px;">
                <input type="checkbox" name="delete_pet" value="1">
                <strong>'.esc_html__('Eliminar esta mascota','pointfindert2d').'</strong>.
            </label>
        ';
    }

    $this->FieldOutput .= '
    <style>
        .cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .cell33 {width: 33.333333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .img_portada_pet{ position: relative; height: 400px; overflow: hidden; border: solid 1px #777; background: #EEE; }
        .img_portada_pet_fondo{ position: absolute; top: -1px; left: -1px; width: calc( 100% + 2px ); height: 402px; z-index: 50; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: transparent; filter: blur(2px); transition: all .5s ease; }
        .img_portada_pet_normal{ position: absolute; top: 0px; left: 0px; width: 100%; height: 380px; z-index: 100; background-size: contain; background-position: center; background-repeat: no-repeat; background-color: transparent; margin: 10px 0px; transition: all .5s ease; }
        .cambiar_portada_pet{ position: absolute; bottom: 10px; right: 10px; width: auto; padding: 10px; font-size: 16px; color: #FFF; background: #000; border: solid 1px #777; z-index: 200; }
        .cambiar_portada_pet input{ position: absolute; top: -24px; left: 0px; width: 100%; height: 167%; z-index: 100; opacity: 0; cursor: pointer; }

        .jj_dash_cel50{float: left; width: calc(50% - 9px);}
        .jj_dash2_cel50{float: right; width: calc(50% - 9px);}

        @media (max-width: 568px) {
            .jj_dash_cel50{float: left; width: calc(100% - 9px);}
            .jj_dash2_cel50{float: left; width: calc(100% - 9px);}
        }
    </style>
    <section>
        <div class="img_portada_pet">
            <div class="img_portada_pet_fondo" style="background-image: url('.$photo_pet.');"></div>
            <div class="img_portada_pet_normal" style="background-image: url('.$photo_pet.');"></div>
            <div class="cambiar_portada_pet">
                Cambiar Foto
                <input type="file" id="portada_pet" name="portada_pet" accept="image/*" />
            </div>
        </div>
    </section>

   <div class="cell50">
       <section>
            <label for="pet_name" class="lbl-text">'.esc_html__('Nombre de la Mascota','pointfindert2d').':</label>
            <label class="lbl-ui">
                <input type="hidden" name="pet_id" value="'.$current_pet['pet_id'].'" />
                <input type="text" name="pet_name" class="input" value="'.$current_pet['name'].'" />
            </label>
       </section>
   </div>

   <div class="cell50">
       <section>
            <label for="pet_type" class="lbl-text"><strong>'.esc_html__('Tipo de Mascota','pointfindert2d').'</strong>:</label>
            <label class="lbl-ui">
                <select name="pet_type" class="input" id="pet_type" onchange="vlz_cambio_tipo()" />
                    <option value="">Favor Seleccione</option>
                    '.$tipos_str.'
                </select>
            </label>
       </section>
   </div>

    <div id="razas_perros" style="display: none;">'.$razas_str.'</div>
    <div id="razas_gatos" style="display: none;">'.$razas_str_gatos.'</div>
    <div class="cell50">
       <section>
            <label for="pet_breed" class="lbl-text">'.esc_html__('Raza de la Mascota','pointfindert2d').':</label>
            <label class="lbl-ui">
                <select id="pet_breed" name="pet_breed" class="input" value="'.$current_pet['breed'].'" />
                    '.$razas_str.'
                </select>
            </label>
       </section>
   </div>
   <div class="cell50">
       <section>
            <label for="pet_colors" class="lbl-text">'.esc_html__('Colores de la Mascota','pointfindert2d').':</label>
            <label class="lbl-ui">
                <input type="text" name="pet_colors" class="input" value="'.$current_pet['colors'].'" />
            </label>
       </section>
   </div>
   <div class="cell25">
       <section>
            <label for="pet_birthdate" class="lbl-text">'.esc_html__('Fecha de nacimiento','pointfindert2d').':</label>
            <label class="lbl-ui">
                <input type="date" name="pet_birthdate" min="'.date("Y-m-d", strtotime('Now -30 years')).'" max="'.date("Y-m-d", strtotime('Now -1 day')).'" class="input datepicker" value="'.$current_pet['birthdate'].'" />
            </label>
       </section>
   </div>
   <div class="cell25">
       <section>
            <label for="pet_gender" class="lbl-text">'.esc_html__('Género de la mascota','pointfindert2d').':</label>
            <label class="lbl-ui">
                <select name="pet_gender" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$generos_str.'
                </select>
            </label>
        </section>
    </div>
    <div class="cell50">
        <section>
            <label for="pet_size" class="lbl-text"><strong>'.esc_html__('Tamaño de la Mascota','pointfindert2d').'</strong>:</label>
            <label class="lbl-ui">
                <select name="pet_size" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$tamanos_str.'
                </select>
            </label>
        </section>
    </div>
    <div class="cell25">
        <section>
            <label for="pet_sterilized" class="lbl-text">'.esc_html__('Mascota Esterilizada','pointfindert2d').':</label>
            <label class="lbl-ui">
                <select name="pet_sterilized" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$esterilizado_str.'
                </select>
            </label>
        </section>
   </div>
   <div class="cell25">
       <section>
            <label for="pet_sociable" class="lbl-text">'.esc_html__('Mascota Sociable','pointfindert2d').':</label>
            <label class="lbl-ui">
                <select name="pet_sociable" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$sociable_str.'
                </select>
            </label>
       </section>
   </div>
   <div class="cell25">
       <section>
            <label for="aggresive_humans" class="lbl-text">'.esc_html__('Agresiva con Humanos','pointfindert2d').':</label>
            <label class="lbl-ui">
                <select name="aggresive_humans" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$aggresive_humans_str.'
                </select>
            </label>
       </section>
   </div>
   <div class="cell25">
       <section>
            <label for="aggresive_pets" class="lbl-text">'.esc_html__('Agresiva c/otras Mascotas','pointfindert2d').':</label>
            <label class="lbl-ui">
                <select name="aggresive_pets" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$aggresive_pets_str.'
                </select>
            </label>
       </section>
   </div>
   <section>
        <label for="pet_observations" class="lbl-text">'.esc_html__('Observaciones','pointfindert2d').':</label>
        <label class="lbl-ui">
            <textarea name="pet_observations" class="textarea">'. $current_pet['observations'].'</textarea>
        </label>
        '.$eliminar.'
   </section>
    <script>
        function vlz_cambio_tipo(){
            var valor = jQuery("#pet_type").val();
            if( valor == "2605" ){
                var opciones = jQuery("#razas_perros").html();
                jQuery("#pet_breed").html(opciones);
            }
            if( valor == "2608" ){
                var opciones = jQuery("#razas_gatos").html();
                jQuery("#pet_breed").html(opciones);
            }
        }
        function vista_previa_pet(evt) {
            var files = evt.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                if (!f.type.match("image.*")) {
                    jQuery(\'#portada_pet\').val(\'\');
                    alert("El archivo seleccionado no es una imagen.");
                    continue;
                }
                var reader = new FileReader();
                reader.onload = (function(theFile) {
                   return function(e) {
                        jQuery(".img_portada_pet_fondo").css("background-image", "url("+e.target.result+")");
                        jQuery(".img_portada_pet_normal").css("background-image", "url("+e.target.result+")");
                   };
               })(f);
               reader.readAsDataURL(f);
            }
        }
        document.getElementById("portada_pet").addEventListener("change", vista_previa_pet, false);

        jQuery("#pfuaprofileform").submit(function(e){
            jQuery(".vlz_repeat_action").attr("disabled","disabled");
        });
    </script>';
?>