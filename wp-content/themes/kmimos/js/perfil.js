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
                
            data = eval( "("+data+")");

            jQuery(".clv").val("");

            if( jQuery(".vlz_img_portada_valor").val() != "" ){
                jQuery(".menu_perfil .vlz_img_portada_fondo").css("background-image", "url("+RAIZ+"/wp-content/uploads/"+jQuery("#sub_path").val()+"/"+jQuery(".main .vlz_img_portada_valor").val()+")");
                jQuery(".menu_perfil .vlz_img_portada_normal").css("background-image", "url("+RAIZ+"/wp-content/uploads/"+jQuery("#sub_path").val()+"/"+jQuery(".main .vlz_img_portada_valor").val()+")");
            }

         		jQuery(".vlz_img_portada_valor").val("");
      			jQuery("#btn_actualizar").val("Actualizar");
      			jQuery("#btn_actualizar").attr("disabled", false);
            jQuery(".perfil_cargando").css("display", "none");

              var $mensaje="";

               if( data.status == "OK"){


                $mensaje = "Los datos fueron actualizados";

            }else{
                 $mensaje = "Lo sentimos no se pudo actualizar los datos";
            }

            jQuery('#btn_actualizar').before('<span class="mensaje">'+$mensaje+'</span>');  
          
                  setTimeout(function() { 
                  
  
                 jQuery('.mensaje').remove(); 
              

            },3000);

       
       	}
   	);
    initImg("portada");

    jQuery("#form_perfil [data-valid]").each(function( index ) {
        pre_validar( jQuery( this ) );
    });
});