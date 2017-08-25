<?php
/**
 * Booking product add to cart
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<noscript><?php _e( 'Your browser must support JavaScript in order to make a booking.', 'woocommerce-bookings' ); ?></noscript>

<form id="form_servicio" class="cart" method="post" enctype='multipart/form-data'>

	<div id="wc-bookings-booking-form" class="wc-bookings-booking-form" style="display:none">

		<?php do_action( 'woocommerce_before_booking_form' ); ?>

		<?php $booking_form->output(); ?>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="wc-bookings-booking-cost" style="display:none"></div>

	</div>

	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

	<button type="submit" class="wc-bookings-booking-form-button single_add_to_cart_button button xdisabled" style="display:none; float: right; color: #FFF;">Continuar</button>

<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

</form>

<script type="text/javascript">
	function activar_continuar(){
		jQuery('.single_add_to_cart_button').addClass('xdisabled');
		jQuery('.single_add_to_cart_button').css('background-color', "#60cbac;");
		jQuery('.single_add_to_cart_button').css('color', "#fff;");
		jQuery('.single_add_to_cart_button').html('Continuar');
	}
</script>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php 
	// Modificacion Ángel Veloz
	$DS = kmimos_session();
	if( $DS ){

		if( isset($DS['reserva']) ){

			$duracion = ceil((((strtotime($DS['fechas']['fin']) - strtotime($DS['fechas']['inicio']))/60)/60)/24)+1;

			$inicio = explode("-", $DS['fechas']['inicio']);
			$fin    = explode("-", $DS['fechas']['fin']);

			$script_variaciones = "";
			if( count($DS['variaciones']) > 0){
				foreach ($DS['variaciones'] as $key => $value) {
					$script_variaciones .= "jQuery('#wc_bookings_field_persons_{$key}').attr('value', '{$value}');";
				}
			}

			$script_transporte = "";
			if( count($DS['transporte']) > 0 ){
				foreach ($DS['transporte'] as $key => $value) {
					$script_transporte .= "jQuery(\".addon-select option[value*='".$value."']\").attr('selected', 'selected');";
				}
			}

			$script_adicionales = "";
			if( count($DS['adicionales']) > 0 ){
				foreach ($DS['adicionales'] as $key => $value) {
					$script_adicionales .= "jQuery(\".addon-checkbox[value*='".$value."']\").attr('checked', 'checked');";
				}
			}
			echo '
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery("#wc_bookings_field_duration").attr("value", "'.$duracion.'");

						jQuery(".booking_date_day").attr("value", "'.$inicio[0].'");
						jQuery(".booking_date_month").attr("value", "'.$inicio[1].'");
						jQuery(".booking_date_year").attr("value", "'.$inicio[2].'");

						jQuery(".booking_to_date_day").attr("value", "'.$fin[0].'");
						jQuery(".booking_to_date_month").attr("value", "'.$fin[1].'");
						jQuery(".booking_to_date_year").attr("value", "'.$fin[2].'");

						'.$script_variaciones.'
						'.$script_transporte.'
						'.$script_adicionales.'

						calcular_costo();
					});
				</script>
			';

			if ( date( 'Ymd', strtotime($DS['fechas']['inicio']) ) < date( 'Ymd', current_time( 'timestamp' ) ) ) {
				echo '<script type="text/javascript"> var solo_fecha_fin = "YES"; </script>';
			}else{
				echo '<script type="text/javascript"> var solo_fecha_fin = "NO"; </script>';
			}
		}else{
			echo '<script type="text/javascript"> var solo_fecha_fin = "NO"; jQuery(document).ready(function() { activar_continuar(); }); </script>';
		}

	}else{
		echo '<script type="text/javascript"> var solo_fecha_fin = "NO"; jQuery(document).ready(function() { activar_continuar(); }); </script>';
	}
?>


		

