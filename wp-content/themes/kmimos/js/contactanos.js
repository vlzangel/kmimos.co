
jQuery(document).on( 'click', '#enviar_mensaje', function(){
	var btn = jQuery(this);

	if( !btn.hasClass('disabled') ){

			btn.html( 'Enviando' );
			btn.addClass( 'disabled' );
		jQuery('#mensaje').css( 'display', 'none'  );

		if( validar_contactanos( [ 'nombres', 'email', 'asunto', 'contenido' ] ) ){
			jQuery.post( HOME+'/procesos/generales/contactanos.php', jQuery('#contactanos').serialize(), function(data){

				if( data.code == 'OK' ){
					jQuery('#mensaje').html( ' Mensaje enviado ' );
					jQuery('#mensaje').addClass( 'alert-success' );

					jQuery('#contactanos').find("input[type=text], input[type=email], textarea").val("");

				}else{
					jQuery('#mensaje').html( ' Mensaje no enviado ' );
					jQuery('#mensaje').addClass( 'alert-danger' );
				}
				jQuery('#mensaje').css( 'display', 'block' );

				btn.html( 'Enviar mensaje' );	
				btn.removeClass( 'disabled' );	

			});
		}
	}
});

jQuery(document).on('keyup', '[data-type="fields"]', function(){
	validar_contactanos( [ jQuery(this).attr('name') ] );
});

function validar_contactanos( fields ){

	var status = true;
	if( fields.length > 0 ){
		jQuery.each( fields, function(id, val){
			var m = '';
			/*validar vacio*/
			if( jQuery('[name="'+val+'"]').val() == '' ){
				m = 'Este campo no puede estar vacio';
			} 

			if( m == ''){
				mensaje(val, m, true);
			}else{
				mensaje(val, m);
				status = false;
			}

		});
	}
	return status;
}

function mensaje( label, msg='', reset=false ){
	var danger_color =  '#c71111';
	var border_color =  '#c71111';
	var visible = 'visible';
	if( reset ){
		danger_color = '#000';
		border_color = '#ccc';
		visible = 'hidden';
	}
	jQuery('[data-error="'+label+'"]').css('visibility', visible);
	jQuery('[data-error="'+label+'"]').html(msg);
	jQuery( '[name="'+label+'"]' ).css('border-bottom', '1px solid ' + border_color);
}