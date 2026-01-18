<?php
/**
 * Plugin Name: BuddyActivist User Location
 * Description: Geolocation for Buddypress & Buddyboss Users.
 * Author: BuddyActivist
 * Version: 1.0.0
 * Text Domain: buddyactivist-user-location
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'BAUL_VERSION', '1.0.0' );
define( 'BAUL_PLUGIN_FILE', __FILE__ );
define( 'BAUL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BAUL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once BAUL_PLUGIN_DIR . 'includes/class-baul-loader.php';

function baul_init() {
    $loader = new BAUL_Loader();
    $loader->init();
}
add_action( 'plugins_loaded', 'baul_init' );

function baul_load_textdomain() {
    load_plugin_textdomain(
        'buddyactivist-user-location',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );
}
add_action( 'init', 'baul_load_textdomain' );
