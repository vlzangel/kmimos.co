<?php

class Class_Kmimos_Tables{

    var $args;

    function __construct($args=array()){
        global $wpdb;
        $this->args = $args;
        $this->wpdb = $wpdb;

    }


    //CLIENT
    function Create_Table_Client($client=0, $meta_client=array()){

        $user_client = get_user_by('id', $client);

        $phone = $meta_client["user_phone"][0];
        if($phone == ""){
            $phone = $meta_client["user_mobile"][0];
        }
        if($phone == ""){
            $phone = "No registrado";
        }

        $address = '';
        if($address == ''){
            $address = "No registrada";
        }

        $html = '
                    <div style="width:100%; border:1px solid #CCC; border-top:0; margin-bottom:10px; text-align:left; overflow:hidden;">
                    <table style="width:100%;" cellspacing=0 cellpadding=0>
                        <tr>
                            <th colspan="2" style="padding:3px; color:#FFF; text-align:left; background:#00d2b7;"> Detalles del cliente </th>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Nombre:</strong> </td>
                            <td style="padding:2px;">'.$meta_client["first_name"][0].' '.$meta_client["last_name"][0].'</td>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Teléfono:</strong> </td>
                            <td style="padding:2px;">'.$phone.'</td>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Correo:</strong> </td>
                            <td style="padding:2px;">'.$user_client->data->user_email.'</td>
                        </tr>
                    </table>
                    </div>';

        return $html;
    }


    //CAREGIVER
    function Create_Table_Caregiver($caregiver=0, $meta_caregiver=array()){

        $user_caregiver = get_user_by('id', $caregiver);

        $phone = $meta_caregiver["user_phone"][0];
        if($phone == ""){
            $phone = $meta_caregiver["user_mobile"][0];
        }
        if($phone == ""){
            $phone = "No registrado";
        }

        $address = '';
        if($address == ''){
            $address = "No registrada";
        }

        $html = '
                    <div style="width:100%; border:1px solid #CCC; border-top:0; margin-bottom:10px; text-align:left; overflow:hidden;">
                    <table style="width:100%;" cellspacing=0 cellpadding=0>
                        <tr>
                            <th colspan="2" style="padding:3px; color:#FFF; text-align:left; background:#00d2b7;"> Detalles del cuidador </th>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Nombre:</strong> </td>
                            <td style="padding:2px;">'.$meta_caregiver["first_name"][0].' '.$meta_caregiver["last_name"][0].'</td>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Teléfono:</strong> </td>
                            <td style="padding:2px;">'.$phone.'</td>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Correo:</strong> </td>
                            <td style="padding:2px;">'.$user_caregiver->data->user_email.'</td>
                        </tr>
                    </table>
                    </div>';

        return $html;
    }


    //SERVICE
    function Create_Table_Service($IDbooking=0){
        global $_kmimos_booking;
        //var_dump($_kmimos_booking->product);
        $method_title = $_kmimos_booking->meta_order['_payment_method_title'][0];
        $service = explode("-", $_kmimos_booking->product->post_title);
        $service = $service[0];

        $booking_start = date('d/m/Y', strtotime($_kmimos_booking->meta_booking['_booking_start'][0]));
        $booking_end = date('d/m/Y', strtotime($_kmimos_booking->meta_booking['_booking_end'][0]));

        $booking_day_start = date('d', strtotime($_kmimos_booking->meta_booking['_booking_start'][0]));
        $booking_day_end = date('d', strtotime($_kmimos_booking->meta_booking['_booking_end'][0]));
        $day = round($booking_day_end - $booking_day_start);//*(60*60*24)

        $tday = "Noche(s)";
        if( trim($service) != "Hospedaje" ){
            $tday = "Día(s)";
            $day++;
        }

        $html = '
                    <div style="width:100%; border:1px solid #CCC; border-top:0; margin-bottom:10px; text-align:left; overflow:hidden;">
                    <table style="width:100%;" cellspacing=0 cellpadding=0>
                        <tr>
                            <th colspan="2" style="padding:3px; color:#FFF; text-align:left; background:#00d2b7;"> Detalles del servicio </th>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Servicio:</strong> </td>
                            <td style="padding:2px;">'.$service.'</td>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Desde:</strong> </td>
                            <td style="padding:2px;">'.$booking_start.'</td>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Hasta:</strong> </td>
                            <td style="padding:2px;">'.$booking_end.'</td>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Duración:</strong> </td>
                            <td style="padding:2px;">'.$day.' '.$tday.'</td>
                        </tr>
                        <tr>
                            <td style="padding:2px;"> <strong>Pagado con:</strong> </td>
                            <td style="padding:2px;">'.$method_title.'</td>
                        </tr>
                    </table>
                    </div>';//round ()

        return $html;
    }

