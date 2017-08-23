<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>includes/js/script.js"></script>
        <link media="all" type="text/css" rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>includes/css/style.css"/>
        <title>PANEL WHITE LABEL</title>
    </head>
    <body>

    <div id="panel">
        <?php
        if($_wlabel_user->login){
            include_once('backend/panel.php');
        }else{
            include_once('backend/login.php');
        }
        ?>
    </div>

    </body>
</html>
