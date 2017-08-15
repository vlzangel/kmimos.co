<?php
/**********************************************************************************************************************************
*
* Welcome to Point Finder Page
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

// add page visible to editors
add_action( 'admin_menu', 'register_my_page',7);
function register_my_page(){
    add_menu_page( esc_html__('Point Finder Settings','pointfindert2d'), esc_html__('PF Settings','pointfindert2d'), 'manage_options', 'pointfinder_tools', 'pointfinder_tools_content', 'dashicons-location' ); 
}

function pointfinder_tools_content(){
?>
	<div class="wrap about-wrap">

      <h1><?php echo esc_html__('Welcome to PointFinder','pointfindert2d');?></h1>

      <div class="about-text"><?php echo esc_html__('Thank you for purchase Point Finder!','pointfindert2d');?></div>

      <h2 class="nav-tab-wrapper">
      	<a href="<?php echo admin_url('admin.php?page=pointfinder_tools');?>" class="nav-tab nav-tab-active">
           <?php echo esc_html__('Instruction','pointfindert2d');?></a>
       
        <a href="<?php echo admin_url('admin.php?page=pointfinder_demo_installer');?>" class="nav-tab nav-tab">
           <?php echo esc_html__('Quick Setup','pointfindert2d');?></a>

      </h2>
      
      <div class="changelog headline-feature">
        <h2><?php echo esc_html__('Introducing Point Finder','pointfindert2d');?></h2>
        <div class="featured-image">
          <img src="<?php echo get_home_url()."/wp-content/themes/pointfinder";?>/images/pointfinder.png">
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
      </div>

    </div>
    <?php
}
?>
