<?php
  $keyApi = 'AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8';
    // wp_enqueue_script( 'kmimos_gmap', 'https://maps.googleapis.com/maps/api/js?key='.$keyApi.'&callback=initMap');

    $formaction = 'pfupdate_my_shop';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-update-my-shop-button';
    $buttontext = esc_html__('ACTUALIZAR INFORMACIÓN','pointfindert2d');
    $user_id = $params['current_user'];

    global $wpdb;

    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id=".$user_id);

    $mascotas_cuidador = unserialize($cuidador->mascotas_cuidador);
    $tamanos_aceptados = unserialize($cuidador->tamanos_aceptados);
    $edades_aceptadas = unserialize($cuidador->edades_aceptadas);
    $comportamientos_aceptados = unserialize($cuidador->comportamientos_aceptados);
    $atributos = unserialize($cuidador->atributos);

    $petsitter_id = $cuidador->id;
    $lat = $cuidador->latitud;
    $lng = $cuidador->longitud;

    $lat_def = ($lat != '') ?  $lat: 4.708541513768121;
    $lng_def = ($lng != '') ?  $lng: -74.0709801277344;

  $anio = date("Y");
    if( $cuidador->experiencia < 1900 ){
      $cuidador->experiencia = $anio - $cuidador->experiencia;
    }

    $cuidando_desde = "<select class='input' name='cuidando_desde'>";
    for ($i=$anio; $i > 1901; $i--) { 
      $cuidando_desde .= "<option ".selected($cuidador->experiencia, $i, false).">{$i}</option>";
    }
  $cuidando_desde .= "</select>";

    $entrada = "<select class='input' name='entrada'>";
    for ($i=6; $i < 19; $i++) {
      if( $i < 10){ $i = "0$i"; }
      $entrada .= "<option value='{$i}:00:00' ".selected($cuidador->check_in, $i.':00:00', false).">{$i}:00</option>";
      $entrada .= "<option value='{$i}:30:00' ".selected($cuidador->check_in, $i.':30:00', false).">{$i}:30</option>";
    }
  $entrada .= "</select>";

    $salida = "<select class='input' name='salida'>";
    for ($i=6; $i < 19; $i++) {
      if( $i < 10){ $i = "0$i"; }
      $salida .= "<option value='{$i}:00:00' ".selected($cuidador->check_out, $i.':00:00', false).">{$i}:00</option>";
      $salida .= "<option value='{$i}:30:00' ".selected($cuidador->check_out, $i.':30:00', false).">{$i}:30</option>";
    }
  $salida .= "</select>";

    if( $cuidador->portada != "0" ){
        $imagen = '<img id="img_portada" src="'.get_home_url().'/wp-content/uploads/cuidadores/avatares/'.$petsitter_id.'/0.jpg">';
        $imagen = get_home_url().'/wp-content/uploads/cuidadores/avatares/'.$petsitter_id.'/0.jpg';
    }else{
      $imagen = '<img id="img_portada" src="'.get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png">';
        $imagen = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
    }

    if( $atributos['video_youtube'] != '' ){
      $atributos['video_youtube'] = "https://youtu.be/".$atributos['video_youtube'];
    }

    $tamano = array(
        'pequenos'=>'<p>Pequeño (0 - 10cm)</p>', 
        'medianos'=>'<p>Mediano (11 - 25cm)</p>', 
        'grandes'=>'<p>Grande (26 - 45cm)</p>', 
        'gigantes'=>'<p>Gigante (+46cm)</p>'
    );

    $ubicaciones = $wpdb->get_row("SELECT * FROM ubicaciones WHERE cuidador = ".$cuidador->id);

    $mis_estados = str_replace("==", "=", $ubicaciones->estado);
    $mis_estados = explode("=", $mis_estados);

    $estados_ids = array();
    $estados_names = array();
    foreach ($mis_estados as $key => $value) {
      if( trim($value) != "" ){
        $estado = $wpdb->get_row("SELECT * FROM states WHERE id = ".$value);
        $estados_ids[]   = $estado->id;
        $estados_names[] = $estado->name;
      }
    }

    if( count($estados_ids) > 0 ){
      $mi_estado = $estados_ids[0];
    }else{
      $mi_estado = "";
    }

    $mis_delegaciones = str_replace("==", "=", $ubicaciones->municipios);
    $mis_delegaciones = explode("=", $mis_delegaciones);

    $delegaciones_estado = array(); 

    $delegaciones_ids = array();
    $delegaciones_names = array(); $z = true; 
    foreach ($mis_delegaciones as $key => $value) {
      if( trim($value) != "" ){
        $delegacion = $wpdb->get_row("SELECT * FROM locations WHERE id = ".$value);
        $delegaciones_ids[]   = $delegacion->id;
        $delegaciones_names[] = $delegacion->name;
        if( $z ){
          $mi_delegacion = $value; $z = false;
        }
      }
    }

    $estados_array = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY name ASC");
    $estados = "<option value=''>Seleccione un municipio</option>";
    foreach($estados_array as $estado) { 
      if( $mi_estado == $estado->id ){ 
      $sel = "selected"; 
    }else{ $sel = ""; }
        $estados .= "<option value='".$estado->id."' $sel>".$estado->name."</option>";
    } 

  $estados = utf8_decode($estados);

  if($mi_delegacion != ""){
    $municipios_array = $wpdb->get_results("SELECT * FROM locations WHERE state_id = {$mi_estado} ORDER BY name ASC");

      $muni = "<option value=''>Seleccione una localidad</option>";
      foreach($municipios_array as $municipio) { 
        if( $mi_delegacion == $municipio->id ){ $sel = "selected"; }else{ $sel = ""; }
          $muni .= "<option value='".$municipio->id."' $sel>".$municipio->name."</option>";
      }
    $muni = utf8_decode($muni);

    }else{
      $muni = "<option value='' selected>Seleccione una localidad</option>";
    }

    $permitidas = "";
    for ($i=1; $i <= 6 ; $i++) {
      if( $cuidador->mascotas_permitidas == $i ){ $selected = "selected"; }else{ $selected = ""; }
      $permitidas .= "<option value={$i} ".$selected.">{$i}</option>";
    }

