<?php

    //[kmimos_search]

    $pais = (isset($args["pais"]))?$args["pais"]:"mx";

    $vacios = (isset($args["vacios"]))?$args["vacios"]:1;

    $taxonomy = 'pointfinderlocations';
    $parent = get_term_by('slug', $pais, $taxonomy);
    $distdef = 20;

    global $wpdb;
    
    $estados = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY name ASC");

    $str_estados = "";
    foreach($estados as $estado) { 
        $str_estados .= "<option value='".$estado->id."'>".$estado->name."</option>";
    } 

    $str_estados = utf8_decode($str_estados);
    ?>

    <style>
        #estado_cuidador_main {
            margin: 0px !important;
        }

        #grupo_fecha {
            width: 110% !important;
        }

        .selector_fecha {
            width: 50%;
            overflow: hidden;
        }

        .grupo_fecha {
            overflow: hidden;
        }

        .grupo_selector {
            display: inline-block;
            width: 50%;
            float: left;
            margin-top: 3px;
        }

        html.iOS .fecha_hasta, html.iOS .fecha_desde { width: 118px !important}

        @media only screen and (max-width: 639px) { 
            .grupo_selector { width: 100%; } 
            html.iOS .selector_fecha {width: 50% !important}
            html.iOS .fecha_desde {float: left; margin-top: 0px !important; margin-right: -16px;}
            html.iOS .fecha_hasta {float: right; margin-top: 0px !important; margin-right: -16px;}
            .fecha_desde {float: left;}
            .fecha_hasta { display: block;}
            .fecha_hasta_full { display: none;}
        }

        .grupo_selector .marco {
            display: inline-block;
            border: 1px solid #888;
            width: 99%;
            padding-right: 5px;
        }

        .grupo_selector .icono {
            display: inline-block;
            width: 36px;
            height: 36px;
            float: left;
            padding: 5px;
            font-size: 1.8em;
        }

        .grupo_selector sub {
            top: 8px;
            float: left;
        }

        .grupo_selector select, .grupo_selector input {
            height: 25px;
            width: 87%;
            display: inline-block;
            clear: right;
            float: left;
            margin-top: -10px;
            border: 0px;
            background-color: #ffffff;
        }
        select.activo, .grupo_fecha input[type=date].activo { color: #00d2b7 !important; }

        select:focus{
            outline: 0px;
        }
    </style>

    <div id="portada">

        <form id="pointfinder-search-form-manual" method="POST" action="<?php echo get_home_url(); ?>/busqueda/" data-ajax="false" novalidate="novalidate">

            <center>
                <div class="buscador_portada">
                <div class="w100pc mt10">
                    <div class="mt10 izquierda">
                        <label id="estoy_buscando"><strong>ESTOY BUSCANDO</strong></label>
                    </div>
                    <div class="mt10 derecha">
                        <a href="#mas_servicios" class="prettyphoto" rel="more_services" id="ver_mas_servicios">Servicios adicionales...</a>
                    </div>
                </div>

                <div id="servicio_cuidador_main" class="w100pc"> <?php
                    $servicios = array(
                        "hospedaje"                  => '<p>Hospedaje<br><sup>cuidado día y noche</sup></p>', 
                        "guarderia"                  => '<p>Guardería<br><sup>cuidado durante el día</sup></p>', 
                        "paseos"                     => '<p>Paseos<br><sup></sup></p>',
                        "adiestramiento"             => '<p>Entrenamiento<br><sup></sup></p>'
                    ); 

                    foreach($servicios as $key => $value){ ?>
                        <div class="contenedor_servicio izquierda text-left">
                            <div class="boton_portada boton_servicio">
                                <?php if( substr($key, 0, 14) == "adiestramiento"){ $xkey = "adiestramiento"; }else{ $xkey = $key; } ?>
                                <input type="checkbox" name="servicios[]" id="servicio_cuidador_<?php echo $key;?>" class="servicio_cuidador_<?php echo $key;?>" value="<?php echo $key;?>" data-key="<?php echo $key;?>">
                                <label for="servicio_cuidador_<?php echo $key;?>">
                                    <i class="icon-<?php echo $xkey;?>"></i>
                                    <?php echo $value;?>
                                </label>
                            </div>
                        </div> <?php
                    } ?>
                </div>

                <div id="popup_mas_servicios" style="display:none; width: 300px; overflow: hidden;">
                    <div id="mas_servicios">
                        <?php
                            $extras = array(
                                'corte' => array( 
                                    'label'=>'Corte de Pelo y Uñas',
                                    'icon' => 'peluqueria'
                                ),
                                'bano' => array( 
                                    'label'=>'Baño y Secado',
                                    'icon' => 'bano'
                                ),
                                'transportacion_sencilla' => array( 
                                    'label'=>'Transporte Sencillo',
                                    'icon' => 'transporte'
                                ),
                                'transportacion_redonda' => array( 
                                    'label'=>'Transporte Redondo',
                                    'icon' => 'transporte2'
                                ),
                                'visita_al_veterinario' => array( 
                                    'label'=>'Visita al Veterinario',
                                    'icon' => 'veterinario'
                                ),
                                'limpieza_dental' => array( 
                                    'label'=>'Limpieza Dental',
                                    'icon' => 'limpieza'
                                ),
                                'acupuntura' => array( 
                                    'label'=>'Acupuntura',
                                    'icon' => 'acupuntura'
                                )
                            );

                            foreach($extras as $key => $value){ ?>
                                <div class="w96pc boton_extra text-center">
                                    <div class="boton_portada boton_servicio">
                                        <input type="checkbox" name="servicios[]" id="servicio_cuidador_<?php echo $key;?>" class="servicio_cuidador_<?php echo $key;?>" value="<?php echo $key;?>" data-key="<?php echo $key;?>">
                                        <label for="servicio_cuidador_<?php echo $key;?>">
                                            <i class="icon-<?php echo $value['icon'];?>"></i>
                                            <p><?php echo $value['label'];?></p>
                                        </label>
                                    </div>
                                </div> <?php
                            }
                        ?>
                   </div>
                </div>

                <div class="w100pc">
                    <div class="mt10 izquierda">
                        <div id="selector_tipo" class="izquierda"> 
                            <label id="cerca_de" class="izquierda"><strong>CERCA DE </strong></label> 
                            <input type="radio" name="tipo_busqueda" id="otra-localidad" value="otra-localidad" class="ml8" checked> 
                            <label for="otra-localidad"> Otra localidad</label> 

                            <input type="radio" name="tipo_busqueda" id="mi-ubicacion" value="mi-ubicacion" class="ml8" >
                            <label for="mi-ubicacion"> Mi ubicación </label> 
                        </div>
                    </div>
                </div>

                <div id="estado_cuidador_main">

                    <div id="selector_locacion">
                        <div class="grupo_selector">
                            <div class="marco">
                                <div class="icono">
                                    <i class="icon-mapa embebed"></i>
                                </div>
                                <sub>Municipio:</sub><br>
                                <select id="estado_cuidador" name="estados" data-location="co">
                                    <option value="">Seleccione un municipio</option>
                                    <?php echo $str_estados; ?>
                                </select>
                            </div>
                        </div>

                        <div class="grupo_selector">
                            <div class="marco">
                                <div class="icono">
                                    <i class="icon-mapa embebed"></i>
                                </div>
                                <sub>Localidad:</sub><br>
                                <select id="municipio_cuidador" name="municipios">
                                    <option value="">Seleccione primero un municipio</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="selector_coordenadas_x" class="hide">

                        <input type="text" id="latitud" name="latitud">
                        <input type="text" id="longitud" name="longitud">
                        <input type="text" id="distancia" name="distancia" value="1000000000">

                        <input type="text" id="otra_latitud" name="otra_latitud">
                        <input type="text" id="otra_longitud" name="otra_longitud">
                        <input type="text" id="otra_distancia" name="otra_distancia">

                    </div>

                <!--
                    <div class="grupo_selector selector_fecha">
                        <div class="marco">
                            <div class="icono">
                                <i class="icon-calendario"></i>
                            </div>
                            <div class="grupo_fecha fecha_desde">
                                <sub>Desde:</sub><br>
                                <input type="date" id="fecha_desde" name="fecha_desde">
                            </div>
                        </div>
                    </div>

                    <div class="grupo_selector selector_fecha">
                        <div class="marco">
                            <div class="icono">
                                <i class="icon-calendario"></i>
                            </div>
                            <div class="grupo_fecha fecha_hasta">
                                <sub>Hasta:</sub><br>
                                <input type="date" id="fecha_hasta" name="fecha_hasta">
                            </div>
                        </div>
                    </div>
                -->

                </div>

                <div class="w100pc">
                   <div class="mt10 izquierda">
                        <label id="tamano_mascota"><strong>TAMAÑO DE MI MASCOTA</strong></label>
                    </div>
                </div>

                <div id="tamano_mascota_main" style="overflow: hidden;">

                    <?php
                        $tamanos = array(
                            "pequenos" => "Peque&ntilde;os <br><sub>0.0 cm - 25.0cm</sub>",
                            "medianos" => "Medianos <br><sub>25.0 cm - 58.0 cm</sub>",
                            "grandes"  => "Grandes <br><sub>58.0 cm - 73.0 cm</sub>",
                            "gigantes" => "Gigantes <br><sub>73.0 cm - 200.0 cm</sub>",
                        );

                        foreach($tamanos as $key=>$value){ ?>
                            <div class="jj_btn_tamanos" style="float: left; box-sizing: border-box; padding: 0px 1px; margin-bottom: 2px !important;">
                                <div class="boton_portada boton_servicio" style="margin: 0px !important;">
                                    <input type="checkbox" name="tamanos[]" id="tamano_mascota_<?php echo $key;?>" value="<?php echo $key;?>"  class="servicio_cuidador_<?php echo $key;?>" data-key="<?php echo $key;?>">
                                    <label style="line-height: 14px;" for="tamano_mascota_<?php echo $key;?>"><i class="icon-<?php echo $key;?>"></i>
                                        <?php echo $value;?>
                                    </label>
                                </div>
                            </div> <?php
                        }
                    ?>
                </div>

                <div class="w100pc text-center">
                    <div class="boton_buscar">
                        <a class="button pfsearch" id="pf-search-button-manual"><i class="pfadmicon-glyph-627"></i> Buscar Cuidador</a>
                    </div>
                </div>
                
                <!--
                    <input type="hidden" name="s" value="">
                    <input type="hidden" name="serialized" value="1">
                    <input type="hidden" name="action" value="pfs">
                -->
            </div>

            </center>

        </form>
    </div>

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
    var hasGPS=false;

    (function($) {
        "use strict";
        $.pfsliderdefaults = {};
        $.pfsliderdefaults.fields = Array();
        var urlSever = '<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/app-server.php';
        var isTouchDevice = 'ontouchstart' in document.documentElement;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(coordenadas);
        } else {
            $("#selector_locacion").removeClass("hide");
            $("#selector_coordenadas").addClass("hide");
            $("#selector_tipo").addClass("hide");
        }

        if(navigator.platform.substr(0, 2) == 'iP') $('html').addClass("iOS");

        $(function(){
            var pais = $("#estado_cuidador").attr("data-location");
            var edos = $("#estado_cuidador");
            var mpos = $("#municipio_cuidador");

            if($("#otra-localidad").prop( "checked" )){
                $("#selector_locacion").removeClass("hide");
                $("#selector_coordenadas").addClass("hide");
                $('#estado_cuidador > option[value="'+pais+'"]').first().attr('selected', 'selected');
            }

            $("#otra-localidad").click(function(){
                $("#selector_locacion").removeClass("hide");
                $("#selector_coordenadas").addClass("hide");
            });

            $("#mi-ubicacion").click(function(){
                $("#selector_coordenadas").addClass("hide");
                $("#selector_locacion").addClass("hide");
            });

            // Busca los estados del país

            // $.post(urlSever,{action: 'get-location', location: pais }, function(){
            //     edos.prop('disabled', true);
            // }, "json").success(function(data){
            //     $.each(data, function(key,value){
            //         edos.append('<option value="'+key+'">'+value+'</option>');
            //     });
            //     edos.prop('disabled', false);
            // });

            edos.change(function(){
                vlz_ver_municipios();
            });

            mpos.change(function(){
                vlz_coordenadas();
            });

            var toRadian = function (deg) {
                return deg * Math.PI / 180;
            };

            function vlz_ver_municipios(){

                var id =  jQuery("#estado_cuidador").val();
                var txt = jQuery("#estado_cuidador option:selected").text();

                jQuery.ajax( {
                    method: "POST",
                        data: { estado: id },
                    url: "<?php echo get_template_directory_uri(); ?>/vlz/vlz_estados_municipios.php",
                    beforeSend: function( xhr ) {
                        jQuery("#municipios_cuidador").html("<option value=''>Cargando Municipios</option>");
                    }
                }).done(function(data){
                    jQuery("#municipio_cuidador").html("<option value=''>Seleccione una localidad</option>"+data);
                    vlz_coordenadas();
                });
            }

            function vlz_coordenadas(){
                var estado = jQuery("#estado_cuidador option:selected").text();
                var municipio_val = jQuery("#municipio_cuidador option:selected").val();
                var municipio = jQuery("#municipio_cuidador option:selected").text();

                var adress = "colombia";
                if( estado != "" ){ 
                    adress+="+"+estado; 
                }
                if( municipio_val != "" ){ 
                    adress+="+"+municipio; 
                }

                jQuery.ajax({ 
                    url: 'https://maps.googleapis.com/maps/api/geocode/json?address='+adress+'&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8'
                }).done(function(data){

                    var location = data.results[0].geometry.location;

                    var norte = data.results[0].geometry.viewport.northeast;
                    var sur   = data.results[0].geometry.viewport.southwest;

                    var distancia = calcular_rango_de_busqueda(norte, sur);

                    jQuery("#otra_latitud").attr("value", location.lat);
                    jQuery("#otra_longitud").attr("value", location.lng);
                    jQuery("#otra_distancia").attr("value", distancia);

                });
            } 

            function calcular_rango_de_busqueda(norte, sur){
                var d = ( 6371 * 
                    Math.acos(
                        Math.cos(
                            toRadian(norte.lat)
                        ) * 
                        Math.cos(
                            toRadian(sur.lat)
                        ) * 
                        Math.cos(
                            toRadian(sur.lng) - 
                            toRadian(norte.lng)
                        ) + 
                        Math.sin(
                            toRadian(norte.lat)
                        ) * 
                        Math.sin(
                            toRadian(sur.lat)
                        )
                    )
                );
                return d;
            }

/*            $(".grupo_fecha input[type=date]").on("change", function(){
                if($(this).val()!=''){
                    $(this).addClass('activo');
                } else {
                    $(this).removeClass('activo');
                }
            });*/

            $(".boton_servicio > input:checkbox").each(function(index){
                var servicio = $(this).attr('data-key');
                var activo = $(this).prop('checked');
                if(activo) $(this).parent().addClass('activo');
                else $(this).parent().removeClass('activo');
            });

            $(".boton_portada > input:checkbox").on('change',function(e){
                var servicio = $(this).attr('data-key');
                var activo = $(this).prop('checked');
                if(activo) $(".servicio_cuidador_"+servicio).parent().addClass('activo');
                else $(".servicio_cuidador_"+servicio).parent().removeClass('activo');
            });

/*            $("#selector_desde").datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                altField: "#fecha_desde",
                altFormat: "yy-mm-dd",
                onClose: function( selectedDate ) {
                    $( "#selector_hasta" ).datepicker( "option", "minDate", selectedDate );
                }
            });*/

            /*$("#selector_hasta").datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                altField: "#fecha_hasta",
                altFormat: "yy-mm-dd",
                onClose: function( selectedDate ) {
                    $( "#selector_desde" ).datepicker( "option", "maxDate", selectedDate );
                }
            });

            $(".campo_rango").change(function(){
                var desde = $("#fecha_desde").val();
                var hasta = $("#fecha_hasta").val();
                if(desde!='' && hasta!='' && hasta>desde) $('#boton_fechas').addClass('activo');
                else $('#boton_fechas').removeClass('activo');
            });

            $("#fecha_desde").on("change", function(){
                var desde = $(this).val();
                $("#fecha_hasta").attr({min: desde});
                $("#fecha_hasta").focus();
            });*/

            // $("#servicio_cuidador_hospedaje").prop('checked',true);
            // $("#servicio_cuidador_hospedaje").parent().addClass('activo');

            $(".modal").fancybox({
                maxWidth: 340
            });
        });
    })(jQuery);

    function coordenadas(position){
        if(position.coords.latitude!='' && position.coords.longitude!='') {
            document.getElementById("latitud").value=position.coords.latitude;
            document.getElementById("longitud").value=position.coords.longitude;        
        } else {
            var mensaje = 'No es posible leer su ubicación,\nverifique si su GPS está encendido\ny vuelva a recargar la página.'+$("#latitud").val()+','+$("#longitud").val();
            alert(mensaje);        
        }
    }
</script>

