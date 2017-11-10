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
	    global $CONTENIDO;
	    global $wpdb;
	    $user_id=get_current_user_id();
	    $caregivers = get_caregiver($user_select);

	    if(count($caregivers) > 0){

	        $reservas_array = array(
				"pendientes_confirmar" => array(
					"titulo" => 'Solicitudes Pendientes por Confirmar',
					"solicitudes" => array()
				),
				"confirmadas" => array(
					"titulo" => 'Solicitudes Confirmadas',
					"solicitudes" => array()
				),
				"canceladas" => array(
					"titulo" => 'Solicitudes Canceladas',
					"solicitudes" => array()
				),
				"otras" => array(
					"titulo" => 'Otras Solicitudes',
					"solicitudes" => array()
				)
			);

	        //PENDIENTE POR PAGO EN TIENDA DE CONVENINCIA
	        foreach($caregivers as $key => $caregiver){
	            $count++;

	            $_metas   = get_post_meta($caregiver->Nro_solicitud);
	            $cuidador = get_userdata($caregiver->Cuidador_id);
	            $cliente  = get_userdata($caregiver->Cliente_id);

	            $foto = kmimos_get_foto( $caregiver->Cuidador_id ) ;
	            $usuario = $wpdb->get_var("SELECT post_title FROM wp_posts WHERE post_author = {$caregiver->Cuidador_id} AND post_type = 'petsitters'");
	            $telefono = get_user_meta($caregiver->Cuidador_id, "user_phone", true);
	            $correo = $cuidador->user_email;
	            $quien_soy = "DATOS DEL CUIDADOR";

	            if( $user_select == "cu.post_author={$user_id}" ){
	            	$foto = kmimos_get_foto( $caregiver->Cliente_id ) ;

	            	$nom = explode(" ", get_user_meta($caregiver->Cliente_id, "first_name", true));
	            	$ape = explode(" ", get_user_meta($caregiver->Cliente_id, "last_name", true));
	            	$usuario = $nom[0]." ".$ape[0];

		            $telefono = get_user_meta($caregiver->Cliente_id, "user_phone", true);
		            $correo = $cliente->user_email;
	            	$quien_soy = "DATOS DEL CLIENTE";
	            }

	            $Ver = 'ver/'.$caregiver->Nro_solicitud;
	            $Confirmar = $caregiver->Nro_solicitud;
	            $Cancelar = $caregiver->Nro_solicitud;

	            $caregiver->Cuando = date("d/m/Y", strtotime( str_replace("/", "-", $caregiver->Cuando) ));

	            $caregiver->Hora = strtotime($caregiver->Hora);
	            $caregiver->Hora = date("H:i", $caregiver->Hora);

	            $detalle = array(
	            	"desde" => date("d/m/Y", strtotime( str_replace("/", "-", $_metas["service_start"][0]) )),
	            	"hasta" => date("d/m/Y", strtotime( str_replace("/", "-", $_metas["service_end"][0]) )),
	            	"donde" => $caregiver->Donde,

	            	"telefono" => $telefono,
	            	"correo" => $correo,

	            	"quien_soy" => $quien_soy
	            );
	            
	            if($caregiver->Estatus=='pending'){
	                
	                $acciones = array();
	                if($caregiver->Cuidador_id==$user_id){
	                    $acciones = array(
	                    	"ver_s" => $Ver,
	                    	"confirmar_s" => $Confirmar,
	                    	"cancelar_s" => $Cancelar
	                    );
	                }else{
	                    $acciones = array(
	                    	"ver_s" => $Ver,
	                    	"cancelar_s" => $Cancelar
	                    );
	                }

	                $reservas_array["pendientes_confirmar"]["solicitudes"][] = array(
						'id' => $caregiver->Nro_solicitud, 
						'servicio' => $usuario, 
						'inicio' => $caregiver->Cuando, 
						'fin' => $caregiver->Hora, 
						'foto' => $foto,
						'acciones' => $acciones,
						"detalle" => $detalle
					);


	            }else if($caregiver->Estatus=='publish'){
	                
	                $reservas_array["confirmadas"]["solicitudes"][] = array(
						'id' => $caregiver->Nro_solicitud, 
						'servicio' => $usuario, 
						'inicio' => $caregiver->Cuando, 
						'fin' => $caregiver->Hora, 
						'foto' => $foto,
						'acciones' => array(
	                    	"ver_s" => $Ver
	                    ),
						"detalle" => $detalle
					);


	            }else if($caregiver->Estatus=='draft'){
	                
	                
	                $reservas_array["canceladas"]["solicitudes"][] = array(
						'id' => $caregiver->Nro_solicitud, 
						'servicio' => $usuario, 
						'inicio' => $caregiver->Cuando, 
						'fin' => $caregiver->Hora, 
						'foto' => $foto,
						'acciones' => array(
	                    	"ver_s" => $Ver
	                    ),
						"detalle" => $detalle
					);

	            }else{
	                
	                $reservas_array["otras"]["solicitudes"][] = array(
						'id' => $caregiver->Nro_solicitud, 
						'servicio' => $usuario, 
						'inicio' => $caregiver->Cuando, 
						'fin' => $caregiver->Hora, 
						'foto' => $foto,
						'acciones' => array(
	                    	"ver_s" => $Ver
	                    ),
						"detalle" => $detalle
					);

	            }

	        }

	        //BUILD TABLE
	        if($strcaregiver!=''){
	            $CONTENIDO .= '<h1 style="line-height: normal;">'.$strcaregiver.'</h1><hr>';
	        }

	        $CONTENIDO .= construir_listado($reservas_array);
	    }else{
	        if($show && $strnocaregiver!=''){
	            $CONTENIDO .= '<h1 style="line-height: normal;">'.$strnocaregiver.'</h1><hr>';
	        }
	    }

	}

	$count=0; $como_cliente = "Mis solicitudes";
	$current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $user = new WP_User( $user_id );
                
	if( $user->roles[0] == "vendor" ){
		$como_cliente = 'Solicitudes como cliente';
	}
	get_caregiver_tables("cu.post_author={$user_id}",'Solicitudes como cuidador','No hay solicitudes como cuidador.');
	get_caregiver_tables("cl.meta_value={$user_id}", $como_cliente,'No hay solicitudes como cliente.',true);

	global $count;
	if($count==0){
	    $CONTENIDO .= '<h1 style="line-height: normal;">No hay solicitudes</h1><hr>';
	}
?>