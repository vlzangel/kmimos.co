<?php
    include(__DIR__."../../../../../../vlz_config.php");
    include_once("../funciones/db.php");

    extract($_POST);
    
    $db = new db( new mysqli($host, $user, $pass, $db) );

    if( preg_match("/[\+]{1,}/", $email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ){
        $fields[] = [ 'name'=>'email', 'msg'=>"Formato de E-mail invalido"];
        echo "NO_MAIL";
        exit();
    }

    $email = $db->get_var("SELECT * FROM wp_users WHERE user_email = '{$email}'");

    if( $email !== false ){
        echo "SI";
    }else{
        echo "NO";
    }
    exit();
        
?>