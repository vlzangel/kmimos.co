<?php

    //[kmimos_search]

    $HOY = date("Y-m-d");

    $ESTADOS = '
        <option value="">Seleccione una provincia</option>
		<option value="9">AREQUIPA</option>
        <option value="5">CAJAMARCA</option>
        <option value="2">CALLAO</option>
        <option value="8">CUZCO</option>
        <option value="7">HUANCAYO</option>
        <option value="1">LIMA</option>
        <option value="3">PIURA</option>
        <option value="6">PUNO</option>
        <option value="4">TRUJILLO</option>
    ';

    $servicios = array(
        'hospedaje'      => '<p>Hospedaje<br><sup>cuidado día y noche</sup></p>', 
        'guarderia'      => '<p>Guardería<br><sup>cuidado durante el día</sup></p>', 
        'paseos'         => '<p>Paseos<br><sup></sup></p>',
        'adiestramiento' => '<p>Entrenamiento<br><sup></sup></p>'
    ); 
    $SERVICIOS = "";
    foreach($servicios as $key => $value){
        if( substr($key, 0, 14) == 'adiestramiento'){ $xkey = 'adiestramiento'; }else{ $xkey = $key; }
        $SERVICIOS .= "
        <div class='contenedor_servicio izquierda text-left'>
            <div class='boton_portada boton_servicio'>
                <input type='checkbox' name='servicios[]' id='servicio_cuidador_{$key}' class='servicio_cuidador_{$key}' value='{$key}' data-key='{$key}'>
                <label for='servicio_cuidador_{$key}'>
                    <i class='icon-{$key}'></i>
                    {$value}
                </label>
            </div>
        </div>";
    }

    $extras = servicios_adicionales(); $MAS_SERVICIOS = "";
    foreach($extras as $key => $value){
        $MAS_SERVICIOS .= "
        <div class='w96pc boton_extra text-center'>
            <div class='boton_portada boton_servicio'>
                <input type='checkbox' name='servicios[]' id='servicio_cuidador_{$key}' class='servicio_cuidador_{$key}' value='{$key}' data-key='{$key}'>
                <label for='servicio_cuidador_{$key}'>
                    <i class='icon-{$value['icon']}'></i>
                    <p>{$value['label']}</p>
                </label>
            </div>
        </div>";
    }

    $tamanos = array(
        'pequenos' => 'Peque&ntilde;os <br><sub>0.0 cm - 25.0cm</sub>',
        'medianos' => 'Medianos <br><sub>25.0 cm - 58.0 cm</sub>',
        'grandes'  => 'Grandes <br><sub>58.0 cm - 73.0 cm</sub>',
        'gigantes' => 'Gigantes <br><sub>73.0 cm - 200.0 cm</sub>',
    );
    $TAMANOS = "";
    foreach($tamanos as $key => $value){
        $TAMANOS .= "
        <div class='jj_btn_tamanos' style='float: left; box-sizing: border-box; padding: 0px 1px; margin-bottom: 2px !important;'>
            <div class='boton_portada boton_servicio' style='margin: 0px !important;'>
                <input type='checkbox' name='tamanos[]' id='tamano_mascota_{$key}' value='{$key}' class='servicio_cuidador_{$key}' data-key='{$key}'>
                <label for='tamano_mascota_{$key}' style='line-height: 12px; padding: 5px;'>
                    <i class='icon-{$key}'></i>
                    {$value}
                </label>
            </div>
        </div>";
    }

    $HTML = do_shortcode("[layerslider id='1']")."
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
        input[type='date'] {
             line-height: 1.42857143;
             margin-top: -10px;
             height: 25px !important;
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
            border: 0px;
            background-color: #ffffff;
        }
        .grupo_selector select{
            margin-top: -10px;
        }

        input[type='date'] {
            line-height: 1.42857143;
            margin-top: -10px;
            height: 25px !important;
        }

        select.activo, .grupo_fecha input[type=date].activo { color: #00d2b7 !important; }
        select:focus{
            outline: 0px;
        }
        #pp_full_res .pp_inline p {
            margin: 7px 0px 15px 38px;
            text-align: left;
        }

         @media only screen and (max-width: 639px) {
             .grupo_selector { width: 100%; }
             html.iOS .selector_fecha {width: 50% !important}
             html.iOS .fecha_desde {float: left; margin-top: 0px !important; margin-right: -16px;}
             html.iOS .fecha_hasta {float: right; margin-top: 0px !important; margin-right: -16px;}
             .fecha_desde {float: left;}
             .fecha_hasta { display: block;}
             .fecha_hasta_full { display: none;}
         }


         .marco{
             position: relative;
         }
         .no_error {
             display: none;
         }
         .error {
             position: relative;
             border: solid 1px #cf6666;
             margin: 0px 2px 0px 1px;
             border-top: 0px;
             background: #cf6666;
             color: #FFF;
             border-radius: 0px 0px 3px 3px;
             font-size: 12px;
         }

        input.fechas {
            margin-top: 0;
            height: 35px !important;
        }
    </style>
    <div id='portada'>
        <form id='pointfinder-search-form-manual' method='POST' action='".get_home_url()."/wp-content/themes/pointfinder/vlz/buscar.php' data-ajax='false' novalidate='novalidate'>
            <center>
                <div class='buscador_portada'>
                    <div class='w100pc mt10'>
                        <div class='mt10 izquierda'>
                            <label id='estoy_buscando'><strong>ESTOY BUSCANDO</strong></label>
                        </div>
                        <div class='mt10 derecha'>
                            <a href='#mas_servicios' class='theme_button prettyphoto' rel='more_services' id='ver_mas_servicios'>Servicios adicionales...</a>
                        </div>
                    </div>
                    <div id='servicio_cuidador_main' class='w100pc'>
                        {$SERVICIOS}
                    </div>
                    <div id='popup_mas_servicios' style='display:none; overflow: hidden;'>
                        <span></span>
                        <div id='mas_servicios'>
                            <i id='cerrar_mas_servicios' class='fa fa-times' aria-hidden='true'></i>
                            $MAS_SERVICIOS
                       </div>
                    </div>
                    <div class='w100pc'>
                        <div class='mt10 izquierda'>
                            <div id='selector_tipo' class='izquierda'> 
                                <label id='cerca_de' class='izquierda'><strong>CERCA DE </strong></label> 
                                <input type='radio' name='tipo_busqueda' id='otra-localidad' value='otra-localidad' class='ml8' checked> 
                                <label for='otra-localidad'> Otra localidad</label> 
                                <input type='radio' name='tipo_busqueda' id='mi-ubicacion' value='mi-ubicacion' class='ml8' >
                                <label for='mi-ubicacion'> Mi ubicación </label> 
                            </div>
                        </div>
                    </div>
                    <div id='estado_cuidador_main'>
                        <div id='selector_locacion'>
                            <div class='grupo_selector'>
                                <div class='marco'>
                                    <div class='icono'><i class='icon-mapa embebed'></i></div>
                                    <sub>Provincias:</sub><br>
                                    <select id='estado_cuidador' name='estados' data-location='mx'>
                                        $ESTADOS
                                    </select>
                                </div>
                            </div>
                            <div class='grupo_selector'>
                                <div class='marco'>
                                    <div class='icono'><i class='icon-mapa embebed'></i></div>
                                    <sub>Distritos:</sub><br>
                                    <select id='municipio_cuidador' name='municipios'>
                                        <option value=''>Seleccione primero una provincia</option>
                                    </select>
                                    <input type='hidden' id='municipio_cache' name='municipio_cache'>
                                </div>
                            </div>
                        </div>
                        <div id='selector_coordenadas_x' class='hide'>
                            <input type='text' id='latitud' name='latitud'>
                            <input type='text' id='longitud' name='longitud'>
                        </div>
                    </div>

                    <div id='estado_cuidador_main'>
                        <div id='selector_locacion'>
                            <div class='grupo_selector'>
                                <div class='marco'>
                                    <div class='icono'><i class='icon-calendario embebed'></i></div>
                                    <!--
                                    <sub>Desde cuando:</sub><br>
                                    <input type='text' placeholder='Desde' id='checkin' name='checkin' class='fechas' min='".date("Y-m-d")."'>
                                    -->
                                    <input type='text' id='checkin' name='checkin' placeholder='DESDE' value='' class='date_from' readonly>
                                 </div>

                                <div id='val_error_fecha_ini' class='no_error'>
                                    Debe ingresar la fecha de inicio
                                </div>

                            </div>
                            <div class='grupo_selector'>
                                <div class='marco'>
                                    <div class='icono'><i class='icon-calendario embebed'></i></div>
                                    <!--
                                    <sub>Hasta cuando:</sub><br>
                                    <input type='text' placeholder='Hasta' id='checkout' name='checkout' class='fechas' disabled>
                                    -->
                                    <input type='text' id='checkout' name='checkout' placeholder='HASTA' value='' class='date_to' readonly>
                                </div>

                                <div id='val_error_fecha_fin' class='no_error'>
                                    Debe ingresar la fecha de finalización
                                </div>
                            </div>
                        </div>
                        <div id='selector_coordenadas_x' class='hide'>
                            <input type='text' id='latitud' name='latitud'>
                            <input type='text' id='longitud' name='longitud'>
                        </div>
                    </div>

                    <div class='w100pc'>
                       <div class='mt10 izquierda'>
                            <label id='tamano_mascota'><strong>TAMAÑO DE MI MASCOTA</strong></label>
                        </div>
                    </div>
                    <div id='tamano_mascota_main' style='overflow: hidden;'>
                        {$TAMANOS}
                    </div>
                    <div class='w100pc text-center'>
                        <div class='boton_buscar'>
                            <a class='theme_button button pfsearch' id='pf-search-button-manual'><i class='pfadmicon-glyph-627'></i> Buscar Cuidador</a>
                        </div>
                    </div>
                </div>
            </center>
        </form>
    </div>

    <script type='text/javascript'>
        var hasGPS=false;

        (function($) {
            'use strict';

            $('#ver_mas_servicios').on('click', function(){
                if( $('#popup_mas_servicios').css('display') == 'none' ){
                    $('#popup_mas_servicios').css('display', 'block');
                }else{
                    $('#popup_mas_servicios').css('display', 'none');
                }
            });

            $('#cerrar_mas_servicios').on('click', function(){
                $('#popup_mas_servicios').css('display', 'none');
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(coordenadas);
            } else {
                $('#selector_locacion').removeClass('hide');
                $('#selector_coordenadas').addClass('hide');
                $('#selector_tipo').addClass('hide');
            }
            if(navigator.platform.substr(0, 2) == 'iP') $('html').addClass('iOS');
            $(function(){
                var edos = $('#estado_cuidador').val();
                if($('#otra-localidad').prop( 'checked' )){
                    $('#selector_locacion').removeClass('hide');
                    $('#selector_coordenadas').addClass('hide');
                    if( edos != ''){
                        $('#estado_cuidador > option[value='+edos+']').attr('selected', 'selected');
                    }
                }
                $('#otra-localidad').click(function(){
                    $('#selector_locacion').removeClass('hide');
                    $('#selector_coordenadas').addClass('hide');
                });
                $('#mi-ubicacion').click(function(){
                    $('#selector_coordenadas').addClass('hide');
                    $('#selector_locacion').addClass('hide');
                });
                function cargar_municipios(CB){
                    var estado_id = jQuery('#estado_cuidador').val();
                    if( estado_id != '' ){
                        jQuery.getJSON( 
                            '".get_bloginfo( 'template_directory', 'display' )."/vlz/ajax_municipios.php', 
                            {estado: estado_id} 
                        ).done(
                            function( data, textStatus, jqXHR ) {
                                var html = \"<option value=''>Seleccione un distrito</option>\";
                                jQuery.each(data, function(i, val) {
                                    html += '<option value='+val.id+'>'+val.name+'</option>';
                                });
                                jQuery('#municipio_cuidador').html(html);

                                if( CB != undefined) {
                                    CB();
                                }
                            }
                        ).fail(
                            function( jqXHR, textStatus, errorThrown ) {
                                console.log( 'Error: ' +  errorThrown );
                            }
                        );
                    }
                }
                jQuery('#estado_cuidador').on('change', function(e){
                    cargar_municipios();
                });
                cargar_municipios(function(){
                    jQuery(\"#municipio_cuidador > option[value='\"+jQuery('#municipio_cache').val()+\"']\").attr('selected', 'selected');
                });
                jQuery('#municipio_cuidador').on('change', function(e){
                    jQuery('#municipio_cache').attr('value', jQuery('#municipio_cuidador').val() );
                });

                $('.boton_servicio > input:checkbox').each(function(index){
                    var servicio = $(this).attr('data-key');
                    var activo = $(this).prop('checked');
                    if(activo) $(this).parent().addClass('activo');
                    else $(this).parent().removeClass('activo');
                });
                $('.boton_portada > input:checkbox').on('change',function(e){
                    var servicio = $(this).attr('data-key');
                    var activo = $(this).prop('checked');
                    if(activo) $('.servicio_cuidador_'+servicio).parent().addClass('activo');
                    else $('.servicio_cuidador_'+servicio).parent().removeClass('activo');
                });
                $('.modal').fancybox({
                    maxWidth: 340
                });

                function error_home_2(error, id){
                     if(error){
                         jQuery('#'+id).removeClass('no_error');
                         jQuery('#'+id).addClass('error');
                     }else{
                         jQuery('#'+id).removeClass('error');
                         jQuery('#'+id).addClass('no_error');
                     }
                 }

                function seleccionar_checkin() {
                    if( jQuery('#checkin').val() != '' ){
                        var fecha = new Date();
                        jQuery('#checkout').attr('disabled', false);

                        var ini = String( jQuery('#checkin').val() ).split('-');
                        var inicio = new Date( parseInt(ini[2]), parseInt(ini[1]), parseInt(ini[0]) );

                        console.log( jQuery('#checkout').val() );

                        var checkout = String( jQuery('#checkout').val() ).split('-');
                        var checkout = new Date( checkout[0]+'-'+checkout[1]+'-'+checkout[2] );

                        if( jQuery('#checkout').val() != '' ){
                            if( Math.abs(checkout.getTime()) < Math.abs(inicio.getTime()) ){
                                jQuery('#checkout').attr('value', ini[0]+'-'+ini[1]+'-'+ini[2] );
                            }
                        }else{
                            jQuery('#checkout').attr('value', ini[0]+'-'+ini[1]+'-'+ini[2] );
                        }
                            
                        jQuery('#checkout').attr('min', ini[0]+'-'+ini[1]+'-'+ini[2] );
                        error_home_2(false, 'val_error_fecha_ini');
                        error_home_2(false, 'val_error_fecha_fin');
                    }else{
                        error_home_2(true, 'val_error_fecha_ini');
                        jQuery('#checkout').val('');
                        jQuery('#checkout').attr('disabled', true);
                    }
                }

                jQuery('#checkin').on('change', function(e){
                    seleccionar_checkin();
                });

                 jQuery('#checkout').on('change', function(e){
                    if( jQuery('#checkout').val() != '' ){
                        error_home_2(false, 'val_error_fecha_fin');
                    }else{
                        error_home_2(true, 'val_error_fecha_fin');
                    }
                });

                if( jQuery('#checkin').val() != '' ){
                      seleccionar_checkin();
                }


            });


        })(jQuery);

        function coordenadas(position){
            if(position.coords.latitude != '' && position.coords.longitude != '') {
                document.getElementById('latitud').value=position.coords.latitude;
                document.getElementById('longitud').value=position.coords.longitude;        
            } else {
                var mensaje = 'No es posible leer su ubicación,\nverifique si su GPS está encendido\ny vuelva a recargar la página.'+$('#latitud').val()+','+$('#longitud').val();
                alert(mensaje);        
            }
        }
    </script>
    
    ";

    echo comprimir_styles($HTML);
?>

<script type="text/javascript">
    var fecha = new Date();
    jQuery(document).ready(function(){
        function initCheckin(date, actual){
            if(actual){
                jQuery('#checkout').datepick({
                    dateFormat: 'dd/mm/yyyy',
                    defaultDate: date,
                    selectDefaultDate: true,
                    minDate: date,
                    onSelect: function(xdate) {

                    },
                    yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
                    firstDay: 1,
                    onmonthsToShow: [1, 1]
                });
                // jQuery('#checkout').focus();
            }else{
                jQuery('#checkout').datepick({
                    dateFormat: 'dd/mm/yyyy',
                    minDate: date,
                    onSelect: function(xdate) {

                    },
                    yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
                    firstDay: 1,
                    onmonthsToShow: [1, 1]
                });
                // jQuery('#checkout').focus();
            }
        }

        jQuery('#checkin').datepick({
            dateFormat: 'dd/mm/yyyy',
            minDate: fecha,
            onSelect: function(date1) {
                var ini = jQuery('#checkin').datepick( "getDate" );
                var fin = jQuery('#checkout').datepick( "getDate" );
                if( fin.length > 0 ){
                    var xini = ini[0].getTime();
                    var xfin = fin[0].getTime();
                    if( xini > xfin ){
                        jQuery('#checkout').datepick('destroy');
                        initCheckin(date1[0], true);
                    }else{
                        jQuery('#checkout').datepick('destroy');
                        initCheckin(date1[0], false);
                    }
                }else{
                    jQuery('#checkout').datepick('destroy');
                    initCheckin(date1[0], true);
                }
            },
            yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
            firstDay: 1,
            onmonthsToShow: [1, 1]
        });

        jQuery('#checkout').datepick({
            dateFormat: 'dd/mm/yyyy',
            minDate: fecha,
            onSelect: function(xdate) {

            },
            yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
            firstDay: 1,
            onmonthsToShow: [1, 1]
        });
    });


    /*
     jQuery(document).on('click','input[type=\'text\'].fechas',function(event){
     jQuery(this).blur();
     jQuery(this).attr('type','date');
     jQuery(this).focus();
     });
     */
</script>

