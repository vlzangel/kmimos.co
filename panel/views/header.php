<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kmimos | External Panel </title>

    <!-- Bootstrap -->
    <link href="/panel/assets/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/panel/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="/panel/assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="/panel/assets/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="/panel/assets/vendor/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="/panel/assets/vendor/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="/panel/assets/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="/panel/assets/vendor/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">    
    <!-- Custom Theme Style -->
    <link href="/panel/assets/css/custom.min.css" rel="stylesheet">

  </head>

  <body class="login">

  <div class="container body">

    <?php if ($auth){ ?>
    <div class="top_nav">
        <div class="nav_menu">
            <nav>
                <div class="col-md-4" style="padding:6px 10px 10px 10px ;">
                    <img src="../wp-content/uploads/2016/03/logo-kmimos_120x30.png" alt="logo kmimos">
                </div>
                <div class="col-md-6 pull-right">                
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav pull-right">
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reportes <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><a href="/panel/?p=suscriptores">Suscriptores</a></li>
                            <li><a href="/panel/?p=referidos">Usuarios Referidos</a></li>
                            <li><a href="/panel/?p=logout">Cerrar Sesion</a></li>
                          </ul>
                        </li>
                      </ul>
                    </div><!-- /.navbar-collapse -->
                </div>

            </nav>
        </div>
    </div>
    <?php } ?>