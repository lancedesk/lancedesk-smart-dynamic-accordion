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

define( 'LDRJ_HBDA_VERSION', '1.0.0' );
define( 'LDRJ_HBDA_PLUGIN_FILE', __FILE__ );
define( 'LDRJ_HBDA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LDRJ_HBDA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once LDRJ_HBDA_PLUGIN_PATH . 'includes/class-ldrj-hbda-plugin.php';

\LanceDesk\HBDA\Plugin::instance();
