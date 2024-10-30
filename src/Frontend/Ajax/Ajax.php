<?php

namespace MIQID\Plugin\Core\Frontend\Ajax;

class Ajax {
	private static $instance;

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_ajax_nopriv_get_miqid_value', [ $this, 'handler' ] );
		add_action( 'wp_ajax_get_miqid_value', [ $this, 'handler' ] );
	}

	function handler() {
		$MIQIDFields = json_decode(
			wp_unslash( $_REQUEST['MIQIDFields'] ?? '' )
			, true );

		foreach ( $MIQIDFields as $field => $current_value ) {
			$current_value = trim( $current_value );
			$field_arr     = explode( '.', $field );
			/** @var string|null $field_arr_class */
			$field_arr_class = $field_arr[0] ?? null;
			/** @var string|null $field_arr_field */
			$field_arr_field = $field_arr[1] ?? null;
			$current_value   = preg_replace(
				'/\<img ([^\>]+)(?: \/)?\>/',
				'<img \1 />',
				$current_value );
			$shortcode       = sprintf( '[miqid-%1$s fields="%2$s"]',
				mb_strtolower( strtr( $field_arr_class, [ '\\' => '-' ] ) ),
				$field_arr_field );
			$shortcode_value = trim( do_shortcode( $shortcode ) );
			if ( $shortcode_value === $shortcode ) {
				error_log( sprintf( 'Wrong shortcode called: %s', $shortcode ) );
				$shortcode_value = '';
			}

			$MIQIDFields[ $field ] = $shortcode_value;

			if ( $current_value === $shortcode_value ) {
				unset( $MIQIDFields[ $field ] );
			}
		}

		wp_send_json( $MIQIDFields );
	}
}