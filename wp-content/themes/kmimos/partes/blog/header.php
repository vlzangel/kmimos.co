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
        ?>


        <?php 
        wp_enqueue_style( 'fontawesome_47', getTema()."/css/font-awesome.css", array(), '1.0.0'); 
        wp_enqueue_style( 'style_kmimos', getTema()."/style.css", array(), '1.0.0'); 


        wp_enqueue_style('blogcacss',
            getTema().'/css/blog_min.css');

        wp_enqueue_style( 'blogestilos', getTema()."/css/blog.css", array(), '1.0.0'); 
               
        wp_head();
        ?>
        

    </head>

    <body <?php body_class(); ?>>
        <header>
            <div class="info">
                <div class="contain">
                    <span>Habla con nosotros</span>
                    <span>
                    <i class="icon phone fa fa-phone"></i>
                    <!-- 01 800 056 4667 <strong>WhatsApp:</strong>  +52 (55) 6892 2182-->
                    +52 (55) 4742-3162 <strong>WhatsApp:</strong>  +52 (55) 6892 2182
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
                    <!-- 01 800 056 4667<br><strong>WhatsApp:</strong>  +52 (55) 6892 2182-->
                    +52 (55) 4742-3162<br><strong>WhatsApp:</strong>  +52 (55) 6892 2182
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
