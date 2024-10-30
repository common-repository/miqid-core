<?php

namespace MIQID\Plugin\Core\Admin;

use MIQID\Plugin\Core\Util;

class Init {
	private static $instance;

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		Ajax\Init::Instance();
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, '_enqueue' ] );
	}

	function menu() {
		add_menu_page(
			__( 'Dashboard' ),
			'MIQID',
			'manage_options',
			'miqid',
			[ Dashboard::Instance(), 'Page' ],
			sprintf( '%s/icon.png', Util::get_assets_images_url() ),
			10 );

		add_submenu_page(
			'miqid',
			_x( 'Dashboard', 'Menu', 'miqid-core' ),
			_x( 'Dashboard', 'Menu', 'miqid-core' ),
			'manage_options',
			'miqid',
			[ Dashboard::Instance(), 'Page' ],
			10 );

		add_submenu_page(
			'miqid',
			_x( 'Core', 'Menu', 'miqid-core' ),
			_x( 'Core', 'Menu', 'miqid-core' ),
			'manage_options',
			'Core',
			[ Core::Instance(), 'Page' ],
			10 );
	}

	function _enqueue() {
		wp_enqueue_style(
			'miqid-admin',
			sprintf( '%s/%s', Util::get_assets_css_url(), 'admin.css' ),
			[],
			date( 'Ymd-His', filemtime( sprintf( '%s/%s', Util::get_assets_css_path(), 'admin.css' ) ) ) );

		wp_enqueue_script(
			'miqid-admin',
			sprintf( '%s/%s', Util::get_assets_js_url(), 'admin.js' ),
			[ 'jquery' ],
			date( 'Ymd-His', filemtime( sprintf( '%s/%s', Util::get_assets_js_path(), 'admin.js' ) ) ),
			true );

		wp_localize_script( 'miqid-admin', 'miqid', [ 'ajax' => admin_url( 'admin-ajax.php' ) ] );
	}

}