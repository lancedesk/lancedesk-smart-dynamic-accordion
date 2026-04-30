<?php
/**
 * Plugin Name: LanceDesk Smart Dynamic Accordion for Elementor
 * Description: A secure, modular Elementor accordion widget with manual and dynamic post-based content sources.
 * Version: 1.0.1
 * Author: Lance Desk
 * Author URI: https://lancedesk.com
 * Text Domain: lancedesk-smart-dynamic-accordion
 * Domain Path: /languages
 * Requires at least: 6.4
 * Requires PHP: 7.4
 *
 * @package LanceDeskSmartDynamicAccordion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( '\LanceDesk\HBDA\Plugin', false ) ) {
	if ( ! function_exists( 'deactivate_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( function_exists( 'deactivate_plugins' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	add_action(
		'admin_notices',
		static function (): void {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			echo '<div class="notice notice-error"><p>';
			echo esc_html__( 'LanceDesk Smart Dynamic Accordion detected another active copy of the same plugin and deactivated this duplicate to prevent conflicts.', 'lancedesk-smart-dynamic-accordion' );
			echo '</p></div>';
		}
	);

	return;
}

if ( ! defined( 'LDRJ_HBDA_VERSION' ) ) {
	define( 'LDRJ_HBDA_VERSION', '1.0.1' );
}

if ( ! defined( 'LDRJ_HBDA_PLUGIN_FILE' ) ) {
	define( 'LDRJ_HBDA_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'LDRJ_HBDA_PLUGIN_PATH' ) ) {
	define( 'LDRJ_HBDA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'LDRJ_HBDA_PLUGIN_URL' ) ) {
	define( 'LDRJ_HBDA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

require_once LDRJ_HBDA_PLUGIN_PATH . 'includes/class-ldrj-hbda-plugin.php';

\LanceDesk\HBDA\Plugin::instance();
