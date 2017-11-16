<?php include_once(dirname(__DIR__).'/wp-load.php'); ?>
<!DOCTYPE html>
<html> 
    <head>
    	<?php wp_head(); ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Quiero ser cuidador</title>

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
						'<div class="section section2"><span><strong>&#161;SUSCR&Iacute;BETE!</strong> y recibe el Newsletter con nuestras <strong>PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS,</strong> etc.!</span><?php echo subscribe_input('land-cl'); ?></div>' +
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
				<h1>Kmimos es un servicio digital que 
				<strong>conecta Doglovers como t&uacute;</strong>, con dueños de perros que necesitan que les cuiden a sus peludos mientras no est&aacute;n en casa
				</h1>
				<div class="text-center">				
		       	 	<button type="button" class="btn btn-lg btn-kmimos" data-toggle="modal" 
		       	 			data-target="#list-subscribe">
						Quiero ser un Cuidador Certificado Kmimos
					</button>
				</div>
       	 	</article>
	       	<article class="img-section-1 col-sm-5 pull-left  hidden-md hidden-sm hidden-lg">
	       		<img src="img/Character_section1.png" class="img-kmimos img-responsive" width="60%">
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

		<aside class="row" id="section-2-1">
			<div class="container">
				<article class="col-md-12">
					<div class="col-sm-offset-4 col-sm-2 col-xs-6 col-md-2">
						<img src="img/rcn.jpg" class="img-responsive"></div>
					<div class="col-sm-2 col-xs-6 col-md-2">
						<img src="img/unocanal.jpg" class="img-responsive"></div>
				</article>
				<article class="col-md-6 col-lg-6">
					<img src="img/mapa-co.jpg" class="img-responsive">
				</article>
				<article class="col-md-6 col-lg-6 text-center">
					<h2 class="title">En tres años estamos ubicados en México, Panamá, Colombia, Perú y Argentina.</h2>
					<div class="margin-top-50 col-xs-4 col-sm-4 text-center">
						<div>
							<span class="img-circle"><i class="fa fa-star"></i></span>
						</div>
						<br><br>
						<span class="circulos">+21,000</span>
						<br>
						<span>Noches Reservadas</span>
					</div>
					<div class="margin-top-50  col-xs-4 col-sm-4 text-center">
						<div>
							<span class="img-circle"><i class="fa fa-star"></i></span>
						</div>
						<br><br>
						<span class="circulos">+3,400</span>
						<br>
						<span>Clientes</span>
					</div>
					<div class="margin-top-50 col-xs-4 col-sm-4 text-center">
						<div>
							<span class="img-circle"><i class="fa fa-star"></i></span>
						</div>
						<br><br>
						<span class="circulos">+4,200</span>
						<br>
						<span>Perros</span>
					</div>
				</article>

			</div>
		</aside>
		<div class="clearfix"></div>

		<section class="row" id="section-2">
			<div>
				<article class="col-sm-12">
		       	 	<article class="margin-top-20 subtitle-container">
		       	 		<span class="subtitle subtitle-verde">VENTAJAS</span>
		       	 	</article>
		       	 	<article class="container">
		       	 		<div class="col-sm-1 col-xs-offset-3 col-sm-offset-0 col-md-offset-0 col-lg-offset-0">
		       	 			<img src="img/icon 1.png">
		       	 		</div>
		       	 		<div class="col-sm-10 col-sm-offset-1">
			       	 		<h2>Llenarás tu casa con nuevos amigos peludos!</h2>
			       	 	</div>
		       	 	</article>
		       	 	<hr>
		       	 	<article class="container">
		       	 		<div class="col-sm-1 col-xs-offset-3 col-sm-offset-0 col-md-offset-0 col-lg-offset-0">
		       	 			<img src="img/icon 2.png">
		       	 		</div>
		       	 		<div class="col-sm-10 col-sm-offset-1">
			       	 		<h2>Podrás ganarte hasta $4 millones de pesos al mes (dependiendo de la demanda)</h2>
			       	 	</div>
		       	 	</article>
		       	 	<hr>
		       	 	<article class="container">
		       	 		<div class="col-sm-1 col-xs-offset-3 col-sm-offset-0 col-md-offset-0 col-lg-offset-0">
		       	 			<img src="img/icon 3.png">
		       	 		</div>
		       	 		<div class="col-sm-10 col-sm-offset-1">
			       	 		<h2>¡El proceso de certificación es gratis, inscríbete ya!</h2>
			       	 	</div>
		       	 	</article>
		       	 	<hr>
		       	 	<article class="container">
		       	 		<div class="col-sm-1 col-xs-offset-3 col-sm-offset-0 col-md-offset-0 col-lg-offset-0">
		       	 			<img src="img/icon 4.png">
		       	 		</div>
		       	 		<div class="col-sm-10 col-sm-offset-1">
			       	 		<h2>Tendrás flexibilidad en tus horarios</h2>
			       	 	</div>
		       	 	</article>
			       	<div class="col-sm-12 text-center">
						<a  
							href="#section-3" 
							class="controll-pagination back-white">
							<i class="fa fa-angle-down" aria-hidden="true"></i>
						</a>
					</div>
		       	</article>
			</div>
		</section>
		<div class="clearfix"></div>

		<section class="row" id="section-3">
				
       	 	<article class="container">
				<h3 class="subtitle-3 title">CU&Aacute;LES SON LOS REQUISITOS</h3>
			</article>
   	 		<hr class="hr-special">
       	 	<article class="container">
       	 		<div class="col-sm-2">
       	 			<img src="img/shape 1.png">
       	 		</div>
       	 		<div class="col-sm-10  col-sm-offset-0">
	       	 		<h2 class="center-subtitle">Ser mayor de edad</h2>
	       	 	</div>
       	 	</article>
       	 	<hr>
       	 	<article class="container">
       	 		<div class="col-sm-10 text-right col-sm-offset-0">
	       	 		<h2  class="center-subtitle">Tener experiencia cuidando perros propios 
	       	 		por lo menos durante 3 años</h2>
	       	 	</div>
       	 		<div class="col-sm-2">
       	 			<img src="img/shape 2.png">
       	 		</div>
       	 	</article>
       	 	<hr>
       	 	<article class="container">
       	 		<div class="col-sm-2 ">
       	 			<img src="img/shape 3.png">
       	 		</div>
       	 		<div class="col-sm-10  col-sm-offset-0">
	       	 		<h2  class="center-subtitle">Confirmar que en tu domicilio puedes aceptar mascotas</h2>
	       	 	</div>
       	 	</article>
       	 	<hr>
       	 	<article class="container">
       	 		<div class="col-sm-10">
	       	 		<h2 class=" text-right center-subtitle">APROBAR nuestras pruebas de certificación que son totalmente gratuitas</h2>
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

		<section class="row" id="section-4">
				
       	 	<article class="container text-center">
				<h3 class="subtitle-4 title">¿CU&Aacute;L ES EL PROCESO DE CERTIFICACI&Oacute;N?</h3>
			</article>
   	 		<article class="container text-center">
			
   	 			<div class="col-sm-4 center-subtitle">
   	 				<img src="img/icon 5.png">
   	 				<h2>Llena el formulario y envíanos un recibo con tu dirección y tu Cédula por Mensaje Directo a nuestro Facebook<br>@KmimosCo</h2>
   	 			</div>

