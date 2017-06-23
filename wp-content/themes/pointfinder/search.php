<?php 
get_header();
	
	if (isset($_GET['action']) && $_GET['action'] == 'pfs') {
        echo '<div id="mapa"></div>';
/**
 *	Resultado de búsqueda de Cuidadores de Kmimos
 * */
		$claves_filtros = array('ubicacion_cuidador', 'precio_hospedaje', 'precio_guarderia', 'precio_adiestramiento');
		$parametros_filtros = array(
			'ubicacion_cuidador'=>array('tipo'=>'jerarquico','campo'=>'location_petsitter','mascara'=>'__-__-___'),
			'precio_hospedaje'=>array('tipo'=>'precio','campo'=>'precio_desde','minimo'=>'precio_minimo','maximo'=>'precio_maximo','indice'=>'tamano_mascota_hospedaje'),
			
		);
/*			$pfgetdata = $_GET;
			$pfne = $pfne2 = $pfsw = $pfsw2 = $pfpointfinder_google_search_coord = '';
			$hidden_output = $search_output = '';
			$searchkeys = array('pfsearch-filter','pfsearch-filter-order','pfsearch-filter-number','pfsearch-filter-col');

*/
		$kmmgetdata = $_GET;	// String de búsqueda
		// Crea lista de claves propias
//		$searchkeys = array('kmmsearch-filter','kmmsearch-filter-order','kmmsearch-filter-number','kmmsearch-filter-col');
		$searchkeys = array('pfsearch-filter','pfsearch-filter-order','pfsearch-filter-number','pfsearch-filter-col');
		if(is_array($kmmgetdata)){
			$kmmformvars = array();
			$kmmhiddens = '<input type="hidden" name="s" value=""/>';
			foreach($kmmgetdata as $key=>$value){
				if($value!=''){	// Procesa solo las claves que tengan valores
					if(isset($kmmformvars[$key])) $kmmformvars[$key] .= ',' . $value;
					else  $kmmformvars[$key] = $value;
				}
				// Si la clave no pertenece a la lista de claves propias crea campo oculto
				if(!in_array($key,$searchkeys)) $kmmhiddens .= '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
				if ($key == 'ne')  $pfne = sanitize_text_field($value);
				if ($key == 'ne2') $pfne2 = sanitize_text_field($value);
				if ($key == 'sw')  $pfsw = sanitize_text_field($value);
				if ($key == 'sw2') $pfsw2 = sanitize_text_field($value);
				if ($key == 'pointfinder_google_search_coord') {
					$pfpointfinder_google_search_coord = sanitize_text_field($value);
				}
			}
		}

		// El tipo de post a utilizar es el de los cuidadores activos
		$args = array( 'post_type' => 'petsitters', 'post_status' => 'publish');
        
		// Pregunta si existe un campo para orden predeterminado
		if(isset($_GET['pfsearch-filter']) && $_GET['pfsearch-filter']!=''){
			$pfg_orderbyx = esc_attr($_GET['pfsearch-filter']);
		}
		else{
			$pfg_orderbyx = '';
		}
		
		// Pregunta si existe un orden predeterminado
		if(isset($_POST['pfg_order']) && $_POST['pfg_order']!=''){
			$pfg_orderx = esc_attr($_POST['pfg_order']);
		}
		else{
			$pfg_orderx = '';
		}

		// Pregunta si existe un número de post de salida predeterminado
		if(isset($_POST['pfg_number']) && $_POST['pfg_number']!=''){
			$pfg_numberx = esc_attr($_POST['pfg_number']);
		}
		else{
			$pfg_numberx = '';
		}

		$setup22_searchresults_defaultppptype = '12';		// Número de post predeterminados por página
		$setup22_searchresults_defaultsortbytype = 'ID';	// Campo predeterminado para el orden de los resultados
		$setup22_searchresults_defaultsorttype = 'ASC';		// Orden predeterminado de los resultados

/*
		if($pfg_orderbyx == ''){
			$args['meta_key'] = 'featured_petsitter';
			$args['orderby'] = array('meta_value_num' => 'DESC' , $setup22_searchresults_defaultsortbytype => $setup22_searchresults_defaultsorttype);
			$args['posts_per_page'] = $setup22_searchresults_defaultppptype;
		}else{
			$args['meta_key'] = 'featured_petsitter';
			$args['orderby'] = array('meta_value_num' => 'DESC');
			$args['posts_per_page'] = $pfg_numberx;
		}
*/
		if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
			$args['meta_query'] = array();
		}	

		if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
			$args['tax_query'] = array();
		}

		// Revisa todas las claves de filtrado 
		foreach($kmmformvars as $kmmformvar => $kmmvalue){
			// Si la clave no está en la lista de claves propias, entonces la procesa
			if(!in_array($kmmformvar, $searchkeys)){
				// Busca el tipo de clave (tipo del campo usado como filtro)
				if(in_array($kmmformvar,$claves_filtros)){
//					echo $kmmformvar.'<br>';
					switch($parametros_filtros[$kmmformvar]['tipo']){
						case 'jerarquico':
							$mascara = $parametros_filtros[$kmmformvar]['mascara'];
							if (is_array($kmmvalue)) {
								$compare_x = 'REGEXP';
								$valor = implode('|',$kmmvalue);
							}else{
								$compare_x = 'REGEXP';
//								$compare_x = 'LIKE';
								$valor = $kmmvalue;
//								$valor = (strlen($mascara)>strlen($kmmvalue))?$kmmvalue . '%': $kmmvalue;
//								$valor = (strlen($mascara)>strlen($kmmvalue))?$kmmvalue . substr($mascara,strlen($mascara)-strlen($kmmvalue)+1): $kmmvalue;
							}
							$args['meta_query'][] = array(
								'key' => $parametros_filtros[$kmmformvar]['campo'],
								'value' => $valor,
								'compare' => $compare_x,
								'type' => 'CHAR'
							);
							break;
					}
				}
			}
		}
