<?php 

	if( $post->post_name == "carro" ){

		if( isset($_GET['removed_item']) ){

			global $wpdb;
			$D = $wpdb;
			$id_user = get_current_user_id();

			$session = $D->get_var("SELECT session_value FROM wp_woocommerce_sessions WHERE session_key = ".$id_user );
			$carrito = unserialize($session);

			$removido = unserialize($carrito['removed_cart_contents']);

			$producto = 0;
			foreach ($removido as $key => $value) {
				$producto = $value['product_id'];
			}

			$url = $D->get_var("SELECT post_name FROM wp_posts WHERE ID = ".$producto );

			$url = get_home_url()."/producto/".$url."/";

			header("location: ".$url);

		}

	}

	if( isset($_GET['init'])){
		global $wpdb;

		$sql = "
			SELECT 
				u.user_email AS mail,
				m.meta_value AS clave
			FROM 
				wp_users AS u
			INNER JOIN wp_usermeta AS m ON (m.user_id = u.ID)
			WHERE 
				u.ID = '{$_GET['init']}' AND 
				m.meta_key = 'user_pass'
			GROUP BY 
				u.ID
		";
		$data = $wpdb->get_row($sql);

		$info = array();
	    $info['user_login']     = sanitize_user($data->mail, true);
	    $info['user_password']  = sanitize_text_field($data->clave);

	    $user_signon = wp_signon( $info, true );
	    wp_set_auth_cookie($user_signon->ID);

	    header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
	}

	if( isset($_GET['i'])){
		global $current_user;

        $_SESSION['id_admin'] = $current_user->ID;
        $_SESSION['admin_sub_login'] = "YES";

		global $wpdb;

		$sql = "SELECT ID FROM wp_users WHERE md5(ID) = '{$_GET['i']}'";
		// $sql = "SELECT ID FROM wp_users WHERE ID = '{$_GET['i']}'";
		$data = $wpdb->get_row($sql);

	    $user_id = $data->ID;
		$user = get_user_by( 'id', $user_id ); 
		if( $user ) {
		    wp_set_current_user( $user_id, $user->user_login );
		    wp_set_auth_cookie( $user_id );
		}

		if( isset($_GET['admin']) ){
	        $_SESSION['id_admin'] 		 = "";
	        $_SESSION['admin_sub_login'] = "";
	   		header("location: ".get_home_url()."/wp-admin/admin.php?page=bp_clientes");
		}else{
	   		header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
		}
	}

