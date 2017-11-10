<?php

    $PATH_TEMPLATE = (dirname(dirname(dirname(__DIR__))));
    
    global $wpdb;

    $id_orden = vlz_get_page();

    $acc = "CFM"; $usu = "CUI";

    $current_user = wp_get_current_user();
	$user_id = $current_user->ID;

    $usuario = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$id_orden}' AND meta_key = 'requester_user'");
    if( $usuario == $user_id ){ $usu = "CLI"; }

    include($PATH_TEMPLATE."/procesos/conocer/index.php");

    $CONTENIDO .= "
        <a class='km-btn-primary volver_msg' href='".get_home_url()."/perfil-usuario/solicitudes/'>Volver</a>
    ";
?>