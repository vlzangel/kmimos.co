//EFECTO SCROLL
jQuery(document).scroll(function(e) {
	scroll_efecto();
});

jQuery(document).ready(function(e) {
    scroll_efecto();
});

function scroll_efecto(){
	var $blocks = jQuery('.scroll_animate:not(.scroll_visible)');
	var iblocks = 0;
	$blocks.each(function(i,elem){
		if(scroll_visible(jQuery(this))){
			//jQuery(this).addClass('scroll_visible');
			
			if(jQuery(this).hasClass('scroll_visible')){
				return;
			}else{
				var delay=0;
				if(jQuery(this).attr('data-delay')){
					iblocks++;	
					delay=parseFloat(jQuery(this).data('delay'));
				}
				jQuery(this).css({'transition-delay':(delay*iblocks)+'s'});
				jQuery(this).addClass('scroll_visible');			
			}
		}
	});
}