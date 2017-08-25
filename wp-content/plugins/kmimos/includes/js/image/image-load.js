//CARGAR IMAGEN
function preload_image(image,callback){
	image_load = new Image();
	image_load.src = image;
	image_load.onload = function(){
		return callback(image);
    }
	
	if(image_load.complete){ 
		//image_load.onload();
	}
}

/*
//INSTRUNCCION
var image='URL';
var callback = function(image){
	jQuery('#menus').find('.fondo').css('background-image','url('+image+')');
}

preload_image(image,callback);
*/