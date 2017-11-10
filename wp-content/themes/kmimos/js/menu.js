function mostrar_menus(){
	jQuery(".menu").css("display", "block");
}
function ocultar_menus(){
	jQuery(".menu").css("display", "none");
}
jQuery( document ).ready(function() {
	jQuery("#menu_1").on("click", function(e){
		if( jQuery("#menu_usuario").css("display") == "block" ){
			jQuery("#menu_usuario").css("display", "none")
		}else{
			ocultar_menus();
			jQuery("#menu_usuario").css("display", "block")
		}
	});
	jQuery("#menu_2").on("click", function(e){
		if( jQuery("#menu_web").css("display") == "block" ){
			jQuery("#menu_web").css("display", "none")
		}else{
			ocultar_menus();
			jQuery("#menu_web").css("display", "block")
		}
	});
});


jQuery(window).resize(function(){
    if( jQuery( ".iconos_movil_box" ).css("display") != "flex" ){
    	mostrar_menus();
    }else{
    	ocultar_menus();
    }
});