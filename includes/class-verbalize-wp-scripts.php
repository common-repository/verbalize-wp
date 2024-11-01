<?php
/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package Verbalize WP
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Scripts Class
 */
class Verbalize_WP_Scripts {

	/**
	 * Enqueue Scripts on Admin Side
	 *
	 * @param string $hook page hooks.
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function verbalize_wp_admin_scripts( $hook ) {

		if ( 'toplevel_page_audio-text' === $hook ) {

			wp_register_style( 'aud-txt-style', VERBALIZE_WP_INC_URL . '/css/aud-txt.css', array(), VERBALIZE_WP_VERSION, false );
			wp_enqueue_style( 'aud-txt-style' );

			wp_register_script( 'aud-txt-script', VERBALIZE_WP_INC_URL . '/js/aud-txt.js', array( 'jquery' ), VERBALIZE_WP_VERSION, true );
			wp_enqueue_script( 'aud-txt-script' );

			wp_localize_script(
				'aud-txt-script',
				'verbalize_wp_ajax',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'ajax-nonce' ),
				)
			);
		}
	}

	/**
	 * Enqueue Scripts on Front Side
	 *
	 * @param string $hook page hooks.
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function verbalize_wp_front_scripts( $hook ) {

		wp_register_style( 'aud-front-style', VERBALIZE_WP_INC_URL . '/css/aud-txt-front.css', array(), VERBALIZE_WP_VERSION, false );
		wp_enqueue_style( 'aud-front-style' );

		wp_register_style( 'aud-bootstrap', VERBALIZE_WP_INC_URL . '/css/bootstrap.min.css', array(), VERBALIZE_WP_VERSION, false );
		wp_enqueue_style( 'aud-bootstrap' );

		wp_register_script( 'aud-popper', VERBALIZE_WP_INC_URL . '/js/popper.min.js', array(), '1.0', true );
		wp_enqueue_script( 'aud-popper' );

		wp_register_script( 'aud-bootstrap-min', VERBALIZE_WP_INC_URL . '/js/bootstrap.min.js', array(), VERBALIZE_WP_VERSION, true );
		wp_enqueue_script( 'aud-bootstrap-min' );

		wp_register_script( 'aud-front-script', VERBALIZE_WP_INC_URL . '/js/aud-txt-front.js', array( 'jquery' ), VERBALIZE_WP_VERSION, true );
		wp_enqueue_script( 'aud-front-script' );
	}

	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function add_hooks() {

		// add admin scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'verbalize_wp_admin_scripts' ) );

		// add front scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'verbalize_wp_front_scripts' ) );
	}
}
