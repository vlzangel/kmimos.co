<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// Modificacion Ángel Veloz
$DS = kmimos_session();
if( !$DS ){
	$ver_formulario = " style='display: block;' ";
	if( isset($DS["no_pagar"]) ){
		$ver_formulario = " style='display: none;' ";
	}
}else{
	if( WC()->cart->total-WC()->cart->tax_total == 0 ){
		$ver_formulario = " style='display: none;' ";
	}
} ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
		<div id="customer_details" <?php echo $ver_formulario; ?> >
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>
		</div>
		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
		<p style="display: none;"><strong>(<span style="color: red">*</span>)  Campos obligatorios</strong></p>
	<?php endif; ?>

	<h3 id="order_review_heading" style="font-size: 20px; font-weight: 600;"><?php _e( 'Datos de la reserva', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php
		do_action( 'woocommerce_checkout_order_review' );
		?>
	</div>
	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<script>
	jQuery( document ).ready(function() {
		jQuery('dt.variation-Duracin').css('display', 'none');
		jQuery('dd.variation-Duracin').css('display', 'none');

		<?php
             $cu = wp_get_current_user();
             echo "jQuery('#billing_email').attr('value', '{$cu->user_email}');";
             $metas_cliente = get_user_meta($cu->ID);

             echo "jQuery('#billing_first_name').attr('value', '{$cu->user_firstname}');";
             echo "jQuery('#billing_last_name').attr('value', '{$cu->user_lastname}');";
             echo "jQuery('#billing_phone').attr('value', '+57{$metas_cliente["user_mobile"][0]}');";
         ?>

		jQuery('#billing_postcode').attr('value', '10110');
	});
</script>

<style type="text/css">
	tbody .product-total,
	.cart-subtotal td,
	.cart-discount td,
	.order-total td,
	.order-paid td,
	.vlz_totales td,
	.order-remaining td
	{
		text-align: right;
	}
	#add_payment_method #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods>li>label {
		color: #54c8a7;
		font-size: large;
		font-weight: bold;
		text-shadow: 3px 2px 12px rgba(255, 255, 255, 0.57);
	}
	.wc-terms-and-conditions a{
		font-size: 15px;
		color: #54c8a7;
		font-weight: 600;
	}
	@media (max-width: 592px){
		#add_payment_method #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods>li>label {
			font-size: x-small;
		}

	}
</style>