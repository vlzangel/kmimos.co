<?php
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

$page=0;
$limit=4;
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

$row=$qpage*1;//$limit;
$query = "SELECT * FROM destacados ORDER BY id LIMIT $row, $limit";//rand()
$featureds = $wpdb->get_results($query);
$result=false;
$html='';

foreach ($featureds as $featured){
    $result=true;
    //var_dump($featured);
    $caregiver = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$featured->cuidador}");
    $price='$0.00';
    $img = kmimos_get_foto($featured->cuidador);
    $url = get_home_url()."/petsitters/".$data->url;


    $img=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$img);
    $img=str_replace('http://kmimosmx.sytes.net/QA1/','https://kmimos.com.mx/',$img);

    $html.='<div class="product scroll_animate" data-position="self" data-scale="small">';
    $html.='<div class="image easyload" data-original="'.$img.'" style="background-image:url();"></div>';

    $html.='<div class="detail">';
    $html.='<div class="title">'.$data->nom.'</div>';
    $html.='<div class="content price">'.$price.'</div>';
    $html.='<div class="button">VER MAS</div>';
    $html.='</div>';

    $html.='<a class="absolute" href="'.$url.'" target="_blank" title="'.$data->nom.'"></a>';
    $html.='</div>';
}

echo json_encode(array('result'=>$result,'page'=>$page,'html'=>$html));
?>