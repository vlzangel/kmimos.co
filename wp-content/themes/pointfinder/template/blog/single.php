<?php
include_once(__DIR__.'/header.php');
?>

<style type="text/css">
    /*SINGLE*/
    #single{position:relative; text-align:left; background:#FFF; overflow:hidden;}
    #single .title{position:relative; margin:0; padding:40px 0; color:#fdc421; font-size:25px; font-weight:bold; text-align:center; line-height: 1.3;}
    #single .image{position:relative; width:100%; margin:0;}
    #single .single{position:relative; margin:-100px auto 50px auto; padding:30px 50px; border-radius:20px; box-shadow:0 0 10px #555; background:#FFF;}
    #single .single .title{text-align:left;}
    #single .single .content{position:relative; color:#555; font-size:15px; text-align: justify; line-height: 1.3; display: flex;}
    #single .single .content .column{width:100%; margin:0 10px;}

    /*new*/
    #single .single{padding: 30px 0;}
    #single .single .title{padding: 40px 50px;}
    #single .single .content {display: block;}
    #single .single .content .column{margin:0px; padding: 20px 50px;}

    @media screen and (max-width:768px), screen and (max-device-width:768px){
        #single .single .content{display: flex; flex-flow: wrap;}
        #single .single .content .column{width:calc(50% - 20px); padding: 10px 20px;}

        /*new*/
        #single .single .content .column{width: calc(100% - 20px);}
    }

    @media screen and (max-width:480px), screen and (max-device-width:480px){
        #single .image{margin: 0;}
        #single .single{margin-top: 0; box-shadow: none;}
        #single .single .title{padding:10px 20px; font-size: 20px; line-height: 1.3;}
        #single .single .content .column{width:100%; margin: 0; padding: 10px 20px;}
    }


    /*FEATURED*/
    #featured{display: none;}
    #featured{position:fixed; width: 500px; max-width: 100%; top: calc(50% - 220px); right: calc(100% - 40px); border-radius:0 20px 20px 0; background: #dadada; z-index:1; transition: all .3s;}
    #featured.show{/*right: auto;*/}
    #featured .info.title{color:#FFF; font-weight:normal; text-align:center; background:#900fa2; display: none;}
    #featured .caregiver{position:relative; max-width: calc(100% - 80px); min-width: 150px; overflow: hidden;}
    #featured .caregiver .action{display: none;}
    #featured .caregiver .action,
    #featured .show{position:absolute; width:100%; height:100%;}
    #featured.show .show .icon:before{content:"\f0d9";}
    #featured .caregiver .action .icon,
    #featured .show .icon{position:absolute; width:25px; top:calc(50% - 12px); right:0; font-size:15px;}
    #featured .caregiver .action .icon[data-direction="prev"]{left:0; right: auto;}
    #featured .group{width: 350px; max-width: 100%; margin: 0; float: right; display:block !important;}
    #featured .post{ width:100%; margin:10px 0; display: flex; flex-flow: wrap;}
    #featured .post .data{width: 50px; padding: 10px;  border-radius:20px 0 0 20px; background:#900fa2; overflow:hidden;}
    #featured .post .data .bone{ width: 30px; height: 30px; margin: 0 auto; background: center/contain no-repeat;}
    #featured .post .data .rating{margin:0; color: #FFF; font-size: 20px; font-weight: bold; text-align: center; }
    #featured .post .data .rating:before{content: "+";}
    #featured .post .image{width: 150px; padding: 10px; border:2px solid #8f8f8f; border-width: 2px 0; overflow:hidden;}
    #featured .post .image .img{width: 90%; padding-top: 90%; margin: 0 auto; border-radius: 50%; background: #EEE center/cover no-repeat;}
    #featured .post .detail{width: calc(100% - 200px); padding: 20px; border:2px solid #8f8f8f; border-left-width:0; border-radius:0 20px 20px 0; background: #FFF; overflow:hidden;}
    #featured .post .detail .title{padding: 10px 0; color: #888; font-size: 25px;}
    #featured .post .detail .content{margin:10px 0; color: #888; font-size: 15px; text-align:left;}
    #featured .post .detail .content.price{color: #900fa2;}


    #featured.central{position:relative; width:100%; margin:0; padding: 20px 0; top:0; right:0; left:0; border-radius:20px; background:#FFF; display:block;}
    #featured.central .info{display: block;}
    #featured.central .caregiver{max-width: 100%;}
    #featured.central .caregiver .action{display: block;}
    #featured.central .group{width: calc(100% - 80px); margin: 0 auto; float: none; display: flex !important;}
    #featured.central .post{margin: 30px 20px; overflow: hidden;}
    #featured.central .post .image{margin: 0; padding: 20px 10px;}
    #featured.central .show{display: none !important;}


    @media screen and (max-width:768px), screen and (max-device-width:768px){
        #featured .post{width: calc(50% - 40px); display: block;}
        #featured .post .data{width: 100%; border-radius:20px 20px 0 0;}
        #featured .post .image{width: 100%;  border-width: 0 2px;}
        #featured .post .image .img{width: 150px; padding-top: 150px;}
        #featured .post .detail{width: 100%; padding: 0 20px; text-align: left; border-width:0 2px 2px 2px; border-radius:0 0 20px 20px;}
    }
    @media screen and (max-width:480px), screen and (max-device-width:480px){
        #featured .post {width: 100%;}
        #featured .post:nth-child(2){display: none;}
    }



    /*KMIBOX*/
    #kmibox{position: relative; margin: 30px 0; padding: 20px 0;  color: #FFF; font-size: 30px; text-align: center; overflow: hidden; background: #dadada;}
    #kmibox .group{display: flex; flex-flow: wrap;}
    #kmibox .section{width: 30%;}
    #kmibox .image{position:relative; background: center/contain no-repeat; background-image: url('https://www.kmimos.com.mx/wp-content/uploads/2017/06/personaje-400x353.png');}
    #kmibox .detail{position:relative; padding: 10px 0; color:#999;  text-align: right;  line-height: 1.5;}
    #kmibox .detail .logo{height: 100px; background: center right/contain no-repeat; background-image: url('https://www.kmimos.com.mx/wp-content/uploads/2017/06/Logo-2.png');}
    #kmibox .detail .title{ margin: 10px 0; font-size: 20px; font-weight: bold;}
    #kmibox .detail .content{ margin: 10px 0; font-size: 15px;}
    #kmibox .detail .button{padding:10px 20px;  color: #FFF; font-size: 20px; border-radius:50px; cursor: pointer; background: #23d3c4 !important; display: inline-block;}

    #kmibox .section.register{margin: 0 40px 0 0; padding: 40px; color: #FFF; font-size: 17px; border-radius: 20px; background: #900fa2;}
    #kmibox .section.register span{padding: 10px;  display: block;}
    #kmibox .section.register .message{padding: 0; text-align: center; opacity:0; visible:hidden; transition: all .3s;}
    #kmibox .section.register .message.show{opacity:1; visible:visible;}
    #kmibox .section.register .message .icon{padding: 10px;}
    #kmibox .section.register form{margin: 0; display:flex;}
    #kmibox .section.register input,
    #kmibox .section.register button{width: 100%; max-width: calc(100% - 60px); margin: 5px; padding: 5px 10px; color: #CCC; font-size: 15px; border-radius: 20px;  border: none; background: #FFF; }
    #kmibox .section.register button {padding: 10px;  width: 40px;}

    @media screen and (max-width:768px), screen and (max-device-width:768px){
        #kmibox .section{width: 50%;}
        #kmibox .section.register{margin: 0;}
        #kmibox .section.image{display: none;}
    }
    @media screen and (max-width:480px), screen and (max-device-width:480px){
        #kmibox .section{width: 100%;}
        #kmibox .section.detail{width: 100%;}
        #kmibox .section.image{position: absolute; width: 100%; width: 100%; height: 100%; opacity: 0.2; }

        /* RESPONSIVE SUGERIDO */
        #kmibox{margin:0;}
        #kmibox .group {padding-bottom: 150px;}
        #kmibox .section.image {display: none;}
        #kmibox .section.image.responsive{position: absolute; height: 200px; opacity: 1; left: 0; bottom:0px; background-position: bottom right; display: block;}
        #kmibox .section.detail{max-width: 300px; padding: 0; text-align: left;}
        #kmibox .detail .logo{background-position: center left;}
        #kmibox .detail .button {font-size: 15px;}

    }