    //PETS
    function Create_Table_Pets($IDclient=0){
        $html = '';
        if($IDclient!=0){
            $table_posts=$this->wpdb->posts;
            $query="SELECT * FROM $table_posts WHERE post_author = '$IDclient' AND post_type='pets'";
            $mascotas = $this->wpdb->get_results($query);
            $html .= '
                <div style="width:100%; border:1px solid #CCC; border-top:0; margin-bottom:10px; text-align:left; overflow:hidden;">
                <table style="width:100%;" cellspacing=0 cellpadding=0 align="left">
                    <tr>
                        <th colspan="2" style="padding:3px; color:#FFF; text-align:left; background:#00d2b7;"> <strong>Detalles de las mascotas:</strong> </th>
                    </tr>
                    <tr>
                        <td style="padding:5px; border-bottom:1px solid #CCC;"> <strong>Nombre</strong> </td>
                        <td style="padding:5px; border-bottom:1px solid #CCC;"> <strong>Detalles</strong> </td>
                    </tr>
                    </div>';


            $comportamientos_array = array(
                "pet_sociable" 			 => "Sociables",
                "pet_sociable2" 		 => "No sociables",
                "aggressive_with_pets"   => "Agresivos con perros",
                "aggressive_with_humans" => "Agresivos con humanos",
            );

            $tamanos_array = array(
                "Pequeño",
                "Mediano",
                "Grande",
                "Gigante"
            );

            if(count($mascotas) > 0){
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

                    $anio = explode("-", $data_mascota['birthdate_pet'][0]);
                    $edad = date("Y")-$anio[0];

                    $query="SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet'][0];
                    $raza = $this->wpdb->get_var($query);

                    $html .= '
                        <tr>
                            <td style="padding:5px; border-bottom:1px solid #CCC;" valign="top"> '.$data_mascota['name_pet'][0].'</td>
                            <td style="padding:5px; border-bottom:1px solid #CCC;" valign="top">
                                <strong>Raza:</strong> '.$raza.'<br>
                                <strong>Edad:</strong> '.$edad.' año(s)<br>
                                <strong>Tamaño:</strong> '.$tamanos_array[ $data_mascota['size_pet'][0] ].'<br>
                                <strong>Comportamiento:</strong> '.implode("<br>", $temp).'<br>
                            </td>
                        </tr>
                    ';
                }
            }else{

                $html .= '
                    <tr>
                        <td colspan="2">No tiene mascotas registradas.</td>
                    </tr>
                ';
            }
            $html .= '</table>';
            $html .= '</div>';

        }
        return $html;
    }


    //CONFIRMATION
    function Create_Table_Confirmation($IDbooking=0){

        $html = '
                    <div style="width:100%; border:1px solid #CCC; border-top:0; margin-bottom:10px; text-align:center; overflow:hidden;">
                    <p><strong>¿ACEPTAS ESTA RESERVA?</strong></p>
                    <table style="width:100%;" cellspacing=0 cellpadding=0 align="">
                        <tr>
                            <td align="center">
                                <a href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$IDbooking.'&s=1&t=1" style="text-decoration: none; padding: 7px 0px; background: #00d2b7; border-bottom: solid 1px #cccccc; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">Aceptar</a>
                            </td>
                            <td align="center">
                                <a href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$IDbooking.'&s=0&t=1" style="text-decoration: none; padding: 7px 0px; background: #dc2222; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">Rechazar</a>
                            </td>
                        </tr>
                    </table>
                    </div>';

        return $html;
    }
}

$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

$_kmimos_tables = new Class_Kmimos_Tables();


