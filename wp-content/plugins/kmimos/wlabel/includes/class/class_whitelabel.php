<?php

class Class_WhiteLabel{
//
    var $args;

    function __construct($args=array()){
        global $wpdb, $_GET, $_SESSION, $_COOKIE;
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->args = $args;
        $this->wpdb = $wpdb;
        $this->GET = $_GET;
        $this->SESSION = $_SESSION;
        $this->COOKIE = $_COOKIE;
        $this->wlabel = '';
        $this->wlabel_active = false;
        $this->wlabel_table = $this->wpdb->prefix.'kmimos_wlabel';
        $this->wlabel_table_user = $this->wpdb->prefix.'kmimos_wlabel_user';
        $this->wlabel_result='';
        $this->wlabel_data=array();
        $this->BD_Create_Table();
        $this->Active();
    }

    function BD_Create_Table(){
        $table =  $this->wlabel_table;
        $table_user =  $this->wlabel_table_user;

        $query="CREATE TABLE IF NOT EXISTS $table (id INT   NOT NULL AUTO_INCREMENT, title VARCHAR(50) NULL, wlabel VARCHAR(50) NULL, data LONGTEXT NULL, time VARCHAR(50) NULL, PRIMARY KEY(id));";
        $this->wpdb->query($query);

        $query="CREATE TABLE IF NOT EXISTS $table_user (id INT  NOT NULL AUTO_INCREMENT, name VARCHAR(50) NULL, wlabel VARCHAR(20) NULL, email VARCHAR(50) NULL, user VARCHAR(50) NULL, pass LONGTEXT NULL, time VARCHAR(50) NULL, PRIMARY KEY(id));";
        $this->wpdb->query($query);
        return;
    }

    function vGET(){
        global $_SESSION, $_COOKIE;
        if(array_key_exists('wlabel',$this->GET)){
            $this->wlabel=$this->GET['wlabel'];

            //SESSION
            $_SESSION['wlabel']=$this->wlabel;
            $this->SESSION = $_SESSION;

            //COOKIE
            setcookie('wlabel',$this->wlabel, (time() + (86400*1/12)));
            $this->COOKIE = $_COOKIE;
            return true;
        }
        return false;
    }

    function vSESSION(){
        global $_SESSION, $_COOKIE;
        $vGET = $this->vGET();

        //SESSION
        if(array_key_exists('wlabel',$this->SESSION)){
            $this->wlabel=$this->SESSION['wlabel'];
            return true;

        //COOKIE
        }else if(array_key_exists('wlabel',$this->COOKIE)){
            //setcookie('wlabel','', (time() + (86400*1)));
            $this->wlabel=$this->COOKIE['wlabel'];
            $_SESSION['wlabel']=$this->wlabel;
            $this->SESSION = $_SESSION;
            return true;
        }
        return $vGET;
    }

    function Active(){
        if($this->vSESSION()){
            $wlabel =  $this->wlabel;
            $table =  $this->wlabel_table;
            $query="SELECT * FROM $table WHERE wlabel='$wlabel';";
            $result=$this->wpdb->get_results($query);

            if(count($result)>0){
                $this->wlabel_active = true;
                $this->wlabel_result=$result[0];
                $data = preg_replace('[\n|\r|\n\r|\r\n]','', $this->wlabel_result->data);
                //var_dump($data);

                $this->wlabel_data=json_decode($data);//,true
                //var_dump($this->wlabel_result->data);
                //var_dump($this->wlabel_data);
            }
        }
    }

    function Image(){
        $image=$this->wlabel_data->image;
        //var_dump($image);

        if($image!=''){
            $html='<style type="text/css">';
            $html.='.pf-logo-container{background-image: url('.$image.') !important; background-size: contain !important; background-position: center left !important;}';
            $html.='</style>';
            return $html;
        }
    }

    function Script(){
        $script=$this->wlabel_data->js;

        if($script!=''){
            $script=str_replace('""',"'",$script);
            $html='<script type="text/javascript">';
            $html.=$script;
            $html.='</script>';
            return $html;
        }
    }

    function Css(){
        $color=$this->wlabel_data->color;
        $css=$this->wlabel_data->css;

        $html='<style type="text/css">';
        if($color!='') {
            $html .= '.wpf-header{background-color:' . $color . ' !important;}';
            $html .= '.wpf-header .pftopline{background-color:' . $color . ' !important; border-bottom:1px solid #333;}';
            $html .= '.wpf-header #pf-primary-nav .pfnavmenu .pfnavsub-menu li:hover { background-color: ' . $color . ' !important;}';
            $html .= '.wpf-header #pf-topprimary-navmobi ul { background:  ' . $color . ' !important;}';
            $html .= '.wpf-footer{background-color:' . $color . ' !important; background-image:none !important;}';
            $html .= '.pfnavmenu li:hover{background-color:' . $color . ' !important;}';
            $html .= '.pfnavmenu .pfnavsub-menu li:hover{background-color:' . $color . ' !important;}';
        }
        $html.=$css;
        $html.='</style>';
        return $html;
    }

    function Html($section='header'){
        $html=$this->wlabel_data->html;
        if(array_key_exists($section,$html)){
            return $html->$section;
        }
    }

    function Header(){
        if($this->wlabel_active){
            $html=$this->Image();
            $html.=$this->Css();
            $html.=$this->Script();
            $html.=$this->Html();
            echo $html;
        }
    }

    function Footer(){
        if($this->wlabel_active){
            $footer=$this->Html('footer');
            $html='';
            //if(){}
                $html.=$footer;

            $html=$this->Html('footer');
            echo $html;
        }
    }

}


