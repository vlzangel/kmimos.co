<?php

class Class_Subscribe{
    var $args;

    function __construct($args=array()){
        global $wpdb, $_GET, $_SESSION, $_COOKIE;
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->args = $args;
        $this->wpdb = $wpdb;
        $this->table = $this->wpdb->prefix.'kmimos_subscribe';
        $this->BD_Create_Table();
    }


    function BD_Create_Table(){
        $table =  $this->table;
        $query="CREATE TABLE IF NOT EXISTS $table (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(50) NULL, email VARCHAR(50) NULL, source VARCHAR(10) NULL, time VARCHAR(50) NULL, PRIMARY KEY(id));";
        $this->wpdb->query($query);
        return;
    }


    function insert($data=array()){
        $table =  $this->table;
        $this->wpdb->insert($table, $data);
        return;
    }

    function result($query='*',$where=''){
        $table =  $this->table;
        $result = $this->wpdb->get_results($query);
       return $result;
    }


}



?>