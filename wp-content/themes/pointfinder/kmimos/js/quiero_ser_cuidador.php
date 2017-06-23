<script>

	<?php
		$campos = array();

		$campos["nombres"] = "text";
		$campos["apellidos"] = "text";
		$campos["ife"] = "text";
		$campos["telefono"] = "text";
		$campos["referido"] = "list";
		$campos["descripcion"] = "text";
		$campos["vlz_img_perfil"] = "text";
		$campos["username"] = "text";
		$campos["email"] = "text";
		$campos["clave"] = "text";
		$campos["clave2"] = "text";
		$campos["estado"] = "list_dinamica";
		$campos["municipio"] = "list";
		$campos["direccion"] = "text";
		$campos["latitud"] = "text";
		$campos["longitud"] = "text";
		$campos["num_mascotas_casa"] = "text";
		$campos["num_mascotas_aceptadas"] = "list";
		$campos["cuidando_desde"] = "list";
		$campos["emergencia"] = "list";

		foreach ($tam as $key => $value) {
			$campos["tengo_".$key] = "check";
			$campos["acepto_".$key] = "check";
			$campos["hospedaje_".$key] = "text";
		}

		foreach ($Comportamientos as $key => $value) {
			$campos["comportamiento_".$key] = "check";
		}

		$campos["cachorros"] = "check";
		$campos["adultos"] = "check";
		$campos["esterilizado"] = "check";
		$campos["tipo_propiedad"] = "list";
		$campos["areas_verdes"] = "check";
		$campos["tiene_patio"] = "check";
		$campos["entrada"] = "list";
		$campos["salida"] = "list";

		$campos["portada"] = "";
		$campos["terminos"] = "check";

		$campos["vlz_img_perfil"] = "img";

		/*
		$campos[""] = "text";
		$campos[""] = "text";
		$campos[""] = "text";
		*/

		$datos_json = json_encode($campos, JSON_UNESCAPED_UNICODE );
		echo "
			var xcampos_form = jQuery.makeArray(
			eval(
				'(".$datos_json.")'
				)
			);
			var campos_form = xcampos_form[0] ;
		";
	?>

		function get_cookie(name){
			return jQuery.cookie("CR_"+name);
		}

		function set_cookie(name, valor){
			jQuery.cookie("CR_"+name, valor);
		}

		function borrar_cookie(name){
			jQuery.removeCookie("CR_"+name);
		}

		function verificar_cache_form(){

		  	jQuery.each(campos_form, function( id, tipo ) {
				
		  		var valor = get_cookie(id);

		  		if( valor != undefined ){
					switch( tipo ){
						case "text":
							jQuery("#"+id).attr("value", valor );
						break;
						case "list":
							jQuery('#'+id+' > option[value="'+valor+'"]').attr('selected', 'selected');
						break;
						case "list_dinamica":
							jQuery('#'+id+' > option[value="'+valor+'"]').attr('selected', 'selected');
							jQuery('#'+id).change();
						break;
						case "check":
							
							if( valor == 1 ){
								jQuery("#"+id).parent().click();
							}

						break;
						case "img":
							jQuery(".vlz_img_portada_fondo").css("background-image", "url(<?php echo get_home_url()."/imgs/Temp/"; ?>"+valor+")");
		        			jQuery(".vlz_img_portada_normal").css("background-image", "url(<?php echo get_home_url()."/imgs/Temp/"; ?>"+valor+")");
		        			jQuery("#vlz_img_perfil").attr("value", valor);
		        			jQuery("#error_vlz_img_perfil").css("display", "none");
						break;
					}
		  		}
			});

		}

	// Carga y optimización de la carga de imagenes

		jQuery( document ).ready(function() {
		  	verificar_cache_form();
		});

		function vista_previa(evt) {
		  	var files = evt.target.files;
		  	for (var i = 0, f; f = files[i]; i++) {  
		       	if (!f.type.match("image.*")) {
		            continue;
		       	}
		       	var reader = new FileReader();
		       	reader.onload = (function(theFile) {
		           return function(e) {
		           		jQuery(".kmimos_cargando").css("display", "block");
		    			redimencionar(e.target.result, function(img_reducida){
		    				var a = "<?php echo get_home_url()."/imgs/vlz_subir_img.php"; ?>";
		    				var img_pre = jQuery("#vlz_img_perfil").val();
		    				jQuery.post( a, {img: img_reducida, previa: img_pre}, function( url ) {
					      		jQuery(".vlz_img_portada_fondo").css("background-image", "url(<?php echo get_home_url()."/imgs/Temp/"; ?>"+url+")");
			        			jQuery(".vlz_img_portada_normal").css("background-image", "url(<?php echo get_home_url()."/imgs/Temp/"; ?>"+url+")");
			        			jQuery("#vlz_img_perfil").attr("value", url);
			        			jQuery("#error_vlz_img_perfil").css("display", "none");
			           			jQuery(".kmimos_cargando").css("display", "none");
			           			set_cookie("vlz_img_perfil", jQuery("#vlz_img_perfil").attr("value") );
			           			jQuery("#portada").val("");
					      	});
		    			});
		           };
		       })(f);
		       reader.readAsDataURL(f);
		   	}
		}      
		document.getElementById("portada").addEventListener("change", vista_previa, false);

	// Funciones de Validación

		function especiales(id){
			switch(id){
				case "ife":
		      		var ife = jQuery( "#ife" ).val();
		      		if( ife.length == 13 ){
		      			return true;
		      		}else{
		      			ver_error(id);
		      			return false;
		      		}
				break;
				case "telefono":
		      		var telefono = jQuery( "#telefono" ).val();
		      		if( telefono.length >= 7 ){
		      			return true;
		      		}else{
		      			ver_error(id);
		      			return false;
		      		}
				break;
				case "clave":
		      		var clv1 = jQuery("#clave").attr("value");
		      		var clv2 = jQuery("#clave2").attr("value");

		      		if( clv1 == clv2 ){
		      			return true;
		      		}else{
		      			ver_error(id);
		      			return false;
		      		}
				break;
				case "clave2":
		      		var clv1 = jQuery("#clave").attr("value");
		      		var clv2 = jQuery("#clave2").attr("value");

		      		if( clv1 == clv2 ){
		      			return true;
		      		}else{
		      			ver_error(id);
		      			return false;
		      		}
				break;
				case "hospedaje":
		      		var z = 0;
					var t = [
						'pequenos',
						'medianos',
						'grandes',
						'gigantes'
					];

					jQuery.each(t, function( index, value ) {
						var temp = jQuery('#hospedaje_'+value).attr('value');
						if( temp == '' ){ temp = 0; }
						z += parseInt( temp );	
					});

					if( z == 0 ){
						jQuery('#error_hospedaje').attr('class', 'error');
					}else{
						jQuery('#error_hospedaje').attr('class', 'no_error');
					}

					return ( z == 0 );
				break;
				default:
					return true;
				break;
			}
		}

		function clvs_iguales(e){
			var clv1 = jQuery("#clave").attr("value");
			var clv2 = jQuery("#clave2").attr("value");
			if( clv1 == clv2 ){
				jQuery("#error_clave").removeClass("error");
				jQuery("#error_clave").addClass("no_error");
				jQuery("#clave").removeClass("vlz_input_error");
				jQuery("#error_clave2").removeClass("error");
				jQuery("#error_clave2").addClass("no_error");
				jQuery("#clave2").removeClass("vlz_input_error");
			}else{
				jQuery("#error_clave").removeClass("no_error");
				jQuery("#error_clave").addClass("error");
				jQuery("#clave").addClass("vlz_input_error");
				jQuery("#error_clave2").removeClass("no_error");
				jQuery("#error_clave2").addClass("error");
				jQuery("#clave2").addClass("vlz_input_error");
			}
		}

		function vlz_validar(){
			var error = 0;

			if( !form.checkValidity() )		{ error++; }
			if( !especiales("clave") )		{ error++; }
			if(  especiales("hospedaje") )	{ error++; }
			if( !especiales("ife") )		{ error++; }
			if( !especiales("telefono") )	{ error++; }

			if( error > 0 ){
				var primer_error = ""; var z = true;
				jQuery( ".error" ).each(function() {
				  	if( jQuery( this ).css( "display" ) == "block" ){
				  		if( z ){
				  			primer_error = "#"+jQuery( this ).attr("data-id"); z = false;
				  		}
				  	}
				});

				jQuery('html, body').animate({ scrollTop: jQuery(primer_error).offset().top-75 }, 2000);
			}else{
				vlz_modal('terminos', 'Términos y Condiciones');
			}

		}

	// Eventos validación

		var form = document.getElementById('vlz_form_nuevo_cuidador');
		form.addEventListener( 'invalid', function(event){
		    event.preventDefault();
		    jQuery("#error_"+event.target.id).removeClass("no_error");
		    jQuery("#error_"+event.target.id).addClass("error");
		    jQuery("#"+event.target.id).addClass("vlz_input_error");
		    console.log(event.target.id);
		}, true);

		form.addEventListener( 'keyup', function(event){
		    if ( event.target.validity.valid && especiales(event.target.id)  ) {
		    	if( jQuery("#error_"+event.target.id).hasClass( "error" ) ){
		    		jQuery("#error_"+event.target.id).removeClass("error");
		        	jQuery("#error_"+event.target.id).addClass("no_error");
		        	jQuery("#"+event.target.id).removeClass("vlz_input_error");
		    	}
		    } else {
		    	if( jQuery("#error_"+event.target.id).hasClass( "no_error" ) ){
		    		jQuery("#error_"+event.target.id).removeClass("no_error");
		        	jQuery("#error_"+event.target.id).addClass("error");
		        	jQuery("#"+event.target.id).addClass("vlz_input_error");
		    	} 
		    }

			set_cookie(event.target.id, jQuery("#"+event.target.id).attr("value") );
		
		}, true);

		form.addEventListener( 'change', function(event){
		    if ( event.target.validity.valid && especiales(event.target.id) ) {
		    	if( jQuery("#error_"+event.target.id).hasClass( "error" ) ){
		    		jQuery("#error_"+event.target.id).removeClass("error");
		        	jQuery("#error_"+event.target.id).addClass("no_error");
		        	jQuery("#"+event.target.id).removeClass("vlz_input_error");
		    	}
		    } else {
		    	if( jQuery("#error_"+event.target.id).hasClass( "no_error" ) ){
		    		jQuery("#error_"+event.target.id).removeClass("no_error");
		        	jQuery("#error_"+event.target.id).addClass("error");
		        	jQuery("#"+event.target.id).addClass("vlz_input_error");
		    	} 
		    }

			set_cookie(event.target.id, jQuery("#"+event.target.id).attr("value") );
		}, true);

		jQuery( "#clave" ).keyup(clvs_iguales);
		jQuery( "#clave2" ).keyup(clvs_iguales);

		function kmiayuda_show(id){
			jQuery("#"+id).css("border-radius", "4px 4px 0px 0px");
		    jQuery("#"+id).css("border-bottom", "0");
		    jQuery("#error_"+id).css("border-bottom", "0");
		    jQuery("#error_"+id).css("border-radius", "0px");
		    jQuery("#kmiayuda_"+id).addClass("kmiayuda");
		    jQuery("#kmiayuda_"+id).removeClass("no_kmiayuda");
		}

		function kmiayuda_hide(id){
			jQuery("#"+id).css("border-radius", "4px");
		    jQuery("#"+id).css("border-bottom", "1px solid #CCC");
		    jQuery("#error_"+id).css("border-bottom", "1px solid #CCC");
		    jQuery("#error_"+id).css("border-radius", "0px 0px 4px 4px");
		    jQuery("#kmiayuda_"+id).addClass("no_kmiayuda");
		    jQuery("#kmiayuda_"+id).removeClass("kmiayuda");
		}

		jQuery(".vlz_input").on( 'focus', function(event){
			var txt = jQuery( this ).attr("data-help");
		  	if( txt != "" && txt != undefined ){
			    kmiayuda_show(event.target.id);
			}
		});

		jQuery(".vlz_input").on( 'blur', function(event){
			var txt = jQuery( this ).attr("data-help");
		  	if( txt != "" && txt != undefined ){
			    kmiayuda_hide(event.target.id);
			}
		});

		jQuery( document ).ready(function() {
		  	kmiayuda_show("vlz_img_perfil");
		  	kmiayuda_show("entrada");
		  	kmiayuda_show("salida");
		});

	// Modificar el DOM

		function ver_error(id){
			jQuery("#error_"+id).removeClass("no_error");
        	jQuery("#error_"+id).addClass("error");
        	jQuery("#"+id).addClass("vlz_input_error");
		}

		jQuery(".vlz_input").each(function( index ) {
		  	var error = jQuery("<div class='no_error' id='error_"+( jQuery( this ).attr('id') )+"' data-id='"+( jQuery( this ).attr('id') )+"'></div>");
		  	var txt = jQuery( this ).attr("data-title");
		  	if( txt == "" || txt == undefined ){ txt = "Completa este campo."; }
		  	error.html( txt );
		  	jQuery( this ).parent().append( error );
		});

		jQuery(".vlz_input").each(function( index ) {
		  	var txt = jQuery( this ).attr("data-help");
		  	if( txt != "" && txt != undefined ){
			  	var ayuda = jQuery("<div class='no_kmiayuda' id='kmiayuda_"+( jQuery( this ).attr('id') )+"' data-id='"+( jQuery( this ).attr('id') )+"'></div>");
			  	ayuda.html( txt );
			  	jQuery( this ).parent().append( ayuda );
		  	}
		});

		jQuery(".vlz_pin_check").on("click", function(){
			if( jQuery("input", this).attr("value") == "0" ){
				jQuery("input", this).attr("value", "1");
				jQuery(this).removeClass("vlz_no_check");
				jQuery(this).addClass("vlz_check");
			}else{
				jQuery("input", this).attr("value", "0");
				jQuery(this).removeClass("vlz_check");
				jQuery(this).addClass("vlz_no_check");
			}

			set_cookie(jQuery("input", this).attr("id"), jQuery("input", this).attr("value") );
		});

		jQuery(".vlz_boton_agregar").on("click", function(){
			jQuery(".vlz_boton_quitar").off("click");
			var servicios = jQuery('<select class="vlz_input" id="servicio[]" name="servicio[]"><option value="8">Guarder&iacute;a (Cuidado durante el d&iacute;a)</option><option value="9">Adiestramiento de obediencia b&aacute;sico</option><option value="10">Adiestramiento de obediencia intermedio</option><option value="11">Adiestramiento de obediencia avanzado</option><option value="12">Paseos</option></select>');
			var contCampos = jQuery("<div>", {"class": "vlz_cell66 jj_input_cell00"});
			<?php
			$tam = array(
				"pequenos" => "Peque&ntilde;os",
				"medianos" => "Medianos",
				"grandes"  => "Grandes",
				"gigantes" => "Gigantes",
			);
			$txts = array(
				"pequenos" => "",
				"medianos" => "",
				"grandes"  => "",
				"gigantes" => ""
			);
				foreach ($tam as $key => $value) {
				echo "var ".$key." = jQuery(\"<div class='vlz_cell25'><input type='text' id='adicional_".$key."' name='adicional_".$key."[]' class='vlz_input' placeholder='".$value."' title='".$txts[$key]."'><div class='no_error' id='error_adicional_".$key."'></div></div>\");";
				echo "jQuery(contCampos).append( ".$key." );";
			}
			?>
			
			var contBoton = jQuery("<div>", {"class": "vlz_cell25"});
				jQuery(contBoton).append( jQuery("<div>", {"class": "vlz_boton_quitar"}) );

			var lista = jQuery("<div>", {"class": "vlz_cell75 jj_input_cell00"});
				jQuery(lista).append( servicios );

			var contLista = jQuery("<div>", {"class": "vlz_cell33 jj_input_cell00"});
			jQuery(contLista).append( contBoton );
			jQuery(contLista).append( lista );

			var newItem = jQuery("<div>", {"class": "vlz_sub_seccion"});
			jQuery(newItem).append( contLista );
			jQuery(newItem).append( contCampos );

			jQuery( ".vlz_contenedor_adicionales" ).append( newItem );

			jQuery(".vlz_boton_quitar").on("click", function(){
				jQuery(this).parent().parent().parent().remove();
			});

		});

	/* DIRECCIONES */

		jQuery("#estado").on("change", function(e){
			var estado_id = jQuery("#estado").val(); 
		    if( estado_id != "" ){
		        var html = "<option value=''>Seleccione un municipio</option>";
		        jQuery.each(estados_municipios[estado_id]['municipios'], function(i, val) {
		            html += "<option value="+val.id+" data-id='"+i+"'>"+val.nombre+"</option>";
		        });
		        jQuery("#municipio").html(html);
		        /*var location    = estados_municipios[estado_id]['coordenadas']['referencia'];
		        var norte       = estados_municipios[estado_id]['coordenadas']['norte'];
		        var sur         = estados_municipios[estado_id]['coordenadas']['sur'];
		        jQuery("#latitud").attr("value", location.lat);
		        jQuery("#longitud").attr("value", location.lng);*/
		    }
		});

		jQuery("#municipio").on("change", function(e){
			vlz_coordenadas();
		});

		function vlz_coordenadas(){
			var estado_id = jQuery("#estado").val();            
		    var municipio_id = jQuery('#municipio > option[value="'+jQuery("#municipio").val()+'"]').attr('data-id');   
		    /*if( estado_id != "" ){
		        var location    = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['referencia'];
		        var norte       = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['norte'];
		        var sur         = estados_municipios[estado_id]['municipios'][municipio_id]['coordenadas']['sur'];
		        jQuery("#latitud").attr("value", location.lat);
		        jQuery("#longitud").attr("value", location.lng);
		    }*/
		}

	// Generales
		function GoToHomePage(){
			// location = 'http://kmimos.ilernus.com';  
			// location = "<?php echo get_home_url().'/perfil-usuario/?ua=profile'; ?>";  
			location = location.protocol+"//"+location.host+"/perfil-usuario/?ua=profile";
		}
			
		function vlz_modal(tipo, titulo, contenido){
			switch(tipo){
				case "terminos":
					jQuery("#vlz_titulo_registro").html(titulo);
					jQuery("#terminos_y_condiciones").css("display", "table");
					jQuery("#boton_registrar_modal").css("display", "inline-block");
				break;
			}
		}

	//MODAL PRECIOS SUGERIDOS-----------------------------------------------------------------------------------------------

		var modalOpend = true;
		jQuery(window).scroll(function() {
			if( jQuery(window).width() < 550 ) {
				var trigger_precios = jQuery("#trigger_precios").offset().top-200;
			}else{
				var trigger_precios = jQuery("#trigger_precios").offset().top-500;
			}
		    if(jQuery(document).scrollTop() > trigger_precios){
			    if(modalOpend){
			    	jQuery('#jj_modal').fadeIn();
			       	modalOpend = false;
			    }
		     }else{
		      	jQuery('#jj_modal').fadeOut();
		     }
		});

		function ocultarModal(){
			jQuery('#jj_modal').fadeOut();
			jQuery('#jj_modal').css('display', 'none');
			modalOpend = false;
		}

	// Envio de formulario

		jQuery("#vlz_form_nuevo_cuidador").submit(function(e){
			jQuery("#vlz_modal_cerrar_registrar").attr("onclick", "");
			if( form.checkValidity() ){
		    	var terminos = jQuery("#terminos").attr("value");
				if( terminos == 1){
					var a = "<?php echo get_home_url()."/wp-content/themes/pointfinder/kmimos/registro_cuidador/vlz_procesar.php"; ?>";
			  		jQuery("#vlz_contenedor_botones").css("display", "none");
			  		jQuery(".vlz_modal_contenido").css("display", "none");
			  		jQuery("#vlz_cargando").css("display", "block");
			  		jQuery("#vlz_cargando").css("height", "auto");
			  		jQuery("#vlz_cargando").css("text-align", "center");
			  		jQuery("#vlz_cargando").html("<h2>Registrando, por favor espere...</h2>");
			  		jQuery("#vlz_titulo_registro").html("Registrando, por favor espere...");
			     	
					jQuery.post( a, jQuery("#vlz_form_nuevo_cuidador").serialize(), function( data ) {
			      		data = eval(data);
			      		if( data.error == "SI" ){
			      			jQuery('html, body').animate({ scrollTop: jQuery("#email").offset().top-75 }, 2000);
			      			alert(data.msg);
			      			jQuery("#terminos_y_condiciones").css("display", "none");
			      			jQuery("#vlz_contenedor_botones").css("display", "block");
				      		jQuery(".vlz_modal_contenido").css("display", "block");
				      		jQuery("#terminos").css("display", "block");
				      		jQuery("#vlz_cargando").css("height", "auto");
				      		jQuery("#vlz_cargando").css("display", "none");
				      		jQuery("#vlz_cargando").css("text-align", "justify");
				      		jQuery("#vlz_titulo_registro").html('Términos y Condiciones');
			  				jQuery("#boton_registrar_modal").css("display", "inline-block");
			      		}else{
			      			jQuery("#vlz_titulo_registro").html("Registro Completado!");
						  	jQuery("#vlz_cargando").html(data.msg);
				      		jQuery("#vlz_registro_cuidador_cerrar").css("display", "inline-block");
				      		<?php
				      			if( substr($_SERVER["HTTP_REFERER"], -18) == "nuevos-aspirantes/" ){
				      				$_SESSION['nuevosAspirantes'] = "SI";
				      			}

				      			if( isset($_SESSION['nuevosAspirantes']) ){
				      				echo "_gaq.push(['_trackEvent','registro_cuidador','click','aspirantes','1']);";
				      			}
				      		?>
			  				jQuery.each(campos_form, function( id, tipo ) {
			  					borrar_cookie(id);
			  				});
			      		}
			      	});
				}else{
			  		alert("Debe aceptar los términos y condiciones.");
					vlz_modal('terminos', 'Términos y Condiciones');
				}
			}
			e.preventDefault();
		});

</script>
