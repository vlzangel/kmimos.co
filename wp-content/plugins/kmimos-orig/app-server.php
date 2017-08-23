<?php

if($_GET['action']=='test') die('Bienvenido al servidor de la aplicación de Kmimos');

$ip = $_SERVER['REMOTE_ADDR'];

$action = $_POST['action'];

switch($action){

case 'init':

	$response = json_encode(

        array(

            'base_url'=>'http'.(($_SERVER['HTTPS'])?'s':'').'://'.$_SERVER['SERVER_NAME'], 

            'base_image'=>'/wp-content/uploads/', 

            'no_avatar'=>'/wp-content/plugins/kmimos/assets/images/avatar.png', 

            'no_image'=>'/wp-content/plugins/kmimos/assets/images/no_image.jpg'

        )

    );

	break;

case 'get-rating-status':

    $reserva = $_POST['booking'];

    $html = '';

    include_once('../../../wp-config.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }



    $sql = "SELECT meta_value AS ID FROM wp_postmeta WHERE meta_key='customer_comment' AND post_id=".$reserva;



    if($stmt = $conn->query($sql)){

        $comentario = $stmt->fetch_object();

        if($comentario->ID!=''){ 

            $html = '<strong><i class="pfadmicon-glyph-95"></i>Valorado</strong>';

        }

        else {

            $html = '<a href="'.get_home_url().'/valorar-cuidador/?id='.$reserva.'" class="button cancel">Valorar</a>';

        }

    }



	$conn->close();

    $response = $html;

	break;

case 'set-user':

    include_once('../../../wp-config.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }



    $resp = array();

    $user = $conn->real_escape_string($_POST['user']);

    $id = $conn->real_escape_string($_POST['id']);

    $token = $conn->real_escape_string($_POST['token']);

    if($token==md5($user.'@kmimos:'.$id)){

        $metas =array(

            'fname'=>'first_name',

            'lname'=>'last_name',

            'nick'=>'nickname',

            'description'=>'description',

            'phone'=>'user_phone',

            'mobile'=>'user_mobile',

            'favorites'=>'user_favorites',

            'pets'=>'user_pets',

            'address'=>'user_address',

            'country'=>'user_country',

            'tax_id'=>'user_vatnumber',

            'twitter'=>'user_twitter',

            'facebook'=>'user_facebook',

            'gplus'=>'user_googleplus',

            'linkedin'=>'user_linkedin',

            'bill_fname'=>'billing_first_name',

            'bill_lname'=>'billing_last_name',

            'bill_company'=>'billing_company',

            'bill_address'=>'billing_address_1',

            'bill_address2'=>'billing_address_2',

            'bill_city'=>'billing_city',

            'bill_postcode'=>'billing_postcode',

            'bill_country'=>'billing_country',

            'bill_state'=>'billing_state',

            'bill_phone'=>'billing_phone',

            'bill_email'=>'billing_email',

            'ship_fname'=>'shipping_first_name',

            'ship_lname'=>'shipping_last_name',

            'ship_company'=>'shipping_company',

            'ship_address'=>'shipping_address_1',

            'ship_address2'=>'shipping_address_2',

            'ship_city'=>'shipping_city',

            'ship_postcode'=>'shipping_postcode',

            'ship_country'=>'shipping_country',

            'ship_state'=>'shipping_state',

            'fails'=>'fails_login'

        );

        $sql  = "UPDATE wp_usermeta ";

        $sql .= "SET meta_value = CASE meta_key ";

        foreach($metas as $key=>$value){

            if(isset($_POST[$key])){

                $sql .= "WHEN '".$value."' THEN '".utf8_decode($_POST[$key])."' ";

            }

        }

        $ahora = strtotime("now");

        $sql .= "WHEN '_yoast_wpseo_profile_updated' THEN '".$ahora."' ";

        $sql .= "ELSE meta_value END ";

        $sql .= "WHERE user_id =".$id;

        if($conn->query($sql) === true){

            $resp['set'] = true;

            $resp['updated'] = date("Y-m-d H:i:s", $ahora);

            $resp['display']= 'Actualización de datos satisfacctoria';                        

        }

        else {

            $resp['set'] = false;

            $resp['id'] = $id;

            $resp['display']= 'Error al intentar Actualizar datos del usuario';

        }

    }

//    $resp['sql'] = $sql;

//    $resp['xsql'] = decodificar($sql);

	$response = json_encode($resp);

	$conn->close();

	break;

case 'add-pet':

    include_once('../../../wp-config.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }



    $resp = array();

    $user = $conn->real_escape_string($_POST['user']);

    $id = (isset($_POST['id']))? $conn->real_escape_string($_POST['id']):'';

    $token = $conn->real_escape_string($_POST['token']);

    if($token==md5($user.'@kmimos:'.$id)){

    }

//    $resp['sql'] = $sql;

	$response = json_encode($resp);

	$conn->close();

	break;

case 'get-pets':

    include_once('../../../wp-config.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }



    $metas =array(

        'name'=>'name_pet',

        'owner'=>'owner_pet',

        'breed'=>'breed_pet',

        'colors'=>'colors_pet',

        'birthdate'=>'birthdate_pet',

        'gender'=>'gender_pet',

    );



    $resp = array();

    if(isset($_POST['id'])){

        $id =  $conn->real_escape_string($_POST['id']);

        $sql = "SELECT p.ID, ";

        foreach($metas as $key=>$value){

            $sql .= "(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='".$value."') as ".$key.", ";

        }

        $sql .= "(SELECT guid FROM wp_posts WHERE post_author=owner AND post_type='attachment') as avatar ";

        $sql .= "FROM wp_posts AS p INNER JOIN wp_postmeta AS pm ON pm.post_id=p.ID ";

        $sql .= "WHERE p.post_status='publish' AND pm.meta_key = 'owner_pet' AND pm.meta_value = '".$id."'";

        $result = $conn->query($sql);

        while($pet = $result->fetch_object()){

            $pets = array();

            $pets['name'] = utf8_encode($pet->name);

            $pets['breed'] = utf8_encode($pet->breed);

            $pets['colors'] = utf8_encode($pet->colors);

            $pets['gender'] = $pet->gender;

            $fecha = time() - strtotime($pet->birthdate);

            $pets['months'] = floor((($fecha / 3600) / 24) / 30);

            $pets['avatar'] = ($pet->avatar!==null)? $pet->avatar: 'http'.(($_SERVER['HTTPS'])?'s':'').'://'.$_SERVER['SERVER_NAME'].'/wp-content/plugins/kmimos/assets/images/kmimos.png';

            $resp[$pet->ID]=$pets;

        }

        $result->close();

    }

//    $resp['sql'] = $sql;

	$response = json_encode($resp);

	$conn->close();

	break;

case 'set-pass':

    include_once('../../../wp-config.php');

    include_once('../../../wp-includes/class-phpass.php');

    include_once('../../../wp-includes/pluggable.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }



    $resp = array();

    $user = $conn->real_escape_string($_POST['user']);

    $id = (isset($_POST['id']))? $conn->real_escape_string($_POST['id']):'';

    $token = $conn->real_escape_string($_POST['token']);

    if($token==md5($user.'@kmimos:'.$id)){

        $pass = $conn->real_escape_string($_POST['pass']);

        $new = $conn->real_escape_string($_POST['new']);

        $sql = "SELECT u.ID, u.user_login, u.user_pass ";

        $sql .= "FROM wp_users AS u ";

        $sql .= "WHERE (u.user_login='".$user."' AND u.ID='".$id."')";

//        $resp['sql'] = $sql;

        if($stmt = $conn->query($sql)){

            $usuario = $stmt->fetch_object();

            if($id!='' && $id==$usuario->ID || $id==''){

                $hasher = new PasswordHash(8, TRUE);

                if($hasher->CheckPassword($pass, $usuario->user_pass)){       

                    $sql="UPDATE wp_users SET user_pass='".wp_hash_password($new)."' WHERE user_login='".$user."' AND ID=".$id;

                    $conn2 = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                    if($conn2->query($sql) === TRUE){

                        $resp['set'] = true;

                        $resp['display']= 'Cambio contraseña satisfacctorio';                        

                    }

                    else $resp['set'] = false;

                    $conn2->close();

                }

                else $resp['set'] = false;

            }

            else $resp['set'] = false;

            

//            $resp['sql'] = $sql;

            $stmt->close();

        }

        else {

            $resp['set'] = false;

        }

        if($resp['set'] == false){

            $resp['id'] = $id;

            $resp['display']= 'Error al intentar cambiar contraseña';

        }

    }

	$response = json_encode($resp);

	$conn->close();

	break;

case 'login-user':

    include_once('../../../wp-config.php');

    include_once('../../../wp-includes/class-phpass.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }



    $resp = array();

    $user = $conn->real_escape_string($_POST['user']);

//    $pass = $conn->real_escape_string($_POST['pass']);

    $pass = $conn->real_escape_string($_POST['psw']);

    $id = (isset($_POST['id']))? $conn->real_escape_string($_POST['id']):'';

    $token = $conn->real_escape_string($_POST['token']);

    if($token==md5($user.'@kmimos:'.$pass)){

        $metas =array(

            'fname'=>'first_name',

            'lname'=>'last_name',

            'nick'=>'nickname',

            'description'=>'description',

            'updated'=>'_yoast_wpseo_profile_updated',

            'phone'=>'user_phone',

            'mobile'=>'user_mobile',

            'favorites'=>'user_favorites',

            'pets'=>'user_pets',

            'address'=>'user_address',

            'country'=>'user_country',

            'tax_id'=>'user_vatnumber',

            'twitter'=>'user_twitter',

            'facebook'=>'user_facebook',

            'gplus'=>'user_googleplus',

            'linkedin'=>'user_linkedin',

            'bill_fname'=>'billing_first_name',

            'bill_lname'=>'billing_last_name',

            'bill_company'=>'billing_company',

            'bill_address'=>'billing_address_1',

            'bill_address2'=>'billing_address_2',

            'bill_city'=>'billing_city',

            'bill_postcode'=>'billing_postcode',

            'bill_country'=>'billing_country',

            'bill_state'=>'billing_state',

            'bill_phone'=>'billing_phone',

            'bill_email'=>'billing_email',

            'ship_fname'=>'shipping_first_name',

            'ship_lname'=>'shipping_last_name',

            'ship_company'=>'shipping_company',

            'ship_address'=>'shipping_address_1',

            'ship_address2'=>'shipping_address_2',

            'ship_city'=>'shipping_city',

            'ship_postcode'=>'shipping_postcode',

            'ship_country'=>'shipping_country',

            'ship_state'=>'shipping_state',

            'fails'=>'fails_login'

        );



        $sql = "SELECT u.ID, u.user_login, u.user_email, u.user_pass, u.user_status, u.display_name, ";

        foreach($metas as $key=>$value){

            $sql .= "(SELECT meta_value FROM wp_usermeta WHERE user_id=u.ID AND meta_key='".$value."') as ".$key.", ";

        }

        $sql .= "(SELECT GROUP_CONCAT(post_modified SEPARATOR ',') FROM wp_posts ";

        $sql .= "WHERE FIND_IN_SET (ID, (SELECT GROUP_CONCAT(post_id SEPARATOR ',') ";

        $sql .= "FROM wp_postmeta WHERE meta_value=u.ID AND meta_key='owner_pet'))) as up_pets,  ";

        

        $sql .= "(SELECT GROUP_CONCAT(post_modified SEPARATOR ',') FROM wp_posts ";

        $sql .= "WHERE FIND_IN_SET (ID, (SELECT GROUP_CONCAT(post_id SEPARATOR ',') ";

        $sql .= "FROM wp_postmeta WHERE meta_value=u.ID AND meta_key='user_favorites'))) as up_favs ";



        $sql .= "FROM wp_users AS u ";

        $sql .= "WHERE (u.user_login='".$user."' OR u.user_email='".$user."')";

//        $resp['sql'] = $sql;

        if($stmt = $conn->query($sql)){

            $usuario = $stmt->fetch_object();

            if($id!='' && $id==$usuario->ID || $id==''){

                $hasher = new PasswordHash(8, TRUE);

                if($hasher->CheckPassword($pass, $usuario->user_pass)){       

                    $resp['login'] = true;

                    $resp['id'] = $usuario->ID;

                    $resp['display']= utf8_encode($usuario->display_name);

                    $resp['updated'] = date("Y-m-d H:i:s", $usuario->updated);

                    $resp['pets']= utf8_encode($usuario->pets);

                    $resp['up_pets']= ($usuario->up_pets!==null)?$usuario->up_pets:'';

                    $resp['favorites']= utf8_encode($usuario->favorites);

                    $resp['up_favs']= ($usuario->up_favs!==null)?$usuario->up_favs:'';

                    if($id!=''){

                        $resp['fname']= utf8_encode($usuario->fname);

                        $resp['lname']= utf8_encode($usuario->lname);

                        $resp['nick']= utf8_encode($usuario->nick);

                        $resp['description']= utf8_encode($usuario->description);

                        $resp['phone']= utf8_encode($usuario->phone);

                        $resp['mobile']= utf8_encode($usuario->mobile);

                        $resp['address']= utf8_encode($usuario->address);

                        $resp['country']= utf8_encode($usuario->country);

                        $resp['tax_id']= utf8_encode($usuario->tax_id);

                        $resp['twitter']= utf8_encode($usuario->twitter);

                        $resp['facebook']= utf8_encode($usuario->facebook);

                        $resp['gplus']= utf8_encode($usuario->gplus);

                        $resp['linkedin']= utf8_encode($usuario->linkedin);

                        $resp['bill_fname']= utf8_encode($usuario->bill_fname);

                        $resp['bill_lname']= utf8_encode($usuario->bill_lname);

                        $resp['bill_company']= utf8_encode($usuario->bill_company);

                        $resp['bill_address']= utf8_encode($usuario->bill_address);

                        $resp['bill_address2']= utf8_encode($usuario->bill_address2);

                        $resp['bill_city']= utf8_encode($usuario->bill_city);

                        $resp['bill_country']= utf8_encode($usuario->bill_country);

                        $resp['bill_state']= utf8_encode($usuario->bill_state);

                        $resp['bill_phone']= utf8_encode($usuario->bill_phone);

                        $resp['bill_email']= utf8_encode($usuario->bill_email);

                        $resp['ship_fname']= utf8_encode($usuario->ship_fname);

                        $resp['ship_lname']= utf8_encode($usuario->ship_lname);

                        $resp['ship_company']= utf8_encode($usuario->ship_company);

                        $resp['ship_address']= utf8_encode($usuario->ship_address);

                        $resp['ship_address2']= utf8_encode($usuario->ship_address2);

                        $resp['ship_city']= utf8_encode($usuario->ship_city);

                        $resp['ship_postcode']= utf8_encode($usuario->ship_postcode);

                        $resp['ship_country']= utf8_encode($usuario->ship_country);

                        $resp['ship_state']= utf8_encode($usuario->ship_state);

                    }

                    if($usuario->fails!==null && $usuario->fails!=''){

                        $sql="UPDATE wp_usermeta SET meta_value='' WHERE meta_key='fails_login' AND user_id=".$usuario->ID;

                        $conn2 = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                        $conn2->query($sql);

                        $conn2->close();

                    }

    //                $resp['sql'] = $sql;

                }

                else $resp['login'] = false;

            }

            else $resp['login'] = false;

            

            $stmt->close();

        }

        else {

            $resp['login'] = false;

        }

    }

    if($resp['login'] == false){

        $resp['id'] = $id;

        $resp['display']= 'Error al intentar validarse';

//        $resp['sql'] = $sql;

        $resp['fails'] = $usuario->fails;

        $now = date('Y-m-d H:i:s');

        if($usuario->fails===null){

            $sql="INSERT INTO wp_usermeta (user_id,meta_key,meta_value) VALUES (".$usuario->ID.",'fails_login','".$now."')";

        }

        else if($usuario->fails!=''){

            $sql="UPDATE wp_usermeta SET meta_value=CONCAT(meta_value,',','".$now."') WHERE meta_key='fails_login' AND user_id=".$usuario->ID;

        }

        else {

            $sql="UPDATE wp_usermeta SET meta_value='".$now."' WHERE meta_key='fails_login' AND user_id=".$usuario->ID;

        }

        $conn2 = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $conn2->query($sql);

        $conn2->close();

//                $resp['sql'] = $sql;

    }

	$response = json_encode($resp);

	$conn->close();

	break;

case 'get-pet':

    include_once('../../../wp-config.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



    $resp = array();

    $pet = $conn->real_escape_string($_POST['pet']);

    $token = $conn->real_escape_string($_POST['token']);

    if($token==md5($pet.'@kmimos:')){

        $metas =array(

            'name'=>'name_pet',

            'owner'=>'owner_pet',

            'size'=>'size_pet',

            'breed'=>'breed_pet',

            'colors'=>'colors_pet',

            'birthdate'=>'birthdate_pet',

            'gender'=>'gender_pet',

            'sterilized'=>'pet_sterilized',

            'sociable'=>'pet_sociable',

            'unsociable'=>'pet_unsociable',

            'aggressive_w_pets'=>'aggressive_with_pets',

            'aggressive_w_humans'=>'aggressive_with_humans',

            'about'=>'about_pet'

        );



//        $sql = "SELECT p.ID, (SELECT GROUP_CONCAT(SUBSTRING(guid,".$largo.") SEPARATOR ',') FROM wp_posts WHERE FIND_IN_SET ";

//        $sql .= "(ID, (SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='carousel_petsitter'))>0) AS galeria, ";

        $sql = "SELECT p.ID, p.post_modified, ";

        foreach($metas as $key=>$value){

            $sql .= "(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='".$value."') as ".$key.", ";

        }

        $sql .= "(SELECT guid FROM wp_posts WHERE post_author=owner AND post_type='attachment') as avatar ";

        $sql .= "FROM wp_posts AS p ";

        $sql .= "WHERE p.post_status='publish' AND p.post_type='pets' AND p.ID=".$pet;



        if($stmt = $conn->query($sql)){

            $mascota = $stmt->fetch_object();

            $resp['pet'] = true;

            $resp['name'] = utf8_encode($mascota->name);

            $resp['owner'] = $mascota->owner;

            $resp['size'] = $mascota->size;

            $resp['breed'] = utf8_encode($mascota->breed);

            $resp['colors'] = utf8_encode($mascota->colors);

            $resp['birthdate'] = $mascota->birthdate;

            $resp['gender'] = $mascota->gender;

            $resp['sterilized'] = $mascota->sterilized;

            $resp['sociable'] = $mascota->sociable;

            $resp['unsociable'] = $mascota->unsociable;

            $resp['aggressive_w_pets'] = $mascota->aggressive_w_pets;

            $resp['aggressive_w_humans'] = $mascota->aggressive_w_humans;

            $resp['about'] = utf8_encode($mascota->about);

            $resp['avatar'] = ($mascota->avatar!==null)? $mascota->avatar: 'http'.(($_SERVER['HTTPS'])?'s':'').'://'.$_SERVER['SERVER_NAME'].'/wp-content/plugins/kmimos/assets/images/kmimos.png';

            $resp['updated'] = $mascota->post_modified;

            $stmt->close();

        }

        else {

            $resp['pet'] = false;

//            $resp['sql'] = $sql;

            $resp['display']= 'Error mascota no existe';

        }

    }

	$response = json_encode($resp);

	$conn->close();

	break;

case 'check-login':

    include_once('../../../wp-config.php');

    include_once('../../../wp-load.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



    $resp = array();

	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }

/*    $login = $conn->real_escape_string($_POST['login']);

    $token = $conn->real_escape_string($_POST['token']);*/

    $login = $_POST['login'];

    $token = $_POST['token'];

/*    $resp['login']=trim($login);

    $resp['token']=$token;

    $resp['token1']=md5($login.'@kmimos:');*/

    if($token==md5($login.'@kmimos:') && $login!='admin'){

        $sql = "SELECT COUNT(*) as users ";

        $sql .= "FROM wp_users ";

        $sql .= "WHERE user_login='".$login."'";

        if($stmt = $conn->query($sql)){

            $usuario = $stmt->fetch_object();

            if($usuario->users>0){       

                $resp['login'] = true;

            }

            else {

                $resp['login'] = false;

            }

            $stmt->close();

        }

        else $resp['login'] = false;

    }

//    $resp['token']=md5($login.'@kmimos:');

	$response = json_encode($resp);

	$conn->close();

    break;

case 'check-email':

    include_once('../../../wp-config.php');

    include_once('../../../wp-load.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



    $resp = array();

	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }

//    $login = $conn->real_escape_string($_POST['login']);

//    $token = $conn->real_escape_string($_POST['token']);

    $email = $_POST['email'];

    $token = $_POST['token'];

    if($token==md5($email.'@kmimos:')){

        if(!is_email($email)){

            $resp['email'] = false;           

            $resp['display'] = 'dirección de email inválida';

        }

        else {

            $sql = "SELECT COUNT(*) as users ";

            $sql .= "FROM wp_users ";

            $sql .= "WHERE user_email='".$email."'";

            if($stmt = $conn->query($sql)){

                $usuario = $stmt->fetch_object();

                if($usuario->users>0){       

                    $resp['email'] = true;

                    $resp['display'] = 'email existente, intente recuperar su contraseña';

                }

                else {

                    $resp['email'] = false;

                    $resp['display'] = 'email disponible, puede usar esta dirección de correo';

                }

                $stmt->close();

            }

            else $resp['email'] = false;

        }

    }

//    $resp['sql']=$sql;

//    $resp['token']=md5($email.'@kmimos:');

    $response = json_encode($resp);

	$conn->close();

    break;

case 'signin-user':

    include_once('../../../wp-config.php');

    include_once('../../../wp-load.php');



    $login = $_POST['login'];

    $pass = $_POST['pass'];

    $email = $_POST['email'];

    $fname = $_POST['fname'];

    $lname = $_POST['lname'];

    $token = $_POST['token'];

//    $display = $_POST['display'];

    



    $resp = array();

    

    if(!is_email($email)){

        $resp['signin'] = false;

        $resp['display'] = 'La dirección de correo electrónico "'.$email.'" NO es válida.';

    }

    else if($login=='' && $pass==''){

        $resp['signin'] = false;

        $resp['display'] = 'Debe indicar un nombre de usuario y una contraseña.';

    }

//    else {

    else if($token==md5($login.'@kmimos:'.$pass) && $login!='admin'){

        $user_info = array(

            "user_pass"     => $pass,

            "user_login"    => $login,

            "user_nicename" => $login,

            "user_email"    => $email,

            "display_name"  => $fname." ".$lname,

            "first_name"    => $fname,

            "last_name"     => $lname,

        );

        $user_id = wp_insert_user( $user_info );

        if ( is_wp_error($user_id) ) {

            $resp['signin'] = false;

            $resp['error'] = $user_id->get_error_code();

            $resp['display'] = $user_id->get_error_message();

        } else {

            $resp['signin'] = true;

            $resp['id'] = $user_id;

            $resp['display'] = "Usuario creado satisfactoriamente con el id: ".$user_id;

            // Envía correo electrónico notificando la creación del usuario

            do_action( 'tml_new_user_registered', $user_id, null , 'both');

        }

    }

    $resp['token']=md5($login.'@kmimos:'.$pass);

	$response = json_encode($resp);

    break;

case 'get-petsitter':

    $id = $_POST['id'];

    $code = $_POST['code'];

    include_once('../../../wp-config.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }

        

    $valores = array();

    $valores['root'] = get_home_url().'/wp-content/uploads/';

    $largo = strlen($valores['root']);

    $galeria = array();

   

    $metas =array(

        'propiedad'=>'housing_petsitter',

        'verdes'=>'greens_petsitter',

        'patio'=>'yard_petsitter',

        'machos'=>'pets_males',

        'hembras'=>'pets_females',

        'esteriles'=>'pets_sterilized',

        'cachorros'=>'pets_puppies',

        'adultos'=>'pets_adults',

        'seniors'=>'pets_seniors',

        'sociable'=>'pets_sociable',

        'insociable'=>'pets_unsociable',

        'agresivam'=>'aggressive_with_pets',

        'agresivah'=>'aggressive_with_humans',

        'desde'=>'starting_petsitter',

        'youtube'=>'video_youtube'

    );



    $sql = "SELECT p.ID, (SELECT GROUP_CONCAT(SUBSTRING(guid,".$largo.") SEPARATOR ',') FROM wp_posts WHERE FIND_IN_SET ";

    $sql .= "(ID, (SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='carousel_petsitter'))>0) AS galeria, ";

    foreach($metas as $key=>$value){

        $sql .= "(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='".$value."') AS ".$key.", ";

    }

    $sql .= "p.post_content AS detalle ";

    $sql .= "FROM wp_posts AS p LEFT JOIN wp_postmeta AS c ON p.ID=c.post_id AND c.meta_key='code_petsitter' ";

    $sql .= "WHERE p.post_status='publish' AND p.post_type='petsitters' ";

    if(isset($_POST['code']) && $code !='' && !isset($_POST['id'])){

        $sql .= "AND c.meta_value=".$code;

    }

    elseif(isset($_POST['id']) && $id !='') {

        $sql .= "AND p.ID=".$id;

    }

    else {

        $response =  json_encode('ERROR: debe enviar el ID o el código del cuidador.');

        $conn->close();

        break;        

    }

//    $tipos = array('casa','departamento');

    $result = $conn->query($sql);

    while($cuidador = $result->fetch_object()){

        $valores['galeria'] = explode(',',$cuidador->galeria);

        $valores['detalle'] = utf8_encode($cuidador->detalle);

        $valores['propiedad'] = $cuidador->propiedad;

        $edades = array((int)$cuidador->cachorros, (int)$cuidador->adultos, (int)$cuidador->seniors);

        $valores['edades'] = $edades;

        $generos = array((int)$cuidador->machos, (int)$cuidador->hembras, (int)$cuidador->esteriles);

        $valores['generos'] = $generos;

        $acepta = array((int)$cuidador->sociable, (int)$cuidador->insociable, (int)$cuidador->agresivam, (int)$cuidador->agresivah);

        $valores['acepta'] = $acepta;

        $valores['desde'] = $cuidador->desde;

        if($cuidador->youtube !='') $valores['youtube'] = $cuidador->youtube;

        $valores['patio'] = (int)$cuidador->patio;

        $valores['verdes'] = (int)$cuidador->verdes;

    }



    $comentarios = array();

//  Busca los comentarios del cuidador

    $valores['comentarios'] = $comentarios;

    $response =  json_encode($valores);

//    $response =  json_encode($sql);

//	$result->close();

	$conn->close();

    break;

case 'get-petsitters':

    $lat = (isset($_POST['lat']))? $_POST['lat']:19.4141721;

    $lng = (isset($_POST['lng']))? $_POST['lng']:-99.1431542;

    $desde = (isset($_POST['from']))? $_POST['from']:0;

    $largo = (isset($_POST['size']))? $_POST['size']:20;



    if(isset($_POST['dist'])) $distancia = $_POST['dist'];

    if(isset($_POST['order']))$orden = $_POST['order'];

/*

        distance_asc

        distance_desc

        price_asc

        price_desc

        experience_asc

        experience_desc

        name_asc

        name_desc

        rate_asc

        rate_desc

*/

        

//    $ids = $_POST['ids'];

        

    if($lat>0 && $lat<14 && $lng>-75 && $lng<-58){

        $lat = 19.4141721;

        $lng = -99.1431542;

    }



    $servicios = $_POST['serv']; // Servicios seleccionados

    if(is_array($servicios)) $servicios= "'".implode("','",$servicios)."'";

    $ubicacion = $_POST['loc']; // Ubicaciones seleccionadas

    if(is_array($ubicacion)) $ubicacion=  "'".implode("','",$ubicacion)."'";

    if(isset($_POST['price_from']) && isset($_POST['price_to']) && $_POST['price_from']!='' && $_POST['price_to']!='') { // Rango de precios seleccionado

        $rango_precio = $_POST['price_from'].','. $_POST['price_to'];

    }

    else $rango_precio = '';

    if(isset($_POST['exp_from']) && isset($_POST['exp_to']) && $_POST['exp_from']!='' && $_POST['exp_to']!='') { // Rango de experiencia seleccionado

        $rango_exper = $_POST['exp_from'].','. $_POST['exp_to'];

    }

    else $rango_exper = '';

    if(isset($_POST['rate_from']) && isset($_POST['rate_to']) && $_POST['rate_from']!='' && $_POST['rate_to']!='') { // Rango de valoración del cuidador seleccionado

        $rango_valor = $_POST['rate_from'].','. $_POST['rate_to'];

    }

    else $rango_valor = '';

    if(isset($_POST['rank_from']) && isset($_POST['rank_to']) && $_POST['rank_from']!='' && $_POST['rank_to']!='') { // Ranking del cuidador seleccionado

        $rango_rank = $_POST['rank_from'].','. $_POST['rank_to'];

    }

    else $rango_rank = '';

    if(isset($_POST['acepta']) && $_POST['acepta']!=''){

        // Tamaños de mascotas aceptadas por el cuidador

        $acepta = (is_array($_POST['acepta']))? implode("','",$_POST['acepta']):$_POST['acepta'];    

    }

    else $acepta = '';

    if(isset($_POST['tiene']) && $_POST['tiene']!=''){

        // Tamaños de mascotas que tiene el cuidador

        $tiene = (is_array($_POST['tiene']))? implode("','",$_POST['tiene']):$_POST['tiene'];    

    }

    else $tiene = '';    

    if(isset($_POST['conductas']) && $_POST['conductas']!=''){

        // Conductas de mascotas aceptadas por el cuidador

        $conductas = (is_array($_POST['conductas']))? implode("','",$_POST['conductas']):$_POST['conductas'];    

    }

    else $conductas = ''; 



    $sp = "CALL listOfPetsitters('".$servicios."','".$ubicacion."','".$rango_precio."','".$rango_exper."','".$rango_valor."','". $rango_rank."','".$acepta."','".$tiene."','".$conductas."','".$orden."','".$lat."','".$lng."','".$distancia. "',0,@total,@ids)";



    $servicios= explode(',',$servicios);

    if(in_array("hospedaje", $servicios)) $servicio="hospedaje";

    elseif(in_array("guarderia", $servicios)) $servicio="guarderia";

    elseif(in_array("paseo", $servicios)) $servicio="paseo";

    elseif(in_array("adiestramiento", $servicios)) $servicio="adiestramiento";

    elseif(in_array("peluqueria", $servicios)) $servicio="peluqueria";

    elseif(in_array("bano", $servicios)) $servicio="bano";

    elseif(in_array("transporte", $servicios)) $servicio="transporte";

    elseif(in_array("transporte2", $servicios)) $servicio="transporte2";

    elseif(in_array("veterinario", $servicios)) $servicio="veterinario";

    elseif(in_array("limpieza", $servicios)) $servicio="limpieza";

    elseif(in_array("acupuntura", $servicios)) $servicio="acupuntura";

    else $servicio="precio";

/*        

    $response =  json_encode($sp);

	break;

*/

    include_once('../../../wp-config.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }



    $result = $conn->query($sp);

    $cuidador = $result->fetch_object();

    $total = $cuidador->total;

    $ids = $cuidador->ids;

    $result->close();

    $conn->close();

        

//print_r($result);

        

    // Selecciona la cantidad de cuidadores en el rango deseado

    $pagina =implode(',',array_slice(explode(',',$ids), $desde, $largo));



    if(in_array($servicio,array('visita_veterinario','limpieza_dental','acupuntura'))) $precio = $servicio;

    else $precio = $servicio.'_desde';

    $metas =array(

        'user_id'=>'user_petsitter',

        'email'=>'email_petsitter',

        'location'=>'location_petsitter',

        'sizes'=>'sizes_pets',

        'featured'=>'featured_petsitter',

        'price'=>$precio,

        'votes'=>'votes_petsitter',

        'rating'=>'rating_petsitter',

        'express'=>'express_booking',

        'hospedaje'=>'hospedaje_desde',

        'guarderia'=>'guarderia_desde',

        'peluqueria'=>'peluqueria_desde',

        'corte_adicional'=>'corte_adicional',

        'bano_adicional'=>'bano_adicional',

        'bano'=>'bano_desde',

        'paseo'=>'paseo_desde',

        'simple'=>'simple_desde',

        'doble'=>'doble_desde',

        'veterinario'=>'visita_veterinario',

        'limpieza'=>'limpieza_dental',

        'adiestramiento'=>'adiestramiento_desde',

        'acupuntura'=>'precio_acupuntura'

    );

    $sql = "SELECT geodistanceKm(".$lat.",".$lng.",lat.meta_value,lng.meta_value) AS distance, p.ID, ";

    $sql .= "p.post_title as name, p.post_modified, ";

    foreach($metas as $key=>$value){

        $sql .= "(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='".$value."') AS ".$key.", ";

    }

    $sql .= "(SELECT guid FROM wp_posts WHERE post_title=CONCAT(LPAD((SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='code_petsitter'),4,'0'),'_000p') AND post_type='attachment') AS img,lat.meta_value AS lat,lng.meta_value AS lng, ";

    $sql .= "(SELECT guid FROM wp_posts WHERE post_author=user_id AND post_type='attachment') as avatar ";

    $sql .= "FROM wp_posts AS p ";

    $sql .= "LEFT JOIN wp_postmeta AS lat ON lat.post_id=p.ID ";

    $sql .= "LEFT JOIN wp_postmeta AS lng ON lng.post_id=p.ID ";

    $sql .= "LEFT JOIN wp_postmeta AS ex ON ex.post_id=p.ID ";

    $sql .= "WHERE p.post_status='publish' AND p.post_type='petsitters' AND p.ID IN (".$pagina.") ";

    $sql .= "AND lat.meta_value <>'' AND lat.meta_key='latitude_petsitter' ";

    $sql .= "AND lng.meta_value <>'' AND lng.meta_key='longitude_petsitter' ";

    $sql .= "AND ex.meta_value <>'' AND ex.meta_key='starting_petsitter' ";

    if(isset($distancia) && $distancia>0) $sql .= "HAVING distance <= ".$distancia." ";

    switch($orden){

        case 'distance_asc':

            $sql .= "ORDER BY distance ASC, CONVERT(price, UNSIGNED) ASC ";

            break;

        case 'distance_desc':

            $sql .= "ORDER BY distance DESC, ORDER BY CONVERT(price, UNSIGNED) DESC ";

            break;

        case 'price_asc':

            $sql .= "ORDER BY CONVERT(price, UNSIGNED) ASC, distance ASC ";

            break;

        case 'price_desc':

            $sql .= "ORDER BY CONVERT(price, UNSIGNED) DESC, distance DESC ";

            break;

        case 'experience_asc':

            $sql .= "ORDER BY (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ex.meta_value)), '%Y')+0) ASC, distance ASC ";

            break;

        case 'experience_desc':

            $sql .= "ORDER BY (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ex.meta_value)), '%Y')+0) DESC, distance DESC ";

            break;

        case 'name_asc':

            $sql .= "ORDER BY name ASC, distance ASC ";

            break;

        case 'name_desc':

            $sql .= "ORDER BY name DESC, distance DESC ";

            break;

/*        case 'rate_asc':

            break;

        case 'rate_desc':

            break;*/

        default:

            $sql .= "ORDER BY position ASC ";

            break;            

    }

//    $sql .= "LIMIT ".$largo." OFFSET ".$desde." ";

    $sql .= "LIMIT ".$largo." ";

/*

    $response =  json_encode($sql);

    break;        

*/    

    $listado = array();

    if($desde == '0'){

        $listado[0] = array(

            'total'=>$total, 

            'latitud'=>$lat, 

            'longitud'=>$lng,

/*            'indice'=>$pagina,

            'sp'=>$sp,

            'sql'=>$sql,*/

            'precios'=>$servicio

        );

    }

    if($pagina==''){

        $response =  json_encode($listado);

        break;        

    }

	$conn2 = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if($result2 = $conn2->query($sql) or die('ERROR: '.$sql)){ 

        $pointer =1;

        while($cuidador = $result2->fetch_array()){

            $cuidador_id = $cuidador['ID'];

            $valores = array();

            $valores['ID'] = $cuidador_id;

            $valores['name'] = utf8_encode($cuidador['name']);

//            $valores['email'] = $cuidador['email'];

            $valores['last'] = $cuidador['post_modified'];

            $valores['lat'] = $cuidador['lat'];

            $valores['lng'] = $cuidador['lng'];

            $valores['distance'] = $cuidador['distance'];

            $valores['location'] = $cuidador['location'];

//                $valores['capacity'] = $cuidador['capacity'];

//                $valores['pets'] = $cuidador['pets'];

            $valores['sizes'] = array_by_size(unserialize($cuidador['sizes']));

//                $valores['img'] = substr_replace($cuidador['img'],"_",-4,0);

            if(isset($cuidador['img'])){

                $imagen = explode('/uploads/',$cuidador['img']);

                $valores['img'] = $imagen[1];

            }

            if($cuidador->user_id>0) {

                 $avatar=($cuidador['avatar']=='')?get_avatar_url($cuidador['email'],array("size"=>80)): $cuidador['avatar'];

            }

            else $avatar=get_avatar_url($cuidador['email'],array("size"=>80));

            if($avatar!='') $valores['avatar']=$avatar;

            $valores['featured'] = $cuidador['featured'];

            if($cuidador->express!='')$valores['express'] = $cuidador['express'];

            $valores['price'] = $cuidador['price'];

//                $valores['currency'] = $currency;

            $votes = $cuidador['votes'];

            if($votes=='') $votes = 0;

            $valores['votes']=$votes;

            if($votes>0) $valores['rating'] = $cuidador['rating'];

/*            

            $valores['servicios']['hospedaje'] = array_by_size(unserialize($cuidador['hospedaje']));

            $guarderia = unserialize($cuidador['guarderia']);

            if(count($guarderia)>0) $valores['servicios']['guarderia'] = array_by_size($guarderia);

            if(count(unserialize($cuidador['peluqueria'])[0])>0) $valores['servicios']['peluqueria'] = array_by_size(unserialize($cuidador['peluqueria']));

            if(count(unserialize($cuidador['bano'])[0])>0) $valores['servicios']['bano'] = array_by_size(unserialize($cuidador['bano']));

            if(count(unserialize($cuidador['paseo'])[0])>0) $valores['servicios']['paseo'] = array_by_size(unserialize($cuidador['paseo']));

            $transporte = array();

            if(count(unserialize($cuidador['simple'])[0])>0) $transporte['simple']= array_by_size(unserialize($cuidador['simple']), array('short','med','long'));

            if(count(unserialize($cuidador['doble'])[0])>0) $transporte['doble']= array_by_size(unserialize($cuidador['doble']), array('short','med','long'));

            if(count(unserialize($cuidador['simple'])[0])>0 || count(unserialize($cuidador['doble'])[0])>0)$valores['servicios']['transporte'] = $transporte;

            if($cuidador['veterinario']!='') $valores['servicios']['veterinario'] = $cuidador['veterinario'];

            if($cuidador['limpieza']!='') $valores['servicios']['limpieza'] = $cuidador['limpieza'];

            if(count(unserialize($cuidador['adiestramiento'])[0])>0) $valores['servicios']['adiestramiento'] = array_by_size(unserialize($cuidador['adiestramiento']), array('basic','medium','advanced'));

            if($cuidador['acupuntura']!='') $valores['servicios']['acupuntura'] = $cuidador['acupuntura'];

*/

            $flags=0;

/*            if(count(unserialize($cuidador['hospedaje'])[0])>0)$flags = $flag+1;

            if(count(unserialize($cuidador['guarderia'])[0])>0)$flags = $flag+2;

            if(count(unserialize($cuidador['paseo'])[0])>0)$flags = $flag+4;

            if(count(unserialize($cuidador['adiestramiento'])[0])>0)$flags = $flag+8;

            if(count(unserialize($cuidador['peluqueria'])[0])>0)$flags = $flag+16;

            if(count(unserialize($cuidador['bano'])[0])>0)$flags = $flag+32;

            if(count(unserialize($cuidador['simple'])[0])>0)$flags = $flag+64;

            if(count(unserialize($cuidador['doble'])[0])>0)$flags = $flag+128;*/

            if(isset($cuidador['hospedaje']) && $cuidador['hospedaje']<>'')$flags = $flags+1;

            if(isset($cuidador['guarderia']) && $cuidador['guarderia']<>'')$flags = $flags+2;

            if(isset($cuidador['paseo']) && $cuidador['paseo']<>'')$flags = $flags+4;

            if(isset($cuidador['adiestramiento']) && $cuidador['adiestramiento']<>'')$flags = $flags+8;

            if(isset($cuidador['peluqueria']) && $cuidador['peluqueria']<>'' || isset($cuidador['corte_adicional']) && $cuidador['corte_adicional']<>'') $flags = $flags+16;

            if(isset($cuidador['bano']) && $cuidador['bano']<>'' || isset($cuidador['bano_adicional']) && $cuidador['bano_adicional']<>'') $flags = $flags+32;

            if(isset($cuidador['simple']) && $cuidador['simple']<>'') $flags = $flags+64;

            if(isset($cuidador['doble']) && $cuidador['doble']<>'') $flags = $flags+128;

            if(isset($cuidador['veterinario']) && $cuidador['veterinario']<>'') $flags = $flags+256;

            if(isset($cuidador['limpieza']) && $cuidador['limpieza']<>'')$flags = $flags+512;

            if(isset($cuidador['acupuntura']) && $cuidador['acupuntura']<>'')$flags = $flags+1024;

            if(isset($cuidador['express']) && $cuidador['express']==1)$flags = $flags+2048;

            $valores['servicios']= $flags;

            $listado[$pointer++]=$valores;

        }



    	$result2->close();

    }

//    array_multisort($pagina, SORT_ASC, $listado);

//    $response =  json_encode($cuidadores);

    $response =  json_encode($listado);

//    $response =  json_encode($sql);

	$conn2->close();

	break;

case 'get-level2':

case 'get-location':

case 'get-location-by-name':

case 'get-locations':

	$location = $_POST['location'];

	$name = $_POST['name'];



	if($location=='ve'){

		$estados = array(

			'AM'=>'Amazonas','AN'=>'Anzoátegui','AP'=>'Apure','AR'=>'Aragua','BA'=>'Barinas','BO'=>'Bolívar',

			'CA'=>'Carabobo','CO'=>'Cojedes','DA'=>'Delta Amacuro','DC'=>'Distrito Capital','FA'=>'Falcón',

			'GU'=>'Guárico','LA'=>'Lara','ME'=>'Mérida','MI'=>'Miranda','MO'=>'Monagas','NE'=>'Nueva Esparta',

			'PO'=>'Portuguesa','SU'=>'Sucre','TA'=>'Táchira','TR'=>'Trujillo','VA'=>'Vargas','YA'=>'Yaracuy',

			'ZU'=>'Zulia'

		);

		$response = (json_encode($estados));

        break;

	}



	include_once('../../../wp-config.php');



	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



	if ($conn->connect_error) {

        $response =('No pudo conectarse al servidor: ' . $conn->connect_error);

        break;

    }



	$where = '';

	$locations = array();

	switch(strlen($location)){

		case 0:	// Retorna lista de paises

			$where = "LENGTH(t.slug) = 2 AND tt.taxonomy='pointfinderlocations_top'";

			$largo = 2;

			break;

		case 2: // Retorna los estados del país seleccionado

			$where = "t.slug LIKE '".$location."-__' AND NOT (SUBSTRING(t.slug,7,3) REGEXP '[0-9]+')";

//			$where = "t.slug LIKE '".$location."-__-%' AND NOT (SUBSTRING(t.slug,7,3) REGEXP '[0-9]+')";

			$where .= " AND tt.taxonomy='pointfinderlocations'";

			$largo = 5;

			break;

		case 5: // Retorna los municipios del estado seleccionado

			$where = "slug LIKE '".$location."-___' AND (SUBSTRING(slug,7,3) REGEXP '[0-9]+')";

//			$where = "slug LIKE '".$location."-___-%' AND (SUBSTRING(slug,7,3) REGEXP '[0-9]+')";

			$where .= " AND tt.taxonomy='pointfinderlocations'";

			$largo = 9;

			break;

		case 9: // Retorna la latidud y la longitud del municipio seleccionado

            $sql = "SELECT latitude, longitude FROM wp_locations WHERE code='".$location."'";

            $result = $conn->query($sql);

            $row = $result->fetch_array(MYSQLI_NUM);

            $locations['lat'] = $row[0];

            $locations['lng'] = $row[1];

            break;

	}

    if(strlen($location)<9){

        if($name !='') $where .= " AND t.name LIKE '%".$name."%'";

        if($where != ''){

            $sql = "SELECT SUBSTRING(t.slug,1,$largo) AS codigo, t.name ";

            $sql .= "FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id=tt.term_id ";

            $sql .= "WHERE ".$where." ORDER BY t.name";

            $result = $conn->query($sql);

            $count = $result->num_rows;

            $accion = $_POST['action'];

    //        echo json_encode($accion);

            if ($count > 0) {

                for($i=0;$i<$count; $i++){

                    $row = $result->fetch_array(MYSQLI_NUM);

                    $locations[$row[0]] = utf8_encode($row[1]);

                    if ($accion=='get-locations'){

    /*                    $sql = "SELECT SUBSTRING(t.slug,1,9) AS codigo, t.name ";

                        $sql .= "FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id=tt.term_id ";

                        $sql .= "WHERE slug LIKE '".$row[0]."-___-%' AND (SUBSTRING(slug,7,3) REGEXP '[0-9]+')";

                        $sql .= " AND tt.taxonomy='pointfinderlocations' ORDER BY t.name";

      */                  

                        $sql = "SELECT SUBSTRING(t.slug,1,9) AS codigo, t.name ";

                        $sql .= "FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id=tt.term_id ";

                        $sql .= "WHERE slug LIKE '".$row[0]."-___' AND (SUBSTRING(slug,7,3) REGEXP '[0-9]+')";

                        $sql .= " AND tt.taxonomy='pointfinderlocations' ORDER BY t.name";

                        $result2 = $conn->query($sql);

                        $count2 = $result2->num_rows;

                        if ($count2 > 0) {

                            for($j=0;$j<$count2; $j++){

                                $row2 = $result2->fetch_array(MYSQLI_NUM);

                                $locations[$row2[0]] = utf8_encode($row2[1]);

                            }

                        }

                    }

                }

            }

        }

	}

/*  if(strlen($location)==5)$response = json_encode($sql);

    else $response = json_encode($locations);*/

    $response = json_encode($locations);

    if ($_POST['action']=='get-locations') {

        $result2->close();

    }

	$result->close();

	$conn->close();

/* Estados US 

    $estados['us'] = array( "AL"=>"Alabama", "AK"=>"Alaska", "AZ"=>"Arizona", "AR"=>"Arkansas", "CA"=>"California", "CO"=>"Colorado", "CT"=>"Connecticut", "DE"=>"Delaware", "FL"=>"Florida", "GA"=>"Georgia", "HI"=>"Hawaii", "ID"=>"Idaho", "IL"=>"Illinois", "IN"=>"Indiana", "IA"=>"Iowa", "KS"=>"Kansas", "KY"=>"Kentucky", "LA"=>"Louisiana", "ME"=>"Maine", "MD"=>"Maryland", "MA"=>"Massachusetts", MI=>"Michigan", "MN"=>"Minnesota", "MS"=>"Mississippi", "MO"=>"Missouri", "MT"=>"Montana", "NE"=>"Nebraska", "NV"=>"Nevada", "NH"=>"New Hampshire", "NJ"=>"New Jersey", "NM"=>"New Mexico", "NY"=>"New York", "NC"=>"North Carolina", "ND"=>"North Dakota", "OH"=>"Ohio", "OK"=>"Oklahoma", "OR"=>"Oregon", "PA"=>"Pennsylvania", "RI"=>"Rhode Island", "SC"=>"South Carolina", "SD"=>"South Dakota", "TN"=>"Tennessee", "TX"=>"Texas", "UT"=>"Utah", "VT"=>"Vermont", "VA"=>"Virginia", "WA"=>"Washington", "WV"=>"West Virginia", "WI"=>"Wisconsin", "WY"=>"Wyoming" );

/* Estados MX 

    $estados['mx'] = array( 1=>"Aguascalientes", 2=>"Baja California", 3=>"Baja California Sur", 4=>"Campeche", 5=>"Coahuila de Zaragoza", 6=>"Colima", 7=>"Chiapas", 8=>"Chihuahua", 9=>"Distrito Federal", 10=>"Durango", 11=> "Guanajuato", 12=>"Guerrero", 13=>"Hidalgo", 14=>"Jalisco", 15=>"México", 16=>"Michoacán de Ocampo", 17=>"Morelos", 18=>"Nayarit", 19=>"Nuevo León", 20=>"Oaxaca", 21=>"Puebla", 22=>"Querétaro", 23=>"Quintana Roo", 24=>"San Luis Potosí", 25=>"Sinaloa", 26=>"Sonora", 27=>"Tabasco", 28=>"Tamaulipas", 29=>"Tlaxcala", 30=>"Veracruz de Ignacio de la Llave", 31=>"Yucatán", 32=>"Zacatecas" );

	echo json_encode($estados[$pais]);*/

	break;

/*



	$municipios = array('1' => 'Municipio 1', '2' => 'Municipio 2');

	echo json_encode($municipios);

	break;*/

}

echo $response;

if($_POST['action']=='get-locations') $response = '-- TODOS LOS MUNICIPIOS DE '.$_POST['location'].' --';

$post='';

foreach($_POST as $key=>$value){

    $post .= $key.'='.$value.', ';

}

write_log($_POST['action'].': '.substr($post,0,-2).'::'.$response, 'Debug');



function write_log($cadena,$tipo)

{

    date_default_timezone_set('America/Caracas');

    $arch = fopen(realpath( '.' )."/logs/app_".date("Y-m-d").".txt", "a+"); 



	fwrite($arch, "[".date("Y-m-d H:i:s.u")." ".$_SERVER['REMOTE_ADDR']." ".

                   $_SERVER['HTTP_X_FORWARDED_FOR']." - $tipo ] ".$cadena."\n");

	fclose($arch);

}

function array_by_size($meta, $variable=''){

    $variable = ($variable=='')? array(0=>'peq',1=>'med',2=>'gde',3=>'gte'):$variable;

    $values = (is_array($meta))? $meta: explode('","',substr($meta,2,-2));

    $result = array();

/*    $prev='';

    $equal=true;*/

    for($i=0;$i<count($variable);$i++){

        if(isset($values[$i]) && $values[$i]!='' && $values[$i]!=null) {

 /*           if($prev=='') $prev=$values[$i];

            else if($prev!=$values[$i]) $equal=false;*/

            $result[$variable[$i]]=$values[$i];

        }

    }

    return $result;

/*    if ($equal) return $result[0];

    else return $result;*/

}



function haversine($lat1, $long1, $lat2, $long2){ 

    //Distancia en kilometros en 1 grado distancia.

    //Distancia en millas nauticas en 1 grado distancia: $mn = 60.098;

    //Distancia en millas en 1 grado distancia: 69.174;

    //Solo aplicable a la tierra, es decir es una constante que cambiaria en la luna, marte... etc.

    $km = 111.302;

    

    //1 Grado = 0.01745329 Radianes    

    $degtorad = 0.01745329;

    

    //1 Radian = 57.29577951 Grados

    $radtodeg = 57.29577951; 

    //La formula que calcula la distancia en grados en una esfera, llamada formula de Harvestine. Para mas informacion hay que mirar en Wikipedia

    //http://es.wikipedia.org/wiki/F%C3%B3rmula_del_Haversine

    $dlong = ($long1 - $long2); 

    $dvalue = (sin($lat1 * $degtorad) * sin($lat2 * $degtorad)) + (cos($lat1 * $degtorad) * cos($lat2 * $degtorad) * cos($dlong * $degtorad)); 

    $dd = acos($dvalue) * $radtodeg; 

    return round(($dd * $km), 2);

}



function decodificar($str){

    return str_replace('\u?','~',$str);

/*    $chars = explode('\\u', $str);

    if(count($chars)>1){

        for($i=1;$i<=count($chars);$i++){

            $chars[$i]=chr(hexdec(substr($chars[$i],0,4))).substr($chars[$i],4);

        }

    }

    return implode('',$chars);	*/

}

function ordenar ($a, $b) {

    return $a['d'] - $b['d'];

}

?>