<?php 
	include_once("vlz_geo.php"); 
	$L = geo("L");
	$N = geo("N");
	$S = geo("S");

	$scripts = '
		<script type="text/javascript" src="'.get_home_url().'/wp-content/themes/pointfinder/vlz/js/markerclusterer.js"></script>
		<script type="text/javascript" src="'.get_home_url().'/wp-content/themes/pointfinder/vlz/js/oms.min.js"></script>

		<script type="text/javascript">
			function vlz_select(id){
				if( jQuery("#"+id+" input").prop("checked") && jQuery("#"+id).hasClass("vlz_check_select")){
					jQuery("#"+id+" input").prop("checked", false);
					jQuery("#"+id).removeClass("vlz_check_select");
				}else{

					jQuery("#"+id+" input").prop("checked", true);
					jQuery("#"+id).addClass("vlz_check_select");
				}
			}
	';
	if( count($_POST['servicios']) > 0 ){
		foreach ($_POST['servicios'] as $key => $value) {
			$scripts .= "vlz_select('servicio_{$value}');";
		}
	}
		
	if( count($_POST['tamanos']) > 0 ){
		foreach ($_POST['tamanos'] as $key => $value) {
			$scripts .= "vlz_select('tamanos_{$value}');";
		}
	}

	$scripts .= '
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
			jQuery("html, body").animate({
		        scrollTop: 0
		    }, 500);
		}

		jQuery("#estados").on("change", function(e){
			var estado_id = jQuery("#estados").val();
		    if( estado_id != "" ){
		    	jQuery.getJSON( 
					"'.get_bloginfo( 'template_directory', 'display' ).'/vlz/ajax_municipios.php", 
					{estado: estado_id} 
				).done(
					function( data, textStatus, jqXHR ) {
				        var html = "<option value=\"\">Seleccione un municipio</option>";
				        jQuery.each(data, function(i, val) {
				            html += "<option value="+val.id+">"+val.name+"</option>";
				        });
				        jQuery("#municipios").html(html);
				    }
			    ).fail(
			    	function( jqXHR, textStatus, errorThrown ) {
				        console.log( "Error: " +  errorThrown );
					}
				);
		    }
		});

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
			
		jQuery("#orderby > option[value=\''.$_POST['orderby'].'\']").attr("selected", "selected"); 
		jQuery("#tipo_busqueda > option[value=\''.$_POST['tipo_busqueda'].'\']").attr("selected", "selected");
		vlz_tipo_ubicacion();


		function vlz_siguiente(){
			jQuery("#vlz_pagina").val( jQuery("#vlz_pagina").val()+1 );
			jQuery("#vlz_form_buscar").submit();
		}

		function vlz_anterior(){
			jQuery("#vlz_pagina").val( jQuery("#vlz_pagina").val()-1 );
			jQuery("#vlz_form_buscar").submit();
		}

		jQuery(".pficon-imageclick").on("click", function(){
			if(jQuery(this).attr("data-pf-link")){
				jQuery.prettyPhoto.open(jQuery(this).attr("data-pf-link"));
			}
		});

		var markers = [];
		var infos = [];
		var map;
		function initMap() {
		    map = new google.maps.Map(document.getElementById("mapa"), {
		        zoom: 3,
		        mapTypeId: google.maps.MapTypeId.ROADMAP,
	            fullscreenControl: true
		    });

	        var oms = new OverlappingMarkerSpiderfier(map, { 
		        markersWontMove: true,
		        markersWontHide: true,
		        basicFormatEvents: true
		  	});

		    var bounds = new google.maps.LatLngBounds();

		    jQuery.each(pines, function( index, cuidador ) {
		        bounds.extend( new google.maps.LatLng(cuidador.lat, cuidador.lng) );
		        markers[index] = new google.maps.Marker({ 
		            vlz_index: index,
		            map: map,
		            draggable: false,
		            position: new google.maps.LatLng(cuidador.lat, cuidador.lng),
		            icon: "'.get_home_url().'/wp-content/themes/pointfinder/vlz/js/images/n2.png"
		        });
		        infos[index] = new google.maps.InfoWindow({ 
		            content: \'<a class="mini_map" href="\'+cuidador.url+\'" target="_blank"> <img src="\'+cuidador.img+\'" style="max-width: 200px; max-height: 230px;"> <div>\'+cuidador.nom+\'</div> </a>\'
		        });
		        markers[index].addListener("click", function(e) { 
		            infos[this.vlz_index].open(map, this);
		        });

        		oms.addMarker(markers[index]);
		    });

		    var markerCluster = new MarkerClusterer(map, markers, {imagePath: "'.get_home_url().'/wp-content/themes/pointfinder/vlz/js/images/n"});
		    map.fitBounds(bounds);

		    minClusterZoom = 14;
		    markerCluster.setMaxZoom(minClusterZoom);
		    window.oms = oms;

		}

		(function(d, s){
			$ = d.createElement(s), e = d.getElementsByTagName(s)[0];
			$.async=!0;
			$.setAttribute("charset","utf-8");
			$.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&callback=initMap";
			$.type="text/javascript";
			e.parentNode.insertBefore($, e)
		})(document,"script");
/*
		function seleccionar_checkin() {
	        if( jQuery("#checkin").val() != "" ){
	            var fecha = new Date();
	            jQuery("#checkout").attr("disabled", false);
	            var ini = String( jQuery("#checkin").val() ).split("-");
	            var inicio = new Date( parseInt(ini[0]), parseInt(ini[1])-1, parseInt(ini[2]) );
	            var checkout = String( jQuery("#checkout").val() ).split("-");
	            if( jQuery("#checkout").val() != "" ){
	            	var checkout = new Date( checkout[0]+"-"+checkout[1]+"-"+checkout[2] );
	                if( Math.abs(checkout.getTime()) < Math.abs(inicio.getTime()) ){
	                    jQuery("#checkout").attr("value", ini[0]+"-"+ini[1]+"-"+ini[2] );
	                }
	            }else{
	                jQuery("#checkout").attr("value", ini[0]+"-"+ini[1]+"-"+ini[2] );
	            }
	                
	            jQuery("#checkout").attr("min", ini[0]+"-"+ini[1]+"-"+ini[2] );
	        }else{
	            jQuery("#checkout").val("");
	            jQuery("#checkout").attr("disabled", true);
	        }
	    }

	    jQuery("#checkin").on("change", function(e){
	        seleccionar_checkin();
	    });
*/
	</script>';

	//$SCRIPTS = comprimir_styles($scripts);
	$SCRIPTS = $scripts;