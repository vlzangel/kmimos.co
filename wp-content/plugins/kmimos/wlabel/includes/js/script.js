
function  WhiteLabel_form_login(form){
    var url=jQuery(form).data('validate');
    jQuery(form).find('.message').html('').css({'display':''}).removeClass('show');

    jQuery.post(url,jQuery(form).serialize(),function(data){console.log(data);
        data=jQuery.parseJSON(data);
        jQuery(form).find('.message').html(data['message']).css({'display':'block'}).addClass('show');
        //.delay(10000).removeClass('show');
    });
}


function WhiteLabel_panel_logout(element){
    var url=jQuery(element).data('logout');
    jQuery(element).html('Espere ...');
    jQuery.get(url, function(data){
        data=jQuery.parseJSON(data);
        jQuery(element).html(data['message']);
    });
}


function WhiteLabel_panel_export(element){
    var dexport=jQuery(element).closest('.export');
    var urlbase=dexport.data('urlbase');
    var module=dexport.data('module');
    var title=dexport.data('title');
    var type=dexport.data('type');
    var file=dexport.data('file');
    var url=urlbase+file;

    var data="";
    if(type=='table'){
        jQuery('table').each(function(e){
            jQuery(this).find('tr:not(.noshow)').each(function(e){
                jQuery(this).find('th, td').each(function(e){//
                    if(!jQuery(this).hasClass('noshow') && !jQuery(this).hasClass('noshow_check') && !jQuery(this).hasClass('noshow_select')){
                        data+=jQuery(this).html()+';';
                    }
                });
                data+="\n";
            });
            data+="\n\n\n";
        });
    }

    jQuery(element).html('Espere ...');
    jQuery.post(url, { module: module, title: title, data: data, urlbase: urlbase }, function(data){
        //console.log(data);
        data=jQuery.parseJSON(data);
        dexport.find('.action').html(data['message']);
        dexport.find('.file').html(data['file']);
    });
}



//MENU
function WhiteLabel_panel_menu(module){
    var path=jQuery('.section .menu').data('url');
    var url=path+'content/modules/'+module+'.php';
    jQuery.get(url, function(data){
        jQuery('.section .modules').html(data);
        modules_filter(jQuery('.filter select'));
        modules_filter(jQuery('.filter input'));
    });
}


//MODULES
jQuery(document).on('change click','.filter select, .filter input',function(e){
    modules_filter(this);
});

function modules_filter(element){
    var type = jQuery(element).closest('.type').data('type');
    var table = jQuery(element).closest('.section').find('table');

    if(type=='trdate'){
        modules_filter_trdate(element, type, table);
        return;
    }else if(type=='tddate'){
        modules_filter_tddate(element, type, table);
        return;
    }else if(type=='tdcheck'){
        modules_filter_tdcheck(element, type, table);
        return;
    }

}



function modules_filter_trdate(element, type, table){
    table.find('tbody tr.trshow').addClass('noshow');
    var month = jQuery(element).closest('.type').find('select[name="month"]').val();
    var year = jQuery(element).closest('.type').find('select[name="year"]').val();

    if(month !='' && year !=''){
        table.find('tbody tr.trshow[data-month="'+month+'"][data-year="'+year+'"]').removeClass('noshow');
    }else if(year !=''){
        table.find('tbody tr.trshow[data-year="'+year+'"]').removeClass('noshow');
    }else if(month !=''){
        table.find('tbody tr.trshow[data-month="'+month+'"]').removeClass('noshow');
    }else{
        table.find('tbody tr.trshow').removeClass('noshow');
    }
    modules_table_count(element);
    return;
}


function modules_filter_tddate(element, type, table){
    table.find('td.tdshow, th.tdshow').addClass('noshow_select');
    var month = jQuery(element).closest('.type').find('select[name="month"]').val();
    var year = jQuery(element).closest('.type').find('select[name="year"]').val();

    if(month !='' && year !=''){
        table.find('td.tdshow[data-month="'+month+'"][data-year="'+year+'"], th.tdshow[data-month="'+month+'"][data-year="'+year+'"]').removeClass('noshow_select');
    }else if(year !=''){
        table.find('td.tdshow[data-year="'+year+'"], th.tdshow[data-year="'+year+'"]').removeClass('noshow_select');
    }else if(month !=''){
        table.find('td.tdshow[data-month="'+month+'"], th.tdshow[data-month="'+month+'"]').removeClass('noshow_select');
    }else{
        table.find('td.tdshow, th.tdshow').removeClass('noshow_select');
    }
    return;
}


function modules_filter_tdcheck(element, type, table){
    jQuery(element).closest('.type').find('input[type="checkbox"]').each(function(index){
        var name = jQuery(this).attr('name');
        if(jQuery(this).is(':checked')) {// .val()=='yes'
            table.find('td[data-check="'+name+'"], th[data-check="'+name+'"]').removeClass('noshow_check');
        }else{
            table.find('td[data-check="'+name+'"], th[data-check="'+name+'"]').addClass('noshow_check');
        }
    });
    return;
}


function modules_table_count(element){
    var table = jQuery(element).closest('.section').find('table');
    var footer_td = table.find('tfoot tr td');
    var body_tr = table.find('tbody tr');

    jQuery(footer_td).each(function(index){
        if(jQuery(this).hasClass('count')){
            var indexftd=index;
            var count=0;

            jQuery(body_tr).each(function(index){
                if(!jQuery(this).hasClass('noshow')){
                    //if(jQuery(this).data('status')!='cancelled' && jQuery(this).data('status')!='modified'){}
                        count=count-(jQuery(this).find('td').eq(indexftd).html()*(-1));

                }
            });
        }

        count=Math.round(count*100)/100;
        //footer_td.eq(index).html(count);
        jQuery(this).html(count);
    });
}

jQuery(document).ready(function(e){
    jQuery('.filter select, .filter input').each(function(index){
        modules_filter(this);
    });

    jQuery('table').each(function(index){
        //modules_table_count(this);
    });
});