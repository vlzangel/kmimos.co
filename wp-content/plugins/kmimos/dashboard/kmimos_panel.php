<?php

global $wpdb;



?>

<div class="wrap">

	<?php screen_icon();?><h2>Sistema de Administración de Kmimos - Panel de Control</h2>

</div>

<?php

$titulo = "RESUMEN DE PEDIDOS";

$fields = array(

/*    array('title'=>'#Reserva', 'align'=>'center', 'type'=>'number', 'ancho'=>90), */

    array('title'=>'Reserva', 'align'=>'center', 'type'=>'text', 'width'=>90), 

    array('title'=>'Desde', 'align'=>'center', 'type'=>'text', 'width'=>90), 

    array('title'=>'Hasta', 'align'=>'center', 'type'=>'text', 'width'=>90), 

    array('title'=>'Noches', 'align'=>'center', 'type'=>'number', 'width'=>40), 

    array('title'=>'Servicio', 'align'=>'left', 'type'=>'number', 'width'=>400), 

    array('title'=>'Cliente', 'align'=>'left', 'type'=>'number', 'width'=>160), 

    array('title'=>'Estado', 'align'=>'left', 'type'=>'number', 'width'=>120), 

    array('title'=>'Municipio', 'align'=>'left', 'type'=>'number', 'width'=>120), 

    array('title'=>'#Pedido', 'align'=>'center', 'type'=>'number', 'width'=>60), 

    array('title'=>'Monto', 'align'=>'right', 'type'=>'currency', 'width'=>90), 

    array('title'=>'Remanente', 'align'=>'right', 'type'=>'currency', 'width'=>90), 

    array('title'=>'Estatus Pago', 'align'=>'center', 'type'=>'text', 'width'=>120), 

    array('title'=>'Mascotas', 'align'=>'center', 'type'=>'mascotas', 'width'=>120), 

);



$width=0;

for($i=0;$i<count($fields);$i++) $width += $fields[$i]['width'];

$class = ' style="width: '.$width.'px;"'; // Clase de la tabla



$sql = "SELECT DATE_FORMAT(bk.post_date,'%d/%m/%Y') AS Reserva, 

DATE_FORMAT(INSERT(INSERT(INSERT(INSERT(INSERT(fd.meta_value,13,0,':'),11,0,':'),9,0,' '),7,0,'-'),5,0,'-'),'%d/%m/%Y') AS Desde,  

DATE_FORMAT(INSERT(INSERT(INSERT(INSERT(INSERT(fh.meta_value,13,0,':'),11,0,':'),9,0,' '),7,0,'-'),5,0,'-'),'%d/%m/%Y') AS Hasta, 

DATEDIFF(fh.meta_value,fd.meta_value) AS Dias, 

srv.post_title AS servicio, usr.display_name AS Nombre, ue.meta_value AS Estado, uc.meta_value AS Municipio, 

ord.ID AS pedido, ot.meta_value AS Monto,  IFNULL(rm.meta_value,0) AS Remanente, bk.post_status AS Status_Pago, bp.meta_value AS mascotas



FROM wp_posts AS bk 

LEFT JOIN wp_posts AS ord ON bk.post_parent=ord.ID AND ord.post_type = 'shop_order'

LEFT JOIN wp_postmeta AS fd ON bk.ID=fd.post_id AND fd.meta_key = '_booking_start'

LEFT JOIN wp_postmeta AS fh ON bk.ID=fh.post_id AND fh.meta_key = '_booking_end'

LEFT JOIN wp_postmeta AS bp ON bk.ID=bp.post_id AND bp.meta_key = '_booking_persons' 

LEFT JOIN wp_postmeta AS sv ON bk.ID=sv.post_id AND sv.meta_key = '_booking_product_id' 

LEFT JOIN wp_posts AS srv ON sv.meta_value=srv.ID AND srv.post_type = 'product'

LEFT JOIN wp_postmeta AS ot ON ord.ID=ot.post_id AND ot.meta_key = '_order_total' 

LEFT JOIN wp_postmeta AS rm ON ord.ID=rm.post_id AND rm.meta_key = '_wc_deposits_remaining' 

LEFT JOIN wp_postmeta AS us ON ord.ID=us.post_id AND us.meta_key = '_customer_user' 

LEFT JOIN wp_users AS usr ON us.meta_value=usr.ID 

LEFT JOIN wp_usermeta AS ue ON usr.ID=ue.user_id AND ue.meta_key = 'user_state' 

LEFT JOIN wp_usermeta AS uc ON usr.ID=uc.user_id AND uc.meta_key = 'user_county' 



WHERE bk.post_type = 'wc_booking' AND ord.ID IS NOT NULL 



ORDER BY bk.ID DESC LIMIT 0,20";

$result = $wpdb->get_results( $sql, ARRAY_N );

//print_r($result[0]);

$html = '<table'.$class.'>';

/*$html .= '<colgroup>';

foreach($columns as $column){

    $html .= '<col width="'.$column['width'].'">';



}

$html .= '</colgroup>';*/

$html .= '<caption>'.$titulo.'</caption>';

$html .= '<thead>';

$html .= '<tr>';

for($i=0;$i<count($fields);$i++){

    $html .= '<th>'.$fields[$i]['title'].'</th>';

}

$html .= '</tr>';

$html .= '</thead>';

$html .= '<tbody>';



foreach($result as $row){

    $html .= '<tr>';

    for($i=0;$i<count($fields);$i++){

        switch($fields[$i]['type']){

            case 'mascotas':

                $mascotas = unserialize($row[$i]);

                $valor = count($mascotas);

                break;

            case 'currency':

                $valor = number_format($row[$i],2,",",".");

                break;

            default:

                $valor = $row[$i];

                break;

        }

        $html .= '<td style="text-align:'.$fields[$i]['align'].'; width: '.$fields[$i]['width'].'px">'.$valor.'</td>';

    }

 /*   foreach($columns as $name=>$column){

        $value = get_post_meta($child_id, $name, true);

        switch($column['type']){

            case 'number':

                $value = number_format($value, $column['decimals'], $column['decimalpoint'], $column['separator']);

                break;

            case 'pad':

                if($column['pad_type']=='left') $pad = STR_PAD_LEFT;

                if($column['pad_type']=='right') $pad = STR_PAD_RIGHT;

                if($column['pad_type']=='both') $pad = STR_PAD_BOTH;

                $value = str_pad($value, $column['length'], $column['pad_string'], $pad);

                break;

            case 'date':

                $date_format = $column['format'];

                $value = date($date_format,strtotime($value));

                break;

        }

        if($column['link']==true) $value = '<a href="/wp-admin/post.php?post='.$child_id.'&action=edit" target="_self">'.$value.'</a>';

        $html .= '<td data-id="'.$child_id.'" class="'.$column['class'].'">'.$value.'</td>';

    }*/

    $html .= '</tr>';

}

$html .= '</tbody>';

$html .= '<tfoot>';

$html .= '<tr>';

$html .= '<td colspan="'.count($columns).'">'.$erp_text[$key.'_total'].': '.count($childs).'</td>';

$html .= '</tr>';

$html .= '</tfoot>';

$html .= '</table>';



echo $html;

?>

