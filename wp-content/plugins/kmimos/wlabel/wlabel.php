<?php

$WP_path_load =dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/wp-load.php';
if(file_exists($WP_path_load)){
    include_once($WP_path_load);
}

//CLASS
include_once(dirname(__FILE__).'/includes/class/class_whitelabel.php');
include_once(dirname(__FILE__).'/includes/class/class_whitelabel-user.php');
$_wlabel = new Class_WhiteLabel();
$_wlabel_user = new Class_WhiteLabel_User();


//STYLE HEADER
add_action('wp_head', 'WhiteLabel_ADDheader');
function WhiteLabel_ADDheader(){
    global $_wlabel;
    $_wlabel->Header();
}

//STYLE FOOTER
add_action('wp_footer', 'WhiteLabel_ADDfooter');
function WhiteLabel_ADDfooter(){
    global $_wlabel;
    $_wlabel->Footer();
}

//USER REGISTER
add_action('user_register', 'WhiteLabel_registration_save', 10, 1);
function WhiteLabel_registration_save($user_id){
    global $_wlabel;
    if ($_wlabel->wlabel_active){
        update_user_meta($user_id, '_wlabel', $_wlabel->wlabel);
    }
}

//ORDER PROSSESING
add_action('woocommerce_thankyou', 'WhiteLabel_custom_processing');
add_action('woocommerce_order_status_completed', 'WhiteLabel_custom_processing');
add_action('woocommerce_order_status_processing', 'WhiteLabel_custom_processing');
function WhiteLabel_custom_processing($order){
    global $_wlabel;
    $wlabel=$_wlabel->wlabel;

    if ($_wlabel->wlabel_active){
        update_post_meta($order, '_wlabel', $wlabel);
    }

    //kmimos modified
    $modified_order = get_post_meta($order,'reserva_modificada', true);
    if(!empty($modified)){
        $modified_walbel = get_post_meta($modified_order,'_wlabel', true);
        if(!empty($modified_walbel)){
            update_post_meta($order, '_wlabel', $modified_walbel);
        }
    }
}




//CREATE TEMPLATE
add_filter('page_template', 'WhiteLabel_Template');
function WhiteLabel_Template($template){
    if(is_page('wlabel')){
        $dirtemplate=dirname(__FILE__).'/index.php';
        if(file_exists($dirtemplate)){
            $template=$dirtemplate;
        }
    }
    return $template;
}


//CREATE PAGE
add_action('init', 'WhiteLabel_Page');
function WhiteLabel_Page(){
    global $user_ID;
    $post_name = 'wlabel';
    $post_title = 'Label';
    $post_content = '';
    $page = get_page_by_path($post_name);
    $post=array('post_author'=>$user_ID, 'post_content'=>$post_content, 'post_name'=>$post_name, 'post_status'=>'publish', 'post_title'=>$post_title, 'post_type'=>'page', 'post_parent'=>0, 'menu_order'=>0, 'to_ping'=> '', 'pinged'=>'');

    if($page->post_name!=$post_name){
        $insert = wp_insert_post($post);
        if(!$insert){
            wp_die('Error creando Post');
        }
    }else{
        //update_post_meta($page_id,'_wp_page_template', 'page_registro.php' );
    }
    return;
}


//RULES HTACCESS
add_action('generate_rewrite_rules', 'WhiteLabel_add_rewrite_rules');
function WhiteLabel_add_rewrite_rules($wp_rewrite){
    $new_rules = array(
        'label/?$' =>'index.php?pagename=wlabel',
        'label/([a-z]*)/?$' =>'index.php?pagename=wlabel&wlabel='.$wp_rewrite->preg_index(1));
    $wp_rewrite->rules=$new_rules+$wp_rewrite->rules;
}

add_filter('query_vars', 'WhiteLabel_query_vars');
function WhiteLabel_query_vars($query_vars){
    //$query_vars[]="wlabel";
    return $query_vars;
}

add_action('init', 'WhiteLabel_flush_rewrite_rules');
function WhiteLabel_flush_rewrite_rules(){
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

?>