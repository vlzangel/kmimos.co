<?php

    include("../../../../../vlz_config.php");

    date_default_timezone_set('America/Mexico_City');

	$conn = new mysqli($host, $user, $pass, $db);

	$errores = array();

    extract($_POST);

	if ($conn->connect_error) {
        echo 'false';
	}else{

        $existen = $conn->query( "SELECT * FROM wp_users WHERE user_email = '{$email_1}'" );
        if( $existen->num_rows > 0 ){
            $datos = $existen->fetch_assoc();

            $msg = "Se encontraron los siguientes errores:\n\n";

            if( $datos['user_email'] == $email_1 ){
                $msg .= "Este E-mail [{$email_1}] ya esta en uso\n";
            }

            $error = array(
                "error" => "SI",
                "msg" => $msg
            );

            echo "(".json_encode( $error ).")";

            exit;

        }else{

            $hoy = date("Y-m-d H:i:s");

            $new_user = "
                INSERT INTO wp_users VALUES (
                    NULL,
                    '".$email_1."',
                    '".md5($clave)."',
                    '".$email_1."',
                    '".$email_1."',
                    '',
                    '".$hoy."',
                    '',
                    0,
                    '".$nombres." ".$apellidos."'
                );
            ";

            $conn->query( utf8_decode( $new_user ) );
            $user_id = $conn->insert_id;




            //WHITE_LABEL
            if (!isset($_SESSION)) {
                session_start();
            }

            if(array_key_exists('wlabel',$_SESSION) || $referido=='Volaris' || $referido=='Vintermex'){
                $wlabel='';

                if(array_key_exists('wlabel',$_SESSION)){
                    $wlabel=$_SESSION['wlabel'];

                }else if($referido=='Volaris'){
                    $wlabel='volaris';

                }else if($referido=='Vintermex'){
                    $wlabel='viajesintermex';
                }

                if ($wlabel!=''){
                    $query_wlabel = "INSERT INTO wp_usermeta VALUES (NULL, '".$user_id."', '_wlabel', '".$wlabel."');";
                    $conn->query( utf8_decode( $query_wlabel ) );
                }
            }


            $name_photo = "";
            $user_photo = 0;
            if( $vlz_img_perfil != "" ){
                $name_photo = time();
                $user_photo = 1;
                $img = end(explode(',', $vlz_img_perfil));
                $sImagen = base64_decode($img);
                $dir = "../../../../uploads/avatares_clientes/{$user_id}/";
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
            }
            
            $sql = "
                INSERT INTO wp_usermeta VALUES
                    (NULL, {$user_id}, 'user_pass',           '{$clave}'),
                    (NULL, {$user_id}, 'user_photo',          '{$user_photo}'),
                    (NULL, {$user_id}, 'name_photo',          '{$name_photo}.jpg'),
                    (NULL, {$user_id}, 'user_mobile',         '{$movil}'),
                    (NULL, {$user_id}, 'user_phone',          '{$telefono}'),
                    (NULL, {$user_id}, 'user_country',        'México'),
                    (NULL, {$user_id}, 'nickname',            '{$email_1}'),
                    (NULL, {$user_id}, 'first_name',          '{$nombres}'),
                    (NULL, {$user_id}, 'last_name',           '{$apellidos}'),
                    (NULL, {$user_id}, 'description',         '{$descripcion}'),
                    (NULL, {$user_id}, 'user_referred',       '{$referido}'),
                    (NULL, {$user_id}, 'rich_editing',        'true'),
                    (NULL, {$user_id}, 'comment_shortcuts',   'false'),
                    (NULL, {$user_id}, 'admin_color',         'fresh'),
                    (NULL, {$user_id}, 'use_ssl',             '0'),
                    (NULL, {$user_id}, 'show_admin_bar_front', 'false'),
                    (NULL, {$user_id}, 'wp_capabilities',     'a:1:{s:10:\"subscriber\";b:1;}'),
                    (NULL, {$user_id}, 'wp_user_level',       '0');
            ";
            $conn->query( utf8_decode( $sql ) );

            echo $user_id;

        }
        
	}

?>