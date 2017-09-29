<?php

$styles = "
.vlz_titulo_interno span {
    color: #d80606;
    font-size: 11px;
    vertical-align: middle;
    float: none;
    display: block;
    line-height: 1.2;
    margin-top: 0px;
}

label{
    display: block;
}

.no_error, .no_kmiayuda{
	display: none;
}

.error, .kmiayuda{
	display: block;
    font-size: 10px;
    border: solid 1px #CCC;
    padding: 3px;
    border-radius: 0px 0px 3px 3px;
    background: #ffdcdc;
    line-height: 1.2;
    font-weight: 600;
}

.kmiayuda{
    background: #fdffdc;
}

.vlz_input_error{
    border-radius: 3px 3px 0px 0px !important;
	border-bottom: 0px !important;
}

.vlz_contenedor_listados, .vlz_contenedor_dir {
    width: calc( 33.3333% - 4px );
    display: inline-block;
    margin-bottom: 5px;
}

.vlz_banner_cuidador_contenedor{
	text-align: right;
}

div{
	vertical-align: top;
}

.vlz_banner_footer{
	margin: 15px 0px; 
	border-top: solid 1px #000; 
	border-bottom: solid 1px #000; 
	padding: 5px;
}

.vlz_banner_footer .vlz_cell75{
	color: #CCC;
	font-size: 19px;
	line-height: 1.1;
	text-align: justify;
}

.vlz_verde {
    color: #2d9a7a;
}

.vlz_naranja {
    color: #ffa500;
}

.vlz_gris_footer {
    position: absolute;
    bottom: 0px;
    display: inline-block;
    left: 0px;
}

.vlz_banner_footer div{
	height: 100px;
}

.vlz_banner_footer img{
	height: 100%;
}

.vlz_banner_footer .vlz_cell25{
	text-align: center;
}

.vlz_sub_seccion_mensaje {
    padding: 2px 2px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    margin: 2px 0px 5px !important;
}

@media (max-width: 992px){
	.wpf-container {
	    margin: 0px !important;
	}
	.vlz_banner_footer .vlz_cell75{
		font-size: 17px;
	}
}

@media screen and (max-width: 769px){
	section.blog-full-width .pf-container {
	    margin-top: 110px !important;
	}
	.vlz_titulo_contenedor,
	.vlz_banner_cuidador_contenedor
	{
		width: calc(100% - 9px) !important;
	}
	.vlz_banner_footer .vlz_cell75{
		font-size: 16px;
	}
	.vlz_banner_footer .vlz_cell25{
		width: calc(25% - 9px) !important;
	}
}

@media screen and (max-width: 750px){
	.vlz_modal_ventana{
		width: 90% !important;
	}
	.vlz_banner_footer div{
		height: 110px;
	}
}

@media screen and (max-width: 568px){
	section.blog-full-width .pf-container {
	    margin-top: 40px !important;
	}
	#vlz_mapa {
	    height: 250px !important;
	}
	#vlz_boton_dir, #vlz_campo_dir{
	    width: calc(100% - 9px) !important;
	}
	#vlz_boton_dir{
	    margin-top: 5px !important;
	}
	#check_term{
	    display: block;
	    padding-right: 30px;
	    font-size: 12px !important;
	    height: auto !important;
	}
	#boton_registrar_modal{
	    display: inline-block;
	    font-size: 13px;
	    margin-top: 5px;
	}
	.vlz_modal_contenido {
	    height: 320px !important;
	}

	.vlz_contenedor_listados, .vlz_contenedor_dir{
	    width: calc( 100% - 9px ) !important;
		margin-bottom: 5px;
	}
	.vlz_banner_footer .vlz_cell75{
		font-size: 13px;
	}

	.vlz_banner_footer div{
	height: 80px;
	}
	.vlz_banner_footer .vlz_cell25{
		width: calc(25% - 9px) !important;
	}
	.vlz_banner_footer .vlz_cell75{
		width: calc(75% - 9px) !important;
	}
}

@media screen and (max-width: 500px){
	.vlz_banner_footer .vlz_cell75{
		font-size: 12px;
	}
	.vlz_banner_footer div{
		height: 100px;
	}
	.vlz_banner_footer .vlz_cell25{
		width: calc(30% - 9px) !important;
	}
	.vlz_banner_footer .vlz_cell75{
		width: calc(70% - 9px) !important;
	}
	.vlz_parte{
	    margin-top: 30px !important;
	}
}

