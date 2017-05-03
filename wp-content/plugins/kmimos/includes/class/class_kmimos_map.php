<?php

class Class_Kmimos_Map{

    var $args;

    function __construct($args=array()){
        global $wpdb;
        $this->args = $args;
        $this->wpdb = $wpdb;
        $this->table_map = $this->wpdb->prefix.'kmimos_map';
        $this->filephp_map = plugins_url('class/class_kmimos_map.php?'.time(), dirname(__FILE__));
        $this->filejs_map = plugins_url('js/kmimos_map.js?'.time(), dirname(__FILE__));
        $this->folder_map = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/uploads/maps/';
        $this->url_map = site_url('wp-content/uploads/maps/');
        $this->BD_Create_Table();
        $this->Script_Map();
    }

    function BD_Create_Table(){
        $table =  $this->table_map;
        $query="CREATE TABLE IF NOT EXISTS $table (id INT NULL AUTO_INCREMENT, filter LONGTEXT NULL, user INT NULL, map LONGTEXT NULL, time LONGTEXT NULL, PRIMARY KEY(id));";
        $this->wpdb->query($query);
        return;
    }

    function BD_Insert($args=array()){
        $table =  $this->table_map;
        $filter =  $args['filter'];
        $user =  $args['user'];
        $map =  $args['map'];
        $time =  time();

        if($table!='' && count($args)>0){
            $query="INSERT INTO $table (filter, user, map, time) VALUES ('$filter','$user','$map','$time');";
            $this->wpdb->query($query);
            $this->Send_Map($map);
        }
    }

    function BD_Update($args=array()){
        $table =  $this->table_map;
        $filter =  $args['filter'];
        $user =  $args['user'];
        $map =  $args['map'];
        $time =  time();

        if($table!='' && count($args)>0){
            $query="UPDATE $table SET user='$user', map='$map', time='$time' WHERE filter='$filter';";
            $this->wpdb->query($query);
            $this->Send_Map($map);
        }
    }

    function BD_Result($filter=array()){
        $table =  $this->table_map;

        $items=array();
        if(count($filter)>0){
            foreach($filter as $index=>$item){
                $items[]="item.filter LIKE '%$item%'";
            }
        }

        $where='';
        if(count($items)>0){
            $where='WHERE '.implode(' AND ',$items);
        }

        $query="SELECT item.* FROM $table  AS item $where";
        return $this->wpdb->get_results($query);
    }

    function Get_Map($file=''){
        $filename=$this->Create_Filename($file);

        if (!file_exists($filename)){
            file_put_contents($filename, '');
        }

        $map=file_get_contents($filename);
        $this->Script_Map_Site($file);
        return $map;
    }

    function Send_Map($file='',$map=''){
        $filename=$this->Create_Filename($file);
        file_put_contents($filename,$map);
    }

    function Create_Filename($file='',$type='path'){
        if($file==''){
            $file='default';
        }
        if($type=='url'){
            return $this->url_map.$file.'.txt';

        }else{
            return $this->folder_map.$file.'.txt';
        }
    }

    function Generate_Filter($filter=array()){
        $filter_result=array();
        $filter_result[]=$filter['otra_latitud'];
        $filter_result[]=$filter['otra_longitud'];
        if(isset($filter['servicios'])){
            foreach($filter['servicios'] as $service){
                $filter_result[]=$service;
            }

        }
        if(isset($filter['tamanos'])){
            foreach($filter['tamanos'] as $size){
                $filter_result[]=$size;
            }

        }
        return $filter_result;
    }

    function Search_Map($filter=array(),$number_user=0){
        $filter_result=$this->Generate_Filter($filter);
        $filter_filename=implode('-',$filter_result);
        $map='';

        $args=array();
        $args['filter']=implode(',',$filter_result);
        $args['user']=$number_user;
        $args['map']=$filter_filename;

        if(count($filter_result)>0){
            $result=$this->BD_Result($filter_result);

            if(count($result)>0){
                $file=$result[0]->map;
                $map=$this->Get_Map($file);
            }else{
                $this->BD_Insert($args);
            }
        }
        return $map;
    }

    function Script_Map(){
        wp_enqueue_script('kmimos_mapjs',$this->filejs_map.'?'.time());
    }

    function Script_Map_Site($file=''){
        echo '<script type="text/javascript">';
        if($file!=''){
            echo "var FILEkmimos_Map='".$file."';";
            echo "var URLkmimos_Map='".$this->Create_Filename($file,'url')."';";
            echo "var PHPkmimos_Map='".$this->filephp_map."';";
        }else{
            echo "var FILEkmimos_Map='';";
            echo "var URLkmimos_Map='';";
            echo "var PHPkmimos_Map='';";
        }
        echo '</script>';
    }
}

$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

$kmimos_map = new Class_Kmimos_Map();


if(isset($_POST['kmimos_map']) && isset($_POST['file']) && isset($_POST['map'])){
    if($_POST['kmimos_map']!='' && $_POST['map']!=''){
        $kmimos_map->Send_Map($_POST['kmimos_map'],str_replace('\"','"',$_POST['map']));
    }
}


