<?php

    //[kmimos_search]

    $pais = (isset($args["pais"]))?$args["pais"]:"mx";

    $vacios = (isset($args["vacios"]))?$args["vacios"]:1;

    $taxonomy = 'pointfinderlocations';
    $parent = get_term_by('slug', $pais, $taxonomy);
    $distdef = 20;

    $str_estados = '';
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
                            $extras = servicios_adicionales();
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
                                <sub>Estado:</sub><br>
                                <select id="estado_cuidador" name="estados" data-location="mx">
                                    <option value="">Seleccione un estado</option>
                                    <option value="7">Aguascalientes</option>
                                    <option value="8">Baja California</option>
                                    <option value="9">Baja California Sur</option>
                                    <option value="10">Campeche</option>
                                    <option value="13">Chiapas</option>
                                    <option value="14">Chihuahua</option>
                                    <option value="1">Ciudad de México</option>
                                    <option value="11">Coahuila de Zaragoza</option>
                                    <option value="12">Colima</option>
                                    <option value="15">Durango</option>
                                    <option value="2">Estado de México</option>
                                    <option value="16">Guanajuato</option>
                                    <option value="17">Guerrero</option>
                                    <option value="18">Hidalgo</option>
                                    <option value="3">Jalisco</option>
                                    <option value="19">Michoac&aacute;n de Ocampo</option>
                                    <option value="20">Morelos</option>
                                    <option value="21">Nayarit</option>
                                    <option value="4">Nuevo León</option>
                                    <option value="22">Oaxaca</option>
                                    <option value="23">Puebla</option>
                                    <option value="24">Queretaro</option>
                                    <option value="25">Quintana Roo</option>
                                    <option value="26">San Luis Potosi</option>
                                    <option value="27">Sinaloa</option>
                                    <option value="28">Sonora</option>
                                    <option value="29">Tabasco</option>
                                    <option value="30">Tamaulipas</option>
                                    <option value="31">Tlaxcala</option>
                                    <option value="32">Veracruz de Ignacio de la Llave</option>
                                    <option value="33">Yucatan</option>
                                    <option value="34">Zacatecas</option> 
                                </select>
                            </div>
                        </div>

                        <div class="grupo_selector">
                            <div class="marco">
                                <div class="icono">
                                    <i class="icon-mapa embebed"></i>
                                </div>
                                <sub>Municipio:</sub><br>
                                <select id="municipio_cuidador" name="municipios">
                                    <option value="">Seleccione primero un estado</option>
                                </select>
                                <input type="hidden" id="municipio_cache" name="municipio_cache">
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

                </div>

                <div class="w100pc">
                   <div class="mt10 izquierda">
                        <label id="tamano_mascota"><strong>TAMAÑO DE MI MASCOTA</strong></label>
                    </div>
                </div>

                <div id="tamano_mascota_main" style="overflow: hidden;">

                    <?php
                        $tamanos = array(
                            "pequenos" => "Peque&ntilde;os 0.0 cm - 25.0cm",
                            "medianos" => "Medianos 25.0 cm - 58.0 cm",
                            "grandes"  => "Grandes 58.0 cm - 73.0 cm",
                            "gigantes" => "Gigantes 73.0 cm - 200.0 cm",
                        );

                        foreach($tamanos as $key=>$value){ ?>
                            <div class="jj_btn_tamanos" style="float: left; box-sizing: border-box; padding: 0px 1px; margin-bottom: 2px !important;">
                                <div class="boton_portada boton_servicio" style="margin: 0px !important;">
                                    <input type="checkbox" name="tamanos[]" id="tamano_mascota_<?php echo $key;?>" value="<?php echo $key;?>" class="servicio_cuidador_<?php echo $key;?>" data-key="<?php echo $key;?>">
                                    <label for="tamano_mascota_<?php echo $key;?>"><i class="icon-<?php echo $key;?>"></i>
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
                
            </div>

            </center>

        </form>
    </div>

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<?php echo get_estados_municipios(); ?>
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

            var toRadian = function (deg) {
                return deg * Math.PI / 180;
            };

            function cargar_municipios(CB){

                var estado_id = jQuery("#estado_cuidador").val();
                
                if( estado_id != "" ){

                    var html = "<option value=''>Seleccione un municipio</option>";
                    jQuery.each(estados_municipios[estado_id]['municipios'], function(i, val) {
                        html += "<option value="+val.id+" data-id='"+i+"'>"+val.nombre+"</option>";
                    });

                    jQuery("#municipio_cuidador").html(html);

                    var location    = estados_municipios[estado_id]['coordenadas']['referencia'];
                    var norte       = estados_municipios[estado_id]['coordenadas']['norte'];
                    var sur         = estados_municipios[estado_id]['coordenadas']['sur'];

                    var distancia = calcular_rango_de_busqueda(norte, sur);

                    jQuery("#otra_latitud").attr("value", location.lat);
                    jQuery("#otra_longitud").attr("value", location.lng);
                    jQuery("#otra_distancia").attr("value", distancia);


                    if( CB != undefined) {
                        CB();
                    }
		}
            }

            

            jQuery("#estado_cuidador").on("change", function(e){
                cargar_municipios();
            });
            cargar_municipios(function(){
                jQuery('#municipio_cuidador > option[value="'+jQuery("#municipio_cache").val()+'"]').attr('selected', 'selected');
            });

            jQuery("#municipio_cuidador").on("change", function(e){
                jQuery("#municipio_cache").attr("value", jQuery("#municipio_cuidador").val() );
                vlz_coordenadas();
            });

            function vlz_coordenadas(){
                var estado_id = jQuery("#estado_cuidador").val();            
                var municipio_id = jQuery('#municipio_cuidador > option[value="'+jQuery("#municipio_cache").val()+'"]').attr('data-id');            
                
                if( estado_id != "" ){

                    var location    = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['referencia'];
                    var norte       = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['norte'];
                    var sur         = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['sur'];

                    var distancia = calcular_rango_de_busqueda(norte, sur);

                    jQuery("#otra_latitud").attr("value", location.lat);
                    jQuery("#otra_longitud").attr("value", location.lng);
                    jQuery("#otra_distancia").attr("value", distancia);

                }
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

