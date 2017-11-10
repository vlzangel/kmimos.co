<?php  

require_once("../vlz_config.php");
global $host, $user, $db, $pass, $url_base;
date_default_timezone_set('America/Mexico_City');

// Validar si existe referencia de usuario
$hidden = "";
$email = '';
$name = '';
$referencia = '';
if( isset($_GET['r']) ){
	$referencia = $_GET['r'];
}else{
	// Activar campos Name / Email
	if( isset($_GET['e']) ){
		// buscar usuario
		$email = strip_tags($_GET['e']);

		$cnn = new mysqli($host, $user, $pass, $db);
		if($cnn){
			$sql = "select * from wp_users where user_email = '".$email."'";
			$r = $cnn->query( $sql );
			$row = mysqli_fetch_all($r,MYSQLI_ASSOC);
			if( count($row)>0 ){
				$hidden = "hidden";
				$name = $row[0]['user_nicename'];
			}
		}
	}
}

?>
<?php

	$query = '';
	foreach ($_GET as $key => $value) {
		$separador = (!empty($query))? '&' : '' ;
		if( $key == 'utm_campaign'){
			$value = 'landing_' . $_GET['utm_campaign']; 
		}
		$query .= $separador.$key.'='.$value;
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Kmimos - Suma huellas a nuestro club y gana descuentos</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		
		<script src="js/jquery/jquery.js"></script>
		<link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/kmimos.css">

		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-56422840-1', 'auto');
		  ga('send', 'pageview');
		</script>

		<style type="text/css">
			#message{position: fixed; width: 100%; height: 100%; bottom: 0; left: 0; padding: 20px; text-align: center; box-shadow: 0 0 3px #CCC; background: rgba(0, 0, 0, 0.8); z-index: 2;}
			#message.Msubscribe .contain{position: relative; width: 95%; max-width: 100%; margin: 0 auto;}
			#PageSubscribe{position:relative; max-width: 700px;  margin: 0 auto;  padding: 25px;  top: 75px; color: #FFF; border-radius: 20px; /* background:#00bc00;*/
				background: #ba2287;  overflow: hidden; margin-top: 5%; margin-left: 34%;}
			#PageSubscribe .exit{float: right; cursor: pointer;}
			#PageSubscribe .section{ width: 50%; padding: 10px; float: left; font-size: 17px; text-align: left;}
			#PageSubscribe .section.section1{font-size: 20px;}
			#PageSubscribe .section.section1 span{font-size: 25px; font-weight: 400;}
			#PageSubscribe .section.section1 .images{padding:10px 0; text-align: center;}
			#PageSubscribe .section.section3{width: 100%; font-size: 17px; font-weight: bold; text-align: center;}
			#PageSubscribe .section.section2{}
			#PageSubscribe .section.section2 .message{font-size: 15px; border: none; background: none; opacity:0; visibility: : hidden; transition: all .3s;}
			#PageSubscribe .section.section2 .message.show{opacity:1; visibility: :visible;}
			#PageSubscribe .section.section2 .icon{width: 30px; padding: 5px 0;}
			#PageSubscribe .section.section2 .subscribe {margin: 20px 0;  }
			#PageSubscribe .section.section2 form{margin: 0; display:flex;}
			#PageSubscribe .section.section2 input,
			#PageSubscribe .section.section2 button{width: 100%; max-width: calc(100% - 60px); margin: 5px; padding: 5px 10px; color: #CCC; font-size: 15px; border-radius: 20px;  border: none; background: #FFF; }
			#PageSubscribe .section.section2 button {padding: 10px;  width: 40px;}

			@media screen and (max-width:480px), screen and (max-device-width:480px) {
				#PageSubscribe { top: 15px; margin-left: 0%;}
				#PageSubscribe .section{ width: 100%; padding: 10px 0; font-size: 12px;}
				#PageSubscribe .section.section1 {font-size: 15px;}
				#PageSubscribe .section.section1 span {font-size: 20px;}
				#PageSubscribe .section.section3 {font-size: 12px;}
				#PageSubscribe .section.section2 input, #PageSubscribe .section.section2 button {font-size: 10px;}
			}
			@media screen and (min-width:1280px) {
				#PageSubscribe {margin-top: 5%; margin-left: 40%;}
			}
		</style>

	<script type='text/javascript'>
		//Subscribe
		function SubscribeSite(){
			clearTimeout(SubscribeTime);

			var dog = '<img height="70" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-09.png">' +
				'<img height="20" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-10.png">';

			var html='<div id="PageSubscribe"><i class="exit fa fa-times" aria-hidden="true" onclick="SubscribePopUp_Close(\'#message.Msubscribe\')"></i>' +
				'<div class="section section1"><span>G&aacute;nate <strong>$50 pesos</strong> en tu primera reserva</span><br>&#8216;&#8216;Aplica para clientes nuevos&#8217;&#8217;<div class="images">'+dog+'</div></div>' +
				'<div class="section section2"><span><strong>&#161;SUSCR&Iacute;BETE!</strong> y recibe el Newsletter con nuestras <strong>PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS,</strong> etc.!</span>'+

				'<div class="subscribe">'+
				'<form onsubmit="form_subscribe(this); return false;">'+
				'<input type="hidden" name="section" value="landing-volaris"/>'+
				'<input type="mail" name="mail" value="" placeholder="Introduce tu correo aqu&iacute" required/>'+
				'<button type="submit"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>'+
				'</form>'+
				'<div class="message"></div>'+
				'</div>'+

				'</div>';
			SubscribePopUp_Create(html);
		}

		function SubscribePopUp_Create(html){
			var element = '#message.Msubscribe';
			if(jQuery(element).length==0){
				jQuery('body').append('<div id="message" class="Msubscribe"></div>');
				jQuery(element).append('<div class="contain"></div>');
			}

			jQuery(element).find('.contain').html(html);
			jQuery(element).fadeIn(500,function(){
				/*
				 vsetTime = setTimeout(function(){
				 SubscribePopUp_Close(element);
				 }, 6000);
				 */
			});
		}

		jQuery(document).ready(function(e){
			SubscribeTime = setTimeout(function(){
				SubscribeSite();
			}, 7400);
		});

		function form_subscribe(element){
			var subscribe = jQuery(element).closest('.subscribe');
			var message = subscribe.find('.message');
			var email = subscribe.find('input[name="mail"]').val();
			var url = '../landing/newsletter.php?source=referidos&email='+email;
			if(email!=''){
				jQuery.post(url, jQuery(element).serialize(),function(data){
					//console.log(data);
					var textmessage="Error al guardar los datos";

					if( data == 1){
						textmessage="Registro Exitoso. Por favor revisa tu correo en la Bandeja de Entrada o en No Deseados";
					}else if( data == 2){
						textmessage="Formato de email invalido";
					}else if( data == 3){
						textmessage="Este correo ya est&aacute; registrado. Por favor intenta con uno nuevo";
					}else{
						textmessage="Error al guardar los datos";
					}

					if(message.length>0){
						message.addClass('show');
						message.html('<i class="icon fa fa-envelope"></i>'+textmessage+'');
						vsetTime = setTimeout(function(){
							message_subscribe(message);
						}, 5000);
					}
				});
			}
			return false;
		}

		function message_subscribe(element){
			clearTimeout(vsetTime);
			element.removeClass('show');
			element.html('');
			return true;
		}

		function SubscribePopUp_Close(element){
			if(jQuery(element).length>0){
				jQuery(element).fadeOut(500,function(){
					jQuery(element).remove();
				});
			}
		}
	</script>

	</head>
	<body  class="container">

		<div  class="col-md-offset-1">
			
			<section class="col-xs-12 col-sm-12 col-md-3 col-lg-3" >

				<header ><img id="img-text" src="img/20titular1.png"></header>

				<article><img src="img/11logopatitas.png" class="img-responsive"></article>

				<footer class="text-center">
					<div id='footer-content' class="col-md-12">
						<h1>¡Inscribete!</h1>
						<h2>gana descuentos</h2>
						<h2>para ti y tus amigos</h2>
						<form id="frm">
							<div class="<?php echo $hidden; ?>">
								<input type="hidden" id="referencia" name="referencia" class="form-control" value="<?php echo $referencia; ?>">
								<input type="text"   id="name"  name="name" class="form-control " value="<?php echo $name; ?>" placeholder="Nombre y Apellido">
								<input type="email"  id="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Correo electr&oacute;nico" required>
							</div>
							<button type="button" id="send" class="btn-kmimos btn">¡Quiero participar!</button>
							<span id="msg" style="padding-top:3px;color:#fff;"></span>
						</form>
						<form action="compartir/?e=" method="post" id="temp"></form>
					</div>
				</footer>

			</section>
			
			<section class="col-xs-12 col-sm-12 col-md-8 col-lg-8 hidden-sm hidden-xs">
				<img src="img/1backgroundfoto.jpg" class="img-responsive" width="98%">
			</section>
			<section class="col-xs-12 col-sm-12 col-md-8 col-lg-8 hidden-md hidden-lg" >
				<img src="img/bg-movil.jpg" class="img-responsive img-rounded" style="border-radius: 20px;" width="98%">
			</section>
			<br>
			<br>
		</div>
		<script
		  src="https://code.jquery.com/jquery-2.2.4.min.js?<?php echo time(); ?>"
		  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		  crossorigin="anonymous">  
		</script>		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<script type="text/javascript">
			$(function(){
				var url = "<?php echo $url_base; ?>";
				$('#frm').on('submit', function(e){
				  e.preventDefault(e);
				  _registerLanding();
				});

				$('#send').on('click', function(e){
				  _registerLanding();
				});

				$("input").change(function(){
				  if( $(this).val() != "" ){
				    $('msg').html("");
				  }
				});

				function _registerLanding(){

					  if( $('#email').val() == "" || $('#name').val() == "" ){
					    $('#msg').html('Debe completar los datos');
					    return;
					  }


					  $('#loading').removeClass('hidden');
					  $('#msg').html('Registrando Usuario.');
					  $.ajax( url+"../landing/registro-usuario.php?email="+$('#email').val()+"&name="+$('#name').val()+"&referencia="+$('#referencia').val() )
					  .done(function() {
					    $('#msg').html('Generando url.');
					  })
					  .fail(function() {
					    $('#msg').html(' ');
					    $('#loading').addClass('hidden');
					  });  



					  $('#loading').removeClass('hidden');
					  $('#msg').html('Enviando...');
					  $.ajax( url+"../landing/list-subscriber.php?source=kmimos-mx-clientes-referidos&email="+$('#email').val() )
					  .done(function() {
					    $('#loading').addClass('hidden');
					    $('#msg').html('Guardando referencia.');

					    //window.open($('#temp').attr('action')+$('#email').val(), '_system');
					    window.location.href = $('#temp').attr('action')+$('#email').val();
					  })
					  .fail(function() {
					    //$('#msg').html('Referencia: No pudimos completar su solicitud, intente nuevamente');
					    //$('#loading').addClass('hidden');
					  });  

				}

			});
		</script>


	    
	</body>
</html>