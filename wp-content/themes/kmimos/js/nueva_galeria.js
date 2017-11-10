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
			jQuery("#btn_actualizar").val("Subir Foto");
			jQuery("#btn_actualizar").attr("disabled", false);
            jQuery(".perfil_cargando").css("display", "none");

             var $mensaje="";

             console.log(data);

             var obj = jQuery.parseJSON( '{ "status": "OK" }' );
             console.log(obj.status);

            if( obj.status == "OK"){             

                $mensaje = "Los datos  fueron actualizados";

            }else{

                 $mensaje = "Lo sentimos no se pudo actualizar los datos ";
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

});