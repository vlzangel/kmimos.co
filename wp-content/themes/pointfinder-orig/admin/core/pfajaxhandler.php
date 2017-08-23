<?php
/**********************************************************************************************************************************
*
* Custom AJAX Handler for faster load.
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/
define('DOING_AJAX', true);
if (!isset( $_POST['action'])){
    if ( !isset($_GET['action'])) {
        die('Not supported.');
    };
};
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
header('Content-Type: text/html');
send_nosniff_header();
header('Cache-Control: no-cache');
header('Pragma: no-cache');
if(!empty($_POST['action'])){
    $action = esc_attr(trim($_POST['action']));
}
if (empty($action)) {
   $action = esc_attr(trim($_GET['action']));
}
$allowed_actions = array(
    'pfget_onoffsystem',
    'pfget_nagsystem',
    'pfget_infowindow',
    'pfget_markers',
    'pfget_taxpoint',
    'pfget_listitems',
    'pfget_usersystem',
    'pfget_modalsystem',
    'pfget_usersystemhandler',
    'pfget_modalsystemhandler',
    'pfget_favorites',
    'pfget_reportitem',
    'pfget_flagreview',
    'pfget_paymentsystem',
    'pfget_quicksetupprocess',
    'pfget_grabtweets',
    'pfget_autocomplete',
    'pfget_createorder',
    'pfget_claimitem',
    'pfget_searchitems',
    'pfget_imageupload',
    'pfget_imagesystem',
    'pfget_featuresystem',
    'pfget_fieldsystem',
    'pfget_membershipsystem',
    'pfget_membershippaymentsystem',
    'pfget_itemsystem',
    'pfget_listingtype',
    'pfget_listingtypelimits',
    'pfget_posttag',
    'pfget_fileupload',
    'pfget_filesystem',
    'pfget_customtabsystem',
    'pfget_listingpaymentsystem',
    'pfget_featuresfilter'
);

if($action=='pfget_municipalities'){
    header('Content-Type: application/json; charset=UTF-8;');

    $vacios = $estado = '';

    if(isset($_GET['vacios']) && $_GET['vacios']!=''){
        $vacios = esc_attr($_GET['vacios']);
    }

    if(isset($_GET['estado']) && $_GET['estado']!=''){
        $estados = explode(',',esc_attr($_GET['estado']));
    }

    $taxonomy = 'pointfinderlocations';
    $result = array();
    foreach ($estados as $estado) {
        $nombre = get_term($estado, $taxonomy);
        $result[] = '-'.$estado.'|'.$nombre->name;
        if($vacios) $municipios = get_terms($taxonomy, array('orderby' => 'name', 'hide_empty'=>0, 'parent'=>$estado));
        else  $municipios = get_terms($taxonomy, array('orderby' => 'name', 'parent'=>$estado));
        foreach($municipios as $municipio){$result[]=$municipio->term_id.'|'.$municipio->name;}
    }

    die(json_encode($result));
}

if(in_array($action, $allowed_actions)){
    if(is_user_logged_in()){
        do_action('PF_AJAX_HANDLER_'.$action);
    }else{
        do_action('PF_AJAX_HANDLER_nopriv_'.$action);
    }
}else{
	die('-2');
} 

?>