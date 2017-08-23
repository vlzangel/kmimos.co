<?php

$WP_path_load =dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/wp-load.php';
if(file_exists($WP_path_load)){
    include_once($WP_path_load);
}

//CLASS
include_once(dirname(__FILE__).'/includes/class/class_subscribe.php');
$_subscribe = new Class_Subscribe();


//SCRIPT
add_action('wp_enqueue_scripts', 'subscribe_enqueue_scripts');
function subscribe_enqueue_scripts() {
    wp_enqueue_script('subscribe_script', plugin_dir_url(__DIR__).'subscribe/includes/js/script.js');//
}


function subscribe_input(){
    global $_subscribe;

    $html='<div class="subscribe" data-subscribe="'.plugin_dir_url(__DIR__).'">';
    $html.='<form onsubmit="form_subscribe(this); return false;">';
    $html.='<input type="mail" name="mail" value="" placeholder="" required/>';
    $html.='<button type="submit"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>';
    $html.='</form>';
    $html.='</div>';
    return $html;
}
?>