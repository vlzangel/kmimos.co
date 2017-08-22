<?php
header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=utf-8' );
header(sprintf( 'Content-Disposition: attachment; filename=my-csv-%s.csv', date( 'dmY-His' ) ) );
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

//include_once(dirname(dirname(dirname(__DIR__))).'/wlabel.php');
//$return['message']=$_wlabel_user->LogOut();

//FILE
$module=$_POST['module'];
$urlbase=$_POST['urlbase'];
$file='/csv/detalle_'.$module.'_'.date('Ymd',time()).'.csv';
$file_path=dirname(__FILE__).$file;
$file_url=$urlbase.$file;

$text="";
$text.=$_POST['title']."\n\n";
$text.=$_POST['data'];

////// CREATE CSV
$handle = fopen($file_path,'w+');
fwrite($handle,$text);
fclose($handle);

$return['message']='Exportado';//.$text
$return['file']='<a class="file" href="'.$file_url.'">Ver CSV</a>';
echo json_encode($return);
?>