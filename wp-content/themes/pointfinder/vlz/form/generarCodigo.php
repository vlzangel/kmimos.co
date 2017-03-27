<?php
function generarClave($lenght=8){
	$lenght = ($lenght<8)? 8 : $lenght;
	$char = strRand(1, "abcdefghijklmnopqrstuvwxyz");
	$char .= strRand(1, "ABCDEFGHIJKLMNOPQRSTUVWXYZ");
	$char .= strRand(1, "0123456789");
	$char .= strRand(1, "#");
	$char .= strRand($lenght-4);
	return str_shuffle($char);
}
function strRand($lenght=8, $char = ""){
	if($char == ""){
		$char = "#0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	}
		return substr(str_shuffle($char), 0, $lenght);
}
 
//echo generarClave();
?>



