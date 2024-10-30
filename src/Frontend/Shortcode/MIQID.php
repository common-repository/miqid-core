<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode;

use MIQID\Plugin\Core\Util;

class MIQID {
	private static $instance;

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_shortcode( 'miqid', [ $this, '_miqid' ] );
	}

	function _miqid( $atts ): string {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts( [
			'profile'   => false,
			'address'   => false,
			'separator' => ' ',
		], $atts );

		$output = [];
		foreach ( [ 'profile', 'address' ] as $class ) {
			$atts[ $class ] = array_filter( explode( ',', $atts[ $class ] ) );
			foreach ( $atts[ $class ] as $att ) {
				$att            = explode( '|', $att );
				$key            = Util::snake_case( sprintf( 'miqid_%s_%s', $class, array_shift( $att ) ) );
				$output[ $key ] = sprintf( '%s%s', apply_filters( $key, '' ), array_shift( $att ) );
			}
		}

		return implode( $atts['separator'], $output );
	}

}