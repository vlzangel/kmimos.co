<?php

// ***************************************
// Cargar listados de Reservas
// ***************************************
function getRazaDescripcion($id, $razas){
	$nombre = "[{$id}]";
	if($id > 0){
		if( !empty($razas) ){
			if(array_key_exists($id, $razas)){
				$nombre = $razas[$id];
			}
		}
	}
	return $nombre;
}

function get_razas(){
	global $wpdb;
	$sql = "SELECT * FROM razas ";
	$result = $wpdb->get_results($sql);
	$razas = [];
	foreach ($result as $raza) {
		$razas[$raza->id] = $raza->nombre;
	}
	return $razas;
}

function get_status($sts_reserva, $sts_pedido, $forma_pago=""){
	
	// Resultado
	$sts_corto = "---";
	$sts_largo = "Estatus Reserva: {$sts_reserva}  /  Estatus Pedido: {$sts_pedido}";
	//===============================================================
	// BEGIN PaymentMethod
	// Nota: Agregar la equivalencia de estatus de las pasarelas de pago
	//===============================================================
	$payment_method_cards = [ // pagos por TDC / TDD
		'openpay_cards'
	]; 
	$payment_method_store = [ // pagos por Tienda por conveniencia
		'openpay_stores'
	]; 
	//===============================================================
	// END PaymentMethod
	//===============================================================

	// Pedidos
	switch ($sts_reserva) {
		case 'unpaid':
			$sts_corto = "Pendiente";
			if( $sts_pedido == 'wc-on-hold'){
				if( in_array($forma_pago, $payment_method_cards) ){
					$sts_largo = "Pendiente por confirmar el cuidador"; // metodo de pago es por TDC / TDD ( parcial )
				}elseif( in_array($forma_pago, $payment_method_store) ){
					$sts_largo = "Pendiente por pago"; // Tienda por conv
				}else{
					$sts_largo = "Estatus Pedido: {$sts_pedido}"; 
				}
			}
			if( $sts_pedido == 'wc-pending'){
				$sts_largo = 'Verificar LOG OpenPay';
			}
			break;
		case 'confirmed':
			$sts_corto = 'Confirmado';
			$sts_largo = 'Confirmado';
			break;
		case 'paid':
			$sts_corto = 'Pagado';
			$sts_largo = 'Pagado';
			break;
		case 'cancelled':
			$sts_corto = 'Cancelado';
			$sts_largo = 'Cancelado';
			break;
	}

	return 	$result = [ 
		"reserva"  => $sts_reserva, 
		"pedido"   => $sts_pedido,
		"sts_corto"=> $sts_corto,
		"sts_largo"=> $sts_largo,
	];

}

function photo_exists($path=""){
	$photo = (file_exists('../'.$path) && !empty($path))? 
		get_option('siteurl').'/'.$path : 
		get_option('siteurl')."/wp-content/themes/pointfinder/images/noimg.png";
	return $photo;
}

function getEdad($fecha){
	$fecha = str_replace("/","-",$fecha);
	$hoy = date('Y/m/d');

	$diff = abs(strtotime($hoy) - strtotime($fecha) );
	$years = floor($diff / (365*60*60*24)); 
	$desc = " Años";
	$edad = $years;
	if($edad==0){
		$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
		$edad = $months;
		$desc = ($edad > 1) ? " Meses" : " Mes";
	}
	if($edad==0){
		$days  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		$edad = $days;
		$desc = " Días";
	}

	return $edad . $desc;
}


function getMascotas($user_id){
	if(!$user_id>0){ return []; }
	$result = [];
	$list = kmimos_get_my_pets($user_id);
	$pets = explode(",",$list['list']);

	foreach ($pets as $row) {
		$result[$row] = kmimos_get_pet_info($row);
	}
	return $result;
}