<div style="position:absolute;left:0px;"class="col-sm-5 hidden-xs hidden-sm arrow-left"><img src="img/Arrow 1.png"></div>

   	 			<div class="col-sm-4 center-subtitle">
   	 				<img src="img/icon 6.png">
   	 				<h2>Pruebas Psicom&eacute;tricas y conocimientos Veterinarios, No olvides revisar tu correo y spam, se realizan en l&iacute;nea!</h2>
   	 			</div>

<div style="position:absolute;right:0px;"class="col-sm-5 hidden-xs hidden-sm arrow-right"><img src="img/Arrow 1.png"></div>

   	 			<div class="col-sm-4 center-subtitle">
   	 				<img src="img/icon 7.png">
   	 				<h2>Una Entrevista</h2>
   	 			</div>
   	 		</article>
   	 		<article class="container text-center">
   	 			<div class="row">
   	 			
				<div style="position:absolute;left:0px;"class="col-sm-5 hidden-xs hidden-sm arrow-2"><img src="img/Arrow 1.png">
   	 			</div>
	
				<div class="col-sm-offset-4 col-sm-4">
	   	 				<img src="img/icon 8.png">
	   	 				<h2>Una vez activado, recibir&aacute;s visitas aleatorias a tu casa para revisar que todo est&eacute; en condiciones &oacute;ptimas para nuestros amigos peludos y... ¡A Recibir Peludos!</h2>
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

		<section class="row" id="section-5">
			<div>
				<article class="col-sm-12">

       	 	<aside class="container">
				<h3 class="subtitle-3 title">EN QU&Eacute; CONSTA EL SERVICIO ></h3>
			</aside>
   	 		<hr>
       	 	<article class="container">
       	 		<div class="col-sm-2">
       	 			<img src="img/shape 5.png">
       	 		</div>
       	 		<div class="col-sm-8 col-sm-offset-1">
	       	 		<h2 class="center-subtitle">Con Kmimos contar&aacute;s con una cobertura de servicios veterinarios para la estad&iacute;a del perrito que se queda en tu casa.</h2>
	       	 	</div>
       	 	</article>
       	 	<hr>
       	 	<article class="container">
       	 		<div class="col-sm-2">
       	 			<img src="img/shape 6.png">
       	 		</div>
       	 		<div class="col-sm-8 col-sm-offset-1">
	       	 		<h2  class="center-subtitle">Enviar&aacute;s fotos y v&iacute;deos diarios, ¡Mu&eacute;strale a su dueño que es el Rey de la casa! </h2>
	       	 	</div>
       	 	</article>
       	 	<hr>
       	 	<article class="container">
       	 		<div class="col-sm-2">
       	 			<img src="img/shape 7.png">
       	 		</div>
       	 		<div class="col-sm-8 col-sm-offset-1">
	       	 		<h2  class="center-subtitle">Cuentas con un equipo de atenci&oacute;n personalizada para el manejo del perrito, <br>¡Nunca est&aacute;s solo!</h2>
	       	 	</div>
       	 	</article>


	       	<div class="col-sm-12 text-center">
				<a  
					href="#section-6" 
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

	</div>
	<footer class="text-center ">
		<div class="col-sm-12">		
			<h2>¡EN KMIMOS, LLEGAN COMO HU&Eacute;SPED Y CONSIGUEN A UN NUEVO AMIGO!</h2>
			<button type="button" class="btn btn-lg btn-kmimos" data-toggle="modal" 
	   	 			data-target="#list-subscribe">
				Quiero ser un Cuidador Certificado Kmimos
			</button>
		</div>
		<div class="col-sm-12 margin-top-20">
			<a href="/">¿Quieres Conocer Kmimos?</a>
		</div>

		<aside class="text-center col-sm-12">
       	 	<img src="img/LogoKmimos.png" width="150px">
       	</aside>
	</footer>

	<section id="section-help">
		<!-- BEGIN col-md and col-lg -->
		<div class="container hidden-xs">
			<article class="col-sm-1 col-md-1 no-padding">
				<img src="img/help.jpg" class="img-responsive">
			</article>
			<article class="col-sm-11 col-md-11">
				<span class="help-title title">
					Tienes dudas sobre el registro? Tienes poco tiempo para registrarte?
					<span>kmimos te ayuda!</span>
				</span>
				<p class="help-subtitle">
					Ponte en contacto con nosotros. Mándanos un email a 
					<span class="resaltar"> contactoco@kmimos.la </span> 
					o por Teléfono o Whatsapp al 
					<span class="resaltar"> (57) 315 849 2186 </span>
				</p>
				<span class="help-title font-gris">La familia Kmimos te espera!!</span>
			</article>
		</div>		
		<!-- END col-md and col-lg -->

		<!-- BEGIN col-xs  and col-sm -->
		<div class="container hidden-sm hidden-md hidden-lg hidden-xl">
			<article class="col-xs-3 col-sm-3 col-md-1 no-padding">
				<img src="img/help.jpg" class="img-responsive">
			</article>
			<article class="col-xs-9 col-sm-9">
				<span class="help-title title">
					Tienes dudas sobre el registro?
				</span>
			</article>
			<article class="col-xs-12">
				<p class="help-subtitle">
					Ponte en contacto con nosotros. 
					<span class="resaltar"> contactoco@kmimos.la </span> 
					ó   
					<span class="resaltar"> (57) 315 849 2186  </span>
				</p>
			</article>
		</div>
		<!-- END col-xs  and col-sm -->

	</section>


	<!-- Registro de Email -->
	<div class="modal" id="list-subscribe" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h3 class="modal-title"> Quiero ser un Cuidador Certificado Kmimos</h3>
	      </div>
	      <div class="modal-body">
	      		<br>
	      	<form id="frm-temp">
		      	<div class="form-group">
			        <div class="input-group input-group">
			        	<span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
					    <input type="email" class="form-control" name="email" id="email" placeholder="escribe@tu-email.com" required>
					</div><!-- /input-group -->
				</div>
				<div class="form-group">
			        <div class="input-group input-group">
			        	<span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
					    <input type="text" class="form-control" name="phone" id="phone" placeholder="Escribe tu Tel&eacute;fono">
					</div><!-- /input-group -->
				</div>
				<div class="form-group">
					<div class="col-xs-7" style="padding-left:21px;" >
						<label class="checkbox">
							<input type="checkbox" id="terminos" name="terminos" required value="0">
							<span>Acepto los 
							<a role="button" data-toggle="collapse" href="#terminos_condiciones" aria-expanded="false" aria-controls="terminos_condiciones">t&eacute;rminos y condiciones</a>
							</span>
						</label>
				    </div>
					<div class="col-xs-4 pull-right text-right">
				    	<button class="btn btn-success" type="button" id="subscribe">Continuar</button>
				    </div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<i id="loading" class="hidden" style="font-size:12px;" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
					<span id="msg"></span>
					<br>
				</div>
	      		<br>
  				<div class="collapse" id="terminos_condiciones" style="width: 100%!important;">
				  <div class="well" style="width: 100%!important; text-align:justify; height: 200px; overflow: auto;">
				    <?php include_once('terminos.php'); ?>
	  				<div class="clearfix"></div>
				  </div>
				</div>
  				<div class="clearfix"></div>
	      		<br>
				<div>
					<i id="loading" class="hidden" style="font-size:12px;" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
					<span id="msg"></span>
				</div>
	      		<br>
      		</form>
			<form id="frm-redirect" action="/quiero-ser-cuidador-certificado-de-perros/"  method="post" >
      		</form>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<script
	  src="https://code.jquery.com/jquery-2.2.4.min.js"
	  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
	  crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>  
    <script src="js/main.js"></script>
    
    </body>
</html>
