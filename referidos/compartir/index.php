<?php
require_once("../../vlz_config.php");
global $url_base;

$url="";
$ssl =  ($_SERVER['SERVER_PORT']==443)? 's':'' ;
if(isset($_GET['e'])){
	$url = "http".$ssl."://".$_SERVER['HTTP_HOST']."/referidos/?r=".md5($_GET['e']);
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
        <link rel="stylesheet" href="css/animate.css">

		<meta property="og:url"           content="<?php echo $url; ?>" />
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="Kmimos - Clientes Referidos" />
		<meta property="og:description"   content="Suma huellas a nuestro club y gana descuentos" />
		<meta property="og:image"         content="<?php echo $url; ?>" />
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-56422840-1', 'auto');
		  ga('send', 'pageview');
		</script>


	</head>
	<body>
		<!-- Load Facebook SDK for JavaScript -->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.9";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<div id="contenidos"></div>
		<div class="container">

			<header class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
				<img src="img/dogs-top.jpg" class="img-responsive" width="100%" alt="">
				<h1 class="text-center" style="font-size: 1.8em;">Suma huellas a nuestro club y gana descuentos</h1>
			</header>

			<section class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 text-center">
				<div class="row">
					<section class="col-xs-12 col-sm-4 col-md-3  text-right">
						<span id="bloque1">Es muy sencillo:</span><br>
					</section>
					<section id="bloque3" class="bloque col-xs-12 col-sm-8 col-md-9 ">
						<span>Por cada amigo que complete una reservaci&oacute;n, t&uacute; ganas 
						<span class="resaltar">150$</span> acumulables hasta  <span class="resaltar">750$</span> y tu amigo gana otros <span class="resaltar">150$</span>
						</span>
					</section>
					<section class="col-xs-12 col-sm-12 col-md-12 wow">
						<h2 class="text-center" style="font-size: 1.8em; color: #881c9b;font-weight: bold;">Picale 
						AQU&Iacute; y <label id="label-animated" class="animated flip"> comparte </label> con tus amigos</h2>
					</section>
					<div class="col-xs-12 col-sm-12 col-md-12 text-center">
						<section id="shared" class="text-center row animated bounceIn">
							<?php if(!empty($url)){ ?>
								<span id="twitter_shared" data-target="1" class="button-shared" >
									<img src="img/btntwitter.png" width="50px">
								</span>
								<span id="facebook_shared" data-target="1" class="button-shared">
									<img src="img/btnfacebook.png" width="50px">
								</span>
								<span id="mail_publicar" data-target="1" class="button-shared">
									<img src="img/btnemail.png" width="50px">
								</span>
							<?php } else { ?>
								<a href="/referidos" class="btn btn-md" style="background: #9F159F; border-color:#9F159F;color:#fff;">Obtener enlace</a>
							<?php } ?>
						</section>					
					</div>
					
				</div>	

			</section>

			<section style="padding: 0px; " class="clearfix  col-xs-12 col-sm-12 col-md-8 col-md-offset-2  text-center">
				
				<!-- Link Twitter -->	
				<div style="padding: 0px; " id="twitter" class="col-sm-12 col-xs-12 col-md-12 col-lg-12 clearfix hidden">
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
				<div  style="padding: 0px; " id="facebook" class="col-sm-12 col-xs-12 col-md-12 col-lg-12 clearfix hidden">
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
				<div  style="padding: 0px; " id="info" class="col-sm-12 col-xs-12 col-md-12 col-lg-12 clearfix hidden">
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
						<div class="shared-link-email"><?php echo $url;?></div>
					</div>
				</div>

			</section>

			<aside class="clearfix footer-content col-xs-12 col-sm-12 col-md-8 col-md-offset-2 text-left">
					<span>Amigos referidos: </span>
					<br class="hidden-md hidden-lg">
					<img src="img/1.png">
					<span style="margin:10px;">¡Bien hecho!</span>
					<br class="hidden-md hidden-lg">
					<img src="img/5.png">
					<span style="margin:10px;">¡Muy influyente!</span>
					<br class="hidden-md hidden-lg">
					<img src="img/10.png">
					<span style="margin:10px;">¡Guau!</span>
			</aside>

			<aside class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h2 class="text-center" style="font-size: 1.8em; color: #881c9b;font-weight: bold;">¡Sigue ganando!</h2>
			</aside>

			<button class="btn-cerrar hidden" id="close"><i class="fa fa-close"></i> </button>

		</div>
	


		<script
		  src="https://code.jquery.com/jquery-2.2.4.min.js?<?php echo time(); ?>"
		  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		  crossorigin="anonymous"></script>

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<script>
		var url = "<?php echo $url_base; ?>";
		var click_action = 0;
		$(document).ready(function(){

			$("#mail_publicar").click(function(){

		        if(!$("#twitter").hasClass('hidden')){ $("#twitter").addClass('hidden'); }
		        if(!$("#facebook").hasClass('hidden')){ $("#facebook").addClass('hidden'); }

		        if($("#info").hasClass('hidden')){
		        	$("#info").removeClass('hidden');
					send_track('<?php echo $_GET['e']; ?>','referidos_email');
					ga('send', 'pageview', 'referidos_shared_email');
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
					send_track('<?php echo $_GET['e']; ?>','referidos_facebook');				
					ga('send', 'pageview', 'referidos_shared_facebook');				
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
					send_track('<?php echo $_GET['e']; ?>','referidos_twitter');
					ga('send', 'pageview', 'referidos_shared_twitter');				
				}else{
		        	$("#twitter").addClass('hidden');
				}
				click_action = 1;
		    });
			
			// $("#close").on('click', function(){
			// 	if(click_action==1){
			// 		window.close();
			// 	}else{
			// 		$('#contenido').append(
			// 			$('<div id="alert-warning" role="alert"style="position:absolute;top:0px;z-index:999;margin-bottom:0px!important;width:100%;"class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Hola!</strong> Hemos detectado que no compartiste tu link, debes hacerlo para ganar mas referidos.</div>')
			// 		);
			// 	}
			// });

			// window.onbeforeunload = function(e) {
			//    $("#close").click();
			// };

			function send_track(email, option){
				$.ajax( url+"landing/list-subscriber-tracking.php?email="+email+"&option="+option )
				.done(function() {
					console.log( 'success track: '+option );
				})
				.fail(function() {
					console.log( 'error al track: '+option );
				});  
			}

			function testAnim(x) {
				$('#label-animated').removeClass().addClass(x + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
					$(this).removeClass();
				});
			};
			
			var id = setInterval(frame, 10);
			var width = 0;
			function frame() {
				if (width == 100) {
					clearInterval(id);
				} else {
					testAnim( 'flip' );
					width++;
				}
			}
		});
		</script>

	</body>
</html>