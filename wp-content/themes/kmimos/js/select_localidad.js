var intervalo = 0;
var cant = 0;

jQuery(document).ready(function(){

    jQuery.post(
        HOME+"/procesos/busqueda/ubicacion.php",
        {},
        function(data){

            jQuery("#ubicacion_list").html(data);

            jQuery("#ubicacion_list li").on("click", function(e){

                if( jQuery(this).html() != "X" ){
                    jQuery("#ubicacion_txt").val( jQuery(this).html() );
                    jQuery("#ubicacion").val( jQuery(this).attr("value") );
                    jQuery("#ubicacion").attr( "data-value", jQuery(this).attr("data-value") );
                    jQuery("#ubicacion").attr( "data-txt", jQuery(this).html() );

                    jQuery( ".cerrar_list_box" ).css("display", "none");
                } 

                jQuery("#ubicacion_list").removeClass("ubicacion_list_hover"); 
            });
            jQuery("#ubicacion_txt").attr("readonly", false);
        }
    );

    jQuery("#ubicacion_txt").on("keyup", function ( e ) {
        buscarLocacion(String(jQuery("#ubicacion_txt").val()).toLowerCase());
    });

    jQuery("#ubicacion_txt").on("focus", function ( e ) { 
        jQuery("#ubicacion_list").addClass("ubicacion_list_hover");    
    });

    jQuery("#ubicacion_txt").on("change", function ( e ) {    
        var txt = getCleanedString( String(jQuery("#ubicacion_txt").val()).toLowerCase() );
        if( txt == "" ){
            jQuery("#ubicacion").val( "" );
            jQuery("#ubicacion").attr( "data-value", "" );
        }else{
            if( jQuery("#ubicacion").val() != "" ){
                if( jQuery("#ubicacion_txt").val() != jQuery("#ubicacion").val() ){
                    jQuery("#ubicacion_txt").val( jQuery("#ubicacion").attr( "data-txt" ) );
                }
            }
        }
    });

    jQuery(".cerrar_list").on("click", function(e){
        jQuery( ".cerrar_list_box" ).css("display", "none");
    });

    function buscarLocacion(txt){
        clearInterval(intervalo);
        intervalo = setInterval(function(){ 
            var buscar_1 = String(getCleanedString( txt )).trim();
            jQuery("#ubicacion_list li").css("display", "none");
            if( buscar_1 != "" ){
                cant = 0;
                jQuery("#ubicacion_list li").each(function( index ) {
                    if( String(jQuery( this ).attr("data-value")).toLowerCase().search(buscar_1) != -1 ){
                        jQuery( this ).css("display", "block");
                        cant++;
                    }
                });

                if( cant > 0 ){
                    jQuery( ".cerrar_list_box" ).css("display", "block");
                }else{
                    jQuery( ".cerrar_list_box" ).css("display", "none");
                }
            }
            clearInterval(intervalo); 
        }, 300);
            
    }

    function getCleanedString(cadena){
        cadena = cadena.toLowerCase();
        cadena = cadena.replace(/ /g," ");
        cadena = cadena.replace(/á/gi,"a");
        cadena = cadena.replace(/é/gi,"e");
        cadena = cadena.replace(/í/gi,"i");
        cadena = cadena.replace(/ó/gi,"o");
        cadena = cadena.replace(/ú/gi,"u");
        cadena = cadena.replace(/ñ/gi,"n");
        cadena = cadena.replace(/,/gi,"");
        return cadena;
    }

});

