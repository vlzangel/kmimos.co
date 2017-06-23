<?php 
	include_once("vlz_geo.php"); 
	$L = geo("L");
	$N = geo("N");
	$S = geo("S");

	echo "<!-- Hola -->";
	echo get_estados_municipios();
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

	function  initMap() {<?php
	
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
			
			$name_photo = get_user_meta($value['USER'], "name_photo", true);
			$cuidador_id = $value['ID'];

			if( empty($name_photo)  ){ $name_photo = "0"; }
			if( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}") ){
				$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}";
			}elseif( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg") ){
				$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg";
			}else{
				$img = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
			}

			$url = $value['url'];
			$nombre = $value['nombre'];
			$c = $value['ID'];

			echo "

				var point = new google.maps.LatLng('{$value['lat']}', '{$value['lng']}');
				bounds.extend(point);

				marker_{$c} = new google.maps.Marker({
					map: map,
					draggable: false,
					animation: google.maps.Animation.DROP,
					position: new google.maps.LatLng('{$value['lat']}', '{$value['lng']}'),
					icon: '".get_home_url()."/wp-content/themes/pointfinder"."/vlz/img/pin.png'
				});

				infowindow_{$c} = new google.maps.InfoWindow({ content: '<a class=\"mini_map\" href=\"{$url}\" target=\"_blank\"> <img src=\"{$img}\" style=\"max-width: 200px; max-height: 230px;\"> <div>{$nombre}</div> </a>' });

				marker_{$c}.addListener('click', function() { infowindow_{$c}.open(map, marker_{$c}); });
			";
					
		}

			if( count($coordenadas_all_2) > 0 ){ echo "map.fitBounds(bounds);"; }

		?>
	}

	jQuery("#estados").on("change", function(e){

		var estado_id = jQuery("#estados").val();            
	    
	    if( estado_id != "" ){

	        var html = "<option value=''>Seleccione un municipio</option>";
	        jQuery.each(estados_municipios[estado_id]['municipios'], function(i, val) {
	            html += "<option value="+val.id+" data-id='"+i+"'>"+val.nombre+"</option>";
	        });

	        jQuery("#municipios").html(html);

	        /*var location    = estados_municipios[estado_id]['coordenadas']['referencia'];
	        var norte       = estados_municipios[estado_id]['coordenadas']['norte'];
	        var sur         = estados_municipios[estado_id]['coordenadas']['sur'];

	        var distancia = calcular_rango_de_busqueda(norte, sur);

	        jQuery("#otra_latitud").attr("value", location.lat);
	        jQuery("#otra_longitud").attr("value", location.lng);
	        jQuery("#otra_distancia").attr("value", distancia);*/

	    }

	});

	jQuery("#municipios").on("change", function(e){
		vlz_coordenadas();
	});

	function vlz_coordenadas(){
		
		var estado_id = jQuery("#estados").val();            
        var municipio_id = jQuery('#municipios > option[value="'+jQuery("#municipios").val()+'"]').attr('data-id');   

        /*if( estado_id != "" ){

            var location    = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['referencia'];
            var norte       = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['norte'];
            var sur         = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['sur'];

            var distancia = calcular_rango_de_busqueda(norte, sur);

            jQuery("#otra_latitud").attr("value", location.lat);
            jQuery("#otra_longitud").attr("value", location.lng);
            jQuery("#otra_distancia").attr("value", distancia);

        }*/

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
						<?php 
					} ?>
				}); <?php 	
			}

			?>  <?php
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

	(function(d, s){
		$ = d.createElement(s), e = d.getElementsByTagName(s)[0];
		$.async=!0;
		$.setAttribute('charset','utf-8');
		$.src='//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&callback=initMap';
		$.type='text/javascript';
		e.parentNode.insertBefore($, e)
	})(document,'script');
	
</script>

<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&callback=initMap"> -->