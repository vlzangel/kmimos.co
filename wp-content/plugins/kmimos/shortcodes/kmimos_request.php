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


//VALIDATE TOKEN
$request_id = 0;
if(isset($_SESSION['caregiver_request']) && isset($_POST['id'])){
    //if($_SESSION['caregiver_request']==$_POST['id']){}
    unset($_SESSION['caregiver_request']);

    /*
        Data General
    */

    $estilos = "
        <style>
            p{
                margin: 5px 0px;
                text-align: justify;
            }
            table {
                margin-left: 10px;
            }
            h2{
                margin: 10px 0px 0px;
            }
            td{
                padding: 0px !important;
            }
        </style>
    ";
    //jaurgeui
    $post_id    = $_POST['id'];
    $pet_ids    = $_POST['pet_ids'];

    $cuidador_post   = get_post($post_id);
    $nombre_cuidador = $cuidador_post->post_title;


    $datos_cuidador  = get_user_meta($cuidador_post->post_author);
    $telf_cuidador = $datos_cuidador["user_phone"][0];
    if( $telf_cuidador == "" ){
        $telf_cuidador = $datos_cuidador["user_mobile"][0];
    }
    if( $telf_cuidador == "" ){
        $telf_cuidador = "No registrado";
    }

    $datos_cliente = get_user_meta($user_id);
    $cliente  = $datos_cliente['first_name'][0].' '.$datos_cliente['last_name'][0];

    $telf_cliente = $datos_cliente["user_phone"][0];
    if( isset($datos_cliente["user_mobile"][0]) ){
        $separador = (!empty($telf_cliente))? ' / ': "";
        $telf_cliente .= $separador . $datos_cliente["user_mobile"][0];
    }
    if( $telf_cliente == "" ){
        $telf_cliente = "No registrado";
    }

    $inicio = "08";
    $fin    = "22";
    $rango  = 6;

    $hora_actual = strtotime("now");
    $xhora_actual = date("H", $hora_actual);

    if( ($xhora_actual-$rango) < $inicio ){
        $hoy = date("d-m-Y", $hora_actual);
        $hoy = explode("-", $hoy);
        $hoy = strtotime($hoy[0]."-".$hoy[1]."-".$hoy[2]." ".$inicio.":00:00");
        $ayer = date("d-m-Y", strtotime("-1 day"));
        $ayer = explode("-", $ayer);
        $ayer = strtotime($ayer[0]."-".$ayer[1]."-".$ayer[2]." ".$fin.":00:00");
        $exceso = $hoy-($hora_actual-($rango*3600));
        $fecha_cancelacion = $ayer-$exceso;
    }else{
        $fecha_cancelacion = ($hora_actual-($rango*3600));
    }

    $new_post = array(
        'post_type'     =>  'request',
        'post_status'   =>  'pending',
        'post_title'    =>  'Solicitud conocer cuidador "'.$nombre_cuidador.'" del '.date('d-m-Y H:i'),
        'post_date'     =>  date("Y-m-d H:i:s"),
        'post_modified' =>  date("Y-m-d H:i:s")
    );

    $request_id = wp_insert_post($new_post);

    $new_postmeta = array(
        'request_type'          => 1,
        'request_status'        => 1,
        'requester_user'        => $user_id,
        'requested_petsitter'   => $post_id,
        'request_date'          => date('d-m-Y'),
        'request_time'          => date('H:i:s'),
        'request_next'          => $rango,
        'next_time'             => date("d-m-Y H:i:s", $fecha_cancelacion),
        'meeting_when'          => $_POST['meeting_when'],
        'meeting_time'          => $_POST['meeting_time'],
        'meeting_where'         => $_POST['meeting_where'],
        'pet_ids'               => $pet_ids,
        'service_start'         => $_POST['service_start'],
        'service_end'           => $_POST['service_end'],
    );

    foreach($new_postmeta as $key => $value){
        update_post_meta($request_id, $key, $value);
    }

    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '".$post_id."'");

    $email_cuidador = $cuidador->email;
    $email_cliente  = $current_user->user_email;
    $email_admin    = "contactoco@kmimos.la";

    $metas_cuidador = get_user_meta($cuidador->user_id);

    $telf_cuidador = $metas_cuidador["user_phone"][0];
     if( isset($metas_cuidador["user_mobile"][0]) ){
        $separador = (!empty($telf_cuidador))? ' / ': "";
        $telf_cuidador .= $separador . $metas_cuidador["user_mobile"][0];
    }
    if( $telf_cuidador == "" ){
        $telf_cuidador = "No registrado";
    }

    $asunto     = 'Solicitud para conocer a cuidador';

    $saludo_admin   = '<p><strong>Hola,</strong></p>';
    $service_id     = $_POST['type_service'];
    $service        = get_term( $service_id, 'product_cat' );

    $mascotas = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID IN ( '".implode(",", $pet_ids)."' )");
    $detalles_mascotas = "";
    $detalles_mascotas .= '
        <table style="width:100%; margin-left: 0px; margin-top: 5px;" cellspacing=0 cellpadding=0>
            <tr>
                <th style="padding: 3px; background: #00d2b7;"> <strong>Nombre</strong> </th>
                <th style="padding: 3px; background: #00d2b7;"> <strong>Raza</strong> </th>
                <th style="padding: 3px; background: #00d2b7;"> <strong>Edad</strong> </th>
                <th style="padding: 3px; background: #00d2b7;"> <strong>Tamaño</strong> </th>
                <th style="padding: 3px; background: #00d2b7;"> <strong>Comportamiento</strong> </th> 
            </tr>
    ';

    $comportamientos_array = array(
        "pet_sociable"           => "Sociables",
        "pet_sociable2"          => "No sociables",
        "aggressive_with_pets"   => "Agresivos con perros",
        "aggressive_with_humans" => "Agresivos con humanos",
    );
    $tamanos_array = array(
        "Pequeño",
        "Mediano",
        "Grande",
        "Gigante"
    );
    if( count($mascotas) > 0 ){
        foreach ($mascotas as $key => $mascota) {
            $data_mascota = get_post_meta($mascota->ID);

            $temp = array();
            foreach ($data_mascota as $key => $value) {

                switch ($key) {
                    case 'pet_sociable':
                        if( $value[0] == 1 ){
                            $temp[] = "Sociable";
                        }else{
                            $temp[] = "No sociable";
                        }
                    break;
                    case 'aggressive_with_pets':
                        if( $value[0] == 1 ){
                            $temp[] = "Agresivo con perros";
                        }
                    break;
                    case 'aggressive_with_humans':
                        if( $value[0] == 1 ){
                            $temp[] = "Agresivo con humanos";
                        }
                    break;
                }

            }

            $nacio = strtotime(date($data_mascota['birthdate_pet'][0]));
            $diff = abs(strtotime(date('Y-m-d')) - $nacio);
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $edad.= $years.' año(s) '.$months.' mes(es)';

            $raza = $wpdb->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet'][0]);

            $detalles_mascotas .= '
                <tr>
                    <td style="border-bottom: solid 1px #00d2b7; padding: 3px;" valign="top"> '.$data_mascota['name_pet'][0].'</td>
                    <td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$raza.'</td>
                    <td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$edad.'</td>
                    <td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.$tamanos_array[ $data_mascota['size_pet'][0] ].'</td>
                    <td style="padding: 3px; border-bottom: solid 1px #00d2b7;" valign="top"> '.implode("", $temp).'</td>
                </tr>
            ';
        }
    }else{
        $detalles_mascotas .= '
            <tr>
                <td colspan="5">No tiene mascotas registradas.</td>
            </tr>
        ';
    }
    $detalles_mascotas .= '</table>';

    $mascotas = (count($pet_ids) == 1) ? '<h2 style="color: #557da1; font-size: 16px;">Detalles de la mascota: </h2>'.$detalles_mascotas : '<h2 style="color: #557da1; font-size: 16px;">Detalles de las mascotas: </h2>'.$detalles_mascotas;

    /*
        Cuidador
    */

        $mensaje_cuidador = $estilos.'
            <h2 style="color: #557da1; font-size: 16px;">Hola '.$nombre_cuidador.'</h2>
            <p>Recibimos una solicitud del cliente <strong>'.$cliente.'</strong> para conocerte.</p>

            <p><strong>Código de la solicitud: '.$request_id.'</strong></p>

            <center>
                <p><strong>¿ACEPTAS ESTA SOLICITUD?</strong></p>
                <table>
                    <tr>
                        <td>
                            <a href="'.get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$request_id.'&s=1" style="text-decoration: none; padding: 7px 0px; background: #00d2b7; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">Aceptar</a>
                        </td>
                        <td>
                            <a href="'.get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$request_id.'&s=0" style="text-decoration: none; padding: 7px 0px; background: #dc2222; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">Rechazar</a>
                        </td>
                    </tr>
                </table>
            </center>

            <h2 style="color: #557da1; font-size: 16px;">Datos del Cliente</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Nombre: </strong></td>
                    <td>'.$cliente.'</td>
                </tr>
                <tr>   
                    <td><strong>Teléfono: </strong></td>
                    <td>'.$telf_cliente.'</td>
                </tr>
                <tr>   
                    <td><strong>Correo: </strong></td>
                    <td>'.$email_cliente.'</td>
                </tr>
            </table>

            <h2 style="color: #557da1; font-size: 16px;">Datos de la Reunión</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Fecha: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['meeting_when'])).'</td>
                </tr>
                <tr>   
                    <td><strong>Hora: </strong></td>
                    <td>'.$_POST['meeting_time'].'</td>
                </tr>
                <tr>   
                    <td><strong>Fin: </strong></td>
                    <td>'.$_POST['meeting_where'].'</td>
                </tr>
            </table>

            <h2 style="color: #557da1; font-size: 16px;">Posible fecha de Estadía</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Inicio: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['service_start'])).'</td>
                </tr>
                <tr>   
                    <td><strong>Fin: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['service_end'])).'</td>
                </tr>
            </table>
            '.$mascotas.'
            <p><strong>SIGUIENTES PASOS - Importante:</strong></p>
            <p>Puntos a considerar para tu cita:</p>
            <ul>
                <li style="text-align: justify;">Preséntate con el cliente de una manera cordial, formal y cuidando tu imagen. (Vestimenta casual)</li>
                <li style="text-align: justify;">Verifica que el perro del dueño tenga sus vacunas y te compartan su cartilla de vacunación.</li>
                <li style="text-align: justify;"><strong>IMPORTANTE:</strong> Sin cartilla de vacunación, no estarán amparados ni tú ni el perro ante los beneficios veterinarios de Kmimos</li>
                <li style="text-align: justify;">En caso de no conocerse personalmente, asegúrate de que te envíen fotos del perro que llegará a tu casa para confirmar que se observa un tamaño acorde a lo descrito por su dueño.</li>
                <li style="text-align: justify;">Por favor revisa físicamente al perrito antes de recibirlo.  Apapáchalo y recorre su piel de ser posible, para detectar de ser posible cualquier rasguño, golpe, etc. que pueda traer antes de tu que lo hubieres recibido (si detectas algo por favor menciónaselo de manera muy educada y cordial al cliente, y posteriormente envíanos fotos vía whatsapp o correo al equipo de atención al cliente de Kmimos)  Whatsapp: (+57) 315 849 2186, o al correo contactoco@kmimos.la/li>
            </ul>
            <p style="text-align: justify;">Recuerda que cada perro tiene un comportamiento diferente, por lo que deberás tener la mayor información posible sobre sus comportamientos:</p>
            <ul>
                <li style="text-align: justify;">
                    ¿Cómo es su rutina diaria?
                    <ul>
                        <li style="text-align: justify;">¿Sale a pasear?</li>
                        <li style="text-align: justify;">¿A qué hora come y hace del baño?</li>
                        <li style="text-align: justify;">etc</li>
                    </ul>
                </li>
                <li style="text-align: justify;">
                    ¿Cómo se comporta con otros seres vivos?
                    <ul>
                        <li style="text-align: justify;">¿Cómo interactúa con otros perros y personas?</li>
                        <li style="text-align: justify;">¿Cómo reacciona con un extraño?</li>
                        <li style="text-align: justify;">etc</li>
                    </ul>
                </li>
                <li style="text-align: justify;">
                    ¿Cómo identificar su animó?
                    <ul>
                        <li style="text-align: justify;">¿Cómo se comporta cuando esta triste o estresado?</li>
                        <li style="text-align: justify;">¿Qué hace su dueño cuando esta triste o estresado?</li>
                        <li style="text-align: justify;">etc</li>
                    </ul>
                </li>
            </ul>
            <center>
                <p><strong>¿ACEPTAS ESTA SOLICITUD?</strong></p>
                <table>
                    <tr>
                        <td>
                            <a href="'.get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$request_id.'&s=1" style="text-decoration: none; padding: 7px 0px; background: #00d2b7; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">Aceptar</a>
                        </td>
                        <td>
                            <a href="'.get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$request_id.'&s=0" style="text-decoration: none; padding: 7px 0px; background: #dc2222; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">Rechazar</a>
                        </td>
                    </tr>
                </table>
            </center>
            <p style="text-align: justify;">Para cualquier apoyo que necesites, o si es la primera vez que atiendes a un Kmiamigo, por favor contacta a la brevedad al equipo de Kmimos vía telefónica al (+57) 315 849 2186, o al correo contactoco@kmimos.la</p>
        ';

        $mensaje_cuidador  = kmimos_get_email_html($asunto, $mensaje_cuidador, '', true, true);

    /*
        Cliente
    */

        $mensaje_cliente = $estilos.'
            <h2 style="color: #557da1; font-size: 16px;">Hola '.$cliente.',</h2>
            <p>Recibimos la solicitud realizada para Conocer a un Cuidador Kmimos.</p>

            <p><strong>Código de la solicitud: '.$request_id.'</strong></p>

            <h2 style="color: #557da1; font-size: 16px;">Datos del Cuidador</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Nombre: </strong></td>
                    <td>'.$nombre_cuidador.'</td>
                </tr>
                <tr>   
                    <td><strong>Teléfono: </strong></td>
                    <td>'.$telf_cuidador.'</td>
                </tr>
                <tr>   
                    <td style="width: 70px;"><strong>Correo: </strong></td>
                    <td>'.$email_cuidador.'</td>
                </tr>
            </table>

            <h2 style="color: #557da1; font-size: 16px;">Datos de la Reunión</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Fecha: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['meeting_when'])).'</td>
                </tr>
                <tr>   
                    <td><strong>Hora: </strong></td>
                    <td>'.$_POST['meeting_time'].'</td>
                </tr>
                <tr>   
                    <td style="width: 70px;"><strong>Fin: </strong></td>
                    <td>'.$_POST['meeting_where'].'</td>
                </tr>
            </table>

            <h2 style="color: #557da1; font-size: 16px;">Posible fecha de Estadía</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Inicio: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['service_start'])).'</td>
                </tr>
                <tr>   
                    <td><strong>Fin: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['service_end'])).'</td>
                </tr>
            </table>

            <p><strong>Importante:</strong></p>
            <ul>
                <li style="text-align: justify;">Dentro de las siguientes 12 horas recibirás una llamada o correo electrónico  por parte del Cuidador y/o de un asesor Kmimos para confirmar tu cita o brindarte soporte con este proceso.</li>
                <li style="text-align: justify;">También podrás contactar al cuidador a partir de este momento, a los teléfonos y/o correos mostrados arriba para acelerar el proceso si así lo deseas.</li>
                <li style="text-align: justify;">Para cualquier duda y/o comentario puedes contactar al Staff Kmimos a los teléfonos (+57) 315 849 2186, o al correo contactoco@kmimos.la</li>
            </ul>
        ';

        $xmensaje_cliente = $mensaje_cliente;
        $mensaje_cliente = kmimos_get_email_html($asunto, $mensaje_cliente, 'Gracias por tu preferencia,', true, true);

    /*
        Administrador
    */


        $inicio = strtotime($_POST['service_start']);
        $diff = abs(strtotime($_POST['service_end']) - $inicio);
        $days = floor($diff / (60*60*24));

        $mensaje_admin = $estilos.'
            <h2 style="color: #557da1; font-size: 16px;">Hola Administrador,</h2>
            <p>Se ha registrado una solicitud para Conocer a un Cuidador.</p>

            <p><strong>Código de la solicitud: '.$request_id.'</strong></p>

            <h2 style="color: #557da1; font-size: 16px;">Datos del Cuidador</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Nombre: </strong></td>
                    <td>'.$nombre_cuidador.'</td>
                </tr>
                <tr>   
                    <td><strong>Teléfono: </strong></td>
                    <td>'.$telf_cuidador.'</td>
                </tr>
                <tr>   
                    <td><strong>Correo: </strong></td>
                    <td>'.$email_cuidador.'</td>
                </tr>
            </table>

            <h2 style="color: #557da1; font-size: 16px;">Datos del Cliente</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Nombre: </strong></td>
                    <td>'.$cliente.'</td>
                </tr>
                <tr>   
                    <td><strong>Teléfono: </strong></td>
                    <td>'.$telf_cliente.'</td>
                </tr>
                <tr>   
                    <td><strong>Correo: </strong></td>
                    <td>'.$email_cliente.'</td>
                </tr>
            </table>

            <h2 style="color: #557da1; font-size: 16px;">Datos de la Reunión</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Fecha: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['meeting_when'])).'</td>
                </tr>
                <tr>   
                    <td><strong>Hora: </strong></td>
                    <td>'.$_POST['meeting_time'].'</td>
                </tr>
                <tr>   
                    <td><strong>Fin: </strong></td>
                    <td>'.$_POST['meeting_where'].'</td>
                </tr>
            </table>

            <h2 style="color: #557da1; font-size: 16px;">Posible fecha de Estadía</h2>
            <table cellspacing=0 cellpadding=0>
                <tr>   
                    <td style="width: 70px;"><strong>Inicio: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['service_start'])).'</td>
                </tr>
                <tr>   
                    <td><strong>Fin: </strong></td>
                    <td>'.date('d/m/Y', strtotime($_POST['service_end'])).'</td>
                </tr>
            </table>

            <p>Número de mascotas: '.count($pet_ids).'</p>
            <p>Número de días: '.$days.'</p>
            <p>Total: '.$days*count($pet_ids).' días/mascotas</p>
        '.$mascotas;

        $mensaje_admin = kmimos_get_email_html($asunto, $mensaje_admin, 'Saludos,', false, true);

    /*
        Enviando E-mails
    */

        add_filter( 'wp_mail_from_name', function( $name ) {
            return 'Kmimos Colombia';
        });
        add_filter( 'wp_mail_from', function( $email ) {
            return 'contactoco@kmimos.la';
        });


        wp_mail( $email_cuidador, $asunto, $mensaje_cuidador, kmimos_mails_administradores());
        wp_mail( $email_cliente,  $asunto, $mensaje_cliente);
        wp_mail( $email_admin,    $asunto, $mensaje_admin);

        // $administradores = "e.celli@kmimos.la, e.celli@desdigitec.com, r.cuevas@kmimos.la, r.cuevas@desdigitec.com, r.gonzalez@kmimos.la, r.gonzalez@desdigitec.com";

        // wp_mail( $administradores, "Copia Administradores: ".$asunto, $mensaje_cuidador);
        // wp_mail( $administradores, "Copia Administradores: ".$asunto, $mensaje_cliente);
        // wp_mail( $administradores, "Copia Administradores: ".$asunto, $mensaje_admin);
    
        echo "<div style='display: block; margin: 0px auto; width: 600px;'>".$xmensaje_cliente."</div>";

}else{

    $post_id = $_GET['id'];
    $_SESSION['caregiver_request']=$post_id;

    if($post_id==''){
        echo "Selecciona el cuidador que deseas conocer";
        return false;
    }

    $pasos = array(false, false, false);

    if($user_id != 0) {
        $pasos[0] = true;
    }

    $petsitter = get_post( $post_id );

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

    <form id="request_form" method="post" action="<?php echo get_home_url(); ?>/conocer-al-cuidador/">
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
                <td><input type="date" id="service_start" name="service_start" style="width: 100%; padding: 5px; line-height: 1;" required min="<?php echo date("Y-m-d", strtotime('Now +1 day')) ?>" max="<?php echo date("Y-m-d", strtotime('Now +1 year')) ?>"></td>
            </tr>
            <tr>
                <td>¿Hasta cuando requieres el servicio?</td>
                <td><input type="date" id="service_end" name="service_end" style="width: 100%; padding: 5px; line-height: 1;" required min="<?php echo date("Y-m-d", strtotime('Now +1 day')) ?>" max="<?php echo date("Y-m-d", strtotime('Now +1 year')) ?>"></td>
            </tr>
        </table>
        <input type="hidden" name="id" value="<?php echo $post_id; ?>">
        <input type="submit" id="request-button" class="boton_aplicar_filtros" value="Enviar solicitud"<?php if(($pasos[0] && $pasos[1] && $pasos[2])==false) echo " disabled"; ?>>
    </form>

    <script>
        jQuery.noConflict(); 
        jQuery(document).ready(document).ready(function() {
            jQuery("#meeting_when").change(function(){
                jQuery("#service_start").attr("min",jQuery(this).val());
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
                        date: true,
                    }
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