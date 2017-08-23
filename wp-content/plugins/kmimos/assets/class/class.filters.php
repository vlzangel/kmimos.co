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

        $adicionales = (isset($_GET['servicio_adicional']))? $_GET['servicio_adicional']:array();

        $latitud = $_GET['latitud'];

        $longitud =$_GET['longitud'];

        $distancia = $_GET['distancia'];

        $ordenar = ($_GET['orderby']!='')? $_GET['orderby']:'rating_desc';

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

    <?php
        echo "/*";
            print_r($_GET);
        echo "*/";
    ?>

	var topeMinimoPrecio = {hospedaje: '<?php echo $rangos->hospedajebase; ?>', guarderia: '<?php echo $rangos->guarderiabase; ?>', paseos: '<?php echo $rangos->paseosbase; ?>', adiestramiento: '<?php echo $rangos->adiestramientobase; ?>', peluqueria: '<?php echo $rangos->peluqueriabase; ?>', bano: '<?php echo $rangos->banobase; ?>'};

	var topeMaximoPrecio = {hospedaje: '<?php echo $rangos->hospedajetop; ?>', guarderia: '<?php echo $rangos->guarderiatop; ?>', paseos: '<?php echo $rangos->paseostop; ?>', adiestramiento: '<?php echo $rangos->adiestramientotop; ?>', peluqueria: '<?php echo $rangos->peluqueriatop; ?>', bano: '<?php echo $rangos->banotop; ?>'};

<?php

        if(isset($_GET['precio_minimo']) && $_GET['precio_minimo']!=''){ ?>

	var valorMinimoPrecio = {hospedaje: '<?php echo $rangos->hospedajemin; ?>', guarderia: '<?php echo $rangos->guarderiamin; ?>', paseos: '<?php echo $rangos->paseosmin; ?>', adiestramiento: '<?php echo $rangos->adiestramientomin; ?>', peluqueria: '<?php echo $rangos->peluqueriamin; ?>', bano: '<?php echo $rangos->banomin; ?>'};

<?php

        }

        else { ?>

	var valorMinimoPrecio = {hospedaje: '<?php echo $rangos->hospedajebase; ?>', guarderia: '<?php echo $rangos->guarderiabase; ?>', paseos: '<?php echo $rangos->paseosbase; ?>', adiestramiento: '<?php echo $rangos->adiestramientobase; ?>', peluqueria: '<?php echo $rangos->peluqueriabase; ?>', bano: '<?php echo $rangos->banobase; ?>'};

<?php

        }

        if(isset($_GET['precio_maximo']) && $_GET['precio_maximo']!=''){ ?>

	var valorMaximoPrecio = {hospedaje: '<?php echo $rangos->hospedajemax; ?>', guarderia: '<?php echo $rangos->guarderiamax; ?>', paseos: '<?php echo $rangos->paseosmax; ?>', adiestramiento: '<?php echo $rangos->adiestramientomax; ?>', peluqueria: '<?php echo $rangos->peluqueriamax; ?>', bano: '<?php echo $rangos->banomax; ?>'};

<?php

        }

        else { ?>

	var valorMaximoPrecio = {hospedaje: '<?php echo $rangos->hospedajetop; ?>', guarderia: '<?php echo $rangos->guarderiatop; ?>', paseos: '<?php echo $rangos->paseostop; ?>', adiestramiento: '<?php echo $rangos->adiestramientotop; ?>', peluqueria: '<?php echo $rangos->peluqueriatop; ?>', bano: '<?php echo $rangos->banotop; ?>'};

<?php

        }

