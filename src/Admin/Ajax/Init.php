<?php

namespace MIQID\Plugin\Core\Admin\Ajax;

use MIQID\Plugin\Core\Classes\Settings;

class Init {
	private static $instance;

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		//add_action( 'wp_ajax_fetch_miqid_core', [ $this, 'fetch_miqid_core' ] );
		add_action( 'wp_ajax_save_miqid', [ $this, 'save_miqid' ] );
	}

	function fetch_miqid_core() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 0, 400 );
		}

		wp_send_json( get_option( 'miqid-core' ) );
	}

	function save_miqid() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 0, 400 );
		}

		$miqid_core = new Settings( array_merge( get_option( 'miqid-core' ), $_POST['miqid-core'] ) );
		$miqid_css  = array_merge( get_option( 'miqid-css' ), $_POST['miqid-css'] );

		update_option( 'miqid-core', $miqid_core->jsonSerialize() );
		update_option( 'miqid-css', $miqid_css );
		wp_send_json( [ 'miqid-core' => $miqid_core, 'miqid-css' => $miqid_css ] );
	}
}