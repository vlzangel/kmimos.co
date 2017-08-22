<style type="text/css">
    table {
        border: 0;
        background-color: #FFF !important;
    }
    td {
        border: 0 !important;
    }
</style>
<?php

global $current_user;
global $wpdb;
global $redirect_to;

date_default_timezone_set('America/Bogota');

$actual = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$referencia = $_SERVER['HTTP_REFERER'];

$user_id = $current_user->ID;

$propietario = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".$_GET['id'] );

if( $propietario == $user_id ){

    echo "         
        <style>
            .vlz_modal{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; display: table; z-index: 10000; background: rgba(0, 0, 0, 0.8); vertical-align: middle !important; }
            h1{ font-size: 18px; }
            h2{ font-size: 16px; }
            .vlz_modal_interno{ display: table-cell; text-align: center; vertical-align: middle !important; }
            .vlz_modal_ventana{ position: relative; display: inline-block; width: 60%!important; text-align: left; box-shadow: 0px 0px 4px #FFF; border-radius: 5px; z-index: 1000; }
            .vlz_modal_titulo{ background: #FFF; padding: 15px 10px; font-size: 18px; color: #52c8b6; font-weight: 600; border-radius: 5px 5px 0px 0px; }
            .vlz_modal_contenido{ background: #FFF; height: 450px; box-sizing: border-box; padding: 5px 15px; border-top: solid 1px #d6d6d6; border-bottom: solid 1px #d6d6d6; overflow: auto; text-align: justify; }
            .vlz_modal_pie{ background: #FFF; padding: 15px 10px; border-radius: 0px 0px 5px 5px; }
            .vlz_modal_fondo{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 500; }
            .vlz_boton_siguiente{ padding: 10px 50px; background-color: #a8d8c9; display: inline-block; font-size: 16px; border: solid 1px #2ca683; border-radius: 3px; float: right; cursor: pointer; } 
            @media screen and (max-width: 750px){ .vlz_modal_ventana{ width: 90% !important; } }
        </style>              
        <div id='jj_modal_ir_al_perfil' class='vlz_modal'>
            <div class='vlz_modal_interno'>
                <div class='vlz_modal_fondo' onclick='jQuery('#jj_modal_ir_al_perfil').css('display', 'none');'></div>
                <div class='vlz_modal_ventana jj_modal_ventana'S>
                    <div class='vlz_modal_titulo'>¡Oops!</div>
                    <div class='vlz_modal_contenido' style='height: auto;'>
                        <h1 align='justify'>No puedes realizarte solicitudes a tí mismo.</h1>
                        <h2 align='justify'>Has click <a href='".get_home_url()."/busqueda/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para buscar entre cientos de cuidadores certificados kmimos.<h2>
                    </div>
                    <div class='vlz_modal_pie' style='border-radius: 0px 0px 5px 5px!important; height: 70px;'>
                        <a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
                    </div>
                </div>
            </div>
        </div>
    ";
    exit;

}

if($_SESSION['token_mail'] != "" ){

    echo kmimos_style(array('formularios'));

    $post_id = $_GET['id'];

    echo "
        <div style='display: block; margin: 0px auto; max-width: 600px;'>
            ".$_SESSION['token_mail']."

            <div style='text-align: center;'>
                <a href='".get_home_url()."/conocer-al-cuidador/?id=".$post_id."' class='kmimos_boton'>
                    Finalizar
                </a>
            </div>
        </div>";

    $_SESSION['token_mail'] = "";

}else{

    $post_id = $_GET['id'];

    if($post_id == ''){
        echo "Selecciona el cuidador que deseas conocer";
        return false;
    }

    $_SESSION['caregiver_request']=$post_id;
    $pasos = array(false, false, false);

    if($user_id != 0) {
        $pasos[0] = true;
    }

    $petsitter = get_post( $post_id );
    $cuidador_post   = get_post($post_id);

    // busca las mascotas del usuario
        $args = array(
            'post_type'     =>  'pets'      , 
            'post_status'   =>  'publish'   ,
            'meta_key'      =>  'owner_pet' , 
            'meta_value'    =>  $user_id
        );
        $loop =new WP_Query($args);
        $pets = $loop->posts;
        $pasos[1]=kmimos_user_info_ready($user_id);

        if(count($pets)>0){
            $pasos[2]=true;
        }

    $paso1 = ($pasos[0]) ? '<i class="pfadmicon-glyph-469 green"></i> (Iniciaste sesión)':'<i class="pfadmicon-glyph-476 red"></i> <a href="'.wp_login_url( 'conocer-al-cuidador/?id='.$post_id ).'" class="kmm-login-register" target="_self">Inicia Sesión</a>';
    $paso2 = ($pasos[1]) ? '<i class="pfadmicon-glyph-469 green"></i> (Todo en orden)':'<i class="pfadmicon-glyph-476 red"></i> <a href="'.get_home_url().'/perfil-usuario/?ua=profile" target="_blank" class="kmi_link" class="kmi_link"><strong>Ir a mi perfil</strong></a>';
    $paso3 = ($pasos[2]) ? '<i class="pfadmicon-glyph-469 green"></i> (Tienes '.count($pets).' mascotas)': '<i class="pfadmicon-glyph-476 red"></i> <a href="'.get_home_url().'/perfil-usuario/?ua=mypets" target="_blank" class="kmi_link">Ir a mis mascotas</a>'; ?>

    <style>
        .green { color: forestgreen !important; }
        .red { color:crimson !important; }
        .kmm-login-register { width: 160px; display: inline-block; padding: 0px; }
        .error { color: red; font-weight: bolder;}
        ul { list-style: none; padding: 0px; }
        input[type=submit] { max-width: 320px; margin: 20px auto; }
        input[type=submit]:disabled { background-color: #cccccc; }
    /*-------------------------Jaurgeui----------------------------*/
        .kmi_link{
            font-size: initial; 
            color: #54c8a7;
        }

        a.kmi_link:hover{
            color:#138675!important;
        }
    /*-------------------------Jaurgeui----------------------------*/
    </style>

    <h1>Solicitud para conocer a <?php echo $cuidador_post->post_title; ?></h1>
    <p>Para poder conocer a un cuidador primero tienes que:<p>
    <ol>
        <li>Haberte registrado en nuestro portal y haber iniciado sesión.   <?php echo $paso1;                      ?></li>
        <li>Completar todos los datos requeridos en tu perfil.              <?php echo ($pasos[0]) ? $paso2 : '';   ?></li>
        <li>Completar tu lista de mascotas en tu perfil.                    <?php echo ($pasos[0]) ? $paso3 : '';   ?></li>
    </ol>

    <form id="request_form" method="post" action="<?php echo get_home_url(); ?>/conocer-al-cuidador-v">
        <table cellspacing=0 cellpadding=0>
            <tr>
                <td>¿Cuando deseas conocer al cuidador?</td>
                <td><input type="date" id="meeting_when" name="meeting_when" style="width: 100%; padding: 5px; line-height: 1;" required min="<?php echo date("Y-m-d", strtotime('Now')) ?>"></td>
            </tr>
            <tr>
                <td>¿A qué hora te convendría la reunión?</td>
                <td>
                    <select id="meeting_time" name="meeting_time" style="width: 100%; padding: 5px;" required>
                        <?php
                            $dial = " a.m.";
                            for ($i=7; $i < 20; $i++) {

                                $t = $i;
                                if( $t > 12 ){ 
                                    $t = $t-12; $dial = ' p.m.';
                                }else{
                                    if($t == 12){
                                        $dial = ' m';
                                    }
                                }
                                if( $t < 10 ){ $x = "0"; }else{ $x = ''; }
                                if( $i < 10 ){ $xi = "0"; }else{ $xi = ''; }

                                echo '<option value="'.$xi.$i.':00:00" data-id="'.$i.'">'.$x.$t.':00 '.$dial.'</option>';
                                if( $i != 19){
                                    echo '<option value="'.$xi.$i.':30:00" data-id="'.$i.'.5">'.$x.$t.':30 '.$dial.'</option>';
                                }
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>¿Dónde deseas conocer al cuidador?</td>
                <td><input type="text" id="meeting_where" name="meeting_where" style="width: 100%; padding: 5px;" required></td>
            </tr>
            <tr>
                <td>¿Qué mascotas requieren el servicio?</td>
                <td>
                    <ul><?php
                        $mascotas = kmimos_get_my_pets($user_id);
                        $keys = explode(',',$mascotas['list']);
                        $values = explode(',',$mascotas['names']);
                        for($i=0; $i<$mascotas['count']; $i++){ ?>
                            <li>
                                <input type="checkbox" name="pet_ids[]" id="pet_<?php echo $i; ?>" value="<?php echo $keys[$i]; ?>">
                                <label for="pet_<?php echo $i; ?>"><?php echo $values[$i]; ?></label>
                            </li> <?php
                        } ?>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>¿Desde cuando requieres el servicio?</td>
                <td><input type="date" id="service_start" name="service_start" style="width: 100%; padding: 5px; line-height: 1;" required min="<?php echo date("Y-m-d", strtotime('now +1 day')) ?>"></td>
            </tr>
            <tr>
                <td>¿Hasta cuando requieres el servicio?</td>
                <td><input type="date" id="service_end" name="service_end" style="width: 100%; padding: 5px; line-height: 1;" required min="<?php echo date("Y-m-d", strtotime('now +1 day')) ?>"></td>
            </tr>
        </table>
        <input type="hidden" name="funcion" value="request">
        <input type="hidden" name="id" value="<?php echo $post_id; ?>">
        <input type="submit" id="request-button" class="boton_aplicar_filtros" value="Enviar solicitud"<?php if(($pasos[0] && $pasos[1] && $pasos[2])==false) echo " disabled"; ?>>
    </form>

    <script>
        jQuery.noConflict();
        jQuery(document).ready(document).ready(function() {
            jQuery("#meeting_when").change(function(){
                var dt = new Date(jQuery(this).val());
                dt.setDate( parseInt(dt.getDate()) + 1);
                var r = dt.toISOString().split('T');
                jQuery("#service_start").attr("min", r[0]);

            });
            jQuery("#service_start").change(function(){
                jQuery("#service_end").attr("min",jQuery(this).val());
            });
            jQuery('#request_form').validate({ // initialize the plugin
                rules: {
                    meeting_when: {
                        required: true,
                        date: true,
                    },
                    meeting_where: {
                        required: true,
                        minlength: 2,
                    },
                    type_service: {
                        required: true,
                    },
                    'pet_ids[]': {
                        required: true,
                        minlength: 1,
                    },
                    service_start: {
                        required: true,
                        date: true,
                    },
                    service_end: {
                        required: true,
                    },
                },  
                messages:{
                    meeting_when:{
                       min: "La fecha no puede ser menor a {0}",
                       required:"Este campo es requido"
                    },
                    meeting_where:{
                       minlength:"Debe ingresar como mínimo {0} carácteres",
                       required:"Este campo es requido" 
                    },
                    'pet_ids[]': {
                        required: "Este campo es requido",
                    },
                    service_start:{
                       min: "La fecha no puede ser menor a {0}",
                       required:"Este campo es requido"
                    },
                    service_end:{
                       min: "La fecha no puede ser menor a {0}",
                       required:"Este campo es requido"
                    },
                }
            });
        });
    </script> <?php

}

?>