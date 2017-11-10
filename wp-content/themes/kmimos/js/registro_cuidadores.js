jQuery( document ).ready(function() {
	
	jQuery.post(
        HOME+"/procesos/busqueda/ubicacion.php",
        {},
        function(data){
            jQuery("#ubicacion_list").html(data);
            jQuery("#ubicacion_list div").on("click", function(e){
                jQuery("#ubicacion_txt").val( jQuery(this).html() );
                jQuery("#ubicacion").val( jQuery(this).attr("value") );
                jQuery("#ubicacion").attr( "data-value", jQuery(this).attr("data-value") );

                jQuery("#ubicacion_list").css("display", "none");
            });
            jQuery("#ubicacion_txt").attr("readonly", false);
        }
    );

	jQuery(".solo_letras").on("keyup", function(e){
		var valor = jQuery( this ).val();
		if( valor != "" ){
			var resul = ""; var no_permitido = false;
			jQuery.each(valor.split(""), function( index, value ) {
			  	if( /^[a-zA-Z ]*$/g.test(value) ){
					resul += value;
				}else{
					no_permitido = true;
				}
			});
			if( no_permitido ){
				jQuery( this ).val(resul);
			}
		}
	});
	
	jQuery(".solo_numeros").on("keyup", function(e){
		var valor = jQuery( this ).val();
		if( valor != "" ){
			var resul = ""; var no_permitido = false;
			jQuery.each(valor.split(""), function( index, value ) {
			  	if( /^[0-9]*$/g.test(value) ){
					resul += value;
				}else{
					no_permitido = true;
				}
			});
			if( no_permitido ){
				jQuery( this ).val(resul);
			}
		}
	});
	
	jQuery("input").on("keypress", function(e){
		var valor = jQuery( this ).attr("minlength");
		if( valor != undefined && valor+0 > 0 ){
			if( jQuery( this ).val().split("").length > valor ){
				var cont = 0; var result = "";
				jQuery.each(jQuery( this ).val().split(""), function( index, value ) {
				  	if( cont < valor ){
				  		result += value;
				  	}
				});
				jQuery( this ).val(result);
				return false;
			}
		}
	});

	jQuery(".social_email").on("change", function(){
		var email = jQuery(this);
		if( email.val().trim() == "" ){
			mensaje( email.attr("name"), '<span name="sp-email">Ingrese su email</span>' )
		}else{
			jQuery.ajax({
		        data: {
					'email': email.val()
				},
		        url:   HOME+'/procesos/login/main.php',
		        type:  'post',
		        success:  function (response) {
	                if (response == 'SI') {
						mensaje( email.attr("name"), '<span name="sp-email">Este E-mail ya esta en uso</span>' );
	                }else if (response == 'NO'){
						mensaje( email.attr("name"), '', true );
	                }
		        }
		    }); 
		}

	});

	jQuery(".obtener_direccion").on("click", function(e){
	    navigator.geolocation.getCurrentPosition(
	    	function(pos) {
	      		var crd = pos.coords;

	      		var position = {
	      			latitude:  crd.latitude,
	      			longitude: crd.longitude
	      		};

	      		vlz_coordenadas(position);

	    	}, 
	    	function error(err) {
	      		alert("No podemos obtener tus coordenadas, por favor ingresa tus datos");
	    	},
	    	{
		      	enableHighAccuracy: true,
		      	timeout: 5000,
		      	maximumAge: 0
		    }
	    );
	});

});

function redireccionar(){
	console.log("Entro: "+jQuery("#btn_iniciar_sesion").attr("data-url"));
	if( jQuery(".popup-registro-cuidador").css("display") == "none" ){
		if( jQuery(".popup-registro-cuidador-correo").css("display") == "none" ){
			location.href = jQuery("#btn_iniciar_sesion").attr("data-url");
		}
	}
}

