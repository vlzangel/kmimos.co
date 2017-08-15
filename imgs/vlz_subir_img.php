<?php
	extract($_POST);

	if( $previa != "" ){
        if( file_exists("Temp/".$previa) ){
            unlink("Temp/".$previa);
        }
	}

	$img = end(explode(',', $img));
    $sImagen = base64_decode($img);

    $dir = "Temp/";

    if( !file_exists($dir) ){
        @mkdir($dir);
    }

    $name = time().".jpg";
    $path = $dir.$name;

    file_put_contents($path, $sImagen);

    $sExt = mime_content_type( $path );

    switch( $sExt ) {
        case 'image/jpeg':
            $aImage = @imageCreateFromJpeg( $path );
        break;
        case 'image/gif':
            $aImage = @imageCreateFromGif( $path );
        break;
        case 'image/png':
            $aImage = @imageCreateFromPng( $path );
        break;
        case 'image/wbmp':
            $aImage = @imageCreateFromWbmp( $path );
        break;
    }

    $nWidth  = 800;
    $nHeight = 600;

    $aSize = getImageSize( $path );

    if( $aSize[0] > $aSize[1] ){
        $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
    }else{
        $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
    }

    $aThumb = imageCreateTrueColor( $nWidth, $nHeight );

    imageCopyResampled( $aThumb, $aImage, 0, 0, 0, 0, $nWidth, $nHeight, $aSize[0], $aSize[1] );

    imagejpeg( $aThumb, $path );

    imageDestroy( $aImage );
    imageDestroy( $aThumb );

    echo $name;
?>