function getProduct( $num_reserva = 0 ){
	$services = [];

	global $wpdb;
	$sql = "	
		SELECT 
			i.meta_key as 'servicio',
			i.meta_value as 'descripcion'
		FROM wp_woocommerce_order_itemmeta as i
			-- Order_item_id
			LEFT JOIN wp_woocommerce_order_itemmeta as o ON ( o.meta_key = 'Reserva ID' and o.meta_value = $num_reserva )
			-- Reserva
			LEFT JOIN wp_posts as re ON re.ID = i.meta_value -- No. Reserva
		WHERE	
			i.meta_key like 'Servicios Adicionales%'
			and i.order_item_id = o.order_item_id
	";
	$services = $wpdb->get_results($sql);

	return $services;	
}

function getServices( $num_reserva = 0 ){
	$services = [];

	global $wpdb;
	$sql = "	
		SELECT 
			i.meta_key as 'servicio',
			i.meta_value as 'descripcion'
		FROM wp_woocommerce_order_itemmeta as i
			-- Order_item_id
			LEFT JOIN wp_woocommerce_order_itemmeta as o ON ( o.meta_key = 'Reserva ID' and o.meta_value = $num_reserva )
			-- Reserva
			LEFT JOIN wp_posts as re ON re.ID = i.meta_value -- No. Reserva
		WHERE	
			i.meta_key like 'Servicios Adicionales%'
			and i.order_item_id = o.order_item_id
	";
	$services = $wpdb->get_results($sql);

	return $services;
}

