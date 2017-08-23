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

	if(!function_exists('italo_menus')){
	    function italo_menus($menus){

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

	        $menus[] = array(
	                'title'=>'Control WhiteLabel',
	                'short-title'=>'Control WhiteLabel',
	                'parent'=>'kmimos',
	                'slug'=>'bp_wlabel',
	                'access'=>'manage_options',
	                'page'=>'backpanel_wlabel',
	                'icon'=>plugins_url('/assets/images/icon.png', __FILE__)
	        );

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

	        return $menus;
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

?>