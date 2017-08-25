<?php
echo "
		<style>
			.vlz_tabla{
				width: 100%;
			    margin-bottom: 40px;
			}
			.vlz_tabla th{
			    background: #59c9a8!important;
				color: #FFF;
				border-top: 1px solid #888;
				border-right: 1px solid #888;
				text-align: center;
			}
			.vlz_tabla td{
				border-top: 1px solid #888;
				border-right: 1px solid #888;
			}
			h1{
				line-height: 0px;
			}
			hr{
			    border-top: 2px solid #eee;
			}
			a{
			    color: #005796;
				font-weight: 600;
			    opacity: 0.8;
			}
			.vlz_h1{
				background: #59c9a8;
			    display: inline-block;
			    padding: 5px 50px 5px 5px;
			    line-height: 1 !important;
			    margin: 0px;
			    border: solid 1px #888;
			    border-bottom: 0;
			    border-radius: 0px 5px 0px 0px;
			    color: #FFF;
			}
			a:hover{
			    opacity: 1;
			}
		</style>
	";



global $wpdb;
$sql = "SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_author = {$user_id} AND post_status NOT LIKE '%cart%'";
$reservas = $wpdb->get_results($sql);


//CART
$items = WC()->cart->get_cart();
if(count($items) > 0){
    ?>
    <h1 class='vlz_h1 jj_h1'>Reservas Por Completar</h1>
    <table class='vlz_tabla jj_tabla table table-striped table-responsive'>
        <tr>
            <th class="product-name"><?php _e( 'SERVICIO', 'woocommerce' ); ?></th>
            <th class="product-service"><?php _e( 'DETALLE', 'woocommerce' ); ?></th>
            <th class="product-subtotal"><?php _e( 'TOTAL', 'woocommerce' ); ?></th>
            <th>ACCIONES</th>
        </tr>

        <?php
        foreach($items as $cart_item_key => $cart_item){
            $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
            ?>
            <tr>
                <td class="product-name" data-title="<?php _e( 'Product', 'woocommerce' ); ?>">
                    <?php
                    if ( ! $_product->is_visible() ) {
                        echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
                    } else {
                        echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $_product->get_title() ), $cart_item, $cart_item_key );
                    }
                    ?>
                </td>
                <td class="product-service" data-title="<?php _e( 'Service', 'woocommerce' ); ?>">

                    <?php
                    // Meta data
                    echo WC()->cart->get_item_data( $cart_item );

                    // Backorder notification
                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                        echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
                    }
                    ?>
                </td>
                <td class="product-subtotal" data-title="<?php _e( 'Total', 'woocommerce' ); ?>">
                    <?php
                    echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                    ?>
                </td>
                <td style='text-align: right; width: 166px;'>

                    <?php
                    echo build_select(
                        array(
                            array(
                                'text'=>'Ver',
                                'value'=>esc_url( WC()->cart->get_cart_url())
                            ),
                            array(
                                'text'=>'Pagar',
                                'value'=>esc_url( WC()->cart->get_checkout_url())
                            ),
                            /*
                            array(
                                'text'=>'Modificar',
                                'value'=>esc_url( $_product->get_permalink($cart_item))
                            ),
                            */
                            array(
                                'text'=>'Eliminar',
                                'value'=>esc_url( WC()->cart->get_remove_url($cart_item_key))
                            )
                        ));
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}


$completadas = array();
$confirmadas = array();
$canceladas  = array();
$pendientes = array();

