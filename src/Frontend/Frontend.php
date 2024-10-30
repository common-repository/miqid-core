<?php


namespace MIQID\Plugin\Core\Frontend;

use Elementor\Plugin;
use MIQID\Plugin\Core\Frontend\Ajax\Ajax;
use MIQID\Plugin\Core\Frontend\Shortcode\Shortcode;
use MIQID\Plugin\Core\Util;

class Frontend {
	private static $instance;

	static function Instance(): Frontend {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, '_enqueue' ] );

		$settings = Util::get_miqid_core_settings();

		if ( $settings->is_user_switching_enabled() ) {
			new User_Switching();
		}

		Ajax::Instance();

		Shortcode::Instance();

		WP_Login::Instance();
	}

	function _enqueue() {
		$path        = sprintf( '%sassets', plugin_dir_path( dirname( __DIR__ ) ) );
		$url         = sprintf( '%sassets', plugin_dir_url( dirname( __DIR__ ) ) );
		$auto_loader = is_user_logged_in() ? 'auto_loader.js' : null;
		if ( class_exists( '\Elementor\Plugin' ) && Plugin::$instance->editor->is_edit_mode() ) {
			$auto_loader = null;
		}
		$auto_loader = null;
		foreach (
			array_filter( [
				$auto_loader,
			] ) as $file
		) {
			$handler   = Util::id( basename( $file, '.js' ) );
			$filemtime = filemtime( sprintf( '%s/js/%s', $path, $file ) );

			wp_enqueue_script(
				$handler,
				sprintf( '%s/js/%s', $url, $file ),
				[ 'jquery' ],
				date( 'Ymd-His', $filemtime ),
				true );
		}
	}
}