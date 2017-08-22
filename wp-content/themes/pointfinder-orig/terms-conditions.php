<?php 
/*
Template Name: Terms & Conditions 2
*/

	get_header();
		
		if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}

		echo '<section role="main">';
	        echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	        echo '<div class="pf-container"><div class="pf-row">';
	        	
	        		echo '<div class="col-lg-12">';
						echo '<div class="">'; 
							if (have_posts()){ 
			        			while (have_posts()) : the_post();
								the_content();
								endwhile;
							};
						echo '</div>';
					echo '</div>';
	            
	        echo '</div></div>';
	        echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
	    echo '</section>';

	get_footer();
?>