</style>

<?php
$POSTarray=array();
while(have_posts()){
    the_post();

    $img=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'single-post-thumbnail');
    $thumbnail=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'single-post-thumbnail');

    $img=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$img);
    $thumbnail=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$thumbnail);

    $img=str_replace('http://kmimosmx.sytes.net/QA1/','https://kmimos.com.mx/',$img);
    $thumbnail=str_replace('http://kmimosmx.sytes.net/QA1/','https://kmimos.com.mx/',$thumbnail);


    $POSTarray['title']=the_title('','',false);
    $POSTarray['image']=$img;
    $POSTarray['thumbnail']=$thumbnail;
    $POSTarray['content']=get_the_content();//wp_trim_words(strip_tags(), 20, $more = ' ...');
    $POSTarray['custom']=get_post_custom($post->ID);
    //echo $post->ID.'<br/>';
}
?>

<section id="single">
    <div class="title scroll_animate" data-position="self" data-scale="small">LOS ARTÍCULOS MÁS VISTOS</div>
    <?php if($POSTarray['image'][0]!=''){// && file_exists($POSTarray['image'][0]) ?>
        <img class="image" data-original="<?php echo $POSTarray['image'][0]; ?>" src="<?php echo $POSTarray['thumbnail'][0]; ?>" alt="<?php echo $POSTarray['title']; ?>">
    <?php }?>
    <div class="single contain">
        <div class="title scroll_animate" data-position="self" data-scale="small"><?php echo $POSTarray['title']; ?></div>
        <div class="content scroll_animate" data-position="top" data-scale="small">
            <?php echo wpautop($POSTarray['content'], true); ?>
        </div>
    </div>