function vlz_coordenadas(position){
	console.log("Hola 3");
    if(position.latitude != '' && position.longitude != '') {
        LAT = position.latitude;
        LNG = position.longitude;        
    }

    if( LAT == 0 || LNG == 0 ){ }else{
		jQuery.ajax({
	        url:   'https://maps.googleapis.com/maps/api/geocode/json?latlng='+LAT+','+LNG+'&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8',
	        type:  'get',
	        success:  function (response) {

                jQuery(".km-datos-estado-opcion option:contains('"+response.results[0].address_components[5].long_name+"')").prop('selected', true);
                var estado_id = jQuery(".km-datos-estado-opcion option:contains('"+response.results[0].address_components[5].long_name+"')").val();
                
                cambio_municipio(estado_id, function(){
                	jQuery(".km-datos-municipio-opcion option:contains('"+response.results[0].address_components[4].long_name+"')").prop('selected', true);
                });

                jQuery("#rc_direccion").val( response.results[0].formatted_address );

                jQuery("#rc_direccion").focus();

                jQuery("#latitud").val(LAT);
                jQuery("#longitud").val(LNG);
                
	        }
	    }, "json"); 
	}
}

var LAT = 0;
var LNG = 0;

jQuery(document).on("click", '[data-target="#popup-registro-cuidador1"]' ,function(e){
	e.preventDefault();

	jQuery("#vlz_form_nuevo_cuidador input").val('');

	jQuery(".popup-registro-exitoso").hide();
	jQuery(".popup-registro-cuidador-paso1").hide();
	jQuery(".popup-registro-cuidador-paso2").hide();
	jQuery(".popup-registro-cuidador-paso3").hide();
	jQuery(".popup-registro-cuidador-correo").hide();
	jQuery(".popup-registro-exitoso-final").hide();

	jQuery(".popup-registro-cuidador").fadeIn("fast");

	jQuery( jQuery(this).data('target') ).modal('show');
});

jQuery(document).on("click", '.popup-registro-cuidador .km-btn-popup-registro-cuidador', function ( e ) {
	e.preventDefault();

	jQuery(".popup-registro-cuidador").hide();
	jQuery(".popup-registro-cuidador-correo").fadeIn("fast");
});
 
jQuery("#cr_minus").on('click', function(e){
	e.preventDefault();
	var el = jQuery(this);
	if( !el.hasClass('disabled') ){
		var div = el.parent();
		var span = jQuery(".km-number", div);
		var input = jQuery("input", div);
		if ( span.html() > 0 ) {
			jQuery("#cr_plus").removeClass('disabled');
			var valor = parseInt(span.html()) - 1;
			span.html( valor );
			input.val( valor );
			input.attr( "value", valor );
		}

		if ( span.html() <= 1 ) {
			el.addClass("disabled");			
		}
	}
});

jQuery("#cr_plus").on('click', function(e){
	e.preventDefault();
	var el = jQuery(this);

	if( !el.hasClass('disabled') ){
		var div = el.parent();
		var span = jQuery(".km-number", div);
		var input = jQuery("input", div);
		if ( span.html() >= 0 ) {
			jQuery("#cr_minus").removeClass('disabled');
			var valor = parseInt(span.html()) + 1;
			span.html( valor );
			input.val( valor );
			input.attr( "value", valor );
		}

		if ( span.html() >= 6) {
			el.addClass("disabled");
			span.html( 6 );
			input.val( 6 );	
		}
	}
});

jQuery(document).on("click", '.popup-registro-cuidador-correo .km-btn-popup-registro-cuidador-correo', function ( e ) {
	e.preventDefault();		
	var a = HOME+"/procesos/cuidador/registro-paso1.php";
	var obj = jQuery(this);

	jQuery('input').css('border-bottom', '#ccc');
	jQuery('[data-error]').css('visibility', 'hidden');

	var list = ['rc_email','rc_nombres','rc_apellidos','rc_ife','rc_email','rc_clave','rc_telefono', 'rc_referred'];
	var valid = km_cuidador_validar(list);

	if( valid ){
		obj.html('Enviando datos');
		jQuery.post( a, jQuery('#vlz_form_nuevo_cuidador').serialize(), function( data ) {
			data = eval(data);
			if( data['error'] == "SI" ){				 
				if( data['fields'] != 'null' ){
					jQuery.each(data['fields'], function(id, val){
						mensaje( "rc_"+val['name'],val['msg']  );
					});
				}
				obj.html('SIGUIENTE');
			}else{
				jQuery('[data-target="name"]').html( jQuery('[name="rc_nombres"]').val() );
				jQuery(".popup-registro-cuidador-correo").hide();
				jQuery(".popup-registro-exitoso").fadeIn("fast");

				jQuery('[name="rc_num_mascota"]').val(1);
			}
		});
	}
});

