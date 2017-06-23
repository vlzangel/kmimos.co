<?php

	get_header();

	$post_id = get_the_id();
	$meta = get_post_meta( $post_id );

	global $wpdb;
	global $post;

	$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = ".$post->ID);
	$descripcion = $wpdb->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$cuidador->user_id} AND meta_key = 'description'");

	$slug = $wpdb->get_var("SELECT post_name FROM wp_posts WHERE post_type = 'product' AND post_author = '{$cuidador->user_id}' AND post_name LIKE '%hospedaje%' ");

	$latitud 	= $cuidador->latitud;
	$longitud 	= $cuidador->longitud;

	$name_photo = get_user_meta($cuidador->user_id, "name_photo", true);
	$cuidador_id = $cuidador->id;

	if( empty($name_photo)  ){ $name_photo = "0"; }
	if( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}") ){
		$foto = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}";
	}elseif( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg") ){
		$foto = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg";
	}else{
		$foto = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
	}

	$tama_aceptados = unserialize( $cuidador->tamanos_aceptados );
	$tamanos = array(
		'pequenos' => 'Pequeños',
		'medianos' => 'Medianos',
		'grandes'  => 'Grandes',
		'gigantes' => 'Gigantes'
	);

	$aceptados = array();
	foreach ($tama_aceptados as $key => $value) {
		if( $value == 1){
			$aceptados[] = $tamanos[$key];
		}
	} 

	$edad_aceptada = unserialize( $cuidador->edades_aceptadas );
	$edades = array(
		'cachorros' => 'Cachorros',
		'adultos' => 'Adultos'
	);
	$edades_aceptadas = array();
	foreach ($edad_aceptada as $key => $value) {
		if( $value == 1){
			$edades_aceptadas[] = $edades[$key];
		}
	} 

	$atributos = unserialize( $cuidador->atributos );

	$anios_exp = $cuidador->experiencia;
	if( $anios_exp > 1900 ){
		$anios_exp = date("Y")-$anios_exp;
	}

	$mascota_cuidador = unserialize( $cuidador->mascotas_cuidador );
	$mascotas_cuidador = array();
	foreach ($mascota_cuidador as $key => $value) {
		if( $value == 1){
			$mascotas_cuidador[] = $tamanos[$key];
		}
	}

	/* Galeria */

	$id_cuidador = ($cuidador->id)-5000;
	$path_galeria = "wp-content/uploads/cuidadores/galerias/".$id_cuidador."/";

	echo "<div class='hola_xxx' style='display: none;'>".$path_galeria."</div>";
	if( is_dir($path_galeria) ){

		if ($dh = opendir($path_galeria)) { 
			$imagenes = array();
	        while (($file = readdir($dh)) !== false) { 
	            if (!is_dir($path_galeria.$file) && $file!="." && $file!=".."){ 
	               $imagenes[] = $path_galeria.$file;
	            } 
	        } 
	      	closedir($dh);

	      	$cant_imgs = count($imagenes);
	      	if( $cant_imgs > 0 ){
	      		$items = array(); $home = get_home_url()."/";
	      		foreach ($imagenes as $value) {//
	      			$items[] = "
	      				<div class='vlz_item scroll_animate' data-scale='small' data-position='top' onclick=\"vlz_galeria_ver('".$home.$value."')\">
	      					<div class='vlz_item_fondo easyload' data-original='".$home.$value."'  style='background-image: url(); filter:blur(2px);'></div>
	      					<div class='vlz_item_imagen easyload' data-original='".$home.$value."' style='background-image: url();'></div>
	      				</div>
	      			";
	      		}
	      		$galeria = "
	      			<div class='vlz_contenedor_galeria'>
	      				<div class='vlz_contenedor_galeria_interno' style='width: ".($cant_imgs*300)."px;'>
		      				".implode("", $items)."
		      			</div>
	      			</div>
	      			<div class='vlz_modal_galeria' onclick='vlz_galeria_cerrar()'>
	      				<div class='vlz_modal_galeria_interna'></div>
	      			</div>
	      			<script>
	      				function vlz_galeria_ver(url){
	      					jQuery('.vlz_modal_galeria_interna').css('background-image', 'url('+url+')');
	      					jQuery('.vlz_modal_galeria').css('display', 'table');
	      				}
	      				function vlz_galeria_cerrar(){
	      					jQuery('.vlz_modal_galeria').css('display', 'none');
	      					jQuery('.vlz_modal_galeria_interna').css('background-image', '');
	      				}
	      			</script>
	      		";
	      	}else{
	      		$galeria = "";
	      	}
  		} 
	}

	include("vlz/vlz_style_perfil.php");

	$cuidadores = $wpdb->get_results("SELECT * FROM cuidadores");
	foreach ($cuidadores as $key => $value) {
		vlz_actualizar_ratings($value->id_post);
	} ?>

	<style type="text/css">
		/*
		.vlz_contenedor_galeria {
			height: auto;
			overflow: hidden;
		}
		.vlz_contenedor_galeria_interno{
			width:auto !important;
			text-align: center;
		}
		.vlz_item{
			height: 0;
			width: 100%;
			max-width: 250px;
			padding-top: 25%;
			float: none;
			display: inline-block;
		}
		*/
	</style>

	<div class="vlz_contenedor">

		<div class="vlz_contenedor_header">

			<div class='vlz_lados'>
				<div class="vlz_img_portada">
	                <div class="vlz_img_portada_fondo easyload" data-original="<?php echo $foto; ?>" style="background-image: url(); filter:blur(2px);"></div>
	                <div class="vlz_img_portada_normal easyload" data-original="<?php echo $foto; ?>" style="background-image: url();"></div>
	            </div>
			</div>

			<div class='vlz_lados'>
				<h1 class="center-white"><?php the_title(); ?></h1>
				<?php echo kmimos_petsitter_rating($post_id); ?>

				<?php 
					if(is_user_logged_in()){
						echo '<a class="theme_button button conocer-cuidador" href="'.get_home_url().'/conocer-al-cuidador/?id='.$post_id.'">Conocer al Cuidador</a>';
						// ******************************************
						// BEGIN Imprime boton Reserva segun su busqueda
						// ******************************************
						include("vlz/seleccion_boton_reserva.php");
						// END Imprime boton Reserva segun su busqueda
						
					}else{ ?>
						<span 
							class="theme_button button conocer-cuidador" 
							onclick="jQuery('#pf-login-trigger-button').click();"
						>Conocer al Cuidador</span>
						<span 
							class="button reservar" 
							onclick="jQuery('#pf-login-trigger-button').click();"
						>Reservar</span>
				<?php } ?>		

			</div>
		</div>
		

			<!-- Italo Sprint 2 -->
			<div class="vlz_separador"></div>
			<h3 class="vlz_titulo">Estos son mis servicios</h3>
			<div class="vlz_seccion">

				<?php

					$args = array(
						'post_type' => 'product',
				        'post_status' => 'publish',
				        'author' => $cuidador->user_id
				    );

				    $products = get_posts( $args );

				    $ids = '';
				    foreach($products as $product){
				        if( $ids != '') $ids .= ',';
				        $ids .= $product->ID;
				    }

				    if($ids != ''){
				        $comando = '[products ids="'.$ids.'"]';
				        echo do_shortcode($comando);
				    }

				?>

			</div>
			<!-- Italo Sprint 2 -->

		<?php if( $descripcion != "" ){ ?>
			<div class="vlz_separador"></div>
			<h3 class="vlz_titulo">Descripción del Cuidador</h3>
			<div class="vlz_seccion vlz_descripcion">
				<p> <?php echo $descripcion; ?> </p>
			</div>
		<?php } ?>

		<?php if( $galeria != "" ){ ?>
			<div class="vlz_separador"></div>
			<h3 class="vlz_titulo">Mi Galería</h3>
			<div class="vlz_seccion vlz_descripcion">
				<?php echo $galeria; ?>
			</div>
		<?php } ?>

		<div class="vlz_separador"></div>

			<h3 class="vlz_titulo">Detalles del Cuidador</h3>
			<div class="vlz_seccion">

				<div class="vlz_detalles">
					<div class="vlz_item_detalles">
						<p class="label text-gray">Tipo de propiedad</p>
						<div class="icon">
							<img alt="Detalles casa" height="32px" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/images/casa.png">
						</div>
						<?php $housings = array('1'=>'Casa','2'=>'Departamento'); ?>
						<p class="label-small">
							<b><?php echo $housings[ $atributos['propiedad'] ]; ?></b>
						</p>
					</div>

					<div class="vlz_item_detalles">
						<p class="label text-gray">Tamaños aceptados</p>
						<div class="icon"><img alt="Detalles perro grande" height="32px" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/images/detalles-perro-grande.png"></div>
						<p class="label-small">
							<?php 
								if( count($aceptados) > 0 ){
									$tams_acep = '<br>('.implode(', ',$aceptados).')';
								}else{
									$tams_acep = "Todos";
								}
								echo '<b>'.$tams_acep.'</b>';
							?>
						</p>
					</div>

					<div class="vlz_separador_item"></div>

					<div class="vlz_item_detalles">
						<p class="label text-gray">Edades aceptadas</p>
						<div class="icon">
							<img alt="Detalles edad perro cachorro" height="32px" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/images/detalles-edad-perro-cachorro.png">
						</div>
						<p class="label-small">
							<?php echo '<b>'.implode(', ',$edades_aceptadas).'</b>'; ?>
						</p>
					</div>

					<div class="vlz_item_detalles">
						<p class="label text-gray">Años de experiencia</p>
						<div class="icon">
							<img alt="Detalles experiencia" height="32px" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/images/detalles-experiencia.png">
						</div>
						<p class="label-small"> <?php echo "<b>".$anios_exp."</b>"; ?> </p>
					</div>
				</div>

			</div>

		<div class="vlz_separador"></div>

			<h3 class="vlz_titulo">Otros Detalles</h3>
			<div class="vlz_seccion">

				<div class="vlz_detalles">
					<div class="vlz_item_detalles">
						<p class="label text-gray">Mascotas en casa</p>
						<div class="icon">
							<img alt="Otros detalles otros perros" height="32px" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/images/otros-detalles-otros-perros.png">
						</div>
						<?php 
							if($cuidador->num_mascotas+0 > 0){ 
								if( count($mascotas_cuidador) > 0 ){
									$tams = '<br>('.implode(', ',$mascotas_cuidador).')';
								}else{
									$tams = "";
								} ?>
							<p class="label-small"> <?php echo "<b>".$cuidador->num_mascotas." Perro(s) en casa {$tams}</b>"; ?> </p>
						<?php }else{ ?>
							<p class="label-small"> <?php echo "<b>No tiene mascotas propias</b>"; ?> </p>
						<?php } ?>
					</div>

					<div class="vlz_item_detalles">
						<p class="label text-gray">Mi propiedad</p>
						<div class="icon"><img alt="Otros detalles patio" height="32px" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/images/otros-detalles-patio.png"></div>
						<p class="label-small">
							<b><?php echo ( $atributos['yard'] == 1 ) ? 'Tiene patio' : 'No tiene patio'; ?></b>
						</p>
					</div>

					<div class="vlz_separador_item"></div>

					<div class="vlz_item_detalles">
						<p class="label text-gray">Mi propiedad</p>
						<div class="icon"><img alt="Otros detalles areas verdes" height="32px" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/images/otros-detalles-areas-verdes.png"></div>
						<p class="label-small">
							<b><?php echo ( $atributos['green'] == 1 ) ? 'Tiene áreas verdes' : 'No tiene áreas verdes'; ?></b>
						</p>
					</div>

					<div class="vlz_item_detalles">
						<p class="label text-gray"># Perros aceptados</p>
						<div class="icon"><img alt="Otros detalles cantidad perros" height="32px" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/images/otros-detalles-cantidad-perros.png"></div>
						<p class="label-small">
							<b><?php echo $cuidador->mascotas_permitidas; ?> </b>
						</p>
					</div>

				</div>

			</div>

		<div class="vlz_separador"></div>

			<h3 class="vlz_titulo">Mi Ubicaci&oacute;n</h3>
			<div class="vlz_seccion">

				<iframe id="petsitter-map" src="<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/mapa.php?lat=<?php echo $latitud; ?>&lng=<?php echo $longitud; ?>" width="100%" height="300" style="border:none"></iframe>

			</div>


			<!-- Italo Sprint 2 
			<div class="vlz_separador"></div>
			<h3 class="vlz_titulo">Estos son mis servicios</h3>
			<div class="vlz_seccion">

				<?php

					$args = array(
						'post_type' => 'product',
				        'post_status' => 'publish',
				        'author' => $cuidador->user_id
				    );

				    $products = get_posts( $args );

				    $ids = '';
				    foreach($products as $product){
				        if( $ids != '') $ids .= ',';
				        $ids .= $product->ID;
				    }

				    if($ids != ''){
				        $comando = '[products ids="'.$ids.'"]';
				        echo do_shortcode($comando);
				    }

				?>

			</div> -->

		<?php if( $atributos['video_youtube'][0] != ''){ ?>

			<div class="vlz_separador"></div>

				<?php
					$video = $atributos['video_youtube'];
					preg_match_all('#v=(.*?)#', $video, $encontrados);
				?>
				<h3 class="vlz_titulo">Este es el video que el cuidador subió a Youtube.</h3>
				<div class="vlz_seccion">
					<iframe id="video_youtube" width="100%" src="https://www.youtube.com/embed/<?php echo $video; ?>" frameborder="0" allowfullscreen></iframe>
				</div>

		<?php } ?>

		<?php
			$comments = count( get_comments('post_id='.$post->ID) );
			//if( $comments > 0 ){ ?>
				<div class="vlz_separador"></div>
				<h3 class="vlz_titulo">Valoraciones</h3>
				<div class="vlz_seccion">
					<?php  comments_template(); ?>
				</div> <?php
			//}
		?>

	</div>

<?php get_footer(); ?>