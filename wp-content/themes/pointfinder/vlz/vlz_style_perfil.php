<?php 
$HTML = "
<style type='text/css'>
	.vlz_contenedor{
	    max-width: 1140px;
	    margin: 20px auto 20px;
	    border: solid 2px #59c9a8;
	    border-radius: 5px;
	    border-top: solid 35px #59c9a8;
	    border-bottom: solid 15px #59c9a8;
	}
	.vlz_contenedor_header{
		display: table;
	    width: 100%;
	}
	.vlz_contenedor_header div{
		display: table-cell;
	    text-align: center;
	    vertical-align: middle;
	    padding: 10px 10px 0px;
	}
	.vlz_contenedor_header div div{
		display: block;
	}
	.vlz_lados{
		width: 50%;
	}
	.vlz_valoraciones{
		font-size: 11px;
		font-weight: bold;
		color: #777;
	    clear: left;
	}
	.wpf-container, #pfuaprofileform div.mce-fullscreen {
	    margin: 10px 10px 10px;
	}
	.vlz_separador{
		border-bottom: solid 1px #59c9a8;
		margin: 10px;
	}
	.vlz_titulo{
	    font-size: 18px;
	    color: #555555;
	    margin: 0px 10px 10px;
	}
	.vlz_seccion{
		margin: 0px 10px;
	}
	.vlz_descripcion{
		text-align: justify;
	}
	.label-small{
	    line-height: 20px;
	    text-align: justify;
	}
	.vlz_detalles{
	    font-family: Arial, Helvetica, Verdana, sans-serif;
		font-size: 12px;
		overflow: hidden;
	}
	.vlz_detalles *{
		text-align: center;
	}

	.conocer-cuidador, .reservar{
		margin: 5px 3px;
		display: inline-block;
	    cursor: pointer;
	}

	.vlz_item_detalles{
		width: 25%;
		float: left;
		box-sizing: border-box;
		padding: 10px;
	}

	.vlz_separador_item{
		display: inline-block;
	}

	.comment-textarea{
		text-align: justify;
	}

	.woocommerce ul.products li.product, .woocommerce-page ul.products li.product, .woocommerce-page[class*=columns-] ul.products li.product, .woocommerce[class*=columns-] ul.products li.product {
	    border: solid 1px #848484;
		padding: 0px !important;
	    border-radius: 3px;
	}

	.woocommerce ul.products li.product a {
	    text-decoration: none;
	    display: block;
	}

	.woocommerce ul.products li.product small {
		display: block;
		padding: 0px 5px;
	}

	div#rating {
	    width: 160px;
	}

	.vlz_valoraciones{
        margin: 0px auto 5px;
	}

	textarea{
	    border: 1px solid #59c9a8 !important;
	    resize: none !important;
	}

	.woocommerce ul.products li.product a img, .woocommerce-page ul.products li.product a img {
	    margin: 5px;
	    width: calc( 100% - 10px );
	}

	.woocommerce ul.products li.product{
		border: solid 1px #009a86 !important;
    	overflow: hidden;
	}

	.vlz_img_portada{ position: relative; height: 250px; overflow: hidden; border: solid 1px #ccc; background: #EEE; }
	.vlz_img_portada_fondo{ position: absolute; top: 0px; left: 0px; width: 100%; height: 250px; z-index: 50; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: transparent; filter: blur(2px); transition: all .5s ease; }
	.vlz_img_portada_normal{ position: absolute; top: 0px; left: 0px; width: 100%; height: 225px; z-index: 100; background-size: contain; background-position: center; background-repeat: no-repeat; background-color: transparent; margin: 10px 0px; transition: all .5s ease; }
	.vlz_cambiar_portada{ position: absolute; bottom: 10px; right: 10px; width: auto; padding: 10px; font-size: 16px; color: #FFF; background: #23475e; border: solid 1px #fff; z-index: 200; }
	.vlz_cambiar_portada input{ position: absolute; top: -24px; left: 0px; width: 100%; height: 167%; z-index: 100; opacity: 0; cursor: pointer; }
	
	.reply{
		display: none !important;
	}

	/*	Galería */

	.vlz_contenedor_galeria{
		height: 250px;
		overflow: auto;
	}

	.vlz_contenedor_galeria_interno{
		height: 100%;
	}
	.vlz_item {
	    height: 100%;
	    width: 300px;
	    float: left;
	    position: relative;
	    cursor: pointer;
	}
	.vlz_item_fondo {
	    position: absolute;
	    top: 2px;
	    left: 2px;
	    width: calc( 100% - 4px );
	    height: calc( 100% - 4px );
	    background-position: center;
	    background-size: cover;
	    background-repeat: no-repeat;
	    filter: blur(2px);
	    overflow: hidden;
	}
	.vlz_item_imagen {
	    position: absolute;
	    top: 10px;
	    left: 10px;
	    width: calc( 100% - 20px );
	    height: calc( 100% - 20px );
	    z-index: 100;

	    background-position: center;
	    background-size: contain;
	    background-repeat: no-repeat;
	}

	.vlz_modal_galeria {
	    position: fixed;
	    top: 0px;
	    left: 0px;
	    width: 100%;
	    height: 100%;
	    z-index: 9999999999;
	    background-color: rgba(0, 0, 0, 0.8);
	    display: none;
	    cursor: pointer;
	}

	.vlz_modal_galeria_interna {
	    position: fixed;
	    top: 50px;
	    left: 50px;
	    width: calc( 100% - 100px );
	    height: calc( 100% - 100px );
	    z-index: 100;
	    display: table-cell;
	    text-align: center;
	    vertical-align: middle;

	    background-position: center;
	    background-size: contain;
	    background-repeat: no-repeat;
	    cursor: pointer;
	}

	@media (max-width: 1140px){
		.vlz_contenedor{
		    margin: 0px 10px 20px;

		}
	}

	@media (min-width: 1024px) {
		.wpf-container {
	        margin: 130px 0 0 0 !important;
		}
	}

	@media (max-width: 770px) {
		.wpf-container{ margin: 45px 10px 10px; }

		.comment_valuation{
	        width: 50% !important;
		    box-sizing: border-box;
		    margin: 0px !important;
		}
	}

	@media (max-width: 650px) {
		.vlz_contenedor_header div{
			display: block;
			text-align: center;
			width: 100%;
		}
		.vlz_centro{
			padding-bottom: 10px !important;
		}

		.vlz_item_detalles{
			width: 50%;
		}

		.vlz_separador_item{
		    clear: left;
		    display: block;
		}
	}

	@media (max-width: 600px) {
		.woocommerce ul.products li.product, .woocommerce-page ul.products li.product, .woocommerce-page[class*=columns-] ul.products li.product, .woocommerce[class*=columns-] ul.products li.product {
		    width: 50%;
		    float: left;
		    clear: both;
		    margin: 0px;
		    box-sizing: border-box;
		    padding: 3px;
		    border: 0;
		}
	}

	@media (max-width: 570px) {
		.wpf-container {
		    margin: 65px 10px 10px;
		}
	}

	@media (max-width: 540px) {
		.woocommerce ul.products li.product, .woocommerce-page ul.products li.product, .woocommerce-page[class*=columns-] ul.products li.product, .woocommerce[class*=columns-] ul.products li.product {
		    width: 100%;
		    float: left;
		    clear: both;
		    margin: 0px;
		    box-sizing: border-box;
		    padding: 3px;
		    border: 0;
		}
		.wpf-container {
		    margin: 65px 10px 10px;
		}

		div#rating {
		    width: 125px;
		    display: inline-block;
		    float: right;
		}

		div#rating>img {
		    width: 22px;
		    float: none;
		    margin-top: -12px;
		}
	}

	@media (max-width: 350px) {
		.comment_valuation{
	        width: 100% !important;
		    box-sizing: border-box;
		    margin: 0px !important;
		}
		.wpf-container {
		    margin: 10px 10px 10px;
		}
	}
</style>";
?>