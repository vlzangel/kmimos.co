//VALIDAR SCROLL VISIBLE
function scroll_visible(elemento) {
	var elemento= jQuery(elemento);
	var docViewTop = jQuery(window).scrollTop();
	var docViewBottom = docViewTop + jQuery(window).height();
	var elemOffset = 0;
	var elemMAXview=100;//0
	
	if(elemento.data('offset')!=undefined){
		elemOffset = elemento.data('offset');
	}
	var elemTop = jQuery(elemento).offset().top;
	var elemBottom = elemTop + jQuery(elemento).height();
	
	if(elemOffset != 0) { 
		if(docViewTop - elemTop >= 0) {
		  elemTop = jQuery(elemento).offset().top + elemOffset;
		} else {
		  elemBottom = elemTop + jQuery(elemento).height() - elemOffset
		}
	}
	
	if(((elemBottom <= docViewBottom) || ((elemTop+elemMAXview) <= docViewBottom)) && ((elemTop >= docViewTop) || ((elemBottom-elemMAXview) >= docViewTop))){
		return true;
	}else{
		return false;
	}
}