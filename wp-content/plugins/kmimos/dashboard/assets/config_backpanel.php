<?php
// ========================================
// Paginas Permitiddas con este Estilos
// ========================================
$array_backpanel = [
	'bp_reservas', 
	'bp_conocer_cuidador', 
	'bp_usuarios', 
	'bp_suscriptores'
];
if( in_array($_GET['page'], $array_backpanel) ){ 

// ========================================
// BEGIN Style Dashboard ( New backpanel )
// ======================================== 
wp_enqueue_style( 'kmimos_style1', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/bootstrap/dist/css/bootstrap.css" );
wp_enqueue_style( 'kmimos_style2', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/font-awesome/css/font-awesome.min.css" );
wp_enqueue_style( 'kmimos_style3', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/nprogress/nprogress.css" );
wp_enqueue_style( 'kmimos_style4', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/iCheck/skins/flat/green.css" );
wp_enqueue_style( 'kmimos_style5', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" );
wp_enqueue_style( 'kmimos_style6', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" );
wp_enqueue_style( 'kmimos_style7', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" );
wp_enqueue_style( 'kmimos_style8', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" );
wp_enqueue_style( 'kmimos_style9', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" );
wp_enqueue_style( 'kmimos_style0', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/css/backpanel.css" );

// ========================================
// BEGIN JS Dashboard ( New backpanel )
// ========================================    
wp_enqueue_script( 'kmimos_script1', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/bootstrap/dist/js/bootstrap.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script2', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net/js/jquery.dataTables.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script3', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script4', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script5', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script6', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script7', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script8', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-buttons/js/buttons.print.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script9', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script10', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script11', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script12', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-scroller/js/dataTables.scroller.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script13', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js",
	array(), '1.0.0', true );
wp_enqueue_script( 'kmimos_script14', get_home_url()."/wp-content/plugins/kmimos/dashboard/assets/js/custom.js",
	array(), '1.0.0', true );

}