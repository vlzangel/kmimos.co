//EFECTO EASYLOAD
function easyload(){ 
	jQuery('.easyload:not(.easyloaded)').each(function(i,elem){
		if(scroll_visible(this)){
			var elemento=this;
			var img_src=jQuery(this).attr('src');
			var img_original=jQuery(this).data('original');
			if(!jQuery(this).hasClass('easyloaded')){//img_original!=img_src //console.log(img_original);
				var callback = function(image){ 
									jQuery(elemento).attr({'src':image});
									jQuery(elemento).addClass('easyloaded');  
								}
				preload_image(img_original,callback);
			}
			if(this.tagName != 'IMG'){//DIV console.log(this.tagName);
				var callback = function(image){ 
									jQuery(elemento).css({'background-image':'url('+image+')'});
									jQuery(elemento).addClass('easyloaded'); 
								}
				preload_image(img_original,callback);
				
			}
		}
	});
}

jQuery(document).ready(function(){
	easyload();
	jQuery(window).scroll(function(){
		easyload();
	});
});