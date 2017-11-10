<?php

	$param = ( !empty($_SERVER['QUERY_STRING']) )? '&'.$_SERVER['QUERY_STRING'] : '&sindatos' ;	
	$url = 'https://www.kmimos.com.mx/?wlabel=volaris'.$param;
	header('Location:'.$url );
	if($_SERVER['HTTP_HOST']!='www.kmimos.com.mx'){
	  header('Location: https://www.kmimos.com.mx/volaris/?'.$param);
	}

	//echo $url . '<br>' . $param;
	exit();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <style type="text/css"> 
            html, body{ position: relative; height: 100%;  margin: 0; padding: 0;}
            iframe{ position: absolute; width: 1px; min-width: 100%; *width: 100%; height: 100%; border: none;}
        </style>
    </head>
    <body>
        <iframe src="<?php echo $url; ?>" allowfullscreen=""></iframe>
    </body>
</html>
