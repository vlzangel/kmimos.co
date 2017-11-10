<?php 
    /*
        Template Name: Beneficios
    */
    
    wp_enqueue_style('beneficios_kmimos', $home."/wp-content/themes/pointfinder/css/beneficios.css", array(), '1.0.0');
	wp_enqueue_style('beneficios_responsive', $home."/wp-content/themes/pointfinder/css/responsive/beneficios_responsive.css", array(), '1.0.0');

    get_header();

	
        
        $HTML = "
        <ul class='beneficios'>
        	<li>
        		<div>
				    <img src='https://kmimos.com.mx/wp-content/uploads/2016/02/beneficios-1.png' alt='beneficios-1'>
				    <h1 style='text-align: center;'>Cobertura veterinaria para tu mascota</h1>
				    <p>
				    	Tu perrito tendrá cobertura contra accidentes durante su estadía con nuestros cuidadores asociados.
				    </p>
        		</div>
			</li>
        	<li>
        		<div>
				    <img src='https://kmimos.com.mx/wp-content/uploads/2016/02/beneficios-2.png' alt='' >
		            <h1 style='text-align: center;'>Cuidadores Certificados</h1>
		            <p>
		            	Nuestros cuidadores asociados pasan por un riguroso proceso de certificación, basado en estándares internacionales y mediante los que
		                evaluamos su integridad, conocimientos y experiencia en el cuidado de perros.
		            </p>
        		</div>
			</li>
        	<li>
        		<div>
				    <img src='https://kmimos.com.mx/wp-content/uploads/2016/02/beneficios-3.png' alt='' width='100' height='100'>
		            <h1 style='text-align: center;'>Fotografías y videos diarios</h1>
		            <p>
		            	Desde el momento en el que tu perrito llega al hogar de nuestros cuidadores asociados, se toman fotografías y/o videos que serán compartidos
		                contigo de manera diaria para que puedas ver cómo la están pasando en su estadía. Nuestro personal monitorea de manera constante a
		                los cuidadores asociados mientras tu amigo vive la experiencia Kmimos.
		            </p>
        		</div>
			</li>
        	<li>
        		<div>
				    <img src='https://kmimos.com.mx/wp-content/uploads/2016/02/beneficios-4.png' alt='' width='100' height='100'>
		            <h1 style='text-align: center;'>Atención personalizada</h1>
		            <p>
		            	Brindamos cualquier apoyo necesario para garantizar que tu perrito disfruta la experiencia mas placentera que jamás haya tenido, y para
		                ello tanto los clientes como cuidadores asociados Kmimos recibirán atención 24 horas durante la estadía de tu perrito.
		            </p>
        		</div>
			</li>
	    </ul>";

		echo comprimir_styles($HTML);

    get_footer(); 
?>