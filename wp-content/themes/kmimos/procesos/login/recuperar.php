<?php
    include( dirname(dirname(dirname(dirname(dirname(__DIR__)))))."/wp-config.php");

    extract($_POST);
    
    global $wpdb;

    $db = $wpdb;

    $home = $db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'");

    $USER = $db->get_row("SELECT * FROM wp_users WHERE user_email = '{$email}'");

    if( $ID === false ){

    }else{
        $keyKmimos = md5($USER->ID);

        $url_activate = $home."restablecer/?r=".$keyKmimos;

        //MESSAGE

            $user_login = 

            $mail_file = realpath('../../template/mail/recuperar.php');
            $message_mail = file_get_contents($mail_file);
            $message_mail = str_replace('[name]', get_user_meta($USER->ID, "first_name", true), $message_mail);
            $message_mail = str_replace('[url]', $url_activate, $message_mail);
            $message_mail = str_replace('[URL_IMGS]', $home."/wp-content/themes/kmimos/images/emails", $message_mail);

        //MAIL
        $subjet = 'Cambiar contraseña en Kmimos';
        $message = get_email_html($message_mail);
        wp_mail($USER->user_email,  $subjet, $message);

        $response['sts'] = 1;
        $response['msg'] = 'Hemos enviado los pasos para restablecer la contraseña a tu correo.';
        echo json_encode($response);
    }

    exit();

?>