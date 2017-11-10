jQuery(document).ready(function(){
    jQuery(document).on('change','select.redirect', function(e){
        var value=jQuery(this).val();
        if(value!=''){
            if(jQuery(this).find('option[value="'+value+'"]').hasClass('action_confirmed')){
                var confirmed = confirm("Esta Seguro de cancelar esta reserva?");
                if (confirmed == true) {
                    window.location.href = value;
                }
            }else{
                if(jQuery(this).find('option[value="'+value+'"]').hasClass('modified')){
                    var data = jQuery(this).val();
                    jQuery.post(
                        URL_PROCESOS_PERFIL, 
                        {
                            accion: "update_reserva",
                            data: data
                        },
                        function(resp){
                            console.log(resp);
                            location.href = RAIZ+resp.url;
                        }, 
                        'json'
                    ).fail(function(e) {
                        console.log( e );
                    });
                }else{
                    window.location.href = value;
                }
            }
        }
    });

    jQuery(".vlz_accion").on("click", function(e){
        var value = jQuery(this).attr("data-accion");
        if(value != ''){
            if(jQuery(this).hasClass('vlz_cancelar')){
                var confirmed = confirm("Esta Seguro de cancelar esta reserva?");
                if (confirmed == true) {
                    window.location.href = value;
                }
            }else{
                if(jQuery(this).hasClass('vlz_modificar')){
                    var data = value;
                    jQuery.post(
                        URL_PROCESOS_PERFIL, 
                        {
                            accion: "update_reserva",
                            data: data
                        },
                        function(resp){

                            if( resp.error != "" ){
                                var errores = {
                                    "cancelled": "fue cancelada anteriormente",
                                    /*"confirmed": "fue confirmada anteriormente",*/
                                    "modified": "fue modificada anteriormente"
                                };
                                alert( "La reserva "+errores[resp.error] );
                                location.reload();
                            }else{
                                location.href = RAIZ+resp.url;
                            }
                        }, 
                        'json'
                    ).fail(function(e) {
                        console.log( e );
                    });
                }else{
                    if(jQuery(this).hasClass('vlz_pdf')){
                        window.open(value, '_blank');
                    }else{
                        window.location.href = value;
                    }
                }
            }
        }
    });

    jQuery(".ver_reserva_init").on("click", function(e){
        jQuery(this).parent().parent().parent().addClass("vlz_desplegado");
    });

    jQuery(".ver_reserva_init_fuera").on("click", function(e){
        jQuery(this).parent().parent().addClass("vlz_desplegado");
    });

    jQuery(".ver_reserva_init_closet").on("click", function(e){

        jQuery(this).parent().removeClass("vlz_desplegado");
    });

});