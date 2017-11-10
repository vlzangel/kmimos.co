jQuery( document ).ready(function(){
	jQuery('#pfuaprofileform').submit(function(e){

		var z = 0;
		var t = [
			'pequenos',
			'medianos',
			'grandes',
			'gigantes'
		];

		jQuery.each(t, function( index, value ) {
			var temp = jQuery('#hospedaje_'+value).attr('value');
			if( temp == '' ){ temp = 0; }
			z += parseInt( temp );
		});

		if( z == 0 ){
			jQuery('#error_hospedaje').attr('class', 'error');
			jQuery('html, body').animate({ scrollTop: jQuery('#precios_hospedaje').offset().top-75 }, 2000);
			e.preventDefault();
		}else{
			jQuery('#error_hospedaje').attr('class', 'no_error');
		}
	});

	jQuery( '.vlz_activador' ).each(function( index ) {
	  	jQuery( this ).on('click', function(e){
	  		var status = jQuery('#oculto_'+e.target.id).val();
	  		if(status == 1){
	  			jQuery('#'+e.target.id).removeClass('vlz_activado');
	  			jQuery('#'+e.target.id).addClass('vlz_desactivado');
	  			jQuery('#'+e.target.id).attr('value', 'Desactivado');
	  			jQuery('#oculto_'+e.target.id).val(0);
	  		}else{
	  			jQuery('#'+e.target.id).addClass('vlz_activado');
	  			jQuery('#'+e.target.id).removeClass('vlz_desactivado');
	  			jQuery('#'+e.target.id).attr('value', 'Activado');
	  			jQuery('#oculto_'+e.target.id).val(1);
	  		}
	  	});
	});

	postJSON( 
  		"form_perfil",
       	URL_PROCESOS_PERFIL, 
       	function( data ) {
			jQuery("#btn_actualizar").val("Procesando...");
            jQuery("#btn_actualizar").attr("disabled", true);
			jQuery(".perfil_cargando").css("display", "inline-block");
       	}, 
       	function( data ) {

       		console.log( data );

			jQuery("#btn_actualizar").val("Actualizar");
			jQuery("#btn_actualizar").attr("disabled", false);
            jQuery(".perfil_cargando").css("display", "none");
       	}
   	);
});