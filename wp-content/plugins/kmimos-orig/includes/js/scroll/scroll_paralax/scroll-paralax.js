//EFECTO PARALAX
jQuery(document).ready(function(){
	jQuery(window).scroll(function(){
		jQuery('.paralax').each(function(i,elem){
			var paralax_factor=10;//-jQuery(this).offset().top
			var paralax_posicion=jQuery(window).scrollTop()/paralax_factor;
			var paralax_config={};
				paralax_config['transition']='all 0s';
				paralax_config['background-position']='50% 0';
				paralax_config['background-size']='cover';
				paralax_config['background-repeat']='repeat';
				//paralax_config['background-attachment']='fixed';
					
			if(scroll_visible(this)){
				if(jQuery(this).data('movimiento')){ 
					paralax_posicion=jQuery(window).scrollTop()/jQuery(this).data('movimiento');
				}
				jQuery(this).css(paralax_config);
				jQuery(this).css({'background-position':'50% -'+paralax_posicion+'px'});
			}else{
			}
		});
	});
	
});