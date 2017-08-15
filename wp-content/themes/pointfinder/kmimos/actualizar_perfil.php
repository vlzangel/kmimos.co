<?php
	define('WP_USE_THEMES', false);
    require('../../../../wp-blog-header.php');

    extract($_POST);

    global $wpdb;

    $wpdb->query("UPDATE wp_users SET display_name = '{$nombres} {$apellidos}' WHERE ID = {$user_id};");

    update_user_meta($user_id, 'first_name', $nombres );
    update_user_meta($user_id, 'last_name', $apellidos );
    update_user_meta($user_id, 'user_mobile', $movil );
    update_user_meta($user_id, 'user_phone', $telefono );
    update_user_meta($user_id, 'user_referred', $referido );
    update_user_meta($user_id, 'description', $descripcion );

    $name_photo = "";
    $user_photo = 0;
    if( $vlz_img_perfil != "" ){

    	$datos = get_user_meta($user_id);
    	if( $datos["wp_capabilities"][0] == 'a:1:{s:6:"vendor";b:1;}'){
			$cuidador = $wpdb->get_row("SELECT id, portada FROM cuidadores WHERE user_id = '$user_id'");
			$user_id_tipo = $cuidador->id;
        	$dir = "../../../uploads/cuidadores/avatares/{$user_id_tipo}/";
    	}else{
        	$dir = "../../../uploads/avatares_clientes/{$user_id}/";
    	}
		
		echo $dir;

        $name_photo = time();
        $user_photo = 1;
        $img = end(explode(',', $vlz_img_perfil));
        $sImagen = base64_decode($img);
        @mkdir($dir);
        file_put_contents($dir.'temp.jpg', $sImagen);
        $sExt = mime_content_type( $dir.'temp.jpg' );
        switch( $sExt ) {
            case 'image/jpeg': $aImage = @imageCreateFromJpeg( $dir.'temp.jpg' ); break;
            case 'image/gif':  $aImage = @imageCreateFromGif( $dir.'temp.jpg' );  break;
            case 'image/png':  $aImage = @imageCreateFromPng( $dir.'temp.jpg' );  break;
            case 'image/wbmp': $aImage = @imageCreateFromWbmp( $dir.'temp.jpg' ); break;
        }
        $nWidth  = 800;
        $nHeight = 600;
        $aSize = getImageSize( $dir.'temp.jpg' );
        if( $aSize[0] > $aSize[1] ){
            $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
        }else{
            $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
        }
        $aThumb = imageCreateTrueColor( $nWidth, $nHeight );
        imageCopyResampled( $aThumb, $aImage, 0, 0, 0, 0, $nWidth, $nHeight, $aSize[0], $aSize[1] );
        imagejpeg( $aThumb, $dir.$name_photo.".jpg" );
        imageDestroy( $aImage );
        imageDestroy( $aThumb );
        unlink($dir."temp.jpg");

   	 	update_user_meta($user_id, 'name_photo', $name_photo.'.jpg' );
    }

	wp_set_password( $clave, $user_id );

	$sql = "SELECT ID FROM wp_users WHERE ID = '{$user_id}'";
	$data = $wpdb->get_row($sql);

    $user_id = $data->ID;
	$user = get_user_by( 'id', $user_id ); 
	if( $user ) {
	    wp_set_current_user( $user_id, $user->user_login );
	    wp_set_auth_cookie( $user_id );
	}

	//header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
?>