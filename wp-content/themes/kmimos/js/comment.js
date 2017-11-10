jQuery("#commentform").submit(function(e){
    if( jQuery("#g-recaptcha-response").val() == "" ){
        event.preventDefault();
        alert( "Debes validar el CAPTCHA para continuar." );
        return false;
    }

    if( !jQuery("#submit").hasClass("disable") ){
        jQuery("#submit").addClass("disable");

        var result = getAjaxData('/procesos/generales/comment.php','post', jQuery(this).serialize());
        
        result = jQuery.parseJSON(result);

        if(result['result']=='success'){
            jQuery("#comment").val("");
            jQuery("#author").val("");
            jQuery("#email").val("");
            jQuery('.BoxComment').fadeOut();
            GetComments();
            jQuery("#submit").removeClass("disable");
        }else if(result['result']=='error'){
            alert(result['message']);
        }
    }

});

function GetComments(){
    var data = getAjaxData('/procesos/cuidador/comentarios.php','post', {servicio: SERVICIO_ID});
    comentarios_cuidador = jQuery.parseJSON(data);
    comentarios();
}

