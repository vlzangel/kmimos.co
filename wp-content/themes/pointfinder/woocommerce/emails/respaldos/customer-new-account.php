<?php

/**

 * Customer new account email

 *

 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.

 *

 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).

 * will need to copy the new files to your theme to maintain compatibility. We try to do this.

 * as little as possible, but it does happen. When this occurs the version of the template file will.

 * be bumped and the readme will list any important changes.

 *

 * @see 	    http://docs.woothemes.com/document/template-structure/

 * @author 		WooThemes

 * @package 	WooCommerce/Templates/Emails

 * @version     1.6.4

 */



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}



?>



<?php 



//do_action( 'woocommerce_email_header', $email_heading, $email ); 



echo kmimos_get_email_header();

/*

*   Coloca el contenido

*/

$html .= '    <div class="container">';

$html .= '      <span class="title">'.$email_heading.'</span>';

$html .= '      <div class="content">';



echo $html;

?>



<p><?php printf( __( "Thanks for creating an account on %s. Your username is <strong>%s</strong>.", 'woocommerce' ), esc_html( $blogname ), esc_html( $user_login ) ); ?></p>



<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>



	<p><?php printf( __( "Your password has been automatically generated: <strong>%s</strong>", 'woocommerce' ), esc_html( $user_pass ) ); ?></p>



<?php endif; ?>



<p><?php printf( __( 'You can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), wc_get_page_permalink( 'myaccount' ) ); ?></p>



<?php 

$gretting = 'Atentamente,';



$html = '      </div>

      <div class="gretting">

        <span>'.$gretting.'</span><br>

        <img src="'.get_home_url().'/wp-content/uploads/2016/03/logo-kmimos_120x30.png" alt="Firma Kmimos">

      </div>';



/*

*   Introduce los banners

*/

$html .= kmimos_get_email_banners();

/*

*   Fin de los banners

*/

$html .= '    </div>';

/*

*   Empieza el pie del correo

*/

$html .= kmimos_get_email_footer();

echo $html;

//do_action( 'woocommerce_email_footer', $email ); ?>

