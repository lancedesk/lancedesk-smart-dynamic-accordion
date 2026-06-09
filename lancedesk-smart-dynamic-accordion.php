<?php
/**
 * Plugin Name: LanceDesk Smart Dynamic Accordion for Elementor
 * Description: A secure, modular Elementor accordion widget with manual and dynamic post-based content sources.
 * Version: 1.0.8
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

if ( ! defined( 'LDRJ_HBDA_TEXT_DOMAIN' ) ) {
	define( 'LDRJ_HBDA_TEXT_DOMAIN', 'lancedesk-smart-dynamic-accordion' );
}

if ( ! defined( 'LDRJ_HBDA_CONFLICT_NOTICE_TRANSIENT' ) ) {
	define( 'LDRJ_HBDA_CONFLICT_NOTICE_TRANSIENT', 'ldrj_hbda_conflict_notice' );
}

/**
 * Return active plugin files that conflict with this plugin's identity.
 *
 * @return array<int, string>
 */
function ldrj_hbda_find_conflicting_active_plugins(): array {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$plugin_data   = function_exists( 'get_plugins' ) ? get_plugins() : array();
	$current_file  = plugin_basename( __FILE__ );
	$active_plugin = (array) get_option( 'active_plugins', array() );
	$conflicts     = array();

	foreach ( $plugin_data as $file => $headers ) {
		if ( $file === $current_file ) {
			continue;
		}

		if ( ! in_array( $file, $active_plugin, true ) ) {
			continue;
		}

		$text_domain = isset( $headers['TextDomain'] ) ? (string) $headers['TextDomain'] : '';

		if ( LDRJ_HBDA_TEXT_DOMAIN === $text_domain ) {
			$conflicts[] = $file;
		}
	}

	return $conflicts;
}

/**
 * Show admin notice after automatic duplicate deactivation.
 *
 * @return void
 */
function ldrj_hbda_maybe_show_conflict_notice(): void {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( ! get_transient( LDRJ_HBDA_CONFLICT_NOTICE_TRANSIENT ) ) {
		return;
	}

	delete_transient( LDRJ_HBDA_CONFLICT_NOTICE_TRANSIENT );

	echo '<div class="notice notice-error"><p>';
	echo esc_html__( 'LanceDesk Smart Dynamic Accordion was deactivated because another active plugin copy with the same text domain was detected.', LDRJ_HBDA_TEXT_DOMAIN );
	echo '</p></div>';
}

/**
 * Activation-time protection against duplicate active copies.
 *
 * @return void
 */
function ldrj_hbda_activation_conflict_guard(): void {
	$conflicts = ldrj_hbda_find_conflicting_active_plugins();

	if ( empty( $conflicts ) ) {
		return;
	}

	if ( ! function_exists( 'deactivate_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	deactivate_plugins( plugin_basename( __FILE__ ) );
	set_transient( LDRJ_HBDA_CONFLICT_NOTICE_TRANSIENT, 1, MINUTE_IN_SECONDS );

	wp_die(
		esc_html__( 'Activation blocked: another active copy of LanceDesk Smart Dynamic Accordion is already running. Please keep only one installed copy active.', LDRJ_HBDA_TEXT_DOMAIN ),
		esc_html__( 'Plugin Conflict', LDRJ_HBDA_TEXT_DOMAIN ),
		array(
			'response'  => 409,
			'back_link' => true,
		)
	);
}

register_activation_hook( __FILE__, 'ldrj_hbda_activation_conflict_guard' );

/**
 * Runtime protection in case duplicate copies become active.
 *
 * @return void
 */
function ldrj_hbda_runtime_conflict_guard(): void {
	$conflicts = ldrj_hbda_find_conflicting_active_plugins();

	if ( empty( $conflicts ) ) {
		return;
	}

	if ( ! function_exists( 'deactivate_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	deactivate_plugins( plugin_basename( __FILE__ ) );
	set_transient( LDRJ_HBDA_CONFLICT_NOTICE_TRANSIENT, 1, MINUTE_IN_SECONDS );
}

add_action( 'admin_init', 'ldrj_hbda_runtime_conflict_guard', 1 );
add_action( 'admin_notices', 'ldrj_hbda_maybe_show_conflict_notice' );

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
			echo esc_html__( 'LanceDesk Smart Dynamic Accordion detected another active copy of the same plugin and deactivated this duplicate to prevent conflicts.', LDRJ_HBDA_TEXT_DOMAIN );
			echo '</p></div>';
		}
	);

	return;
}

if ( ! defined( 'LDRJ_HBDA_VERSION' ) ) {
	define( 'LDRJ_HBDA_VERSION', '1.0.8' );
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

require_once LDRJ_HBDA_PLUGIN_PATH . 'includes/class-plugin.php';

\LanceDesk\HBDA\Plugin::instance();
