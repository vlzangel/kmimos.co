<?php 
/*
Template Name: IDX Template
*/

get_header();
	
	if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}

    $setup_item_idx_sidebarpos = PFASSIssetControl('setup_item_idx_sidebarpos','','2');

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