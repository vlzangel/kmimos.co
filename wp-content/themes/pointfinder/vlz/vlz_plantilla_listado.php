<?php
	
	function get_ficha_cuidador($cuidador, $i, $favoritos){
		$home		= get_home_url();
		$img 		= kmimos_get_foto_cuidador($cuidador->id);
		$anios_exp 	= $cuidador->experiencia; if( $anios_exp > 1900 ){ $anios_exp = date("Y")-$anios_exp; }
		$url		= $home."/petsitters/".$cuidador->slug;

		if( isset($cuidador->DISTANCIA) ){ $distancia 	= 'A '.floor($cuidador->DISTANCIA).' km de tu busqueda'; }

		$fav_check = 'false';
		if (in_array($cuidador->id_post, $favoritos)) {
			$fav_check = 'true'; $favtitle_text = esc_html__('Remove from Favorites','pointfindert2d');
		}

		$destacado = "";
		$atributos = unserialize($cuidador->atributos);
		if( isset($atributos["destacado"]) && $atributos["destacado"] == "1" ){
			$destacado = '<div class="pfribbon-wrapper-featured"><div class="pfribbon-featured">DESTACADO</div></div>';
		}

		$ficha = '
		<li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item">
			<div class="pflist-item" style="background-color:#ffffff;">  
					<div class="pflist-item-inner">
						<div class="pflist-imagecontainer pflist-subitem" >
							<div class="vlz_postada_cuidador">
								<a class="vlz_img_cuidador easyload" data-preload="'.$home.'/wp-content/themes/pointfinder/images/cargando1111.gif" data-original="'.$img.'" href="'.$url.'" style="background-image: url('.$home.'/wp-content/themes/pointfinder/images/loading.gif); filter:blur(2px);"></a>
								<span class="vlz_img_cuidador_interno easyload" data-original="'.$img.'" data-href="'.$url.'" style="background-image: url();"></span>
							</div>
							<div class="RibbonCTR">
						        <span class="Sign">
						            <a id="fav_'.$cuidador->id_post.'" class="pf-favorites-link" data-pf-num="'.$cuidador->id_post.'" data-pf-active="'.$fav_check.'" data-pf-item="false" title="Agregar a favoritos">
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
								<span class="pfHoverButtonStyle pfHoverButtonWhite pfHoverButtonSquare">
									<a href="'.$url.'">
										<i class="pfadmicon-glyph-794"></i>
									</a>
								</span>
							</div>
							
							'.$destacado.'

							<div style="left: 0px; font-size: 12px; position: absolute; top: 0px; font-weight: 600; color: #FFF; padding: 10px; box-sizing: border-box; width: 100%; background: linear-gradient(to top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.75) 100%);">
								'.$distancia.'
							</div>
							<div class="pflisting-itemband">
								<div class="pflist-pricecontainer">
									
									<div class="pflistingitem-subelement pf-price"> 
										<sub style="bottom: 0px;">Hospedaje desde</sub><br>S./ $'.$cuidador->precio.'
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
											'.utf8_encode($cuidador->titulo).'
											</a>
											<span class="tooltiptext">'.$anios_exp.' año(s) de experiencia</span>
										</div>
									</div>
								</li>
								<li>
									<div class="text-center rating" style="float: none;">
										<div id="rating">'.kmimos_petsitter_rating($cuidador->id_post).'</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="pflist-subdetailcontainer pflist-subitem" style="height: 50px; clear: both;">
							<div style="position: absolute; left: 20px; bottom: 20px; font-size: 25px;">
								<a onclick="infos['.$i.'].open(map, markers['.$i.']); map.setZoom(15); map.setCenter(markers['.$i.'].getPosition()); vlz_top();" title="VER EN MAPA">
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
		return ($ficha);
	}
?>