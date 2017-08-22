<?php require_once('db.php');
 
// **************************************
// Acciones
// **************************************
$action = ( array_key_exists('p', $_GET) )? $_GET['p'] : '' ;
$result = 0;
switch ($action) {
	case 'save':
		$result = Wlabel_save($_POST);
		break;	
	default:
		break;
}

print_r($result);

// **************************************
// Funciones
// **************************************
function Wlabel_save($post=[]){

		if( !empty($post) ){
			if( !array_key_exists('titulo', $post) || !array_key_exists('nombre', $post) ){ return 0; }

			$titulo = ( !empty($post['titulo']) )? $post['titulo'] : '' ;
			$nombre = ( !empty($post['nombre']) )? $post['nombre'] : '' ;

			$image = ( array_key_exists('image', $post) )? $post['image'] : '' ;
			$color = ( array_key_exists('color', $post) )? $post['color'] : '' ;
			$color_botones = ( array_key_exists('color_botones', $post) )? $post['color_botones'] : '' ;
			$color_fuentes = ( array_key_exists('color_fuentes', $post) )? $post['color_fuentes'] : '' ;
			$limitday = ( array_key_exists('limitday', $post) )? $post['limitday'] : '' ;
			$css = ( array_key_exists('css', $post) )? $post['css'] : '' ;
			$js = ( array_key_exists('js', $post) )? $post['js'] : '' ;
			$hhtml = ( array_key_exists('hhtml', $post) )? $post['hhtml'] : '' ;
			$fhtml = ( array_key_exists('fhtml', $post) )? $post['fhtml'] : '' ;

			$datos = json_encode([
				"image"=> $image,
				"color"=> [
					"background" => $color,
					"boton" => $color_botones,
					"fuente" => $color_fuentes,
				],
				"limitday"=> $limitday,
				"css"=> $css,
				"js"=> $js,
				"html"=>[
					"header"=> $hhtml,
					"footer"=> $fhtml
				]
			]);

			if( !empty($titulo) && !empty($nombre) ){
				$sql = "
					insert into wp_kmimos_wlabel (
						wp_kmimos_wlabel.title,
						wp_kmimos_wlabel.wlabel,
						wp_kmimos_wlabel.`data`
					) values (
						'{$titulo}',
						'{$nombre}',
						'{$datos}'
					);";
print_r($sql);					
				$result = get_fetch_assoc($sql);
			}
			return $result;
		}
	}
