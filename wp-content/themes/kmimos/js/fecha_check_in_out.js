jQuery(document).ready(function(){

    jQuery(function() {
        jQuery('input[readonly]').on('focus', function(ev) {
            jQuery(this).trigger('blur');
        });
    });

    function initCheckin(date, actual){
        if(actual){
            jQuery('#checkout').datepick({
                dateFormat: 'dd/mm/yyyy',
                defaultDate: date,
                selectDefaultDate: true,
                minDate: date,
                onSelect: function(xdate) {
                    if(typeof calcular === 'function') {
                        calcular();
                    }
                },
                yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
                firstDay: 1,
                onmonthsToShow: [1, 1]
            });
        }else{
            jQuery('#checkout').datepick({
                dateFormat: 'dd/mm/yyyy',
                minDate: date,
                onSelect: function(xdate) {
                    if(typeof calcular === 'function') {
                        calcular();
                    }
                },
                yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
                firstDay: 1,
                onmonthsToShow: [1, 1]
            });
        }
    }

    jQuery('#checkin').datepick({
        dateFormat: 'dd/mm/yyyy',
        minDate: fecha,
        onSelect: function(date1) {
            var ini = jQuery('#checkin').datepick( "getDate" );
            var fin = jQuery('#checkout').datepick( "getDate" );
            if( fin.length > 0 ){
                var xini = ini[0].getTime();
                var xfin = fin[0].getTime();
                if( xini > xfin ){
                    jQuery('#checkout').datepick('destroy');
                    initCheckin(date1[0], true);
                }else{
                    jQuery('#checkout').datepick('destroy');
                    initCheckin(date1[0], false);
                }
            }else{
                jQuery('#checkout').datepick('destroy');
                initCheckin(date1[0], true);
            }
            if(typeof calcular === 'function') {
                calcular();
            }
            if(typeof validar_busqueda_home === 'function') {
                validar_busqueda_home();
            }
        },
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });

    jQuery('#checkout').datepick({
        dateFormat: 'dd/mm/yyyy',
        minDate: fecha,
        onSelect: function(xdate) {
            if(typeof calcular === 'function') {
                calcular();
            }
        },
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });
});
