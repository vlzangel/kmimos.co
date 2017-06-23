<?php

	$name_photo = get_user_meta($cuidador->user_id, "name_photo", true);
	$cuidador_id = $cuidador->id;

	if( empty($name_photo)  ){ $name_photo = "0"; }
	if( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}") ){
		$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}";
	}elseif( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg") ){
		$img = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg";
	}else{
		$img = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
	}

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

	// $coordenadas_all_2[] = array(
	// 	"ID" 		=> $cuidador->id,
	// 	"USER" 		=> $cuidador->user_id,
	// 	"lat" 		=> $cuidador->latitud,
	// 	"lng" 		=> $cuidador->longitud,
	// 	"nombre" 	=> $cuidador->nombre,
	// 	"url" 		=> $url,
	// 	"portada" 	=> $cuidador->portada
	// );

	$distancia = $cuidador->DISTANCIA;

	$distancia = 'A '.floor($distancia).' km de tu busqueda';

	//echo "Estado: ".$_POST['estados'];

	// if( $_POST['estados'] == "" ){
	// 	$distancia = "";
	// }else{
	// 	if( $_POST['orderby'] != "distance_asc" && $_POST['orderby'] != "distance_desc" ){
	// 		$distancia = "";
	// 	}
	// }

	if( $_POST['tipo_busqueda'] == "otra-localidad" ){
		if( $_POST['municipios'] == "" ){
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
					<div class="pflist-imagecontainer pflist-subitem" >
						<div class="vlz_postada_cuidador">
							<a class="vlz_img_cuidador easyload" data-original="'.$img.'" href="'.$url.'" style="background-image: url(); filter:blur(2px);"></a>
							<span class="vlz_img_cuidador_interno easyload" data-original="'.$img.'" data-href="'.$url.'" style="background-image: url();"></span>
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
									<sub style="bottom: 0px;">Hospedaje desde</sub><br>MXN $'.$cuidador->hospedaje_desde.'
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
<script>
	jQuery('.vlz_img_cuidador_interno').hover(function(){
		jQuery(this).css('cursor', 'pointer');	
	})
	jQuery('.vlz_postada_cuidador').on('click', '[data-href]', function(){
		var j_url= jQuery(this).attr('data-href');
		location.href = j_url;
	});
</script>