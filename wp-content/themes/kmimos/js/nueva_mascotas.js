jQuery( document ).ready(function() {
	postJSON( 
  		"form_perfil",
       	URL_PROCESOS_PERFIL, 
       	function( data ) {
			jQuery("#btn_actualizar").val("Procesando...");
            jQuery("#btn_actualizar").attr("disabled", true);
			jQuery(".perfil_cargando").css("display", "inline-block");
       	}, 
       	function( data ) {
       		jQuery(".vlz_img_portada_valor").val("");

            jQuery("#form_perfil").closest('form').find("input[type=text], textarea, input[type=date]").val("");
            jQuery("#form_perfil").closest('form').find("select option[value='']").prop("selected", true);

            jQuery('#form_perfil .vlz_img_portada_fondo').css("background-image", "url("+IMG_DEFAULT+")");
            jQuery('#form_perfil .vlz_img_portada_normal').css("background-image", "url("+IMG_DEFAULT+")");

			jQuery("#btn_actualizar").val("Crear Mascota");
			jQuery("#btn_actualizar").attr("disabled", false);
            jQuery(".perfil_cargando").css("display", "none");

             var $mensaje="";

             console.log(data);

             var obj = jQuery.parseJSON( '{ "status": "OK" }' );
             console.log(obj.status);

            if( obj.status == "OK"){             

                $mensaje = "El registro de su mascota fue exitoso";

            }else{

                 $mensaje = "Lo sentimos no se pudo registrar a su mascota ";
            }

            console.log($mensaje);

            jQuery('#btn_actualizar').before('<br><span class="mensaje">'+$mensaje+'</span><br>');  

                  setTimeout(function() { 
                 jQuery('.mensaje').remove(); 

                    if( obj.status == "OK"){
                        location.href ="../";
                    }
              

            },3000); 
            
       	}
   	);
    initImg("portada");

    jQuery("#form_perfil [data-valid]").each(function( index ) {
        pre_validar( jQuery( this ) );
    });

    jQuery("#pet_type").on("change", function(e){
        var valor = jQuery("#pet_type").val();
        if( valor == "2605" ){
            var opciones = jQuery("#razas_perros").html();
            jQuery("#pet_breed").html(opciones);
        }
        if( valor == "2608" ){
            var opciones = jQuery("#razas_gatos").html();
            jQuery("#pet_breed").html(opciones);
        }
    });

    var minFecha = new Date();
    var min = minFecha.getFullYear();
    minFecha.setFullYear( parseInt(min)-30 );
    minFecha.setDate( parseInt(minFecha.getDate()) - 1);

    var maxFecha = new Date();
    maxFecha.setDate( parseInt(maxFecha.getDate()) - 1);

    jQuery("#pet_birthdate").datepick({
        dateFormat: 'dd/mm/yyyy',
        minDate: minFecha,
        maxDate: maxFecha,
        onSelect: function(date1) {
            
        },
        yearRange: minFecha.getFullYear()+':'+maxFecha.getFullYear(),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });

});