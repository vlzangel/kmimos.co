<?php

	$user_id = $cuidador->user_id;

	$img = kmimos_get_foto($user_id);

	$anios_exp = $cuidador->experiencia;
	if( $anios_exp > 1900 ){
		$anios_exp = date("Y")-$anios_exp;
	}

	$data = get_post($cuidador->id_post);

	$cuidador->nombre = explode(" ", $cuidador->nombre);
	$cuidador->nombre = $cuidador->nombre[0];

	$url = get_home_url() . "/petsitters/" . $data->post_name;

	$cuidador->hospedaje_desde = $cuidador->hospedaje_desde*1.2;

	if( $cuidador->id_post == 0 ){
		$nombre_cuidador = $cuidador->nombre;
	}else{
		$nombre_cuidador = $data->post_title;
	}

	$coordenadas_all_2[] = array(
		"ID" 		=> $cuidador->id,
		"lat" 		=> $cuidador->latitud,
		"lng" 		=> $cuidador->longitud,
		"nombre" 	=> $cuidador->nombre,
		"url" 		=> $url,
		"portada" 	=> $cuidador->portada
	);

	$distancia = $cuidador->DISTANCIA;

	$distancia = 'A '.floor($distancia).' km de tu busqueda';

	if( $_POST['tipo_busqueda'] == "otra-localidad" ){
		if( $_POST['estados'] == "" ){
			$distancia = "";
		}else{
			if( $_POST['orderby'] != "" && $_POST['orderby'] != "distance_asc" && $_POST['orderby'] != "distance_desc" ){
				$distancia = "";
			}
		}
	}

/* Start: Favorites */
if (is_user_logged_in()) {
	$user_favorites_arr = get_user_meta( get_current_user_id(), 'user_favorites', true );
	if (!empty($user_favorites_arr)) {
		$user_favorites_arr = json_decode($user_favorites_arr,true);
	}else{
		$user_favorites_arr = array();
	}
}

$fav_check = 'false';
if (is_user_logged_in() && count($user_favorites_arr)>0) {
	if (in_array($cuidador->id_post, $user_favorites_arr)) {
		$fav_check = 'true';
		$favtitle_text = esc_html__('Remove from Favorites','pointfindert2d');
	}
}
/* End: Favorites */


	echo '
		<li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item">
			<div class="pflist-item" style="background-color:#ffffff;">  
				<div class="pflist-item-inner">
					<div class="pflist-imagecontainer pflist-subitem">
						<div class="vlz_postada_cuidador">
							<a class="vlz_img_cuidador" href="'.$url.'" style="background-image: url('.$img.');"></a>
							<span class="vlz_img_cuidador_interno" style="background-image: url('.$img.');"></span>
						</div>
						<div class="RibbonCTR">
					        <span class="Sign">
					            <a class="pf-favorites-link" data-pf-num="'.$cuidador->id_post.'" data-pf-active="'.$fav_check.'" data-pf-item="false" title="Agregar a favoritos">
					            	<i class="pfadmicon-glyph-629"></i>
					            </a>
					        </span>
					        <span class="Triangle"></span>
					    </div>
						<div class="pfImageOverlayH hidden-xs"></div>
						<div class="pfButtons pfStyleV2 pfStyleVAni hidden-xs">
							<span class="pfHoverButtonStyle pfHoverButtonWhite pfHoverButtonSquare clearfix">
								<a class="pficon-imageclick" data-pf-link="'.$img.'" style="cursor:pointer">
									<i class="pfadmicon-glyph-684"></i>
								</a>
							</span>
							'.$video_link.'
							<span class="pfHoverButtonStyle pfHoverButtonWhite pfHoverButtonSquare">
								<a href="'.$url.'">
									<i class="pfadmicon-glyph-794"></i>
								</a>
							</span>
						</div>
						'.$destacados.'
						<div style="left: 0px; font-size: 12px; position: absolute; top: 0px; font-weight: 600; color: #FFF; padding: 10px; box-sizing: border-box; width: 100%; background: linear-gradient(to top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.75) 100%);">
							'.$distancia.'
						</div>
						<div class="pflisting-itemband">
							<div class="pflist-pricecontainer">
								
								<div class="pflistingitem-subelement pf-price"> 
									<sub style="bottom: 0px;">Hospedaje desde</sub><br>COP $'.$cuidador->hospedaje_desde.'
								</div>
							</div>
						</div>
					</div>
					<div class="pflist-detailcontainer pflist-subitem">
						<ul class="pflist-itemdetails">
							<li class="pflist-itemtitle text-center">
								<div style="display: table-cell; vertical-align: middle; width: 240px; height: 30px; align-content: center;">
									<div class="tooltip">
										<a href="'.$url.'" style="text-transform: capitalize;">
										'.$nombre_cuidador.'
										</a>
										<span class="tooltiptext">'.$anios_exp.' año(s) de experiencia</span>
									</div>
								</div>
							</li>
							<li>
								<div class="text-center rating" style="float: none;">
									<div id="rating">';
										echo kmimos_petsitter_rating($cuidador->id_post);
									echo '</div>';
									echo '
								</div>
							</li>
						</ul>
					</div>
					<div class="pflist-subdetailcontainer pflist-subitem" style="height: 50px; clear: both;">
						<div style="position: absolute; left: 20px; bottom: 20px; font-size: 25px;">
							<a onclick="infowindow_'.$ID.'.open(map, marker_'.$ID.'); map.setZoom(15); map.setCenter(marker_'.$ID.'.getPosition()); vlz_top();" title="VER EN MAPA">
								<i class="pfadmicon-glyph-109" style="color: #00b69d;"></i>
							</a>
						</div>
						<div class="clr text-center" style="font-size: 20px; position: absolute; right: 20px; bottom: 20px;">
							'.vlz_servicios($cuidador->adicionales).'
						</div>
					</div>
				</div>
			</div>
		</li>
	';
?>