function getReservas($desde="", $hasta=""){

	$filtro_adicional = "";

	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional = " 
			AND DATE_FORMAT(re.post_date, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}else{
		$filtro_adicional = " AND MONTH(re.post_date) = MONTH(NOW()) AND YEAR(re.post_date) = YEAR(NOW()) ";
	}

	global $wpdb;
	$sql = "
		SELECT
			cl.ID as 'cliente_id',
			CONCAT(cln.meta_value,' ',cll.meta_value) as 'cliente_nombre',
			cu.meta_value as 'cuidador_nombre',
			i.meta_value as 'nro_reserva',
			pe.ID as 'nro_pedido',
			pr.post_title as 'producto_title',
			est.name as 'estado',
			mun.name as 'municipio',
			DATE_FORMAT(re.post_date,'%d-%m-%Y') as 'fecha_solicitud',
			DATE_FORMAT(fd.meta_value,'%d-%m-%Y') as  'desde',
			DATE_FORMAT(fh.meta_value,'%d-%m-%Y') as 'hasta',
			(du.meta_value -1) as  'nro_noches',
			(IFNULL(mpe.meta_value,0) + IFNULL(mme.meta_value,0) + IFNULL(mgr.meta_value,0) + IFNULL(mgi.meta_value,0)) as 'nro_mascotas',
			((du.meta_value -1) * ( IFNULL(mpe.meta_value,0) + IFNULL(mme.meta_value,0) + IFNULL(mgr.meta_value,0) + IFNULL(mgi.meta_value,0) )) as 'total_noches',
			fp.meta_value as 'forma_pago',
			pm.meta_value as 'metodo_pago_pk',
			re.post_status as 'estatus_reserva',
			pe.post_status as 'estatus_pago',
			IFNULL(bc.meta_value,0) as 'monto_total',
			IFNULL(ot.meta_value,0) as 'monto_pagado',
			IFNULL(rm.meta_value,0) as 'monto_remanente'
		FROM wp_woocommerce_order_itemmeta as i
			-- Reserva
			LEFT JOIN wp_posts as re ON re.ID = i.meta_value -- No. Reserva
			LEFT JOIN wp_postmeta as fd ON ( fd.post_id = re.ID and fd.meta_key = '_booking_start' ) -- Desde
			LEFT JOIN wp_postmeta as fh ON ( fh.post_id = re.ID and fh.meta_key = '_booking_end' ) -- Hasta
			LEFT JOIN wp_postmeta as bc ON ( bc.post_id = re.ID and bc.meta_key = '_booking_cost' ) -- Total a pagar
			-- Pedido
			LEFT JOIN wp_posts as pe ON pe.ID = re.post_parent -- No. Pedido
			LEFT JOIN wp_postmeta as es ON ( es.post_id = pe.ID and es.meta_key = '_shipping_state') -- Estado 
			LEFT JOIN wp_postmeta as cy ON ( cy.post_id = pe.ID and cy.meta_key = '_shipping_city') -- Ciudad
			LEFT JOIN wp_postmeta as pm ON ( pm.post_id = pe.ID and pm.meta_key = '_payment_method') 	-- Forma de Pago
			LEFT JOIN wp_postmeta as fp ON ( fp.post_id = pe.ID and fp.meta_key = '_payment_method_title') -- Forma de Pago
			LEFT JOIN wp_postmeta as ot ON ( ot.post_id = pe.ID and ot.meta_key = '_order_total') -- Total Orden
			LEFT JOIN wp_postmeta as rm ON ( rm.post_id = pe.ID and rm.meta_key = '_wc_deposits_remaining') -- Remanente
			-- Woocommerce
			LEFT JOIN wp_woocommerce_order_itemmeta as fe  ON (fe.order_item_id = i.order_item_id and fe.meta_key = 'Fecha de Reserva')
			LEFT JOIN wp_woocommerce_order_itemmeta as du  ON (du.order_item_id = i.order_item_id and du.meta_key = 'Duración')
			LEFT JOIN wp_woocommerce_order_itemmeta as mpe ON (mpe.order_item_id = i.order_item_id and mpe.meta_key = 'Mascotas Pequeños')
			LEFT JOIN wp_woocommerce_order_itemmeta as mme ON (mme.order_item_id = i.order_item_id and mme.meta_key = 'Mascotas Medianos')
			LEFT JOIN wp_woocommerce_order_itemmeta as mgr ON (mgr.order_item_id = i.order_item_id and mgr.meta_key = 'Mascotas Grandes')
			LEFT JOIN wp_woocommerce_order_itemmeta as mgi ON (mgi.order_item_id = i.order_item_id and mgi.meta_key = 'Mascotas Gigantes')
			LEFT JOIN wp_woocommerce_order_itemmeta as pr_id ON (pr_id.order_item_id = i.order_item_id and pr_id.meta_key = '_product_id') -- ID Producto
			-- Productos
			LEFT JOIN wp_posts as pr ON pr.ID = pr_id.meta_value -- Productos			
			-- LEFT JOIN wp_postmeta as pr_m ON pr_m.post_id = pr.ID
			-- Datos Cuidador
			LEFT JOIN cuidadores as us ON us.user_id = pr.post_author -- Datos del Cuidador
			LEFT JOIN ubicaciones as ub ON ub.cuidador = us.id -- Ubicacion del Cuidador
 			LEFT JOIN states as est ON est.id = REPLACE(ub.estado,'=','') -- Estado 
 			LEFT JOIN locations as mun ON mun.id = REPLACE(ub.municipios,'=','') -- Municipios 
			LEFT JOIN wp_woocommerce_order_itemmeta as cu  ON (cu.order_item_id = i.order_item_id and cu.meta_key = 'Ofrecido por') -- Nombre del cuidador
			-- Datos Cliente
			LEFT JOIN wp_users as cl ON cl.ID = re.post_author 
			LEFT JOIN wp_usermeta as cln ON ( cln.user_id = cl.ID  and cln.meta_key = 'first_name' )
			LEFT JOIN wp_usermeta as cll ON ( cll.user_id = cl.ID  and cll.meta_key = 'last_name' )
			LEFT JOIN wp_usermeta as cli ON ( cli.user_id = cl.ID  and cli.meta_key = 'name_photo' )
		WHERE 
			i.meta_key = 'Reserva ID'  {$filtro_adicional}
		ORDER BY i.meta_id DESC
	";

	$reservas = $wpdb->get_results($sql);
	return $reservas;
}

