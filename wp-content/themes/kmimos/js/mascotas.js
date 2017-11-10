jQuery( document ).ready(function() {

    jQuery(".mascotas_delete").on("click", function(e){
        var pet_id = jQuery( this ).attr("data-img");
        if(!confirm("Esta seguro de eliminar la mascota.?") ) {
            return false;
        } else {
            
            jQuery.post(
                URL_PROCESOS_PERFIL, 
                {
                    accion: "delete_mascotas",
                    pet_id: pet_id
                },
                function(data){
                    location.reload();
                }
            );

            return false;
        }  
    });

    jQuery("#btn_actualizar").on("click", function(e){
        location.href = URL_NUEVA_IMG;
    });

    jQuery("#btn_actualizar").attr("type", "button");

});