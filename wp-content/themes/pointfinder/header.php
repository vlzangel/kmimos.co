<?php include 'pre-header.php'; ?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">		
		<?php
			if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
        		header('X-UA-Compatible: IE=edge,chrome=1');
        	}
			if ( is_page() ){
				global $post;
				$descripcion = get_post_meta($post->ID, 'kmimos_descripcion', true);
        		if( $descripcion != ""){
        			echo "<meta name='description' content='{$descripcion}'>";
        		}else{ ?> <meta name="description" content="<?php esc_html(bloginfo('description')); ?>"> <?php }
        	}else{ ?> <meta name="description" content="<?php esc_html(bloginfo('description')); ?>"> <?php }
        	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">';

			wp_enqueue_style( 'vlz', get_home_url()."/wp-content/themes/pointfinder/css/vlz.css?v=1.0.0" );
			wp_head(); 
		?>
	</head>
	<body <?php body_class(); ?> > <?php 

	$MENU = get_menu_header();
	$HTML = '
		<div id="pf-loading-dialog" class="pftsrwcontainer-overlay"></div>
        <header class="wpf-header hidden-print" id="pfheadernav">
		    <div class="pftopline wpf-transition-all">
		       	<div class="pf-container">
					<div class="pf-row">
						<div class="col-lg-12 col-md-12">
							<div class="wpf-toplinewrapper">
								<div class="pf-toplinks-right clearfix">
									<nav id="pf-topprimary-nav" class="pf-topprimary-nav pf-nav-dropdown clearfix hidden-sm hidden-xs">
										<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu">
											<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu ">
												'.$MENU["head"].'			
												'.$MENU["body"].'			
												'.$MENU["footer"].'			
											</ul>
										</ul>
									</nav>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		    
		    <div class="wpf-navwrapper">
		        <a href="'.get_home_url().'/" class="jj-patica-menu">
		        	<img src="https://www.kmimos.com.mx/wp-content/uploads/2016/02/patita.png">
		        </a>  
		        <a id="pf-primary-nav-button" title="Menú">
		        	<i class="pfadmicon-glyph-500"></i>
		        </a>
				<a id="pf-topprimary-nav-button" title="Menú del Usuario">
					<i class="pfadmicon-glyph-632"></i>
				</a>
				<div class="pf-container pf-megamenu-container">
						<div class="pf-row col-md-2 col-lg-2">';


				if( $post->post_name == 'quiero-ser-cuidador-certificado-de-perros' ){
	$HTML .= 	'<div id="btn-instrucciones-header" class="hidden-xl hidden-lg hidden-md">
					<a 	class="btn-instrucciones-header pull-left hidden-md hidden-lg hidden-xl" onclick="jQuery(\'#modal_instrucciones\').css(\'display\', \'block\');">
						Instrucciones
					</a>
				</div>';
	 			}


	$HTML .= '				<a class="pf-logo-container hidden-xs hidden-sm" href="'.get_home_url().'"></a>
						</div>
						<div class="col-lg-10 col-md-10" id="pfmenucol1">
							<div class="pf-menu-container">
								<nav id="pf-primary-nav" class="pf-nav-dropdown clearfix">
									<style>
										.ser_cuidador{
											vertical-align: middle;
											text-align: center;
										}
										.ser_cuidador span{
											height: 74px;
											vertical-align: middle;
											display: inline-block;
										}
										.ser_cuidador div{
											vertical-align: middle;
											display: inline-block;
											padding: 5px 10px;
											background-color: #fefe78;
											color: #333333;
											box-sizing: border-box;
											-webkit-font-smoothing: antialiased;
											text-rendering: optimizeLegibility;
											font-family: "Open Sans";
											font-weight: 600;
											font-style: normal;
											border-radius: 2px;
										}
										.pfshrink .ser_cuidador span{
											height: 38px;
										}
										@media (max-width: 568px){
											.wpf-header #pf-primary-nav .pfnavmenu .main-menu-item > a {
												height: auto !important;
												line-height: 18px !important;
											}
											.wpf-header #pf-primary-nav .pfnavmenu .pfnavsub-menu > li a.sub-menu-link, .anemptystylesheet {
												text-align: left !important;
											}
											.wpf-header #pf-primary-nav li a, .wpf-header #pf-primary-nav.pfmobileview .pfnavmenu .main-menu-item > a {
												text-align: left !important;
											}
											#pfmenucol1, .pfnavmenu li.pf-megamenu-main {
												margin-bottom: 3px;
											}
											.wpf-navwrapper .pf-menu-container.pfactive {
												margin-top: 10px;
												margin-bottom: 15px;
											}
											.ser_cuidador {
												padding: 0px 0px 13px !important;
											}
											.ser_cuidador span {
												height: auto;
												vertical-align: middle;
												display: inline-block;
											}
											.ser_cuidador div {
												display: block;
												text-align: center;
											}

											#pf-primary-nav-button,
											#pf-topprimary-nav-button{
												display: inline-block;
												/*padding: 4px 0px;*/

											}
										} 
									</style>
									<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu">';

										echo comprimir_styles($HTML);

										$HTML = "";

										pointfinder_navigation_menu();
									
										$EC = is_cuidador();
										if( $EC != 1 ){
											$HTML .= "
												<li>
													<a class='ser_cuidador' href='".get_home_url()."/quiero-ser-cuidador-certificado-de-perros/'>
														<span></span>
														<div class='theme_button button_header'>Quiero ser cuidador</div>
													</a>
												<li>
											";
										} $HTML .= '
									</ul>

								</nav>	

								<nav id="pf-topprimary-navmobi" class="pf-topprimary-nav pf-nav-dropdown clearfix" style="display: none;">
									<ul class="pf-nav-dropdown  pfnavmenu pf-topnavmenu pf-nav-dropdownmobi">
										'.$MENU["body_movil"].'
									</ul>
								</nav>

								<script type="text/javascript">
									jQuery("#nav-menu-item-1617 ul a").attr("target", "_blank");
								</script>
						
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>

		<style type="text/css">
			.pfloggedin:hover .pfnavsub-menu{
				    display: inline-block;
				    min-width: 214px !important;
				    left: 0px;
			}
			.main-menu-item:hover .pfnavsub-menu{
			    display: inline-block;
			    min-width: 214px !important;
			    left: 0px;
			}
		</style>
		
        <div class="wpf-container">
        	<div id="pfmaincontent" class="wpf-container-inner">';

        echo comprimir_styles($HTML);

