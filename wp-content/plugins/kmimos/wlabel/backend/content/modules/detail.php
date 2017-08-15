<?php
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

function number_round($number){
    $number=(round($number*100))/100;
    return $number;
}

global $wpdb;
$wlabel=$_wlabel_user->wlabel;
$WLresult=$_wlabel_user->wlabel_result;
$WLcommission=$_wlabel_user->wlabel_Commission();
$_wlabel_user->wLabel_Filter(array('tddate','tdcheck'));
$_wlabel_user->wlabel_Export('DETALLE DE MONTOS','title','table');
?>
<div class="module_title">
    RESERVAS POR DIA
</div>

<div class="section">
    <div class="tables">
    <table cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th class="title">Titulo</th>
            <?php
                $day_init=strtotime(date('m/d/Y',$WLresult->time));
                $day_last=strtotime(date('m/d/Y',time()));
                $day_more=(24*60*60);

                for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){
                    echo '<th class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('d/m/Y',$day).'</th>';//date('d',$day).'--'.
                    if(date('t',$day)==date('d',$day) || $day_last==$day){
                        echo '<th class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('F/Y',$day).'</th>';
                        if(date('m',$day)=='12' || $day_last==$day){
                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('Y',$day).'</th>';
                        }
                    }
                }
            echo '<th class="total tdshow" data-check="total">TOTAL</th>';
            ?>

        </tr>
        </thead>
        <tbody>



<?php

$sql = "
    SELECT
      posts.*
    FROM
      wp_posts AS posts
      LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')
      LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=posts.post_author AND usermeta.meta_key='_wlabel')
    WHERE
      (
      (posts.post_type = 'wc_booking' AND usermeta.meta_value = '{$wlabel}')
      OR
      (posts.post_type = 'wc_booking' AND postmeta.meta_value = '{$wlabel}')
      )
      AND NOT
      posts.post_status LIKE '%cart%'
    ORDER BY
      posts.ID DESC
";


$bookings = $wpdb->get_results($sql);

$BUILDbookings = array();
foreach($bookings as $key => $booking){
    //var_dump($booking);
    $ID=$booking->ID;
    $date=strtotime($booking->post_date);
    $customer=$booking->post_author;
    $status=$booking->post_status;
    $order=$booking->post_parent;
    $status_name=$status;

    $_metas_booking = get_post_meta($ID);
    $_metas_order = get_post_meta($order);
    //var_dump($_metas_booking);
    //var_dump($_metas_order);

    $IDproduct=$_metas_booking['_booking_product_id'][0];
    $IDcustomer=$_metas_booking['_booking_customer_id'][0];
    $IDorder_item=$_metas_booking['_booking_order_item_id'][0];

    //$_meta_WCorder = wc_get_order_item_meta($IDorder_item,'_line_total');
    //$_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_total');
    $_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_subtotal');
    //var_dump($WCorder_items);

    //CUSTOMER
    $_metas_customer = get_user_meta($customer);
    $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];

    $product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID ='$IDproduct'");
    //var_dump($product);

    $BUILDbookings[$ID] = array();
    $BUILDbookings[$ID]['booking'] = $ID;
    $BUILDbookings[$ID]['order'] = $order;
    $BUILDbookings[$ID]['date'] = $date;
    $BUILDbookings[$ID]['customer'] = $customer;
    $BUILDbookings[$ID]['status'] = $status;
    $BUILDbookings[$ID]['status_name'] = $status_name;
    $BUILDbookings[$ID]['metas_booking'] = $_metas_booking;
    $BUILDbookings[$ID]['metas_order'] = $_metas_order;
    $BUILDbookings[$ID]['WCorder_line_total'] = $_meta_WCorder_line_total*1;
    //$BUILDbookings[$ID][''] = ;
}







//TOTAL DE MONTO DE RESERVAS CANCELADAS
echo '<tr>';
echo '<th class="title">Monto Total de Reservas</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDbookings as $booking){
        if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
            $amount_booking=0;
            //if($booking['status']!='modified'){} //$booking['status']=='cancelled'
                $amount_booking=$booking['WCorder_line_total'];

            $amount_booking=(round($amount_booking*100)/100);
            $amount_day=$amount_day+$amount_booking;
            $amount_month=$amount_month+$amount_booking;
            $amount_year=$amount_year+$amount_booking;
            $amount_total=$amount_total+$amount_booking;
        }
    }


    echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_day).'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_month).'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_year).'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';



