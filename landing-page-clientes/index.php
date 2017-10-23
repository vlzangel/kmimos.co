<?php include_once(dirname(__DIR__).'/wp-load.php'); ?>
<!DOCTYPE html>
<html> 
    <head>
		<?php wp_head(); ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Kmimos Clientes</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,900" rel="stylesheet">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/kmimos.css">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-93578532-1', 'auto');
		  ga('send', 'pageview');

		</script>

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
					'<div class="section section2"><span><strong>&#161;SUSCR&Iacute;BETE!</strong> y recibe el Newsletter con nuestras <strong>PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS,</strong> etc.!</span><?php echo subscribe_input('land-cl'); ?></div>';


				SubscribePopUp_Create(html);
			}

			jQuery(document).ready(function(e){
				SubscribeTime = setTimeout(function(){
					SubscribeSite();
				}, 7400);
			});
		</script>
    </head>
    <body>
       
       	<div class="container-fluid">

		<section class="row " id="section-1">
			<header class="text-center">
	       	 	<img src="img/LogoKmimos.png" class="logo">
	       	</header>

	       	<article class="col-sm-5 hidden-xs">
	       		<img src="img/Character_section1.png" class=" img-kmimos img-responsive">
	       	</article>
       	 	<article class="col-sm-7">
				<h1>Los Hoteles para perros ponen a sus hu&eacute;spedes en jaulas
				<strong>Kmimos los pone en casa de cuidadores certificados</strong>
				</h1>
       	 	</article>
	       	<article class="img-section-1 col-sm-5 pull-left  hidden-md hidden-sm hidden-lg">
	       		<img src="img/Character_section1.png" class="img-kmimos img-responsive">
	       	</article>

       	 	<article class="col-xs-12 text-center">
	       	 	<a href="/?home&utm_source=youtube&utm_medium=landing_page&utm_campaign=buscar_cuidador_disponible&utm_term=cuidador%2Bperros%2Bcolombia&utm_content=landing_page#jj-landing-page" class="btn btn-kmimos">Buscar Cuidadores Disponibles</a>
       	 	</article>
       	 	<article class="col-sm-12 text-center">
				<a  href="#section-2" 
					class="controll-pagination back-white">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
					<!-- img src="img/7.png" -->
				</a>
       	 	</article>
		</section>
		<div class="clearfix"></div>

		<section class="row" id="section-3">
			<br><br>
       	 	<article class="container">
       	 		<div class="col-sm-2">
       	 			<img src="img/shape 1.png">
       	 		</div>
       	 		<div class="col-sm-10  col-sm-offset-0">
	       	 		<h2 class="center-subtitle">Tu mejor Amigo se queda en Casa del Cuidador Certificado</h2>
	       	 	</div>
       	 	</article>
       	 	<hr>
       	 	<article class="container">
       	 		<div class="col-sm-10 text-right col-sm-offset-0">
	       	 		<h2  class="center-subtitle">Duerme como Rey.
	       	 		<br>En Salas, sofas o Hasta en la cama del cuidador</h2>
	       	 	</div>
       	 		<div class="col-sm-2">
       	 			<img src="img/Icon-2.gif">
       	 		</div>
       	 	</article>
       	 	<hr>
       	 	<article class="container">
       	 		<div class="col-sm-2 ">
       	 			<img src="img/shape 7.png">
       	 		</div>
       	 		<div class="col-sm-10  col-sm-offset-0">
	       	 		<h2  class="center-subtitle">Tambien tendr&aacute; Cobertura Veterinaria en caso de que sea necesario</h2>
	       	 	</div>
       	 	</article>
       	 	<hr>
       	 	<article class="container">
       	 		<div class="col-sm-10">
	       	 		<h2 class=" text-right center-subtitle">El Costo depende del Cuidador y del Tamaño de tu Perro.
	       	 		<br>Por ejemplo: Un perro Pequeño cuesta alrededor de 25.000 pesos la noche</h2>
	       	 		<br>
	       	 		<br>
	       	 	</div>
       	 		<div class="col-sm-2 ">
       	 			<img src="img/shape 4.png">
       	 		</div>
       	 	</article>
	       	<div class="col-sm-12 text-center">
			<a  
				href="#section-4" 
				class="controll-pagination back-green">
				<i class="fa fa-angle-down" aria-hidden="true"></i>
			</a>
			</div>
		</section>
		<div class="clearfix"></div>

		<section class="row" id="section-6">
			<div class="col-sm-10 col-sm-offset-1">			
				<aside class="col-sm-2 hidden-sm hidden-xs">
					<img src="img/object 1.png" width="80px">
				</aside>
				<div class="col-sm-7 text-center container-iframe">
					<article class="video video-container">
						<iframe src="https://www.youtube.com/embed/ZWIRhPjkRG0" frameborder="0" allowfullscreen></iframe>
					</article>
				</div>
				<aside class="col-sm-2">
					<img src="img/Character 5.png" class="img-responsive ">
				</aside>
			</div>
		</section>
		<div class="clearfix"></div>





		<section class="row" id="section-4">
				
       	 	<article class="container text-center">
				<h3 class="subtitle-4 title">PASOS PARA RESERVAR</h3>
			</article>
   	 		<article class="container text-center">
			
   	 			<div class="col-sm-4 center-subtitle">
   	 				<img src="img/Icon 1.png">
   	 				<h2>Busca y Compara Cuidadores Cerca de Ti</h2>
   	 			</div>

				<div style="position:absolute;left:0px;"class="col-sm-5 hidden-xs hidden-sm arrow-left"><img src="img/Arrow 1.png"></div>

   	 			<div class="col-sm-4 center-subtitle">
   	 				<img src="img/Icon 2.png">
   	 				<h2>Reservas la Estadia de Tu Perro</h2>
   	 			</div>

				<div style="position:absolute;right:0px;"class="col-sm-5 hidden-xs hidden-sm arrow-right"><img src="img/Arrow 1.png"></div>

   	 			<div class="col-sm-4 center-subtitle">
   	 				<img src="img/Icon 3.png">
   	 				<h2>Tu Perro se va a la Casa del Cuidador</h2>
   	 			</div>
   	 		</article>
   	 		<article class="container text-center">
   	 			<div class="row">
 			
					<div style="position:absolute;left:0px;" class="col-sm-3  hidden-xs hidden-sm arrow-2">
						<img src="img/Arrow 1.png">
	   	 			</div>
					<div class="col-sm-offset-2 col-sm-4">
	   	 				<img src="img/Icon 4.png">
	   	 				<h2>Recibes Fotos y Videos Diarios De tu Perro</h2>  	 			
	   	 			</div>

					<div style="position:absolute;left:45%;" class="col-sm-1 hidden-xs hidden-sm arrow-2">
						<img src="img/Arrow 1.png">
	   	 			</div>		
					<div class="col-sm-4">
	   	 				<img src="img/Icon 5.png">
	   	 				<h2>Tu Perro Regresa Feliz</h2>
	   	 			</div>   	 			
	
	   	 		</div>

   	 		</article>
	       	<div class="col-sm-12 text-center">
			<a  
				href="#section-5" 
				class="controll-pagination back-white">
				<i class="fa fa-angle-down" aria-hidden="true"></i>
			</a>
			</div>
		</section>
		<div class="clearfix"></div>

	</div>
	<footer class="text-center">
		<h2>NUNCA HA SIDO TAN FACIL ENCONTRAR UN CUIDADOR QUE AME TANTO A LOS PERROS COMO TU</h2>
		<a href="/?home&utm_source=youtube&utm_medium=landing_page&utm_campaign=buscar_cuidador_disponible&utm_term=cuidador%2Bperros%2Bcolombia&utm_content=landing_page#jj-landing-page" class="btn btn-kmimos">Buscar Cuidadores Disponibles</a>
		<aside class="text-center">
       	 	<img src="img/LogoKmimos.png" width="150px">
       	</aside>
	</footer>

	<script
	  src="https://code.jquery.com/jquery-2.2.4.min.js"
	  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
	  crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
    
    </body>
</html>
