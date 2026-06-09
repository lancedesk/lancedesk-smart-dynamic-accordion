<?php
/**
 * Smart Accordion widget.
 *
 * @package LanceDeskSmartDynamicAccordion
 */

namespace LanceDesk\HBDA\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use LanceDesk\HBDA\Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Smart Accordion widget class.
 */
class Smart_Accordion_Widget extends Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'ldrj_hbda_smart_accordion';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Smart Dynamic Accordion', 'lancedesk-smart-dynamic-accordion' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-accordion';
	}

	/**
	 * Widget categories.
	 *
	 * @return array
	 */
	public function get_categories(): array {
		return array( 'ldrj-hbda' );
	}

	/**
	 * Widget keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'accordion', 'faq', 'toggle', 'dynamic' );
	}

	/**
	 * Define frontend dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return array( 'ldrj-hbda-accordion' );
	}

	/**
	 * Define frontend dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends(): array {
		return array( 'ldrj-hbda-accordion' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->ldrj_hbda_register_content_controls();
		$this->ldrj_hbda_register_style_controls();
		$this->ldrj_hbda_register_load_more_style_controls();
		$this->ldrj_hbda_register_icon_style_controls();
	}

	/**
	 * Content controls.
	 *
	 * @return void
	 */
	private function ldrj_hbda_register_content_controls(): void {
		$this->start_controls_section(
			'ldrj_hbda_section_content',
			array(
				'label' => esc_html__( 'Accordion', 'lancedesk-smart-dynamic-accordion' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ldrj_hbda_source_mode',
			array(
				'label'   => esc_html__( 'Source', 'lancedesk-smart-dynamic-accordion' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => array(
					'manual'  => esc_html__( 'Manual Items', 'lancedesk-smart-dynamic-accordion' ),
					'dynamic' => esc_html__( 'Dynamic from Posts', 'lancedesk-smart-dynamic-accordion' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'ldrj_hbda_item_title',
			array(
				'label'       => esc_html__( 'Title', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Accordion item title', 'lancedesk-smart-dynamic-accordion' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'ldrj_hbda_item_content',
			array(
				'label'   => esc_html__( 'Content', 'lancedesk-smart-dynamic-accordion' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Accordion item content goes here.', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_manual_items',
			array(
				'label'       => esc_html__( 'Items', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ ldrj_hbda_item_title }}}',
				'condition'   => array(
					'ldrj_hbda_source_mode' => 'manual',
				),
				'default'     => array(
					array(
						'ldrj_hbda_item_title'   => esc_html__( 'How can I support the foundation?', 'lancedesk-smart-dynamic-accordion' ),
						'ldrj_hbda_item_content' => esc_html__( 'You can support by donating, partnering, or sponsoring key initiatives.', 'lancedesk-smart-dynamic-accordion' ),
					),
					array(
						'ldrj_hbda_item_title'   => esc_html__( 'What are the tax benefits?', 'lancedesk-smart-dynamic-accordion' ),
						'ldrj_hbda_item_content' => esc_html__( 'Donations may be tax deductible depending on local regulations.', 'lancedesk-smart-dynamic-accordion' ),
					),
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'lancedesk-smart-dynamic-accordion' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'after'  => array(
						'title' => esc_html__( 'After', 'lancedesk-smart-dynamic-accordion' ),
						'icon'  => 'eicon-h-align-right',
					),
					'before' => array(
						'title' => esc_html__( 'Before', 'lancedesk-smart-dynamic-accordion' ),
						'icon'  => 'eicon-h-align-left',
					),
				),
				'default' => 'after',
				'toggle'  => false,
			)
		);

		$this->add_control(
			'ldrj_hbda_expand_icon',
			array(
				'label'   => esc_html__( 'Expand Icon', 'lancedesk-smart-dynamic-accordion' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_collapse_icon',
			array(
				'label'   => esc_html__( 'Collapse Icon', 'lancedesk-smart-dynamic-accordion' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_icon_render_mode',
			array(
				'label'   => esc_html__( 'Icon Render Mode', 'lancedesk-smart-dynamic-accordion' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto' => esc_html__( 'Auto (SVG if available)', 'lancedesk-smart-dynamic-accordion' ),
					'text' => esc_html__( 'Text Only (+ / −)', 'lancedesk-smart-dynamic-accordion' ),
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_post_type',
			array(
				'label'     => esc_html__( 'Post Type', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->ldrj_hbda_get_post_type_options(),
				'default'   => 'post',
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_query_heading',
			array(
				'label'     => esc_html__( 'Query', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_posts_per_page',
			array(
				'label'     => esc_html__( 'Items Count', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'min'       => 1,
				'max'       => 100,
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_enable_load_more',
			array(
				'label'        => esc_html__( 'Enable Load More', 'lancedesk-smart-dynamic-accordion' ),
				'description'  => esc_html__( 'When more items match the query than the initial count, reveal additional items on demand.', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_load_more_mode',
			array(
				'label'     => esc_html__( 'Load More Mode', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'button',
				'options'   => array(
					'button'          => esc_html__( 'Show More Button', 'lancedesk-smart-dynamic-accordion' ),
					'infinite_scroll' => esc_html__( 'Infinite Scroll', 'lancedesk-smart-dynamic-accordion' ),
				),
				'condition' => array(
					'ldrj_hbda_source_mode'      => 'dynamic',
					'ldrj_hbda_enable_load_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_load_more_batch',
			array(
				'label'       => esc_html__( 'Items Per Load', 'lancedesk-smart-dynamic-accordion' ),
				'description' => esc_html__( 'How many additional items to reveal each time. Defaults to the initial items count when empty.', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 50,
				'default'     => 6,
				'condition'   => array(
					'ldrj_hbda_source_mode'      => 'dynamic',
					'ldrj_hbda_enable_load_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_load_more_button_text',
			array(
				'label'       => esc_html__( 'Show More Button Text', 'lancedesk-smart-dynamic-accordion' ),
				/* translators: %d: number of remaining accordion items. */
				'description' => esc_html__( 'Use %d as a placeholder for the remaining item count.', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::TEXT,
				/* translators: %d: number of remaining accordion items. */
				'default'     => esc_html__( 'Show %d more', 'lancedesk-smart-dynamic-accordion' ),
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode'      => 'dynamic',
					'ldrj_hbda_enable_load_more' => 'yes',
					'ldrj_hbda_load_more_mode'   => 'button',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_load_more_loading_text',
			array(
				'label'     => esc_html__( 'Loading Text', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Loading…', 'lancedesk-smart-dynamic-accordion' ),
				'condition' => array(
					'ldrj_hbda_source_mode'      => 'dynamic',
					'ldrj_hbda_enable_load_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_offset',
			array(
				'label'     => esc_html__( 'Offset', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'min'       => 0,
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_order',
			array(
				'label'     => esc_html__( 'Order', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'DESC' => esc_html__( 'Descending', 'lancedesk-smart-dynamic-accordion' ),
					'ASC'  => esc_html__( 'Ascending', 'lancedesk-smart-dynamic-accordion' ),
				),
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_orderby',
			array(
				'label'     => esc_html__( 'Order By', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'       => esc_html__( 'Date', 'lancedesk-smart-dynamic-accordion' ),
					'title'      => esc_html__( 'Title', 'lancedesk-smart-dynamic-accordion' ),
					'menu_order' => esc_html__( 'Menu Order', 'lancedesk-smart-dynamic-accordion' ),
					'rand'       => esc_html__( 'Random', 'lancedesk-smart-dynamic-accordion' ),
				),
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_include_ids',
			array(
				'label'       => esc_html__( 'Include Post IDs', 'lancedesk-smart-dynamic-accordion' ),
				'description' => esc_html__( 'Comma-separated IDs, e.g. 12,44,87', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_exclude_ids',
			array(
				'label'       => esc_html__( 'Exclude Post IDs', 'lancedesk-smart-dynamic-accordion' ),
				'description' => esc_html__( 'Comma-separated IDs, e.g. 15,23', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_filter_heading',
			array(
				'label'     => esc_html__( 'Taxonomy Filters', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$default_post_type = 'post';

		$this->add_control(
			'ldrj_hbda_category_terms',
			array(
				'label'       => esc_html__( 'Categories', 'lancedesk-smart-dynamic-accordion' ),
				'description' => esc_html__( 'Optional. Filter by one or more category-like terms for the selected post type.', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Query::ldrj_hbda_get_taxonomy_term_options( $default_post_type, true ),
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_tag_terms',
			array(
				'label'       => esc_html__( 'Tags', 'lancedesk-smart-dynamic-accordion' ),
				'description' => esc_html__( 'Optional. Filter by one or more tag-like terms for the selected post type.', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Query::ldrj_hbda_get_taxonomy_term_options( $default_post_type, false ),
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_taxonomy_relation',
			array(
				'label'     => esc_html__( 'Filter Relation', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'AND',
				'options'   => array(
					'AND' => esc_html__( 'Match All Selected Filters', 'lancedesk-smart-dynamic-accordion' ),
					'OR'  => esc_html__( 'Match Any Selected Filter', 'lancedesk-smart-dynamic-accordion' ),
				),
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_advanced_tax_heading',
			array(
				'label'     => esc_html__( 'Advanced Taxonomy', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_taxonomy',
			array(
				'label'       => esc_html__( 'Custom Taxonomy Slug', 'lancedesk-smart-dynamic-accordion' ),
				'description' => esc_html__( 'Optional. Example: product_cat, faq_topic', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_tax_terms',
			array(
				'label'       => esc_html__( 'Custom Taxonomy Terms', 'lancedesk-smart-dynamic-accordion' ),
				'description' => esc_html__( 'Comma-separated term slugs or IDs', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_tax_field',
			array(
				'label'     => esc_html__( 'Custom Taxonomy Term Type', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slug',
				'options'   => array(
					'slug'    => esc_html__( 'Slug', 'lancedesk-smart-dynamic-accordion' ),
					'term_id' => esc_html__( 'ID', 'lancedesk-smart-dynamic-accordion' ),
				),
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_title_source',
			array(
				'label'     => esc_html__( 'Dynamic Title Source', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'title',
				'options'   => array(
					'title' => esc_html__( 'Post Title', 'lancedesk-smart-dynamic-accordion' ),
					'meta'  => esc_html__( 'Custom Field', 'lancedesk-smart-dynamic-accordion' ),
				),
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_mapping_heading',
			array(
				'label'     => esc_html__( 'Field Mapping', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_title_meta_key',
			array(
				'label'       => esc_html__( 'Title Custom Field Key', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode'  => 'dynamic',
					'ldrj_hbda_title_source' => 'meta',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_content_source',
			array(
				'label'     => esc_html__( 'Dynamic Content Source', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'content',
				'options'   => array(
					'content' => esc_html__( 'Post Content', 'lancedesk-smart-dynamic-accordion' ),
					'excerpt' => esc_html__( 'Excerpt', 'lancedesk-smart-dynamic-accordion' ),
					'meta'    => esc_html__( 'Custom Field', 'lancedesk-smart-dynamic-accordion' ),
				),
				'condition' => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_meta_value_format',
			array(
				'label'     => esc_html__( 'Custom Field Value Type', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'auto',
				'options'   => array(
					'auto'      => esc_html__( 'Auto Detect', 'lancedesk-smart-dynamic-accordion' ),
					'text'      => esc_html__( 'Plain Text', 'lancedesk-smart-dynamic-accordion' ),
					'html'      => esc_html__( 'HTML', 'lancedesk-smart-dynamic-accordion' ),
					'image_url' => esc_html__( 'Image URL', 'lancedesk-smart-dynamic-accordion' ),
				),
				'condition' => array(
					'ldrj_hbda_source_mode'    => 'dynamic',
					'ldrj_hbda_content_source' => 'meta',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_meta_key',
			array(
				'label'       => esc_html__( 'Custom Field Key', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'ldrj_hbda_source_mode'    => 'dynamic',
					'ldrj_hbda_content_source' => 'meta',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_show_read_more',
			array(
				'label'        => esc_html__( 'Show Read More Link', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'ldrj_hbda_source_mode' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_read_more_new_tab',
			array(
				'label'        => esc_html__( 'Open in New Tab', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'ldrj_hbda_source_mode'    => 'dynamic',
					'ldrj_hbda_show_read_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_read_more_nofollow',
			array(
				'label'        => esc_html__( 'Add nofollow', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'ldrj_hbda_source_mode'    => 'dynamic',
					'ldrj_hbda_show_read_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_read_more_text',
			array(
				'label'     => esc_html__( 'Read More Label', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read more', 'lancedesk-smart-dynamic-accordion' ),
				'condition' => array(
					'ldrj_hbda_source_mode'    => 'dynamic',
					'ldrj_hbda_show_read_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_faq_schema',
			array(
				'label'        => esc_html__( 'Enable FAQ Schema', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'ldrj_hbda_faq_min_items',
			array(
				'label'     => esc_html__( 'Schema Minimum Q&A Count', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 50,
				'default'   => 2,
				'condition' => array(
					'ldrj_hbda_faq_schema' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_faq_require_question_mark',
			array(
				'label'        => esc_html__( 'Require "?" in Question Title', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'ldrj_hbda_faq_schema' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_open_first',
			array(
				'label'        => esc_html__( 'Open First Item', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'ldrj_hbda_allow_multiple_open',
			array(
				'label'        => esc_html__( 'Allow Multiple Open', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style controls.
	 *
	 * @return void
	 */
	private function ldrj_hbda_register_style_controls(): void {
		$this->start_controls_section(
			'ldrj_hbda_section_style',
			array(
				'label' => esc_html__( 'Accordion', 'lancedesk-smart-dynamic-accordion' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_item_spacing',
			array(
				'label'      => esc_html__( 'Space Between Items', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-item + .ldrj-hbda-item' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_show_dividers',
			array(
				'label'        => esc_html__( 'Show Dividers', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'lancedesk-smart-dynamic-accordion' ),
				'label_off'    => esc_html__( 'No', 'lancedesk-smart-dynamic-accordion' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'ldrj_hbda_divider_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#acb2cf',
				'selectors' => $this->ldrj_hbda_get_divider_selectors( 'border-{{SIDE}}-color: {{VALUE}};' ),
				'condition' => array(
					'ldrj_hbda_show_dividers' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_divider_width',
			array(
				'label'      => esc_html__( 'Divider Width', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'size' => 1,
					'unit' => 'px',
				),
				'selectors'  => $this->ldrj_hbda_get_divider_selectors( 'border-{{SIDE}}-width: {{SIZE}}{{UNIT}};' ),
				'condition'  => array(
					'ldrj_hbda_show_dividers' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_divider_style',
			array(
				'label'     => esc_html__( 'Divider Style', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'lancedesk-smart-dynamic-accordion' ),
					'dashed' => esc_html__( 'Dashed', 'lancedesk-smart-dynamic-accordion' ),
					'dotted' => esc_html__( 'Dotted', 'lancedesk-smart-dynamic-accordion' ),
					'double' => esc_html__( 'Double', 'lancedesk-smart-dynamic-accordion' ),
				),
				'selectors' => $this->ldrj_hbda_get_divider_selectors( 'border-{{SIDE}}-style: {{VALUE}};' ),
				'condition' => array(
					'ldrj_hbda_show_dividers' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_divider_advanced_heading',
			array(
				'label'     => esc_html__( 'Advanced', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'ldrj_hbda_show_dividers' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_divider_border_top',
			array(
				'label'        => esc_html__( 'Border at Top', 'lancedesk-smart-dynamic-accordion' ),
				'description'  => esc_html__( 'Adds a line above the first accordion item only.', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'lancedesk-smart-dynamic-accordion' ),
				'label_off'    => esc_html__( 'No', 'lancedesk-smart-dynamic-accordion' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'ldrj_hbda_show_dividers' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_divider_border_bottom',
			array(
				'label'        => esc_html__( 'Border at Bottom', 'lancedesk-smart-dynamic-accordion' ),
				'description'  => esc_html__( 'Adds a line below the last accordion item only.', 'lancedesk-smart-dynamic-accordion' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'lancedesk-smart-dynamic-accordion' ),
				'label_off'    => esc_html__( 'No', 'lancedesk-smart-dynamic-accordion' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'ldrj_hbda_show_dividers' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_title_heading',
			array(
				'label' => esc_html__( 'Title', 'lancedesk-smart-dynamic-accordion' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'ldrj_hbda_title_color',
			array(
				'label'     => esc_html__( 'Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#03195f',
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-trigger-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ldrj_hbda_title_typography',
				'selector' => '{{WRAPPER}} .ldrj-hbda-trigger-text',
			)
		);

		$this->start_controls_tabs( 'ldrj_hbda_trigger_state_tabs' );

		$this->start_controls_tab(
			'ldrj_hbda_trigger_state_normal',
			array(
				'label' => esc_html__( 'Normal', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_title_color_normal',
			array(
				'label'     => esc_html__( 'Title Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#03195f',
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-trigger-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_trigger_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-trigger' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ldrj_hbda_trigger_state_hover',
			array(
				'label' => esc_html__( 'Hover', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_title_color_hover',
			array(
				'label'     => esc_html__( 'Title Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-trigger:hover .ldrj-hbda-trigger-text' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_trigger_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-trigger:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ldrj-hbda-trigger:focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ldrj-hbda-trigger:active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ldrj_hbda_trigger_state_active',
			array(
				'label' => esc_html__( 'Active', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_title_color_active',
			array(
				'label'     => esc_html__( 'Title Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-item.is-open .ldrj-hbda-trigger-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_trigger_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-item.is-open .ldrj-hbda-trigger' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'ldrj_hbda_title_padding',
			array(
				'label'      => esc_html__( 'Title Padding', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-trigger'       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ldrj-hbda-trigger:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ldrj-hbda-trigger:focus' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ldrj-hbda-trigger:active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => 20,
					'right'    => 0,
					'bottom'   => 20,
					'left'     => 0,
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_content_heading',
			array(
				'label'     => esc_html__( 'Content', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'ldrj_hbda_content_color',
			array(
				'label'     => esc_html__( 'Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1f2937',
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ldrj_hbda_content_typography',
				'selector' => '{{WRAPPER}} .ldrj-hbda-content',
			)
		);

		$this->add_control(
			'ldrj_hbda_content_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-content-wrap' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_read_more_heading',
			array(
				'label'     => esc_html__( 'Read More Link', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'ldrj_hbda_show_read_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_read_more_color',
			array(
				'label'     => esc_html__( 'Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-read-more' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'ldrj_hbda_show_read_more' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'ldrj_hbda_read_more_typography',
				'selector'  => '{{WRAPPER}} .ldrj-hbda-read-more',
				'condition' => array(
					'ldrj_hbda_show_read_more' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'ldrj_hbda_content_border',
				'selector' => '{{WRAPPER}} .ldrj-hbda-content-wrap',
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_content_padding',
			array(
				'label'      => esc_html__( 'Content Padding', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 16,
					'left'     => 0,
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Load-more button style controls.
	 *
	 * @return void
	 */
	private function ldrj_hbda_register_load_more_style_controls(): void {
		$this->start_controls_section(
			'ldrj_hbda_section_style_load_more',
			array(
				'label'     => esc_html__( 'Load More Button', 'lancedesk-smart-dynamic-accordion' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ldrj_hbda_source_mode'      => 'dynamic',
					'ldrj_hbda_enable_load_more' => 'yes',
					'ldrj_hbda_load_more_mode'   => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_load_more_align',
			array(
				'label'     => esc_html__( 'Alignment', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'lancedesk-smart-dynamic-accordion' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'lancedesk-smart-dynamic-accordion' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'lancedesk-smart-dynamic-accordion' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-wrap' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_load_more_width',
			array(
				'label'      => esc_html__( 'Width', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 80,
						'max' => 600,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-btn' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
				),
			)
		);

		$this->start_controls_tabs( 'ldrj_hbda_load_more_state_tabs' );

		$this->start_controls_tab(
			'ldrj_hbda_load_more_state_normal',
			array(
				'label' => esc_html__( 'Normal', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_load_more_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_load_more_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#03195f',
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ldrj_hbda_load_more_state_hover',
			array(
				'label' => esc_html__( 'Hover', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_load_more_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ldrj-hbda-load-more-btn:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_load_more_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-btn:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ldrj-hbda-load-more-btn:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ldrj_hbda_load_more_typography',
				'selector' => '{{WRAPPER}} .ldrj-hbda-load-more-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'ldrj_hbda_load_more_border',
				'selector' => '{{WRAPPER}} .ldrj-hbda-load-more-btn',
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_load_more_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_load_more_padding',
			array(
				'label'      => esc_html__( 'Padding', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => 14,
					'right'    => 28,
					'bottom'   => 14,
					'left'     => 28,
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_load_more_margin',
			array(
				'label'      => esc_html__( 'Margin', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-load-more-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => 24,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Dedicated icon style controls.
	 *
	 * @return void
	 */
	private function ldrj_hbda_register_icon_style_controls(): void {
		$this->start_controls_section(
			'ldrj_hbda_section_style_icon',
			array(
				'label' => esc_html__( 'Icon', 'lancedesk-smart-dynamic-accordion' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ldrj-hbda-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ldrj_hbda_icon_thickness',
			array(
				'label'      => esc_html__( 'Thickness', 'lancedesk-smart-dynamic-accordion' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ldrj-hbda-icon svg'      => 'stroke: currentColor; stroke-width: {{SIZE}}{{UNIT}}; paint-order: stroke fill;',
					'{{WRAPPER}} .ldrj-hbda-icon-fallback' => 'text-shadow: 0 0 {{SIZE}}{{UNIT}} currentColor;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ldrj_hbda_icon_typography',
				'selector' => '{{WRAPPER}} .ldrj-hbda-icon, {{WRAPPER}} .ldrj-hbda-icon-fallback',
			)
		);

		$this->start_controls_tabs( 'ldrj_hbda_icon_state_tabs' );

		$this->start_controls_tab(
			'ldrj_hbda_icon_state_normal',
			array(
				'label' => esc_html__( 'Normal', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_icon_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#03195f',
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ldrj_hbda_icon_state_hover',
			array(
				'label' => esc_html__( 'Hover', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_icon_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-trigger:hover .ldrj-hbda-icon' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ldrj_hbda_icon_state_active',
			array(
				'label' => esc_html__( 'Active', 'lancedesk-smart-dynamic-accordion' ),
			)
		);

		$this->add_control(
			'ldrj_hbda_icon_color_active',
			array(
				'label'     => esc_html__( 'Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-item.is-open .ldrj-hbda-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Build Elementor selectors for intelligent single-line dividers.
	 *
	 * Between items: bottom border on all items except the last (one line per gap).
	 * Top edge: optional top border on the first item only.
	 * Bottom edge: optional bottom border on the last item only.
	 *
	 * @param string $property_css CSS declarations with {{SIDE}} placeholder (top|bottom).
	 *
	 * @return array<string, string>
	 */
	private function ldrj_hbda_get_divider_selectors( string $property_css ): array {
		$between_rule = str_replace( '{{SIDE}}', 'bottom', $property_css );
		$top_rule     = str_replace( '{{SIDE}}', 'top', $property_css );
		$bottom_rule  = str_replace( '{{SIDE}}', 'bottom', $property_css );

		return array(
			'{{WRAPPER}} .ldrj-hbda-has-dividers .ldrj-hbda-item:not(:last-child)' => $between_rule,
			'{{WRAPPER}} .ldrj-hbda-has-dividers.ldrj-hbda-divider-top .ldrj-hbda-item:first-child' => $top_rule,
			'{{WRAPPER}} .ldrj-hbda-has-dividers.ldrj-hbda-divider-bottom .ldrj-hbda-item:last-child' => $bottom_rule,
		);
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings   = $this->get_settings_for_display();
		$items      = $this->ldrj_hbda_get_items( $settings );
		$open_first = isset( $settings['ldrj_hbda_open_first'] ) && 'yes' === $settings['ldrj_hbda_open_first'];
		$multi_open = isset( $settings['ldrj_hbda_allow_multiple_open'] ) && 'yes' === $settings['ldrj_hbda_allow_multiple_open'];
		$icon_pos   = isset( $settings['ldrj_hbda_icon_position'] ) && 'before' === $settings['ldrj_hbda_icon_position'] ? 'before' : 'after';
		$faq_schema = isset( $settings['ldrj_hbda_faq_schema'] ) && 'yes' === $settings['ldrj_hbda_faq_schema'];

		if ( empty( $items ) ) {
			return;
		}

		$load_more_config = $this->ldrj_hbda_get_load_more_config( $settings, count( $items ) );

		$show_dividers   = isset( $settings['ldrj_hbda_show_dividers'] ) && 'yes' === $settings['ldrj_hbda_show_dividers'];
		$wrapper_classes = 'ldrj-hbda-accordion ldrj-hbda-icon-' . $icon_pos;
		if ( $multi_open ) {
			$wrapper_classes .= ' ldrj-hbda-multi-open';
		}
		if ( $show_dividers ) {
			$wrapper_classes .= ' ldrj-hbda-has-dividers';
			if ( isset( $settings['ldrj_hbda_divider_border_top'] ) && 'yes' === $settings['ldrj_hbda_divider_border_top'] ) {
				$wrapper_classes .= ' ldrj-hbda-divider-top';
			}
			if ( isset( $settings['ldrj_hbda_divider_border_bottom'] ) && 'yes' === $settings['ldrj_hbda_divider_border_bottom'] ) {
				$wrapper_classes .= ' ldrj-hbda-divider-bottom';
			}
		}

		$wrapper_attrs = array(
			'class'           => $wrapper_classes,
			'data-multi-open' => $multi_open ? 'yes' : 'no',
			'data-open-first' => $open_first ? 'yes' : 'no',
		);

		if ( ! empty( $load_more_config['enabled'] ) ) {
			$wrapper_attrs['class']               .= ' ldrj-hbda-has-load-more';
			$wrapper_attrs['data-load-more']       = 'yes';
			$wrapper_attrs['data-load-more-mode']  = esc_attr( (string) $load_more_config['mode'] );
			$wrapper_attrs['data-widget-id']       = esc_attr( $this->get_id() );
			$wrapper_attrs['data-offset']          = (string) (int) $load_more_config['offset'];
			$wrapper_attrs['data-remaining']       = (string) (int) $load_more_config['remaining'];
			$wrapper_attrs['data-settings']        = esc_attr( wp_json_encode( $this->ldrj_hbda_get_ajax_settings_payload( $settings ) ) );
			$wrapper_attrs['data-loading-text']    = esc_attr( (string) $load_more_config['loading_text'] );
			$wrapper_attrs['data-button-template'] = esc_attr( (string) $load_more_config['button_template'] );
		}

		echo '<div';
		foreach ( $wrapper_attrs as $attr => $value ) {
			echo ' ' . esc_attr( $attr ) . '="' . esc_attr( (string) $value ) . '"';
		}
		echo '>';

		foreach ( $items as $index => $item ) {
			$this->ldrj_hbda_render_item_markup( $item, (int) $index, $settings, $open_first && 0 === (int) $index );
		}

		if ( ! empty( $load_more_config['enabled'] ) ) {
			if ( 'button' === $load_more_config['mode'] && $load_more_config['remaining'] > 0 ) {
				echo '<div class="ldrj-hbda-load-more-wrap">';
				echo '<button type="button" class="ldrj-hbda-load-more-btn" aria-live="polite">';
				echo esc_html( $this->ldrj_hbda_format_load_more_label( (string) $load_more_config['button_template'], (int) $load_more_config['remaining'] ) );
				echo '</button>';
				echo '</div>';
			}

			if ( 'infinite_scroll' === $load_more_config['mode'] && $load_more_config['remaining'] > 0 ) {
				echo '<div class="ldrj-hbda-load-more-sentinel" aria-hidden="true"></div>';
				echo '<div class="ldrj-hbda-load-more-status" aria-live="polite" hidden>';
				echo esc_html( (string) $load_more_config['loading_text'] );
				echo '</div>';
			}
		}

		echo '</div>';

		if ( $faq_schema ) {
			$this->ldrj_hbda_render_faq_schema( $items, $settings );
		}
	}

	/**
	 * Render a single accordion item.
	 *
	 * @param array<string,string> $item Item data.
	 * @param int                  $index Item index.
	 * @param array<string,mixed>  $settings Widget settings.
	 * @param bool|null            $force_open Optional explicit open state.
	 * @param string|null          $widget_id Optional Elementor widget ID for markup IDs.
	 *
	 * @return void
	 */
	public function ldrj_hbda_render_item_markup( array $item, int $index, array $settings, ?bool $force_open = null, ?string $widget_id = null ): void {
		$element_id = null !== $widget_id ? $widget_id : (string) $this->get_id();
		$item_id    = 'ldrj-hbda-item-' . esc_attr( $element_id . '-' . $index );

		if ( null === $force_open ) {
			$force_open = isset( $settings['ldrj_hbda_open_first'] ) && 'yes' === $settings['ldrj_hbda_open_first'] && 0 === $index;
		}

		$item_class = 'ldrj-hbda-item';
		if ( $force_open ) {
			$item_class .= ' is-open';
		}

		echo '<div class="' . esc_attr( $item_class ) . '">';
		echo '<button type="button" class="ldrj-hbda-trigger" aria-expanded="' . esc_attr( $force_open ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $item_id ) . '">';
		echo '<span class="ldrj-hbda-trigger-text">' . esc_html( $item['title'] ) . '</span>';
		echo '<span class="ldrj-hbda-icon" aria-hidden="true">';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor icon markup is rendered via Icons_Manager.
		echo '<span class="ldrj-hbda-icon-expand">' . $this->ldrj_hbda_render_icon_markup( $settings, $settings['ldrj_hbda_expand_icon'] ?? array(), '+' ) . '</span>';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor icon markup is rendered via Icons_Manager.
		echo '<span class="ldrj-hbda-icon-collapse">' . $this->ldrj_hbda_render_icon_markup( $settings, $settings['ldrj_hbda_collapse_icon'] ?? array(), '−' ) . '</span>';
		echo '</span>';
		echo '</button>';
		echo '<div id="' . esc_attr( $item_id ) . '" class="ldrj-hbda-content-wrap" ' . ( $force_open ? '' : 'hidden' ) . '>';
		echo '<div class="ldrj-hbda-content">' . wp_kses_post( $item['content'] ) . '</div>';
		if ( ! empty( $item['url'] ) ) {
			$read_more_attrs = $this->ldrj_hbda_build_link_attributes( $item );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Link attributes are escaped in ldrj_hbda_build_link_attributes().
			echo '<a class="ldrj-hbda-read-more" href="' . esc_url( $item['url'] ) . '"' . $read_more_attrs . '>' . esc_html( $item['read_more_label'] ) . '</a>';
		}
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Build normalized item array.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 *
	 * @return array<int,array<string,string>>
	 */
	private function ldrj_hbda_get_items( array $settings ): array {
		if ( isset( $settings['ldrj_hbda_source_mode'] ) && 'dynamic' === $settings['ldrj_hbda_source_mode'] ) {
			return $this->ldrj_hbda_get_dynamic_items( $settings );
		}

		$manual_items = isset( $settings['ldrj_hbda_manual_items'] ) && is_array( $settings['ldrj_hbda_manual_items'] ) ? $settings['ldrj_hbda_manual_items'] : array();
		$items        = array();

		foreach ( $manual_items as $item ) {
			$title   = isset( $item['ldrj_hbda_item_title'] ) ? sanitize_text_field( wp_strip_all_tags( $item['ldrj_hbda_item_title'] ) ) : '';
			$content = isset( $item['ldrj_hbda_item_content'] ) ? (string) $item['ldrj_hbda_item_content'] : '';

			if ( '' === $title && '' === trim( wp_strip_all_tags( $content ) ) ) {
				continue;
			}

			$items[] = array(
				'title'              => $title,
				'content'            => $content,
				'url'                => '',
				'read_more_label'    => '',
				'read_more_new_tab'  => 'no',
				'read_more_nofollow' => 'no',
			);
		}

		return $items;
	}

	/**
	 * Generate accordion items from dynamic post query.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 *
	 * @return array<int,array<string,string>>
	 */
	private function ldrj_hbda_get_dynamic_items( array $settings ): array {
		$posts_per_page = isset( $settings['ldrj_hbda_posts_per_page'] ) ? absint( $settings['ldrj_hbda_posts_per_page'] ) : 6;
		$offset         = isset( $settings['ldrj_hbda_offset'] ) ? absint( $settings['ldrj_hbda_offset'] ) : 0;

		if ( $posts_per_page < 1 ) {
			$posts_per_page = 6;
		}

		return Query::ldrj_hbda_get_dynamic_items( $settings, $posts_per_page, $offset );
	}

	/**
	 * Build load-more runtime config for frontend markup.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param int                 $loaded_count Number of initially rendered items.
	 *
	 * @return array<string,mixed>
	 */
	private function ldrj_hbda_get_load_more_config( array $settings, int $loaded_count ): array {
		$config = array(
			'enabled'         => false,
			'mode'            => 'button',
			'offset'          => $loaded_count,
			'remaining'       => 0,
			'loading_text'    => esc_html__( 'Loading…', 'lancedesk-smart-dynamic-accordion' ),
			/* translators: %d: number of remaining accordion items. */
			'button_template' => esc_html__( 'Show %d more', 'lancedesk-smart-dynamic-accordion' ),
		);

		if ( ! isset( $settings['ldrj_hbda_source_mode'] ) || 'dynamic' !== $settings['ldrj_hbda_source_mode'] ) {
			return $config;
		}

		if ( empty( $settings['ldrj_hbda_enable_load_more'] ) || 'yes' !== $settings['ldrj_hbda_enable_load_more'] ) {
			return $config;
		}

		$total     = Query::ldrj_hbda_count_posts( $settings );
		$remaining = max( 0, $total - $loaded_count );

		if ( $remaining < 1 ) {
			return $config;
		}

		$config['enabled']   = true;
		$config['mode']      = isset( $settings['ldrj_hbda_load_more_mode'] ) && 'infinite_scroll' === $settings['ldrj_hbda_load_more_mode'] ? 'infinite_scroll' : 'button';
		$config['offset']    = $loaded_count;
		$config['remaining'] = $remaining;

		if ( ! empty( $settings['ldrj_hbda_load_more_loading_text'] ) ) {
			$config['loading_text'] = sanitize_text_field( (string) $settings['ldrj_hbda_load_more_loading_text'] );
		}

		if ( ! empty( $settings['ldrj_hbda_load_more_button_text'] ) ) {
			$config['button_template'] = sanitize_text_field( (string) $settings['ldrj_hbda_load_more_button_text'] );
		}

		return $config;
	}

	/**
	 * Format load-more button label with remaining count placeholder.
	 *
	 * @param string $template Button text template.
	 * @param int    $remaining Remaining item count.
	 *
	 * @return string
	 */
	private function ldrj_hbda_format_load_more_label( string $template, int $remaining ): string {
		if ( false !== strpos( $template, '%d' ) ) {
			/* translators: %d: number of remaining accordion items. */
			return sprintf( $template, max( 0, $remaining ) );
		}

		if ( $remaining > 0 ) {
			/* translators: %d: number of remaining accordion items. */
			return $template . ' (' . sprintf( esc_html__( '%d remaining', 'lancedesk-smart-dynamic-accordion' ), $remaining ) . ')';
		}

		return $template;
	}

	/**
	 * Build sanitized settings payload for AJAX load-more requests.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 *
	 * @return array<string,mixed>
	 */
	private function ldrj_hbda_get_ajax_settings_payload( array $settings ): array {
		$keys = array(
			'ldrj_hbda_post_type',
			'ldrj_hbda_posts_per_page',
			'ldrj_hbda_offset',
			'ldrj_hbda_order',
			'ldrj_hbda_orderby',
			'ldrj_hbda_include_ids',
			'ldrj_hbda_exclude_ids',
			'ldrj_hbda_category_terms',
			'ldrj_hbda_tag_terms',
			'ldrj_hbda_taxonomy_relation',
			'ldrj_hbda_taxonomy',
			'ldrj_hbda_tax_terms',
			'ldrj_hbda_tax_field',
			'ldrj_hbda_title_source',
			'ldrj_hbda_title_meta_key',
			'ldrj_hbda_content_source',
			'ldrj_hbda_meta_key',
			'ldrj_hbda_meta_value_format',
			'ldrj_hbda_show_read_more',
			'ldrj_hbda_read_more_text',
			'ldrj_hbda_read_more_new_tab',
			'ldrj_hbda_read_more_nofollow',
			'ldrj_hbda_load_more_batch',
			'ldrj_hbda_expand_icon',
			'ldrj_hbda_collapse_icon',
			'ldrj_hbda_icon_render_mode',
		);

		$payload = array();
		foreach ( $keys as $key ) {
			if ( array_key_exists( $key, $settings ) ) {
				$payload[ $key ] = $settings[ $key ];
			}
		}

		return self::ldrj_hbda_sanitize_ajax_settings( $payload );
	}

	/**
	 * Sanitize settings received through AJAX.
	 *
	 * @param array<string,mixed> $settings Raw settings.
	 *
	 * @return array<string,mixed>
	 */
	public static function ldrj_hbda_sanitize_ajax_settings( array $settings ): array {
		$sanitized = array(
			'ldrj_hbda_post_type'          => isset( $settings['ldrj_hbda_post_type'] ) ? sanitize_key( (string) $settings['ldrj_hbda_post_type'] ) : 'post',
			'ldrj_hbda_posts_per_page'     => isset( $settings['ldrj_hbda_posts_per_page'] ) ? absint( $settings['ldrj_hbda_posts_per_page'] ) : 6,
			'ldrj_hbda_offset'             => isset( $settings['ldrj_hbda_offset'] ) ? absint( $settings['ldrj_hbda_offset'] ) : 0,
			'ldrj_hbda_order'              => isset( $settings['ldrj_hbda_order'] ) && in_array( $settings['ldrj_hbda_order'], array( 'ASC', 'DESC' ), true ) ? $settings['ldrj_hbda_order'] : 'DESC',
			'ldrj_hbda_orderby'            => isset( $settings['ldrj_hbda_orderby'] ) ? sanitize_key( (string) $settings['ldrj_hbda_orderby'] ) : 'date',
			'ldrj_hbda_include_ids'        => isset( $settings['ldrj_hbda_include_ids'] ) ? sanitize_text_field( (string) $settings['ldrj_hbda_include_ids'] ) : '',
			'ldrj_hbda_exclude_ids'        => isset( $settings['ldrj_hbda_exclude_ids'] ) ? sanitize_text_field( (string) $settings['ldrj_hbda_exclude_ids'] ) : '',
			'ldrj_hbda_category_terms'     => array(),
			'ldrj_hbda_tag_terms'          => array(),
			'ldrj_hbda_taxonomy_relation'  => isset( $settings['ldrj_hbda_taxonomy_relation'] ) && 'OR' === $settings['ldrj_hbda_taxonomy_relation'] ? 'OR' : 'AND',
			'ldrj_hbda_taxonomy'           => isset( $settings['ldrj_hbda_taxonomy'] ) ? sanitize_key( (string) $settings['ldrj_hbda_taxonomy'] ) : '',
			'ldrj_hbda_tax_terms'          => isset( $settings['ldrj_hbda_tax_terms'] ) ? sanitize_text_field( (string) $settings['ldrj_hbda_tax_terms'] ) : '',
			'ldrj_hbda_tax_field'          => isset( $settings['ldrj_hbda_tax_field'] ) && in_array( $settings['ldrj_hbda_tax_field'], array( 'slug', 'term_id' ), true ) ? $settings['ldrj_hbda_tax_field'] : 'slug',
			'ldrj_hbda_title_source'       => isset( $settings['ldrj_hbda_title_source'] ) ? sanitize_key( (string) $settings['ldrj_hbda_title_source'] ) : 'title',
			'ldrj_hbda_title_meta_key'     => isset( $settings['ldrj_hbda_title_meta_key'] ) ? sanitize_key( (string) $settings['ldrj_hbda_title_meta_key'] ) : '',
			'ldrj_hbda_content_source'     => isset( $settings['ldrj_hbda_content_source'] ) ? sanitize_key( (string) $settings['ldrj_hbda_content_source'] ) : 'content',
			'ldrj_hbda_meta_key'           => isset( $settings['ldrj_hbda_meta_key'] ) ? sanitize_key( (string) $settings['ldrj_hbda_meta_key'] ) : '',
			'ldrj_hbda_meta_value_format'  => isset( $settings['ldrj_hbda_meta_value_format'] ) ? sanitize_key( (string) $settings['ldrj_hbda_meta_value_format'] ) : 'auto',
			'ldrj_hbda_show_read_more'     => isset( $settings['ldrj_hbda_show_read_more'] ) && 'yes' === $settings['ldrj_hbda_show_read_more'] ? 'yes' : '',
			'ldrj_hbda_read_more_text'     => isset( $settings['ldrj_hbda_read_more_text'] ) ? sanitize_text_field( (string) $settings['ldrj_hbda_read_more_text'] ) : esc_html__( 'Read more', 'lancedesk-smart-dynamic-accordion' ),
			'ldrj_hbda_read_more_new_tab'  => isset( $settings['ldrj_hbda_read_more_new_tab'] ) && 'yes' === $settings['ldrj_hbda_read_more_new_tab'] ? 'yes' : '',
			'ldrj_hbda_read_more_nofollow' => isset( $settings['ldrj_hbda_read_more_nofollow'] ) && 'yes' === $settings['ldrj_hbda_read_more_nofollow'] ? 'yes' : '',
			'ldrj_hbda_load_more_batch'    => isset( $settings['ldrj_hbda_load_more_batch'] ) ? absint( $settings['ldrj_hbda_load_more_batch'] ) : 0,
			'ldrj_hbda_icon_render_mode'   => isset( $settings['ldrj_hbda_icon_render_mode'] ) ? sanitize_key( (string) $settings['ldrj_hbda_icon_render_mode'] ) : 'auto',
		);

		if ( isset( $settings['ldrj_hbda_category_terms'] ) && is_array( $settings['ldrj_hbda_category_terms'] ) ) {
			foreach ( $settings['ldrj_hbda_category_terms'] as $term_key ) {
				$sanitized['ldrj_hbda_category_terms'][] = sanitize_text_field( (string) $term_key );
			}
		}

		if ( isset( $settings['ldrj_hbda_tag_terms'] ) && is_array( $settings['ldrj_hbda_tag_terms'] ) ) {
			foreach ( $settings['ldrj_hbda_tag_terms'] as $term_key ) {
				$sanitized['ldrj_hbda_tag_terms'][] = sanitize_text_field( (string) $term_key );
			}
		}

		if ( isset( $settings['ldrj_hbda_expand_icon'] ) && is_array( $settings['ldrj_hbda_expand_icon'] ) ) {
			$sanitized['ldrj_hbda_expand_icon'] = self::ldrj_hbda_sanitize_icon_setting( $settings['ldrj_hbda_expand_icon'] );
		}

		if ( isset( $settings['ldrj_hbda_collapse_icon'] ) && is_array( $settings['ldrj_hbda_collapse_icon'] ) ) {
			$sanitized['ldrj_hbda_collapse_icon'] = self::ldrj_hbda_sanitize_icon_setting( $settings['ldrj_hbda_collapse_icon'] );
		}

		return $sanitized;
	}

	/**
	 * Sanitize Elementor icon control values for AJAX rendering.
	 *
	 * @param array<string,mixed> $icon_setting Icon control value.
	 *
	 * @return array<string,string>
	 */
	private static function ldrj_hbda_sanitize_icon_setting( array $icon_setting ): array {
		$clean = array();

		if ( isset( $icon_setting['value'] ) ) {
			$clean['value'] = sanitize_text_field( (string) $icon_setting['value'] );
		}

		if ( isset( $icon_setting['library'] ) ) {
			$clean['library'] = sanitize_key( (string) $icon_setting['library'] );
		}

		return $clean;
	}

	/**
	 * Render selected Elementor icon setting with fallback text.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param array<string,mixed> $icon_setting Icon control value.
	 * @param string              $fallback Fallback character.
	 *
	 * @return string
	 */
	private function ldrj_hbda_render_icon_markup( array $settings, array $icon_setting, string $fallback ): string {
		$render_mode = isset( $settings['ldrj_hbda_icon_render_mode'] ) ? sanitize_key( (string) $settings['ldrj_hbda_icon_render_mode'] ) : 'auto';
		if ( 'text' === $render_mode ) {
			return '<span class="ldrj-hbda-icon-fallback">' . esc_html( $fallback ) . '</span>';
		}

		if ( empty( $icon_setting['value'] ) ) {
			return '<span class="ldrj-hbda-icon-fallback">' . esc_html( $fallback ) . '</span>';
		}

		ob_start();
		Icons_Manager::render_icon(
			$icon_setting,
			array(
				'aria-hidden' => 'true',
			)
		);
		$markup = ob_get_clean();

		if ( ! is_string( $markup ) || '' === trim( $markup ) ) {
			return '<span class="ldrj-hbda-icon-fallback">' . esc_html( $fallback ) . '</span>';
		}

		return $markup;
	}

	/**
	 * Build safe link attributes for optional read more link.
	 *
	 * @param array<string,string> $item Accordion item.
	 *
	 * @return string
	 */
	private function ldrj_hbda_build_link_attributes( array $item ): string {
		$attributes = '';
		$rel_parts  = array();

		if ( isset( $item['read_more_new_tab'] ) && 'yes' === $item['read_more_new_tab'] ) {
			$attributes .= ' target="_blank"';
			$rel_parts[] = 'noopener';
		}

		if ( isset( $item['read_more_nofollow'] ) && 'yes' === $item['read_more_nofollow'] ) {
			$rel_parts[] = 'nofollow';
		}

		if ( ! empty( $rel_parts ) ) {
			$attributes .= ' rel="' . esc_attr( implode( ' ', array_unique( $rel_parts ) ) ) . '"';
		}

		return $attributes;
	}

	/**
	 * Output FAQPage schema for accordion items.
	 *
	 * @param array<int,array<string,string>> $items Widget items.
	 * @param array<string,mixed>             $settings Widget settings.
	 *
	 * @return void
	 */
	private function ldrj_hbda_render_faq_schema( array $items, array $settings ): void {
		$entities              = array();
		$min_items             = isset( $settings['ldrj_hbda_faq_min_items'] ) ? absint( $settings['ldrj_hbda_faq_min_items'] ) : 2;
		$require_question_mark = isset( $settings['ldrj_hbda_faq_require_question_mark'] ) && 'yes' === $settings['ldrj_hbda_faq_require_question_mark'];

		if ( $min_items < 1 ) {
			$min_items = 1;
		}

		foreach ( $items as $item ) {
			$question = isset( $item['title'] ) ? sanitize_text_field( $item['title'] ) : '';
			$answer   = isset( $item['content'] ) ? trim( wp_strip_all_tags( (string) $item['content'] ) ) : '';

			if ( $require_question_mark && false === strpos( $question, '?' ) ) {
				continue;
			}

			if ( '' === $question || '' === $answer ) {
				continue;
			}

			$entities[] = array(
				'@type'          => 'Question',
				'name'           => $question,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $answer,
				),
			);
		}

		if ( count( $entities ) < $min_items ) {
			return;
		}

		$schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $entities,
		);

		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
	}

	/**
	 * Build post-type options for control select.
	 *
	 * @return array<string,string>
	 */
	private function ldrj_hbda_get_post_type_options(): array {
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		$options = array();

		foreach ( $post_types as $slug => $post_type ) {
			$options[ $slug ] = $post_type->labels->singular_name;
		}

		return $options;
	}
}
