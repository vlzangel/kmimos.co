<?php
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

$page=0;
$limit=2;
$direction='next';

if(array_key_exists('direction',$_POST)){
    $direction=$_POST['direction'];
}

if(array_key_exists('page',$_POST)){
    $page=$_POST['page'];
}

$qpage=$page;
if($direction=='next'){
    $qpage=$page+1;

}else if($direction=='prev' && $page>0){
    $qpage=$page-1;
}


//var_dump($qpage);
$row=$qpage*1;//$limit;
$query = "SELECT * FROM destacados ORDER BY id LIMIT $row, $limit";//rand()
$featureds = $wpdb->get_results($query);
$result=false;
$html='';

if(count($featureds)>0){
    $page=$qpage;
    foreach ($featureds as $featured){
        $result=true;
        //var_dump($featured);
        $caregiver = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$featured->cuidador}");
        $state = $wpdb->get_row("SELECT * FROM states WHERE id = {$featured->estado}");
        $data = $wpdb->get_row("SELECT *, post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$caregiver->id_post}");

        $votes=kmimos_petsitter_rating_and_votes($caregiver->id_post);
        $bone=site_url().'/wp-content/uploads/iconos/bone.svg';
        $img = kmimos_get_foto_cuidador($featured->cuidador);
        $url = get_home_url()."/petsitters/".$data->url;

        //EXPERIENCE
        $experience = $caregiver->experiencia;
        if($experience>1000){
            $experience=date('Y',time())-$experience;
        }

        if($experience==0 ||  $experience==''){
            $experience='Sin experiencia';
        }else{
            $experience=$experience.' año(s) de experiencia';
        }

        $img=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$img);
        $bone=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$bone);

        $img=str_replace('http://kmimosmx.sytes.net/QA1/','https://kmimos.com.mx/',$img);
        $bone=str_replace('http://kmimosmx.sytes.net/QA1/','https://kmimos.com.mx/',$bone);

        $html.='<div class="post">';// scroll_animate" data-position="self

        $html.='<div class="data">';
        $html.='<div class="rating">'.round($votes['rating']).'</div>';
        $html.='<div class="bone" style="background-image: url('.$bone.');"></div>';
        $html.='</div>';

        $html.='<div class="image">';
        $html.='<div class="img" data-original="'.$img.'" style="background-image:url('.$img.');"></div>';// easyload
        $html.='</div>';

        $html.='<div class="detail">';
        $html.='<div class="title">'.$data->nom.'</div>';
        $html.='<div class="content state">'.utf8_decode($state->name).'</div>';
        //$html.='<div class="content experience">'.$experience.'</div>';
        $html.='<div class="content price">Desde $ '.($caregiver->hospedaje_desde*1.2).'</div>';
        $html.='</div>';

        $html.= '<a class="absolute" href="'.$url.'" target="_blank" title="'.$data->nom.'"></a>';
        $html.='</div>';
    }
}

echo json_encode(array('result'=>$result,'page'=>$page,'html'=>$html));
?>