?>

<?php
  
  $this->FieldOutput .= '
    <style>
        div{
            vertical-align: top;
        }
        .cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .cell75 {width: 75%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .cell33 {width: 33.333333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .cell66 {width: 66.666666666%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .cell100 {width: 100%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
        .tamanos_checks{
            display: inline-block;
            padding: 7px 8px 0px 0px;
        }
        .vlz_check, .vlz_no_check{
            cursor: pointer; 
            position: relative;
            background-color: #EEE;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 12px;
            border: solid 1px #EEE;
            margin-bottom: 3px;
        }
        .vlz_check{
            background-color: #a8d8c9;
            border: 1px solid #87b8ab;
        }
        .vlz_no_check:after{
            content: \' \';
            position: absolute;
            top: 8px;
            right: 8px;
            width: 15px;
            height: 15px;
            border: solid 1px #CCC;
            border-radius: 2px;
        }
        .vlz_check:after{
            content: \' \';
            position: absolute;
            top: 8px;
            right: 8px;
            width: 15px;
            height: 15px;
            border: 1px solid #87b8ab;
            border-radius: 2px;
            background-size: 11px;
            background-repeat: no-repeat;
            background-position: 1px 1px;
            background-image: url('.get_home_url()."/wp-content/themes/pointfinder".'/vlz/img/iconos/new_check.png);
        }
        .golden-forms section {
            margin-bottom: 8px;
            position: relative;
        }
        .pf-uadashboard-container .pfalign-right {
            border-top: 0px;
        }

         @media screen and (max-width: 1100px){
            #pfuaprofileform .lbl-text {
                font-size: 9px;
            }
            .vlz_pin_check p,
            .vlz_pin_check {
                font-size: 10px !important;
            }
            .vlz_input {
                height: 35px!important;
            }
        }

        @media screen and (max-width: 700px){
            .cell25 {
                width: 50%;
            }
        }

        @media screen and (max-width: 500px){
            #pfuaprofileform .lbl-text {
                font-size: 11px;
                margin-bottom: 3px;
            }
            .cell25, .cell50{
                width: 100%;
            }
        }

    </style>
  ';
?>

<?php
  $this->FieldOutput .= '
    <h1 style="margin: 0px; padding: 0px;">Mi informaci&oacute;n como Cuidador</h1><hr style="margin: 5px 0px 10px;">
    <div class="tienda_cuidador">
      <input type="hidden" name="id" value="'.$petsitter_id.'">
            <section>
                <div class="cell25"> 
                    <label for="shop_details" class="lbl-text">'.esc_html__('DNI O PASAPORTE', 'pointfindert2d').':</label>
                    <input type"text" name="dni" class="input" value="'.$cuidador->dni.'"> 
                </div>    
                <div class="cell25"> 
                    <label class="lbl-text">'.esc_html__('Cuidando Desde', 'pointfindert2d').':</label>
                    '.$cuidando_desde.'                         
                </div>            
                <div class="cell25">     
                    <section>
                        <span class="goption">
                            <label class="lbl-text">'.esc_html__('Hora de Entrada','pointfindert2d').'</label>
                            '.$entrada.'
                       </span>
                    </section>                          
                </div>
                <div class="cell25">     
                    <section>
                        <span class="goption">
                            <label class="lbl-text">'.esc_html__('Hora de Salida','pointfindert2d').'</label>
                            '.$salida.'
                       </span>
                    </section>                          
                </div>                          
              <div class="clearfix" style="margin-bottom:5px"></div>
            </section> 

            <section>
                <div class="cell25">     
                   <section> 
                      <label for="solo_esterilizadas" class="lbl-text">'.esc_html__('¿No Esterilizadas?','pointfindert2d').':</label>
                        <select name="solo_esterilizadas" class="input">
                            <option value="0" '.selected($atributos['esterilizado'], 0, false).'>No</option>
                            <option value="1" '.selected($atributos['esterilizado'], 1, false).'>Si</option>
                        </select>
                   </section>                          
                </div>
                <div class="cell25">     
                   <section> 
                      <label for="emergencia" class="lbl-text">'.esc_html__('Transporte de Emergencia','pointfindert2d').':</label>
                        <select name="emergencia" class="input">
                            <option value="0" '.selected($atributos['emergencia'], 0, false).'>No</option>
                            <option value="1" '.selected($atributos['emergencia'], 1, false).'>Si</option>
                        </select>
                   </section>                          
                </div>
            <div class="cell25">     
                    <section>
                        <label for="acepto_hasta" class="lbl-text">'.esc_html__('Num. de perros aceptados','pointfindert2d').':</label>
                        <select id="acepto_hasta" name="acepto_hasta" class="input">
                            '.$permitidas.'
                        </select>
                    </section>                          
                </div>
                <div class="cell25">
                   <section>
                        <label for="video_youtube" class="lbl-text">'.esc_html__('Video de Youtube (URL)','pointfindert2d').':</label>
                        <label class="lbl-ui">
                          <input  type="text" name="video_youtube" class="input" value="'.$atributos['video_youtube'].'" />
                        </label>                          
                   </section>                          
                </div> 
                <div class="clearfix" style="margin-bottom:10px"></div>
            </section>

      <section>
                <div class="cell25">  
                    <label for="shop_type" class="lbl-text">'.esc_html__('Tipo de Propiedad','pointfindert2d').':</label>
                    <label class="lbl-ui">
                      <select name="propiedad" class="input">
                            <option value="1" '.selected($atributos['propiedad'], 1, false).'>Casa</option>
                            <option value="2" '.selected($atributos['propiedad'], 2, false).'>Departamento</option>
                        </select>
                    </label>    
              </div>
                <div class="cell25">     
                    <section>
                        <label class="lbl-text">'.esc_html__('Posee Áreas Verdes','pointfindert2d').'</label>
                        <select name="green" class="input">
                            <option value="0" '.selected($atributos['green'], 0, false).'>No</option>
                            <option value="1" '.selected($atributos['green'], 1, false).'>Si</option>
                        </select>
                    </section>                          
              </div>
                <div class="cell25">    
                    <section>
                        <label class="lbl-text">'.esc_html__('Posee Patio','pointfindert2d').'</label>
                        <select name="yard" class="input">
                            <option value="0" '.selected($atributos['yard'], 0, false).'>No</option>
                            <option value="1" '.selected($atributos['yard'], 1, false).'>Si</option>
                        </select>
                    </section>   
              </div>
                <div class="cell25">
                  <label for="ages_accepted" class="lbl-text">'.esc_html__('Mascotas en Casa','pointfindert2d').':</label>    
                    <input type"text" name="num_mascotas_casa" class="input" value="'.$cuidador->num_mascotas.'">                 
                </div>
                <div class="clearfix"></div> 
            </section>

            <section>
                <div class="cell50">     
                   <section> 
                      <label for="estado" class="lbl-text">'.esc_html__('Municipio','pointfindert2d').':</label>
                        <select id="estado" name="estado" class="input">
                            '.$estados.'
                        </select>
                   </section>                          
                </div>
            <div class="cell50">     
                    <section>
                        <label for="delegacion" class="lbl-text">'.esc_html__('Localidad','pointfindert2d').':</label>
                        <select id="delegacion" name="delegacion" class="input">
                            '.$muni.'
                        </select>
                    </section>                          
                </div>
                <div class="clearfix" style="margin-bottom:10px"></div>
            </section>

            <section>
                <div class="cell100">     
                   <section> 
                      <label for="ages_accepted" class="lbl-text">'.esc_html__('Dirección','pointfindert2d').':</label>
                        <input  type="text" id="direccion" name="direccion" class="input" value="'.$cuidador->direccion.'" />
                   </section>                          
                </div>
                <div class="clearfix" style="margin-bottom:10px"></div>
            </section>

            <section>

                <div class="cell25">
                  <label for="ages_accepted" class="lbl-text">'.esc_html__('Tamaños de mis mascotas','pointfindert2d').':</label>';
                    foreach ($mascotas_cuidador as $key => $value) {
                      if($value == 1){
                        $check = "vlz_check";
                      }else{
                        $check = "";
                      }
                      $this->FieldOutput .= '
                    <span class="goption col12">
                            <div class="vlz_input vlz_no_check vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" id="tengo_'.$key.'" name="tengo_'.$key.'" value="'.$value.'">'.$tamano[$key].'</div>
                        </span>';
                    }
                    $this->FieldOutput .= '                
                </div>

            <div class="cell25">     
                    <section>
                        <label for="behavior_accepted" class="lbl-text">'.esc_html__('Conductas Aceptadas','pointfindert2d').':</label>';
                        $compor = array(
                            "sociables" => "Sociables",
                            "no_sociables" => "No Sociables",
                            "agresivos_personas" => "Agresivos con Humanos",
                            "agresivos_perros" => "Agresivos con Mascotas",
                            "agresivos_humanos" => "Agresivos con Humanos",
                            "agresivos_mascotas" => "Agresivos con Mascotas"
                        );
                        foreach ($comportamientos_aceptados as $key => $value) {
                            if($comportamientos_aceptados[$key] == 1){
                                $check = "vlz_check";
                            }else{
                                $check = "";
                            }
                            $this->FieldOutput .= '
                                <span class="goption col12">
                                    <div class="vlz_input vlz_no_check vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$comportamientos_aceptados[$key].'">'.$compor[$key].'</div>  
                                </span>
                            ';
                        }

                        $this->FieldOutput .= '
                    </section>    
                </div>
            <div class="cell25">     
                    <section>
                        <label for="behavior_accepted" class="lbl-text">'.esc_html__('Tama&ntilde;os Aceptados','pointfindert2d').':</label>';
                        foreach ($tamanos_aceptados as $key => $value) {
                          if($value == 1){
                            $check = "vlz_check";
                          }else{
                            $check = "";
                          }
                          $this->FieldOutput .= '
                            <span class="goption col12">
                                  <div class="vlz_input vlz_no_check vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" id="acepta_'.$key.'" name="acepta_'.$key.'" value="'.$value.'">'.$tamano[$key].'</div>
                            </span>';
                        }
                        $this->FieldOutput .= '
                    </section>                          
                </div>

                <div class="cell25">     
                   <section>
                        <label for="ages_accepted" class="lbl-text">'.esc_html__('Edades Aceptadas','pointfindert2d').':</label>';
                        $edades = array(
                          "cachorros" => "Cachorros",
                          "adultos"   => "Adultos"
                        );
                        foreach ($edades_aceptadas as $key => $value) {
                          if($edades_aceptadas[$key] == 1){ $check = "vlz_check"; }else{ $check = ""; }
                          $this->FieldOutput .= '
                            <span class="goption col12">
                                  <div class="vlz_input vlz_no_check vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" id="acepta_'.$key.'" name="acepta_'.$key.'" value="'.$edades_aceptadas[$key].'">'.$edades[$key].'</div>  
                              </span>
                          ';
                        }
                        $this->FieldOutput .= '
                   </section>                          
                </div>
                <div class="clearfix" style="margin-bottom:5px"></div><br>
            </section>

            <section>
                <input type="hidden" class="geolocation" id="latitude_petsitter" name="latitude_petsitter" placeholder="Latitud" step="any" value="'. $lat_def .'" />
                <input type="hidden" class="geolocation" id="longitude_petsitter" name="longitude_petsitter" placeholder="Longitud" step="any" value="'. $lng_def .'" />
            </section>
            '.get_estados_municipios().'
            <script>
                jQuery(".vlz_pin_check").on("click", function(){
                    if( jQuery("input", this).attr("value") == "0" ){
                        jQuery("input", this).attr("value", "1");
                        jQuery(this).removeClass("vlz_no_check");
                        jQuery(this).addClass("vlz_check");
                    }else{
                        jQuery("input", this).attr("value", "0");
                        jQuery(this).removeClass("vlz_check");
                        jQuery(this).addClass("vlz_no_check");
                    }
                });

                jQuery("#estado").on("change", function(e){
                    var estado_id = jQuery("#estado").val();    
                    if( estado_id != "" ){
                        var html = "<option value=\'\'>Seleccione un municipio</option>";
                        jQuery.each(estados_municipios[estado_id]["municipios"], function(i, val) {
                            html += "<option value="+val.id+" data-id=\'"+i+"\'>"+val.nombre+"</option>";
                        });
                        jQuery("#delegacion").html(html);
                    }
                });

                jQuery("#delegacion").on("change", function(e){
                    vlz_coordenadas();
                });

                function vlz_coordenadas(){
                    var estado_id = jQuery("#estado").val();            
                    var municipio_id = jQuery(\'#delegacion > option[value="\'+jQuery("#delegacion").val()+\'"]\').attr(\'data-id\');   
                }                

            </script> 
        </div>
  ';

  /*
    <label for="shop_zip" class="lbl-text">'.esc_html__('Indique su ubicación en el Mapa','pointfindert2d').':</label>
    <div id="map-canvas" data-latitude="latitude_petsitter" data-longitude="longitude_petsitter"></div>
  */
?>