?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">		
		<?php
			if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
        		header('X-UA-Compatible: IE=edge,chrome=1');
        	}
		?>
				
		<?php
			if ( is_page() ){
				global $post;
				$descripcion = get_post_meta($post->ID, 'kmimos_descripcion', true);

        		if( $descripcion != ""){
        			echo "<meta name='description' content='{$descripcion}'>";
        		}else{
        			?> <meta name="description" content="<?php esc_html(bloginfo('description')); ?>"> <?php
        		}
        	}else{
        		?> <meta name="description" content="<?php esc_html(bloginfo('description')); ?>"> <?php
        	}
		?>
		<!--[if lt IE 9]>
		<script src="<?php echo get_home_url()."/wp-content/themes/pointfinder"; ?>/js/html5shiv.js"></script>
		<![endif]-->
		<?php

		$general_responsive = esc_attr(PFSAIssetControl('general_responsive','','1'));
		if($general_responsive == 1){
			$as_mobile_zoom = PFASSIssetControl('as_mobile_zoom','','1');
			if ($as_mobile_zoom == 1) {
				echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">';
			} else {
				echo '<meta name="viewport" content="width=device-width">';
			}
		}
		
		$iostouchicon_1 = esc_url(PFSAIssetControl('setup17_logosettings_sitefavicon','url',''));
		if($iostouchicon_1){
			echo '<link rel="shortcut icon" href="'.$iostouchicon_1.'" type="image/x-icon">';
			echo '<link rel="icon" href="'.$iostouchicon_1.'" type="image/x-icon">';
		}

		/* Start: Transparent Header Addon */
			global $post;
			$transparent_header_text = $style_text = $logocsstext = "";
			if (isset($post->ID) && !is_search()) {
				$transparent_header = get_post_meta( $post->ID, 'webbupointfinder_page_transparent', true );
				if (!empty($transparent_header)) {
					$transparent_header_text = " pftransparenthead";

					$menulinecolor = get_post_meta( $post->ID, 'webbupointfinder_page_menulinecolor', true );
					$menucolor = get_post_meta( $post->ID, 'webbupointfinder_page_menucolor', true );
					$menubg = get_post_meta( $post->ID, 'webbupointfinder_page_headerbarsettings_bgcolor', true );
					$menubg_sticky = get_post_meta( $post->ID, 'webbupointfinder_page_headerbarsettings_bgcolor2', true );
					$menutextb = get_post_meta( $post->ID, 'webbupointfinder_page_menutextsize', true );
					$logoadditional = get_post_meta( $post->ID, 'webbupointfinder_page_logoadditional', true );

					if (!empty($logoadditional)) {

						/* Logo for this bar */
						$setup18_headerbarsettings_padding = PFSAIssetControl('setup18_headerbarsettings_padding','margin-top','30');
						$setup18_headerbarsettings_padding_number = str_replace('px', '', $setup18_headerbarsettings_padding);
						
						$setup17_logosettings_sitelogo = PFSAIssetControl('setup17_logosettings_sitelogo2','','');
						if (!is_array($setup17_logosettings_sitelogo)) {
							$setup17_logosettings_sitelogo = array('url'=>'','width'=>188,'height'=>30);
						} 
						$setup17_logosettings_sitelogo_height = (!empty($setup17_logosettings_sitelogo["height"]))?$setup17_logosettings_sitelogo["height"]:30;
						$setup17_logosettings_sitelogo_height_number = str_replace('px', '', $setup17_logosettings_sitelogo_height);
						$setup17_logosettings_sitelogo_width = (!empty($setup17_logosettings_sitelogo["width"]))?$setup17_logosettings_sitelogo["width"]:188;
						$setup17_logosettings_sitelogo_width_number = str_replace('px', '', $setup17_logosettings_sitelogo_width);

						$setup17_logosettings_sitelogo2x = PFSAIssetControl('setup17_logosettings_sitelogo22x','','');
						if (!is_array($setup17_logosettings_sitelogo2x)) {
							$setup17_logosettings_sitelogo2x = array('url'=>'','width'=>188,'height'=>30);
						}
						$setup17_logosettings_sitelogo2x_height = (!empty($setup17_logosettings_sitelogo2x["height"]))?$setup17_logosettings_sitelogo2x["height"]:30;
						$setup17_logosettings_sitelogo2x_height_number = str_replace('px', '', $setup17_logosettings_sitelogo2x_height);
						$setup17_logosettings_sitelogo2x_width = (!empty($setup17_logosettings_sitelogo2x["width"]))?$setup17_logosettings_sitelogo2x["width"]:188;
						$setup17_logosettings_sitelogo2x_width_number = str_replace('px', '', $setup17_logosettings_sitelogo2x_width);

						$pfpadding_half = $setup18_headerbarsettings_padding_number / 2;

						/*Logo Settings*/
							$logocsstext .= '.wpf-header.pftransparenthead .pf-logo-container{margin:'.$setup18_headerbarsettings_padding.' 0;height: '.$setup17_logosettings_sitelogo_height_number.'px;}';
							$logocsstext .= '.wpf-header.pftransparenthead .pf-logo-container{background-image:url('.$setup17_logosettings_sitelogo["url"].');background-size:'.$setup17_logosettings_sitelogo_width_number.'px '.$setup17_logosettings_sitelogo_height_number.'px;width: '.$setup17_logosettings_sitelogo_width_number.'px;}';

							$logocsstext .= '.wpf-header.pftransparenthead.pfshrink .pf-logo-container{height: '.($setup17_logosettings_sitelogo_height_number/2).'px;margin:'.$pfpadding_half.'px 0;}';
							$logocsstext .= '.wpf-header.pftransparenthead.pfshrink .pf-logo-container{background-size:'.($setup17_logosettings_sitelogo_width_number/2).'px '.($setup17_logosettings_sitelogo_height_number/2).'px;width: '.($setup17_logosettings_sitelogo_width_number/2).'px;}';

							$logocsstext .= '@media (max-width: 568px) {.wpf-header.pftransparenthead .pf-logo-container{height: '.($setup17_logosettings_sitelogo_height_number/2).'px;margin:'.$pfpadding_half.'px 0;}.wpf-header.pftransparenthead .pf-logo-container{background-size:'.($setup17_logosettings_sitelogo_width_number/2).'px '.($setup17_logosettings_sitelogo_height_number/2).'px;width: '.($setup17_logosettings_sitelogo_width_number/2).'px;}}';



						/* Retina Logo Settings */
							if(is_array($setup17_logosettings_sitelogo2x)){
								if(count($setup17_logosettings_sitelogo2x)>0){
									$logocsstext .= '@media only screen and (-webkit-min-device-pixel-ratio: 1.5),(min-resolution: 144dpi){.wpf-header.pftransparenthead .pf-logo-container{background-image:url('.$setup17_logosettings_sitelogo2x["url"].');background-size:'.($setup17_logosettings_sitelogo2x_width_number/2).'px '.($setup17_logosettings_sitelogo2x_height_number/2).'px;width: '.($setup17_logosettings_sitelogo2x_width_number/2).'px;}}';
								}
							}
					}



					$style_text = "<style>";
					
					$style_text .= "@media (min-width: 992px) {";
					$style_text .= $logocsstext;
					if (!empty($menutextb)) {
						$style_text .= ".wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li a,.wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li.selected > a{font-weight:bold;}";
					}

					if (!empty($menucolor)) {
						$style_text .= ".wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li a,.wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li.selected > a{color:".$menucolor['regular'].";}";
	                    $style_text .= ".wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li a:hover,.wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li.selected > a:hover{color:".$menucolor['hover'].";}";
					}

					if (!empty($menulinecolor)) {
						$style_text .= ".wpf-header.pftransparenthead #pf-primary-nav li.current_page_item > a,.wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li > a:hover{border-bottom: 2px solid ".$menulinecolor.";}";
						$style_text .= ".wpf-header.pftransparenthead #pf-primary-nav .pf-megamenu-main.current_page_item > a,.wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu .pf-megamenu-main > a:hover{border-bottom: 0;}";
						$style_text .= ".wpf-header.pftransparenthead #pf-primary-nav li.current_page_item.selected > a,.wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li.selected > a:hover{border-bottom: 0}";
						$style_text .= ".wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu li.selected > .pfnavsub-menu{border-top: 2px solid ".$menulinecolor.";}";
						$style_text .= ".wpf-header.pftransparenthead #pf-primary-nav .pfnavmenu .pfnav-megasubmenu li.selected > .pfnavsub-menu{border-top:0;}";
					}

					if (!empty($menubg)) {
						$style_text .= ".wpf-header.pftransparenthead{background:".$menubg['color'].";background:".$menubg['rgba'].";}";
					}
					if (!empty($menubg_sticky)) {
						$style_text .= ".wpf-header.pftransparenthead.pfshrink{background:".$menubg_sticky['color'].";background:".$menubg_sticky['rgba'].";}";
					}

					$style_text .= "}";
					$style_text .= "</style>";
					echo $style_text;
				}
			}
		/* End: Transparent Header Addon */

		wp_enqueue_style( 'vlz', get_home_url()."/wp-content/themes/pointfinder/css/vlz.css?v=1.0.0" );

		wp_head();

		echo "
			<script type='text/javascript'>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  	})(window,document,'script','".get_home_url().'/wp-content/plugins/kmimos/javascript/analytics.js'."','ga');

				ga('create', 'UA-56422840-1', 'auto');
				ga('send', 'pageview');
			</script>";
		?>

		<script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
			0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
			for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"<?php echo get_home_url().'/wp-content/plugins/kmimos/javascript/mixpanel-2-latest.min.js'; ?>" : "<?php echo get_home_url().'/wp-content/plugins/kmimos/javascript/mixpanel-2-latest.min.js'; ?>";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
			mixpanel.init("972817bb3a7c91a4b95c1641495dfeb7");

		</script><!-- end Mixpanel -->

	</head>
	<body <?php body_class(); ?> >
	<?php
	$general_rtlsupport = PFSAIssetControl('general_rtlsupport','','0');
	$pflogintext = "";
    ?>
		<?php if (!is_page_template('pf-empty-page.php' )  && !is_page_template('terms-conditions.php' )) {?>
		<div id="pf-loading-dialog" class="pftsrwcontainer-overlay"></div>
        <header class="wpf-header hidden-print<?php echo $transparent_header_text;?>" id="pfheadernav">
        	<?php
        	$as_topline_status = PFASSIssetControl('as_topline_status','','1');
        	if ($as_topline_status == 1) {
        	?>
        	<div class="pftopline wpf-transition-all">
        		<div class="pf-container">
					<div class="pf-row">
						<div class="col-lg-12 col-md-12">
							<?php 
							
        					$setup19_socialiconsbarsettings_main = esc_attr(PFSAIssetControl('setup19_socialiconsbarsettings_main','','0'));

							$setup4_membersettings_frontend = esc_attr(PFSAIssetControl('setup4_membersettings_frontend','','0'));
							$setup4_membersettings_loginregister = esc_attr(PFSAIssetControl('setup4_membersettings_loginregister','','0'));

							$setup19_socialiconsbarsettings_theme = esc_attr(PFSAIssetControl('setup19_socialiconsbarsettings_theme','',''));
							

							$setup19_socialiconsbarsettings_envelope = esc_html(PFSAIssetControl('setup19_socialiconsbarsettings_envelope','',''));
							$setup19_socialiconsbarsettings_envelope_link = esc_html(PFSAIssetControl('setup19_socialiconsbarsettings_envelope_link','',''));
							$setup19_socialiconsbarsettings_phone = esc_html(PFSAIssetControl('setup19_socialiconsbarsettings_phone','',''));
							$setup19_socialiconsbarsettings_phone_link = esc_html(PFSAIssetControl('setup19_socialiconsbarsettings_phone_link','',''));
						
							?>
							<div class="wpf-toplinewrapper">
								<?php
								if($setup19_socialiconsbarsettings_main == 1){
								?>
								<div class="pf-toplinks-left clearfix">
									<ul class="pf-sociallinks">
										<?php
											$pf_socialname_arr = array("facebook","twitter","linkedin","google-plus","pinterest","dribbble","dropbox","flickr","github","instagram","rss","skype","tumblr","vk","youtube");
											$output = '';
											$output_num = 1;
											foreach ($pf_socialname_arr as $socialname) {
												if($socialname != ''){
													$social_admin_var = esc_url(PFSAIssetControl('setup19_socialiconsbarsettings_'.$socialname,'',''));
													if($social_admin_var != '' && $output_num < 8){
														$output .= '<li class="pf-sociallinks-item '.$socialname.'  wpf-transition-all"><a href="'.$social_admin_var.'" target="_blank"><i class="pfadmicon-'.pfsocialtoicon($socialname).'"></i></a></li>';
														$output_num++;
													}
												}
											}
											echo $output;							
										?>
										
									<?php if ($setup19_socialiconsbarsettings_phone != '') {?>
									<li class="pf-sociallinks-item pf-infolinks-item envelope  wpf-transition-all">
										<a href="<?php echo $setup19_socialiconsbarsettings_phone_link;?>"><i class="pfadmicon-glyph-765"></i> <span class="pf-infolink-item-text"><?php echo $setup19_socialiconsbarsettings_phone;?></span></a>
									</li>
									<?php } ?>

									<?php if ($setup19_socialiconsbarsettings_envelope != '') {?>
									<li class="pf-sociallinks-item pf-infolinks-item pflast envelope  wpf-transition-all">
										<a href="<?php echo $setup19_socialiconsbarsettings_envelope_link;?>"><i class="pfadmicon-glyph-823"></i><span class="pf-infolink-item-text"><?php echo $setup19_socialiconsbarsettings_envelope;?></span></a>
									</li>
									<?php } ?>
									</ul>
								
								</div>
								<?php }?>
								<?php
								//if($setup4_membersettings_loginregister == 1){
									if(is_user_logged_in()){$pflogintext = " pfloggedin";}
								?>
								<div class="pf-toplinks-right clearfix">
									<nav id="pf-topprimary-nav" class="pf-topprimary-nav pf-nav-dropdown clearfix hidden-sm hidden-xs">
										<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu ">
											<li class="pf-my-account pfloggedin">
												<?php 
												if(function_exists('icl_object_id')) {
													$pf_languages = icl_get_languages('skip_missing=1&orderby=KEY&order=DIR'); 
													foreach ($pf_languages as $pf_languagex) {
														if (PF_current_language() == $pf_languagex['language_code']) {
															echo '<a href="#" class="pf_language_selects"><img src="'.$pf_languagex['country_flag_url'].'"/>'.$pf_languagex['translated_name'].'</a>';
														}
													}
													echo '<ul class="pfnavsub-menu sub-menu menu-odd  menu-depth-1 hidden-xs hidden-sm">';
														foreach ($pf_languages as $pf_language) {
															echo '<li>';
																echo '<a href="'.esc_url($pf_language['url']).'" class="pf_language_selects"><img src="'.esc_url($pf_language['country_flag_url']).'"/>'.esc_html($pf_language['translated_name']).'</a>';
															echo '</li>';
														}
													echo '</ul>';
												}
												?>
												
											</li>
											<?php 
											if ( !is_user_logged_in() ){
											?>
											<li class="pf-login-register<?php echo $pflogintext?>" id="pf-login-trigger-button"><a href="#"><i class="pfadmicon-glyph-584"></i> <?php  echo esc_html__('Login','pointfindert2d')?></a></li>
											<li class="pf-login-register<?php echo $pflogintext?>"><a href="<?php echo get_home_url()."/registrar/"; ?>"><i class="pfadmicon-glyph-365"></i> <?php  echo esc_html__('Register','pointfindert2d')?></a></li>
											<li class="pf-login-register<?php echo $pflogintext?>" id="pf-lp-trigger-button"><a href="#"><i class="pfadmicon-glyph-889"></i> <?php  echo esc_html__('Forgot Password','pointfindert2d')?></a></li>
											<?php 
											}else {
												global $current_user;
											?>
											<li class="pf-my-account pfloggedin" style="min-width: 200px; text-align: right;">
												<a href="#">
												<i class="pfadmicon-glyph-632"></i> 
												<?php  echo $current_user->nickname?>
												</a>
												<ul class="pfnavsub-menu sub-menu menu-odd  menu-depth-1 hidden-xs hidden-sm">
													<?php 
													global $wp;
													//$current_url = add_query_arg( $wp->query_string, '', esc_url(home_url( $wp->request )) ); 
													
													$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');
													$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
													$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
													$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
													$setup_invoices_sh = PFASSIssetControl('setup_invoices_sh','','1');

													$setup29_dashboard_contents_my_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_my_page_menuname','',''));
//													$setup29_dashboard_contents_inv_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_inv_page_menuname','',''));
													$setup29_dashboard_contents_inv_page_menuname =__('Mis Compras','pointfindert2d');
													$setup29_dashboard_contents_favs_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_favs_page_menuname','',''));
													$setup29_dashboard_contents_profile_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_profile_page_menuname','',''));
													$setup29_dashboard_contents_submit_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','',''));
													$setup29_dashboard_contents_rev_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_rev_page_menuname','',''));

													$pfmenu_output = '';
													$pfmenu_perout = PFPermalinkCheck();

													$pfmenu_output .= '<li ><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile"><i class="pfadmicon-glyph-406"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
													
													$pfmenu_output .= ($setup4_membersettings_frontend == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypets"><i class="pfadmicon-glyph-460"></i> '. $setup29_dashboard_contents_my_page_menuname.'</a></li>' : '' ;
													$pfmenu_output .= ($setup4_membersettings_favorites == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites"><i class="pfadmicon-glyph-375"></i> '. $setup29_dashboard_contents_favs_page_menuname.'</a></li>' : '';
													$pfmenu_output .= ($setup4_membersettings_frontend == 1 && $setup_invoices_sh == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=invoices"><i class="pfadmicon-glyph-33"></i> Historial </a></li>' : '' ;
													$pfmenu_output .= ($setup11_reviewsystem_check == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=reviews"><i class="pfadmicon-glyph-377"></i> '. $setup29_dashboard_contents_rev_page_menuname.'</a></li>' : '';
													

													$EC = is_cuidador();
													if( $EC == 1 ){
														$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myshop"><i class="pfadmicon-glyph-664"></i> Descripción del cuidador</a></li>';
														$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myservices"><i class="pfadmicon-glyph-453"></i> Mis Servicios</a></li>';
														$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypictures"><i class="pfadmicon-glyph-82"></i> Mis Fotos</a></li>';
														$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mybookings"><i class="pfadmicon-glyph-33""></i> Mis Reservas</a></li>';
													}

													$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=caregiver"><i class="pfadmicon-glyph-33"></i> Mis Solicitudes</a></li>';
													$pfmenu_output .= '<li><a href="'.wp_logout_url( home_url() ).'"><i class="pfadmicon-glyph-476"></i> '. esc_html__('Logout','pointfindert2d').'</a></li>';
													echo $pfmenu_output;
													?>
												</ul>
												
											</li>
											<?php } ?>
										</ul>
									</nav>
								</div>
								<?php //}?>
							</div>
						</div>
					</div>
				</div>
        	</div>
            <?php 
            }
            ?>
            <div class="wpf-navwrapper">
            
            <a  href="<?php echo get_home_url();?>/" class="jj-patica-menu"><img src="https://www.kmimos.com.mx/wp-content/uploads/2016/02/patita.png"></a>  
            
	            <?php 
				$pf_navmenu = wp_nav_menu(array(
				        'echo' => FALSE,
				        'theme_location'  => 'pointfinder-main-menu',
				        'fallback_cb' => '__return_false'
				    	)
				);

				if ( ! empty ( $pf_navmenu ) ){
				?>
					<a id="pf-primary-nav-button" title="<?php echo esc_html__('Menu','pointfindert2d');?>"><i class="pfadmicon-glyph-500"></i></a>
				<?php }?>
			
				<?php 
				if (!isset($setup4_membersettings_loginregister)) {
					$setup4_membersettings_loginregister = esc_attr(PFSAIssetControl('setup4_membersettings_loginregister','','0'));
				}
				if ($setup4_membersettings_loginregister == 1) {
				?>
				<a id="pf-topprimary-nav-button" title="<?php echo esc_html__('User Menu','pointfindert2d');?>"><i class="pfadmicon-glyph-632"></i></a>
				<a id="pf-topprimary-nav-button2" title="<?php echo esc_html__('User Menu','pointfindert2d');?>"><i class="pfadmicon-glyph-787"></i></a>
				<?php 
				}
				?>
				<a id="pf-primary-search-button" title="<?php echo esc_html__('Search','pointfindert2d');?>"><i class="pfadmicon-glyph-627"></i></a>
				<div class="pf-container pf-megamenu-container">
					<div class="pf-row">
						<?php
						function pointfinder_logocolumn_get(){
						?>
						<div class="col-lg-2 col-md-2">
							<a class="pf-logo-container" href="<?php echo esc_url(home_url());?>"></a>
						</div>
						<?php	
						}
						?>

						<?php
						function pointfinder_menucolumn_get(){
						?>
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
										    height: auto;
										    line-height: 18px;
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
									<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu">
										<?php 
											pointfinder_navigation_menu();
										
											$EC = is_cuidador();
											if( $EC != 1 ){
												echo "
													<li>
														<a class='ser_cuidador' href='".get_home_url()."/quiero-ser-cuidador-certificado-de-perros/'>
															<span></span>
															<div class='theme_button button_header'>Quiero ser cuidador</div>
														</a>
													<li>
												";
												
											}
										?>
												
									</ul>
								</nav>	

								<nav id="pf-topprimary-navmobi" class="pf-topprimary-nav pf-nav-dropdown clearfix">
									<ul class="pf-nav-dropdown  pfnavmenu pf-topnavmenu pf-nav-dropdownmobi">
										<?php 
										if ( !is_user_logged_in() ){
										?>
											<li class="pf-login-register<?php echo $pflogintext; ?>" id="pf-login-trigger-button-mobi"><a href="#"><i class="pfadmicon-glyph-584"></i> <?php  echo esc_html__('Login','pointfindert2d')?></a></li>
											<li class="pf-login-register<?php echo $pflogintext; ?>"><a href="<?php echo get_home_url()."/registrar/"; ?>"><i class="pfadmicon-glyph-365"></i> <?php  echo esc_html__('Register','pointfindert2d')?></a></li>
											<li class="pf-login-register<?php echo $pflogintext; ?>" id="pf-lp-trigger-button-mobi"><a href="#"><i class="pfadmicon-glyph-889"></i><?php  echo esc_html__('Forgot Password','pointfindert2d')?></a></li>
										<?php 
										}else {
										
										global $current_user;
										global $wp;
										//$current_url = add_query_arg( $wp->query_string, '', esc_url(home_url( $wp->request )) ); 
										
										$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');
										$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
										$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
										$setup4_membersettings_dashboard_link = esc_url(get_permalink($setup4_membersettings_dashboard));


										$setup29_dashboard_contents_my_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_my_page_menuname','',''));
//										$setup29_dashboard_contents_inv_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_inv_page_menuname','',''));
										$setup29_dashboard_contents_inv_page_menuname = __('Mis Compras','pointfindert2d');
										$setup29_dashboard_contents_favs_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_favs_page_menuname','',''));
										$setup29_dashboard_contents_profile_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_profile_page_menuname','',''));
										$setup29_dashboard_contents_submit_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','',''));
										$setup29_dashboard_contents_rev_page_menuname = esc_html(PFSAIssetControl('setup29_dashboard_contents_rev_page_menuname','',''));
										$setup4_membersettings_frontend = esc_html(PFSAIssetControl('setup4_membersettings_frontend','','0'));
										$setup_invoices_sh = PFASSIssetControl('setup_invoices_sh','','1');
										$pfmenu_output = '';
										$pfmenu_perout = PFPermalinkCheck();

										$pfmenu_output .= '<li ><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile"><i class="pfadmicon-glyph-406"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
										// $pfmenu_output .= ($setup4_membersettings_frontend == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem"><i class="pfadmicon-glyph-475"></i> '. $setup29_dashboard_contents_submit_page_menuname.'</a></li>' : '' ;
										$pfmenu_output .= ($setup4_membersettings_frontend == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypets"><i class="pfadmicon-glyph-460"></i> '. $setup29_dashboard_contents_my_page_menuname.'</a></li>' : '' ;
										$pfmenu_output .= ($setup4_membersettings_favorites == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites"><i class="pfadmicon-glyph-375"></i> '. $setup29_dashboard_contents_favs_page_menuname.'</a></li>' : '';
										$pfmenu_output .= ($setup4_membersettings_frontend == 1 && $setup_invoices_sh == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=invoices"><i class="pfadmicon-glyph-33"></i> Historial </a></li>' : '' ;
										$pfmenu_output .= ($setup11_reviewsystem_check == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=reviews"><i class="pfadmicon-glyph-377"></i> '. $setup29_dashboard_contents_rev_page_menuname.'</a></li>' : '';
										
										$EC = is_cuidador();
										if( $EC == 1 ){
											$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myshop"><i class="pfadmicon-glyph-664"></i> Descripción del cuidador</a></li>';
											$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myservices"><i class="pfadmicon-glyph-453"></i> Mis Servicios</a></li>';
											$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypictures"><i class="pfadmicon-glyph-82"></i> Mis Fotos</a></li>';
											$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mybookings"><i class="pfadmicon-glyph-33"></i> Mis Reservas</a></li>';
										}


											$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=caregiver"><i class="pfadmicon-glyph-33"></i> Mis Solicitudes</a></li>';
											$pfmenu_output .= '<li><a href="'.wp_logout_url( home_url() ).'"><i class="pfadmicon-glyph-476"></i> '. esc_html__('Logout','pointfindert2d').'</a></li>';
										echo $pfmenu_output;
										
										} 
										?>
									</ul>
								</nav>

								<nav id="pf-topprimary-navmobi2" class="pf-topprimary-nav pf-nav-dropdown clearfix">
									<ul class="pf-nav-dropdown  pfnavmenu pf-topnavmenu pf-nav-dropdownmobi">
										<?php 
										if(function_exists('icl_object_id')) {
											$pf_languages = icl_get_languages('skip_missing=1&orderby=KEY&order=DIR'); 
											foreach ($pf_languages as $pf_language) {
													echo '<li>';
														echo '<a href="'.esc_url($pf_language['url']).'" class="pf_language_selects"><img src="'.esc_url($pf_language['country_flag_url']).'"/>'.esc_html($pf_language['translated_name']).'</a>';
													echo '</li>';
											}
										}
										?>
									</ul>
								</nav>

								<script type="text/javascript">
									jQuery("#nav-menu-item-1617 ul a").attr("target", "_blank");
								</script>
					
							</div>
						</div>
						<?php	
						}
						?>
						<?php
						if ($general_rtlsupport == 1) {
							pointfinder_menucolumn_get();
							pointfinder_logocolumn_get();
						}else{
							pointfinder_logocolumn_get();
							pointfinder_menucolumn_get();
						}
						?>
					</div>
				</div>
			</div>

        </header>
		
        <div class="wpf-container<?php echo $transparent_header_text;?>">
        	<div id="pfmaincontent" class="wpf-container-inner">
        <?php }?>