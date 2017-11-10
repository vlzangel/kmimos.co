var hasGPS=false;

(function(jQuery) {
    'use strict';
    
    jQuery('#servicios_adicionales').on('click', function () {
       jQuery('#servicios_adicionales').dropdown('show');
    });
    
    jQuery(document).on('click', '[data-action="validate"]', function(e){
        if( validar_busqueda_home() ){
            jQuery('#popup-servicios').modal('show');
        }
    });

    jQuery('[data-target="patitas-felices"]').on('click', function(){

        if( jQuery('#cp_email').val() != '' && jQuery('#cp_nombre').val() != ''){

            jQuery('#msg').html('Enviando solicitud.');
            jQuery('#cp_loading').removeClass('hidden');
            jQuery('#cp_loading').fadeIn(1500);

            jQuery.ajax( RAIZ+"landing/registro-usuario.php?email="+jQuery('#cp_email').val()+"&name="+jQuery('#cp_nombre').val()+"&referencia=kmimos-home" )
            .done(function(e) {
                var redirect = RAIZ+"/referidos/compartir/?e="+jQuery('#cp_email').val();
                switch (jQuery.trim(e)){
                    case '0':
                        jQuery('#msg').html('¡No pudimos completar su solicitud!');
                        break;
                    case '1':
                        jQuery('#msg').html('¡Felicidades, ya formas parte de nuestro Club!');
                        jQuery('a[data-redirect="patitas-felices"]').attr('href', redirect);
                        jQuery('a[data-redirect="patitas-felices"]').click();
                        window.open( redirect, '_blank' );
                        break;
                    case '2':
                        jQuery('#msg').html('¡Ya formas parte de nuestro Club!');
                        jQuery('a[data-redirect="patitas-felices"]').attr('href', redirect);
                        jQuery('a[data-redirect="patitas-felices"]').click();
                        window.open( redirect, '_blank' );
                        break;
                    default:
                        break;
                }
                setTimeout(function() {
                    jQuery('#cp_loading').fadeOut(1500);
                },3000);
            })
            .fail(function() {
                jQuery('#msg').html('Registro: No pudimos completar su solicitud, intente nuevamente');
                jQuery('#cp_loading').addClass('hidden');
            });  

        }else{
           
            var danger_color =  '#c71111';
            var border_color =  '#c71111';
            var visible = 'visible';
 
            jQuery('[data-error="cp_nombre"]').css('visibility', visible);
            jQuery('[data-error="cp_nombre"]').css('color', danger_color);
            jQuery('[data-error="cp_nombre"]').html(msg);
            jQuery('[name="cp_nombre"]').css('border-bottom', '1px solid ' + border_color);
            jQuery('[name="cp_nombre"]').css('color', danger_color);

            jQuery('[data-error="cp_email"]').css('visibility', visible);
            jQuery('[data-error="cp_email"]').css('color', danger_color);
            jQuery('[data-error="cp_email"]').html(msg);
            jQuery('[name="cp_email"]').css('border-bottom', '1px solid ' + border_color);
            jQuery('[name="cp_email"]').css('color', danger_color);

        }
    });

    jQuery('.adicionales_button').on('click', function(){
        if( jQuery('.modal_servicios').css('display') == 'none' ){
            jQuery('.modal_servicios').css('display', 'table');
        }else{
            jQuery('.modal_servicios').css('display', 'none');
        }
    });

    jQuery('#close_mas_servicios').on('click', function(){
        jQuery('.modal_servicios').css('display', 'none');
    });

    jQuery(function(){

        jQuery("#boton_buscar").on("click", function(e){
            jQuery("#buscador").submit();
        });

        jQuery("#close_video").on("click", function(e){
            close_video();
        });

        jQuery('.km-opcion').on('click', function(e) {
            jQuery(this).toggleClass('km-opcionactivo');
            jQuery(this).children("input:checkbox").prop("checked", !jQuery(this).children("input").prop("checked"));
        });

    });
})(jQuery);

window.addEventListener("load", loadBGVideoHOME);


