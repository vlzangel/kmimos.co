<?php

class Class_Kmimos_Script{
    var $args;

    function __construct($args=array()){
        global $wpdb;
        $this->args = $args;
        $this->wpdb = $wpdb;
        $this->filejs_script = plugins_url('js/script.js?'.time(), dirname(__FILE__));
        $this->Script();
    }

    function Script(){
        wp_enqueue_script('kmimos_scriptjs',$this->filejs_script.'?'.time(), '', '', true);
    }

}

$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

$_kmimos_Script = new Class_Kmimos_Script();


