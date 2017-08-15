var urlSever = 'http://kmimos.com.mx/wp-content/plugins/kmimos/app-server.php';

var marker;

var latDef=19.4132314;

var lngDef=-99.146425;

var zomDef=12;



jQuery.noConflict(); 

jQuery(document).ready(document).ready(function() {



	jQuery("#latitude_petsitter").on('change',initMap(jQuery("#latitude_petsitter").val(), jQuery("#longitude_petsitter").val()));

	jQuery("#longitude_petsitter").on('change',initMap(jQuery("#latitude_petsitter").val(), jQuery("#longitude_petsitter").val()));



	function initMap(latitud='', longitud='', zoom=12) {

		if(latitud=='' && longitud=='') return false;

		console.log('Nuevas coordenadas:'+latitud+','+longitud);

	  var map = new google.maps.Map(document.getElementById('map-canvas'), {

	    zoom: zoom,

	    center:  new google.maps.LatLng(latitud, longitud), 

 		mapTypeId: google.maps.MapTypeId.ROADMAP

	  });



		var target_latitud = jQuery("#map-canvas").attr('data-latitude'); 

		var target_longitud = jQuery("#map-canvas").attr('data-longitude'); 



	  marker = new google.maps.Marker({

	    map: map,

	    draggable: true,

	    animation: google.maps.Animation.DROP,

	    position: new google.maps.LatLng(latitud, longitud)

	  });



	  marker.addListener('click', toggleBounce);



	  google.maps.event.addListener(marker,'dragend', function(){

	  	console.log(target_latitud+':'+marker.getPosition().lat()+', '+target_longitud+':'+marker.getPosition().lng());

	  	jQuery("#"+target_latitud).val(marker.getPosition().lat());

	  	jQuery("#"+target_longitud).val(marker.getPosition().lng());

	  });

	}



	function toggleBounce() {

	  if (marker.getAnimation() !== null) {

	    marker.setAnimation(null);

	  } else {

	    marker.setAnimation(google.maps.Animation.BOUNCE);

	  }

	}

});

