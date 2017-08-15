<?php


function get_caregiver($user_select=""){
    global $wpdb;

    $sql = "
		SELECT
			p.ID as Nro_solicitud,
			DATE_FORMAT(p.post_date,'%d-%m-%Y') as Fecha_solicitud,
			p.post_status as Estatus,

			DATE_FORMAT(fd.meta_value,'%d-%m-%Y') as Servicio_desde,
			DATE_FORMAT(fh.meta_value,'%d-%m-%Y') as Servicio_hasta,
			d.meta_value as Donde,
			w.meta_value as Cuando,
			t.meta_value as Hora,

			cl.meta_value as Cliente_id,
			cu.post_author as Cuidador_id
		FROM wp_postmeta as m
			LEFT JOIN wp_posts as p  ON p.ID = m.post_id
			LEFT JOIN wp_postmeta as fd ON p.ID = fd.post_id and fd.meta_key = 'service_start'
			LEFT JOIN wp_postmeta as fh ON p.ID = fh.post_id and fh.meta_key = 'service_end'
			LEFT JOIN wp_postmeta as d  ON p.ID = d.post_id  and d.meta_key  = 'meeting_where'
			LEFT JOIN wp_postmeta as t  ON p.ID = t.post_id  and t.meta_key  = 'meeting_time'
			LEFT JOIN wp_postmeta as w  ON p.ID = w.post_id  and w.meta_key  = 'meeting_when'

			LEFT JOIN wp_postmeta as cl ON p.ID = cl.post_id and cl.meta_key = 'requester_user'
			LEFT JOIN wp_postmeta as pc ON p.ID = pc.post_id and pc.meta_key = 'requested_petsitter'
			LEFT JOIN wp_posts as cu ON cu.ID = pc.meta_value
		WHERE
			m.meta_key = 'request_status'
			AND
			$user_select
		ORDER BY DATE_FORMAT(p.post_date,'%d-%m-%Y') DESC
		;
	";

    return $wpdb->get_results($sql);
}

