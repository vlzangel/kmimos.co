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

date_default_timezone_set('America/Mexico_City');

$user_id = $current_user->ID;

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
                        minlength: 5,
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