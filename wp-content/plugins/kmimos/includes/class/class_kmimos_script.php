<?php
class Class_Kmimos_Script{
    var $args;
    function __construct($args=array()){
        global $wpdb;
        $this->args = $args;
        $this->wpdb = $wpdb;
        $this->filejs_script = plugins_url('js/', dirname(__FILE__));
        $this->Script();
    }
    function Script(){
        wp_enqueue_script('kmimos_scriptjs',$this->filejs_script.'script.js', '', '', true);
        wp_enqueue_script('kmimos_scriptjs_scroll_visible',$this->filejs_script.'scroll/scroll_visible/scroll-visible.js', '', '', true);
        //wp_enqueue_script('kmimos_scriptjs_scroll_paralax',$this->filejs_script.'scroll/scroll-paralax/scroll-paralax.js', '', '', true);
        //wp_enqueue_script('kmimos_scriptjs_scroll_carousel',$this->filejs_script.'scroll/scroll-carousel/scroll-carousel.js', '', '', true);
        //wp_enqueue_script('kmimos_scriptjs_scroll_suavizar',$this->filejs_script.'scroll/scroll-suavizar/scroll-suavizar.js', '', '', true);
        wp_enqueue_script('kmimos_scriptjs_scroll_efecto',$this->filejs_script.'scroll/scroll_efecto/scroll-efecto.js', '', '', true);
        wp_enqueue_script('kmimos_scriptjs_image_load',$this->filejs_script.'image/image-load.js', '', '', true);
        wp_enqueue_script('kmimos_scriptjs_image_easyload',$this->filejs_script.'image/image-easyload.js', '', '', true);
        wp_enqueue_style('kmimos_scriptjs_scroll_cssefecto',$this->filejs_script.'scroll/scroll_efecto/scroll-efecto.css');
        wp_enqueue_style('kmimos_scriptjs_scroll_csseasyload',$this->filejs_script.'image/image-easyload.css');
    }
}
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}
$_kmimos_Script = new Class_Kmimos_Script();