@media screen and (max-width: 420px){
	.vlz_banner_footer .vlz_cell75{
		font-size: 10px;
	}
	.vlz_banner_footer div{
		height: 100px;
	}
	.vlz_banner_footer .vlz_cell25{
		width: calc(40% - 9px) !important;
	}
	.vlz_banner_footer .vlz_cell75{
		width: calc(60% - 9px) !important;
	}
}

.vlz_parte{
    position: relative;
    padding: 0px 10px;
    border: solid 3px #87b8ab;
    border-radius: 0px 3px 3px 3px;
    margin-top: 40px;
}

.vlz_titulo_parte{
	position: absolute;
	top: -32px;
    left: -3px;
    background: #87b8ab;
    padding: 5px 50px 5px 10px;
    border-radius: 3px 3px 0px 0px;
    color: #FFF;
    font-size: 16px;
}

.vlz_form div{ vertical-align: top; }

.vlz_titulo{
    text-align: left;
    font-size: 36px;
    margin-top: 0px;
    margin-bottom: 20px;
    line-height: 1.1;
}
.vlz_sub_titulo{
	font-size: 16px;
	color: #555555 !important;
	margin-bottom: 0px !important;
	text-align: justify;
}

.vlz_titulo_interno{
    color: #87B8AB;
    font-size: 18px;
    font-weight: 500;
    border-bottom: 2px solid #87B8AB;
    margin-bottom: 20px;
    padding-bottom: 10px;
}

.vlz_cell20  { position: relative; margin: 0px 3px; display: inline-block; width: calc( 20% - 9px ); }
.vlz_cell25  { position: relative; margin: 0px 3px; display: inline-block; width: calc( 25% - 9px ); }
.vlz_cell33  { position: relative; margin: 0px 3px; display: inline-block; width: calc( 33.333333333% - 9px ); }
.vlz_cell50  { position: relative; margin: 0px 3px; display: inline-block; width: calc( 50% - 9px ); }
.vlz_cell66  { position: relative; margin: 0px 3px; display: inline-block; width: calc( 66.666666666% - 9px ); }
.vlz_cell75  { position: relative; margin: 0px 3px; display: inline-block; width: calc( 75% - 9px ); }
.vlz_cell100 { position: relative; margin: 0px 3px; display: inline-block; width: calc( 100% - 9px ); }

.vlz_seccion{
    margin-bottom: 3rem !important;
    overflow: hidden;
}

.vlz_sub_seccion{
    margin-bottom: 15px;
}

.vlz_input{
	display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857;
    color: #6F7272;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    -o-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
}

.vlz_input:focus{
	outline: 0px;
}

textarea.vlz_input{
	resize: none;
	height: 103px;
}

.vlz_check, .vlz_no_check{
	cursor: pointer; 
	position: relative;
	background-color: #EEE;
	font-size: 12px;
    padding: 8px 12px;
}

.vlz_check{
	background-color: #a8d8c9;
	border: 1px solid #87b8ab;
}

.vlz_no_check:after{
	content: ' ';
    position: absolute;
    top: 8px;
    right: 8px;
    width: 15px;
    height: 15px;
    border: solid 1px #CCC;
    border-radius: 2px;
}

.vlz_check:after{
	content: ' ';
    position: absolute;
    top: 8px;
    right: 8px;
    width: 15px;
    height: 15px;
    border: 1px solid #87b8ab;
    border-radius: 2px;
    background-image: url(".get_home_url()."/wp-content/themes/pointfinder/vlz/img/iconos/new_check.png);
    background-size: 11px;
    background-repeat: no-repeat;
    background-position: 1px 1px;
}

#vlz_mapa{
	height: 400px;
}