jQuery(document).on("click", '.popup-registro-exitoso .km-btn-popup-registro-exitoso', function ( e ) {
	e.preventDefault();

	jQuery(".popup-registro-exitoso").hide();
	jQuery(".popup-registro-cuidador-paso1").fadeIn("fast");
});

jQuery(document).on("click", '[data-step="1"]', function ( e ) {
	e.preventDefault();
	jQuery(".popup-registro-cuidador-paso3").hide();
	jQuery(".popup-registro-cuidador-paso2").hide();
	jQuery(".popup-registro-cuidador-paso1").fadeIn("fast");
});

jQuery(document).on("click", '[data-step="2"]', function ( e ) {
	e.preventDefault();
	jQuery(".popup-registro-cuidador-paso1").hide();
	jQuery(".popup-registro-cuidador-paso3").hide();
	jQuery(".popup-registro-cuidador-paso2").fadeIn("fast");
});

jQuery(document).on("click", '.popup-registro-cuidador-paso1 .km-btn-popup-registro-cuidador-paso1', function ( e ) {
	e.preventDefault();

	var list = ['rc_descripcion'];
	var valid = km_cuidador_validar(list);
	if( valid ){
		jQuery(".popup-registro-cuidador-paso1").hide();
		jQuery(".popup-registro-cuidador-paso2").fadeIn("fast");		
	}
});

jQuery(document).on("click", '.popup-registro-cuidador-paso2 .km-btn-popup-registro-cuidador-paso2', function ( e ) {
	e.preventDefault();
	var list = ['rc_estado', 'rc_municipio'];
	var valid = km_cuidador_validar(list);
	if( valid ){
		jQuery(".popup-registro-cuidador-paso2").hide();
		jQuery(".popup-registro-cuidador-paso3").fadeIn("fast");
	}
});

jQuery(document).on("click", '.popup-registro-cuidador-paso3 .km-btn-popup-registro-cuidador-paso3', function ( e ) {
	e.preventDefault();

	var a = HOME+"/procesos/cuidador/registro-paso2.php";
	var obj = jQuery(this);
		obj.html('Enviando datos');

	jQuery('input').css('border-bottom', '#ccc');
	jQuery('[data-error]').css('visibility', 'hidden');

	var list = ['rc_num_mascota'];
	var valid = km_cuidador_validar(list);

	if( valid ){
		jQuery.post( a, jQuery("#vlz_form_nuevo_cuidador").serialize(), function( data ) {
			data = eval(data);
			if( data['error'] == "SI" ){
				
				if( data['fields'].length > 0 ){
					jQuery.each(data['fields'], function(id, val){
						mensaje( val['name'],val['msg']  );
					});
				}
				obj.html('SIGUIENTE');
			}else{
				jQuery('[data-id="ilernus-user"]').html( jQuery('[name="rc_email"]').val() );
				jQuery('[data-id="ilernus-pass"]').html( jQuery('[name="rc_clave"]').val() );

				jQuery(".popup-registro-cuidador-paso3").hide();
				jQuery(".popup-registro-exitoso-final").fadeIn("fast");
			}
		});
	}
});

jQuery(document).on('click', '#finalizar-registro-cuidador', function(){

	var url = jQuery(this).attr('data-href');

	$("<a>").attr("href", "https://kmimos.ilernus.com/login/index.php").attr("target", "_blank")[0].click();

	setTimeout(function() {
		location.href = url;
    },1500);

});

/*POPUP REGISTRO CUIDADOR*/
jQuery( document ).on('click', "[data-load='portada']", function(e){
	jQuery('#portada').click();
});

function cambio_municipio(estado_id, CB = false){
	jQuery.getJSON( 
        HOME+"procesos/generales/municipios.php", 
        {estado: estado_id} 
    ).done(
        function( data, textStatus, jqXHR ) {
            var html = "<option value=''>Seleccione un municipio</option>";
            jQuery.each(data, function(i, val) {
                html += "<option value="+val.id+">"+val.name+"</option>";
            });
            jQuery('[name="rc_municipio"]').html(html);

            if( CB != false ){
            	CB();
            }
        }
    ).fail(
        function( jqXHR, textStatus, errorThrown ) {
            console.log( "Error: " +  errorThrown );
        }
    );
}

