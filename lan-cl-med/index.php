<?php include_once(dirname(__DIR__).'/wp-load.php'); ?>
<!DOCTYPE html>
<html> 
    <head>
		<?php wp_head(); ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Kmimos Animado</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/animate.css">
		<link rel="stylesheet" href="css/kmimos.css">
		<link rel="stylesheet" href="css/style.css">

		<script src="js/jquery/jquery.js"></script>
		<script src="js/script.js"></script>

		<style type="text/css">
			#PageSubscribe{position:relative; max-width: 700px;  margin: 0 auto;  padding: 25px;  top: 75px; border-radius: 20px;  background: #ba2287;  overflow: hidden;}
			#PageSubscribe .exit{float: right; cursor: pointer;}
			#PageSubscribe .section{ width: 50%; padding: 10px; float: left; font-size: 17px; text-align: left;}
			#PageSubscribe .section.section1{font-size: 20px;}
			#PageSubscribe .section.section1 span{font-size: 25px;}
			#PageSubscribe .section.section1 .images{padding:10px 0; text-align: center;}
			#PageSubscribe .section.section3{width: 100%; font-size: 17px; font-weight: bold; text-align: center;}
			#PageSubscribe .section.section2{}
			#PageSubscribe .section.section2 .message{font-size: 15px; border: none; background: none; opacity:0; visible: hidden; transition: all .3s;}
			#PageSubscribe .section.section2 .message.show{opacity:1; visible:visible;}
			#PageSubscribe .section.section2 .icon{width: 30px; padding: 5px 0;}
			#PageSubscribe .section.section2 .subscribe {margin: 20px 0;  }
			#PageSubscribe .section.section2 form{margin: 0; display:flex;}
			#PageSubscribe .section.section2 input,
			#PageSubscribe .section.section2 button{width: 100%; max-width: calc(100% - 60px); margin: 5px; padding: 5px 10px; color: #CCC; font-size: 15px; border-radius: 20px;  border: none; background: #FFF; }
			#PageSubscribe .section.section2 button {padding: 10px;  width: 40px;}

			@media screen and (max-width:480px), screen and (max-device-width:480px) {
				#PageSubscribe { top: 15px;}
				#PageSubscribe .section{ width: 100%; padding: 10px 0; font-size: 12px;}
				#PageSubscribe .section.section1 {font-size: 15px;}
				#PageSubscribe .section.section1 span {font-size: 20px;}
				#PageSubscribe .section.section3 {font-size: 12px;}
			}

			.container-fluid {
				padding-right: 0;
				padding-left: 0;
			}
			.row {
				margin-right: 0;
				margin-left: 0;
			}
		</style>

		<script type='text/javascript'>
			//Subscribe
			function SubscribeSite(){
				clearTimeout(SubscribeTime);

				var dog = '<img height="70" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-09.png">' +
					'<img height="20" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-10.png">';

				var html='<div id="PageSubscribe"><i class="exit fa fa-times" aria-hidden="true" onclick="SubscribePopUp_Close(\'#message.Msubscribe\')"></i>' +
					'<div class="section section1"><span>G&aacute;nate <strong>COP 8,000</strong> en tu primera reserva</span><br>&#8216;&#8216;Aplica para clientes nuevos&#8217;&#8217;<div class="images">'+dog+'</div></div>' +
					'<div class="section section2"><span><strong>&#161;SUSCR&Iacute;BETE!</strong> y recibe el Newsletter con nuestras <strong>PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS,</strong> etc.!</span><?php echo subscribe_input('lan-cl-med'); ?></div>' +
					'<div class="section section3">*Dentro de 48 hrs. Te enviaremos v&iacute;a email tu c&uacute;pon de descuento</div>' +
					'</div>';


				SubscribePopUp_Create(html);
			}

			jQuery(document).ready(function(e){
				SubscribeTime = setTimeout(function(){
					SubscribeSite();
				}, 7400);
			});
		</script>
    </head>

    <body id="waypoint">

		<section class="contain_window viaje" style="background:url(img/new/medellin.jpg) center/cover no-repeat; ">
			<div class="header">
				<img src="img/LogoKmimos.png" class="logo">
			</div>
			<div class="content contain wow zoomIn">
				<p>Te vas de viaje?</p>
				<p>Necesitas que alguien cuide de tu mascota?</p>
				<p><strong>Libre de Jaulas</strong></p>
			</div>
			<div class="action">
				<a href="http://kmimos.co?utm_source=landing&utm_campaign=usuarios_landing_medellin&utm_term=cuidado_perro_hospedaje_paseos#jj-landing-page" tile="kmimos">
					<div class="button wow zoomIn">BUSCAR CUIDADORES DISPONIBLES</div>
				</a>
				<div>
					<i class="down fa fa-angle-down" aria-hidden="true"></i>
				</div>
			</div>
		</section>


		<section class="contain_window red" style="background:url(img/new/fondo.jpg) center bottom/cover no-repeat; ">
			<div class="content contain wow zoomIn">
				<p>Quieres dejar al ser más querido de tu familia, seguro que te lo cuidarán como en casa?</p>
				<p><strong>Tenemos una red con más de 50 cuidadores certificados para que lo reciban en sus hogares.</strong></p>
			</div>
			<div class="images">
				<div class="group group1 wow bounceInLeft">
					<img id="img1" class="image" src="img/new/img1.png"/>
					<img id="img4" class="image" src="img/new/img-can1.png"/>
				</div>
				<div class="group group3 wow bounceInRight">
					<img id="img3" class="image" src="img/new/img3.png"/>
					<img id="img6" class="image" src="img/new/img-can3.png"/>
					<img id="img7" class="image" src="img/new/img-ball3.png"/>
				</div>
				<div class="group group2 wow zoomIn">
					<img id="img2" class="image" src="img/new/img2.png"/>
					<img id="img5" class="image" src="img/new/img-can2.png"/>
				</div>
			</div>
			<div class="action">
				<i class="down fa fa-angle-down" aria-hidden="true"></i>
			</div>
		</section>


		<section id="testimony" class="contain_window" style="background-image:url(img/new/fondo.jpg); ">
			<div class="image"></div>
			<div class="header">
				TESTIMONIOS
			</div>
			<div class="content">
				<div class="contain">
					<div class="navigate">
						<div class="direction" data-direction="ant"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
						<div class="direction" data-direction="sig"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
					</div>
					<div class="testimonials wow zoomIn">
						<div class="testimony" data-img="img/new/caregiver/medellin-1.jpg">
							<p class="title"></p>
							<p>"De verdad que los recomiendo, dejé a mi pequeña durante una semana y siempre estuvimos en contacto."</p>
						</div>
						<div class="testimony" data-img="img/new/caregiver/medellin-2.jpg">
							<p class="title"></p>
							<p>"De verdad mi bebe la paso increíble y yo pude estar tranquila porque la deje como en casa."</p>
						</div>
						<div class="testimony" data-img="img/new/caregiver/medellin-3.jpg">
							<p class="title"></p>
							<p>"Mi bebé siempre llega cansado de jugar. Gracias a Kmimos por cuidadores de esta calidad!"</p>
						</div>
					</div>
				</div>
			</div>
			<div class="action">
				<i class="down fa fa-angle-down" aria-hidden="true"></i>
			</div>
		</section>



       	<div class="col-sm-12">

			<section class="row" id="section-1" style="display: none;">
				<header class="text-center">
		       	 	<img src="img/LogoKmimos.png" class="logo">
		       	</header>
		       	<article class="col-sm-5 hidden-xs">
		       		<img src="img/Character_section1.png" class=" img-kmimos img-responsive">
		       	</article>
	       	 	<article class="col-sm-5">
					<h1>¡Deja a tu mascota en casa de un cuidador certificado!</h1>
	       	 	</article>
		       	<article class="img-section-1 col-sm-5 pull-left  hidden-md hidden-sm hidden-lg">
		       		<img src="img/Character_section1.png" class="img-kmimos img-responsive">
		       	</article>
	       	 	<article class="col-xs-12 text-center margin-bottom-20">
		       	 	<a href="/?home&utm_source=youtube&utm_medium=landing_page&utm_campaign=buscar_cuidador_disponible&utm_term=cuidador%2Bperros%2Bcolombia&utm_content=landing_page#jj-landing-page" class="btn btn-kmimos">Buscar Cuidador Disponible</a>
	       	 	</article>
			</section>
			<div class="clearfix"></div>


	   	 	<div class="row text-center title"><span>Tu perrito</span></div>
			<section class="row" id="section-2">
		       	 	<article class="col-xs-12 col-sm-12 col-md-6 text-center ">
	       	 			<img src="img/Icon-1.gif" class="wow zoomIn">
		       	 		<h2>Se queda en la casa del cuidador</h2>
		       	 	</article>
		       	 	<article class="col-xs-12 col-sm-12 col-md-6 text-center ">
	       	 			<img src="img/Icon-2.gif" class="wow zoomIn">
		       	 		<h2>Duerme como un rey en salas y sof&aacute;s</h2>
		       	 	</article>
		       	 	<article class="col-xs-12 col-sm-12 col-md-6 text-center ">
	       	 			<img src="img/Icon-3.gif" class="wow zoomIn">
		       	 		<h2>Tendr&aacute; cobertura veterinaria</h2>
		       	 	</article>
		       	 	<article class="col-xs-12 col-sm-12 col-md-6 text-center ">
	       	 			<img src="img/Icon-4.gif" class="wow zoomIn">
		       	 		<h2>El costo depende de su tamaño</h2>
		       	 	</article>
			</section>
			<div class="clearfix"></div>


	   	 	<div class="row text-center title"><span>Pasos para reservar:</span></div>
			<section id="section-3">
		       	 	<article class="row wow bounceInRight" data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-5.gif">
		       	 		</div>
		       	 		<h1>PASO 1</h1>
		       	 		<h3><i class="fa fa-point"></i> Busca cuidadores cerca de tí</h3>
		       	 	</article>
		       	 	<article class="row wow bounceInLeft"  data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-6.gif">
		       	 		</div>
		       	 		<h1>PASO 2</h1>
		       	 		<h3><i class="fa fa-point"></i> Haz la reserva para tu perrito</h3>
		       	 	</article>
		       	 	<article class="row  wow bounceInRight" data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-7.gif">
		       	 		</div>
		       	 		<h1>PASO 3</h1>
		       	 		<h3><i class="fa fa-point"></i> Tu mascota va a casa del cuidador</h3>
		       	 	</article>
		       	 	<article class="row wow bounceInLeft" data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-8.gif">
		       	 		</div>
		       	 		<h1>PASO 4</h1>
		       	 		<h3><i class="fa fa-point"></i> Recibes fotos y videos durante su estad&iacute;a</h3>
		       	 	</article>
		       	 	<article class="row wow bounceInRight" data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-9.gif">
		       	 		</div>
		       	 		<h1>PASO 5</h1>
		       	 		<h3><i class="fa fa-point"></i> &Eacute;l o ella regresa Feliz</h3>
		       	 	</article>
			</section>
			<div class="clearfix"></div>


			<section class="row" id="section-6">
				<div class="col-sm-10 col-sm-offset-1">			
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2 text-center xcontainer-iframe">
							<article class="xvideo xvideo-container">
								<iframe src="https://www.youtube.com/embed/ZWIRhPjkRG0" 
									frameborder="0" allowfullscreen></iframe>
							</article>
							<article class="col-sm-12 text-center" style="padding-top:20px;">
								<a href="http://kmimos.co?utm_source=landing&utm_campaign=usuarios_landing_medellin&utm_term=cuidado_perro_hospedaje_paseos#jj-landing-page" class="btn btn-kmimos">Buscar Cuidadores Disponibles</a>
							</article>
						</div>
					</div>
					<div>
						<aside class="col-sm-2 hidden-sm hidden-xs">
							<img src="img/object 1.png" width="80px">
						</aside>
						<aside class="col-sm-3 pull-right">
							<img src="img/Character 5.png" class="img-responsive ">
						</aside>
					</div>
				</div>

			</section>
			<div class="clearfix"></div>


			<footer class="row text-left">
				<div class="col-md-6 col-sm-offset-1">
					<h1>
						Nunca Ha Sido Tan Fácil Encontrar <br>
						Un Cuidador para tu Mascota
					</h1>
				</div>
				<div class="col-md-5 pull-right text-right circulo">
			       	<img src="img/character 1.png" class="img-cuidador">
				</div>
				<aside class="col-sm-12 text-center">
		       	 	<img src="img/LogoKmimos.png" width="150px">
		   		</aside>
			</footer>
		</div>

		<script
		  src="https://code.jquery.com/jquery-2.2.4.min.js?<?php echo time(); ?>"
		  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		  crossorigin="anonymous"></script>
	    <script src="js/wow.js  "></script>
	    <script src="js/main.js?"></script>
    
    </body>
</html>
