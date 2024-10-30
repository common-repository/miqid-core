<?php
/**
 * Plugin Name:       MIQID-Core
 * Description:       MIQID-Core handle the basics.
 * Version:           1.9.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            MIQ ApS
 * Author URI:        https://miqid.com/
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       miqid-core
 */

use MIQID\Plugin\Core\Init;

require_once __DIR__ . '/vendor/autoload.php';

if ( ! defined( 'WPINC' ) ) {
	die();
}

function load_miqid_core_language() {
	load_plugin_textdomain( 'miqid-core', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'load_miqid_core_language' );

$init = new Init();