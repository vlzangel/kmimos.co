<?php

    include("../../../../../wp-config.php");
	include("../funciones/vlz_funciones_globales.php");

    date_default_timezone_set('America/Bogota');

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$errores = array();

    extract($_POST);

	if ($conn->connect_error) {
        echo 'false';
	}else{

		foreach ($_POST as $key => $value) {
			if($value == ''){ $_POST[$key] = 0; }
		}

        extract($_POST);

        $foto = "0";
        if( $vlz_img_perfil != "0" ){
        	$foto = "1";
        }
        $experiencia = date("Y")-$cuidando_desde;

        $mascotas_cuidador = array(
        	"pequenos" => $tengo_pequenos+0,
        	"medianos" => $tengo_medianos+0,
        	"grandes"  => $tengo_grandes+0,
        	"gigantes" => $tengo_gigantes+0
        );
        $mascotas_cuidador = serialize($mascotas_cuidador);

        $tamanos_aceptados = array(
        	"pequenos" => $acepto_pequenos+0,
        	"medianos" => $acepto_medianos+0,
        	"grandes"  => $acepto_grandes+0,
        	"gigantes" => $acepto_gigantes+0
        );
        $tamanos_aceptados = serialize($tamanos_aceptados);

        $edades_aceptadas = array(
        	"cachorros" => $cachorros+0,
        	"adultos"   => $adultos+0
        );
        $edades_aceptadas = serialize($edades_aceptadas);

        $comportamientos_aceptados = array(
        	"sociables" 			=> $comportamiento_no_sociables+0,
        	"no_sociables"   		=> $comportamiento_no_sociables+0,
        	"agresivos_perros"   	=> $comportamiento_agresivos_perros+0,
        	"agresivos_personas"   	=> $comportamiento_agresivos_humanos+0,
        );
        $comportamientos_aceptados = serialize($comportamientos_aceptados);

        $hospedaje = array(
        	"pequenos" => $hospedaje_pequenos,
        	"medianos" => $hospedaje_medianos,
        	"grandes"  => $hospedaje_grandes,
        	"gigantes" => $hospedaje_gigantes
        );

        $imgs_product = array(
            "hospedaje"                 => "8370",
            "guarderia"                 => "8371",
            "adiestramiento_basico"     => "8372",
            "adiestramiento_intermedio" => "8372",
            "adiestramiento_avanzado"   => "8372",
            "paseos"                    => "8372"
        );

        $temp = array();
        if(count($hospedaje) > 0){
	        foreach ($hospedaje as $key => $value) {
	        	if($value+0 > 0){
	        		$temp[] = $value;
	        	}
	        }
        }
        if(count($temp) > 0){ $hospedaje_desde = min($temp); }else{ $hospedaje_desde = 0; }
        
        $hospedaje = serialize($hospedaje);

        $token = md5(microtime());

        $atributos = array(
        	"green" => $areas_verdes+0,
        	"yard" => $tiene_patio+0,
        	"propiedad"  => $tipo_propiedad+0,
        	"esterilizado" => $esterilizado+0,
            "emergencia" => $emergencia+0,
        	"token" => $token
        );
        $atributos = serialize($atributos);

        $slugs_adicionales = array(
        	"",
	        "transportacion_sencilla",
	        "transportacion_redonda",
	        "visita_al_veterinario",
	        "acupuntura",
	        "limpieza_dental",
	        "bano",
	        "corte",
	        "guarderia",
	        "adiestramiento_basico",
	        "adiestramiento_intermedio",	        
            "adiestramiento_avanzado",
	        "paseos"
	    );

        $adicionales = array();

        if( count($servicio) > 0 ){
        	foreach ($servicio as $key => $value) {
        		$temp = array(
		        	"pequenos" => $adicional_pequenos[$key]+0,
		        	"medianos" => $adicional_medianos[$key]+0,
		        	"grandes"  => $adicional_grandes[$key]+0,
		        	"gigantes" => $adicional_gigantes[$key]+0
		        );
		        $adicionales[ $slugs_adicionales[$value] ] = $temp;
		        $temp = NULL;
        	}
        }

        $adicionales_extra = array(
            "bano",
            "corte",
            "limpieza_dental",
            "visita_al_veterinario",
            "acupuntura"
        );
        foreach ($adicionales_extra as $value) {
            if( $_POST['adicional_'.$value] > 0 ){
                $adicionales[ $value ] = $_POST['adicional_'.$value]+0;
            }
        }

        $transporte = array(
            "trans_sencillo_" => "transportacion_sencilla",
            "trans_redonda_"  => "transportacion_redonda"
        );
        $rutas = array(
            "corto",
            "medio",
            "largo"
        );
        foreach ($transporte as $pre => $slug) {
            foreach ($rutas as $ruta) {
                if( $_POST[$pre.$ruta]+0 > 0 ){
                    $adicionales[ $slug ][$ruta] = $_POST[$pre.$ruta]+0;
                }
            }
        }

        $adicionales = serialize($adicionales);

        $sql = "
        	INSERT INTO cuidadores VALUES (
        		NULL,
        		'0',
        		'0',
        		'$ife',
        		'$email',
        		'$experiencia',
        		'0',
        		'$latitud',
        		'$longitud',
        		'$direccion',
        		'$num_mascotas_casa',
        		'$num_mascotas_aceptadas',
        		'$entrada',
        		'$salida',
        		'$mascotas_cuidador',
        		'$tamanos_aceptados',
        		'$edades_aceptadas',
        		'$comportamientos_aceptados',
        		'$hospedaje',
        		'$hospedaje_desde',
        		'$adicionales',
        		'$atributos',
                '0',
                '0'
        	);
        ";

        $existen = $conn->query( "SELECT * FROM wp_users WHERE  user_nicename = '{$username}' OR user_email = '{$email}'" );
        if( $existen->num_rows > 0 ){
            $datos = $existen->fetch_assoc();

            $msg = "Se encontraron los siguientes errores:\n\n";

            if( $datos['user_email'] == $email ){
                $msg .= "Este E-mail [{$email}] ya esta en uso\n";
            }

            if( $datos['user_nicename'] == $username ){
                $msg .= "Este nombre de Usuario [{$username}] ya esta en uso\n";
            }

            $error = array(
                "error" => "SI",
                "msg" => $msg
            );

            echo "(".json_encode( $error ).")";

            exit;

        }else{

            $temp = array( "token" => $token );

            /*include('Requests.php');

            Requests::register_autoloader();

            $options = array(
                'wstoken'               =>  "496e2def61883d009a258ef2ee03aed6",
                'wsfunction'            =>  "kmimos_user_create_users",
                'moodlewsrestformat'    =>  "json",
                'users' => array(
                    0 => array(
                        'username'      => $username,
                        'password'      => $clave,
                        'firstname'     => $nombres,
                        "lastname"      => $apellidos,
                        "email"         => $email,
                        "preferences"   => array(
                            0 => array(
                                "type"  => 'kmimostoken',
                                "value" => $token
                            )
                        ),
                        "cohorts" => array(
                            0 => array(
                                "type"  => 'idnumber',
                                "value" => "kmi-qsc"
                            )
                        )
                    )
                )
            );

            $request = Requests::post('http://kmimos.ilernus.com/webservice/rest/server.php', array(), $options );

            $respuesta = json_decode($request->body);
            if( isset($respuesta->exception)){
                $error = array(
                    "error" => "SI",
                    "msg" => "No se pudo crear el registro en la plataforma de certificación"
                );
                echo "(".json_encode( $error ).")";
                exit;
            }else{
                $error = array(
                    "error" => "NO",
                    "msg" => ""
                );
                echo "(".json_encode( $error ).")";
            }*/

            $error = array(
                "error" => "NO",
                "msg" => ""
            );
            echo "(".json_encode( $error ).")";
            
            if( $conn->query( utf8_decode( $sql ) ) ){

                $cuidador_id = $conn->insert_id;

                $hoy = date("Y-m-d H:i:s");

                $new_user = "
                    INSERT INTO wp_users VALUES (
                        NULL,
                        '".$username."',
                        '".md5($clave)."',
                        '".$username."',
                        '".$email."',
                        '',
                        '".$hoy."',
                        '',
                        0,
                        '".$nombres." ".$apellidos."'
                    );
                ";

                $conn->query( utf8_decode( $new_user ) );
                $user_id = $conn->insert_id;

                $conn->query( "UPDATE cuidadores SET user_id = '".$user_id."' WHERE id = ".$cuidador_id);

                $sql = "INSERT INTO ubicaciones VALUES (NULL, '{$cuidador_id}', '={$estado}=', '={$municipio}=')";
                $conn->query( utf8_decode( $sql ) );

                if($foto == 1){
                    $img = end(explode(',', $vlz_img_perfil));
                    $sImagen = base64_decode($img);
                    $dir = "../../../../uploads/avatares/".$user_id."/";
                    @mkdir($dir);

                    file_put_contents($dir.'temp.jpg', $sImagen);
                    $sExt = mime_content_type( $dir.'temp.jpg' );
                    switch( $sExt ) {
                        case 'image/jpeg':
                            $aImage = @imageCreateFromJpeg( $dir.'temp.jpg' );
                        break;
                        case 'image/gif':
                            $aImage = @imageCreateFromGif( $dir.'temp.jpg' );
                        break;
                        case 'image/png':
                            $aImage = @imageCreateFromPng( $dir.'temp.jpg' );
                        break;
                        case 'image/wbmp':
                            $aImage = @imageCreateFromWbmp( $dir.'temp.jpg' );
                        break;
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

                    $name_photo = time();

                    imagejpeg( $aThumb, $dir."{$name_photo}.jpg" );
                    imageDestroy( $aImage );
                    imageDestroy( $aThumb );

                    unlink($dir."temp.jpg");
                }

                $sql = "
                    INSERT INTO wp_usermeta VALUES
                        (NULL, ".$user_id.", 'user_favorites',      ''),
                        (NULL, ".$user_id.", 'name_photo',          '".$name_photo."'),
                        (NULL, ".$user_id.", 'user_address',        '".$direccion."'),
                        (NULL, ".$user_id.", 'user_mobile',         '".$telefono."'),
                        (NULL, ".$user_id.", 'user_country',        'Colombia'),
                        (NULL, ".$user_id.", 'nickname',            '".$username."'),
                        (NULL, ".$user_id.", 'first_name',          '".$nombres."'),
                        (NULL, ".$user_id.", 'last_name',           '".$apellidos."'),
                        (NULL, ".$user_id.", 'user_referred',       '".$referido."'),
                        (NULL, ".$user_id.", 'description',         ''),
                        (NULL, ".$user_id.", 'rich_editing',        'true'),
                        (NULL, ".$user_id.", 'comment_shortcuts',   'false'),
                        (NULL, ".$user_id.", 'admin_color',         'fresh'),
                        (NULL, ".$user_id.", 'use_ssl',             '0'),
                        (NULL, ".$user_id.", 'show_admin_bar_front', 'false'),
                        (NULL, ".$user_id.", 'wp_capabilities',     'a:1:{s:6:\"vendor\";b:1;}'),
                        (NULL, ".$user_id.", 'wp_user_level',       '0');
                ";
                $conn->query( utf8_decode( $sql ) );

                $nombres    = trim($nombres);
                $apellidos  = trim($apellidos);

                $slug = $user_id."-".( $nombres  )."-".( substr($apellidos, 0, 1) );

                $slug = urls_amigables( utf8_decode($slug) );

                $nom = strtoupper( substr($nombres, 0, 1) ).strtolower( substr($nombres, 1)  )." ".strtoupper( substr($apellidos, 0, 1) ).".";

                $sql_post_cuidador = "
                    INSERT INTO
                        wp_posts 
                    VALUES (
                        NULL, 
                        ".$user_id.", 
                        '".$hoy."', 
                        '".$hoy."', 
                        '', 
                        '".$nom."', 
                        '', 
                        'pending', 
                        'open', 
                        'closed', 
                        '', 
                        '".$slug."', 
                        '', 
                        '', 
                        '".$hoy."', 
                        '".$hoy."', 
                        '', 
                        0, 
                        '/petsitters/".$slug."/', 
                        0, 
                        'petsitters', 
                        '', 
                        0
                    );
                ";

                $conn->query( utf8_decode( $sql_post_cuidador ) );
                $id_post = $conn->insert_id;

                $conn->query( "UPDATE cuidadores SET id_post = '".$id_post."' WHERE id = ".$cuidador_id);

                //********************************************************************************************************************************
                //
                //    Servicios Adicionales
                //
                //********************************************************************************************************************************

                    $comision = 1.2;

                    $servicios_adicionales = unserialize($adicionales);

                    $servicios_adicionales["hospedaje"] = unserialize($hospedaje);

                    if( count($adicionales) > 0 ){

                        $temp_adicionales = array(); $id_hospedaje = 0;

                        foreach ($servicios_adicionales as $key => $precio) {
                            
                            if( isset( $adicionales_principales[$key] )){

                                if( $precio > 0){
                                    $status = "pending";
                                }else{
                                    $status = "unpublish";
                                }

                                $sql = sql_producto(array(
                                    "user"          => $user_id,
                                    "hoy"           => $hoy,
                                    "titulo"        => $adicionales_principales[$key]." - ".$nom,
                                    "descripcion"   => descripciones($key),
                                    "slug"          => $user_id."-".$key,
                                    "cuidador"      => $id_post,
                                    "status"        => $status
                                ));
                                $conn->query( $sql );

                                $id = $conn->insert_id;

                                if( $key == "hospedaje" ){
                                    $id_hospedaje = $id;
                                }

                                $PH = precios(array(
                                    "precios" => $precio
                                ), $comision);

                                $precios = $PH['precios'];
                                $base = $PH['base'];

                                $sql = sql_meta_producto(array(
                                    "id_servicio"   => $id,
                                    "precio"        => $base,
                                    "cuidador_post" => $id_post,
                                    "cantidad"      => $num_mascotas_aceptadas,
                                    "img"           => $imgs_product[$key],
                                    "slug"          => $key
                                ));
                                $conn->query( $sql );

                                $sql = sql_cats(array(
                                    "servicio"   => $id,
                                    "categoria"  => $cats[$key]
                                ));
                                $conn->query( $sql );

                                foreach ($tamanos as $tam => $value) {

                                    if( $precios[$tam]+0 > 0 ){
                                        $status = "pending";
                                        $precio = $precios[$tam];
                                    }else{
                                        $status = "unpublish";
                                        $precio = "0.00";
                                    }

                                    $sql = sql_variante(array(
                                        "user"      => $user_id,
                                        "hoy"       => $hoy,
                                        "titulo"    => $value,
                                        "precio"    => $precio,
                                        "slug"      => $user_id."-".$key."-".$tam,
                                        "servicio"  => $id,
                                        "menu"      => $order_menu[$tam],
                                        "status"    => $status                
                                    ));

                                    $conn->query( utf8_decode($sql) );

                                    if( $precios[$tam] > 0 ){
                                        $precios[$tam] = $precios[$tam]-$base;
                                    }
                                    $sql = sql_meta_variante(array(
                                        "bookable_person_id" => $conn->insert_id,
                                        "bloque"             => $precios[$tam]
                                    ));
                                       
                                    $conn->query( utf8_decode($sql) );

                                }

                            }

                        }

                        $servicios_adicionales["comision"] = "1.2";
                        $servicios_adicionales["id"] = $id_hospedaje;

                        $sql = sql_addons($servicios_adicionales);

                        $conn->query( ($sql) );

                    }

                    $info = array();
                    $info['user_login']     = sanitize_user($email, true);
                    $info['user_password']  = sanitize_text_field($clave);

                    $user_signon = wp_signon( $info, true );
                    wp_set_auth_cookie($user_signon->ID);

            }else{
                $error = array(
                    "error" => "SI",
                    "msg" => "No se ha podido crear el cuidador"
                );
                echo "(".json_encode( $error ).")";
            }
        }
        
	}

?>