</section>

<section id="featured" class="">
    <div class="title info">
        Da un vistazo a nuestros cuidadores certificados que cuidarán de nuestras mascotas.
        <strong>Libre de jaulas y encierros</strong>
    </div>
    <div class="show">
        <i class="icon arrow fa fa-caret-right" data-direction="next"></i>
    </div>
    <?php
        include_once(__DIR__.'/frontend/featured.php');
    ?>
</section>


<section id="kmibox">
    <div class="group contain">
        <div class="section register">
            <span>Te interesaron nuestros artículos?</span>
            <span><strong>SUSCR&Iacute;BETE!</strong> y recibe el Newsletter con nuestras <strong>PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS,</strong> etc.!</span>
            <?php echo subscribe_input('blog'); ?>
        </div>
        <div class="section image responsive"></div>
        <div class="section image"></div>
        <div class="section detail">
            <div class="logo"></div>
            <div class="title">Conoce  y elige el plan que <br>mejor te convenga</div>
            <div class="content">Regalale un detalle al consentido de tu hogar!</div>
            <a href="<?php echo site_url(); ?>"><div class="button">Quiero mi KmiBOX</div></a>
        </div>
    </div>
</section>

<script type="text/javascript">
    jQuery(document).on('click','#featured .show .icon', function(e){
        featured_show(this);
    });

    function featured_show(element){
        var featured = jQuery(element).closest('#featured');
        if(featured.hasClass('show')){
            featured.removeClass('show');
            jQuery('#featured').css({'right':''});
        }else{
            featured.addClass('show');
            var width= jQuery('#featured.show').width();
            jQuery('#featured.show').css({'right':'calc(100% - '+width+'px)'});
        }
    }

    function divide_content(column, separator){
        var content = jQuery('#single .single .content');
        var sections = content.children(separator).length;
        var text = content.html();
        content.html('');

        for(var i=0; i<column; i++){
            content.append('<div class="column column'+i+'">'+text+'</div>');
            text='';

            if(i>0){
                var iprev =  (i-1);
                var divide = Math.ceil(sections/(column-iprev));
                content.find('.column'+iprev).children(separator).slice(divide).appendTo(content.find('.column'+i));
            }
        }
    }
    divide_content(4, 'p, h1, h2, h3, ul');




    function insert_element(element, after,  separator){
        var content = jQuery('#single .single .content');
        var sections = content.find('.column').length;
        var text = content.html();

        if(sections>0){
            content.find('.column').eq(after).after(jQuery(element).addClass('central'));
        }
    }

    jQuery(document).ready(function(e){
        insert_element('#featured', 0, 'p');
    });
</script>

<?php
include_once(__DIR__.'/footer.php');
?>
