<?php
//the_post();
global $post;
$slug=$post->post_name;
$page_current=site_url().'/'.$slug;

$search='';
if(array_key_exists('search',$_POST)){
    $_SESSION['search']=$_POST['search'];
    header("Refresh:0");
    exit();

}else if(array_key_exists('search',$_SESSION)){
    $_POST['search']=$_SESSION['search'];
    $search = $_SESSION['search'];
    unset($_SESSION['search']);
}

?>

<html <?php language_attributes(); ?> class="no-js">
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <?php
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">';
            wp_head();
        ?>
    </head>

<style>
    a.absolute{position: absolute; width: 100%; height: 100%;  top: 0; left: 0;}
    .contain{position: relative; width: 95%; max-width: 1000px; margin: 0 auto;}
    .logo{background:url(https://www.kmimos.com.mx/wp-content/uploads/2017/07/bolsita-y-logotipo-02.png) center/contain no-repeat;}
    .blog_title{color: #ff8700; font-size: 35px; font-weight: bold; line-height: 1;}
    .icon.arrow{position: relative; width: 50px; padding: 5px 0; margin: 5px 5px; color: #FFF; font-size: 40px; text-align: center; border-radius: 50%; cursor: pointer; background: #900fa2;  }
    .responsive{display: none;}
    .info{line-height: 1.3;}


    @media screen and (max-width:768px), screen and (max-device-width:768px){}
    @media screen and (max-width:480px), screen and (max-device-width:480px){
        .icon.arrow{width:25px; font-size:15px;}
        .blog_title{display:block; text-align:center;}

        /* RESPONSIVE SUGERIDO */
        .blog_title{text-align:left; font-size: 20px;}
        .responsive{display: block;}
    }


    /*HEADER*/
    header .logo{width: 100%; max-width: 500px;  height: 300px; margin: 0 auto;}
    header .info{padding: 10px;  color: #FFF; font-size: 20px;  background: #23d3c4; overflow: hidden;}
    header .info .icon{width: 40px; margin: 0 10px; padding:10px; color: #23d3c4; text-align: center; border-radius: 50%; background: #FFF;}
    header .info .icon.help{float:right; cursor: pointer;}
    header .info .session{padding: 10px; float:right; border-left: 2px solid #FFF; cursor: pointer;}

    header .redes{position:absolute; width:70px;  right: 0;  top:calc(50% - 115px);}
    header .redes .icon{width: 40px; margin:5px 0; padding:10px; color: #FFF; font-size: 20px; text-align: center; border-radius: 50%; cursor: pointer; background: #23d3c4;}
    header .redes .icon.bolsa{ height: 40px; border-radius: 0; display: none !important; background:url(https://www.kmimos.com.mx/wp-content/uploads/2017/07/bolsita-y-logotipo-03.png) center/contain no-repeat;}

    header .search{position: relative; color: #aaa; font-size: 15px; text-align: right;}
    header .search input{position: relative; padding:5px 10px; border:1px solid #aaa; border-radius: 20px;}
    header .search input:focus{border-color: #ff8700 !important;}
    header .search button{position: relative; outline: none; border: none; background: none;}

    header .menu{position: relative; border: 1px solid #aaa;}
    header .menu .items{display: flex; align-items: center;}
    header .menu .items .item{width: 100%;  padding: 10px; color: #aaa; font-size: 20px; text-align: center; border-right: 1px solid #aaa; cursor: pointer;}
    header .menu .items .item a{color: #aaa;}
    header .menu .items .item.caregiver{width: 250%; color: #FFF; border: none; background: #ff8700;}
    header .menu .items .item.caregiver a{color: #FFF;}
    header .menu .responsive{text-align: right; display: none;}
    header .menu .responsive .bar{padding: 10px; font-size: 30px; cursor: pointer;}

    header .menu .items.show{position: absolute; width: 100%; top: 100%; border: 1px solid #aaa; background: #FFF; display: block; z-index: 1;}
    header .menu .items.show .item{width: 100%; text-align: right; border: none;}


    @media screen and (max-width:768px), screen and (max-device-width:768px){
        header .info {text-align: center;}
        header .info span{ display: block;}
        header .info .session {float: none; border: none; }
        header .info .icon.help{display: none;}
        header .logo {height: 200px;}

        header .redes{position: relative; width: auto; margin: 10px 0; text-align: center; top: auto;}
        header .redes .icon.bolsa{width: 40px; margin: 0 5px; padding: 15px; display: initial;}

        header .menu{border-width: 1px 0 1px 0;}
        header .menu .items{display: none;}
        header .menu .responsive{display: block;}

        /* RESPONSIVE SUGERIDO 480*/
        header .info { display: none;}
        header .info.responsive{position: fixed; width: 100%; top: 0; display: block; overflow: visible; z-index: 1;}
        header .info.responsive .group.contain{width: 100%; font-size:7px; align-items: center; justify-content: flex-end; overflow: hidden;}
        header .info.responsive  span{font-size:15px; float: left;}
        header .info.responsive .session{font-size:12px; padding: 10px 5px; float: right;}
        header .info.responsive .icon{float: right;}
        header .info.responsive .icon.help{float: right;}
        header .info.responsive .icon.bar{padding: 5px 0px;}
        header .info.responsive .menu{border: none; display: block !important;}
        header .info.responsive .menu .items.show{border: none; background: #23d3c4;}
        header .info.responsive .menu .items.show .item,
        header .info.responsive .menu .items.show .item a{color: #FFF;}
        header .info.responsive .section{padding: 10px 0; margin: 0; border-top: 1px solid #FFF; display: none;}
        header .info.responsive .section.phone{}
        header .info.responsive .section.search{}
        header .info.responsive .section.search form{margin: 0;}
        header .info.responsive .section.search button{color: #FFF; display: inline-flex;}
        header .info .session{width: 60px; padding: 5px; border-left: 2px solid #FFF; }
        header .info .icon{width: 35px; margin: 5px; padding: 10px; font-size: 15px;}
        header .info .icon.help{display: inline-block; float: none;}
        header .info .icon.bar{padding: 0px; color: #FFF; font-size: 30px; cursor: pointer; background: #23d3c4;}
        header .info .icon.search{cursor: pointer; display: inline-block;}
        header .info span{margin: 0 5px; text-align: left; display: flex; align-items: center;}

        header .header{margin-top: 70px;}
        header .logo{width: calc(100% - 100px); height: 100px;}
        header .search{display: none;}
        header .menu{display: none;}
    }
    @media screen and (max-width:480px), screen and (max-device-width:480px){}

</style>
    <body>
        <header>
            <div class="info">
                <div class="contain">
                    <span>Habla con nosotros</span>
                    <span>
                    <i class="icon phone fa fa-phone"></i>
                    01 800 056 4667 <strong>WhatsApp:</strong>  +52 (55) 6892 2182
                    </span>
                    <div id="pf-login-trigger-button" class="session">Inicia Sesión</div>
                    <i class="icon help fa fa-question"></i>
                </div>
            </div>

            <div class="info responsive">
                <div class="group contain">
                    <span>
                    <i class="icon phone fa fa-phone"></i>
                    </span>
                    <div id="pf-login-trigger-button-mobi" class="session">Inicia Sesión</div>
                    <i class="icon bar fa fa-bars"></i>
                    <i class="icon help fa fa-question"></i>
                    <i class="icon search fa fa-search"></i>
                </div>


                <div class="menu">
                    <div class="items">
                        <div class="item"><a href="<?php echo site_url(); ?>">KMIMOS</a></div>
                        <div class="item"><a href="<?php echo site_url(); ?>/beneficios-para-tu-perro/">BENEFICOS</a></div>
                        <div class="item"><a href="">FAQ</a></div>
                        <div class="item"><a href="https://www.booking.com/index.html?aid=1147066&lang=es">SERVICIOS</a></div>
                        <div class="item caregiver"><a href="<?php echo site_url(); ?>/quiero-ser-cuidador-certificado-de-perros/">QUIERO SER CUIDADOR</a></div>
                    </div>
                </div>

                <div class="phone section">
                    01 800 056 4667<br><strong>WhatsApp:</strong>  +52 (55) 6892 2182
                </div>

                <div class="search section">
                    <form  method="post" action="<?php echo site_url().'/blog/#last'; ?>">
                        <input type="text" name="search" value="<?php echo $search; ?>" placeholder=""/>
                        <button type="submit"><span class="fa fa-search"></span> BUSCAR</button>
                    </form>
                </div>
            </div>

            <div class="header contain">
                <div class="logo"></div>
                <div class="redes">
                    <a href="https://www.facebook.com/Kmimosmx/"><i class="icon phone fa fa-facebook"></i></a>
                    <a href="https://twitter.com/kmimosmx/"><i class="icon phone fa fa-twitter"></i></a>
                    <a href="https://www.instagram.com/kmimosmx/"><i class="icon phone fa fa-instagram"></i></a>
                    <i class="icon bolsa"></i>
                </div>

                <div class="search">
                    <form  method="post" action="<?php echo site_url().'/blog/#last'; ?>">
                        <input type="text" name="search" value="<?php echo $search; ?>" placeholder=""/>
                        <button type="submit"><span class="fa fa-search"></span> BUSCAR</button>
                    </form>
                </div>

                <div class="menu">
                    <div class="items">
                        <div class="item"><a href="<?php echo site_url(); ?>">KMIMOS</a></div>
                        <div class="item"><a href="<?php echo site_url(); ?>/beneficios-para-tu-perro/">BENEFICOS</a></div>
                        <div class="item"><a href="">FAQ</a></div>
                        <div class="item"><a href="https://www.booking.com/index.html?aid=1147066&lang=es">SERVICIOS</a></div>
                        <div class="item caregiver"><a href="<?php echo site_url(); ?>/quiero-ser-cuidador-certificado-de-perros/">QUIERO SER CUIDADOR</a></div>
                    </div>
                    <div class="responsive">
                        <i class="bar fa fa-bars" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </header>


        <script type="text/javascript">
            //MENU
            jQuery('header .menu .responsive .bar, header .info.responsive .bar').click(function(e){
                responsive_menu(this);
            });

            function responsive_menu(element){
                var items = jQuery(element).closest('header').find('.menu').find('.items');
                if(items.hasClass('show')){
                    items.slideUp(function(){
                        jQuery(this).removeClass('show');
                    });
                }else{
                    items.slideDown().addClass('show');
                }
            }

            //SEARCH
            jQuery('header .info.responsive .icon.search').click(function(e){
                responsive_search(this);
            });

            function responsive_search(element){
                var search = jQuery(element).closest('.info.responsive').find('.search.section');
                if(search.hasClass('show')){
                    search.slideUp(function(){
                        jQuery(this).removeClass('show');
                    });
                }else{
                    search.slideDown().addClass('show');
                }
            }

            //PHONE
            jQuery('header .info.responsive .icon.phone').click(function(e){
                responsive_phone(this);
            });

            function responsive_phone(element){
                var phone = jQuery(element).closest('.info.responsive').find('.phone.section');
                if(phone.hasClass('show')){
                    phone.slideUp(function(){
                        jQuery(this).removeClass('show');
                    });
                }else{
                    phone.slideDown().addClass('show');
                }
            }

        </script>
