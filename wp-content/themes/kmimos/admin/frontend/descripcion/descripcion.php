<?php
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

    $cuidando_desde = "<select class='input' id='cuidando_desde' name='cuidando_desde'>";
    for ($i=$anio; $i > 1901; $i--) { 
      	$cuidando_desde .= "<option ".selected($cuidador->experiencia, $i, false).">{$i}</option>";
    }
  	$cuidando_desde .= "</select>";

    $entrada = "<select class='input' id='entrada' name='entrada'>";
    for ($i=6; $i < 19; $i++) {
      	if( $i < 10){ $i = "0$i"; }
      	$entrada .= "<option value='{$i}:00:00' ".selected($cuidador->check_in, $i.':00:00', false).">{$i}:00</option>";
      	$entrada .= "<option value='{$i}:30:00' ".selected($cuidador->check_in, $i.':30:00', false).">{$i}:30</option>";
    }
  	$entrada .= "</select>";

    $salida = "<select class='input' id='salida' name='salida'>";
    for ($i=6; $i < 19; $i++) {
      	if( $i < 10){ $i = "0$i"; }
      	$salida .= "<option value='{$i}:00:00' ".selected($cuidador->check_out, $i.':00:00', false).">{$i}:00</option>";
      	$salida .= "<option value='{$i}:30:00' ".selected($cuidador->check_out, $i.':30:00', false).">{$i}:30</option>";
    }
  	$salida .= "</select>";

    if( $atributos['video_youtube'] != '' ){
      	$atributos['video_youtube'] = "https://youtu.be/".$atributos['video_youtube'];
    }

    $tamano = array(
        'pequenos'=>'Pequeño (0 - 25cm)', 
        'medianos'=>'Mediano (25 - 58cm)', 
        'grandes'=>'Grande (58 - 73cm)', 
        'gigantes'=>'Gigante (73 - 200cm)'
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

    if( !isset($_SESSION)){ session_start(); }
    if(  $_SESSION['admin_sub_login'] == 'YES' ){
        $permitidas = '
            <input type="text" id="acepto_hasta" name="acepto_hasta" class="input" value="'.$cuidador->mascotas_permitidas.'" />
        ';
    }else{
        $permitidas = "";
        for ($i=1; $i <= 6 ; $i++) {
            if( $cuidador->mascotas_permitidas == $i ){ $selected = "selected"; }else{ $selected = ""; }
            $permitidas .= "<option value={$i} ".$selected.">{$i}</option>";
        }
        $permitidas = '
            <select id="acepto_hasta" name="acepto_hasta" class="input">
                '.$permitidas.'
            </select>
        ';
    }

    $mascotas_cuidador_str = "";
    foreach ($mascotas_cuidador as $key => $value) {
      	if($value == 1){ $check = "vlz_check"; }else{ $check = ""; }
      	$mascotas_cuidador_str .= '<div class="vlz_input vlz_no_check vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" id="tengo_'.$key.'" name="tengo_'.$key.'" value="'.$value.'">'.$tamano[$key].'</div>';
    }

    $compor = array(
        "sociables" => "Sociables",
        "no_sociables" => "No Sociables",
        "agresivos_personas" => "Agresivos con Humanos",
        "agresivos_perros" => "Agresivos con Mascotas",
        "agresivos_humanos" => "Agresivos con Humanos",
        "agresivos_mascotas" => "Agresivos con Mascotas"
    );
    $comportamientos_aceptados_str = "";
    foreach ($comportamientos_aceptados as $key => $value) {
        if($comportamientos_aceptados[$key] == 1){ $check = "vlz_check"; }else{ $check = ""; }
        $comportamientos_aceptados_str .= '<div class="vlz_input vlz_no_check vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$comportamientos_aceptados[$key].'">'.$compor[$key].'</div>';
    }
    $tamanos_aceptados_str = "";
    foreach ($tamanos_aceptados as $key => $value) {
        if($value == 1){ $check = "vlz_check"; }else{ $check = ""; }
      	$tamanos_aceptados_str .= '<div class="vlz_input vlz_no_check vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" id="acepta_'.$key.'" name="acepta_'.$key.'" value="'.$value.'">'.$tamano[$key].'</div>';
    }

    $edades = array(
      "cachorros" => "Cachorros",
      "adultos"   => "Adultos"
    );
    $edades_aceptadas_str = "";
    foreach ($edades_aceptadas as $key => $value) {
        if($value == 1){ $check = "vlz_check"; }else{ $check = ""; }
      	$edades_aceptadas_str .= '<div class="vlz_input vlz_no_check vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" id="acepta_'.$key.'" name="acepta_'.$key.'" value="'.$edades_aceptadas[$key].'">'.$edades[$key].'</div>';
    }
  
  	$CONTENIDO .= '
    <input type="hidden" name="accion" value="update_descripcion" />
    <input type="hidden" name="cuidador_id" value="'.$cuidador->id.'" />
    <input type="hidden" name="user_id" value="'.$user_id.'" />

    <h1 style="margin: 0px; padding: 0px;">Mi informaci&oacute;n como Cuidador</h1><hr style="margin: 5px 0px 10px;">
    <div class="inputs_containers row_4" style="padding-bottom: 0px;">
        <section> 
            <label for="pet_name" class="lbl-text">'.esc_html__('IFE','kmimos').':</label>
            <label class="lbl-ui">
                <input type"text" id="dni" name="dni" class="input" value="'.$cuidador->dni.'"> 
            </label>
        </section>

        <section> 
            <label class="lbl-text">'.esc_html__('Cuidando Desde', 'kmimos').':</label>
            <label class="lbl-ui">
                '.$cuidando_desde.'
            </label>
        </section>

        <section> 
            <label class="lbl-text">'.esc_html__('Hora de Entrada', 'kmimos').':</label>
            <label class="lbl-ui">
                '.$entrada.'
            </label>
        </section>

        <section> 
            <label class="lbl-text">'.esc_html__('Hora de Salida', 'kmimos').':</label>
            <label class="lbl-ui">
                '.$salida.'
            </label>
        </section>

        <section> 
          	<label for="solo_esterilizadas" class="lbl-text">'.esc_html__('¿No Esterilizadas?','kmimos').':</label>
            <label class="lbl-ui">
	            <select id="solo_esterilizadas" name="solo_esterilizadas" class="input">
	                <option value="0" '.selected($atributos['esterilizado'], 0, false).'>No</option>
	                <option value="1" '.selected($atributos['esterilizado'], 1, false).'>Si</option>
	            </select>
            </label>
       </section>  

        <section> 
          	<label for="emergencia" class="lbl-text">'.esc_html__('Transporte de Emergencia','kmimos').':</label>
            <label class="lbl-ui">
	            <select id="emergencia" name="emergencia" class="input">
	                <option value="0" '.selected($atributos['emergencia'], 0, false).'>No</option>
	                <option value="1" '.selected($atributos['emergencia'], 1, false).'>Si</option>
	            </select>
            </label>
       </section>  

        <section> 
            <label for="acepto_hasta" class="lbl-text">'.esc_html__('Num. de perros aceptados','kmimos').':</label>
            <label class="lbl-ui">
                '.$permitidas.'
            </label>
        </section>

        <section> 
            <label for="video_youtube" class="lbl-text">'.esc_html__('Video de Youtube (URL)','kmimos').':</label>
            <label class="lbl-ui">
             	<input  type="text" id="video_youtube" name="video_youtube" class="input" value="'.$atributos['video_youtube'].'" />
            </label>
        </section>

        <section> 
            <label for="shop_type" class="lbl-text">'.esc_html__('Tipo de Propiedad','kmimos').':</label>
            <label class="lbl-ui">
              	<select id="propiedad" name="propiedad" class="input">
                    <option value="1" '.selected($atributos['propiedad'], 1, false).'>Casa</option>
                    <option value="2" '.selected($atributos['propiedad'], 2, false).'>Departamento</option>
                </select>
            </label> 
        </section>
   
        <section>
            <label class="lbl-text">'.esc_html__('Posee Áreas Verdes','kmimos').'</label>
            <label class="lbl-ui">
	            <select id="green" name="green" class="input">
	                <option value="0" '.selected($atributos['green'], 0, false).'>No</option>
	                <option value="1" '.selected($atributos['green'], 1, false).'>Si</option>
	        	</select>
            </label> 
        </section> 

        <section>
            <label class="lbl-text">'.esc_html__('Posee Patio','kmimos').'</label>
            <label class="lbl-ui">
                <select id="yard" name="yard" class="input">
                    <option value="0" '.selected($atributos['yard'], 0, false).'>No</option>
                    <option value="1" '.selected($atributos['yard'], 1, false).'>Si</option>
                </select>
            </label> 
        </section>   

        <section>
            <label for="ages_accepted" class="lbl-text">'.esc_html__('Mascotas en Casa','kmimos').':</label>
            <label class="lbl-ui">
                <input type"text" id="num_mascotas_casa" name="num_mascotas_casa" class="input" value="'.$cuidador->num_mascotas.'"> 
            </label> 
        </section> 
    </div>
           
    <div class="inputs_containers row_4" style="padding-bottom: 0px;"> 

	    <section>
	         <label for="ages_accepted" class="lbl-text">'.esc_html__('Tamaños de mis mascotas','kmimos').':</label>
            <label class="lbl-ui">
	          	'.$mascotas_cuidador_str.'     
            </label>
	   </section>

	    <section>
	        <label for="behavior_accepted" class="lbl-text">'.esc_html__('Conductas Aceptadas','kmimos').':</label>
            <label class="lbl-ui">
	          	'.$comportamientos_aceptados_str.'     
            </label>
	   </section>

	    <section>
	        <label for="behavior_accepted" class="lbl-text">'.esc_html__('Tama&ntilde;os Aceptados','kmimos').':</label>
            <label class="lbl-ui">
	          	'.$tamanos_aceptados_str.'     
            </label>
	   </section>

	    <section>
	        <label for="ages_accepted" class="lbl-text">'.esc_html__('Edades Aceptadas','kmimos').':</label>
            <label class="lbl-ui">
	          	'.$edades_aceptadas_str.'     
            </label>
	   </section>

    </div>
           
    <div class="inputs_containers row_3" style="padding-bottom: 0px;">            
                         
       <section> 
            <label for="estado" class="lbl-text">'.esc_html__('Estado','kmimos').':</label>
            <label class="lbl-ui">
                <select id="estado" id="estado" name="estado" class="input">
                  '.$estados.'
                </select>
            </label> 
       </section>  

        <section>
        <label for="delegacion" class="lbl-text">'.esc_html__('Delegación','kmimos').':</label>
            <label class="lbl-ui">
            <select id="delegacion" name="delegacion" class="input">
                '.$muni.'
            </select>
            </label> 
        </section>  

        <section> 
            <label for="ages_accepted" class="lbl-text">'.esc_html__('Dirección','kmimos').':</label>
            <label class="lbl-ui">
              <input  type="text" id="direccion" name="direccion" class="input" value="'.$cuidador->direccion.'" />
            </label> 
        </section>

    </div>';
?>