//TOTAL DE MONTO DE RESERVAS
echo '<tr>';
echo '<th class="title">Monto de Reservas</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDbookings as $booking){
        if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
            $amount_booking=0;
            if($booking['status']!='cancelled' && $booking['status']!='modified'){
                $amount_booking=$booking['WCorder_line_total'];
            }
            $amount_booking=(round($amount_booking*100)/100);
            $amount_day=$amount_day+$amount_booking;
            $amount_month=$amount_month+$amount_booking;
            $amount_year=$amount_year+$amount_booking;
            $amount_total=$amount_total+$amount_booking;
        }
    }


    echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_day).'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_month).'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_year).'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';




//TOTAL DE MONTO DE RESERVAS CANCELADAS
echo '<tr>';
echo '<th class="title">Monto de Reservas Canceladas</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDbookings as $booking){
        if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
            $amount_booking=0;
            if($booking['status']=='cancelled' || $booking['status']=='modified'){
                $amount_booking=$booking['WCorder_line_total'];
            }
            $amount_booking=(round($amount_booking*100)/100);
            $amount_day=$amount_day+$amount_booking;
            $amount_month=$amount_month+$amount_booking;
            $amount_year=$amount_year+$amount_booking;
            $amount_total=$amount_total+$amount_booking;
        }
    }


    echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_day).'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_month).'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_year).'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';





//TOTAL DE MONTO POR PAGAR
echo '<tr>';
echo '<th class="title">Monto de reservas por pagar en tienda por conveniencia</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;
//
for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDbookings as $booking){
        if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
            $amount_booking=0;
            if($booking['status']=='unpaid' && $booking['metas_order']['_payment_method'][0] == 'openpay_stores'){
                $amount_booking=$booking['WCorder_line_total'];
            }
            $amount_booking=(round($amount_booking*100)/100);
            $amount_day=$amount_day+$amount_booking;
            $amount_month=$amount_month+$amount_booking;
            $amount_year=$amount_year+$amount_booking;
            $amount_total=$amount_total+$amount_booking;
        }
    }


    echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_day).'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_month).'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_year).'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';


//TOTAL DE MONTO DE COMISION
echo '<tr>';
echo '<th class="title">Comision (17%)</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDbookings as $booking){
        if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
            $amount_booking=0;
            if($booking['status']!='cancelled' && $booking['status']!='modified'){
                $amount_booking=$booking['WCorder_line_total']*0.17;
            }
            $amount_booking=(round($amount_booking*100)/100);
            $amount_day=$amount_day+$amount_booking;
            $amount_month=$amount_month+$amount_booking;
            $amount_year=$amount_year+$amount_booking;
            $amount_total=$amount_total+$amount_booking;
        }
    }


    echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_day).'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_month).'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_year).'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';


//TOTAL DE MONTO DE COMISION DE KMIMOS
echo '<tr>';
echo '<th class="title">Comision de  kmimos (17%/'.(100-$WLcommission).'%)</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDbookings as $booking){
        if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
            $amount_booking=0;
            if($booking['status']!='cancelled' && $booking['status']!='modified'){
                $amount_booking=$booking['WCorder_line_total']*0.17*(1-($WLcommission/100));
            }
            $amount_booking=(round($amount_booking*100)/100);
            $amount_day=$amount_day+$amount_booking;
            $amount_month=$amount_month+$amount_booking;
            $amount_year=$amount_year+$amount_booking;
            $amount_total=$amount_total+$amount_booking;
        }
    }


    echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_day).'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_month).'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_year).'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';


//TOTAL DE MONTO DE COMISION wlabel
echo '<tr>';
echo '<th class="title">Comision de '.$wlabel.' (17%/'.$WLcommission.'%)</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDbookings as $booking){
        if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
            $amount_booking=0;
            if($booking['status']!='cancelled' && $booking['status']!='modified'){
                $amount_booking=$booking['WCorder_line_total']*0.17*($WLcommission/100);
            }
            $amount_booking=(round($amount_booking*100)/100);
            $amount_day=$amount_day+$amount_booking;
            $amount_month=$amount_month+$amount_booking;
            $amount_year=$amount_year+$amount_booking;
            $amount_total=$amount_total+$amount_booking;
        }
    }


    echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_day).'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_month).'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.number_round($amount_year).'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';



 ?>
        </tbody>
    </table>
    </div>
</div>