?>    

    var servicioPrincipal = 'hospedaje';



	var topeMinimoExperiencia = '<?php echo $rangos->experbase; ?>';

	var topeMaximoExperiencia = '<?php echo $rangos->expertop; ?>';

	var valorMinimoExperiencia = '<?php echo ((isset($_GET['experiencia_minima']))? $rangos->expermin: $rangos->experbase); ?>';

	var valorMaximoExperiencia = '<?php echo ((isset($_GET['experiencia_maxima']))? $rangos->expermax: $rangos->expertop); ?>';



	var topeMinimoValoracion = '<?php echo $rangos->valorbase; ?>';

	var topeMaximoValoracion = '<?php echo $rangos->valortop; ?>';

	var valorMinimoValoracion = '<?php echo ((isset($_GET['valoracion_minima']))? $rangos->valormin: $rangos->valorbase); ?>';

	var valorMaximoValoracion = '<?php echo ((isset($_GET['valoracion_maxima']))? $rangos->valormax: $rangos->valortop); ?>';





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

        if(ubicaciones=='') ubicaciones = 'Todo el país'

        jQuery("#ubicacion-actual").html(ubicaciones);

        console.log("Ubicaciones="+ubicaciones);

    });

    

    jQuery( "#ubicacion_cuidador" ).select2({

        tags: true,

        tokenSeparators: [',', ' '],

        width: 250,

        minimumInputLength: 2

    });



    jQuery( "#rango_hospedaje" ).slider({

        range: true,

        min: topeMinimoPrecio['hospedaje'], 

        max: topeMaximoPrecio['hospedaje'],

        values: [ valorMinimoPrecio['hospedaje'], valorMaximoPrecio['hospedaje'] ],

        slide: function( event, ui ) {

            jQuery( "#precios_hospedaje" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

            jQuery( "#hospedaje_minimo" ).val( ui.values[ 0 ] );

            jQuery( "#hospedaje_maximo" ).val( ui.values[ 1 ] );

            if(ui.values[ 0 ]!=topeMinimoPrecio['hospedaje']){

               jQuery("#hospedaje_minimo").attr("name","precio_minimo");

            }

            else {

               jQuery("#hospedaje_minimo").attr("name","");

            }

            if(ui.values[ 1 ]!=topeMaximoPrecio['hospedaje']){

               jQuery("#hospedaje_maximo").attr("name","precio_maximo");

            }

            else {

               jQuery("#hospedaje_maximo").attr("name","");

            }

//            jQuery( "#seleccionar_precios" ).attr("checked",true);

//            jQuery( "#precio_minimo" ).attr("name","precio_minimo");

//            jQuery( "#precio_maximo" ).attr("name","precio_maximo");

        }

    });



    jQuery( "#rango_guarderia" ).slider({

        range: true,

        min: topeMinimoPrecio['guarderia'], 

        max: topeMaximoPrecio['guarderia'],

        values: [ valorMinimoPrecio['guarderia'], valorMaximoPrecio['guarderia'] ],

        slide: function( event, ui ) {

            jQuery( "#precios_guarderia" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

            jQuery( "guarderia_minimo" ).val( ui.values[ 0 ] );

            jQuery( "#guarderia_maximo" ).val( ui.values[ 1 ] );

            if(ui.values[ 0 ]==topeMinimoPrecio['guarderia']){

               jQuery("#guarderia_minimo").attr("name","precio_minimo");

            }

            else {

               jQuery("#guarderia_minimo").attr("name","");

            }

            if(ui.values[ 1 ]==topeMaximoPrecio['guarderia']){

               jQuery("#guarderia_maximo").attr("name","precio_maximo");

            }

            else {

               jQuery("#guarderia_maximo").attr("name","");

            }

//            jQuery( "#seleccionar_precios" ).attr("checked",true);

//            jQuery( "#precio_minimo" ).attr("name","precio_minimo");

//            jQuery( "#precio_maximo" ).attr("name","precio_maximo");

        }

    });



    jQuery( "#rango_paseos" ).slider({

        range: true,

        min: topeMinimoPrecio['paseos'], 

        max: topeMaximoPrecio['paseos'],

        values: [ valorMinimoPrecio['paseos'], valorMaximoPrecio['paseos'] ],

        slide: function( event, ui ) {

            jQuery( "#precios_paseos" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

            jQuery( "paseos_minimo" ).val( ui.values[ 0 ] );

            jQuery( "#paseos_maximo" ).val( ui.values[ 1 ] );

            if(ui.values[ 0 ]==topeMinimoPrecio['paseos']){

               jQuery("#paseos_minimo").attr("name","precio_minimo");

            }

            else {

               jQuery("#paseos_minimo").attr("name","");

            }

            if(ui.values[ 1 ]==topeMaximoPrecio['paseos']){

               jQuery("#paseos_maximo").attr("name","precio_maximo");

            }

            else {

               jQuery("#paseos_maximo").attr("name","");

            }

//            jQuery( "#seleccionar_precios" ).attr("checked",true);

//            jQuery( "#precio_minimo" ).attr("name","precio_minimo");

//            jQuery( "#precio_maximo" ).attr("name","precio_maximo");

        }

    });



    jQuery( "#rango_adiestramiento" ).slider({

        range: true,

        min: topeMinimoPrecio['adiestramiento'], 

        max: topeMaximoPrecio['adiestramiento'],

        values: [ valorMinimoPrecio['adiestramiento'], valorMaximoPrecio['adiestramiento'] ],

        slide: function( event, ui ) {

            jQuery( "#precios_adiestramiento" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

            jQuery( "adiestramiento_minimo" ).val( ui.values[ 0 ] );

            jQuery( "#adiestramiento_maximo" ).val( ui.values[ 1 ] );

            if(ui.values[ 0 ]==topeMinimoPrecio['adiestramiento']){

               jQuery("#adiestramiento_minimo").attr("name","precio_minimo");

            }

            else {

               jQuery("#adiestramiento_minimo").attr("name","");

            }

            if(ui.values[ 1 ]==topeMaximoPrecio['adiestramiento']){

               jQuery("#adiestramiento_maximo").attr("name","precio_maximo");

            }

            else {

               jQuery("#adiestramiento_maximo").attr("name","");

            }

//            jQuery( "#seleccionar_precios" ).attr("checked",true);

//            jQuery( "#precio_minimo" ).attr("name","precio_minimo");

//            jQuery( "#precio_maximo" ).attr("name","precio_maximo");

        }

    });



    jQuery( "#rango_peluqueria" ).slider({

        range: true,

        min: topeMinimoPrecio['peluqueria'], 

        max: topeMaximoPrecio['peluqueria'],

        values: [ valorMinimoPrecio['peluqueria'], valorMaximoPrecio['peluqueria'] ],

        slide: function( event, ui ) {

            jQuery( "#precios_peluqueria" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

            jQuery( "#peluqueria_minimo" ).val( ui.values[ 0 ] );

            jQuery( "#peluqueria_maximo" ).val( ui.values[ 1 ] );

            if(ui.values[ 0 ]==topeMinimoPrecio['peluqueria']){

               jQuery("#peluqueria_minimo").attr("name","precio_minimo");

            }

            else {

               jQuery("#peluqueria_minimo").attr("name","");

            }

            if(ui.values[ 1 ]==topeMaximoPrecio['peluqueria']){

               jQuery("#peluqueria_maximo").attr("name","precio_maximo");

            }

            else {

               jQuery("#peluqueria_maximo").attr("name","");

            }

//            jQuery( "#seleccionar_precios" ).attr("checked",true);

//            jQuery( "#precio_minimo" ).attr("name","precio_minimo");

//            jQuery( "#precio_maximo" ).attr("name","precio_maximo");

        }

    });



    jQuery( "#rango_bano" ).slider({

        range: true,

        min: topeMinimoPrecio['bano'], 

        max: topeMaximoPrecio['bano'],

        values: [ valorMinimoPrecio['bano'], valorMaximoPrecio['bano'] ],

        slide: function( event, ui ) {

            jQuery( "#precios_bano" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

            jQuery( "#bano_minimo" ).val( ui.values[ 0 ] );

            jQuery( "#bano_maximo" ).val( ui.values[ 1 ] );

            if(ui.values[ 0 ]==topeMinimoPrecio['bano']){

               jQuery("#bano_minimo").attr("name","precio_minimo");

            }

            else {

               jQuery("#bano_minimo").attr("name","");

            }

            if(ui.values[ 1 ]==topeMaximoPrecio['bano']){

               jQuery("#bano_maximo").attr("name","precio_maximo");

            }

            else {

               jQuery("#bano_maximo").attr("name","");

            }

//            jQuery( "#seleccionar_precios" ).attr("checked",true);

//            jQuery( "#precio_minimo" ).attr("name","precio_minimo");

//            jQuery( "#precio_maximo" ).attr("name","precio_maximo");

        }

    });

/*

    jQuery( "#rango_precios" ).slider({

        range: true,

        min: topeMinimoPrecio[servicioPrincipal], 

        max: topeMaximoPrecio[servicioPrincipal],

        values: [ valorMinimoPrecio[servicioPrincipal], valorMaximoPrecio[servicioPrincipal] ],

        slide: function( event, ui ) {

            jQuery( "#precios" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

            jQuery( "#precio_minimo" ).val( ui.values[ 0 ] );

            jQuery( "#precio_maximo" ).val( ui.values[ 1 ] );

            jQuery( "#seleccionar_precios" ).attr("checked",true);

            jQuery( "#precio_minimo" ).attr("name","precio_minimo");

            jQuery( "#precio_maximo" ).attr("name","precio_maximo");

        }

    });

 */   

    jQuery( "#rango_experiencia" ).slider({

        range: true,

        min: topeMinimoExperiencia,

        max: topeMaximoExperiencia,

        values: [ valorMinimoExperiencia, valorMaximoExperiencia ],

        slide: function( event, ui ) {

            jQuery( "#experiencia" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );

            jQuery( "#experiencia_minima" ).val( ui.values[ 0 ] );

            jQuery( "#experiencia_maxima" ).val( ui.values[ 1 ] );

            if(ui.values[ 0 ]!=topeMinimoExperiencia){

                jQuery( "#experiencia_minima" ).attr("name","experiencia_minima");

            }

            else {

               jQuery("#experiencia_minima").attr("name","");

            }

            if(ui.values[ 1 ]!=topeMaximoExperiencia){

                jQuery( "#experiencia_maxima" ).attr("name","experiencia_maxima");

            }

            else {

               jQuery("#experiencia_maxima").attr("name","");

            }

//            jQuery( "#seleccionar_experiencia" ).attr("checked",true);

//            jQuery( "#experiencia_minima" ).attr("name","experiencia_minima");

//            jQuery( "#experiencia_maxima" ).attr("name","experiencia_maxima");

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

        min: topeMinimoValoracion,

        max: topeMaximoValoracion,

        values: [ valorMinimoValoracion, valorMaximoValoracion ],

        slide: function( event, ui ) {

            jQuery( "#valoracion" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );

            jQuery( "#valoracion_minima" ).val( ui.values[ 0 ] );

            jQuery( "#valoracion_maxima" ).val( ui.values[ 1 ] );

            if(ui.values[ 0 ]!=topeMinimoValoracion){

                jQuery( "#experiencia_minima" ).attr("name","experiencia_minima");

            }

            else {

                jQuery( "#experiencia_minima" ).attr("name","");

            }

            if(ui.values[ 1 ]!=topeMaximoValoracion){

                jQuery( "#experiencia_maxima" ).attr("name","experiencia_maxima");

            }

            else {

                jQuery("#experiencia_maxima").attr("name","");

            }

//            jQuery( "#seleccionar_valoracion" ).attr("checked",true);

//            jQuery("#valoracion_minima").attr("name","valoracion_minima");

//            jQuery("#valoracion_maxima").attr("name","valoracion_maxima");

        }

    });

    

    var mostrar = true;



    jQuery(".reset-range").each(function(index){ 

        var servicio = jQuery(this).val();

        if(jQuery("#servicio_"+servicio).attr("checked") && mostrar==true) {

            jQuery("#grupo_"+servicio).show();

            mostrar = false;

            console.log('Mostrando '+servicio);

        }

        else {

            jQuery("#grupo_"+servicio).hide();

            console.log('Ocultando '+servicio);

        }

    });



    jQuery(".reset-range").on("click", function(){ 

        var servicio = jQuery(this).val();

        console.log('Inicializando rangos del servicio '+servicio);

        // Inicializa rango de precios

        jQuery("#rango_hospedaje").slider("values",[topeMinimoPrecio['hospedaje'],topeMaximoPrecio['hospedaje']]);

        jQuery("#precios_hospedaje").val("$"+topeMinimoPrecio['hospedaje']+" - $"+topeMaximoPrecio['hospedaje']);

        jQuery("#rango_guarderia").slider("values",[topeMinimoPrecio['guarderia'],topeMaximoPrecio['guarderia']]);

        jQuery("#precios_guarderia").val("$"+topeMinimoPrecio['guarderia']+" - $"+topeMaximoPrecio['guarderia']);

        jQuery("#rango_paseos").slider("values",[topeMinimoPrecio['paseos'],topeMaximoPrecio['paseos']]);

        jQuery("#precios_paseos").val("$"+topeMinimoPrecio['paseos']+" - $"+topeMaximoPrecio['paseos']);

        jQuery("#rango_adiestramiento").slider("values",[topeMinimoPrecio['adiestramiento'],topeMaximoPrecio['adiestramiento']]);

        jQuery("#precios_adiestramiento").val("$"+topeMinimoPrecio['adiestramiento']+" - $"+topeMaximoPrecio['adiestramiento']);

        jQuery("#rango_peluqueria").slider("values",[topeMinimoPrecio['peluqueria'],topeMaximoPrecio['peluqueria']]);

        jQuery("#precios_peluqueria").val("$"+topeMinimoPrecio['peluqueria']+" - $"+topeMaximoPrecio['peluqueria']);

        jQuery("#rango_bano").slider("values",[topeMinimoPrecio['bano'],topeMaximoPrecio['bano']]);

        jQuery("#precios_bano").val("$"+topeMinimoPrecio['bano']+" - $"+topeMaximoPrecio['bano']);

        // Inicializa rango de experiencia

        jQuery("#rango_experiencia").slider("values",[topeMinimoExperiencia, topeMaximoExperiencia]);

        jQuery("#experiencia").val(topeMinimoExperiencia+" - "+topeMaximoExperiencia);

        // Inicializa rango de valoraciones

        jQuery("#rango_valoracion").slider("values",[topeMinimoValoracion,topeMaximoValoracion]);

        jQuery("#valoracion").val(topeMinimoValoracion+" - "+topeMaximoValoracion);

        if(jQuery("#servicio_bano").attr("checked")) servicioPrincipal = 'bano';

        if(jQuery("#servicio_peluqueria").attr("checked")) servicioPrincipal = 'peluqueria';

        if(jQuery("#servicio_adiestramiento").attr("checked")) servicioPrincipal = 'adiestramiento';

        if(jQuery("#servicio_paseos").attr("checked")) servicioPrincipal = 'paseos';

        if(jQuery("#servicio_guarderia").attr("checked")) servicioPrincipal = 'guarderia';

        if(jQuery("#servicio_hospedaje").attr("checked")) servicioPrincipal = 'hospedaje';

        jQuery("#precio_minimo").val(jQuery("#"+servicioPrincipal+"_minimo").val());

        jQuery("#precio_maximo").val(jQuery("#"+servicioPrincipal+"_maximo").val());

        jQuery("#precio_minimo").attr("name","precio_minimo");

        jQuery("#precio_maximo").attr("name","precio_maximo");

        jQuery(".grupo_precios").hide();

        jQuery("#grupo_"+servicioPrincipal).show();

    });



    // Inicializa rango de precios de hospedaje

    jQuery( "#precios_hospedaje" ).val( "$" + jQuery( "#rango_hospedaje" ).slider( "values", 0 ) +

      " - $" + jQuery( "#rango_hospedaje" ).slider( "values", 1 ) );

    // Inicializa rango de precios de guarderia

    jQuery( "#precios_guarderia" ).val( "$" + jQuery( "#rango_guarderia" ).slider( "values", 0 ) +

      " - $" + jQuery( "#rango_guarderia" ).slider( "values", 1 ) );

    // Inicializa rango de precios de paseos

    jQuery( "#precios_paseos" ).val( "$" + jQuery( "#rango_paseos" ).slider( "values", 0 ) +

      " - $" + jQuery( "#rango_paseos" ).slider( "values", 1 ) );

    // Inicializa rango de precios de adiestramiento

    jQuery( "#precios_adiestramiento" ).val( "$" + jQuery( "#rango_adiestramiento" ).slider( "values", 0 ) +

      " - $" + jQuery( "#rango_adiestramiento" ).slider( "values", 1 ) );

    // Inicializa rango de precios de peluqueria

    jQuery( "#precios_peluqueria" ).val( "$" + jQuery( "#rango_peluqueria" ).slider( "values", 0 ) +

      " - $" + jQuery( "#rango_peluqueria" ).slider( "values", 1 ) );

    // Inicializa rango de precios de bano

    jQuery( "#precios_bano" ).val( "$" + jQuery( "#rango_bano" ).slider( "values", 0 ) +

      " - $" + jQuery( "#rango_bano" ).slider( "values", 1 ) );

    

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

        jQuery(this).addClass('pfadmicon-glyph-815');

//        jQuery(this).addClass('pfadmicon-glyph-992');

        jQuery(this).on('click', function(e){

            e.preventDefault();

            jQuery(this).next().toggle();

            if(jQuery(this).next().is(":visible")) {

/*                jQuery(this).removeClass('pfadmicon-glyph-992');

                jQuery(this).addClass('pfadmicon-glyph-918');*/

                jQuery(this).removeClass('pfadmicon-glyph-815');

                jQuery(this).addClass('pfadmicon-glyph-818');

            }

            else {

                jQuery(this).removeClass('pfadmicon-glyph-818');

                jQuery(this).addClass('pfadmicon-glyph-815');

            }

        });

    });

    jQuery(".accordion:first").click();



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

    padding: 9px;

    width: 100%;

    border: none;

    text-align: left;

    outline: none;

    font-size: 15px;

    transition: 0.4s;

    border-bottom: 1px solid #ccc;

    border-radius: 10px;

    margin-top: 3px;

}

button.accordion:last-of-type {

    border-bottom: 0px;

}

button.accordion.active, button.accordion:hover {

    background-color: #ddd;

}



div.panel {

    padding: 0 6px;

    display: none;

    background-color: white;

    border: 1px solid #eee;

    border-radius: 10px;

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

<p><br><strong> Ordenar resultados por:</strong></p>

<select name="orderby"><?php

if(isset($_GET['latitud']) && isset($_GET['longitud'])){ ?>

    <option value="rating_desc"<?php if($ordenar=='rating_desc') echo ' selected';?>>Valoración de mayor a menor</option>

    <option value="rating_asc"<?php if($ordenar=='rating_asc') echo ' selected';?>>Valoración de menor a mayor</option>

    <option value="distance_asc"<?php if($ordenar=='distance_asc') echo ' selected';?>>Distancia al cuidador de cerca a lejos</option>

    <option value="distance_desc"<?php if($ordenar=='distance_desc') echo ' selected';?>>Distancia al cuidador de lejos a cerca</option><?php

} ?>

    <option value="price_asc"<?php if($ordenar=='price_asc') echo ' selected';?>>Precio del Servicio de menor a mayor</option>

    <option value="price_desc"<?php if($ordenar=='price_desc') echo ' selected';?>>Precio del Servicio de mayor a menor</option>

    <option value="experience_asc"<?php if($ordenar=='experience_asc') echo ' selected';?>>Experiencia de menos a más años</option>

    <option value="experience_desc"<?php if($ordenar=='experience_desc') echo ' selected';?>>Experiencia de más a menos años</option>

    <option value="name_asc"<?php if($ordenar=='name_asc') echo ' selected';?>>Nombre del Cuidador de la A a la Z</option>

    <option value="name_desc"<?php if($ordenar=='name_desc') echo ' selected';?>>Nombre del Cuidador de la Z a la A</option>

    </select>

    <br><p></p>

<button class="accordion">Ubicación <i class="pfadmicon-glyph-369 derecha" style="font-size: 1.2em;"></i></button>

<div class="panel">

    <div id="selector_tipo" class="izquierda mt10"> <input type="radio" name="tipo_busqueda" id="mi-ubicacion" value="mi-ubicacion" class="ml8"<?php if($tipo_busqueda=='mi-ubicacion') echo ' checked'; ?>> <label for="mi-ubicacion">Mi ubicación </label> <input type="radio" name="tipo_busqueda" id="otra-localidad" value="otra-localidad" class="ml8"<?php if($tipo_busqueda=='otra-localidad') echo ' checked'; ?>> <label for="otra-localidad">Otra localidad</label></div>

    <div id="ubicacion_usuario" class="mt10 clr<?php if($tipo_busqueda=='otra-localidad') echo ' hide'; ?>">

        <div class="grupo_rango">

        <label for="latitud">Latitud:</label><br>

        <input type="number" id="latitud" name="latitud" value="<?php echo $latitud; ?>" step="any">

        </div>

        <div class="grupo_rango">

        <label for="latitud">Longitud:</label><br>

        <input type="number" id="longitud" name="longitud" value="<?php echo $longitud; ?>" step="any">

        </div>

        <div id="rango_distancia_main" style="margin: 10px;">

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

<button class="accordion">Rangos <i class="pfadmicon-glyph-227 derecha" style="font-size: 1.25em;-webkit-transform: rotate(90deg);-moz-transform: rotate(90deg);"></i></button>

<div class="panel">

    <div id="rango_precios_main" style="margin: 10px;">

        <div id="grupo_hospedaje" class="grupo_precios">

            <label for="precios_hospedaje">Rango Hospedaje:</label>

            <input type="text" id="precios_hospedaje" readonly style="border:0; color:#f6931f; font-weight:bold; width: 100px; text-align: right; background-color: transparent; float: right;">

            <div id="rango_hospedaje"></div>

            <input type="hidden" id="hospedaje_minimo" value="<?php echo $rangos->hospedajemin; ?>">

            <input type="hidden" id="hospedaje_maximo" value="<?php echo $rangos->hospedajemax; ?>">

        </div>

        <div id="grupo_guarderia" class="grupo_precios">

            <label for="precios_guarderia">Rango Guardería:</label>

            <input type="text" id="precios_guarderia" readonly style="border:0; color:#f6931f; font-weight:bold; width: 100px; text-align: right; background-color: transparent; float: right;">

            <div id="rango_guarderia"></div>

            <input type="hidden" id="guarderia_minimo" value="<?php echo $rangos->guarderiamin; ?>">

            <input type="hidden" id="guarderia_maximo" value="<?php echo $rangos->guarderiamax; ?>">

        </div>

        <div id="grupo_paseos" class="grupo_precios">

            <label for="precios_paseos">Rango Paseos:</label>

            <input type="text" id="precios_paseos" readonly style="border:0; color:#f6931f; font-weight:bold; width: 100px; text-align: right; background-color: transparent; float: right;">

            <div id="rango_paseos"></div>

            <input type="hidden" id="paseos_minimo" value="<?php echo $rangos->paseosmin; ?>">

            <input type="hidden" id="paseos_maximo" value="<?php echo $rangos->paseosmax; ?>">

        </div>

        <div id="grupo_adiestramiento" class="grupo_precios">

            <label for="precios_adiestramiento">Rango Adiestram.:</label>

            <input type="text" id="precios_adiestramiento" readonly style="border:0; color:#f6931f; font-weight:bold; width: 100px; text-align: right; background-color: transparent; float: right;">

            <div id="rango_adiestramiento"></div>

            <input type="hidden" id="adiestramiento_minimo" value="<?php echo $rangos->adiestramientomin; ?>">

            <input type="hidden" id="adiestramiento_maximo" value="<?php echo $rangos->adiestramientomax; ?>">

        </div>

        <div id="grupo_peluqueria" class="grupo_precios">

            <label for="precios_peluqueria">Rango Peluquería:</label>

            <input type="text" id="precios_peluqueria" readonly style="border:0; color:#f6931f; font-weight:bold; width: 100px; text-align: right; background-color: transparent; float: right;">

            <div id="rango_peluqueria"></div>

            <input type="hidden" id="peluqueria_minimo" value="<?php echo $rangos->peluqueriamin; ?>">

            <input type="hidden" id="peluqueria_maximo" value="<?php echo $rangos->peluqueriamax; ?>">

        </div>

        <div id="grupo_bano" class="grupo_precios">

            <label for="precios_bano">Rango Baño:</label>

            <input type="text" id="precios_bano" readonly style="border:0; color:#f6931f; font-weight:bold; width: 100px; text-align: right; background-color: transparent; float: right;">

            <div id="rango_bano"></div>

            <input type="hidden" id="bano_minimo" value="<?php echo $rangos->banomin; ?>">

            <input type="hidden" id="bano_maximo" value="<?php echo $rangos->banomax; ?>">

        </div>

        <input type="hidden" id="precio_minimo" value="">

        <input type="hidden" id="precio_maximo" value="">

    </div>

    <div id="rango_experiencia_main" style="margin: 10px;">

        <label for="seleccionar_experiencia">Años de Experiencia:</label>

        <input type="text" id="experiencia" readonly style="border:0; color:#f6931f; font-weight:bold; width: 60px; text-align: right; background-color: transparent; float: right;">

        <input type="hidden" id="experiencia_minima" value="<?php echo $rangos->expermin; ?>">

        <input type="hidden" id="experiencia_maxima" value="<?php echo $rangos->expermax; ?>">

        <div id="rango_experiencia"></div>

    </div>

    <div id="rango_valoracion_main" style="margin: 10px;">

        <label for="seleccionar_valoracion">Valoración de Clientes:</label>

        <input type="text" id="valoracion" readonly style="border:0; color:#f6931f; font-weight:bold; width: 60px; text-align: right; background-color: transparent; float: right;">

        <input type="hidden" id="valoracion_minima" value="<?php echo $rangos->valormin; ?>">

        <input type="hidden" id="valoracion_maxima" value="<?php echo $rangos->valormax; ?>">

        <div id="rango_valoracion"></div>

    </div>

</div>

<button class="accordion">Servicios Reservables<span class="pfbadge"><?php echo count($servicios); ?></span></button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que ofrezcan:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

        <li><input type="checkbox" id="servicio_hospedaje" class="reset-range" name="servicio_cuidador[]" value="hospedaje"<?php if(in_array('hospedaje',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_hospedaje" class="lbl-ui select">Hospedaje (cuidado dia y noche)</label></li>

        <li><input type="checkbox" id="servicio_guarderia" class="reset-range" name="servicio_cuidador[]" value="guarderia"<?php if(in_array('guarderia',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_guarderia" class="lbl-ui select">Guardería (cuidado durante el dia)</label></li>

        <li><input type="checkbox" id="servicio_paseos" class="reset-range" name="servicio_cuidador[]" value="paseos"<?php if(in_array('paseos',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_paseos" class="lbl-ui select">Paseos</label></li>

        <li><input type="checkbox" id="servicio_adiestramiento" class="reset-range" name="servicio_cuidador[]" value="adiestramiento"<?php if(in_array('adiestramiento',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_adiestramiento" class="lbl-ui select">Adiestramiento de Obediencia</label></li>

        <li><input type="checkbox" id="servicio_peluqueria" class="reset-range" name="servicio_cuidador[]" value="peluqueria"<?php if(in_array('peluqueria',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_peluqueria" class="lbl-ui select">Peluqueria (Corte de Pelo y Uñas)</label></li>

       <li><input type="checkbox" id="servicio_bano" class="reset-range" name="servicio_cuidador[]" value="bano"<?php if(in_array('bano',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_bano" class="lbl-ui select">Baño y Secado</label></li>

    </ul><br>

</div>

<button class="accordion">Servicios Adicionales<span class="pfbadge"><?php echo count($adicionales); ?></span></button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que ofrezcan:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

       <li><input type="checkbox" id="servicio_transporte" name="servicio_adicional[]" value="transporte"<?php if(in_array('transporte',$adicionales)) echo 'checked="checked"'; ?>><label for="servicio_transporte" class="lbl-ui select">Transportación Sencilla</label></li>

       <li><input type="checkbox" id="servicio_transporte2" name="servicio_adicional[]" value="transporte2"<?php if(in_array('transporte2',$adicionales)) echo 'checked="checked"'; ?>><label for="servicio_transporte2" class="lbl-ui select">Transportación Redonda</label></li>

       <li><input type="checkbox" id="adicional_peluqueria" name="servicio_adicional[]" value="veterinario"<?php if(in_array('corte_adic',$adicionales)) echo 'checked="checked"'; ?>><label for="adicional_peluqueria" class="lbl-ui select">Corte de Pelo y Uñas (Serv. Adic.)</label></li>

       <li><input type="checkbox" id="adicional_bano" name="servicio_adicional[]" value="bano2"<?php if(in_array('bano_adic',$adicionales)) echo 'checked="checked"'; ?>><label for="adicional_bano" class="lbl-ui select">Baño y Secado (Serv. Adic.)</label></li>

       <li><input type="checkbox" id="servicio_veterinario" name="servicio_adicional[]" value="peluqueria2"<?php if(in_array('veterinario',$adicionales)) echo 'checked="checked"'; ?>><label for="servicio_veterinario" class="lbl-ui select">Visita al Veterinario</label></li>

       <li><input type="checkbox" id="servicio_limpieza" name="servicio_adicional[]" value="limpieza"<?php if(in_array('limpieza',$adicionales)) echo 'checked="checked"'; ?>><label for="servicio_limpieza" class="lbl-ui select">Limpieza Dental</label></li>

       <li><input type="checkbox" id="servicio_acupuntura" name="servicio_adicional[]" value="acupuntura"<?php if(in_array('acupuntura',$servicios)) echo 'checked="checked"'; ?>><label for="servicio_acupuntura" class="lbl-ui select">Acupuntura</label></li>

    </ul><br>

</div>

<button class="accordion">Tamaños Aceptados<span class="pfbadge"><?php echo count($acepta); ?></span></button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que acepten tamaños:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

        <li><input type="checkbox" id="acepta_pequeno" name="acepta_mascotas[]" value="s"<?php if(in_array('s',$acepta)) echo 'checked="checked"'; ?>><label for="acepta_pequeno" class="lbl-ui select">Mascotas Pequeñas (menos de 10Kg)</label></li>

        <li><input type="checkbox" id="acepta_mediano" name="acepta_mascotas[]" value="m"<?php if(in_array('m',$acepta)) echo 'checked="checked"'; ?>><label for="acepta_mediano" class="lbl-ui select">Mascotas Medianas (de 11 a 25Kg)</label></li>

        <li><input type="checkbox" id="acepta_grande" name="acepta_mascotas[]" value="l"<?php if(in_array('l',$acepta)) echo 'checked="checked"'; ?>><label for="acepta_grande" class="lbl-ui select">Mascotas Grandes (de 26 a 45Kg)</label></li>

        <li><input type="checkbox" id="acepta_gigante" name="acepta_mascotas[]" value="x"<?php if(in_array('x',$acepta)) echo 'checked="checked"'; ?>><label for="acepta_gigante" class="lbl-ui select">Mascotas Gigantes (más de 45Kg)</label></li>

    </ul><br>

</div>

<button class="accordion">Mascotas de Cuidadores<span class="pfbadge"><?php echo count($tiene); ?></span></button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que tengan mascotas:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

        <li><input type="checkbox" id="tiene_pequeno" name="tiene_mascota[]" value="s"<?php if(in_array('s',$tiene)) echo 'checked="checked"'; ?>><label for="tiene_pequeno" class="lbl-ui select">Mascotas Pequeñas (menos de 10Kg)</label></li>

        <li><input type="checkbox" id="tiene_mediano" name="tiene_mascota[]" value="m"<?php if(in_array('m',$tiene)) echo 'checked="checked"'; ?>><label for="tiene_mediano" class="lbl-ui select">Mascotas Medianas (de 11 a 25Kg)</label></li>

        <li><input type="checkbox" id="tiene_grande" name="tiene_mascota[]" value="l"<?php if(in_array('l',$tiene)) echo 'checked="checked"'; ?>><label for="tiene_grande" class="lbl-ui select">Mascotas Grandes (de 26 a 45Kg)</label></li>

        <li><input type="checkbox" id="tiene_gigante" name="tiene_mascota[]" value="x"<?php if(in_array('x',$tiene)) echo 'checked="checked"'; ?>><label for="tiene_gigante" class="lbl-ui select">Mascotas Gigantes (más de 45Kg)</label></li>

    </ul><br>

</div>

<button class="accordion">Conductas Aceptadas<span class="pfbadge"><?php echo count($conductas); ?></span></button>

<div class="panel">

    <div class="tooltip"><p><strong>Mostrar solo cuidadores que acepten conductas:</strong></p><span class="tooltiptext w240">Muestra solo los cuidadores que cumplan con <strong>todos</strong> los criterios seleccionados en la siguiente lista.</span></div>

    <ul>

        <li><input type="checkbox" id="acepta_sociables" name="acepta_conductas[]" value="s"<?php if(in_array('s',$conductas)) echo 'checked="checked"'; ?>><label for="tiene_pequeno" class="lbl-ui select">Sociables</label></li>

        <li><input type="checkbox" id="acepta_no_sociables" name="acepta_conductas[]" value="n"<?php if(in_array('n',$conductas)) echo 'checked="checked"'; ?>><label for="tiene_mediano" class="lbl-ui select">No Sociables</label></li>

        <li><input type="checkbox" id="acepta_agresiva_mascotas" name="acepta_conductas[]" value="m"<?php if(in_array('m',$conductas)) echo 'checked="checked"'; ?>><label for="tiene_grande" class="lbl-ui select">Agresivas con Otras Mascotas</label></li>

        <li><input type="checkbox" id="acepta_agresiva_humanos" name="acepta_conductas[]" value="h"<?php if(in_array('h',$conductas)) echo 'checked="checked"'; ?>><label for="tiene_gigante" class="lbl-ui select">Agresivas con Humanos</label></li>

    </ul><br>

</div>

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

	}

}

?>