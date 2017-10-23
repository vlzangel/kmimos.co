<?php
$url="";
if(isset($_GET['e'])){
	$url = "https://www.kmimos.com.mx/referidos/?r=".md5($_GET['e']);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Kmimos - Clientes Referidos</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/kmimos.css">

		<meta property="og:url"           content="<?php echo $url; ?>" />
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="Kmimos - Clientes Referidos" />
		<meta property="og:description"   content="Suma huellas a nuestro club y gana descuentos" />
		<meta property="og:image"         content="https://www.kmimos.com.mx/referidos" />
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-56422840-1', 'auto');
		  ga('send', 'pageview');
		</script>

	</head>
	<body onBeforeUnload="cerrarventana()">
		<!-- Load Facebook SDK for JavaScript -->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.9";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<div class="container">
			
			<header class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
				<img src="img/dogs-top.jpg" class="img-responsive" width="100%" alt="">
				<div class="col-md-12"><h1 id="subtitulo" class="col-md-offset-2 col-md-8 text-center">
					¡Felicidades, ya formas parte de nuestro Club!
				</h1><br>
				</div>
				<h1 class="text-center" style="font-size: 2.14em;">Suma huellas y gana descuentos</h1>
			</header>

			<section class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 text-center">
				<div class="row">
					<section class="col-xs-12 col-sm-4 col-md-3  text-right">
						<span id="bloque1">Es muy sencillo:</span><br>
					</section>

					<div class="col-xs-12 col-sm-4 col-md-4">
						<section id="bloque2" class="bloque">
							<span>Comparte a trav&eacute;s de los botones e invita a tus amigos a unirse a nuestro club.</span>
						</section>
						<section id="shared" class="text-right row">
							<?php if(!empty($url)){ ?>
							<div class="col-xs-4 col-sm-4 col-sm-4">
								<span id="twitter_shared" data-target="1">
									<img src="img/btntwitter.png" width="50px">
								</span>
							</div>
							<div class="col-xs-4 col-sm-4 col-sm-4">
								<span id="facebook_shared" data-target="1">
									<img src="img/btnfacebook.png" width="50px">
								</span>
							</div>
							<div class="col-xs-4 col-sm-4 col-sm-4">
								<span id="mail_publicar" data-target="1">
									<img src="img/btnemail.png" width="50px">
								</span>
							</div>
							<?php } else { ?>
								<a href="/referidos" class="btn btn-md" style="background: #9F159F; border-color:#9F159F;color:#fff;">Obtener enlace</a>
							<?php } ?>
						</section>					
					</div>
					<section id="bloque3" class="bloque col-xs-12 col-sm-4 col-md-4 ">
						<span>Por cada amigo que complete una reservaci&oacute;n, t&uacute; ganas 
						<span class="resaltar">150$</span> acumulables hasta  <span class="resaltar">750$</span> y tu amigo gana otros <span class="resaltar">150$</span>
						</span>
					</section>

				</div>	


				<!-- Link Twitter -->	
				<div id="twitter" class="col-sm-11 clearfix hidden">
					<div class="fondo-verde">
						<h3 class="text-center">
							¿Y ahora qué sigue?
						</h3>
						<div class="text-left">							
							<span><strong>Paso 1: COMPARTE.</strong> Te vamos a redirigir a tu red social twitter para que compartas el siguiente enlace con tus amigos.</span><br>
							
							<span><strong>Paso 2: ESPERA.</strong> Cuando tus amigos se unan al club gracias a tu referencia, te notificaremos con un email.</span><br>
							
							<span><strong>Paso 3: GANA.</strong> Cuando alguno de tus referidos haga su primera reserva con Kmimos, te avisaremos con un email. Este es el momento en el que puedes hacer válida tu recompensa.</span><br>
						</div>
						<br>	
						<a class="btn btn-info twitter-share-button"
						  href="https://twitter.com/intent/tweet?text=Suma%20huellas%20y%20gana%20descuentos%20<?php echo $url;?>"
						  target="_blank">
							<i class="fa fa-twitter"></i> Tweet
						</a>
					</div>
				</div>

				<!-- Link Facebook -->	
				<div id="facebook" class="col-sm-11 clearfix hidden">
					<div class="fondo-verde">				
						<h3 class="text-center">
							¿Y ahora qué sigue?
						</h3>
						<div class="text-left">							
							<span><strong>Paso 1: COMPARTE.</strong> Te vamos a redirigir a tu red social facebook para que compartas el siguiente enlace con tus amigos.</span><br>
							
							<span><strong>Paso 2: ESPERA.</strong> Cuando tus amigos se unan al club gracias a tu referencia, te notificaremos con un email.</span><br>
							
							<span><strong>Paso 3: GANA.</strong> Cuando alguno de tus referidos haga su primera reserva con Kmimos, te avisaremos con un email. Este es el momento en el que puedes hacer válida tu recompensa.</span><br>
						</div>
						<br>	
						<div class="fb-share-button" data-href="<?php echo $url;?>" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u&amp;src=sdkpreparse">Compartir</a></div>
					</div>
				</div>

				<!-- Link Email -->
				<div id="info" class="col-sm-11 clearfix hidden">
					<div class="fondo-verde">
						<h3 class="text-center">
							¿Y ahora qué sigue?
						</h3>
						<div class="text-left">							
							<span><strong>Paso 1: COMPARTE.</strong> Copia y pega el enlace que aparece en este mensaje. Envíalo a tus amigos por email.</span><br>
							
							<span><strong>Paso 2: ESPERA.</strong> Cuando tus amigos se unan al club gracias a tu referencia, te notificaremos con un email.</span><br>

							<span><strong>Paso 3: GANA.</strong> Cuando alguno de tus referidos haga su primera reserva con Kmimos, te avisaremos con un email. Este es el momento en el que puedes hacer válida tu recompensa.</span><br>
						</div>
						<br>
						<strong class="shared-link-email"><?php echo $url;?></strong>
					</div>
				</div>
			</section>

			<aside class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 text-center">
				<img src="img/opciones.png" class="img-responsive hidden-xs hidden-sm " >
				<img src="img/opciones_mobile.png" class="img-responsive hidden-md hidden-lg" >
			</aside>


			<p id="shared" class="hidden">Debes compartir el enlace</p>

		</div>
	
		<script
		  src="https://code.jquery.com/jquery-2.2.4.min.js?<?php echo time(); ?>"
		  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		  crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<script>
		var click_action = 0;
		$(document).ready(function(){

			$("#mail_publicar").click(function(){
		        if(!$("#twitter").hasClass('hidden')){ $("#twitter").addClass('hidden'); }
		        if(!$("#facebook").hasClass('hidden')){ $("#facebook").addClass('hidden'); }

		        if($("#info").hasClass('hidden')){
		        	$("#info").removeClass('hidden');
				}else{
		        	$("#info").addClass('hidden');
				}
				click_action = 1;
		    });

			$("#facebook_shared").click(function(){
		        if(!$("#twitter").hasClass('hidden')){ $("#twitter").addClass('hidden'); }
		        if(!$("#info").hasClass('hidden')){ $("#info").addClass('hidden'); }

		        if($("#facebook").hasClass('hidden')){
		        	$("#facebook").removeClass('hidden');
				}else{
		        	$("#facebook").addClass('hidden');
				}
				click_action = 1;
		    });

			$("#twitter_shared").click(function(){
		        if(!$("#facebook").hasClass('hidden')){ $("#facebook").addClass('hidden'); }
		        if(!$("#info").hasClass('hidden')){ $("#info").addClass('hidden'); }

		        if($("#twitter").hasClass('hidden')){
		        	$("#twitter").removeClass('hidden');
				}else{
		        	$("#twitter").addClass('hidden');
				}
				click_action = 1;
		    });
			
		});

		function cerrarventana() {
			event.returnValue = ""; 
		}
		</script>

	</body>
</html>