/*
*   Filtra sólo los cuidadores activos
*/
        $args['meta_query']['relation'] = 'AND';
        $args['meta_query'][] = array('key'=>'active_petsitter','value'=>'1','compare' => '==', 'type' => 'NUMERIC');
      
        $services = $_GET['servicio_cuidador'];
        foreach($_GET['servicio_adicional'] as $adicional) $services[]=$adicional;
        $precio = kmimos_servicio_principal($services,1);
/**/        
        
        if(isset($_GET['precio_minimo']) && isset($_GET['precio_maximo']) && $_GET['precio_minimo']!='' && $_GET['precio_maximo']!='') {
            $args['meta_query']['relation'] = 'AND';
            $args['meta_query'][] = array('key'=>$precio,'value'=>$_GET['precio_minimo'],'compare' => '>=', 'type' => 'NUMERIC');
            $args['meta_query'][] = array('key'=>$precio,'value'=>$_GET['precio_maximo'],'compare' => '<=', 'type' => 'NUMERIC');
            $args['meta_query'][] = array('key'=>$precio,'value'=>'','compare' => '!=', 'type' => 'CHAR');
}
        
        if(isset($_GET['experiencia_minima']) && isset($_GET['experiencia_maxima']) && $_GET['experiencia_minima']!='' && $_GET['experiencia_maxima']!='') {
            $args['meta_query']['relation'] = 'AND';
            $args['meta_query'][] = array('key'=>'experience_petsitter','value'=>$_GET['experiencia_minima'],'compare' => '>=', 'type' => 'NUMERIC');
            $args['meta_query'][] = array('key'=>'experience_petsitter','value'=>$_GET['experiencia_maxima'],'compare' => '<=', 'type' => 'NUMERIC');
        }
 /*       
        if(isset($_GET['valoracion_minima']) && isset($_GET['valoracion_maxima']) && $_GET['valoracion_minima']!='' && $_GET['valoracion_maxima']!='') {
            $args['meta_query']['relation'] = 'AND';
//            $args['meta_query'][] = array('key'=>'rating_petsitter','compare' => 'EXISTS');
            $args['meta_query'][] = array('key'=>'rating_petsitter','value'=>$_GET['valoracion_minima'],'compare' => '>=', 'type' => 'NUMERIC');
            $args['meta_query'][] = array('key'=>'rating_petsitter','value'=>$_GET['valoracion_maxima'],'compare' => '<=', 'type' => 'NUMERIC');
        }
*/

        if(isset($_GET['tamano_mascota']) && is_array($_GET['tamano_mascota'])) {
            $tamanos = array('relation'=>'AND');
            foreach($_GET['tamano_mascota'] as $tamano){
                switch($tamano){
                    case 'pequeno':
                        $tamanos[] = array('key'=>'hospedaje_pequenas','value'=>'1','compare' => '=', 'type' => 'NUMERIC');
                        break;
                    case 'mediano':
                        $tamanos[] = array('key'=>'hospedaje_medianas','value'=>'1','compare' => '=', 'type' => 'NUMERIC');
                        break;
                    case 'grande':
                        $tamanos[] = array('key'=>'hospedaje_grandes','value'=>'1','compare' => '=', 'type' => 'NUMERIC');
                        break;
                    case 'gigante':
                        $tamanos[] = array('key'=>'hospedaje_gigantes','value'=>'1','compare' => '=', 'type' => 'NUMERIC');
                        break;
                }
            }
            $args['meta_query']['relation'] = 'AND';
            $args['meta_query'][] = $tamanos;
        }

        if(isset($_GET['servicio_cuidador']) && is_array($_GET['servicio_cuidador'])) {
            $servicios = array('relation'=>'AND');
            foreach($_GET['servicio_cuidador'] as $servicio){
                switch($servicio){
                    case 'hospedaje':
//                        $servicios[] = array('key'=>'precio_hospedaje','value'=>'a:0:{}','compare' => '!=', 'type' => 'CHAR');
                        $servicios[] = array('key'=>'hospedaje_desde','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'guarderia':
//                        $servicios[] = array('key'=>'precio_guarderia','value'=>'a:0:{}','compare' => '!=', 'type' => 'CHAR');
                        $servicios[] = array('key'=>'guarderia_desde','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'paseos':
//                        $servicios[] = array('key'=>'precio_paseo','value'=>'a:0:{}','compare' => '!=', 'type' => 'CHAR');
                        $servicios[] = array('key'=>'paseo_desde','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'adiestramiento':
//                        $servicios[] = array('key'=>'adiestramiento','value'=>'a:3:{i:0;a:0:{}i:1;a:0:{}i:2;a:0:{}}','compare' => '!=', 'type' => 'CHAR');
                        $servicios[] = array('key'=>'adiestramiento_desde','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'peluqueria':
//                        $servicios[] = array('key'=>'precio_corte','value'=>'a:0:{}','compare' => '!=', 'type' => 'CHAR');
                        $servicios[] = array('key'=>'peluqueria_desde','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'bano':
//                        $servicios[] = array('key'=>'precio_bano','value'=>'a:0:{}','compare' => '!=', 'type' => 'CHAR');
                        $servicios[] = array('key'=>'bano_desde','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'transporte':
//                        $servicios[] = array('key'=>'transportacion_s','value'=>'a:0:{}','compare' => '!=', 'type' => 'CHAR');
                        $servicios[] = array('key'=>'simple_desde','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'transporte2':
//                        $servicios[] = array('key'=>'transportacion_r','value'=>'a:0:{}','compare' => '!=', 'type' => 'CHAR');
                        $servicios[] = array('key'=>'doble_desde','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'veterinario':
                        $servicios[] = array('key'=>'visita_veterinario','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'limpieza':
                        $servicios[] = array('key'=>'limpieza_dental','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                    case 'acupuntura':
                        $servicios[] = array('key'=>'precio_acupuntura','value'=>'','compare' => '!=', 'type' => 'CHAR');
                        break;
                }
            }
            $args['meta_query']['relation'] = 'AND';
            $args['meta_query'][] = $servicios;
        }
        $ordenar =$_GET['orderby'];
        switch($ordenar){
            case 'price_desc':   // Ordena por precio descendente   
//                $args['orderby'] = array('meta_value_num'=>'DESC', 'distance'=>'DESC', 'menu_order'=>'DESC');
                $args['orderby'] = array('meta_value_num'=>'DESC', 'distance'=>'DESC');
                $args['meta_key'] = $precio;
                break;
            case 'experience_asc':   // Ordena por experiencia ascendente   
                $args['order'] = 'ASC';
                $args['orderby'] = 'experience';
/*                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                $args['meta_key'] = 'experience_petsitter';*/
                break;
            case 'experience_desc':   // Ordena por experiencia descendente   
                $args['order'] = 'DESC';
                $args['orderby'] = 'experience';
/*                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                $args['meta_key'] = 'experience_petsitter';*/
                break;
            case 'name_asc':   // Ordena por nombre del cuidador ascendente   
/*                $args['order'] = 'ASC';
                $args['orderby'] = 'post_title';*/
                $args['orderby'] = array('meta_value'=>'ASC', 'distance'=>'ASC');
                $args['meta_key'] = 'firstname_petsitter';
                break;
            case 'name_desc':   // Ordena por nombre del cuidador descendente   
                $args['order'] = 'DESC';
                $args['orderby'] = 'post_title';
                break;
            case 'rating_asc':   // Ordena por valoración del cuidador ascendente   
                $args['order'] = 'ASC';
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'rating_petsitter';
                break;
            case 'rating_desc':   // Ordena por valoración del cuidador descendente   
                $args['order'] = 'DESC';
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'rating_petsitter';
                break;
            case 'distance_asc':   // Ordena por distancia del cuidador ascendente   
                $args['order'] = 'ASC';
                $args['orderby'] = 'distance';
                break;
            case 'distance_desc':   // Ordena por distancia del cuidador descendente   
                $args['order'] = 'DESC';
                $args['orderby'] = 'distance';
                break;
            case 'price_asc':   // Ordena por precio ascendente   
            default:
//                $args['orderby'] = array('meta_value_num'=>'ASC', 'distance'=>'ASC','menu_order'=>'ASC');
                $args['orderby'] = array('meta_value_num'=>'ASC', 'distance'=>'ASC');
                $args['meta_key'] = $precio;
                break;
        }

//print_r($args);

		$manualargs = base64_encode(maybe_serialize($args));
		$hidden_output = base64_encode(maybe_serialize($hidden_output));
				
        $setup_item_searchresults_sidebarpos = PFASSIssetControl('setup_item_searchresults_sidebarpos','','2');

		$setup42_searchpagemap_headeritem = PFSAIssetControl('setup42_searchpagemap_headeritem','','1');

		if ($setup42_searchpagemap_headeritem != 1) {
			if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}
		}else{

			/* Get Variables and apply */
/*			$setup42_searchpagemap_height = PFSAIssetControl('setup42_searchpagemap_height','height','550');
			$setup42_searchpagemap_lat = PFSAIssetControl('setup42_searchpagemap_lat','','');
			$setup42_searchpagemap_lng = PFSAIssetControl('setup42_searchpagemap_lng','','');
			$setup42_searchpagemap_zoom = PFSAIssetControl('setup42_searchpagemap_zoom','','12');
			$setup42_searchpagemap_mobile = PFSAIssetControl('setup42_searchpagemap_mobile','','10');
			$setup42_searchpagemap_autofitsearch = PFSAIssetControl('setup42_searchpagemap_autofitsearch','','1');
			$setup42_searchpagemap_type = PFSAIssetControl('setup42_searchpagemap_type','','ROADMAP');
			$setup42_searchpagemap_business = PFSAIssetControl('setup42_searchpagemap_business','','0');
			$setup42_searchpagemap_streetViewControl = PFSAIssetControl('setup42_searchpagemap_streetViewControl','','1');
			$setup42_searchpagemap_style = preg_replace('/\s+/', '',PFSAIssetControl('setup42_searchpagemap_style','',''));
			if (mb_substr($setup42_searchpagemap_style, 0, 1,'UTF-8') == '[' && mb_substr($setup42_searchpagemap_style, -1, 1,'UTF-8') == ']') {
				$setup42_searchpagemap_style = mb_substr($setup42_searchpagemap_style, 1, -1,'UTF-8');
			}
			$setup42_searchpagemap_style = base64_encode( strip_tags( $setup42_searchpagemap_style ));
			$setup42_searchpagemap_ajax = PFSAIssetControl('setup42_searchpagemap_ajax','','0');
			$setup42_searchpagemap_ajax_drag = PFSAIssetControl('setup42_searchpagemap_ajax_drag','','0');
			$setup42_searchpagemap_ajax_zoom = PFSAIssetControl('setup42_searchpagemap_ajax_zoom','','0');
			$setup42_searchpagemap_height = str_replace('px', '', $setup42_searchpagemap_height);

*/				

			$setup42_searchpagemap_zoom = '12';		// Zoom predeterminado del mapa para desktop
			$setup42_searchpagemap_mobile = '10';	// Zoom predeterminado del mapa para móvil
			$setup42_searchpagemap_height = '380';	// Alto predeterminado del mapa
			$setup42_searchpagemap_lat = '19.4132314';			// Latitud inicial de MX
			$setup42_searchpagemap_lng = '-99.146425';			// Longitud inicial de MX
			$setup42_searchpagemap_autofitsearch = '1';
			$setup42_searchpagemap_type = 'ROADMAP';
			$setup42_searchpagemap_business = '1';
			$setup42_searchpagemap_streetViewControl = '1';
			$setup42_searchpagemap_style = '';
			$setup42_searchpagemap_ajax = '0';
			$setup42_searchpagemap_ajax_drag ='0';
			$setup42_searchpagemap_ajax_zoom = '0';

//			$manualargs ='YTo3OntzOjk6InBvc3RfdHlwZSI7czo4OiJhbnVuY2lvcyI7czoxMToicG9zdF9zdGF0dXMiO3M6NzoicHVibGlzaCI7czo4OiJtZXRhX2tleSI7czozNjoid2ViYnVwb2ludGZpbmRlcl9pdGVtX2ZlYXR1cmVkbWFya2VyIjtzOjc6Im9yZGVyYnkiO2E6Mjp7czoxNDoibWV0YV92YWx1ZV9udW0iO3M6NDoiREVTQyI7czoyOiJJRCI7czo0OiJERVNDIjt9czoxNDoicG9zdHNfcGVyX3BhZ2UiO3M6MToiNiI7czoxMDoibWV0YV9xdWVyeSI7YToxOntpOjA7YTozOntzOjg6InJlbGF0aW9uIjtzOjI6Ik9SIjtpOjA7YToyOntzOjM6ImtleSI7czoyODoicG9pbnRmaW5kZXJfaXRlbV9vbm9mZnN0YXR1cyI7czo3OiJjb21wYXJlIjtzOjEwOiJOT1QgRVhJU1RTIjt9aToxO2E6NDp7czozOiJrZXkiO3M6Mjg6InBvaW50ZmluZGVyX2l0ZW1fb25vZmZzdGF0dXMiO3M6NToidmFsdWUiO2k6MDtzOjc6ImNvbXBhcmUiO3M6MToiPSI7czo0OiJ0eXBlIjtzOjc6Ik5VTUVSSUMiO319fXM6OToidGF4X3F1ZXJ5IjthOjE6e2k6MDthOjQ6e3M6ODoidGF4b25vbXkiO3M6MjA6InBvaW50ZmluZGVybG9jYXRpb25zIjtzOjU6ImZpZWxkIjtzOjI6ImlkIjtzOjU6InRlcm1zIjthOjE6e2k6MDtzOjI6IjUxIjt9czo4OiJvcGVyYXRvciI7czoyOiJJTiI7fX19';

//echo $manualargs;
			$comando ='[pf_directory_map setup5_mapsettings_height="'.$setup42_searchpagemap_height.
				'" setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.
				'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.
				'" setup8_pointsettings_ajax="'.$setup42_searchpagemap_ajax.
				'" setup8_pointsettings_ajax_drag="'.$setup42_searchpagemap_ajax_drag.
				'" setup8_pointsettings_ajax_zoom="'.$setup42_searchpagemap_ajax_zoom.
				'" setup5_mapsettings_autofit="0" setup5_mapsettings_autofitsearch="'.$setup42_searchpagemap_autofitsearch.
				'" setup5_mapsettings_type="'.$setup42_searchpagemap_type.
				'" setup5_mapsettings_business="'.$setup42_searchpagemap_business.
				'" setup5_mapsettings_streetViewControl="'.$setup42_searchpagemap_streetViewControl.
				'" mapsearch_status="0" mapnot_status="0" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.
				'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.
				'" setup5_mapsettings_style="'.$setup42_searchpagemap_style.
				'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.
				'" manualargs="'.$manualargs.'" neaddress="'.$pfpointfinder_google_search_coord.'"]';
			echo do_shortcode($comando);

            echo $comando;
		}

		$setup22_searchresults_background2 = PFSAIssetControl('setup22_searchresults_background2','','#ffffff');
		$setup42_authorpagedetails_grid_layout_mode = PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
		$setup22_searchresults_defaultlistingtype = PFSAIssetControl('setup22_searchresults_defaultlistingtype','','4');
		$setup42_authorpagedetails_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
		$setup42_authorpagedetails_grid_layout_mode = ($setup42_authorpagedetails_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;

		$setup22_searchresults_status_catfilters = PFSAIssetControl('setup22_searchresults_status_catfilters','','1');
		
		if ($setup22_searchresults_status_catfilters == 1) {
			$filters_text = 'true';
		}else{
			$filters_text = 'false';
		}

			echo '<section role="main">';
		        echo '<div class="pf-page-spacing"></div>';
		        echo '<div class="pf-container"><div class="pf-row clearfix">';
		        	if ($setup_item_searchresults_sidebarpos == 3) {
		        		echo '<div class="col-lg-12"><div class="pf-page-container">';

// Muestra el listado de cuidadores sin barra lateral 100% del ancho
						$comando = '[pf_itemgrid2 filters="'.$filters_text.'" manualargs="'.$manualargs.'" orderby="" sortby="" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="'.$setup22_searchresults_defaultlistingtype.'" itemboxbg="'.$setup22_searchresults_background2.'" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.'" ]';
                        echo do_shortcode($comando);


						echo '</div></div>';
		        	}else{
		        		if($setup_item_searchresults_sidebarpos == 1){
			                echo '<div class="col-lg-3 col-md-4">';
			                    get_sidebar('itemsearchres' ); 
			                echo '</div>';
			            }
			              
			            echo '<div class="col-lg-9 col-md-8"><div class="pf-page-container">'; 
                        
// Muestra el listado de cuidadores con barra lateral 3/4 del ancho
                        if($_GET['ver']=='test' || true) $comando ='[kmimos_list filters="'.$filters_text.'" hidden_output="'.$hidden_output.'" manualargs="'.$manualargs.'" orderby="" sortby="" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="'.$setup22_searchresults_defaultlistingtype.'" itemboxbg="'.$setup22_searchresults_background2.'" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.'" ]';
			            else $comando ='[pf_itemgrid2 filters="'.$filters_text.'" hidden_output="'.$hidden_output.'" manualargs="'.$manualargs.'" orderby="" sortby="" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="'.$setup22_searchresults_defaultlistingtype.'" itemboxbg="'.$setup22_searchresults_background2.'" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.'" ]';
                        
                        //echo $comando;
                        echo do_shortcode($comando);


			            echo '</div></div>';
			            if($setup_item_searchresults_sidebarpos == 2){
			                echo '<div class="col-lg-3 col-md-4">';
			                    get_sidebar('itemsearchres' );
			                echo '</div>';
			            }
		        	}
		            
		        echo '</div></div>';
		        echo '<div class="pf-page-spacing"></div>';
		    echo '</section>';
		


	}
	else if (isset($_GET['action']) && $_GET['action'] == 'kmms') {

		/**
		*Start: Get search data & apply to query arguments.
		**/

			$pfgetdata = $_GET;
			$pfne = $pfne2 = $pfsw = $pfsw2 = $pfpointfinder_google_search_coord = '';
			$hidden_output = $search_output = '';
			$searchkeys = array('pfsearch-filter','pfsearch-filter-order','pfsearch-filter-number','pfsearch-filter-col');

			if(is_array($pfgetdata)){

				$pfformvars = array();

//echo 'Arreglo ingresado';
//print_r($pfgetdata);

					//Data clean
					$pfgetdata = PFCleanArrayAttr('PFCleanFilters',$pfgetdata);

//echo '<br>Arreglo sanitizado';
//print_r($pfgetdata);

					foreach($pfgetdata as $key=>$value){
						

						//Get Values & clean
						if($value != ''){
							
							if(isset($pfformvars[$key])){
								$pfformvars[$key] = $pfformvars[$key]. ',' .$value;
							}else{
								$pfformvars[$key] = $value;
							}
							if (!is_array($value)) {
								if(!in_array($key, $searchkeys)){
									$hidden_output .= '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
								}
							}
							

						}

						if ($key == 'ne') {$pfne = sanitize_text_field($value);}
						if ($key == 'ne2') {$pfne2 = sanitize_text_field($value);}
						if ($key == 'sw') {$pfsw = sanitize_text_field($value);}
						if ($key == 'sw2') {$pfsw2 = sanitize_text_field($value);}
						if ($key == 'pointfinder_google_search_coord') {$pfpointfinder_google_search_coord = sanitize_text_field($value);}

					
					}
					$hidden_output .= '<input type="hidden" name="s" value=""/>';

					
					
					$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
					$args = array( 'post_type' => $setup3_pointposttype_pt1, 'post_status' => 'publish');
					

					if(isset($_GET['pfsearch-filter']) && $_GET['pfsearch-filter']!=''){$pfg_orderbyx = esc_attr($_GET['pfsearch-filter']);}else{$pfg_orderbyx = '';}
					if(isset($_POST['pfg_order']) && $_POST['pfg_order']!=''){$pfg_orderx = esc_attr($_POST['pfg_order']);}else{$pfg_orderx = '';}
					if(isset($_POST['pfg_number']) && $_POST['pfg_number']!=''){$pfg_numberx = esc_attr($_POST['pfg_number']);}else{$pfg_numberx = '';}

					$setup22_searchresults_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
					$setup22_searchresults_defaultsortbytype = PFSAIssetControl('setup22_searchresults_defaultsortbytype','','ID');
					$setup22_searchresults_defaultsorttype = PFSAIssetControl('setup22_searchresults_defaultsorttype','','ASC');


					if($pfg_orderbyx == ''){
						$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
						$args['orderby'] = array('meta_value_num' => 'DESC' , $setup22_searchresults_defaultsortbytype => $setup22_searchresults_defaultsorttype);
						$args['posts_per_page'] = $setup22_searchresults_defaultppptype;
					}else{
						$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
						$args['orderby'] = array('meta_value_num' => 'DESC');
						$args['posts_per_page'] = $pfg_numberx;
					}

					if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
						$args['meta_query'] = array();
					}	

					if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
						$args['tax_query'] = array();
					}

					// On/Off filter for items
						$args['meta_query'][] = array('relation' => 'OR',
							array(
								'key' => 'pointfinder_item_onoffstatus',
								'compare' => 'NOT EXISTS'
								
							),
							array(
				                    'key'=>'pointfinder_item_onoffstatus',
				                    'value'=> 0,
				                    'compare'=>'=',
				                    'type' => 'NUMERIC'
			                )
			                
						);

					foreach($pfformvars as $pfformvar => $pfvalue){
						
						if(!in_array($pfformvar, $searchkeys)){
							$thiskeyftype = '';
							$thiskeyftype = PFFindKeysInSearchFieldA_ld($pfformvar);
							
							//Get target field & condition
							$target = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_target','','');
							$target_condition = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_target_according','','');

							switch($thiskeyftype){
								case '1'://select
									//is_Multiple
									$multiple = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_multiple','','0');
								
									
									//Find Select box type
									//Check element: is it a taxonomy?
									$rvalues_check = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_rvalues_check','','0');
									
									if($rvalues_check == 0){
										$pfvalue_arr = PFGetArrayValues_ld($pfvalue);
										$fieldtaxname = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_posttax','','');
										$args['tax_query'][]=array(
											'taxonomy' => $fieldtaxname,
											'field' => 'id',
											'terms' => $pfvalue_arr,
											'operator' => 'IN'
										);
									}else{
										
										$target_r = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_rvalues_target','','');
										if (empty($target_r)) {
											$target_r = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_rvalues_target_target','','');
										}
										$target_condition_r = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_rvalues_target_according','','');
										

										if (is_array($pfvalue)) {
											if ($target_condition_r == '=') {
												$compare_x = 'IN';
											}else{
												$compare_x = $target_condition_r;
											}
											$pfcomptype = 'NUMERIC';
										}else{
											if(is_numeric($pfvalue)){
												$pfcomptype = 'NUMERIC';
											}else{
												$pfcomptype = 'CHAR';
											}

											if (strpos($pfvalue, ",") != 0) {
												$pfvalue = pfstring2BasicArray($pfvalue);
												if ($target_condition_r == '=') {
													$compare_x = 'IN';
												}else{
													$compare_x = $target_condition_r;
												}
											}else{
												$compare_x = $target_condition_r;
											}
										}
										$args['meta_query'][] = array(
											'key' => 'webbupointfinder_item_'.$target_r,
											'value' => $pfvalue,
											'compare' => $compare_x,
											'type' => $pfcomptype
											
										);
										
									}
									
									break;
									
								case '2'://slider
									//Find Slider Type from slug
									$slidertype = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_type','','');
									$pfcomptype = 'NUMERIC';
									
									if($slidertype == 'range'){ 
									$pfvalue = trim($pfvalue,"\0");
										$pfvalue_exp = explode(',',$pfvalue);
																	
										$args['meta_query'][] = array(
											'key' => 'webbupointfinder_item_'.$target,
											'value' => array($pfvalue_exp[0],$pfvalue_exp[1]),
											'compare' => 'BETWEEN',
											'type' => $pfcomptype
										);
									}else{
										$args['meta_query'][] = array(
											'key' => 'webbupointfinder_item_'.$target,
											'value' => $pfvalue,
											'compare' => $target_condition,
											'type' => $pfcomptype
										);
									}
									
									
									break;
									
								case '4'://text field

							  		$target = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_target_target','','');
									
									switch ($target) {
										case 'title':
												$args['search_prod_title'] = $pfvalue;
												function title_filter( $where, &$wp_query )
												{
													global $wpdb;
													if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
														if($search_term != ''){
															$search_term = $wpdb->esc_like( $search_term );
															$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(  $search_term ) . '%\'';
														}
													}
													return $where;
												}

										  		add_filter( 'posts_where', 'title_filter', 10, 2 );

											break;

										case 'description':
												$args['search_prod_desc'] = $pfvalue;
												function pf_description_filter( $where, &$wp_query )
												{
													global $wpdb;
													if ( $search_term = $wp_query->get( 'search_prod_desc' ) ) {
														if($search_term != ''){
															$search_term = $wpdb->esc_like( $search_term );
															$where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . esc_sql(  $search_term ) . '%\'';
														}
													}
													return $where;
												}

										  		add_filter( 'posts_where', 'pf_description_filter', 10, 3 );

											break;

										case 'address':
												$pfcomptype = 'CHAR';
												$args['meta_query'][] = array(
													'key' => 'webbupointfinder_items_address',
													'value' => $pfvalue,
													'compare' => 'LIKE',
													'type' => $pfcomptype
												);
											break;

										case 'google':
											break;
										
										default:
												$pfcomptype = 'CHAR';
												$args['meta_query'][] = array(
													'key' => 'webbupointfinder_item_'.$target,
													'value' => $pfvalue,
													'compare' => 'LIKE',
													'type' => $pfcomptype
												);
											break;
									}


									break;

								case '5':

										$pfcomptype = 'NUMERIC';

										$setup4_membersettings_dateformat = PFSAIssetControl('setup4_membersettings_dateformat','','1');
										switch ($setup4_membersettings_dateformat) {
											case '1':$datetype = "d-m-Y";break;
											case '2':$datetype = "m-d-Y";break;
											case '3':$datetype = "Y-m-d";break;
											case '4':$datetype = "Y-d-m";break;
										}

										$pfvalue = date_parse_from_format($datetype, $pfvalue);

										$pfvalue = strtotime(date("Y-m-d", mktime(0, 0, 0, $pfvalue['month'], $pfvalue['day'], $pfvalue['year'])));

							     		if (!empty($pfvalue)) {
											
											$args['meta_query'][] = array(
												'key' => 'webbupointfinder_item_'.$target,
												'value' => intval($pfvalue),
												'compare' => "$target_condition",
												'type' => "$pfcomptype"
											);
											
										}
									break;

								case '6'://checkbox
									
									if(is_numeric($pfvalue)){
										$pfcomptype = 'NUMERIC';
									}else{
										$pfcomptype = 'CHAR';
									}
									if (is_array($pfvalue)) {
										$compare_x = 'IN';
									}else{
										$compare_x = '=';
									}
									$args['meta_query'][] = array(
										'key' => 'webbupointfinder_item_'.$target,
										'value' => $pfvalue,
										'compare' => $compare_x,
										'type' => $pfcomptype
										
									);
									
									break;
							}
						}
						
					}
			}		
		/**
		*End: Get search data & apply to query arguments.
		**/

		$manualargs = base64_encode(maybe_serialize($args));
		$hidden_output = base64_encode(maybe_serialize($hidden_output));
				
        $setup_item_searchresults_sidebarpos = PFASSIssetControl('setup_item_searchresults_sidebarpos','','2');

		$setup42_searchpagemap_headeritem = PFSAIssetControl('setup42_searchpagemap_headeritem','','1');
		if ($setup42_searchpagemap_headeritem != 1) {
			if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}
		}else{

			/* Get Variables and apply */
			$setup42_searchpagemap_height = PFSAIssetControl('setup42_searchpagemap_height','height','440');
			$setup42_searchpagemap_lat = PFSAIssetControl('setup42_searchpagemap_lat','','');
			$setup42_searchpagemap_lng = PFSAIssetControl('setup42_searchpagemap_lng','','');
			$setup42_searchpagemap_zoom = PFSAIssetControl('setup42_searchpagemap_zoom','','12');
			$setup42_searchpagemap_mobile = PFSAIssetControl('setup42_searchpagemap_mobile','','10');
			$setup42_searchpagemap_autofitsearch = PFSAIssetControl('setup42_searchpagemap_autofitsearch','','1');
			$setup42_searchpagemap_type = PFSAIssetControl('setup42_searchpagemap_type','','ROADMAP');
			$setup42_searchpagemap_business = PFSAIssetControl('setup42_searchpagemap_business','','0');
			$setup42_searchpagemap_streetViewControl = PFSAIssetControl('setup42_searchpagemap_streetViewControl','','1');
			$setup42_searchpagemap_style = preg_replace('/\s+/', '',PFSAIssetControl('setup42_searchpagemap_style','',''));
			if (mb_substr($setup42_searchpagemap_style, 0, 1,'UTF-8') == '[' && mb_substr($setup42_searchpagemap_style, -1, 1,'UTF-8') == ']') {
				$setup42_searchpagemap_style = mb_substr($setup42_searchpagemap_style, 1, -1,'UTF-8');
			}
			$setup42_searchpagemap_style = base64_encode( strip_tags( $setup42_searchpagemap_style ));
			$setup42_searchpagemap_ajax = PFSAIssetControl('setup42_searchpagemap_ajax','','0');
			$setup42_searchpagemap_ajax_drag = PFSAIssetControl('setup42_searchpagemap_ajax_drag','','0');
			$setup42_searchpagemap_ajax_zoom = PFSAIssetControl('setup42_searchpagemap_ajax_zoom','','0');
			$setup42_searchpagemap_height = str_replace('px', '', $setup42_searchpagemap_height);
/*
		$setup42_searchpagemap_zoom = '12';		// Zoom predeterminado del mapa para desktop
		$setup42_searchpagemap_mobile = '10';	// Zoom predeterminado del mapa para móvil
		$setup42_searchpagemap_height = '550';	// Alto predeterminado del mapa
		$setup42_searchpagemap_lat = '19.4132314';			// Latitud inicial de MX
		$setup42_searchpagemap_lng = '-99.146425';			// Longitud inicial de MX
		$setup42_searchpagemap_autofitsearch = '1';
		$setup42_searchpagemap_type = 'ROADMAP';
		$setup42_searchpagemap_business = '1';
		$setup42_searchpagemap_streetViewControl = '1';
		$setup42_searchpagemap_style = '';
		$setup42_searchpagemap_ajax = '0';
		$setup42_searchpagemap_ajax_drag ='0';
		$setup42_searchpagemap_ajax_zoom = '0';

		$manualargs ='YTo3OntzOjk6InBvc3RfdHlwZSI7czo4OiJhbnVuY2lvcyI7czoxMToicG9zdF9zdGF0dXMiO3M6NzoicHVibGlzaCI7czo4OiJtZXRhX2tleSI7czozNjoid2ViYnVwb2ludGZpbmRlcl9pdGVtX2ZlYXR1cmVkbWFya2VyIjtzOjc6Im9yZGVyYnkiO2E6Mjp7czoxNDoibWV0YV92YWx1ZV9udW0iO3M6NDoiREVTQyI7czoyOiJJRCI7czo0OiJERVNDIjt9czoxNDoicG9zdHNfcGVyX3BhZ2UiO3M6MToiNiI7czoxMDoibWV0YV9xdWVyeSI7YToxOntpOjA7YTozOntzOjg6InJlbGF0aW9uIjtzOjI6Ik9SIjtpOjA7YToyOntzOjM6ImtleSI7czoyODoicG9pbnRmaW5kZXJfaXRlbV9vbm9mZnN0YXR1cyI7czo3OiJjb21wYXJlIjtzOjEwOiJOT1QgRVhJU1RTIjt9aToxO2E6NDp7czozOiJrZXkiO3M6Mjg6InBvaW50ZmluZGVyX2l0ZW1fb25vZmZzdGF0dXMiO3M6NToidmFsdWUiO2k6MDtzOjc6ImNvbXBhcmUiO3M6MToiPSI7czo0OiJ0eXBlIjtzOjc6Ik5VTUVSSUMiO319fXM6OToidGF4X3F1ZXJ5IjthOjE6e2k6MDthOjQ6e3M6ODoidGF4b25vbXkiO3M6MjA6InBvaW50ZmluZGVybG9jYXRpb25zIjtzOjU6ImZpZWxkIjtzOjI6ImlkIjtzOjU6InRlcm1zIjthOjE6e2k6MDtzOjI6IjUxIjt9czo4OiJvcGVyYXRvciI7czoyOiJJTiI7fX19';

*/
		$comando ='[pf_directory_map setup5_mapsettings_height="'.$setup42_searchpagemap_height.
			'" setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.
			'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.
			'" setup8_pointsettings_ajax="'.$setup42_searchpagemap_ajax.
			'" setup8_pointsettings_ajax_drag="'.$setup42_searchpagemap_ajax_drag.
			'" setup8_pointsettings_ajax_zoom="'.$setup42_searchpagemap_ajax_zoom.
			'" setup5_mapsettings_autofit="0" setup5_mapsettings_autofitsearch="'.$setup42_searchpagemap_autofitsearch.
			'" setup5_mapsettings_type="'.$setup42_searchpagemap_type.
			'" setup5_mapsettings_business="'.$setup42_searchpagemap_business.
			'" setup5_mapsettings_streetViewControl="'.$setup42_searchpagemap_streetViewControl.
			'" mapsearch_status="0" mapnot_status="0" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.
			'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.
			'" setup5_mapsettings_style="'.$setup42_searchpagemap_style.
			'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.
			'" manualargs="'.$manualargs.'" neaddress="'.$pfpointfinder_google_search_coord.'"]';
		echo do_shortcode($comando);
			
//			echo do_shortcode('[pf_directory_map setup5_mapsettings_height="'.$setup42_searchpagemap_height.'" setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.'" setup8_pointsettings_ajax="'.$setup42_searchpagemap_ajax.'" setup8_pointsettings_ajax_drag="'.$setup42_searchpagemap_ajax_drag.'" setup8_pointsettings_ajax_zoom="'.$setup42_searchpagemap_ajax_zoom.'" setup5_mapsettings_autofit="0" setup5_mapsettings_autofitsearch="'.$setup42_searchpagemap_autofitsearch.'" setup5_mapsettings_type="'.$setup42_searchpagemap_type.'" setup5_mapsettings_business="'.$setup42_searchpagemap_business.'" setup5_mapsettings_streetViewControl="'.$setup42_searchpagemap_streetViewControl.'" mapsearch_status="0" mapnot_status="0" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.'" setup5_mapsettings_style="'.$setup42_searchpagemap_style.'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.'" manualargs="'.$manualargs.'" neaddress="'.$pfpointfinder_google_search_coord.'"]');
		}

        
		$setup22_searchresults_background2 = PFSAIssetControl('setup22_searchresults_background2','','#ffffff');
		$setup42_authorpagedetails_grid_layout_mode = PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
		$setup22_searchresults_defaultlistingtype = PFSAIssetControl('setup22_searchresults_defaultlistingtype','','4');
		$setup42_authorpagedetails_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
		$setup42_authorpagedetails_grid_layout_mode = ($setup42_authorpagedetails_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;

		$setup22_searchresults_status_catfilters = PFSAIssetControl('setup22_searchresults_status_catfilters','','1');
		
		if ($setup22_searchresults_status_catfilters == 1) {
			$filters_text = 'true';
		}else{
			$filters_text = 'false';
		}

			echo '<section role="main">';
		        echo '<div class="pf-page-spacing"></div>';
		        echo '<div class="pf-container"><div class="pf-row clearfix">';
		        	if ($setup_item_searchresults_sidebarpos == 3) {
		        		echo '<div class="col-lg-12"><div class="pf-page-container">';

							$comando = '[kmimos_list filters="'.$filters_text.'" manualargs="'.$manualargs.'" orderby="" sortby="" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="'.$setup22_searchresults_defaultlistingtype.'" itemboxbg="'.$setup22_searchresults_background2.'" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.'" ]';
                        
                        //echo $comando;
                        echo do_shortcode($comando);


						echo '</div></div>';
		        	}else{
		        		if($setup_item_searchresults_sidebarpos == 1){
			                echo '<div class="col-lg-3 col-md-4">';
			                    get_sidebar('itemsearchres' ); 
			                echo '</div>';
			            }
			              
			            echo '<div class="col-lg-9 col-md-8"><div class="pf-page-container">'; 
			            $comando = '[kmimos_list filters="'.$filters_text.'" hidden_output="'.$hidden_output.'" manualargs="'.$manualargs.'" orderby="" sortby="" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="'.$setup22_searchresults_defaultlistingtype.'" itemboxbg="'.$setup22_searchresults_background2.'" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.'" ]';
                        echo do_shortcode($comando);


			            echo '</div></div>';
			            if($setup_item_searchresults_sidebarpos == 2){
			                echo '<div class="col-lg-3 col-md-4">';
			                    get_sidebar('itemsearchres' );
			                echo '</div>';
			            }
		        	}
		            
		        echo '</div></div>';
		        echo '<div class="pf-page-spacing"></div>';
		    echo '</section>';
		

		
	}else{
		if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}

		echo '<div class="pf-blogpage-spacing pfb-top"></div>';
		echo '<section role="main">';
			echo '<div class="pf-container">';
				echo '<div class="pf-row">';
					echo '<div class="col-lg-12">';
						
						get_template_part('loop');

					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</section>';
		echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
	}


get_footer();

?>