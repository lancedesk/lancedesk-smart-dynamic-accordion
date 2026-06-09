<?php
/**
 * Main plugin bootstrap.
 *
 * @package LanceDeskSmartDynamicAccordion
 */

namespace LanceDesk\HBDA;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
final class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'ldrj_hbda_load_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'ldrj_hbda_bootstrap' ) );
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @return void
	 */
	public function ldrj_hbda_load_textdomain(): void {
		load_plugin_textdomain(
			'lancedesk-smart-dynamic-accordion',
			false,
			dirname( plugin_basename( LDRJ_HBDA_PLUGIN_FILE ) ) . '/languages'
		);
	}

	/**
	 * Bootstrap plugin after WordPress plugins are loaded.
	 *
	 * @return void
	 */
	public function ldrj_hbda_bootstrap(): void {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'ldrj_hbda_missing_elementor_notice' ) );
			return;
		}

		/*
		 * Initialize only when Elementor itself is ready so required base classes
		 * (like Elementor\Widget_Base) are guaranteed to be available.
		 */
		add_action( 'elementor/init', array( $this, 'ldrj_hbda_init' ) );
	}

	/**
	 * Initialize plugin only after Elementor is fully initialized.
	 *
	 * @return void
	 */
	public function ldrj_hbda_init(): void {
		if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
			add_action( 'admin_notices', array( $this, 'ldrj_hbda_missing_elementor_notice' ) );
			return;
		}

		$this->ldrj_hbda_load_files();
		$this->ldrj_hbda_register_hooks();
	}

	/**
	 * Load required files.
	 *
	 * @return void
	 */
	private function ldrj_hbda_load_files(): void {
		require_once LDRJ_HBDA_PLUGIN_PATH . 'includes/class-query.php';
		require_once LDRJ_HBDA_PLUGIN_PATH . 'includes/class-ajax.php';
		require_once LDRJ_HBDA_PLUGIN_PATH . 'includes/widgets/class-smart-accordion-widget.php';
	}

	/**
	 * Admin notice shown when Elementor is unavailable.
	 *
	 * @return void
	 */
	public function ldrj_hbda_missing_elementor_notice(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-warning"><p>';
		echo esc_html__( 'LanceDesk Smart Dynamic Accordion requires Elementor to be installed and active.', 'lancedesk-smart-dynamic-accordion' );
		echo '</p></div>';
	}

	/**
	 * Register WordPress and Elementor hooks.
	 *
	 * @return void
	 */
	private function ldrj_hbda_register_hooks(): void {
		add_action( 'elementor/elements/categories_registered', array( $this, 'ldrj_hbda_register_elementor_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'ldrj_hbda_register_widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'ldrj_hbda_register_frontend_assets' ) );
		Ajax::ldrj_hbda_register();
	}

	/**
	 * Register custom Elementor category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 *
	 * @return void
	 */
	public function ldrj_hbda_register_elementor_category( $elements_manager ): void {
		$elements_manager->add_category(
			'ldrj-hbda',
			array(
				'title' => esc_html__( 'Lance Desk', 'lancedesk-smart-dynamic-accordion' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register frontend scripts and styles.
	 *
	 * @return void
	 */
	public function ldrj_hbda_register_frontend_assets(): void {
		wp_register_style(
			'ldrj-hbda-accordion',
			LDRJ_HBDA_PLUGIN_URL . 'assets/css/ldrj-hbda-accordion.css',
			array(),
			LDRJ_HBDA_VERSION
		);

		wp_register_script(
			'ldrj-hbda-accordion',
			LDRJ_HBDA_PLUGIN_URL . 'assets/js/ldrj-hbda-accordion.js',
			array(),
			LDRJ_HBDA_VERSION,
			true
		);

		wp_localize_script(
			'ldrj-hbda-accordion',
			'ldrjHbdaAjax',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ldrj_hbda_load_more' ),
			)
		);
	}

	/**
	 * Register Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 *
	 * @return void
	 */
	public function ldrj_hbda_register_widgets( $widgets_manager ): void {
		$widgets_manager->register( new Widgets\Smart_Accordion_Widget() );
	}
}
