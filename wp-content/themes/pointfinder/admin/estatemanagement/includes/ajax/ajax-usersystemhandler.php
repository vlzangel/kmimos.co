<?php
/**********************************************************************************************************************************
*
* Ajax Member System Actions
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


add_action( 'PF_AJAX_HANDLER_pfget_usersystemhandler', 'pf_ajax_usersystemhandler' );
add_action( 'PF_AJAX_HANDLER_nopriv_pfget_usersystemhandler', 'pf_ajax_usersystemhandler' );

function generarClave($lenght=8){
    $lenght = ($lenght<8)? 8 : $lenght;
    $char = strRand(1, "abcdefghijklmnopqrstuvwxyz");
    $char .= strRand(1, "ABCDEFGHIJKLMNOPQRSTUVWXYZ");
    $char .= strRand(1, "0123456789");
    $char .= strRand(1, "#");
    $char .= strRand($lenght-4);
    return str_shuffle($char);
}

function strRand($lenght=8, $char = ""){
    if($char == ""){
        $char = "#0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    return substr(str_shuffle($char), 0, $lenght);
}

function pf_ajax_usersystemhandler(){
	//Security
  check_ajax_referer( 'pfget_usersystemhandler', 'security' );

	header('Content-Type: application/json; charset=UTF-8;');

	//Get form type
	if(isset($_POST['formtype']) && $_POST['formtype']!=''){
		$formtype = esc_attr($_POST['formtype']);
	}


  //Get form data
  if(isset($_POST['vars']) && $_POST['vars']!=''){
    $vars = array();
    parse_str($_POST['vars'], $vars);

    if (is_array($vars)) {
        $vars = PFCleanArrayAttr('PFCleanFilters',$vars);
    } else {
        $vars = esc_attr($vars);
    }
  }
  $pfrecheck = PFRECIssetControl('setupreCaptcha_general_status','','0');
  
  if (wp_get_referer()) {
    $wpreferurl = esc_url(wp_get_referer());
  }else{
    $wpreferurl = esc_url(home_url());
  }

	switch($formtype){
		case 'login':
      if (is_array($vars)) {
        $redirectpage = (isset($vars['redirectpage']))? $vars['redirectpage']:0;

        $pfrechecklg = PFRECIssetControl('setupreCaptcha_general_login_status','','0');

        if(in_array('rem', $vars)){
          $rememberme = ($vars['rem'] == 'on') ? true : false ;
        }else{
          $rememberme = false;
        }
        $info = array();
            $info['user_login'] = sanitize_user($vars['username'],true);
            $info['user_password'] = sanitize_text_field($vars['password']);
            $info['remember'] = $rememberme;

        if ( $pfrecheck == 1 && $pfrechecklg == 1) {
          if (isset($vars['g-recaptcha-response'])) {
            $pfReResult = PFCGreCaptcha($vars['g-recaptcha-response']);
            if ($pfReResult == 1) {
              $user_signon = wp_signon( $info, true );
              if ( is_wp_error( $user_signon )) {
                  echo json_encode( array( 'login'=>false, 'mes'=>esc_html__( 'Wrong username or password!','pointfindert2d' )));
              } else {
                  wp_set_auth_cookie($user_signon->ID);
                  echo json_encode( array( 'login'=>true, 'mes'=>esc_html__('Login successful, redirecting...','pointfindert2d' ),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
              }
            }else{
              echo json_encode( array( 'login'=>false, 'mes'=>esc_html__('Wrong reCaptcha. Please verify first.','pointfindert2d' )));
            }
          }else{
            echo json_encode( array( 'login'=>false, 'mes'=>esc_html__( 'Please enter reCaptcha!','pointfindert2d' )));
          }
        }else{
          $user_signon = wp_signon( $info, true );

          if ( is_wp_error( $user_signon )) {
              echo json_encode( array( 'login'=>false, 'mes'=>esc_html__( 'Wrong username or password!','pointfindert2d' )));
          } else {
              wp_set_auth_cookie($user_signon->ID);
              echo json_encode( array( 'login'=>true, 'mes'=>esc_html__('Login successful, redirecting...','pointfindert2d' ),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
          }
        }
        
      }
		break;
    case 'register':
      $pfrechecklg = PFRECIssetControl('setupreCaptcha_general_reg_status','','0');
      if ( $pfrecheck == 1 && $pfrechecklg == 1) {
        if (isset($vars['g-recaptcha-response'])) {
          $pfReResult = PFCGreCaptcha($vars['g-recaptcha-response']);
          
          if ($pfReResult != 1) {
            $progressok = 0;
            echo json_encode( array( 'login'=>false, 'mes'=>esc_html__('Wrong reCaptcha. Please verify first.','pointfindert2d' )));
          }else{
            $progressok = 1;
          }

        }else{
          $progressok = 0;
          echo json_encode( array( 'login'=>false, 'mes'=>esc_html__( 'Please enter reCaptcha!','pointfindert2d' )));
        }
      }else{
        $progressok = 1;
      }

      if($progressok == 1){

        $username = $vars['username'];
        $email = sanitize_email($vars['email']);
        

        $username = sanitize_user( $username, $strict = true );
        

        // campo vacios
        if( empty($email) || empty($username)){
            $message = sprintf(esc_html__("Debe completar los datos","pointfindert2d"),'<br/>');
            echo json_encode( array( 'status'=>01, 'mes'=>$message));
            exit;
        }
         // Validar formato de usuario
        /*if( !preg_match("/^([a-z]+[0-9]{0,4}){3,12}$/", $username) ){
            $message = sprintf(esc_html__("Nombre de usuario invalido. Solo admite minimo 3 caracteres alfabeticos y hasta 4 caracteres numericos. Ejemplo: Ali18","pointfindert2d"),'<br/>');
            echo json_encode( array( 'status'=>01, 'mes'=>$message));
            exit;
        }*/
        // Validar formato de email
        $isAlliasMail = preg_match("/[\+]{1,}/", $email);
        if( !filter_var($email, FILTER_VALIDATE_EMAIL) || $isAlliasMail ){
            $message = sprintf(esc_html__("Verifique el formato del Correo electrónico. Ejemplo: usuario@dominio.com","pointfindert2d"),'<br/>');
            echo json_encode( array( 'status'=>01, 'mes'=>$message));
            exit;
        }

        $user_exist = username_exists( $username );
        $user_email_exist = email_exists( $email );
        
       
        if ( $user_exist || $user_email_exist ) {

            $message = sprintf(esc_html__("Oops! There appears to be an account already with that name and/or email. %s Please change username and/or email.","pointfindert2d"),'<br/>');
            echo json_encode( array( 'status'=>01, 'mes'=>$message));
            exit;

        } else {

            
            // $password = wp_generate_password( 12, false );

            $password = generarClave();

            $user_id = wp_create_user( $username, $password, $email );

            wp_update_user( array( 'ID' => $user_id ));

            $user = new WP_User( $user_id );
            $user->set_role( 'subscriber' );

           
            /*$message_reply = pointfinder_mailsystem_mailsender(
    					array(
    						'toemail' => $email,
    				        'predefined' => 'registration',
    				        'data' => array('password' => $password,'username'=>$username),
    					)
    				);

            $setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','','1');
            pointfinder_mailsystem_mailsender(
              array(
                'toemail' => $setup33_emailsettings_mainemail,
                    'predefined' => 'registrationadmin',
                    'data' => array('username'=>$username),
              )
            );*/

            $mensaje_mail = '
                <h1>¡Gracias por unirte a nuestra familia Kmimos!</h1>
                <p>Hola <strong>'.$username.'</strong>,</p>
                <p style="text-align: justify;">
                    Abajo encontrarás tus credenciales para que tengas acceso a Kmimos.
                </p>
                <p>
                    <table>
                        <tr> <td> <strong>Usuario:</strong> </td><td>'.$username.'</td> </tr>
                        <tr> <td> <strong>Contraseña:</strong> </td><td>'.$password.'</td> </tr>
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

            add_filter( 'wp_mail_from_name', function( $name ) {
                return 'Kmimos Colombia';
            });
            add_filter( 'wp_mail_from', function( $email ) {
                return 'kmimos@kmimos.la';
            });

            $mail_msg = kmimos_get_email_html("Registro de Nuevo Usuario.", $mensaje_mail, '', true, true);

            if ( wp_mail( $email, "Kmimos Colombia– Gracias por registrarte! Kmimos la NUEVA forma de cuidar a tu perro!", $mail_msg) ) {

                $message = esc_html__("Success! Check your email for your password!","pointfindert2d");

                echo json_encode( array( 'status'=>0 , 'mes'=>$message));

            } else {

                $message = esc_html__("No hemos podido enviar el E-mail.","pointfindert2d");
                echo json_encode( array( 'status'=>03, 'mes'=>$message));
            }
        }
      }
    break;
    case 'lp':

      function pfretrieve_password($user_login) {
          global $wpdb, $current_site;

          if ( empty( $user_login) ) {
              return false;
          } else if ( strpos( $user_login, '@' ) ) {
              $user_data = get_user_by( 'email', trim( $user_login ) );
              if ( empty( $user_data ) )
                 return false;
          } else {
              $login = trim($user_login);
              $user_data = get_user_by('login', $login);
          }

          do_action('lostpassword_post');


          if ( !$user_data ){return false;}

          // redefining user_login ensures we return the right case in the email
          $user_login = $user_data->user_login;
          $user_email = $user_data->user_email;

          do_action('retrieve_password', $user_login);

          $allow = apply_filters('allow_password_reset', true, $user_data->ID);

          if ( ! $allow ){
              return false;
          }else if ( is_wp_error($allow) ){
              return false;
          }

          $key = '';//$wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
          if ( empty($key) ) {
              // Generate something random for a key...
              $key = wp_generate_password(20, false);
              do_action('retrieve_password_key', $user_login, $key);


              if ( empty( $wp_hasher ) ) {
                  require_once ABSPATH . 'wp-includes/class-phpass.php';
                  $wp_hasher = new PasswordHash( 8, true );
              }
              /*
              Change this
              $hashed = $wp_hasher->HashPassword( $key );
              */
              $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
              // Now insert the new md5 key into the db
              $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));
          }

          $message_reply = pointfinder_mailsystem_mailsender(
					array(
						'toemail' => $user_email,
				        'predefined' => 'lostpassword',
				        'data' => array('keylink' => network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login'),'username'=>$user_login),
					)
				);


          if ( !$message_reply){
              return  esc_html__('The e-mail could not be sent.','pointfindert2d') . "<br />\n" . esc_html__('Possible reason: Your host may have disabled the mail() function...','pointfindert2d');
          }

          return esc_html__('Password reset link has been sent to your email.','pointfindert2d');
      }

      $pflpwd = esc_html__('Unfortunately we can not find this email and/or username in our records.','pointfindert2d');
      $pflpwd_status = 1;
      
      $pfrechecklg = PFRECIssetControl('setupreCaptcha_general_fb_status','','0');

      if ( $pfrecheck == 1 && $pfrechecklg == 1) {
        if (isset($vars['g-recaptcha-response'])) {
          $pfReResult = PFCGreCaptcha($vars['g-recaptcha-response']);
          
          if ($pfReResult != 1) {
            $progressok = 0;
            echo json_encode( array( 'login'=>false, 'mes'=>esc_html__('Wrong reCaptcha. Please verify first.','pointfindert2d' )));
          }else{
            $progressok = 1;
          }

        }else{
          $progressok = 0;
          echo json_encode( array( 'login'=>false, 'mes'=>esc_html__( 'Please enter reCaptcha!','pointfindert2d' )));
        }
      }else{
        $progressok = 1;
      }

      if($progressok == 1){
        if (!empty($vars['username'])) {
          if ( username_exists( $vars['username'] ) ){
            $user_login = sanitize_text_field( $vars['username'] );
            $pflpwd = pfretrieve_password($user_login);
            $pflpwd_status = 0;
          }
        }

        if(!empty($vars['email'])){
            $user_email = sanitize_email( $vars['email'] );
            if( email_exists( $user_email )) {
              $user_retemail = get_user_by( 'email', $user_email );
              $pflpwd = pfretrieve_password($user_retemail->data->user_login);
              $pflpwd_status = 0;
            }
        }
        if($pflpwd_status == 0){
          echo json_encode( array( 'status'=>0, 'mes'=>$pflpwd));
        }else{
          echo json_encode( array( 'status'=>1, 'mes'=>$pflpwd));
        };
      }
    break;
	}
die();
}

?>