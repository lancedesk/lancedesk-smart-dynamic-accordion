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
					'after' => array(
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
				'label'       => esc_html__( 'Expand Icon', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'eicon-plus',
					'library' => 'eicons',
				),
				'recommended' => array(
					'eicons' => array(
						'plus',
						'chevron-down',
						'arrow-down',
					),
					'fa-solid' => array(
						'plus',
						'chevron-down',
						'caret-down',
					),
				),
			)
		);

		$this->add_control(
			'ldrj_hbda_collapse_icon',
			array(
				'label'       => esc_html__( 'Collapse Icon', 'lancedesk-smart-dynamic-accordion' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'eicon-minus',
					'library' => 'eicons',
				),
				'recommended' => array(
					'eicons' => array(
						'minus',
						'chevron-up',
						'arrow-up',
					),
					'fa-solid' => array(
						'minus',
						'times',
						'chevron-up',
					),
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
			'ldrj_hbda_taxonomy',
			array(
				'label'       => esc_html__( 'Taxonomy Slug', 'lancedesk-smart-dynamic-accordion' ),
				'description' => esc_html__( 'Example: category, post_tag, product_cat', 'lancedesk-smart-dynamic-accordion' ),
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
				'label'       => esc_html__( 'Taxonomy Terms', 'lancedesk-smart-dynamic-accordion' ),
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
				'label'     => esc_html__( 'Taxonomy Term Type', 'lancedesk-smart-dynamic-accordion' ),
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
			'ldrj_hbda_border_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'lancedesk-smart-dynamic-accordion' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#acb2cf',
				'selectors' => array(
					'{{WRAPPER}} .ldrj-hbda-item' => 'border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
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
					'{{WRAPPER}} .ldrj-hbda-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ldrj-hbda-icon svg' => 'stroke: currentColor; stroke-width: {{SIZE}}{{UNIT}}; paint-order: stroke fill;',
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

		$wrapper_classes = 'ldrj-hbda-accordion ldrj-hbda-icon-' . $icon_pos;
		if ( $multi_open ) {
			$wrapper_classes .= ' ldrj-hbda-multi-open';
		}

		echo '<div class="' . esc_attr( $wrapper_classes ) . '" data-multi-open="' . esc_attr( $multi_open ? 'yes' : 'no' ) . '">';

		foreach ( $items as $index => $item ) {
			$item_id     = 'ldrj-hbda-item-' . esc_attr( $this->get_id() . '-' . $index );
			$is_expanded = $open_first && 0 === (int) $index;
			$item_class  = 'ldrj-hbda-item';

			if ( $is_expanded ) {
				$item_class .= ' is-open';
			}

			echo '<div class="' . esc_attr( $item_class ) . '">';
			echo '<button type="button" class="ldrj-hbda-trigger" aria-expanded="' . esc_attr( $is_expanded ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $item_id ) . '">';
			echo '<span class="ldrj-hbda-trigger-text">' . esc_html( $item['title'] ) . '</span>';
			echo '<span class="ldrj-hbda-icon" aria-hidden="true">';
			echo '<span class="ldrj-hbda-icon-expand">' . $this->ldrj_hbda_render_icon_markup( $settings['ldrj_hbda_expand_icon'] ?? array(), '+' ) . '</span>';
			echo '<span class="ldrj-hbda-icon-collapse">' . $this->ldrj_hbda_render_icon_markup( $settings['ldrj_hbda_collapse_icon'] ?? array(), '−' ) . '</span>';
			echo '</span>';
			echo '</button>';
			echo '<div id="' . esc_attr( $item_id ) . '" class="ldrj-hbda-content-wrap" ' . ( $is_expanded ? '' : 'hidden' ) . '>';
			echo '<div class="ldrj-hbda-content">' . wp_kses_post( $item['content'] ) . '</div>';
			if ( ! empty( $item['url'] ) ) {
				echo '<a class="ldrj-hbda-read-more" href="' . esc_url( $item['url'] ) . '"' . $this->ldrj_hbda_build_link_attributes( $item ) . '>' . esc_html( $item['read_more_label'] ) . '</a>';
			}
			echo '</div>';
			echo '</div>';
		}

		echo '</div>';

		if ( $faq_schema ) {
			$this->ldrj_hbda_render_faq_schema( $items, $settings );
		}
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
				'title'           => $title,
				'content'         => $content,
				'url'             => '',
				'read_more_label' => '',
				'read_more_new_tab' => 'no',
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
		$post_type      = isset( $settings['ldrj_hbda_post_type'] ) ? sanitize_key( $settings['ldrj_hbda_post_type'] ) : 'post';
		$posts_per_page = isset( $settings['ldrj_hbda_posts_per_page'] ) ? absint( $settings['ldrj_hbda_posts_per_page'] ) : 6;
		$order          = isset( $settings['ldrj_hbda_order'] ) && in_array( $settings['ldrj_hbda_order'], array( 'ASC', 'DESC' ), true ) ? $settings['ldrj_hbda_order'] : 'DESC';
		$orderby        = isset( $settings['ldrj_hbda_orderby'] ) ? sanitize_key( $settings['ldrj_hbda_orderby'] ) : 'date';
		$title_source   = isset( $settings['ldrj_hbda_title_source'] ) ? sanitize_key( $settings['ldrj_hbda_title_source'] ) : 'title';
		$title_meta_key = isset( $settings['ldrj_hbda_title_meta_key'] ) ? sanitize_key( $settings['ldrj_hbda_title_meta_key'] ) : '';
		$content_source = isset( $settings['ldrj_hbda_content_source'] ) ? sanitize_key( $settings['ldrj_hbda_content_source'] ) : 'content';
		$meta_key       = isset( $settings['ldrj_hbda_meta_key'] ) ? sanitize_key( $settings['ldrj_hbda_meta_key'] ) : '';
		$meta_format    = isset( $settings['ldrj_hbda_meta_value_format'] ) ? sanitize_key( $settings['ldrj_hbda_meta_value_format'] ) : 'auto';
		$offset         = isset( $settings['ldrj_hbda_offset'] ) ? absint( $settings['ldrj_hbda_offset'] ) : 0;
		$include_ids    = isset( $settings['ldrj_hbda_include_ids'] ) ? $this->ldrj_hbda_parse_csv_ids( (string) $settings['ldrj_hbda_include_ids'] ) : array();
		$exclude_ids    = isset( $settings['ldrj_hbda_exclude_ids'] ) ? $this->ldrj_hbda_parse_csv_ids( (string) $settings['ldrj_hbda_exclude_ids'] ) : array();
		$taxonomy       = isset( $settings['ldrj_hbda_taxonomy'] ) ? sanitize_key( $settings['ldrj_hbda_taxonomy'] ) : '';
		$tax_terms_raw  = isset( $settings['ldrj_hbda_tax_terms'] ) ? (string) $settings['ldrj_hbda_tax_terms'] : '';
		$tax_field      = isset( $settings['ldrj_hbda_tax_field'] ) && in_array( $settings['ldrj_hbda_tax_field'], array( 'slug', 'term_id' ), true ) ? $settings['ldrj_hbda_tax_field'] : 'slug';
		$show_read_more = isset( $settings['ldrj_hbda_show_read_more'] ) && 'yes' === $settings['ldrj_hbda_show_read_more'];
		$read_more_text = isset( $settings['ldrj_hbda_read_more_text'] ) ? sanitize_text_field( $settings['ldrj_hbda_read_more_text'] ) : esc_html__( 'Read more', 'lancedesk-smart-dynamic-accordion' );
		$read_more_new_tab = isset( $settings['ldrj_hbda_read_more_new_tab'] ) && 'yes' === $settings['ldrj_hbda_read_more_new_tab'];
		$read_more_nofollow = isset( $settings['ldrj_hbda_read_more_nofollow'] ) && 'yes' === $settings['ldrj_hbda_read_more_nofollow'];

		$allowed_orderby = array( 'date', 'title', 'menu_order', 'rand' );
		if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
			$orderby = 'date';
		}

		if ( $posts_per_page < 1 ) {
			$posts_per_page = 6;
		}

		$query_args = array(
			'post_type'              => $post_type,
			'post_status'            => 'publish',
			'posts_per_page'         => $posts_per_page,
			'orderby'                => $orderby,
			'order'                  => $order,
			'offset'                 => $offset,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		if ( ! empty( $include_ids ) ) {
			$query_args['post__in'] = $include_ids;
		}

		if ( ! empty( $exclude_ids ) ) {
			$query_args['post__not_in'] = $exclude_ids;
		}

		if ( '' !== $taxonomy && taxonomy_exists( $taxonomy ) && '' !== trim( $tax_terms_raw ) ) {
			if ( 'term_id' === $tax_field ) {
				$terms = $this->ldrj_hbda_parse_csv_ids( $tax_terms_raw );
			} else {
				$terms = $this->ldrj_hbda_parse_csv_slugs( $tax_terms_raw );
			}

			if ( ! empty( $terms ) ) {
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy,
						'field'    => $tax_field,
						'terms'    => $terms,
					),
				);
			}
		}

		$posts = get_posts( $query_args );
		$items = array();

		global $post;
		$original_post = $post;

		foreach ( $posts as $query_post ) {
			$post = $query_post;
			setup_postdata( $post );

			$title   = sanitize_text_field( get_the_title( $post ) );
			$content = '';

			if ( 'meta' === $title_source && '' !== $title_meta_key ) {
				$raw_title = get_post_meta( $post->ID, $title_meta_key, true );
				if ( is_scalar( $raw_title ) ) {
					$title = sanitize_text_field( (string) $raw_title );
				}
			}

			if ( 'excerpt' === $content_source ) {
				$raw_excerpt = has_excerpt( $post ) ? $post->post_excerpt : wp_trim_words( wp_strip_all_tags( (string) $post->post_content ), 35 );
				$content     = wpautop( esc_html( $raw_excerpt ) );
			} elseif ( 'meta' === $content_source && '' !== $meta_key ) {
				$raw_meta = get_post_meta( $post->ID, $meta_key, true );
				if ( is_scalar( $raw_meta ) ) {
					$content = $this->ldrj_hbda_format_meta_content( (string) $raw_meta, $meta_format );
				}
			} else {
				$content = apply_filters( 'the_content', get_the_content( null, false, $post ) );
			}

			if ( '' === $content && has_post_thumbnail( $post ) ) {
				$content = wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), 'large' );
			}

			if ( '' === $title && '' === trim( wp_strip_all_tags( $content ) ) ) {
				continue;
			}

			$items[] = array(
				'title'           => $title,
				'content'         => $content,
				'url'             => $show_read_more ? get_permalink( $post ) : '',
				'read_more_label' => $read_more_text,
				'read_more_new_tab' => $read_more_new_tab ? 'yes' : 'no',
				'read_more_nofollow' => $read_more_nofollow ? 'yes' : 'no',
			);
		}

		wp_reset_postdata();
		$post = $original_post;

		return $items;
	}

	/**
	 * Format dynamic meta value based on selected type.
	 *
	 * @param string $raw_value Raw meta value.
	 * @param string $meta_format Selected format.
	 *
	 * @return string
	 */
	private function ldrj_hbda_format_meta_content( string $raw_value, string $meta_format ): string {
		$value = trim( $raw_value );

		if ( '' === $value ) {
			return '';
		}

		if ( 'image_url' === $meta_format ) {
			$url = esc_url( $value );
			if ( '' !== $url ) {
				return '<img src="' . $url . '" alt="" loading="lazy" />';
			}
			return '';
		}

		if ( 'text' === $meta_format ) {
			return wpautop( esc_html( $value ) );
		}

		if ( 'html' === $meta_format ) {
			return wp_kses_post( $value );
		}

		// Auto mode detects images and html, then falls back to plain text.
		if ( wp_http_validate_url( $value ) && preg_match( '/\.(jpg|jpeg|png|gif|webp|avif|svg)(\?.*)?$/i', $value ) ) {
			$url = esc_url( $value );
			if ( '' !== $url ) {
				return '<img src="' . $url . '" alt="" loading="lazy" />';
			}
		}

		if ( $value !== wp_strip_all_tags( $value ) ) {
			return wp_kses_post( $value );
		}

		return wpautop( esc_html( $value ) );
	}

	/**
	 * Render selected Elementor icon setting with fallback text.
	 *
	 * @param array<string,mixed> $icon_setting Icon control value.
	 * @param string              $fallback Fallback character.
	 *
	 * @return string
	 */
	private function ldrj_hbda_render_icon_markup( array $icon_setting, string $fallback ): string {
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

		if ( ! is_string( $markup ) || '' === $markup ) {
			return '<span class="ldrj-hbda-icon-fallback">' . esc_html( $fallback ) . '</span>';
		}

		/*
		 * Frontend theme stacks occasionally miss icon-font assets, which makes
		 * <i class="eicon-..."> render as blank. Prefer SVG markup and fallback
		 * to text symbols when only font-icon markup is returned.
		 */
		if ( false === strpos( $markup, '<svg' ) ) {
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
	 * Parse comma-separated IDs safely.
	 *
	 * @param string $value Raw csv value.
	 *
	 * @return array<int,int>
	 */
	private function ldrj_hbda_parse_csv_ids( string $value ): array {
		$parts = explode( ',', $value );
		$ids   = array();

		foreach ( $parts as $part ) {
			$id = absint( trim( $part ) );
			if ( $id > 0 ) {
				$ids[] = $id;
			}
		}

		return array_values( array_unique( $ids ) );
	}

	/**
	 * Parse comma-separated slugs safely.
	 *
	 * @param string $value Raw csv value.
	 *
	 * @return array<int,string>
	 */
	private function ldrj_hbda_parse_csv_slugs( string $value ): array {
		$parts = explode( ',', $value );
		$out   = array();

		foreach ( $parts as $part ) {
			$slug = sanitize_title( trim( $part ) );
			if ( '' !== $slug ) {
				$out[] = $slug;
			}
		}

		return array_values( array_unique( $out ) );
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
		$entities = array();
		$min_items = isset( $settings['ldrj_hbda_faq_min_items'] ) ? absint( $settings['ldrj_hbda_faq_min_items'] ) : 2;
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
