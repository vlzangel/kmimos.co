//FORM
var vsetTime=0;

// function form_subscribe(element){
//     var base = jQuery(element).closest('.subscribe').data('subscribe');
//     jQuery.post(url, jQuery(element).serialize(),function(data){
//         //console.log(data);
//     });
//     return false;
// }

function form_subscribe(element){
    var subscribe = jQuery(element).closest('.subscribe');
    var message = subscribe.find('.message');
    var base  = subscribe.data('subscribe');
    var url = base + '/subscribe/subscription.php';

    var obj_submit = subscribe.find('[type="submit"]');
    var text_submit = obj_submit.html();

    if( !obj_submit.hasClass("disabled") ){
        //obj_submit.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Guardando');
        obj_submit.addClass('disabled');
        jQuery.post(url, jQuery(element).serialize(),function(data){
            if(data['result']===true){
                if(message.length>0){
                    message.addClass('show');
                    message.html('<i class="icon fa fa-envelope"></i>'+data['message']+'');
                    vsetTime = setTimeout(function(){
                        message_subscribe(message);
                    }, 5000);
                }
                obj_submit.html(text_submit);
                obj_submit.removeClass('disabled');
            }else{
                obj_submit.html(text_submit);
                obj_submit.removeClass('disabled');
            }
            
            if( data['message'] == "Ha sido Registrado" ){
               
                fbq ('track','CompleteRegistration');
                fbq ('track','PopUpHome');

                jQuery.post( 
                    "https://www.kmimos.com.mx/landing-volaris/suscribir_home.php", 
                    {
                        "email": jQuery("#mail_suscripcion").val()
                    }, 
                    function( data ) {
                        console.log("Suscripción enviadas");
                    }
                );
            }
        });
    }
    return false;
}




function message_subscribe(element){
    clearTimeout(vsetTime);
    element.removeClass('show');
    element.html('');
    return true;
}




function SubscribePopUp_Create(html){
    var element = '#message.Msubscribe';
    if(jQuery(element).length==0){
        jQuery('body').append('<div id="message" class="Msubscribe"></div>');
        jQuery(element).append('<div class="contain"></div>');
    }

    jQuery(element).find('.contain').html(html);
    jQuery(element).fadeIn(500,function(){
        /*
         vsetTime = setTimeout(function(){
         SubscribePopUp_Close(element);
        }, 6000);
        */
    });
}

function SubscribePopUp_Close(element){
    if(jQuery(element).length>0){
        jQuery(element).fadeOut(500,function(){
            jQuery(element).remove();
        });
    }
}