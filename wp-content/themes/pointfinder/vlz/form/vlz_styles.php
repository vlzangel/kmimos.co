<style type="text/css">

	.vlz_parte{
	    position: relative;
	    padding: 0px 10px;
	    border: solid 3px #87b8ab;
	    border-radius: 0px 3px 3px 3px;
	    margin-top: 50px;
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
	    text-align: center;
	    font-size: 36px;
	    margin-top: 20px;
   	 	margin-bottom: 20px;
	}
	.vlz_sub_titulo{
		font-size: 18px;
		color: #555555 !important;
		margin-bottom: 20px !important;
		text-align: center;
	}

	.vlz_titulo_interno{
	    color: #87B8AB;
	    font-size: 18px;
	    font-weight: 500;
	    border-bottom: 2px solid #87B8AB;
	    margin-bottom: 20px;
	    padding-bottom: 10px;
	}

	/*.vlz_titulo_interno span{
	    color: #d80606;
		font-size: 12px;
		vertical-align: middle;
		float: right;
		line-height: 1.2;
		padding: 7px 0px;
	}*/

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
		height: 153px;
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
	    background-image: url(<?php echo get_template_directory_uri()."/vlz/img/iconos/new_check.png"; ?>);
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
	    background-image: url(<?php echo get_template_directory_uri()."/vlz/img/iconos/quitar_5.png"; ?>);
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

/*	input:valid, textarea:valid, select:valid {
	  	border: solid 1px rgb(89, 201, 168);
	}
	input:invalid, textarea:invalid, select:invalid {
	 	border: solid 1px rgba(232, 29, 29, 0.45);
	}*/


	/*
		Estilos del Modal
	*/

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

</style>