if( count($reservas) > 0 ){

    $estatus = array(
        "paid" => "Pagada",
        "unpaid" => "No Pagada",
        "cancelled" => "Cancelada",
        "confirmed" => "Confirmada"
    );


    foreach ($reservas as $key => $reserva) {

        $id_reserva = $reserva->ID;

        $metas_orden   = get_post_meta($id_reserva+1);
        $metas_reserva = get_post_meta($id_reserva);

        $ini_str = $metas_reserva['_booking_start'][0];
        $fin_str = $metas_reserva['_booking_end'][0];

        $ini = substr($ini_str, 6, 2)."/".substr($ini_str, 4, 2)."/".substr($ini_str, 0, 4);
        $fin = substr($fin_str, 6, 2)."/".substr($fin_str, 4, 2)."/".substr($fin_str, 0, 4);

        $servicio = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$metas_reserva['_booking_product_id'][0]);

        $datos = $wpdb->get_results("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = ".$metas_reserva['_booking_order_item_id'][0]);


        $sta = $reserva->post_status; $no_tomada = true;

        if( $metas_orden['_payment_method'][0] == 'openpay_stores' &&  $sta == "unpaid" ){
            $pdf = "<a class='btn_pagar'
						href='{$metas_orden['_openpay_pdf'][0]}' 
						target='_blank' 
						title='Ver c�digo para pagar en tienda por conveniencia'
						style='   
							font-weight: 600;
						    background: #54c8a7;
						    padding: 3px 5px;
						    border-radius: 2px;
						    color: #FFF;
						'
					>Pagar</a>";
        }else{
            $pdf = "";
        }

        if( $sta == "confirmed" ){

            $completadas[] = array(
                "ID" 		=> $reserva->ID,
                "URL" 		=> $servicio->post_name,
                "SERVICIO" 	=> $servicio->post_title,
                "INICIO" 	=> $ini,
                "FIN" 		=> $fin
            );
            $no_tomada = false;
        }

        if( $sta == "cancelled" ){

            $canceladas[] = array(
                "ID" 		=> $reserva->ID,
                "URL" 		=> $servicio->post_name,
                "SERVICIO" 	=> $servicio->post_title,
                "INICIO" 	=> $ini,
                "FIN" 		=> $fin
            );
            $no_tomada = false;
        }

        if( $no_tomada ){

            $pendientes[] = array(
                "ID" 		=> $reserva->ID,
                "ORDEN" 	=> $reserva->post_parent,
                "URL" 		=> $servicio->post_name,
                "SERVICIO" 	=> $servicio->post_title,
                "INICIO" 	=> $ini,
                "FIN" 		=> $fin,
                "PDF" 		=> $pdf,
                "STA" 		=> $estatus[$reserva->post_status]
            );

        }

    }







    $booking_coming=array();
    $booking_coming['openpay_unpaid']=array();
    $booking_coming['openpay_unpaid']['title']='Reservas pendientes por pagar en tienda por conveniencia';
    $booking_coming['openpay_unpaid']['th']=array();
    $booking_coming['openpay_unpaid']['tr']=array();

    $booking_coming['pending']=array();
    $booking_coming['pending']['title']='Reservas en error en tarjetas de credito';
    $booking_coming['pending']['th']=array();
    $booking_coming['pending']['tr']=array();

    $booking_coming['no-confirmed']=array();
    $booking_coming['no-confirmed']['title']='Reservas Pendientes por Confirmar';
    $booking_coming['no-confirmed']['th']=array();
    $booking_coming['no-confirmed']['tr']=array();

    $booking_coming['confirmed']=array();
    $booking_coming['confirmed']['title']='Reservas Confirmadas';
    $booking_coming['confirmed']['th']=array();
    $booking_coming['confirmed']['tr']=array();

    $booking_coming['completed']=array();
    $booking_coming['completed']['title']='Reservas Completadas';
    $booking_coming['completed']['th']=array();
    $booking_coming['completed']['tr']=array();

    $booking_coming['cancelled']=array();
    $booking_coming['cancelled']['title']='Reservas Canceladas';
    $booking_coming['cancelled']['th']=array();
    $booking_coming['cancelled']['tr']=array();

    $booking_coming['other']=array();
    $booking_coming['other']['title']='Otras Reservas';
    $booking_coming['other']['th']=array();
    $booking_coming['other']['tr']=array();



    //PENDIENTE POR PAGO EN TIENDA DE CONVENINCIA
    if($reservas > 0){
        foreach($reservas as $key => $reserva){
            //var_dump($reserva);

            $_metas_reserva=get_post_meta($reserva->ID);
            $_metas=get_post_meta($reserva->post_parent);
            //var_dump($_metas);

            $pedido = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);
            //var_dump($pedido);

            if($reserva->post_status=='unpaid' && $_metas['_payment_method'][0]=='openpay_stores'){

                $pdf=array();

                $options='<a class="theme_btn" href="'.$_metas['_openpay_pdf'][0].'">Ver PDF</a>';
                $options.='<a class="theme_btn" href="'.get_home_url().'/ver/'.$reserva->post_parent.'">Ver</a>';
                $options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0">Cancelar</a>';



                $options_args=array();
                if($_metas['_openpay_pdf'][0]!=''){
                    $options_args[]=array(
                        'text'=>'Ver PDF',
                        'value'=>$_metas['_openpay_pdf'][0]
                    );
                }

                $options_args[]=array(
                    'text'=>'Ver',
                    'value'=>get_home_url().'/ver/'.$reserva->post_parent
                );
                $options_args[]= array(
                    'text'=>'Cancelar',
                    'class'=>'cancelled action_confirmed',
                    'value'=>get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0&action=noaction&show=noshow'
                );

                $options=build_select($options_args);

                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'RESERVA');
                $booking_th[]=array('class'=>'','data'=>'SERVICIO');
                //$booking_th[]=array('class'=>'','data'=>'ESTATUS');
                $booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['openpay_unpaid']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$reserva->ID);
                $booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
                //$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['openpay_unpaid']['tr'][]=$booking_td;


                //RESERVAS PENDIENTES POR ERROR DE PAGOS DE TARJETAS
                //$reserva->post_status=='pending'


                //RESERVAS CONFIRMADAS
            }else if($reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])>time()){
                //&& (strtotime($_metas_reserva['_booking_start'][0])
                echo strtotime($_metas_reserva['_booking_start'][0]);
                $options='<a class="theme_btn" href="'.get_home_url().'/ver/'.$reserva->post_parent.'">Ver</a>';
                $options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0">Cancelar</a>';
                $options=build_select(
                    array(
                        array(
                            'text'=>'Ver',
                            'value'=>get_home_url().'/ver/'.$reserva->post_parent
                        )
                    ));


                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'RESERVA');
                $booking_th[]=array('class'=>'','data'=>'SERVICIO');
                //$booking_th[]=array('class'=>'','data'=>'ESTATUS');
                $booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['confirmed']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$reserva->ID);
                $booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
                //$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['confirmed']['tr'][]=$booking_td;



                //RESERVAS COMPLETADAS
            }else if($reserva->post_status=='complete' || ($reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])<time())){

                $options='<a class="theme_btn" href="'.get_home_url().'/ver/'.$reserva->post_parent.'">Ver</a>';
                $options='<a class="theme_btn" href="'.get_home_url().'/valorar-cuidador/?id='.$reserva->ID.'">Valorar</a>';
                $options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0">Cancelar</a>';
                $options=build_select(
                    array(
                        array(
                            'text'=>'Ver',
                            'value'=>get_home_url().'/ver/'.$reserva->post_parent
                        ),
                        array(
                            'text'=>'Valorar',
                            'value'=>get_home_url().'/valorar-cuidador/?id='.$reserva->ID
                        )
                    ));

                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'RESERVA');
                $booking_th[]=array('class'=>'','data'=>'SERVICIO');
                //$booking_th[]=array('class'=>'','data'=>'ESTATUS');
                $booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['completed']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$reserva->ID);
                $booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
                //$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['completed']['tr'][]=$booking_td;


                //RESERVAS CANCELADAS
            }else if($reserva->post_status=='cancelled' || $reserva->post_status=='wc_cancelled'){

                $options='<a class="theme_btn" href="'.get_home_url().'/ver/'.$reserva->post_parent.'">Ver</a>';

                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'RESERVA');
                $booking_th[]=array('class'=>'','data'=>'SERVICIO');
                //$booking_th[]=array('class'=>'','data'=>'ESTATUS');
                $booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['cancelled']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$reserva->ID);
                $booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
                //$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['cancelled']['tr'][]=$booking_td;


                //RESERVAS PNDIENTES POR CONFIRMAR
            }else if($reserva->post_status!='confirmed'){

                $options='<a class="theme_btn" href="'.get_home_url().'/ver/'.$reserva->post_parent.'">Ver</a>';
                $options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0">Cancelar</a>';
                $options=build_select(
                    array(
                        array(
                            'text'=>'Ver',
                            'value'=>get_home_url().'/ver/'.$reserva->post_parent
                        ),
                        array(
                            'text'=>'Cancelar',
                            'class'=>'cancelled action_confirmed',
                            'value'=>get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0'
                        )
                    ));

                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'RESERVA');
                $booking_th[]=array('class'=>'','data'=>'SERVICIO');
                //$booking_th[]=array('class'=>'','data'=>'ESTATUS');
                $booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['no-confirmed']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$reserva->ID);
                $booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
                //$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['no-confirmed']['tr'][]=$booking_td;



            }else{

                $options='<a class="theme_btn" href="'.get_home_url().'/ver/'.$reserva->post_parent.'">Ver</a>';

                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'RESERVA');
                $booking_th[]=array('class'=>'','data'=>'SERVICIO');
                $booking_th[]=array('class'=>'','data'=>'ESTATUS');
                $booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['other']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$reserva->ID);
                $booking_td[]=array('class'=>'','data'=>$reserva->post_status);
                $booking_td[]=array('class'=>'','data'=>$reserva->post_status);
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
                $booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['other']['tr'][]=$booking_td;

            }
        }
    }


    //BUILD TABLE
    echo build_table($booking_coming);


    /*
        if( count($pendientes) > 0 ){
            echo "<h1 class='vlz_h1 jj_h1'>Reservas Pendientes</h1>";

            $resultados = "
                <table class='vlz_tabla jj_tabla table table-striped table-responsive'>
                    <tr>
                        <th>RESERVA</th>
                        <th style='text-align: left;'>SERVICIO</th>
                        <th class='td_responsive'>INICIO</th>
                        <th class='td_responsive'>FIN</th>
                        <th>ACCIONES</th>
                    </tr>";

                foreach ($pendientes as $key => $value) {
                    $resultados .= "
                        <tr>
                            <td style='width: 85px; text-align: center;'>{$value['ID']}</td>
                            <td><a href='".get_home_url()."/producto/{$value['URL']}' target='_blank' >{$value['SERVICIO']}</a></td>
                            <td class='td_responsive' style='text-align: center; width: 85px;'>{$value['INICIO']}</td>
                            <td class='td_responsive' style='text-align: center; width: 85px;'>{$value['FIN']}</td>
                            <td style='text-align: right; width: 166px;'>
                                {$value['PDF']}
                                <a class='btn_ver'
                                    href='".get_home_url()."/ver/".($value['ORDEN'])."/'
                                    style='
                                        font-weight: 600;
                                        background: #54c8a7;
                                        padding: 3px 5px;
                                        border-radius: 2px;
                                        color: #FFF;
                                    '
                                >Ver</a>
                                <!-- <a class='btn_cancelar'
                                    href='".get_home_url()."/wp-content/plugins/kmimos/orden.php?o={$value['ORDEN']}&s=0&r={$value['ID']}'
                                    style='
                                        font-weight: 600;
                                        background: #e80000;
                                        padding: 3px 5px;
                                        border-radius: 2px;
                                        color: #FFF;
                                    '
                                >Cancelar</a> -->
                            </td>
                        </tr>
                    ";
                }
            $resultados .= "</table>";

            echo $resultados;
        }

        if( count($completadas) > 0 ){
            echo "<h1 class='vlz_h1 jj_h1'>Reservas Completadas</h1>";

            $resultados = "
                <table class='vlz_tabla jj_tabla table table-striped'>
                    <tr>
                        <th>RESERVA</th>
                        <th style='text-align: left;'>SERVICIO</th>
                        <th class='td_responsive'>INICIO</th>
                        <th class='td_responsive'>FIN</th>
                        <th>ACCIONES</th>
                    </tr>";

                foreach ($completadas as $key => $value) {
                    $resultados .= "
                        <tr>
                            <td style='width: 85px; center;'>{$value['ID']}</td>
                            <td><a href='".get_home_url()."/producto/{$value['URL']}' target='_blank' >{$value['SERVICIO']}</a></td>
                            <td class='td_responsive' style='text-align: center; width: 85px;'>{$value['INICIO']}</td>
                            <td class='td_responsive' style='text-align: center; width: 85px;'>{$value['FIN']}</td>
                            <td style='text-align: center; width: 85px;'>
                                <a class='btn_ver'
                                    href='".get_home_url()."/valorar-cuidador/?id={$value['ID']}'
                                    style='
                                        font-weight: 600;
                                        background: #54c8a7;
                                        padding: 3px 5px;
                                        border-radius: 2px;
                                        color: #FFF;
                                    '
                                >
                                    Valorar
                                </a>
                            </td>
                        </tr>
                    ";
                }
            $resultados .= "</table>";

            echo $resultados;
        }

        if( count($canceladas) > 0 ){
            echo "<h1 class='vlz_h1 jj_h1'>Reservas Canceladas</h1>";

            $resultados = "
                <table class='vlz_tabla jj_tabla'>
                    <tr>
                        <th>RESERVA</th>
                        <th style='text-align: left;'>SERVICIO</th>
                        <th class='td_responsive'>INICIO</th>
                        <th class='td_responsive'>FIN</th>
                    </tr>";

                foreach ($canceladas as $key => $value) {
                    $resultados .= "
                        <tr>
                            <td style='width: 85px; center;'>{$value['ID']}</td>
                            <td><a href='".get_home_url()."/producto/{$value['URL']}' target='_blank' >{$value['SERVICIO']}</a></td>
                            <td class='td_responsive' style='text-align: center; width: 85px;'>{$value['INICIO']}</td>
                            <td class='td_responsive' style='text-align: center; width: 85px;'>{$value['FIN']}</td>
                        </tr>
                    ";
                }
            $resultados .= "</table>";

            echo $resultados;
        }
    */
}else{
    echo "<h1 style='line-height: normal;'>Usted aún no tiene reservas.</h1><hr>";
}
?>