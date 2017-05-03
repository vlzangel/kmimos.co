
var TIMEmap=0;
var kmimos_save_map = function(IDmap){
    //console.log(FILEkmimos_Map);
    //console.log(URLkmimos_Map);
    //console.log(PHPkmimos_Map);

    var map='';
    if(jQuery(IDmap).length>0){
        if(jQuery(IDmap).find('canvas').length>0){
            clearTimeout(TIMEmap);
            map=jQuery(IDmap).html();

        }else{
            TIMEmap=setTimeout( function(){ kmimos_save_map(IDmap); } ,1000);
            return;
        }
    }

    //console.log(map);
    jQuery.post(PHPkmimos_Map,{'kmimos_map':FILEkmimos_Map,'file':URLkmimos_Map,'map':map},function(data){
        //data=JSON.parse(data);
        //console.log(data);
    });

};


var kmimos_save_map_run = function(IDmap){
    //kmimos_save_map(IDmap);
};
