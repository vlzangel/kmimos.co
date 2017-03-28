<?php 
	include_once("vlz_geo.php"); 
	$L = geo("L");
	$N = geo("N");
	$S = geo("S");
?>

<script type="text/javascript">

	function vlz_select(id){
		if( jQuery("#"+id+" input").prop("checked") ){
			jQuery("#"+id+" input").prop("checked", false);
			jQuery("#"+id).removeClass("vlz_check_select");
		}else{

			jQuery("#"+id+" input").prop("checked", true);
			jQuery("#"+id).addClass("vlz_check_select");
		}
	}
	
	<?php
		if( count($_POST['servicios']) > 0 ){
			foreach ($_POST['servicios'] as $key => $value) {
				echo "vlz_select('servicio_{$value}');";
			}
		}
			
		if( count($_POST['tamanos']) > 0 ){
			foreach ($_POST['tamanos'] as $key => $value) {
				echo "vlz_select('tamanos_{$value}');";
			}
		}
	?>

	jQuery(".vlz_sub_seccion_titulo").on("click", 
		function (){

			var con = jQuery(jQuery(this)[0].nextElementSibling);

			if( con.css("display") == "none" ){
				con.slideDown( "slow", function() { });
			}else{
				con.slideUp( "slow", function() { });
			}
			
		}
	);

	function vlz_top(){
		jQuery('html, body').animate({
	        scrollTop: 0
	    }, 500);
	}

	var map;

	<?php
		foreach ($coordenadas_all_2 as $value) {
			//if( geo("C", $value) ){
				echo "var infowindow_{$value['ID']}; var marker_{$value['ID']}; ";
			//}	
		}
	?>

	function initMap() { <?php 
	
		echo "
			var lat = '".$L['lat']."';
			var lon = '".$L['lng']."';

			map = new google.maps.Map(document.getElementById('mapa'), {
				zoom: 5,
				center:  new google.maps.LatLng(lat, lon), 
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});

			var bounds = new google.maps.LatLngBounds();
		";

		$c = 0;
		foreach ($coordenadas_all_2 as $value) {
			//if( geo("C", $value) ){

				if( $value->portada != '0' ){
					$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/{$value['ID']}/0.jpg";
				}else{
					$img = get_home_url().'/wp-content/themes/pointfinder/images/default.jpg';
				}

				$url = $value['url'];

				$nombre = $value['nombre'];

				$c = $value['ID'];

				echo "
					marker_{$c} = new google.maps.Marker({
						map: map,
						draggable: false,
						animation: google.maps.Animation.DROP,
						position: new google.maps.LatLng('{$value['lat']}', '{$value['lng']}'),
						icon: '".get_template_directory_uri()."/vlz/img/pin.png'
					});

					infowindow_{$c} = new google.maps.InfoWindow({ content: '<a class=\"mini_map\" href=\"{$url}\" target=\"_blank\"> <img src=\"{$img}\" style=\"max-width: 200px; max-height: 230px;\"> <div>{$nombre}</div> </a>' });

					marker_{$c}.addListener('click', function() { infowindow_{$c}.open(map, marker_{$c}); });
				";

			//}
					
		}

		echo "
			bounds.extend(
				new google.maps.LatLng(
			        parseFloat( {$N['lat']} ),
			        parseFloat( {$N['lng']} )
		        )
		    );

			bounds.extend(
				new google.maps.LatLng(
			        parseFloat( {$S['lat']} ),
			        parseFloat( {$S['lng']} )
		        )
		    );

			map.fitBounds(bounds);"; ?>
	}

	function vlz_ver_municipios(CB){
		var id =  jQuery("#estados").val();
		var txt = jQuery("#estados option:selected").text();
		if( id != "" ){
            var html = "";
            jQuery.each(locaciones[0][id], function(i, val) {
                html += "<option value="+val.id+">"+val.name+"</option>";
            });
            jQuery("#municipios").html("<option value=''>Seleccione una localidad</option>"+html);
            vlz_coordenadas();
        }else{
            jQuery("#municipios").html("<option value=''>Seleccione una ciudad primero</option>");
        }
}

	function vlz_coordenadas(CB){
		var estado = jQuery("#estados option:selected").text();
		var municipio_val = jQuery("#municipios option:selected").val();
		var municipio = jQuery("#municipios option:selected").text();
		var adress = "colombia+"+estado;
		if( municipio_val != "" ){ 
			adress+="+"+municipio; 
		}
		jQuery.ajax({ 
			url: 'https://maps.googleapis.com/maps/api/geocode/json?address='+adress+'&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8'
		}).done(function(data){

			if( data.results.length > 0 ){
                var location = data.results[0].geometry.location;
                var norte = data.results[0].geometry.viewport.northeast;
                var sur   = data.results[0].geometry.viewport.southwest;
                var distancia = calcular_rango_de_busqueda(norte, sur);
                jQuery("#otra_latitud").attr("value", location.lat);
                jQuery("#otra_longitud").attr("value", location.lng);
                jQuery("#otra_distancia").attr("value", distancia);
            }
		});
	} 

	function getLocation() {
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition);
	    }
	}
	function showPosition(position) {
		if( jQuery("#tipo_busqueda option:selected").val() == "mi-ubicacion" ){
			jQuery("#latitud").val(position.coords.latitude);
		    jQuery("#longitud").val(position.coords.longitude);
		}
	}

	function vlz_tipo_ubicacion(){
		if( jQuery("#tipo_busqueda option:selected").val() == "mi-ubicacion" ){
			jQuery("#vlz_estados").css("display", "none");
			jQuery("#vlz_inputs_coordenadas").css("display", "block");
		}else{
			jQuery("#vlz_estados").css("display", "block");
			jQuery("#vlz_inputs_coordenadas").css("display", "none");
		}
	}

	<?php 
		
		if( $_POST['tipo_busqueda'] == "otra-localidad" ){
			
			if( $_POST['estado'] != "" ){ ?>
				jQuery('#estados > option[value="<?php echo $_POST['estado']; ?>"]').attr('selected', 'selected');
				vlz_ver_municipios(function(){ <?php 
					if( $_POST['municipio'] != "" ){ ?>
						jQuery('#municipios > option[value="<?php echo $_POST['municipio']; ?>"]').attr('selected', 'selected');
						vlz_coordenadas(); <?php 
					} ?>
				}); <?php 	
			}

			?>  <?php
		}else{ ?>
			/*getLocation(); */ <?php
		}
	?>

	jQuery('#orderby > option[value="<?php echo $_POST['orderby']; ?>"]').attr('selected', 'selected'); 
	jQuery('#tipo_busqueda > option[value="<?php echo $_POST['tipo_busqueda']; ?>"]').attr('selected', 'selected');
	vlz_tipo_ubicacion();

	var toRadian = function (deg) {
	    return deg * Math.PI / 180;
	};

	function calcular_rango_de_busqueda(norte, sur){
		
		var d = ( 6371 * 
			Math.acos(
		    	Math.cos(
		    		toRadian(norte.lat)
		    	) * 
		    	Math.cos(
		    		toRadian(sur.lat)
		    	) * 
		    	Math.cos(
		    		toRadian(sur.lng) - 
		    		toRadian(norte.lng)
		    	) + 
		    	Math.sin(
		    		toRadian(norte.lat)
		    	) * 
		    	Math.sin(
		    		toRadian(sur.lat)
		    	)
		    )
	    );

		return d;
	}

	function vlz_siguiente(){
		jQuery("#vlz_pagina").val( jQuery("#vlz_pagina").val()+1 );
		jQuery("#vlz_form_buscar").submit();
	}

	function vlz_anterior(){
		jQuery("#vlz_pagina").val( jQuery("#vlz_pagina").val()-1 );
		jQuery("#vlz_form_buscar").submit();
	}

	jQuery(".pficon-imageclick").on("click", function(){
		if(jQuery(this).attr('data-pf-link')){
			jQuery.prettyPhoto.open(jQuery(this).attr('data-pf-link'));
		}
	});
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&callback=initMap"> </script> 
