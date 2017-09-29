<?php if ( !defined( 'ABSPATH' ) ) exit; ?>



<?php 



//do_action( 'woocommerce_email_header', $email_heading ); 



echo kmimos_get_email_header();

/*

*   Coloca el contenido

*/

$html .= '    <div class="container">';

$html .= '      <span class="title">'.$email_heading.'</span>';

$html .= '      <div class="content">';



echo $html;



?>



	<p><?php printf( __( "Hi there. This is a notification about a vendor application on %s.", 'wcvendors' ), get_option( 'blogname' ) ); ?></p>



	<p>

		<?php printf( __( "Application status: %s", 'wcvendors' ), $status ); ?><br/>

		<?php printf( __( "Applicant username: %s", 'wcvendors' ), $user->user_login ); ?>

	</p>



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



//do_action( 'woocommerce_email_footer' ); ?>