function get_caregiver_tables($user_select="",$strcaregiver="",$strnocaregiver="",$show=false){
    global $count;
    $user_id=get_current_user_id();
    $caregivers = get_caregiver($user_select);
    if(count($caregivers) > 0){

        $booking_coming=array();
        $booking_coming['pending']=array();
        $booking_coming['pending']['title']='Solicitudes Pendientes por Confirmar';
        $booking_coming['pending']['th']=array();
        $booking_coming['pending']['tr']=array();

        $booking_coming['confirmed']=array();
        $booking_coming['confirmed']['title']='Solicitudes Confirmadas';
        $booking_coming['confirmed']['th']=array();
        $booking_coming['confirmed']['tr']=array();

        $booking_coming['cancelled']=array();
        $booking_coming['cancelled']['title']='Solicitudes Canceladas';
        $booking_coming['cancelled']['th']=array();
        $booking_coming['cancelled']['tr']=array();

        $booking_coming['other']=array();
        $booking_coming['other']['title']='Otras Solicitudes';
        $booking_coming['other']['th']=array();
        $booking_coming['other']['tr']=array();

        //PENDIENTE POR PAGO EN TIENDA DE CONVENINCIA
        foreach($caregivers as $key => $caregiver){
            $count++;

            $_metas=get_post_meta($caregiver->ID);
            //var_dump($_metas);
            $cuidador = get_userdata($caregiver->Cuidador_id);
            $cliente = get_userdata($caregiver->Cliente_id);

            /*
                        $caregiver_args = array('posts_per_page' => '1','post_type' => 'sitters', 'author' => $caregiver->Cuidador_id);
                        $caregiver_query = new WP_Query($caregiver_args);
                        //var_dump($caregiver_query);
                        if($caregiver_query->have_posts()){
                            while($caregiver_query ->have_posts()){
                                $caregiver_query ->the_post();
                                echo get_the_title();
                                var_dump($caregiver_query->post_name);
                                //<a href="'.get_home_url().'/petsitters/'.$cuidador->display_name.'" target="_blank" >'.$cuidador->display_name.'</a>
                            }
                        }
            */
            
            if($caregiver->Estatus=='pending'){
                $options='<a class="theme_btn" href="'.get_home_url().'/solicitud/'.$caregiver->Nro_solicitud.'">Ver</a>';
                $options.='<a class="theme_btn" href="'.get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$caregiver->Nro_solicitud.'&s=1">Confirmar</a>';
                $options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$caregiver->Nro_solicitud.'&s=0">Cancelar</a>';

                if($caregiver->Cuidador_id==$user_id){
                    $options=build_select(array(
                        array(
                            'text'=>'Ver',
                            'value'=>get_home_url().'/solicitud/'.$caregiver->Nro_solicitud
                        ),
                        array(
                            'text'=>'Confirmar',
                            'value'=>get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$caregiver->Nro_solicitud.'&s=1'
                        ),
                        array(
                            'text'=>'Cancelar',
                            'class'=>'cancelled action_confirmed',
                            'value'=>get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$caregiver->Nro_solicitud.'&s=0'
                            )
                    ));
                }else{
                    $options='<a class="theme_btn" href="'.get_home_url().'/solicitud/'.$caregiver->Nro_solicitud.'">Ver</a>';
                }



                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'SOLICITUD');
                $booking_th[]=array('class'=>'','data'=>'CLIENTE');
                $booking_th[]=array('class'=>'','data'=>'CUIDADOR');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FECHA');
                //$booking_th[]=array('class'=>'td_responsive','data'=>'ESTATUS');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['pending']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$caregiver->Nro_solicitud);
                $booking_td[]=array('class'=>'','data'=>$cliente->display_name);
                $booking_td[]=array('class'=>'','data'=>$cuidador->display_name);
                $booking_td[]=array('class'=>'td_responsive','data'=>$caregiver->Fecha_solicitud);
                //$booking_td[]=array('class'=>'td_responsive','data'=>$caregiver->Estatus);
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['pending']['tr'][]=$booking_td;


            }else if($caregiver->Estatus=='publish'){
                $options='<a class="theme_btn" href="'.get_home_url().'/solicitud/'.$caregiver->Nro_solicitud.'">Ver</a>';
                //$options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$caregiver->Nro_solicitud.'&s=0">Cancelar</a>';
                /*
                $options=build_select(
                    array(
                        array(
                            'text'=>'Ver',
                            'value'=>get_home_url().'/solicitud/'.$caregiver->Nro_solicitud
                        ),
                        /*
                        arrat'=>'Cancelar',
                            'class'=>'cancelled action_confirmed',
                            'value'=>get_home_url().'/wp-content/plugins/kmimos/solicitud.php?o='.$caregiver->Nro_solicitud.'&s=0'
                        )
                    ));
                */

                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'SOLICITUD');
                $booking_th[]=array('class'=>'','data'=>'CLIENTE');
                $booking_th[]=array('class'=>'','data'=>'CUIDADOR');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FECHA');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['confirmed']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$caregiver->Nro_solicitud);
                $booking_td[]=array('class'=>'','data'=>$cliente->display_name);
                $booking_td[]=array('class'=>'','data'=>$cuidador->display_name);
                $booking_td[]=array('class'=>'td_responsive','data'=>$caregiver->Fecha_solicitud);
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['confirmed']['tr'][]=$booking_td;


            }else if($caregiver->Estatus=='draft'){
                $options='<a class="theme_btn" href="'.get_home_url().'/solicitud/'.$caregiver->Nro_solicitud.'">Ver</a>';

                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'SOLICITUD');
                $booking_th[]=array('class'=>'','data'=>'CLIENTE');
                $booking_th[]=array('class'=>'','data'=>'CUIDADOR');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FECHA');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['cancelled']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$caregiver->Nro_solicitud);
                $booking_td[]=array('class'=>'','data'=>$cliente->display_name);
                $booking_td[]=array('class'=>'','data'=>$cuidador->display_name);
                $booking_td[]=array('class'=>'td_responsive','data'=>$caregiver->Fecha_solicitud);
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['cancelled']['tr'][]=$booking_td;


            }else{
                $options='<a class="theme_btn" href="'.get_home_url().'/solicitud/'.$caregiver->Nro_solicitud.'">Ver</a>';

                $booking_th=array();
                $booking_th[]=array('class'=>'','data'=>'SOLICITUD');
                $booking_th[]=array('class'=>'','data'=>'CLIENTE');
                $booking_th[]=array('class'=>'','data'=>'CUIDADOR');
                $booking_th[]=array('class'=>'td_responsive','data'=>'FECHA');
                $booking_th[]=array('class'=>'td_responsive','data'=>'ESTATUS');
                $booking_th[]=array('class'=>'','data'=>'ACCIONES');
                $booking_coming['other']['th']=$booking_th;

                $booking_td=array();
                $booking_td[]=array('class'=>'','data'=>$caregiver->Nro_solicitud);
                $booking_td[]=array('class'=>'','data'=>$cliente->display_name);
                $booking_td[]=array('class'=>'','data'=>$cuidador->display_name);
                $booking_td[]=array('class'=>'td_responsive','data'=>$caregiver->Fecha_solicitud);
                $booking_td[]=array('class'=>'td_responsive','data'=>$caregiver->Estatus);
                $booking_td[]=array('class'=>'','data'=>$options);
                $booking_coming['other']['tr'][]=$booking_td;
            }

        }


        //BUILD TABLE
        if($strcaregiver!=''){
            echo '<h1 style="line-height: normal;">'.$strcaregiver.'</h1><hr>';
        }
        echo build_table($booking_coming);
    }else{
        if($show && $strnocaregiver!=''){
            echo '<h1 style="line-height: normal;">'.$strnocaregiver.'</h1><hr>';
        }
    }

}

$count=0;
get_caregiver_tables("cu.post_author={$user_id}",'Como Cuidador.','No hay solicitudes como cuidador.');
get_caregiver_tables("cl.meta_value={$user_id}",'','No hay solicitudes como cliente.',true);

global $count;
if($count==0){
    echo '<h1 style="line-height: normal;">No hay solicitudes</h1><hr>';
}

?>