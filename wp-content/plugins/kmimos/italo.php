<?php
	
	include_once('includes/functions/kmimos_functions.php');

	if(!function_exists('italo_include_script')){
	    function italo_include_script(){
	        
	    }
	}

	if(!function_exists('italo_include_admin_script')){
	    function italo_include_admin_script(){
	        include_once('dashboard/assets/config_backpanel.php');
	    }
	}

	if(!function_exists('validar_perfil_completo')){
	    function validar_perfil_completo(){
	    	global $current_user;
	    	$user_id = $current_user->ID;
	    	if( $user_id > 0 ){	    		
		    	$datos_perfil=[ 
					'user_mobile',
			    	'user_phone'
		    	]; 

		    	foreach( $datos_perfil as $key ){
			    	$value = get_user_meta( $user_id, $key, true );
			    	if( empty($value) ){
			    		echo $key.': '.$value;
						return false;
			    	}
		    	}
				return true;
	    	}
			return false;
	    }
	}

	if(!function_exists('servicios_en_session')){
	    function servicios_en_session( $opt_key = '', $arr, $sub="" ){
	    	$result = false;
	    	if( !empty($arr) ){
	    		if( array_key_exists($sub, $arr) ){
	    			if( in_array($opt_key, $arr[$sub]) ){
	    				$check = true;
	    			}
	    		}
	    	}
	    	return $check;
	    }	
	}

	if(!function_exists('get_user_slug')){
	    function get_user_slug( $cuidador_userID ){
	    	global $wpdb;
	    	if( $cuidador_userID > 0 ){
				$cuidador = $wpdb->get_row("
	                SELECT 
	                    cuidadores.id,
	                    cuidadores.id_post
	                from cuidadores 
	                where cuidadores.user_id = ".$cuidador_userID
	            );
	            $post_id = ( isset( $cuidador->id_post ) )? $cuidador->id_post : 0 ; 
		    	if( $post_id > 0 ){
		    		$user = get_post( $post_id );
		    		if( isset($user->post_name) ){
		    			return get_home_url()."/petsitters/".$user->post_name;
		    		}
		    	}
		    }
	    	return '';
	    }
	}

	if(!function_exists('get_attr_link_conocer_cuidador')){
	    function get_attr_link_conocer_cuidador( $cuidador_name, $post_id ){
	    	global $current_user;
	    	$user_id = $current_user->ID;
	    	$link = ' 
	    		href="#" 
	    		data-name="'.$cuidador_name.'" 
	    		data-id="'.$post_id.'" 
	    		data-target="#popup-conoce-cuidador"
	    	';

			if ( !is_user_logged_in() ){ 
				$link = ' 
					href="#popup-iniciar-sesion"
					data-toggle="modal"
				';
			}else{
				$mascotas = kmimos_get_my_pets($user_id);
				if ( count($mascotas) < 1 ){ 
					$link = ' href="'.get_home_url().'/perfil-usuario/mascotas"';
				}				
			}

			return $link;
	    }
	}
	
	if(!function_exists('add_wlabel')){ 
		function add_wlabel(){
            $wlabel = false;
            $title = '';
            if (!isset($_SESSION)) {
                session_start();
            }
			if(array_key_exists('wlabel',$_SESSION) || $referido=='Volaris' || $referido=='Vintermex'){

                if(array_key_exists('wlabel',$_SESSION)){
                	$title = $_SESSION['wlabel'];
                    $wlabel= true;
                }else if($referido=='Volaris'){
                	$title = 'volaris';
                    $wlabel= true;

                }else if($referido=='Vintermex'){
                	$title = 'vintermex';
                    $wlabel= true;
                }
            }

            if( $wlabel ){
				wp_enqueue_style( 'wlabel_css', getTema()."/css/wlabel-content.css", array(), "1.0.0" );
            }

            return $title;

		}
	}

	if(!function_exists('estados_municipios')){
		function estados_municipios(){
			global $wpdb;
		    $estados_municipios = $wpdb->get_results("
				select 
				  	s.`order` as o_state, 
					l.`order` as o_location,
					s.id as estado_id, 
					s.`name` as estado_name, 
					l.id as municipio_id,
					l.`name` as municipio_name
				from states as s 
					inner join locations as l on l.state_id = s.id
				where  s.country_id = 1
				order by o_state, o_location, estado_name, municipio_name ASC
	    	");
	    	return $estados_municipios;
		}
	}	

	if(!function_exists('get_tipo_servicios')){
	    function get_tipo_servicios(){
	    	return [
		        'hospedaje'      => ['name'=>'Hospedaje'], 
		        'guarderia'      => ['name'=>'Guardería'], 
		        'paseos'         => ['name'=>'Paseos'],
		        'adiestramiento' => ['name'=>'Entrenamiento']
	    	];
	    }
	}

 	if(!function_exists('italo_menus')){
	    function italo_menus($menus){

	    	global $current_user;

	    	$menus[] = array(
                'title'=>'Control de Reservas',
                'short-title'=>'Control de Reservas',
                'parent'=>'kmimos',
                'slug'=>'bp_reservas',
                'access'=>'manage_options',
                'page'=>'backpanel_reservas',
                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

	        $menus[] = array(
	                'title'=>'Control Conocer a Cuidador',
	                'short-title'=>'Control Conocer a Cuidador',
	                'parent'=>'kmimos',
	                'slug'=>'bp_conocer_cuidador',
	                'access'=>'manage_options',
	                'page'=>'backpanel_conocer_cuidador',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

	        $menus[] = array(
	                'title'=>'Listado de Suscriptores',
	                'short-title'=>'Listado de Suscriptores',
	                'parent'=>'kmimos',
	                'slug'=>'bp_suscriptores',
	                'access'=>'manage_options',
	                'page'=>'backpanel_subscribe',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

	        $menus[] = array(
	                'title'=>'Listado de Clientes',
	                'short-title'=>'Listado de Clientes',
	                'parent'=>'kmimos',
	                'slug'=>'bp_clientes',
	                'access'=>'manage_options',
	                'page'=>'backpanel_clientes',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

	        $menus[] = array(
	                'title'=>'Listado de Cuidadores',
	                'short-title'=>'Listado de Cuidadores',
	                'parent'=>'kmimos',
	                'slug'=>'bp_cuidadores',
	                'access'=>'manage_options',
	                'page'=>'backpanel_cuidadores',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

	        /* $menus[] = array(
	                 'title'=>'Control WhiteLabel',
	                 'short-title'=>'Control WhiteLabel',
	                 'parent'=>'kmimos',
	                 'slug'=>'bp_wlabel',
	                 'access'=>'manage_options',
	                 'page'=>'backpanel_wlabel',
	                 'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );*/

	        $menus[] = array(
	                'title'=>'Club Patitas Felices (Participantes)',
	                'short-title'=>'Club Patitas Felices (Participantes)',
	                'parent'=>'kmimos',
	                'slug'=>'bp_participantes_club_patitas_felices',
	                'access'=>'manage_options',
	                'page'=>'backpanel_ctr_participantes',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

	        $menus[] = array(
	                'title'=>'Control de Referidos (Club Patitas Felices)',
	                'short-title'=>'Control de Referidos Club Patitas Felices',
	                'parent'=>'kmimos',
	                'slug'=>'bp_referidos_club_patitas_felices',
	                'access'=>'manage_options',
	                'page'=>'backpanel_ctr_referidos',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

	        $menus[] = array(
	                'title'=>'Estados por Cuidador',
	                'short-title'=>'Estados por Cuidador',
	                'parent'=>'kmimos',
	                'slug'=>'bp_estados_cuidadores',
	                'access'=>'manage_options',
	                'page'=>'backpanel_estados_cuidadores',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );


	        $menus[] = array(
	                'title'=>'Listado Mascotas',
	                'short-title'=>'Listado Mascotas',
	                'parent'=>'kmimos',
	                'slug'=>'bp_mascotas',
	                'access'=>'manage_options',
	                'page'=>'backpanel_mascotas',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

			$menus[] = array(
				'title'=>'Listado Multinivel',
				'short-title'=>'Listado Multinivel',
				'parent'=>'kmimos',
				'slug'=>'bp_multinivel',
				'access'=>'manage_options',
				'page'=>'backpanel_multinivel',
				'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
			);

        
	        $menus[] = array(
	                'title'=>'Newsletter',
	                'short-title'=>'Newsletter',
	                'parent'=>'kmimos',
	                'slug'=>'bp_newsletter',
	                'access'=>'manage_options',
	                'page'=>'backpanel_newsletter',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );


         /* Temporal ********************* */

          if ( 	 $current_user->user_email == 'a.pedroza@kmimos.la' ||
				 $current_user->user_email == 'r.cuevas@kmimos.la'  ||
				 $current_user->user_email == 'e.celli@kmimos.la' 	|| 
				 $current_user->user_email == 'soporte.kmimos@gmail.com'
		 	){

		        $menus[] = array(
		                'title'=>'Pago Cuidador',
		                'short-title'=>'Pago Cuidador',
		                'parent'=>'kmimos',
		                'slug'=>'bp_saldo_cuidadores',
		                'access'=>'manage_options',
		                'page'=>'backpanel_saldo_cuidador',
		                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
		        );
		        $menus[] = array(
		                'title'=>'Pago Cuidador Inicio Reserva',
		                'short-title'=>'Pago Cuidador Inicio Reserva',
		                'parent'=>'kmimos',
		                'slug'=>'bp_saldo_cuidadores_bookinkstart',
		                'access'=>'manage_options',
		                'page'=>'backpanel_saldo_cuidador_BookingStart',
		                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
		        );
		        $menus[] = array(
		                'title'=>'Pago Cuidador Detalle',
		                'short-title'=>'Pago Cuidador Detalle',
		                'parent'=>'kmimos',
		                'slug'=>'bp_saldo_cuidadores_detalle',
		                'access'=>'manage_options',
		                'page'=>'backpanel_saldo_cuidador_detalle',
		                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
		        );

      			$menus[] = array(
      				'title'=>'Saldo de Cupones',
      				'short-title'=>'Saldo de Cupones',
      				'parent'=>'kmimos',
      				'slug'=>'bp_saldo_cupon',
      				'access'=>'manage_options',
      				'page'=>'backpanel_saldo_cupon',
      				'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
      			);
		      }
         /* Temporal ********************* */

	        return $menus;

	    }
	}

	if(!function_exists('backpanel_saldo_cuidador')){
	        function backpanel_saldo_cuidador(){
	            include_once('dashboard/backpanel_saldo_cuidador.php');
	        }
	}

	if(!function_exists('backpanel_mascotas')){
	        function backpanel_mascotas(){
	            include_once('dashboard/backpanel_mascotas.php');
		}
	}

	if(!function_exists('backpanel_saldo_cuidador_detalle')){
        function backpanel_saldo_cuidador_detalle(){
            include_once('dashboard/backpanel_saldo_cuidador_detalle.php');
        }
    }

	if(!function_exists('backpanel_newsletter')){
        function backpanel_newsletter(){
            include_once('dashboard/backpanel_newsletter.php');
        }
    }

	if(!function_exists('backpanel_ctr_participantes')){
        function backpanel_ctr_participantes(){
            include_once('dashboard/backpanel_ctr_participantes.php');
        }
    }

    if(!function_exists('backpanel_ctr_referidos')){
        function backpanel_ctr_referidos(){
            include_once('dashboard/backpanel_ctr_referidos.php');
        }
    }

    if(!function_exists('backpanel_conocer_cuidador')){
        function backpanel_conocer_cuidador(){
            include_once('dashboard/backpanel_conocer_cuidador.php');
        }
    }

    if(!function_exists('backpanel_reservas')){
        function backpanel_reservas(){
            include_once('dashboard/backpanel_reservas.php');
        }
    }

    if(!function_exists('backpanel_subscribe')){
        function backpanel_subscribe(){
            include_once('dashboard/backpanel_subscribe.php');
        }
    }

    if(!function_exists('backpanel_clientes')){
        function backpanel_clientes(){
            include_once('dashboard/backpanel_clientes.php');
        }
    }

    if(!function_exists('backpanel_cuidadores')){
        function backpanel_cuidadores(){
            include_once('dashboard/backpanel_cuidadores.php');
        }
    }

    if(!function_exists('backpanel_estados_cuidadores')){
        function backpanel_estados_cuidadores(){
            include_once('dashboard/backpanel_estados_cuidadores.php');
        }
    }

	if(!function_exists('backpanel_wlabel')){
        function backpanel_wlabel(){
            include_once('wlabel/admin/backpanel.php');
        }
    }

	if(!function_exists('backpanel_saldo_cupon')){
		function backpanel_saldo_cupon(){
			include_once('dashboard/backpanel_saldo_cupon.php');
		}
	}

	if(!function_exists('backpanel_multinivel')){
		function backpanel_multinivel(){
			include_once('dashboard/backpanel_multinivel.php');
		}
	}

	