function loadBGVideoHOME(){
    if( jQuery(window).width() >= 768 ){
        jQuery('.km-video-bg').html(
            '<div class="overlay"></div>'+
            '<video loop muted autoplay poster="'+HOME+'/images/new/km-hero-desktop.jpg" class="km-video-bgscreen">'+
                '<source src="'+HOME+'/images/new/videos/km-home/km-video.webm" type="video/webm">'+
                '<source src="'+HOME+'/images/new/videos/km-home/km-video.mp4" type="video/mp4">'+
                '<source src="'+HOME+'/images/new/videos/km-home/km-video.ogv" type="video/ogg">'+
            '</video>'
        );
    }else{
        jQuery('.km-video-bg').html('<div class="overlay"></div>');
    }
}

var fecha = new Date();
jQuery(document).ready(function(){
    
    jQuery('.bxslider').bxSlider({
        buildPager: function(slideIndex){
            switch(slideIndex){
                case 0:
                    return '<img src="'+HOME+'images/new/km-testimoniales/thumbs/testimonial-3.jpg">';
                case 1:
                    return '<img src="'+HOME+'images/new/km-testimoniales/thumbs/testimonial-2.jpg">';
                case 2:
                    return '<img src="'+HOME+'images/new/km-testimoniales/thumbs/testimonial-1.jpg">';
            }
        }
    });

    jQuery("#buscar").on("click", function ( e ) {
        e.preventDefault();
        jQuery("#buscador").submit();
    });

    jQuery("#buscar_no").on("click", function ( e ) {
        e.preventDefault();
        jQuery("#buscador").submit();
    });

    jQuery("#form_cuidador").submit(function(e){
        if( jQuery("#checkin").val() == "" ){
            jQuery("#checkin").css("border", "solid 1px red");
            jQuery("#checkout").css("border", "solid 1px red");
            jQuery(".validacion_fechas").css("display", "block");

            jQuery(".validacion_fechas").css("display", "block");
            jQuery(".km-ficha-fechas").css("margin-bottom", "0px");
            e.preventDefault();
        }
    });

    jQuery(".datepick td").on("click", function(e){
        jQuery( this ).children("a").click();
    });
});

function show_video(){
    jQuery(".modal_video iframe").attr("src", "https://www.youtube.com/embed/xjyAXaTzEhM?rel=0&showinfo=0&autoplay=1");
    jQuery(".modal_video").css("display", "table");
}

function close_video(){
    jQuery(".modal_video iframe").attr("src", "");
    jQuery(".modal_video").hide();
}

function validar_busqueda_home(){
    var IN  = validar( 'checkin' );
    var OUT = validar( 'checkout' );

    jQuery( '#checkin' ).parent().removeClass('has-error');
    jQuery( '[data-error="checkin"]' ).addClass('hidden');
    jQuery( '#checkout' ).parent().removeClass('has-error');
    jQuery( '[data-error="checkout"]' ).addClass('hidden');

    if( IN ){
        jQuery( '#checkin' ).parent().addClass('has-error');
        jQuery( '[data-error="checkin"]' ).removeClass('hidden');
    }
    if( OUT ){
        jQuery( '#checkout' ).parent().addClass('has-error');
        jQuery( '[data-error="checkin"]' ).removeClass('hidden');
    }

    if( !IN && !OUT ){
        return true;
    }
    return false;
}


jQuery(document).on('click', '[data-target="iframe-testimonio"]', function(){
    if( jQuery(this).data('video') != '' ){
        jQuery('#iframe-testimonio').attr( 'src', jQuery(this).data('video')+"?rel=0&amp;showinfo=0&amp;autoplay=1" );

        jQuery('#testimonio').css('margin-top', jQuery('nav').height());
        jQuery('#testimonio').modal('show');
    }
});

jQuery(document).on('click', '[data-target="close-testimonio"]', function (e) {
    stop_video();
});


jQuery(document).keyup(function(e) {
    if (e.keyCode == 27){
        stop_video();
    }
});

function stop_video(){
    jQuery('#iframe-testimonio').attr( 'src', 'http://');
    jQuery('#testimonio').modal('hide');

    console.log('stop video');
}
