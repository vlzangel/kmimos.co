function update_tipo_usuario(){
	var tipo 	 = jQuery("#editar_tipo_usuario_modal_tipo_usuario").attr('value');
  	var user_id  = jQuery("#editar_tipo_usuario_modal_user_id").attr('value');
  	var index  = jQuery("#editar_tipo_usuario_modal_index").attr('value');

  	console.log(index);
  	administradores.registros[index][3] = tipo;
	jQuery(".kmimos_panel_setup"+user_id).attr("data-desc", tipo);
	jQuery(".kmimos_panel_setup"+user_id+" .editar_tipo_usuario_td").html(tipo);

	jQuery.ajax({
	    url: SETUP_URL_AJAX,
	    type: "post",
	    data: {
	    	action: "update_tipo_usuario",
	    	id: 	user_id,
	    	tipo:   tipo
	    },
	    success: function (data) {
  			jQuery(".kmimos_modal_interno").css("display", "none");
  			refresh();
	    }
	});
}

function update_descripcion(){
	var descripcion = jQuery("#descripcion").val();
  	var page_id  = jQuery("#page_id").attr('value');
  	var index  = jQuery("#index").attr('value');

	jQuery(".editar_descripcion_pagina_"+page_id).attr("data-desc", descripcion);
	jQuery(".editar_descripcion_pagina_"+page_id+" .editar_descripcion_pagina_td").html(descripcion);
	paginas.registros[index][1] = descripcion;

	jQuery.ajax({
	    url: SETUP_URL_AJAX,
	    type: "post",
	    data: {
	    	action: "update_descripcion",
	    	id: 	page_id,
	    	desc:   descripcion
	    },
	    success: function (data) {
  			jQuery(".kmimos_modal_interno").css("display", "none");
	    }
	});
}

// Refresh

function listar_usuarios(){
	var HTML = "";
	var fil = jQuery("#filtrar_usuarios").val();
	var txt = jQuery("#buscar_usuarios").val();

	for (var i = 0; i < kmimos_usuarios.registros[fil].length; i++) {
		if( buscar(txt, kmimos_usuarios.registros[fil][i][1]) || buscar(txt, kmimos_usuarios.registros[fil][i][2]) ){
			HTML += "<tr class='kmimos_usuario' data-id='"+i+"'>";
				HTML += "<td style='width: 50%;'> <a href='"+URL_HOME+"?i="+kmimos_usuarios.registros[fil][i][3]+"' target='_blank' id='kmimos_usuario_"+i+"'> "+kmimos_usuarios.registros[fil][i][1]+" </a> </td>";
				HTML += "<td style='width: 50%;'> "+kmimos_usuarios.registros[fil][i][2]+" </td>";
			HTML += "</tr>";
		}
	}
	jQuery("#kmimos_panel_usuarios").html(HTML);
}


function listar_administradores(){
	var HTML = "";
	var txt = jQuery("#buscar_administrador").val();

	for (var i = 0; i < administradores.registros.length; i++) {
		if( buscar(txt, administradores.registros[i][1]) || buscar(txt, administradores.registros[i][2]) ){
			HTML += "<tr class='editar_tipo_usuario' data-id='"+administradores.registros[i][0]+"' data-tipo='"+administradores.registros[i][3]+"' data-index='"+i+"'>";
				HTML += "<td style='width: 20%;'> <a href='"+URL_HOME+"/?i="+administradores.registros[i][4]+"'> "+administradores.registros[i][2]+" </a> </td>";
				HTML += "<td style='width: 50%;'> "+administradores.registros[i][1]+" </td>";
				HTML += "<td style='width: 30%;' class='editar_tipo_usuario_td'> "+administradores.registros[i][3]+" </td>";
			HTML += "</tr>";
		}
	}
	jQuery("#kmimos_panel_setup").html(HTML);
	
	jQuery(".editar_tipo_usuario").on("click", function(e){
		var user_id = jQuery(this).attr( "data-id" );
		var tipo = jQuery(this).attr( "data-tipo" );
		var index = jQuery(this).attr( "data-index" );
		jQuery("#editar_tipo_usuario_modal_tipo_usuario > option[value='"+tipo+"']").attr('selected', 'selected'); 
		jQuery("#editar_tipo_usuario_modal_user_id").attr('value', user_id); 
		jQuery("#editar_tipo_usuario_modal_index").attr('value', index); 
		jQuery("#editar_tipo_usuario_modal").css("display", "table");
	});
}

function listar_descripciones(){
	var HTML = "";
	var txt = jQuery("#buscar_pagina").val();

	for (var i = 0; i < paginas.registros.length; i++) {
		if( buscar(txt, paginas.registros[i][2]) ){
			HTML += "<tr class='editar_descripcion_pagina editar_descripcion_pagina_"+paginas.registros[i][0]+"' data-id='"+paginas.registros[i][0]+"' data-desc='"+paginas.registros[i][1]+"' data-index='"+i+"'>";
			HTML += "<td style='width: 40%;'> <a href='"+URL_HOME+"/"+paginas.registros[i][3]+"' target='_blank'> "+paginas.registros[i][2]+" </a> </td>";
			HTML += "<td style='width: 60%;' class='editar_descripcion_pagina_td'> "+paginas.registros[i][1]+" </td>";
			HTML += "</tr>";
		}
	}
	jQuery("#kmimos_setup_descripciones").html(HTML);
	jQuery(".editar_descripcion_pagina").on("click", function(e){
		var page_id = jQuery(this).attr( "data-id" );
		var index = jQuery(this).attr( "data-index" );
		var desc = jQuery(this).attr( "data-desc" );

		jQuery("#page_id").attr("value", page_id);
		jQuery("#index").attr("value", index);
		jQuery("#descripcion").val(desc);

		jQuery("#editar_descripcion_modal").css("display", "table");
	});
}

jQuery(function() {
  	refresh();
  	jQuery("#buscar_usuarios").on("keyup", listar_usuarios);
  	jQuery("#buscar_administrador").on("keyup", listar_administradores);
  	jQuery("#buscar_pagina").on("keyup", listar_descripciones);

  	jQuery("#filtrar_usuarios").on("change", listar_usuarios);

});

function buscar(txt, txt_completo){
	if(txt_completo.toLowerCase().indexOf(txt.toLowerCase()) >= 0){ return true; }else{ return false; }
}

function refresh(){
	listar_usuarios();
	listar_administradores();
	listar_descripciones();
}
