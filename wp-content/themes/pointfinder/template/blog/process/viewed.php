<?php

$args = array(
    'post_type'=>'post',
    'post_status' => 'publish',
    'posts_per_page' =>5,
    'meta_key' => 'post_views_count',
    'orderby' => 'meta_value_num',
    'order'      => 'DESC',
);

$iblogs = 0;
$blogs = new wp_query($args);
while($blogs->have_posts()){
    $blogs->the_post();
    $blogs_imagen=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'single-post-thumbnail');
    $blogs_thumbnail=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'single-post-thumbnail');
    $blogs_custom = get_post_custom($post->ID);
    //$SQLpost_viewed='visto '.wpb_get_post_views($post->ID).' veces';//get_the_ID()
    $iblogs++;

    $blogs_imagen=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$blogs_imagen);
    $blogs_thumbnail=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$blogs_thumbnail);

    $blogs_imagen=str_replace('http://kmimosmx.sytes.net/QA1/','https://kmimos.com.mx/',$blogs_imagen);
    $blogs_thumbnail=str_replace('http://kmimosmx.sytes.net/QA1/','https://kmimos.com.mx/',$blogs_thumbnail);

    $blogs_category=wp_get_post_terms($post->ID,'category',array('orderby' => 'name', 'order' => 'ASC'));
    $blogs_category_name=array();
    foreach($blogs_category as $category){
        $blogs_category_id = $category->term_id;
        $blogs_category_name[]=$category->name;
    }



    echo '<div class="post scroll_animate" data-position="self">';
    echo '<div class="image easyload" data-original="'.$blogs_imagen[0].'" style="background-image:url('.$blogs_thumbnail[0].');"></div>';

    echo '<div class="detail">';
    echo '<div class="title">'.the_title('','',false).'</div>';
    echo '<div class="autor scroll_animate" data-position="right">'.$blogs_custom['meta_blog_author_name'][0].'</div>';
    echo '<div class="content scroll_animate" data-position="self">'.wp_trim_words(strip_tags(get_the_content()), 50, $more = ' ...').'</div>';

    if(count($blogs_category)>0){
        echo '<div class="category scroll_animate" data-position="self">'.implode(', ',$blogs_category_name).'</div>';
    }

    echo '<div class="button more scroll_animate" data-position="self">'.__('MORE').'</div>';
    echo '</div>';

    echo '<a class="absolute" href="'.esc_url(get_permalink()).'" target="_blank" title="'.the_title('','',false).'"></a>';
    echo '</div>';
}

wp_reset_postdata();
?>

<script type="text/javascript">
    jQuery(document).on('click','#blog_viewed .control .icon', function(){
        pviewed=jQuery(this).index();
        show_viewed();
    });

    function show_viewed(){
        clearTimeout(tviewed);
        var posts=jQuery('#blog_viewed .post');
        var control=jQuery('#blog_viewed .control');

        if(control.find('.icon').length==0){
            posts.each(function(){
                control.append('<div class="icon"></div>');
            });
        }

        if(pviewed>=0 && posts.length>pviewed){
            posts.removeClass('show');
            posts.eq(pviewed).addClass('show');

            control.find('.icon').removeClass('show');
            control.find('.icon').eq(pviewed).addClass('show');
        }else{
            pviewed=0;
            show_viewed();
        }

        pviewed++;
        tviewed = setTimeout('show_viewed(pviewed)', 5000);
    }

    pviewed=0;
    var tviewed = setTimeout('show_viewed(pviewed)', 0);



    /*//MOUSE
    var view_down = 0;
    var view_up = 0;
    var view_factor = 30;
    jQuery(document).on('mousedown','#blog_viewed .post',function( event ) {
        view_down = event.offsetX;
        console.log(view_down);
        console.log(view_up);
    });

    jQuery(document).on('mouseup','#blog_viewed .post',function( event ) {
        view_up = event.offsetX;

        if(view_down>view_up && (view_down-view_up)>view_factor){
            if(jQuery('#blog_viewed .control .icon.show').next().length>0){
                jQuery('#blog_viewed .control .icon.show').next().click();
            }

        }else if(view_down<view_up && (view_up-view_down)>view_factor){
            if(jQuery('#blog_viewed .control .icon.show').prev().length>0){
                jQuery('#blog_viewed .control .icon.show').prev().click();
            }
        }

    });
    */

    /**///TOUCHMOVE
    var viewed_touchstart = 0;
    var viewed_touchstop = 0;
    var viewed_touchfactor = 30;
    jQuery('#blog_viewed .post').on("touchstart",function( event ) {
        viewed_touchstart = event.originalEvent.changedTouches[0].pageX;//
        //console.log(viewed_touchstart);
    });
    jQuery('#blog_viewed .post').on("touchend",function( event ) {
        var viewed_touchstop = event.originalEvent.changedTouches[0].pageX;//
        //console.log(viewed_touchstop);

        if(viewed_touchstart>viewed_touchstop && (viewed_touchstart-viewed_touchstop)>viewed_touchfactor){
            if(jQuery('#blog_viewed .control .icon.show').next().length>0){
                jQuery('#blog_viewed .control .icon.show').next().click();
            }

        }else if(viewed_touchstart<viewed_touchstop && (viewed_touchstop-viewed_touchstart)>viewed_touchfactor){
            if(jQuery('#blog_viewed .control .icon.show').prev().length>0){
                jQuery('#blog_viewed .control .icon.show').prev().click();
            }

        }

    });

    /*//SWIPE
    jQuery('#blog_viewed .post').on("swipeleft",function(event){
        if(jQuery('#blog_viewed .control .icon.show').next().length>0){
            jQuery('#blog_viewed .control .icon.show').next().click();
        }
    });

    jQuery('#blog_viewed .post').on("swiperight",function(event){
        if(jQuery('#blog_viewed .control .icon.show').prev().length>0){
            jQuery('#blog_viewed .control .icon.show').prev().click();
        }
    });
    */


    /*//SWIPE2
    jQuery('#blog_viewed .post').on("swipe",function( event ) {
        var start = event.swipestart.coords[0];
        var stop = event.swipestop.coords[0];
        var target = event.target.baseURI;
        var factor = 30;

        if(start>stop && (start-stop)>factor){
            if(jQuery('#blog_viewed .control .icon.show').next().length>0){
                jQuery('#blog_viewed .control .icon.show').next().click();
            }

        }else if(start<stop && (stop-start)>factor){
            if(jQuery('#blog_viewed .control .icon.show').prev().length>0){
                jQuery('#blog_viewed .control .icon.show').prev().click();
            }

        }else{
            window.location.href = target;
        }

    });
    */
</script>
