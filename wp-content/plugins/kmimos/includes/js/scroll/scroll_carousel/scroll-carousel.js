//CAROUSEL DE ELEMENTOS

/*ESTRUCTURA
.contenido_carousel
.contenido_carousel_banner
.contenido_carousel_scroll
.contenido_carousel_elemento
*/

//ARREGLO DE COROUSEL
var seccion_arreglo=new Array();
/*SECCIONES
seccion_arreglo.portafolio=new Array();
seccion_arreglo.portafolio.cantidad=3;
seccion_arreglo.portafolio.proporcion=(3/4);
seccion_arreglo.portafolio.seleccion='.contenido_carousel_elemento:nth-child(1)';
seccion_arreglo.portafolio.arreglo='<?php echo json_encode($seccion_array); ?>';
//console.log(seccion_arreglo);
*/

function contenido_carousel_select(elemento){
	var elemento_base=$(elemento).parents('.contenido_carousel');
	var elemento_base_tipo=elemento_base.data('tipo');
	var elemento_contenedor=elemento_base.find('.contenido_carousel_elemento'); 
	var elemento_left=$(elemento).position().left; //.offset().left;
	
	$(elemento_contenedor).removeClass('select');
	$(elemento).addClass('select');
	 
	elemento_base.find('.contenido_carousel_scroll').css({'left':(-elemento_left)+'px'});
}


function contenido_carousel_navegar(elemento){
	var contenedor=$(elemento).parents('.contenido_carousel');
	var tipo=contenedor.data('tipo');
	var sentido=$(elemento).data('sentido'); 
	var elemento_select=contenedor.find('.contenido_carousel_elemento');
	var arreglo_select=contenedor.find(seccion_arreglo[tipo]['seleccion']);
	
	var cantidad=parseInt($(elemento_select).css('content').replace(/\D/g, ''), 10);
	var cantidad_elementos=$(elemento_select).length-1;
	
	if(sentido=='sig' && $(arreglo_select).next().length>0 && $(arreglo_select).index()<=(cantidad_elementos-cantidad)){// && !$(menu_elemento).eq(cantidad_elementos).is(':visible')
		seccion_arreglo[tipo]['seleccion']=$(arreglo_select).next();
	
	}else if(sentido=='ant' && arreglo_select.prev().length>0){
		seccion_arreglo[tipo]['seleccion']=$(arreglo_select).prev();
	}
	contenido_carousel_select(seccion_arreglo[tipo]['seleccion']);
}


function contenido_carousel(){
	var elemento=$('.contenido_carousel');
	var elemento_cantidad=$('.contenido_carousel').length;
	
	for(var carousel=0; carousel<elemento_cantidad; carousel++){
		var elemento_carousel=$(elemento).eq(carousel);
		var elemento_carousel_tipo=elemento_carousel.data('tipo');
		var elemento_carousel_proporcion=seccion_arreglo[elemento_carousel_tipo]['proporcion'];
		var elemento_carousel_seleccion=seccion_arreglo[elemento_carousel_tipo]['seleccion'];
		var elemento_carousel_contenedor=elemento_carousel.find('.contenido_carousel_elemento');
	
		var width=elemento_carousel.find('.contenido_carousel_banner').width();
		var cantidad=parseInt($(elemento_carousel_contenedor).css('content').replace(/\D/g, ''), 10);
		var carousel_margen_right=$(elemento_carousel_contenedor).css('margin-right').replace('px','')*1; 
		var carousel_margen_left=$(elemento_carousel_contenedor).css('margin-left').replace('px','')*1; 
		var carousel_border_right=$(elemento_carousel_contenedor).css('border-right-width').replace('px','')*1;
		var carousel_border_left=$(elemento_carousel_contenedor).css('border-left-width').replace('px','')*1;
		var carousel_navegar_width=elemento_carousel.find('.contenido_navegar .sentido').width()*2;
	
		var carousel_width=(width/cantidad)-(carousel_margen_right+carousel_margen_left+carousel_border_right+carousel_border_left);
		var carousel_height=carousel_width*elemento_carousel_proporcion;
		var carousel_cantidad=elemento_carousel_contenedor.length;
		
		elemento_carousel.find('.contenido_carousel_scroll').css({'width':(((width/cantidad)*carousel_cantidad)+100)+'px'});
		elemento_carousel_contenedor.css({'width':carousel_width+'px','height':carousel_height+'px','visibility':'visible'});
		contenido_carousel_select(elemento_carousel_seleccion);
	}	
}


$(document).ready(function(e){
	//$('.contenido_carousel').on('click','.contenido_navegar .sentido',function(e){ contenido_carousel_navegar(this); });
	$('.contenido_carousel').find('.contenido_navegar .sentido').click(function(e){ contenido_carousel_navegar(this); });
	contenido_carousel();
});


$(window).resize(function(e) {
	contenido_carousel();  
});