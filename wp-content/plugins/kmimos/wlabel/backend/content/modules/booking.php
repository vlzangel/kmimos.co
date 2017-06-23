<?php
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

$_wlabel_user->wLabel_Filter(array('trdate'));
?>

<div class="module_title">
    RESERVAS
</div>


<div class="module_data">
    <div class="item" id="user_filter">Personas reservando en el periodo seleccionado: <span></span></div>
</div>

<div class="section">
    <div class="tables">
    <table cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Nombre de Cliente</th>
            <th>Nombre de Cuidador</th>
            <th>Servicio</th>
            <th>Estatus</th>
            <th>Duración</th>
            <th>Duración por usuario</th>
            <th>Servicios Adicionales</th>
            <th>Monto de reserva</th>
            <th>Monto Kmimos</th>
            <th>Monto Partición Kmimos</th>
            <th>Monto Partición Volaris</th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="count"></td>
            <td class="count"></td>
            <td class="count"></td>
            <td class="count"></td>
        </tr>
        </tfoot>
        <tbody>



<?php
   global $wpdb;
   //var_dump($_wlabel_user->GETuser());
   $wlabel=$_wlabel_user->wlabel;
   //var_dump($wlabel);

   $sql = "
            SELECT
                posts.*,
                posts.ID AS ID,
                posts.post_type AS ptype,
                posts.post_status AS status,
                posts.post_parent AS porder,
                posts.post_author AS customer
            FROM
                wp_posts AS posts
                LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')
                LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=posts.post_author AND usermeta.meta_key='_wlabel')
            WHERE
                posts.post_type = 'wc_booking' AND
                (
                    usermeta.meta_value = '{$wlabel}' OR
                    postmeta.meta_value = '{$wlabel}'
                )
                AND NOT
                posts.post_status LIKE '%cart%'
            ORDER BY
              posts.ID DESC
     ";

   //var_dump($sql);
   //var_dump($wpdb);
   $bookings = $wpdb->get_results($sql);
   //var_dump($bookings);

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



       //var_dump($order);
       //var_dump($IDorder_item);

        $_meta_WCorder = wc_get_order_item_meta($IDorder_item,'');
        $_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_total');
        $_meta_WCorder_duration = wc_get_order_item_meta($IDorder_item,'Duración');
        $_meta_WCorder_caregiver = wc_get_order_item_meta($IDorder_item,'Ofrecido por');

       //SERVICES
       $post = get_post($IDproduct);
       $services = $post->post_name;
       $services=explode('-',$services);
       if(count($services)>0){
           $services=trim($services[0]);
       }else{
           $services='';
       }

       //DURATION
       $duration=str_replace(' Dias','',$_meta_WCorder_duration);
       $duration_text=$duration.' Dia(s)';

       if($services=='hospedaje'){
           $duration=(int)$duration-1;
           $duration_text=$duration.' Noche(s)';
       }


       //var_dump($_meta_WCorder);
       $_meta_WCorder_services_additional=array();
        foreach($_meta_WCorder as $meta=>$value){
            if(strpos($meta,'Servicios Adicionales') !== false){
                $_meta_WCorder_services_additional[]=str_replace('(precio por mascota)','',$value[0]);
            }
        }
        $_meta_WCorder_services_additional=implode(',',$_meta_WCorder_services_additional);

        //CUSTOMER
        $_metas_customer = get_user_meta($customer);
        $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];

        $product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID ='$IDproduct'");
        //var_dump($product);

        $html='
        <tr class="trshow" data-day="'.date('d',$date).'" data-month="'.date('n',$date).'" data-year="'.date('Y',$date).'" data-status="'.$status.'">
            <td>'.$booking->ID.'</td>
            <td>'.date('d/m/Y',$date).'</td>
            <td class="user" data-user="'.$customer.'">'.$_customer_name.'</td>
            <td>'.$_meta_WCorder_caregiver.'</td>
            <td>'.$services.'</td>
            <td>'.$status_name.'</td>
            <td class="duration" data-user="'.$customer.'" data-count="'.$duration.'">'.$duration_text.'</td>
            <td class="duration_total" data-user="'.$customer.'"></td>
            <td>'.$_meta_WCorder_services_additional.'</td>
            <td>'.$_meta_WCorder_line_total.'</td>
            <td>'.$_meta_WCorder_line_total*0.17.'</td>
            <td>'.$_meta_WCorder_line_total*0.17*0.4.'</td>
            <td>'.$_meta_WCorder_line_total*0.17*0.6.'</td>
        </tr>
        ';
        echo $html;
    }


 ?>
        </tbody>
    </table>
    </div>
</div>


<script type="text/javascript">

    jQuery('.filters select, .filters input').change(function(e){
        setTimeout(function(){
            user_filter();
            duration_filter();
        }, 1000);

    });

    function user_filter(){
        var users=[];
        jQuery('table tbody tr:not(.noshow)').each(function(e){
            var user=jQuery(this).find('.user').data('user');
            if(jQuery.inArray(user,users)<0){
                users.push(user);
            }
        });
        //console.log(users);
        jQuery('#user_filter').find('span').html(users.length);
    }

    function duration_filter(){
        var times=[];
        jQuery('table tbody tr:not(.noshow)').each(function(e){
            var user=jQuery(this).find('.duration').data('user');
            var duration=jQuery(this).find('.duration').data('count');
            //times.push({'user':user,'duration':duration});
            //if(jQuery.inArray(user,times)<0){
            if(times[user] == undefined){
                times[user]=duration;
            }else{
                times[user]=times[user]-(-duration);
            }

        });

        //console.log(times);
        for(duser in times){
            jQuery('table tbody tr td.duration_total[data-user="'+duser+'"]').html(times[duser]);
        }

        /**/
    }

    user_filter();
    duration_filter();
</script>


