<?php  

require_once("../vlz_config.php");
global $host, $user, $db, $pass;
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

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Kmimos - Suma huellas a nuestro club y gana descuentos</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

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

	</head>
	<body  class="container">

		<div  class="col-md-offset-1">
			
			<section class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="z-index:9999!important;">

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

		</div>
		<script
		  src="https://code.jquery.com/jquery-2.2.4.min.js?<?php echo time(); ?>"
		  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		  crossorigin="anonymous">  
		</script>		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="js/main.js"></script>

	    
	</body>
</html>