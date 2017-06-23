<?php
    error_reporting(0);

    include("../../../../../wp-load.php");
    include("../../../../../vlz_config.php");
	include("../funciones/vlz_funciones_globales.php");

    date_default_timezone_set('America/Mexico_City');

	$conn = new mysqli($host, $user, $pass, $db);

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
        if( $vlz_img_perfil != "" ){
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
            "hospedaje"         => "55477",
            "guarderia"         => "55478",
            "adiestramiento_basico"     => "55479",
            "adiestramiento_intermedio" => "55479",
            "adiestramiento_avanzado"   => "55479"
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
	        "adiestramiento_avanzado"
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
        foreach ($transporte as $pre => $slug_tranportacion) {
            foreach ($rutas as $ruta) {
                if( $_POST[$pre.$ruta]+0 > 0 ){
                    $adicionales[ $slug_tranportacion ][$ruta] = $_POST[$pre.$ruta]+0;
                }
            }
        }

        $adicionales = serialize($adicionales);

        $coordenadas = unserialize( $wpdb->get_var("SELECT valor FROM kmimos_opciones WHERE clave = 'municipio_{$param['municipios']}' ") );

        $latitud  = "";
        $longitud = "";

        $respuesta = $conn->query( "SELECT valor FROM kmimos_opciones WHERE clave = 'municipio_{$municipio}' " );
        if( $respuesta->num_rows > 0 ){
            while($valor = $respuesta->fetch_assoc()){
                $coordenadas = unserialize($valor["valor"]);
                $latitud  = $coordenadas["referencia"]->lat;
                $longitud = $coordenadas["referencia"]->lng;
            }
        }

        $sql = "
        	INSERT INTO cuidadores VALUES (
        		NULL,
        		'0',
        		'0',
        		'$nombres',
        		'$apellidos',
        		'$ife',
        		'$email',
        		'$telefono',
        		'$descripcion',
        		'$foto',
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

        $existen = $conn->query( "SELECT * FROM wp_users WHERE  user_login = '{$username}' OR user_email = '{$email}'" );
        if( $existen->num_rows > 0 ){
            $msg = "Se encontraron los siguientes errores:\n\n";
            while($datos = $existen->fetch_assoc()){
                if( strtolower($datos['user_email']) == strtolower($email) ){
                    $msg .= "Este E-mail [{$email}] ya esta en uso\n";
                }

                if( strtolower($datos['user_login']) == strtolower($username) ){
                    $msg .= "Este nombre de Usuario [{$username}] ya esta en uso\n";
                }
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
                'wstoken'               =>  "e8738b6e6fad761768364d25c916f5e5",
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

            $request = Requests::post('http://kmimos.ilernus.com/webservice/rest/server.php', array(), $options );*/

            if( $conn->query( utf8_decode( $sql ) ) ){

                $cuidador_id = $conn->insert_id;

                $sql = "INSERT INTO ubicaciones VALUES (NULL, '{$cuidador_id}', '={$estado}=', '={$municipio}=')";

                $conn->query( utf8_decode( $sql ) );

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

                $conn->query( "UPDATE cuidadores SET user_id = '".$user_id."' WHERE id = ".$cuidador_id);

                if($vlz_img_perfil != ""){
                    $dir = "../../../../uploads/cuidadores/avatares/".$cuidador_id."/";

                    @mkdir($dir);

                    $path_origen = "../../../../../imgs/Temp/".$vlz_img_perfil;
                    $path_destino = $dir.$vlz_img_perfil;

                    if( file_exists($path_origen) ){
                        copy($path_origen, $path_destino);
                        unlink($path_origen);
                    }
                }
                
                $sql = ("
                    INSERT INTO wp_posts VALUES (
                        NULL,
                        '".$user_id."',
                        '".$hoy."',
                        '".$hoy."',
                        '',
                        '',
                        '',
                        'inherit',
                        'closed',
                        'closed',
                        '',
                        '',
                        '',
                        '',
                        '".$hoy."',
                        '".$hoy."',
                        '',
                        '0',
                        'http://qa.kmimos.la/kmimos/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg',
                        '0',
                        'attachment',
                        'image/jpeg',
                        '0'
                    );
                ");
                $conn->query( utf8_decode( $sql ) );
                $img_id = $conn->insert_id;

                $sql = "INSERT INTO wp_postmeta VALUES (NULL, ".$img_id.", '_wp_attached_file', 'cuidadores/avatares/".$cuidador_id."/0.jpg');";
                $conn->query( utf8_decode( $sql ) );

                $sql = "
                    INSERT INTO wp_usermeta VALUES
                        (NULL, ".$user_id.", 'user_favorites',      ''),
                        (NULL, ".$user_id.", 'user_photo',          '".$img_id."'),
                        (NULL, ".$user_id.", 'user_address',        '".$direccion."'),
                        (NULL, ".$user_id.", 'user_phone',          '".$telefono."'),
                        (NULL, ".$user_id.", 'user_mobile',         '".$telefono."'),
                        (NULL, ".$user_id.", 'user_country',        'México'),
                        (NULL, ".$user_id.", 'nickname',            '".$username."'),
                        (NULL, ".$user_id.", 'first_name',          '".$nombres."'),
                        (NULL, ".$user_id.", 'last_name',           '".$apellidos."'),
                        (NULL, ".$user_id.", 'user_referred',       '".$referido."'),
                        (NULL, ".$user_id.", 'description',         '".$descripcion."'),
                        (NULL, ".$user_id.", 'name_photo',          '".$vlz_img_perfil."'),
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
                        '".$user_id."', 
                        '', 
                        '', 
                        '".$hoy."', 
                        '".$hoy."', 
                        '', 
                        0, 
                        'http://www.kmimos.com.mx/petsitters/".$user_id."/', 
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
                                    "slug"          =>  $user_id."-".$key,
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
                                        "slug"      => $id_post."-".$key."-".$tam,
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
                    $info['user_login']     = sanitize_user($username, true);
                    $info['user_password']  = sanitize_text_field($clave);

                    $user_signon = wp_signon( $info, true );
                    wp_set_auth_cookie($user_signon->ID);

                    $mensaje_mail = '
                        <style>
                            p{
                                text-align: justify;
                            }
                            a:hover{
                                background: #038063;
                            }
                        </style>
                        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
                        <p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>

                        <p style="text-align: justify;">
                            Estimado Kmiamigo, tu perfil ha sido creado con éxito.  El mismo permanecerá inactivo en la página hasta que completes los siguientes pasos listados abajo"
                        </p>
                        <p style="text-align: justify;">
                            "Dichos pasos han sido diseñados para cumplir con un estricto perfil de seguridad, que garantice que cualquier persona que se convierta en Cuidador asociado Kmimos presente un perfil apto para cuidar y apapachar a nuestros peludos amigos"
                        </p>
                        <p style="text-align: justify;">
                            <strong>Siguientes Pasos para activar tu perfil</strong>
                        </p>
                        <p style="text-align: justify;">
                            <ul>
                                <li>Compártenos por Mensaje Directo a nuestro Facebook @Kmimosmx tu nombre y apellido completo, email, teléfono de casa y celular</li>
                                <li>Una vez que nos envíes dichos datos, en menos de 24 horas recibirás en el correo que registraste las Pruebas Psicométricas y Pruebas de Conceptos Veterinarios básicos.  Por favor respóndelas, y nos llegará a nosotros un mensaje de completadas.</li>
                                <li>En menos de 24 horas después de completadas las pruebas recibirás un correo por parte de Certificación Kmimos, notificando tus resultados.  NO TE OLVIDES DE CHECAR SIEMPRE LA BANDEJA DE ENTRADA O EL CORREO NO DESEADO, ya que a veces llegan allí los correos.</li>
                                <li>En caso de haber aprobado, lee el archivo adjunto al correo que te muestra las políticas operativas.</li>
                                <li>Por último, recibirás una llamada para entrevista telefónica y notificación para la auditoría a tu hogar.</li>
                            </ul>
                        </p>
                        <p style="text-align: justify;">
                            <strong>Abajo encontrarás tus credenciales para que tengas acceso como cuidador a Kmimos, estos mismos los deberás usar en la plataforma de certificación.</strong>
                        </p>
                        <p>
                            <table>
                                <tr> <td> <strong>Usuario:</strong> </td><td>'.$username.'</td> </tr>
                                <tr> <td> <strong>Contraseña:</strong> </td><td>La clave que ingresaste</td> </tr>
                            </table>
                        </p>
                        <p style="text-align: center;">
                            <a 
                                href="'.get_home_url().'/?a=inicio"
                                style="
                                    padding: 10px;
                                    background: #59c9a8;
                                    color: #fff;
                                    font-weight: 400;
                                    font-size: 17px;
                                    font-family: Roboto;
                                    border-radius: 3px;
                                    border: solid 1px #1f906e;
                                    display: block;
                                    max-width: 300px;
                                    margin: 0px auto;
                                    text-align: center;
                                    text-decoration: none;
                                "
                            >Iniciar Sesión</a>
                        </p>
                    ';

/*                $mensaje_mail = '
                        <style>
                            p{
                                text-align: justify;
                            }
                            a:hover{
                                background: #038063;
                            }
                        </style>
                        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
                        <p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>
                        <p style="text-align: justify;">
                           Estimado Kmiamigo, tu perfil ha sido creado con éxito. El mismo permanecerá INACTIVO en la página hasta que completes tu proceso de certificación que consta:
                        </p>
                        <p style="text-align: justify;  text-align: left;">
                            <ul style="text-align: justify;  text-align: left;">
                                <li>Pruebas de conocimiento veterinario.</li>
                                <li>Pruebas Psicométricas.</li>
                                <li>Documentación (IFE, Comprobante de domicilio y Datos Bancarios.</li>
                             </ul>
                        </p>

                        <p style="text-align: justify;">
                            Dicho proceso ha sido diseñado por expertos veterinario para escoger a las personas más adecuadas para recibir, cuidar y apapachar a nuestros peludos amigos.
                        </p>

                        <p style="text-align: justify;">
                            Ahora serás dirigido a hacia el paso 1, <strong>PRUEBAS DE CONOCIMIENTO VETERINARIO</strong>.
                        </p>
                        <p style="text-align: justify;">
                            Guarda el siguiente link, ahí puedes continuar con las pruebas en caso de no terminarlas por algún imprevisto y/o para cargar documento.
                        </p>
                        <p style="text-align: justify;">
                            <strong>Link para continuar es:</strong> http://kmimos.ilernus.com
                        </p>
                        <p style="text-align: justify; color: #f00;">
                            <strong>INGRESA CON EL NOMBRE DE USUARIO Y CONTRASEÑA:</strong>
                        </p>
                        <p style="text-align: justify;">
                            <ul style="text-align: justify;  text-align: left;">
                                <li><strong>Usuario: </strong> '.$username.'</li>
                                <li><strong>Contraseña:</strong> '.$clave.'</li>
                            </ul>
                        </p>
                        <p style="text-align: justify;">
                           ¡EXITO!
                        </p>
                        <p style="text-align: center;">
                            <a class="theme_button"
                                href="http://kmimos.ilernus.com"
                                style="
                                    padding: 10px;
                                    background: #59c9a8;
                                    color: #fff;
                                    font-weight: 400;
                                    font-size: 17px;
                                    font-family: Roboto;
                                    border-radius: 3px;
                                    border: solid 1px #1f906e;
                                    display: block;
                                    max-width: 300px;
                                    margin: 0px auto;
                                    text-align: center;
                                    text-decoration: none;
                                "
                            >Continuar</a>
                        </p>
                    ';*/

                    $mensaje_web = '
                        <style>
                            p{
                                text-align: justify;
                            }
                            a:hover{
                                background: #038063;
                            }
                        </style>
                        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
                        <p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>
                        <p><strong>Usuario: </strong>'.$username.'</p>
                        <p><strong>Contraseña: </strong>'.$clave.'</p>
                        <p style="text-align: justify;">
                            Estimado Kmiamigo, tu perfil ha sido creado con éxito.  El mismo permanecerá inactivo en la página hasta que completes los siguientes pasos listados abajo
                        </p>
                        <p style="text-align: justify;">
                            "Dichos pasos han sido diseñados para cumplir con un estricto perfil de seguridad, que garantice que cualquier persona que se convierta en Cuidador asociado Kmimos presente un perfil apto para cuidar y apapachar a nuestros peludos amigos"
                        </p>
                        <p style="text-align: justify;">
                            <strong>Siguientes Pasos para activar tu perfil</strong>
                        </p>
                        <p style="text-align: justify;">
                            <ul>
                                <li style="text-align: justify;">Compártenos por Mensaje Directo a nuestro Facebook @Kmimosmx tu nombre y apellido completo, email, teléfono de casa y celular</li>
                                <li style="text-align: justify;">Una vez que nos envíes dichos datos, en menos de 24 horas recibirás en el correo que registraste las Pruebas Psicométricas y Pruebas de Conceptos Veterinarios básicos.  Por favor respóndelas, y nos llegará a nosotros un mensaje de completadas.</li>
                                <li style="text-align: justify;">En menos de 24 horas después de completadas las pruebas recibirás un correo por parte de Certificación Kmimos, notificando tus resultados.  NO TE OLVIDES DE CHECAR SIEMPRE LA BANDEJA DE ENTRADA O EL CORREO NO DESEADO, ya que a veces llegan allí los correos.</li>
                                <li style="text-align: justify;">En caso de haber aprobado, lee el archivo adjunto al correo que te muestra las políticas operativas.</li>
                                <li style="text-align: justify;">Por último, recibirás una llamada para entrevista telefónica y notificación para la auditoría a tu hogar.</li>
                            </ul>
                        </p>
                    ';

/*                $mensaje_web = '
                        <style>
                            p{
                                text-align: justify;
                            }
                            a:hover{
                                background: #038063;
                            }
                            #vlz_cargando {
                                max-height: 100% !important;
                            }
                            .vlz_modal_ventana {
                                width:auto;
                                max-width: 80%;
                            }
                        </style>
                        <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
                        <p>Hola <strong>'.$nombres.' '.$apellidos.'</strong>,</p>
                        <p style="text-align: justify;">
                           Estimado Kmiamigo, tu perfil ha sido creado con éxito. El mismo permanecerá INACTIVO en la página hasta que completes tu proceso de certificación que consta:
                        </p>
                        <p style="text-align: justify;  text-align: left;">
                            <ul style="text-align: justify;  text-align: left;">
                                <li>Pruebas de conocimiento veterinario.</li>
                                <li>Pruebas Psicométricas.</li>
                                <li>Documentación (IFE, Comprobante de domicilio y Datos Bancarios.</li>
                             </ul>
                        </p>

                        <p style="text-align: justify;">
                            Dicho proceso ha sido diseñado por expertos veterinario para escoger a las personas más adecuadas para recibir, cuidar y apapachar a nuestros peludos amigos.
                        </p>

                        <p style="text-align: justify;">
                            Ahora serás dirigido a hacia el paso 1, <strong>PRUEBAS DE CONOCIMIENTO VETERINARIO</strong>.
                        </p>
                        <p style="text-align: justify;">
                            Guarda el siguiente link, ahí puedes continuar con las pruebas en caso de no terminarlas por algún imprevisto y/o para cargar documento.
                        </p>
                        <p style="text-align: justify;">
                            <strong>Link para continuar es:</strong> http://kmimos.ilernus.com
                        </p>
                        <p style="text-align: justify; color: #f00;">
                            <strong>INGRESA CON EL NOMBRE DE USUARIO Y CONTRASEÑA:</strong>
                        </p>
                        <p style="text-align: justify;">
                            <ul style="text-align: justify;  text-align: left;">
                                <li><strong>Usuario: </strong> '.$username.'</li>
                                <li><strong>Contraseña:</strong> '.$clave.'</li>
                            </ul>
                        </p>
                        <p style="text-align: justify;">
                            ¡EXITO!
                        </p>
                        <p style="text-align: center;">
                            <a class="theme_button"
                                href="http://kmimos.ilernus.com"
                                style="
                                    padding: 10px;
                                    background: #59c9a8;
                                    color: #fff;
                                    font-weight: 400;
                                    font-size: 17px;
                                    font-family: Roboto;
                                    border-radius: 3px;
                                    border: solid 1px #1f906e;
                                    display: block;
                                    max-width: 300px;
                                    margin: 0px auto;
                                    text-align: center;
                                    text-decoration: none;
                                "
                            >Continuar</a>
                        </p>
                    ';*/


                    $mail_msg = kmimos_get_email_html("Gracias por registrarte como cuidador.", $mensaje_mail, 'Registro de Nuevo Cuidador.', true, true);
                    wp_mail( $email, "Kmimos México – Gracias por registrarte como cuidador! Kmimos la NUEVA forma de cuidar a tu perro!", $mail_msg);

                    $error = array(
                        "error"         => "NO",
                        "msg"           => $mensaje_web
                    );
                    echo "(".json_encode( $error ).")";

            }else{
                $error = array(
                    "error" => "SI",
                    "msg"   => "No se ha podido completar el registro."
                );
                echo "(".json_encode( $error ).")";
            }
        }
        
	}

?>