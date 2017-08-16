<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'RWMB_Map_Field' ) )
{
	class RWMB_Map_Field extends RWMB_Field
	{
		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		static function admin_enqueue_scripts()
		{
			wp_enqueue_script( 'admin-google-api', 'https://maps.google.com/maps/api/js', array(), '', true );
			wp_enqueue_script( 'rwmb-map', RWMB_JS_URL . 'map.js', array( 'jquery', 'jquery-ui-autocomplete', 'admin-google-api' ), RWMB_VER, true );
		}

		/**
		 * Get field HTML
		 *
		 * @param mixed  $meta
		 * @param array  $field
		 *
		 * @return string
		 */
		static function html( $meta, $field )
		{
			$address = isset( $field['address_field'] ) ? $field['address_field'] : false;

			$html = '<div class="rwmb-map-field">';

			$html .= sprintf(
				'<div class="rwmb-map-canvas" style="%s"%s></div>',
				isset( $field['style'] ) ? $field['style'] : '',
				isset( $field['std'] ) ? " data-default-loc=\"{$field['std']}\"" : ''
				
			);

			if ( $address )
			{
				$html .= sprintf(
					'<button class="button rwmb-map-goto-address-button" value="%s">%s</button>',
					is_array( $address ) ? implode( ',', $address ) : $address,
					esc_html__( 'Find Address', 'pointfindert2d' )
				);
			}
			$html .= "<div class='rwmb-field rwmb-heading-wrapper'><h4>".esc_html__('(Optional) Manual lat,lng coordinate. If dont want to select point from above map then you can enter custom position coordinates from here. Ex: 40.34654412118006,-3.7470245361328125','pointfindert2d')."</h4></div>";
			$html .= sprintf(
				"<input type='text' name='%s' class='rwmb-map-coordinate' value='%s'>",
				$field['field_name'],
				$meta
			);

			$html .= '</div>';

			return $html;
		}
	}
}