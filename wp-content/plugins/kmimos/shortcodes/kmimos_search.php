<?php

    //[kmimos_search]

    $data = get_data_home();

    extract($data);

    $home = get_home_url();
    wp_enqueue_style('home_kmimos', $home."/wp-content/themes/pointfinder/css/home_kmimos.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', $home."/wp-content/themes/pointfinder/css/responsive/home_responsive.css", array(), '1.0.0');
    wp_enqueue_script('buscar_home', $home."/wp-content/plugins/kmimos/javascript/buscar.js", array(), '1.0.0');

    $HTML = "
    <script type='text/javascript'> var URL_MUNICIPIOS = '".get_bloginfo( 'template_directory', 'display' )."/vlz/ajax_municipios.php'; </script>

    <div class='modal_video'>
        <div class='modal_container'>
            <div class='modal_box'>
                <i id='close_video' class='fa fa-times' aria-hidden='true'></i>
                <iframe width='100%' height='100%' src='' frameborder='0' allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <div class='modal_servicios'>
        <div class='modal_container'>
            <div class='modal_box'>
                <i id='close_mas_servicios' class='fa fa-times' aria-hidden='true'></i>
                ".$MAS_SERVICIOS."
            </div>
        </div>
    </div>

    <div class='slider_home' style='background-image: url({$home}/wp-content/themes/pointfinder/images/slider_home/fondo_2.png)'>
        <div class='slider_home_container'>
            <ul onclick='show_video();' style='width: 200%;' class='slider_home_box'>
                <li style='background-image: url({$home}/wp-content/themes/pointfinder/images/slider_home/slider_2_1.png)'></li>
                <li style='background-image: url({$home}/wp-content/themes/pointfinder/images/slider_home/slider_2_2.png)'></li>
            </ul>
        </div>
    </div>

    <div class='buscar_box'>
        <form id='buscador' class='container' method='POST' action='".get_home_url()."/busqueda'>
            
            <div class='adicionales_container'>
                <label class='titulo_buscar_label'>ESTOY BUSCANDO</label>
                <div class='adicionales_button'>Servicios adicionales...</div>
            </div>

            <div class='servicios_container'>
                ".$SERVICIOS."
            </div>

            <div class='selector_tipo_container'>
                <label  for='otra-localidad' class='por_ubicacion check_select'>
                    Otra localidad
                    <input type='radio' name='tipo_busqueda' id='otra-localidad' value='otra-localidad' checked=''>
                </label>
                <label  for='mi-ubicacion' class='por_ubicacion'>
                    Mi ubicación
                    <input type='radio' name='tipo_busqueda' id='mi-ubicacion' value='mi-ubicacion'>
                </label>
            </div>

            <div class='selects_ubicacion_container'>
                <select id='estado_cuidador' name='estado_cuidador'> {$ESTADOS} </select>
                <select id='municipio_cuidador' name='municipio_cuidador'> <option value=''>Seleccione primero un estado</option> </select>
            </div>

            <div class='servicios_container'>
                ".$TAMANOS."
            </div>

            <div id='boton_buscar' class='boton_buscar'>
                <i class='pfadmicon-glyph-627'></i>
                Buscar Cuidador
            </div>
        </form>
    </div>

    <div class='beneficios_container'>
        <h1 style='text-align: center;'><span style='color: #01d7df;'><strong>Beneficios</strong></span></h1>
        <ul class='beneficios_home'>
            <li>
                <div>
                    <p style='text-align: center;'>
                        <img src='https://www.kmimos.com.mx/iconos/boton-1.png' alt='boton-1' />
                    </p>
                    <h3 style='text-align: center;'>
                        <span style='color: #90a4ae;'>Cuidadores</span><br />
                        <span style='color: #90a4ae;'>Certificados</span>
                    </h3>
                </div>
            </li>  

            <li>
                <div>
                    <p style='text-align: center;'>
                        <img src='https://www.kmimos.com.mx/iconos/boton-2.png' alt='boton-2' />
                    </p>
                    <h3 style='text-align: center;'>
                        <span style='color: #90a4ae;'>Fotografías</span><br />
                        <span style='color: #90a4ae;'>y videos diarios</span>
                    </h3>
                </div>
            </li>  

            <li>
                <div>
                    <p style='text-align: center;'>
                        <img src='https://www.kmimos.com.mx/iconos/boton-3.png' alt='boton-3' />
                    </p>
                    <h3 style='text-align: center;'>
                        <span style='color: #90a4ae;'>Atención</span><br />
                        <span style='color: #90a4ae;'>personalizada</span>
                    </h3>
                </div>
            </li>  

            <li>
                <div>
                    <p style='text-align: center;'>
                        <img src='https://www.kmimos.com.mx/iconos/boton-4.png' alt='boton-4' />
                    </p>
                    <h3 style='text-align: center;'>
                        <span style='color: #90a4ae;'>Cobertura veterinaria</span><br />
                        <span style='color: #90a4ae;'>para tu mascota</span>
                    </h3>
                </div>
            </li> 
        </ul> 
    </div>


    <div id='bloque_testimonios'>
        <h1>Testimonios</h1>
        <div class='testimonios_container'>
            <ul style='width: {$WIDTH_SLIDER}%' class='testimonios_box'>
                ".$TESTIMONIOS."
            </ul>
        </div>
    </div>

    <div class='slider_logos_container'>
        <ul class='slider_logos_box'>
            <li style='background-image: url({$home}/wp-content/uploads/2017/02/expansion-360x150.png)'></li>
            <li style='background-image: url({$home}/wp-content/uploads/2017/02/reforma-360x150-360x150.png)'></li>
            <li style='background-image: url({$home}/wp-content/uploads/2017/02/mural-360x150-360x150.png)'></li>
            <li style='background-image: url({$home}/wp-content/uploads/2017/02/entrepreneur-360x150.png)'></li>

            <li style='background-image: url({$home}/wp-content/uploads/2017/02/el-universal-360x150-360x150.png)'></li>
            <li style='background-image: url({$home}/wp-content/uploads/2017/02/el-norte-360x150-360x150.png)'></li>
            <li style='background-image: url({$home}/wp-content/uploads/2017/02/el-financiero-360x150-360x150.png)'></li>
            <li style='background-image: url({$home}/wp-content/uploads/2017/02/expansion-360x150.png)'></li>
            <li id='logo_solo_movil' style='background-image: url({$home}/wp-content/uploads/2017/02/expansion-360x150.png)'></li>
        </ul>
    </div>";

    echo comprimir_styles($HTML);
?>

