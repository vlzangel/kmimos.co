jQuery.fn.modal.Constructor.prototype.enforceFocus = function() {};

function close_modal(){
    var c = jQuery(".modal");
    jQuery.each(c,function(i,o){ 
    	var id = c[i].id;
        jQuery("#"+id).modal("hide");
        // jQuery("#"+id).fadeOut("fast");
        // jQuery('.modal-backdrop').fadeOut("fast");
    });
}

$(document).on("click", '[data-target="#popup-iniciar-sesion"]' ,function(e){
	e.preventDefault();

	//close_modal();
	jQuery('[data-error="auth"]').fadeOut("fast");

	jQuery(".popup-iniciar-sesion-1").fadeIn("fast");
	jQuery(".popup-olvidaste-contrasena").fadeOut("fast");
	jQuery('.verify_result').html('');
	jQuery('.response').html('');

	jQuery('#form_login')[0].reset();
	jQuery('#form_recuperar')[0].reset();


	jQuery( '#popup-conoce-cuidador' ).modal('hide');
	jQuery( jQuery(this).data('target') ).modal('show');
});


function menu(){
	var w = jQuery(window).width();

	if(jQuery(this).scrollTop() > 10) {

		if( !jQuery(".navbar").hasClass("bg-white-secondary") ){
			jQuery('.bg-transparent').addClass('bg-white');
			jQuery('.navbar-brand img').attr('src', HOME+'images/new/km-logos/km-logo-negro'+wlabel+'.png');
			jQuery('.sin_logear img').attr('src', HOME+'/images/new/km-navbar-mobile-negro.svg');
		}

	} else {

		if( !jQuery(".navbar").hasClass("bg-white-secondary") ){
			jQuery('.bg-transparent').removeClass('bg-white');
			jQuery('.navbar-brand img').attr('src', HOME+'/images/new/km-logos/km-logo'+wlabel+'.png');
			jQuery('.sin_logear img').attr('src', HOME+'/images/new/km-navbar-mobile.svg');
		}
		
	}

}

jQuery(window).resize(function() {
	menu();

	if( typeof resizeMap !== 'undefined' ){
		resizeMap();
	}
});

jQuery(window).scroll(function() {
	menu();
});

var fecha = new Date();
jQuery(document).ready(function(){
	menu();

	if(navigator.platform.substr(0, 2) == 'iP'){
		/*jQuery('html').addClass('iOS');*/
		jQuery(".label-placeholder").addClass("focus");
	} else {
		jQuery(document).on("focus", "input.input-label-placeholder", function(){
			jQuery(this).parent().addClass("focus");
		}).on("blur", "input.input-label-placeholder", function(){
			let i = jQuery(this);
			if ( i.val() !== "" ) jQuery(this).parent().addClass("focused");
			else jQuery(this).parent().removeClass("focused");

			jQuery(this).parent().removeClass("focus");
		});
	}

});

