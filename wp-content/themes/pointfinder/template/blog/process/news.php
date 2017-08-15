<?php
$html= '<div class="post scroll_animate" data-position="self">';
$html.= 'NO HAY MAS NOTICIAS';
$html.= '</div>';

add_filter( 'posts_where', 'blog_posts_where', 10, 2 );
function blog_posts_where( $where, &$wp_query ){
    global $wpdb;
    if ($blog_search = $wp_query->get('blog_search')) {

        $search=$wpdb->posts.".post_title LIKE '%".esc_sql($wpdb->esc_like($blog_search))."%'";
        $search.=" OR ".$wpdb->posts.".post_content LIKE '%".esc_sql($wpdb->esc_like($blog_search))."%'";

        $where.= " AND (".$search.")";
    }
    return $where;
}

$args = array(
    'post_type'=>'post',
    'post_status' => 'publish',
    'posts_per_page' =>10,
    'paged'=>get_query_var('paged'),
);


if(array_key_exists('search',$_POST)){
    if($_POST['search']!=''){
        $args['blog_search'] = $_POST['search'];
        $args['posts_per_page'] = '-1';

        $html= '<div class="post scroll_animate" data-position="self">';
        $html.= '"'.$_POST['search'].'" No tiene resultados';
        $html.= '</div>';
    }
}


$iblogs = 0;
$blogs = new wp_query($args);
if($blogs->have_posts()){
    $html='';

    if(array_key_exists('search',$_POST)){
        if($_POST['search']!=''){
            $html= '<div class="title scroll_animate" data-position="self">';
            $html.= 'Se encontraron ('.$blogs->found_posts.') resultados para "'.$_POST['search'].'"';
            $html.= '</div>';
        }
    }


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


        $html.= '<div class="post scroll_animate" data-position="self">';
        $html.= '<div class="image easyload" data-original="'.$blogs_imagen[0].'" style="background-image:url('.$blogs_thumbnail[0].');"></div>';

        $html.= '<div class="detail">';
        $html.= '<div class="title">'.the_title('','',false).'</div>';
        $html.= '<div class="autor scroll_animate" data-position="right">'.$blogs_custom['meta_blog_author_name'][0].'</div>';
        $html.= '<div class="content scroll_animate" data-position="self">'.wp_trim_words(strip_tags(get_the_content()), 50, $more = ' ...').'</div>';

        if(count($blogs_category)>0){
            $html.= '<div class="category scroll_animate" data-position="self">'.implode(', ',$blogs_category_name).'</div>';
        }

        $html.= '<div class="button more scroll_animate" data-position="self">'.__('MORE').'</div>';
        $html.= '</div>';

        $html.= '<a class="absolute" href="'.esc_url(get_permalink()).'" target="_blank" title="'.the_title('','',false).'"></a>';
        $html.= '</div>';
    }

    if($_POST['search']==''){
        $html.= '<div class="post redirect scroll_animate" data-position="self">';
        $html.= 'BUSCANDO MAS NOTICIAS';
        $html.= '</div>';

    }else{
        $html.= '<div class="post loadfirst scroll_animate" data-position="self">';
        $html.= '';
        $html.= '</div>';
    }
}
echo $html;

wp_reset_postdata();
?>