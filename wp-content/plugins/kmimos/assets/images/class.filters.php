<?php

class kmimos_filters_class extends WP_Widget {

	public function __construct(){

		$options = array(

			"classname"=>"filter_css",

			"description"=>"Selector de filtros para búsqueda de Cuidadores"

		);

		$this->WP_Widget('filters_id','Kmimos Filtro Cuidadores',$options);

//        parent::__construct( false, __( 'My New Widget Title', 'textdomain' ) );

	}

	public function form($instance){

		$defaults = array(

			"titulo"=>"Filtrar Cuidadores"

		);

		$instance = wp_parse_args((array)$instance, $defaults);

		$titulo = esc_attr($instance["titulo"]);

?>

		<p>Título: <input type="text" name="<?php echo $this->get_field_name("titulo");?>" value="<?php echo $titulo;?>" class="widefat" /></p>

<?php

	}

	public function update($new_instance, $old_instance){

		$instance = $old_instance;

		$instance['titulo'] = strip_tags($new_instance['titulo']);

        return $instance;

	}

	public function widget($args, $instance){

		extract($args);

		$titulo = apply_filters('widget_title',$instance['titulo']);

        echo '<div id="filtros"></div>';

		echo $before_widget;

		echo $before_title.$titulo.$after_title;

		$tamanos = array('1'=>'Pequeñas','2'=>'Medianas','3'=>'Grandes','4'=>'Gigantes');

		$pais = 'mx'; //substr($_SERVER['REQUEST_URI'],0,2);

		$estados = array( "mx-01"=>"Aguascalientes", "mx-02"=>"Baja California", "mx-03"=>"Baja California Sur", 

			"mx-04"=>"Campeche", "mx-05"=>"Coahuila de Zaragoza", "mx-06"=>"Colima", "mx-07"=>"Chiapas", "mx-08"=>"Chihuahua", 

			"mx-09"=>"Distrito Federal", "mx-10"=>"Durango", "mx-11"=> "Guanajuato", "mx-12"=>"Guerrero", "mx-13"=>"Hidalgo", 

			"mx-14"=>"Jalisco", "mx-15"=>"México", "mx-16"=>"Michoacán de Ocampo", "mx-17"=>"Morelos", "mx-18"=>"Nayarit", 

			"mx-19"=>"Nuevo León", "mx-20"=>"Oaxaca", "mx-21"=>"Puebla", "mx-22"=>"Querétaro", "mx-23"=>"Quintana Roo", 

			"mx-24"=>"San Luis Potosí", "mx-25"=>"Sinaloa", "mx-26"=>"Sonora", "mx-27"=>"Tabasco", "mx-28"=>"Tamaulipas", 

			"mx-29"=>"Tlaxcala", "mx-30"=>"Veracruz de Ignacio de la Llave", "mx-31"=>"Yucatán", "mx-32"=>"Zacatecas" );



		$direccion = (($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'];



        // Establece los rangos de los parámetros de los cuidadores según los parámetros recibidos

        $rangos = kmimos_rangos_valores($_GET);

        $servicios = (isset($_GET['servicio_cuidador']))? $_GET['servicio_cuidador']:array();

        $latitud = $_GET['latitud'];

        $longitud =$_GET['longitud'];

        $distancia = $_GET['distancia'];

        $acepta = (isset($_GET['acepta_mascotas']))? $_GET['acepta_mascotas']:array();

        $tiene = (isset($_GET['tiene_mascotas']))? $_GET['tiene_mascotas']:array();

        $conductas = (isset($_GET['acepta_conductas']))? $_GET['acepta_conductas']:array();

        $tipo_busqueda = (isset($_GET['tipo_busqueda']))? $_GET['tipo_busqueda']: 'otra-localidad';

        switch($tipo_busqueda){

        case 'mi-ubicacion':

            if($latitud =='' && $longitud=='' && $distancia==''){

                $tipo_busqueda = 'otra-localidad';

                break;

            }

            if($distancia=='' && $distancia==0) $distancia=20;

            break;

        case 'otra-localidad':

            $distancia=0;

            if(count($ubicaciones)==0) $ubicaciones[]= $pais;

            break;

        }

        $lista = (isset($_GET['ubicacion_cuidador']))?implode("','",$_GET['ubicacion_cuidador']):'';

?>

<script>

jQuery.noConflict(); 

jQuery(document).ready(document).ready(function() {

    

    var distancia=<?php echo $distancia; ?>;



    jQuery("#otra-localidad").on('click',function(){

        jQuery("#ubicacion_usuario").addClass("hide");

        jQuery("#ubicacion_cuidador_main").removeClass("hide");

        jQuery("#distancia").prop("name","");

    });

    

    jQuery("#mi-ubicacion").on('click',function(){

        jQuery("#ubicacion_usuario").removeClass("hide");

        jQuery("#ubicacion_cuidador_main").addClass("hide");

        jQuery("#distancia").prop("name","distancia");

        if(jQuery("#distancia").val()==0) {

            distancia=20;

            jQuery("#rango_distancia").slider("value", distancia);

            jQuery("#lbldistancia").val( distancia + " Km." );

            jQuery("#distancia").val( distancia );

        }

    });

    

    var url = window.location.href.split("#");

    console.log('posicion='+url[1]+'. Largo='+url.length);

    var posicion ='cuidadores'; // En dispositivos móviles posiciona en los cuidadores

    if(jQuery("#pf-primary-nav-button").css('display')=='none') posicion ='mapa';   //Si está expandida posiciona en mapa

    if(url.length==1) jQuery(location).attr('href','#'+posicion);

	var country = '<?php echo $pais; ?>';

	var urlSever = '<?php echo $direccion; ?>/wp-content/plugins/kmimos/app-server.php';

    var activos = ['<?php echo $lista; ?>'];

    console.log('Activos %O', activos);

	var topeMinimoHospedaje = 100;

	var topeMaximoHospedaje = 300;

	var precioMinimoHospedaje = 160;

	var precioMaximoHospedaje = 240;



	var topeMinimoGuarderia = 100;

	var topeMaximoGuarderia = 300;

	var precioMinimoGuarderia = 160;

	var precioMaximoGuarderia = 240;



	var topeMinimoAdiestramiento = 100;

	var topeMaximoAdiestramiento = 300;

	var precioMinimoAdiestramiento = 160;

	var precioMaximoAdiestramiento = 240;





	jQuery("#desde-hospedaje").datepicker();

	jQuery("#hasta-hospedaje").datepicker();

	jQuery("#desde-guarderia").datepicker();

	jQuery("#hasta-guarderia").datepicker();

	jQuery("#desde-adiestramiento").datepicker();

	jQuery("#hasta-adiestramiento").datepicker();



    var urlSever = '<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/app-server.php';

    var locs = jQuery("#ubicacion_cuidador");

    var pais =locs.attr("data-location");

    var ubicaciones = '';

    

    // Busca los estados del país

    jQuery.post(urlSever,{action: 'get-locations', location: pais },function(){

        locs.prop('disabled',true);

    }, "json")

    .success(function(data){

        jQuery.each(data, function(key,value){

            var selected = '';

            if (jQuery.inArray(key,activos)>=0){

                selected = ' selected';

                console.log('Activo estado o municipio:'+value);

                if(ubicaciones!='') ubicaciones = ubicaciones + ', ';

                ubicaciones = ubicaciones + value;

            }

            if(key.length==5) locs.append('<option value="'+key+'" class="edo"'+selected+'>'+value+' (todo el estado)</option>');

            if(key.length==9) locs.append('<option value="'+key+'" class="mpo"'+selected+'>'+value+'</option>');

        });

        locs.prop('disabled',false);

        console.log('Actualizado selector de estados y municipios');

        jQuery("#ubicacion-actual").html(ubicaciones);

        console.log("Ubicaciones="+ubicaciones);

    });

    

    jQuery( "#ubicacion_cuidador" ).select2({

        tags: true,

        tokenSeparators: [',', ' '],

        width: 260,

        minimumInputLength: 2

    });



    jQuery( "#rango_precios" ).slider({

        range: true,

        min: <?php echo $rangos->preciobase; ?>, 

        max: <?php echo $rangos->preciotop; ?>,

        values: [ <?php echo $rangos->preciomin; ?>, <?php echo $rangos->preciomax; ?> ],

        slide: function( event, ui ) {

            jQuery( "#precios" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

            jQuery( "#precio_minimo" ).val( ui.values[ 0 ] );

            jQuery( "#precio_maximo" ).val( ui.values[ 1 ] );

            jQuery( "#seleccionar_precios" ).attr("checked",true);

            jQuery( "#precio_minimo" ).attr("name","precio_minimo");

            jQuery( "#precio_maximo" ).attr("name","precio_maximo");

        }

    });

    

    jQuery( "#rango_experiencia" ).slider({

        range: true,

        min: <?php echo $rangos->experbase; ?>,

        max: <?php echo $rangos->expertop; ?>,

        values: [ <?php echo $rangos->expermin; ?>, <?php echo $rangos->expermax; ?> ],

        slide: function( event, ui ) {

            jQuery( "#experiencia" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );

            jQuery( "#experiencia_minima" ).val( ui.values[ 0 ] );

            jQuery( "#experiencia_maxima" ).val( ui.values[ 1 ] );

            jQuery( "#seleccionar_experiencia" ).attr("checked",true);

            jQuery( "#experiencia_minima" ).attr("name","experiencia_minima");

            jQuery( "#experiencia_maxima" ).attr("name","experiencia_maxima");

        }

    });

    

    jQuery( "#rango_distancia" ).slider({

        range: "min",

        min: 0,

        max: 50,

        value: distancia,

        slide: function( event, ui ) {

            jQuery( "#lbldistancia" ).val( ui.value + ' Km.' );

           jQuery( "#distancia" ).val( ui.value );

        }

    });



    jQuery( "#rango_valoracion" ).slider({

        range: true,

        min: <?php echo $rangos->valorbase; ?>,

        max: <?php echo $rangos->valortop; ?>,

        values: [ <?php echo $rangos->valormin; ?>, <?php echo $rangos->valormax; ?> ],

        slide: function( event, ui ) {

            jQuery( "#valoracion" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );

            jQuery( "#valoracion_minima" ).val( ui.values[ 0 ] );

            jQuery( "#valoracion_maxima" ).val( ui.values[ 1 ] );

            jQuery( "#seleccionar_valoracion" ).attr("checked",true);

            jQuery("#valoracion_minima").attr("name","valoracion_minima");

            jQuery("#valoracion_maxima").attr("name","valoracion_maxima");

        }

    });



    // Inicializa rango de precios

    jQuery( "#precios" ).val( "$" + jQuery( "#rango_precios" ).slider( "values", 0 ) +

      " - $" + jQuery( "#rango_precios" ).slider( "values", 1 ) );

    

    // Inicializa rango de experiencia

    jQuery( "#experiencia" ).val( jQuery( "#rango_experiencia" ).slider( "values", 0 ) +

      " - " + jQuery( "#rango_experiencia" ).slider( "values", 1 ) );

    

    // Inicializa rango de distancia

    jQuery( "#lbldistancia" ).val( jQuery( "#rango_distancia" ).slider( "value" ) + " Km." );

   

    // Inicializa rango de valoraciones

    jQuery( "#valoracion" ).val( jQuery( "#rango_valoracion" ).slider( "values", 0 ) +

      " - " + jQuery( "#rango_valoracion" ).slider( "values", 1 ) );

    

    jQuery( "#seleccionar_precios" ).change(function(){

        if(jQuery(this).attr("checked")) {

           jQuery("#precio_minimo").attr("name","precio_minimo");

           jQuery("#precio_maximo").attr("name","precio_maximo");

        }

        else {

           jQuery("#precio_minimo").attr("name","");

           jQuery("#precio_maximo").attr("name","");

        }

    });



    jQuery( "#seleccionar_experiencia" ).change(function(){

        if(jQuery(this).attr("checked")) {

           jQuery("#experiencia_minima").attr("name","experiencia_minima");

           jQuery("#experiencia_maxima").attr("name","experiencia_maxima");

        }

        else {

           jQuery("#experiencia_minima").attr("name","");

           jQuery("#experiencia_maxima").attr("name","");

        }

    });



    jQuery( "#seleccionar_valoracion" ).change(function(){

        if(jQuery(this).attr("checked")) {

           jQuery("#valoracion_minima").attr("name","valoracion_minima");

           jQuery("#valoracion_maxima").attr("name","valoracion_maxima");

        }

        else {

           jQuery("#valoracion_minima").attr("name","");

           jQuery("#valoracion_maxima").attr("name","");

        }

    });



/*    // Posiciona la página dependiendo de la variable posicion

    jQuery("body").css('margin-top',0);

    location.href='#'+posicion;

//    jQuery(location).attr('href','#cuidadores');

    jQuery("body").delay(1000).css('margin-top',100);

*/    

    jQuery("#boton-izquierda").on('click', function(e){

        e.preventDefault();

        jQuery("#cuidadores").css('margin-bottom','0px');

        switch(posicion){

            case 'cuidadores':  // Mapa/Filtros  ->  Lista/Filtros

                posicion='mapa';

                // Botón izquierdo cambia de Mapa a Lista

                jQuery("#titulo-izquierda").html('Lista');

                jQuery("#icono-izquierda").removeClass('dashicons-location-alt');

                jQuery("#icono-izquierda").addClass('dashicons-id');

                // Botón derecho queda inalterado

//                jQuery("#titulo-derecha").html('Filtros');

                jQuery(".search.search-results").css("marginTop",93);

                break;

            case 'filtros':     // Lista/Mapa  ->  Mapa/Filtros

                posicion='cuidadores';

                // Botón izquierdo cambia de Lista a Mapa

                jQuery("#titulo-izquierda").html('Mapa');

                jQuery("#icono-izquierda").removeClass('dashicons-id');

                jQuery("#icono-izquierda").addClass('dashicons-location-alt');

                // Botón derecho cambia de Mapa a Filtros

                jQuery("#titulo-derecha").html('Filtros');

                jQuery("#icono-derecha").removeClass('dashicons-location-alt');

                jQuery("#icono-derecha").addClass('dashicons-admin-settings');

                jQuery(".search.search-results").css("marginTop",93);

                break;

            case 'mapa':        // Lista/Filtros  ->  Mapa/Filtros

                posicion='cuidadores';

                // Botón izquierdo cambia de Lista a Mapa

                jQuery("#titulo-izquierda").html('Mapa');

                jQuery("#icono-izquierda").removeClass('dashicons-id');

                jQuery("#icono-izquierda").addClass('dashicons-location-alt');

                // Botón derecho queda inalterado

//                jQuery("#titulo-derecha").html('Filtros');

                break;

        }

        jQuery("#boton-izquierda").prop('href','#'+posicion)

        jQuery("body").css('margin-top',0);

        jQuery(location).attr('href','#'+posicion);

        jQuery("body").css('margin-top',100);

    });



    jQuery("#boton-derecha").on('click', function(e){

        e.preventDefault();

        jQuery("#cuidadores").css('margin-bottom','0px');

        switch(posicion){

            case 'cuidadores':  // Mapa/Filtros  ->  Lista/Mapa

                posicion='filtros';

                // Botón izquierdo cambia de Mapa a Lista

                jQuery("#titulo-izquierda").html('Lista');

                jQuery("#icono-izquierda").removeClass('dashicons-location-alt');

                jQuery("#icono-izquierda").addClass('dashicons-id');

                // Botón derecho cambia de Filtros a Mapa

                jQuery("#titulo-derecha").html('Mapa');

                jQuery("#icono-derecha").removeClass('dashicons-admin-settings');

                jQuery("#icono-derecha").addClass('dashicons-location-alt');

                break;

            case 'filtros':     // Lista/Mapa  ->  Lista/Filtros

                posicion='mapa';

                // Botón izquierdo queda inalterado

                // Botón derecho cambia de Mapa a Filtros

                jQuery("#titulo-derecha").html('Filtros');

                jQuery("#icono-derecha").removeClass('dashicons-location-alt');

                jQuery("#icono-derecha").addClass('dashicons-admin-settings');

                break;

            case 'mapa':        // Lista/Filtros  ->  Lista/Mapa

                posicion='filtros';

                // Botón izquierdo queda inalterado

                // Botón derecho cambia de Filtros a Mapa

                jQuery("#titulo-derecha").html('Mapa');

                jQuery("#icono-derecha").removeClass('dashicons-admin-settings');

                jQuery("#icono-derecha").addClass('dashicons-location-alt');

                break;

        }

        jQuery("#boton-derecha").prop('href','#'+posicion)

        jQuery("body").css('margin-top',0);

        jQuery(location).attr('href','#'+posicion);

        jQuery("body").css('margin-top',100);

        



    });



    jQuery(".accordion").each(function( index ){

        jQuery(this).on('click', function(e){

            jQuery(this).toggle("active");

            jQuery(this).next().toggle("show");

            e.preventDefault();

        });

    });

    

    ('click', function(e){

        var acc = jQuery(this);

        for (var i = 0; i < acc.length; i++) {

            acc[i].onclick = function(){

                

                this.classList.toggle("active");

                this.nextElementSibling.classList.toggle("show");

            }

        }



        e.preventDefault();

    });



    //    if(url.length>1) jQuery("#cuidadores").removeClass('mb125');

//    jQuery("#boton-izquierda").delay(5000).trigger( "click" );

/*    var tope = jQuery(document).scrollTop();

    jQuery(document).animate({

        scrollTop: tope-60

    }, 2000);

    console.log('tope='+tope+', nuevo tope='+jQuery(document).scrollTop());*/

});

</script>

<link rel='stylesheet' id='dashicons-css'  href='<?php echo get_home_url(); ?>/wp-includes/css/dashicons.min.css?ver=4.4.3' media='all' />

<style>

button.accordion {

    background-color: #eee;

    color: #444;

    cursor: pointer;

    padding: 18px;

    width: 100%;

    border: none;

    text-align: left;

    outline: none;

    font-size: 15px;

    transition: 0.4s;

}



button.accordion.active, button.accordion:hover {

    background-color: #ddd;

}



div.panel {

    padding: 0 18px;

    display: none;

    background-color: white;

}



div.panel.show {

    display: block;

}

</style>

<div id="sticky" style="margin-top:46px;">

    <a id="boton-izquierda" href="#mapa"><span id="icono-izquierda" class="dashicons dashicons-location-alt"></span><div id="titulo-izquierda">Mapa</div></a>

    <div id="contenido-centro"><span id="icono-centro" class="vc_icon_element-icon fa fa-map-marker"></span><div id="ubicacion-actual"></div></div>

    <a id="boton-derecha" href="#filtros"><span id="icono-derecha" class="dashicons dashicons-admin-settings"></span><div id="titulo-derecha">Filtros</div></a>

</div>

<form id="form-filters" method="get" action="<?php echo $direccion.'/'; ?>" data-ajax="false" novalidate="novalidate">

<input type="submit" class="boton_aplicar_filtros" value="Aplicar Filtros">

<button class="accordion">Ubicación</button>

<div class="panel">

    <div id="selector_tipo" class="izquierda mt10"> <input type="radio" name="tipo_busqueda" id="mi-ubicacion" value="mi-ubicacion" class="ml8"<?php if($tipo_busqueda=='mi-ubicacion') echo ' checked'; ?>> <label for="mi-ubicacion">Mi ubicación </label> <input type="radio" name="tipo_busqueda" id="otra-localidad" value="otra-localidad" class="ml8"<?php if($tipo_busqueda=='otra-localidad') echo ' checked'; ?>> <label for="otra-localidad">Otra localidad</label></div>

    <div id="ubicacion_usuario" class="mt10 clr<?php if($tipo_busqueda=='otra-localidad') echo ' hide'; ?>">

        <div class="grupo_rango">

        <label for="latitud">Latitud:</label><br>

        <input type="number" id="latitud" name="latitud" value="<?php echo $latitud; ?>">

        </div>

        <div class="grupo_rango">

        <label for="latitud">Longitud:</label><br>

        <input type="number" id="longitud" name="longitud" value="<?php echo $longitud; ?>">

        </div>

        <div id="rango_distancia_main" style="margin-bottom: 20px;">

            <label for="seleccionar_distancia">Distancia máxima al cuidador:</label>

            <input type="text" id="lbldistancia" readonly style="border:0; color:#f6931f; font-weight:bold; width: 60px; text-align: center; background-color: transparent;"><input type="hidden" id="distancia" name="distancia" value="<?php echo $distancia; ?>">

            <div id="rango_distancia"></div>

        </div>

    </div>

    <div id="ubicacion_cuidador_main" class="mt10 clr<?php if($tipo_busqueda=='mi-ubicacion') echo ' hide'; ?>">

        <div class="pftitlefield">Ubicación de los Cuidadores</div>

        <label for="ubicacion_cuidador" class="lbl-ui select">

            <select multiple="multiple" id="ubicacion_cuidador" name="ubicacion_cuidador[]" class="pf-special-selectbox select2-hidden-accessible" data-pf-plc="Seleccione ubicación cuidador" tabindex="-1" aria-hidden="true" data-location="mx">

            </select>

        </label>    

    </div>

</div>

<button class="accordion">Rangos</button>

<div class="panel">

    <div id="rango_precios_main" style="margin-bottom: 20px;">

        <label for="seleccionar_precios">Rango de Precios:</label>

        <input type="text" id="precios" readonly style="border:0; color:#f6931f; font-weight:bold; width: 100px; text-align: center; background-color: transparent;"><input type="hidden" id="precio_minimo" name="precio_minimo" value="<?php echo $rangos->preciomin; ?>"><input type="hidden" id="precio_maximo" name="precio_maximo" value="<?php echo $rangos->preciomax; ?>">

        <div id="rango_precios"></div>

    </div>

    <div id="rango_experiencia_main" style="margin-bottom: 20px;">

        <label for="seleccionar_experiencia">Años de Experiencia:</label>

        <input type="text" id="experiencia" readonly style="border:0; color:#f6931f; font-weight:bold; width: 60px; text-align: center; background-color: transparent;"><input type="hidden" id="experiencia_minima" name="experiencia_minima" value="<?php echo $rangos->expermin; ?>"><input type="hidden" id="experiencia_maxima" name="experiencia_maxima" value="<?php echo $rangos->expermax; ?>">

        <div id="rango_experiencia"></div>

    </div>

    <div id="rango_valoracion_main" style="margin-bottom: 20px;">

        <label for="seleccionar_valoracion">Valoración de Clientes:</label>

        <input type="text" id="valoracion" readonly style="border:0; color:#f6931f; font-weight:bold; width: 80px; text-align: center; background-color: transparent;"><input type="hidden" id="valoracion_minima" name="valoracion_minima" value="<?php echo $rangos->valormin; ?>"><input type="hidden" id="valoracion_maxima" name="valoracion_maxima" value="<?php echo $rangos->valormax; ?>">

        <div id="rango_valoracion"></div>

    </div>

</div>

<button class="accordion">Servicios Reservables</button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que ofrezcan:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

        <li><input type="checkbox" id="servicio_hospedaje" name="servicio_cuidador[]" value="hospedaje"<?php if(in_array('hospedaje',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_hospedaje" class="lbl-ui select">Hospedaje (cuidado dia y noche)</label></li>

        <li><input type="checkbox" id="servicio_guarderia" name="servicio_cuidador[]" value="guarderia"<?php if(in_array('guarderia',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_guarderia" class="lbl-ui select">Guardería (cuidado durante el dia)</label></li>

        <li><input type="checkbox" id="servicio_paseos" name="servicio_cuidador[]" value="paseos"<?php if(in_array('paseos',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_paseos" class="lbl-ui select">Paseos</label></li>

        <li><input type="checkbox" id="servicio_adiestramiento" name="servicio_cuidador[]" value="adiestramiento"<?php if(in_array('adiestramiento',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_adiestramiento" class="lbl-ui select">Adiestramiento de Obediencia</label></li>

        <li><input type="checkbox" id="servicio_peluqueria" name="servicio_cuidador[]" value="peluqueria"<?php if(in_array('peluqueria',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_peluqueria" class="lbl-ui select">Peluqueria (Corte de Pelo y Uñas)</label></li>

       <li><input type="checkbox" id="servicio_bano" name="servicio_cuidador[]" value="bano"<?php if(in_array('bano',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_bano" class="lbl-ui select">Baño y Secado</label></li>

    </ul><br>

</div>

<button class="accordion">Servicios Adicionales</button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que ofrezcan:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

       <li><input type="checkbox" id="servicio_transporte" name="servicio_cuidador[]" value="transporte"<?php if(in_array('transporte',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_transporte" class="lbl-ui select">Transportación Sencilla</label></li>

       <li><input type="checkbox" id="servicio_transporte2" name="servicio_cuidador[]" value="transporte2"<?php if(in_array('transporte2',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_transporte2" class="lbl-ui select">Transportación Redonda</label></li>

       <li><input type="checkbox" id="servicio_veterinario" name="servicio_cuidador[]" value="veterinario"<?php if(in_array('veterinario',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_veterinario" class="lbl-ui select">Visita al Veterinario</label></li>

       <li><input type="checkbox" id="servicio_limpieza" name="servicio_cuidador[]" value="limpieza"<?php if(in_array('limpieza',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_limpieza" class="lbl-ui select">Limpieza Dental</label></li>

       <li><input type="checkbox" id="servicio_acupuntura" name="servicio_cuidador[]" value="acupuntura"<?php if(in_array('acupuntura',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_acupuntura" class="lbl-ui select">Acupuntura</label></li>

    </ul><br>

</div>

<button class="accordion">Tamaños Aceptados</button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que acepten tamaños:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

        <li><input type="checkbox" id="acepta_pequeno" name="acepta_mascotas[]" value="s"<?php if(in_array('s',$acepta)) echo 'checked="checked"'; ?>><label for="acepta_pequeno" class="lbl-ui select">Mascotas Pequeñas (menos de 10Kg)</label></li>

        <li><input type="checkbox" id="acepta_mediano" name="acepta_mascotas[]" value="m"<?php if(in_array('m',$acepta)) echo 'checked="checked"'; ?>><label for="acepta_mediano" class="lbl-ui select">Mascotas Medianas (de 11 a 25Kg)</label></li>

        <li><input type="checkbox" id="acepta_grande" name="acepta_mascotas[]" value="l"<?php if(in_array('l',$acepta)) echo 'checked="checked"'; ?>><label for="acepta_grande" class="lbl-ui select">Mascotas Grandes (de 26 a 45Kg)</label></li>

        <li><input type="checkbox" id="acepta_gigante" name="acepta_mascotas[]" value="x"<?php if(in_array('x',$acepta)) echo 'checked="checked"'; ?>><label for="acepta_gigante" class="lbl-ui select">Mascotas Gigantes (más de 45Kg)</label></li>

    </ul><br>

</div>

<button class="accordion">Mascotas de Cuidadores</button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que tengan mascotas:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

        <li><input type="checkbox" id="tiene_pequeno" name="tiene_mascota[]" value="s"<?php if(in_array('s',$tiene)) echo 'checked="checked"'; ?>><label for="tiene_pequeno" class="lbl-ui select">Mascotas Pequeñas (menos de 10Kg)</label></li>

        <li><input type="checkbox" id="tiene_mediano" name="tiene_mascota[]" value="m"<?php if(in_array('m',$tiene)) echo 'checked="checked"'; ?>><label for="tiene_mediano" class="lbl-ui select">Mascotas Medianas (de 11 a 25Kg)</label></li>

        <li><input type="checkbox" id="tiene_grande" name="tiene_mascota[]" value="l"<?php if(in_array('l',$tiene)) echo 'checked="checked"'; ?>><label for="tiene_grande" class="lbl-ui select">Mascotas Grandes (de 26 a 45Kg)</label></li>

        <li><input type="checkbox" id="tiene_gigante" name="tiene_mascota[]" value="x"<?php if(in_array('x',$tiene)) echo 'checked="checked"'; ?>><label for="tiene_gigante" class="lbl-ui select">Mascotas Gigantes (más de 45Kg)</label></li>

    </ul><br>

</div>

<button class="accordion">Cunductas Aceptadas</button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que acepten conductas:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

        <li><input type="checkbox" id="acepta_sociables" name="acepta_conductas[]" value="s"<?php if(in_array('s',$conductas)) echo 'checked="checked"'; ?>><label for="tiene_pequeno" class="lbl-ui select">Sociables</label></li>

        <li><input type="checkbox" id="acepta_no_sociables" name="acepta_conductas[]" value="n"<?php if(in_array('n',$conductas)) echo 'checked="checked"'; ?>><label for="tiene_mediano" class="lbl-ui select">No Sociables</label></li>

        <li><input type="checkbox" id="acepta_agresiva_mascotas" name="acepta_conductas[]" value="m"<?php if(in_array('m',$conductas)) echo 'checked="checked"'; ?>><label for="tiene_grande" class="lbl-ui select">Agresivas con Otras Mascotas</label></li>

        <li><input type="checkbox" id="acepta_agresiva_humanos" name="acepta_conductas[]" value="h"<?php if(in_array('h',$conductas)) echo 'checked="checked"'; ?>><label for="tiene_gigante" class="lbl-ui select">Agresivas con Humanos</label></li>

    </ul><br>

</div>

<p><strong>Ordenar resultados por:</strong></p>

<select name="orderby"><?php

if(isset($_GET['latitud']) && isset($_GET['longitud'])){ ?>

    <option value="distance_asc"<?php if($ordenar=='distance_asc') echo ' selected';?>>Distancia al cuidador de cerca a lejos</option>

    <option value="distance_desc"<?php if($ordenar=='distance_desc') echo ' selected';?>>Distancia al cuidador de lejos a cerca</option><?php

} ?>

    <option value="price_asc"<?php if($ordenar=='price_asc') echo ' selected';?>>Precio del Servicio de menor a mayor</option>

    <option value="price_desc"<?php if($ordenar=='price_desc') echo ' selected';?>>Precio del Servicio de mayor a menor</option>

    <option value="experience_asc"<?php if($ordenar=='experience_asc') echo ' selected';?>>Experiencia de menos a más años</option>

    <option value="experience_desc"<?php if($ordenar=='experience_desc') echo ' selected';?>>Experiencia de más a menos años</option>

    <option value="name_asc"<?php if($ordenar=='name_asc') echo ' selected';?>>Nombre del Cuidador de la A a la Z</option>

    <option value="name_desc"<?php if($ordenar=='name_desc') echo ' selected';?>>Nombre del Cuidador de la Z a la A</option>

    <option value="rating_asc"<?php if($ordenar=='rating_asc') echo ' selected';?>>Valoración de menor a mayor</option>

    <option value="rating_desc"<?php if($ordenar=='rating_desc') echo ' selected';?>>Valoración de mayor a menor</option>

    </select>

    <br><p></p>

    <input type="submit" class="boton_aplicar_filtros" value="Aplicar Filtros">

    <input type="hidden" name="s" value="">

    <input type="hidden" name="serialized" value="1">

    <input type="hidden" name="action" value="pfs">

</form>

<!--script>

var acc = document.getElementsByClassName("accordion");

var i;



for (i = 0; i < acc.length; i++) {

    acc[i].onclick = function(){

        this.classList.toggle("active");

        this.nextElementSibling.classList.toggle("show");

    }

}

</script-->

<?php 

		echo $after_widget;

        echo '<div id="cuidadores"></div>';

	}

}

?>