jQuery(document).on('change', 'select[name="rc_estado"]', function(e){
	var estado_id = jQuery(this).val();
	    
    if( estado_id != "" ){
        cambio_municipio(estado_id);
    }

});

jQuery(document).on('change', 'select[name="rc_municipio"]', function(e){
	var locale=jQuery(this).val();
	
});

/*km_cuidador_validar DATOS*/
jQuery( document ).on('keypress', '[data-clear]', function(e){
	mensaje( jQuery(this).attr('rc_name'), '', true );
});

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
	/*jQuery('[data-error="'+label+'"]').css('color', danger_color);*/
	jQuery('[data-error="'+label+'"]').html(msg);
	jQuery( '[name="'+label+'"]' ).css('border-bottom', '1px solid ' + border_color);
	/*jQuery( '[name="'+label+'"]' ).css('color', danger_color);*/
}

function km_cuidador_validar( fields ){

	var status = true;
	if( fields.length > 0 ){
		jQuery.each( fields, function(id, val){
			var m = '';
			/*validar vacio*/
			if( jQuery('[name="'+val+'"]').val() == '' ){
				m = 'Este campo no puede estar vacio';
			}
			/*validar longitud*/
			if( m == ''){
				m = rc_validar_longitud( val );
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

function validar_longitud( val, min, max, type, err_msg){
	result = '';
	var value = 0;
	switch( type ){
		case 'int':
			value = val;
			break;
		case 'string':
			value = val.length;
			break;
	}

	if( value < min || value > max ){
		result = err_msg;
	}
	return result;
}

function rc_validar_longitud( field ){
	var result = '';
	var val = jQuery('[name="'+field+'"]').val();
	switch( field ){
			case 'rc_email':  
				result = validar_longitud( val, 10, 100, 'string', 'Debe estar entre 10 y 100 caracteres');
				break;

			case 'rc_nombres':
				result = validar_longitud( val, 2, 100, 'string', 'Debe estar entre 2 y 100 caracteres');
				break;

			case 'rc_apellidos':
				result = validar_longitud( val, 2, 100, 'string', 'Debe estar entre 2 y 100 caracteres');
				break;

			case 'rc_ife':
				result = validar_longitud( val, 13, 13, 'string', 'Debe tener 13 digitos');
				break;

			case 'rc_clave':
				result = validar_longitud( val, 1, 200, 'string', 'Debe estar entre 1 y 200 caracteres');
				break;

			case 'rc_telefono':
				result = validar_longitud( val, 7, 15, 'string', 'Debe estar entre 7 y 15 caracteres');
				break;

			case 'rc_descripcion':
				result = validar_longitud( val, 1, 600, 'string', 'Debe estar entre 1 y 100 caracteres');
				break;

			case 'rc_direccion':
				result = validar_longitud( val, 1, 600, 'string', 'Debe estar entre 5 y 300 caracteres');
				break;
	};
	return result;
}

function vista_previa(evt) {
	
	var files = evt.target.files;
	getRealMime(this.files[0]).then(function(MIME){
        if( MIME.match("image.*") ){

        	jQuery(".vlz_cargando").css("display", "block");

            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    redimencionar(e.target.result, function(img_reducida){
                        var img_pre = jQuery(".vlz_rotar_valor").attr("value");
                        jQuery.post( RUTA_IMGS+"/procesar.php", {img: img_reducida, previa: img_pre}, function( url ) {
                           
                        	jQuery("#perfil-img-a").css("background-image", "url("+RAIZ+"imgs/Temp/"+url+")" );
				      		jQuery("#perfil-img").css("display", "none" );
		        			jQuery("#vlz_img_perfil").val( url );
		        			jQuery(".vlz_rotar_valor").attr( "value", url );
			           		jQuery(".kmimos_cargando").css("visibility", "hidden");

                            jQuery(".btn_rotar").css("display", "block");

                            jQuery(".vlz_cargando").css("display", "none");
                        });
                    });      
                };
           })(files[0]);
           reader.readAsDataURL(files[0]);
        }else{
        	padre.children('#portada').val("");
            alert("Solo se permiten imagenes");
        }
    }).catch(function(error){
        padre.children('#portada').val("");
        alert("Solo se permiten imagenes");
    }); 

}      
document.getElementById("portada").addEventListener("change", vista_previa, false);