.vlz_img_portada{ position: relative; height: 250px; overflow: hidden; border: solid 1px #ccc; background: #EEE; }
.vlz_img_portada_fondo{ position: absolute; top: 0px; left: 0px; width: 100%; height: 250px; z-index: 50; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: transparent; filter: blur(2px); transition: all .5s ease; }
.vlz_img_portada_normal{ position: absolute; top: 0px; left: 0px; width: 100%; height: 225px; z-index: 100; background-size: contain; background-position: center; background-repeat: no-repeat; background-color: transparent; margin: 10px 0px; transition: all .5s ease; }
.vlz_cambiar_portada{ position: absolute; bottom: 10px; right: 10px; width: auto; padding: 10px; font-size: 16px; color: #FFF; background: #23475e; border: solid 1px #fff; z-index: 200; }
.vlz_cambiar_portada input{ position: absolute; top: -24px; left: 0px; width: 100%; height: 167%; z-index: 100; opacity: 0; cursor: pointer; }

.vlz_contenedor_botones_footer{
	position: relative;
	overflow: hidden;
	padding: 20px 0px;
	border-top: solid 3px #2ca683;
}

.vlz_boton_siguiente{
    padding: 10px 50px;
    background-color: #a8d8c9;
    display: inline-block;
    font-size: 16px;
    border: solid 1px #2ca683;
    border-radius: 3px;
    float: right;
    cursor: pointer;
}   

.vlz_boton_agregar{
    padding: 5px 25px;
    background-color: #a8d8c9;
    display: inline-block;
    font-size: 14px;
    border: solid 1px #2ca683;
    border-radius: 3px;
    cursor: pointer;
    float: right;
}

.vlz_boton_quitar{
	position: relative;
    padding: 12px;
    background: #c79797;
    margin: 5px 0px;
    border-radius: 3px;
}  

.vlz_boton_quitar:after{
	content: ' ';
    position: absolute;
    top: 9px;
	right: calc( 50% - 8px );
    width: 15px;
    height: 15px;
    border-radius: 2px;
    background-image: url(".get_home_url()."/wp-content/themes/pointfinder/vlz/img/iconos/quitar_5.png);
    background-size: 11px;
    background-repeat: no-repeat;
    background-position: 1px 1px;
    cursor: pointer;
}

.vlz_bloqueador{
	position: absolute;
	top: 0px;
	left: 0px;
	width: 100%;
	height: 100%;
	z-index: 1000;
	display: none;
} 

#vlz_boton_registrar{
	width: 130px;
    padding: 7px 0px;
}

.vlz_val{
	display: none;
    position: absolute;
    top: 0px;
    right: 0px;
    background: #da2d2d;
    padding: 0px 5px;
    color: #FFF;
    font-size: 12px;
    font-weight: 600;
    border-radius: 0px 0px 0px 3px;
}

.vlz_modal{
	position: fixed;
	top: 0px;
	left: 0px;
	width: 100%;
	height: 100%;
	display: table;
	z-index: 10000;
	background: rgba(0, 0, 0, 0.8);
	vertical-align: middle !important;
	display: none;
}

.vlz_modal_interno{
	display: table-cell;
	text-align: center;
	vertical-align: middle !important;
}

.vlz_modal_ventana{
    position: relative;
    display: inline-block;
    width: 80%;
    text-align: left;
    box-shadow: 0px 0px 4px #FFF;
    border-radius: 5px;
    z-index: 1000;
}

.vlz_modal_titulo{
    background: #FFF;
    padding: 15px 10px;
    font-size: 18px;
    color: #52c8b6;
    font-weight: 600;
    border-radius: 5px 5px 0px 0px;
}

.vlz_modal_contenido{
    background: #FFF;
    height: 450px;
    box-sizing: border-box;
    padding: 5px 15px;
    border-top: solid 1px #d6d6d6;
    border-bottom: solid 1px #d6d6d6;
    overflow: auto;
    text-align: justify;
}

.vlz_modal_pie{
    background: #FFF;
    padding: 15px 10px;
    border-radius: 0px 0px 5px 5px;
}

.vlz_modal_fondo{
	position: fixed;
	top: 0px;
	left: 0px;
	width: 100%;
	height: 100%;
    z-index: 500;
}

.vlz_modal_cerrar{
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 20px;
    font-weight: 400;
    font-family: Roboto;
    cursor: pointer;
    display: none;
}

.kmimos_cargando{
	position: absolute;
    top: calc( 50% - 25px );
    left: calc( 50% - 25px );
    z-index: 99999999999999;
    width: 50px;
    opacity: 0.7;
    display: none;
}";

$styles = str_replace("\t", "", $styles);
$styles = str_replace("  ", " ", $styles);
$styles = str_replace("\n", " ", $styles);

echo "<style type='text/css'>".$styles."</style>";
?>