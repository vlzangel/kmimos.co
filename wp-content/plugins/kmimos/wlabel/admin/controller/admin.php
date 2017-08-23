<?php 

	// *****************************************
	//	Javascript
	// *****************************************
	wp_enqueue_script( 'script_wlabel_moment', get_home_url()."/panel/assets/vendor/moment/min/moment.min.js",array(), '1.0.0', true );
	wp_enqueue_script( 'script_wlabel_daterangepicker', get_home_url()."/panel/assets/vendor/bootstrap-daterangepicker/daterangepicker.js",array(), '1.0.0', true );
	wp_enqueue_script( 'script_wlabel', get_home_url()."/wp-content/plugins/kmimos/wlabel/admin/js/script.js",array(), '1.0.0', true );


	// *****************************************
	//	Style
	// *****************************************
	wp_enqueue_style( 'style_wlabel', get_home_url()."/wp-content/plugins/kmimos/wlabel/admin/css/style.css" );
	wp_enqueue_style( 'style_wlabel_daterangepicker', get_home_url()."/panel/assets/vendors/bootstrap-daterangepicker/daterangepicker.css" );

	
	// *****************************************
	//	Function
	// *****************************************

	function Wlabel_FetchAll($desde="", $hasta=""){

		$filtro_adicional = "";

		if( !empty($desde) && !empty($hasta) ){
			$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
			$filtro_adicional .= " 
				DATE_FORMAT(fecha, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
			";
		}else{
			$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
			$filtro_adicional .= " MONTH(fecha) = MONTH(NOW()) AND YEAR(fecha) = YEAR(NOW()) ";
		}

		$filtro_adicional = ( !empty($filtro_adicional) )? " WHERE {$filtro_adicional}" : $filtro_adicional ;

		$result = [];
		$sql = "
			SELECT *
			FROM wp_kmimos_wlabel as s
			{$filtro_adicional}
		";

		$result = get_fetch_assoc($sql);

		return $result;

	}

