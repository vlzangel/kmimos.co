<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Modificacion Ángel Veloz
global $wpdb;
global $current_user;

$data_session = kmimos_session();
if( $data_session ){

	if( isset($data_session["reserva"]) ){

		$new_order   = $order->get_order_number();
		$new_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = '{$new_order}' AND post_type='wc_booking'");

		$servicio = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$new_reserva}' AND meta_key='_booking_product_id'");

		if( $servicio == $data_session["servicio"] ){
			$id_reserva = $data_session["reserva"];

			$sql_SELECT = "SELECT * FROM wp_posts WHERE ID = '{$id_reserva}'";
			$data = $wpdb->get_row($sql_SELECT);
			$id_reserva = $data->ID;
			$id_orden   = $data->post_parent;

			$sql_UPDATE = "UPDATE wp_posts SET post_status = 'modified' WHERE ID IN ( '{$id_reserva}', '{$id_orden}' );";

			$wpdb->query($sql_UPDATE);

			update_post_meta($id_reserva,  'reserva_modificada', $new_reserva);
			update_post_meta($new_reserva, 'modificacion_de',    $id_reserva );

			update_user_meta($current_user->ID, "kmisaldo", ($data_session["saldo_permanente"]+0) );
		}
	}else{
		update_user_meta($current_user->ID, "kmisaldo", ($data_session["saldo_permanente"]+0) );
	}

	kmimos_quitar_session();
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p class="woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

		<p class="woocommerce-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Gracias, tu reserva ha sido recibida.', 'woocommerce' ), $order ); ?></p>

		<ul class="woocommerce-thankyou-order-details order_details">
			<li class="order">
				<?php _e( 'Número de reserva:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
        		
        <?php 
        	if( $order->payment_method_title == "Pago en efectivo en tiendas de conveniencia" ){ 
        		if( WC()->session->get( 'pdf_url' ) != "" ): ?>
		            <a href="<?php echo WC()->session->get( 'pdf_url' ); ?>" class='fondo_kmimos'  style="padding: 5px; color: #fff; font-weight: 400; font-size: 14px; font-family: Roboto; border-radius: 3px; border: solid 1px #1f906e; display: block; max-width: 450px; margin: 0px auto; text-align: center; text-decoration: none;" target="_blank">
						Pícale para ver las instrucciones para<br> Pago en Tiendas por Conveniencia
					</a>                  
		            <br>                    
		            <iframe id="pdf" src="<?php echo WC()->session->get( 'pdf_url' ); ?>" style="width:100%; height:950px;" frameborder="0"></iframe>
		        	<style type="text/css">
						@media (max-width: 600px){
							#pdf { display: none; }
						}
					</style> <?php 
				endif; 
			} 
		?>    
        
        <div class="clear"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>

<div style="text-align: right;">
	<a href="<?php echo get_option('siteurl'); ?>" class="button pay">Inicio</a>
	<a href="<?php echo get_option('siteurl')."/perfil-usuario/?ua=invoices"; ?>" class="button pay">Mis reservas</a>
</div>

<style type="text/css">
	tbody .product-total,
	tfoot td
	{
		text-align: right;
	}
</style>