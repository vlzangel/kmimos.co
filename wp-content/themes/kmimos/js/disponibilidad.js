var fecha = new Date();
jQuery('#inicio').datepick(
    {
        dateFormat: 'dd/mm/yyyy',
        showTrigger: '#calImg',
        minDate: fecha,
        onSelect: seleccionar_checkin,
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1
    }
);

function seleccionar_checkin(date) {
    if( jQuery('#inicio').val() != '' ){
        var fecha = new Date();
        jQuery('#fin').attr('disabled', false);
        var ini = String( jQuery('#inicio').val() ).split('/');
        var fin = String( jQuery('#fin').val() ).split('/');
        var inicio = new Date( parseInt(ini[2]), parseInt(ini[1])-1, parseInt(ini[0]) );
        var fin = new Date( parseInt(fin[2]), parseInt(fin[1])-1, parseInt(fin[0]) );

        jQuery('#fin').removeClass('is-datepick');
        jQuery('#fin').datepick({
            dateFormat: 'dd/mm/yyyy',
            showTrigger: '#calImg',
            minDate: inicio,
            yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
            firstDay: 1
        });

        if( Math.abs(fin.getTime()) < Math.abs(inicio.getTime()) ){
            jQuery('#fin').datepick( 'setDate', jQuery('#inicio').val() );
        }
    }else{
        jQuery('#fin').val('');
        jQuery('#fin').attr('disabled', true);
    }
}

function volver_disponibilidad(){
    jQuery(".fechas").css("display", "none");
    jQuery(".tabla_disponibilidad_box").css("display", "block");
}

function editar_disponibilidad(){
    jQuery(".fechas").css("display", "block");
    jQuery(".tabla_disponibilidad_box").css("display", "none");
}

function guardar_disponibilidad(){

    var ini = jQuery("#inicio").val();
    var fin = jQuery("#fin").val();
    var user_id = jQuery("#user_id").val();

    if( ini == "" || fin == "" ){
        alert("Debes seleccionar las fechas primero");
    }else{
        jQuery.post(
            URL_PROCESOS_PERFIL, 
            {
                servicio: jQuery("#servicio").val(),
                inicio: ini,
                fin: fin,
                user_id: user_id,
                accion: "new_disponibilidad"
            },
            function(data){
                console.log(data);
                location.reload();
            },
            "json"
        );
    }
}

jQuery("#editar_disponibilidad").on("click", function(e){
    editar_disponibilidad();
});

jQuery("#volver_disponibilidad").on("click", function(e){
    volver_disponibilidad();
});

jQuery("#guardar_disponibilidad").on("click", function(e){
    guardar_disponibilidad();
});

jQuery(".vlz_cancelar").on("click", function(e){
    var valor = jQuery(this).attr("data-value");

    var confirmed = confirm("Esta seguro de liberar estos días?");
    if (confirmed == true) {
        
        var id  = jQuery(this).attr("data-id");
        var ini = jQuery(this).attr("data-inicio");
        var fin = jQuery(this).attr("data-fin");

        jQuery.post(
            URL_PROCESOS_PERFIL, 
            {
                servicio: id,
                inicio: ini,
                fin: fin,
                accion: "delete_disponibilidad"
            },
            function(data){
                location.reload();
            }
        );

    }
        
});