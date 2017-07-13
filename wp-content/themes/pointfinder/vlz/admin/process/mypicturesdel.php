<?php
    try{
        if(isset($_GET['p'])){
            global $wpdb;
            $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
            $tmp_user_id = ($cuidador->id) - 5000;
            $fullpath = "wp-content/uploads/cuidadores/galerias/".$tmp_user_id."/".$_GET['p'];
            if(file_exists($fullpath)){
                unlink($fullpath);
                echo '<h2>Imagen Eliminada Satisfactoriamente</h2>';
            }
        }
    }catch(Exception $e){}
    echo '<br><a href="'.get_option( 'siteurl' ).'/perfil-usuario/?ua=mypictures">Mostrar Mis Fotos</a>"
                                <br><small>Redireccionando a Mis Fotos</small>';
    echo '<script>location.href="'.get_option('siteurl').'/perfil-usuario/?ua=mypictures";</script>';
?>