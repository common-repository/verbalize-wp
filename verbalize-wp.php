<?php
/**
 * Plugin Name: Verbalize WP
 * Description: Test to audio convert with shortcode display.
 * Version: 1.0
 * Text Domain : verbalize-wp
 * Author: Admin
 * Domain Path: languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Verbalize WP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'verbwp_fs' ) ) {

	/**
	 * Create a helper function for easy SDK access.
	 */
	function verbwp_fs() {

		global  $verbwp_fs;

		if ( ! isset( $verbwp_fs ) ) {

			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$verbwp_fs = fs_dynamic_init(
				array(
					'id'              => '10289',
					'slug'            => 'wp-sale-voice',
					'type'            => 'plugin',
					'public_key'      => 'pk_d802a217997da1f4776ee35b36472',
					'is_premium'      => true,
					'is_premium_only' => false,
					'has_addons'      => false,
					'has_paid_plans'  => true,
					'trial'           => array(
						'days'               => 14,
						'is_require_payment' => true,
					),
					'menu'            => array(
						'slug'    => 'verbalize-wp',
						'support' => false,
					),
					'is_live'         => true,
				)
			);
		}

		return $verbwp_fs;
	}

	// Init Freemius.
	verbwp_fs();

	// Signal that SDK was initiated.
	do_action( 'verbwp_fs_loaded' );
}

/**
 * Basic plugin definitions
 *
 * @package Verbalize WP
 * @since 1.0
 */
if ( ! defined( 'VERBALIZE_WP_DIR' ) ) {
	define( 'VERBALIZE_WP_DIR', dirname( __FILE__ ) );      // Plugin dir.
}

if ( ! defined( 'VERBALIZE_WP_VERSION' ) ) {
	define( 'VERBALIZE_WP_VERSION', '1.0' );      // Plugin Version.
}

if ( ! defined( 'VERBALIZE_WP_URL' ) ) {
	define( 'VERBALIZE_WP_URL', plugin_dir_url( __FILE__ ) );   // Plugin url.
}

if ( ! defined( 'VERBALIZE_WP_INC_DIR' ) ) {
	define( 'VERBALIZE_WP_INC_DIR', VERBALIZE_WP_DIR . '/includes' );   // Plugin include dir.
}

if ( ! defined( 'VERBALIZE_WP_INC_URL' ) ) {
	define( 'VERBALIZE_WP_INC_URL', VERBALIZE_WP_URL . 'includes' );    // Plugin include url.
}

if ( ! defined( 'VERBALIZE_WP_ADMIN_DIR' ) ) {
	define( 'VERBALIZE_WP_ADMIN_DIR', VERBALIZE_WP_INC_DIR . '/admin' );  // Plugin admin dir.
}

if ( ! defined( 'VERBALIZE_WP_PREFIX' ) ) {
	define( 'VERBALIZE_WP_PREFIX', 'VERBALIZE_WP' ); // Plugin Pre-fix.
}

if ( ! defined( 'VERBALIZE_WP_VAR_PREFIX' ) ) {
	define( 'VERBALIZE_WP_VAR_PREFIX', '_VERBALIZE_WP_' ); // Variable Pre-fix.
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Verbalize WP
 * @since 1.0
 */
load_plugin_textdomain( 'verbalize-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

// Global variables.
global $verbalize_wp_scripts, $verbalize_wp_admin;

// Script class handles most of script functionalities of plugin.
require_once VERBALIZE_WP_INC_DIR . '/class-verbalize-wp-scripts.php';
$verbalize_wp_scripts = new Verbalize_WP_Scripts();
$verbalize_wp_scripts->add_hooks();

// Script class handles most of front functionalities of plugin.
require_once VERBALIZE_WP_INC_DIR . '/class-verbalize-wp-front.php';
$verbalize_wp_front = new Verbalize_WP_Front();
$verbalize_wp_front->add_hooks();

// Admin class handles most of admin panel functionalities of plugin.
require_once VERBALIZE_WP_ADMIN_DIR . '/class-verbalize-wp-admin.php';
$verbalize_wp_admin = new Verbalize_WP_Admin();
$verbalize_wp_admin->add_hooks();
