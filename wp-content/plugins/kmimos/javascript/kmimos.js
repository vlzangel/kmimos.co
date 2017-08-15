jQuery.noConflict(); 

jQuery(document).ready(document).ready(function() {

	map_laterals();

	jQuery(window).on('resize', function(){

		map_laterals();

		jQuery("#petsitter-map").attr("src", jQuery('#petsitter-map').attr("src"));

	});

	function map_laterals(){

		var width = jQuery(window).width();

		var height = jQuery(window).height();

		//console.log('Cambiando dimensiones a: '+width+' x '+height);

		if(width<height){

			jQuery("#map-container").css('padding-left','0px');

			jQuery("#map-container").css('padding-right','0px');

		}

		else {

			jQuery("#map-container").css('padding-left',parseInt(width*0.05));

			jQuery("#map-container").css('padding-right',parseInt(width*0.05));

		}



	}

	jQuery(".rangofecha").on('change', function(e){

		var inicio = new Date(jQuery(this).val());

		inicio.setDate(inicio.getDate() + 2); // Suma 1 dia

		var fecha = inicio.getFullYear()+'-'+(('0'+(inicio.getMonth()+1)).substring(-2))+'-'+(('0'+inicio.getDate()).substring(-2));

		//console.log('Fecha mínima de finalización: '+fecha);

		var hasta = jQuery(this).attr('data-range');

		jQuery('#'+hasta).prop('min',fecha);

		jQuery('#'+hasta).val(fecha);

	});

	jQuery(".reservar, .conocer-cuidador").on('click', function(e){

		window.location.replace(jQuery(this).attr('data-target'));

	});

    

    jQuery(".videoYoutube").fancybox({

		maxWidth	: 800,

		maxHeight	: 600,

		fitToView	: false,

		width		: '70%',

		height		: '70%',

		autoSize	: false,

		closeClick	: false,

		openEffect	: 'none',

		closeEffect	: 'none',

        helpers : {

            overlay : {

                css : {

                    'background' : 'rgba(0, 0, 0, 0.75)'

                }

            }

        }

    });

    

    jQuery(".tooltip").live("touchstart", function() {

      $(this).addClass("active");

    }).live("touchend", function() {

      $(this).removeClass("active");

    });

    

    jQuery('.related.products > h2').html('Otros Servicios Similares');

/*

*   Llena el selector de municipios y estados

*/

/*

    var urlSever = 'https://kmimos.com.mx/wp-content/plugins/kmimos/app-server.php';

    var locs = jQuery("#ubicacion_cuidador");

    var pais =locs.attr("data-location");

    

    // Busca los estados del país

    jQuery.post(urlSever,{action: 'get-locations', location: pais },function(){

        locs.prop('disabled',true);

    }, "json")

    .success(function(data){

        jQuery.each(data, function(key,value){

            if(key.length==5) locs.append('<option value="'+key+'" class="edo">'+value+' (todo el estado)</option>');

            if(key.length==9) locs.append('<option value="'+key+'" class="mpo">'+value+'</option>');

        });

        locs.prop('disabled',false);

        console.log('Actualizado selector de estados y municipios');

    });

*/

/*    jQuery( "#ubicacion_cuidador" ).select2({

        tags: true,

        tokenSeparators: [',', ' '],

        minimumInputLength: 2

    });*/

    

//    jQuery(".select2-no-results").html('Por favor ingrese 2 o más caracteres');

    

//    jQuery( "#ubicacion_cuidador" ).combobox();

    

    jQuery('#wc-bookings-booking-form').prepend('<span class="label qty">1.- Ingrese cantidad de mascotas</span><br>');

    

    var servicios = jQuery('div.product-addon h3').attr('class');

    var servidor = window.location.protocol + "//" + window.location.host + "/"

    var dircarro = servidor+'/carro/';

    if(servicios != null){

        jQuery('.wc-bookings-date-picker-booking').append('<span class="label qty">4.- Seleccione servicios adicionales</span><br>');

    }

    

    //console.log('EXISTE: '+servicios);

    

    jQuery('.wc-bookings-booking-form-button').html('Continuar con tu reserva');

    

    jQuery('.product_cat-cuidadores.virtual form.cart').attr('action',dircarro);

    

    if(jQuery('.woocommerce-message a').attr('href')==dircarro)jQuery('.woocommerce-message').hide();

});

