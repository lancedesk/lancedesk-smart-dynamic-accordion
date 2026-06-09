<?php
/**
 * Frontend AJAX handlers.
 *
 * @package LanceDeskSmartDynamicAccordion
 */

namespace LanceDesk\HBDA;

use LanceDesk\HBDA\Widgets\Smart_Accordion_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load-more AJAX endpoint.
 */
final class Ajax {

	/**
	 * Register AJAX actions.
	 *
	 * @return void
	 */
	public static function ldrj_hbda_register(): void {
		add_action( 'wp_ajax_ldrj_hbda_load_more', array( __CLASS__, 'ldrj_hbda_handle_load_more' ) );
		add_action( 'wp_ajax_nopriv_ldrj_hbda_load_more', array( __CLASS__, 'ldrj_hbda_handle_load_more' ) );
	}

	/**
	 * Handle load-more requests.
	 *
	 * @return void
	 */
	public static function ldrj_hbda_handle_load_more(): void {
		check_ajax_referer( 'ldrj_hbda_load_more', 'nonce' );

		$offset       = isset( $_POST['offset'] ) ? absint( wp_unslash( $_POST['offset'] ) ) : 0;
		$widget_id    = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['widget_id'] ) ) : '';
		$settings_raw = isset( $_POST['settings'] ) ? sanitize_textarea_field( wp_unslash( (string) $_POST['settings'] ) ) : '';

		if ( '' === $widget_id || '' === $settings_raw ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Invalid load-more request.', 'lancedesk-smart-dynamic-accordion' ),
				),
				400
			);
		}

		$settings = json_decode( $settings_raw, true );
		if ( ! is_array( $settings ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Invalid widget settings.', 'lancedesk-smart-dynamic-accordion' ),
				),
				400
			);
		}

		$settings = Smart_Accordion_Widget::ldrj_hbda_sanitize_ajax_settings( $settings );

		$initial_count = isset( $settings['ldrj_hbda_posts_per_page'] ) ? absint( $settings['ldrj_hbda_posts_per_page'] ) : 6;
		$batch_size    = isset( $settings['ldrj_hbda_load_more_batch'] ) ? absint( $settings['ldrj_hbda_load_more_batch'] ) : $initial_count;

		if ( $batch_size < 1 ) {
			$batch_size = $initial_count > 0 ? $initial_count : 6;
		}

		$total  = Query::ldrj_hbda_count_posts( $settings );
		$items  = Query::ldrj_hbda_get_dynamic_items( $settings, $batch_size, $offset );
		$widget = new Smart_Accordion_Widget(
			array(
				'id' => $widget_id,
			),
			array()
		);

		ob_start();
		foreach ( $items as $index => $item ) {
			$item_index = $offset + (int) $index;
			$widget->ldrj_hbda_render_item_markup( $item, $item_index, $settings, false, $widget_id );
		}
		$html = ob_get_clean();

		if ( ! is_string( $html ) ) {
			$html = '';
		}

		$loaded_count = count( $items );
		$new_offset   = $offset + $loaded_count;
		$remaining    = max( 0, $total - $new_offset );

		wp_send_json_success(
			array(
				'html'      => $html,
				'offset'    => $new_offset,
				'remaining' => $remaining,
				'has_more'  => $remaining > 0,
			)
		);
	}
}
