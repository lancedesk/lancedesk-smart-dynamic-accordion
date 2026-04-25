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
		add_action( 'plugins_loaded', array( $this, 'ldrj_hbda_init' ) );
	}

	/**
	 * Initialize plugin only after Elementor is available.
	 *
	 * @return void
	 */
	public function ldrj_hbda_init(): void {
		if ( ! did_action( 'elementor/loaded' ) ) {
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
		require_once LDRJ_HBDA_PLUGIN_PATH . 'includes/widgets/class-ldrj-hbda-smart-accordion-widget.php';
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
