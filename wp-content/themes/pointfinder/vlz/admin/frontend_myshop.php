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

    $estados_array = $wpdb->get_results("SELECT * FROM states WHERE country_id = 0 ORDER BY name ASC");
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




//COORDENADAS
$result_coord = $wpdb->get_results("
						SELECT
							locations.clave AS clave,
							locations.valor AS valor
						FROM
							kmimos_opciones AS locations
						WHERE
							locations.clave LIKE 'municipio%'
							OR
							locations.clave LIKE 'estado%'
						ORDER BY
							locations.id ASC"
);

$state=array();
$locale=array();
foreach($result_coord as $data){
    $clave = $data->clave;
    $valor = unserialize($data->valor);
    //var_dump(strpos($clave,'estado'));

    if(strpos($clave,'estado_') !== false){
        $id=str_replace('estado_','',$clave);
        $state[$id]=array(
            'lat'=>$valor['referencia']->lat,
            'lng'=>$valor['referencia']->lng
        );
    }

    if(strpos($clave,'municipio_') !== false){
        $id=str_replace('municipio_','',$clave);
        $locale[$id]=array(
            'lat'=>$valor['referencia']->lat,
            'lng'=>$valor['referencia']->lng
        );
    }/**/
    //
}

$coord=array();
$coord['state']=$state;
$coord['locale']=$locale;
echo get_estados_municipios();
//'.get_estados_municipios().'
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
                        <input type="hidden" class="geolocation" id="latitud" name="latitud" placeholder="Latitud" step="any" value="'.$cuidador->latitud.'" />
						<input type="hidden" class="geolocation" id="longitud" name="longitud" placeholder="Longitud" step="any" value="'.$cuidador->longitud.'" />
                   </section>
                </div>

				<div id="messageDirection" class="message"></div>
			    <div id="map"></div>
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




<script type='text/javascript'>
    //MAP
    var map;
    var lat='';
    var lng='';

    function initMap() {
        var latitud = lat;
        var longitud = lng;
        var input = document.getElementById('direccion');//address

        if(latitud!='' && longitud!='' && typeof(google) != "undefined"){
            jQuery('#map').css({'height':'250px'});

            point = new google.maps.LatLng(latitud, longitud);
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center:  point,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });


            //AUTOCOMPLETE
            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', fillAutocomplete);
            //autocomplete.bindTo('bounds', map);

            //ZOOM
            var bounds = new google.maps.LatLngBounds();
            bounds.extend(point);
            //map.fitBounds(bounds);

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: point,
                icon: "https://www.kmimos.com.mx/wp-content/themes/pointfinder/vlz/img/pin.png"
            });

            google.maps.event.addListener(marker, 'dragstart', function(evt){});
            google.maps.event.addListener(marker, 'dragend', function(event){
                lat=this.getPosition().lat();
                lng=this.getPosition().lng();
                set_inputCoord();
            });

        }
    }




    function fillAutocomplete(){
        var place = autocomplete.getPlace();
        //console.log(place);

        if(typeof place === 'undefined'){
            messageInsite('#messageDirection','Sus coordenadasno fueron reconocidas.  puede buscar su ubicacion directamente en el mapa moviendo el pin');
            message('Sus coordenadas no fueron reconocidas.  puede buscar su ubicacion directamente en el mapa moviendo el pin');
            return;

        }else{
            if(!place.geometry){
                //window.alert("Autocomplete's returned place contains no geometry");
                messageInsite('#messageDirection','El sitio Seleccionado, GoogleMap no obtiene las coordenadas');
                message('El sitio Seleccionado, GoogleMap no obtiene las coordenadas');
                return;

            }else{
                var callback = function(){}
                //messageClose('#messageDirection', callback);
                jQuery('#messageDirection').css({'display':'none'});
                messageInsite('#messageDirection','Puede mejorar su ubicacion directamente en el mapa moviendo el pin');
                message('Puede mejorar su ubicacion directamente en el mapa moviendo el pin');

                lat=place.geometry.location.lat();
                lng=place.geometry.location.lng();
                set_inputCoordMap();
            }
        }
    }

    (function(d, s){
        $ = d.createElement(s), e = d.getElementsByTagName(s)[0];
        $.async=!0;
        $.setAttribute('charset','utf-8');
        $.src='//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&libraries=places&callback=initMap';
        $.type='text/javascript';
        e.parentNode.insertBefore($, e)
    })(document,'script');


    //COORDENADAS
    var objectCoord = jQuery.makeArray(eval(
        '(<?php echo json_encode($coord); ?>)'
    ));
    var Coordsearch = objectCoord[0] ;

    jQuery(document).on('change', 'select[name="estado"]', function(e){
        var state=jQuery(this).val();
        var latitude=Coordsearch['state'][state]['lat'];
        var longitude=Coordsearch['state'][state]['lng'];

        if(latitude!='' && longitude!=''){
            lat=latitude;
            lng=longitude;
            set_inputCoordMap();
            messageInsite('#messageDirection','Seleccionaste Municipio');
            message('Seleccionaste Municipio');
        }
    });

    jQuery(document).on('change', 'select[name="delegacion"]', function(e){
        var locale=jQuery(this).val();
        var latitude=Coordsearch['locale'][locale]['lat'];
        var longitude=Coordsearch['locale'][locale]['lng'];

        if(latitude!='' && longitude!=''){
            lat=latitude;
            lng=longitude;
            set_inputCoordMap();
            messageInsite('#messageDirection','seleccionaste Localidad');
            message('seleccionaste Localidad');
        }
    });

    /*
     jQuery(document).on('keypress', 'input[name="direccion"]', function (e) {
     var pressedKey = String.fromCharCode(e.keyCode);
     var $txt = jQuery(this);
     jQuery(this).change();
     e.preventDefault();
     if(pressedKey == 'a'){

     }
     });
     */

    //, input[name="direccion"]
    jQuery(document).on('change', 'select[name="delegacion"], select[name="estado"]', function(e){
        var estado=jQuery('select[name="estado"]');
        var municipio=jQuery('select[name="delegacion"]');
        var direccion=jQuery('input[name="direccion"]');

        var value='';
        if(estado.val()!=''){
            value+=estado.find('option:selected').text()+' ';
        }
        if(municipio.val()!=''){
            value+=municipio.find('option:selected').text()+' ';
        }
        if(direccion.val()!=''){
            //value+=direccion.val();
        }


        jQuery('input[name="direccion"]').val(value);
        jQuery('input[name="address"]').val(value);//.focus();


    });


    //ADDRESS ACTION
    jQuery(document).on('focusout', 'input[name="direccion"]', function(e){
        fillAutocomplete();
    });

    function set_inputCoord(){
        jQuery('input[name="latitud"]').val(lat);
        jQuery('input[name="longitud"]').val(lng);
    }

    function set_inputCoordMap(){
        set_inputCoord();
        initMap();
    }

</script>


<?php // include(dirname(dirname(__DIR__))."/kmimos/js/quiero_ser_cuidador.php"); ?>