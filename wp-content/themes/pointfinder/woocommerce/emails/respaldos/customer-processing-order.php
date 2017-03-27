<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	echo kmimos_get_email_header();

	$html .= '<div class="container">';
	$html .= '	<span class="title">'.$email_heading.'</span>';
	$html .= '	<div class="content">';
	echo $html; ?>

	<p><?php _e( "Your order has been received and is now being processed. Your order details are shown below for your reference:", 'woocommerce' ); ?></p>

	<?php

	$pedido_id = $order->post->ID;

	echo "Solicitud de reserva de servicio (no. ".$pedido_id.")";

	do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

	do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

	// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

	$gretting = 'Atentamente,';

	$html = '	</div>
	      	<div class="gretting">
	        	<span>'.$gretting.'</span><br>
	        	<img src="'.get_home_url().'/wp-content/uploads/2016/03/logo-kmimos_120x30.png" alt="Firma Kmimos">
	     	</div>';

	$html .= kmimos_get_email_banners();

	$html .= '    </div>';

	$html .= kmimos_get_email_footer();
	echo $html;

?>

