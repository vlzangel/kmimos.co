// Modificacion Ángel Veloz
jQuery(document).ready(function($) {

	if ( ! window.console ) {
		window.console = {
			log : function(str) {
				// alert(str);
			}
		};
	}

	jQuery("#form_servicio")
		.on('change', 'input, select', function() {

			var id = jQuery(this).attr("id");
			if( id != "pay-deposit" && id != "pay-full-amount" ){
				jQuery('.single_add_to_cart_button').addClass('xdisabled');

				jQuery('.single_add_to_cart_button').css('background-color', "#60cbac;");
				jQuery('.single_add_to_cart_button').css('color', "#fff;");
				jQuery('.single_add_to_cart_button').html('Continuar');
			}

		})
		.each(function(){
			/*var button = $(this).closest('form').find('.single_add_to_cart_button');
			button.addClass('disabled');*/
		});

	$( '.single_add_to_cart_button' ).on( 'click', function( event ) {
		if ( $(this).hasClass('xdisabled') ) {
			//alert( booking_form_params.i18n_choose_options );
			calcular_costo();
			event.preventDefault();
			return false;
		}
	})

	$('.wc-bookings-booking-form, .wc-bookings-booking-form-button').show().removeAttr( 'disabled' );

});

function calcular_costo(){
	var name  = jQuery(this).attr('name');

	var $fieldset = jQuery(this).closest('fieldset');
	var $picker   = $fieldset.find( '.picker:eq(0)' );
	if ( $picker.data( 'is_range_picker_enabled' ) ) {
		if ( 'wc_bookings_field_duration' !== name ) {
			return;
		}
	}

	var index = jQuery('.wc-bookings-booking-form').index(this);
	$form = jQuery("#form_servicio");

	var required_fields = $form.find('input.required_for_calculation');
	var filled          = true;
	jQuery.each( required_fields, function( index, field ) {
		var value = jQuery(field).val();
		if ( ! value ) {
			filled = false;
		}
	});
	if ( ! filled ) {
		$form.find('.wc-bookings-booking-cost').hide();
		return;
	}

	$form.find('.wc-bookings-booking-cost').block({message: null, overlayCSS: {background: '#fff', backgroundSize: '16px 16px', opacity: 0.6}}).show();
	jQuery.ajax({
		type: 		'POST',
		url: 		booking_form_params.ajax_url,
		data: 		{
			action: 'wc_bookings_calculate_costs',
			form:   $form.serialize()
		},
		success: 	function( code ) {

			// console.log(code);

			if ( code.charAt(0) !== '{' ) {
				code = '{' + code.split(/\{(.+)?/)[1];
			}

			result = jQuery.parseJSON( code );

			if ( result.result == 'ERROR' ) {
				$form.find('.wc-bookings-booking-cost').html( result.html );
				$form.find('.wc-bookings-booking-cost').unblock();
				$form.find('.single_add_to_cart_button').addClass('xdisabled');
			} else if ( result.result == 'SUCCESS' ) {
				$form.find('.wc-bookings-booking-cost').html( result.html );
				$form.find('.wc-bookings-booking-cost').unblock();
				$form.find('.single_add_to_cart_button').removeClass('xdisabled');

				jQuery('.single_add_to_cart_button').css('background-color', "#f84700;"); // Continuar con tu reserva
				jQuery('.single_add_to_cart_button').html('Ir al pago');
			} else {
				$form.find('.wc-bookings-booking-cost').hide();
				$form.find('.single_add_to_cart_button').addClass('xdisabled');
			}
		},
		error: function() {
			$form.find('.wc-bookings-booking-cost').hide();
			$form.find('.single_add_to_cart_button').addClass('xdisabled');
		},
		dataType: 	"html"
	});
}
