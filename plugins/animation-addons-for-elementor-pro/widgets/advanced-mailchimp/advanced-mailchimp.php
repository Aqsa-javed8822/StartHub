<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Mailchimp
 *
 * Elementor widget for mailchimp.
 *
 * @since 1.0.0
 */
class Advanced_Mailchimp extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'aae--advanced-mailchimp';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'Advanced Mailchimp', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'wcf eicon-mailchimp';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_style_depends() {
		return [ 'wcf--mailchimp' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_script_depends() {
		return [ 'wcf--mailchimp' ];
	}

	public function get_field_option() {
		$options        = get_option( 'aae_addon_mailchimp_form_field' ) ? (array) get_option( 'aae_addon_mailchimp_form_field' ) : [];
		$return_options = [];
		foreach ( $options as $option ) {
			if(is_array($option)){
				$return_options[ $option['type'] . '-' . $option['merge_id'] ] = $option['name'];
			}else{
				$return_options[ $option->type . '-' . $option->merge_id ] = $option->name;
			}
			
		}

		return $return_options;
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_mailchimp_controls();

		$this->register_form_content_controls();

		$this->register_layout_style();

		$this->register_label_style_controls();

		$this->register_input_style_controls();

		$this->register_email_style_controls();

		$this->register_select_style_controls();

		$this->register_radio_style_controls();

		$this->register_button_style_controls();
	}

	protected function register_mailchimp_controls() {
		$this->start_controls_section(
			'_section_mailchimp',
			[
				'label' => __( 'MailChimp', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'mailchimp_api',
			[
				'label'       => __( 'MailChimp API', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Enter your mailchimp api here', 'animation-addons-for-elementor-pro' ),
				'dynamic'     => [ 'active' => true ],
				'default'     => get_option( 'aae_mailchimp_api' )
			]
		);

		$this->add_control(
			'mailchimp_lists',
			[
				'label'       => __( 'Audience', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => false,
				'placeholder' => 'Choose your created audience ',
				'options'     => [],
				'description' => esc_html__( 'Create a audience/ list in mailchimp account ', 'animation-addons-for-elementor-pro' ) . '<a href="https://mailchimp.com/help/create-audience/" target="_blank"> ' . esc_html__( 'Create Audience', 'animation-addons-for-elementor-pro' ) . '</a>',
			]
		);

		$this->add_control(
			'mailchimp_list_tags',
			[
				'label'       => __( 'Tags', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Tag-1, Tag-2', 'animation-addons-for-elementor-pro' ),
				'description' => __( 'Enter tag here to separate your subscribers. Use comma separator to use multiple tags. Example: Tag-1, Tag-2, Tag-3', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'mailchimp_lists!' => '',
				],
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'enable_double_opt_in',
			[
				'label'        => __( 'Enable Double Opt In?', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
				'label_off'    => __( 'No', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->end_controls_section();
	}

	protected function register_layout_style() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'flex_wrap',
			[
				'label'     => esc_html__( 'Flex Wrap', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'wrap',
				'options'   => [
					'wrap'   => esc_html__( 'Wrap', 'animation-addons-for-elementor-pro' ),
					'nowrap' => esc_html__( 'No Wrap', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-mailchimp-form' => 'flex-wrap: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'col_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mailchimp-form' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'      => esc_html__( 'Row Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mailchimp-form' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_align',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
					'space-between'  => [
						'title' => esc_html__( 'Space Between', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .wcf-mailchimp-form' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_form_content_controls() {
				$this->start_controls_section(
			'_section_mailchimp_form_message',
			[
				'label' => esc_html__('Message', 'animation-addons-for-elementor-pro'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'confirmation_message',
			[
				'label'       => esc_html__('Confirmation Message', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__('Please confirm your subscription via the link sent to your email.', 'animation-addons-for-elementor-pro'),
				'placeholder' => esc_html__('Please confirm your subscription via the link sent to your email.', 'animation-addons-for-elementor-pro'),
				'dynamic'     => ['active' => true],
				'frontend_available' => true,
				'render_type' => 'none',
			]			
		);
		
		$this->add_control(
			'success_message',
			[
				'label'       => esc_html__('Success Message', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__('Thank you for subscribing!', 'animation-addons-for-elementor-pro'),
				'placeholder' => esc_html__('Thank you for subscribing!', 'animation-addons-for-elementor-pro'),
				'dynamic'     => ['active' => true],
				'frontend_available' => true,	
				'render_type' => 'none',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'_section_mailchimp_form',
			[
				'label' => esc_html__( 'Form', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// Email
		$this->add_control(
			'_email_heading',
			[
				'label'     => esc_html__( 'Email:', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_label',
			[
				'label'       => esc_html__( 'Label', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Email', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Email', 'animation-addons-for-elementor-pro' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'email_placeholder',
			[
				'label'       => esc_html__( 'Placeholder', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Email', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Email input placeholder', 'animation-addons-for-elementor-pro' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		// Additional Fields
		$repeater = new Repeater();

		$repeater->add_control(
			'field_label',
			[
				'label'       => esc_html__( 'Field Label', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'First Name', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label'       => esc_html__( 'Placeholder', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Placeholder', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'field_type',
			[
				'label'       => esc_html__( 'Field Type', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => false,
				'default'     => '',
				'show_label'  => false,
				'options'     => $this->get_field_option(),

			]
		);

		$this->add_control(
			'additional_fields',
			[
				'label'         => esc_html__( 'Additional Fields', 'animation-addons-for-elementor-pro' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ field_label }}}',
				'prevent_empty' => false,
				'separator'     => 'before',
			]
		);


		// Button
		$this->add_control(
			'_button_heading',
			[
				'label'     => esc_html__( 'Button:', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Subscribe', 'animation-addons-for-elementor-pro' ),
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label'       => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-check',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'button_icon_position',
			[
				'label'   => esc_html__( 'Icon Position', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before' => esc_html__( 'Before', 'animation-addons-for-elementor-pro' ),
					'after'  => esc_html__( 'After', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'button_icon_spacing',
			[
				'label'      => esc_html__( 'Icon Spacing', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mc-button' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_label_style_controls() {
		$this->start_controls_section(
			'_section_style_input_label',
			[
				'label' => esc_html__( 'Label', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_label',
			[
				'label'     => esc_html__( 'Show Label', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'inline-block',
				'options'   => [
					'inline-block' => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
					'none'         => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf--mailchimp label:not(.aae-radio label)' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_label_typography',
				'label'    => esc_html__( 'Typography', 'animation-addons-for-elementor-pro' ),
				'selector' => '{{WRAPPER}} label',
			]
		);

		$this->add_control(
			'input_label_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'input_label_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} > label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_input_style_controls() {
		$this->start_controls_section(
			'_section_input_style',
			[
				'label' => esc_html__( 'Input', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .input input, {{WRAPPER}} .input select' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typography',
				'label'    => esc_html__( 'Typography', 'animation-addons-for-elementor-pro' ),
				'selector' => '{{WRAPPER}} .input input, {{WRAPPER}} .input select',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'input_background',
				'label'    => esc_html__( 'Background', 'animation-addons-for-elementor-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .input input, {{WRAPPER}} .input select',
				'exclude'  => [
					'image',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .input input, {{WRAPPER}} .input select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .input-wrapper' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'input_border',
				'label'     => esc_html__( 'Border', 'animation-addons-for-elementor-pro' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .input input, {{WRAPPER}} .input select',
			]
		);

		$this->add_responsive_control(
			'input_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .input input, {{WRAPPER}} .input select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'input_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'animation-addons-for-elementor-pro' ),
				'selector' => '{{WRAPPER}} .input input, {{WRAPPER}} .input input:focus, {{WRAPPER}} .input select',
			]
		);

		//placeholder
		$this->add_control(
			'input_placeholder_heading',
			[
				'label'     => esc_html__( 'Placeholder', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .input input::-webkit-input-placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} .input input::-moz-placeholder'          => 'color: {{VALUE}}',
					'{{WRAPPER}} .input input:-ms-input-placeholder'      => 'color: {{VALUE}}',
					'{{WRAPPER}} .input input:-moz-placeholder'           => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'input_placeholder_font_size',
			[
				'label'      => esc_html__( 'Font Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .input input::-webkit-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .input input::-moz-placeholder'          => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .input input:-ms-input-placeholder'      => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .input input:-moz-placeholder'           => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_email_style_controls() {
		$this->start_controls_section(
			'_section_email_style',
			[
				'label' => esc_html__( 'Email', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'email_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .input-wrapper.aae-email' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_select_style_controls() {
		$this->start_controls_section(
			'_section_select_style',
			[
				'label' => esc_html__( 'Select', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'select_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .input select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'select_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .input-wrapper.aae-dropdown' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_radio_style_controls() {
		$this->start_controls_section(
			'_section_radio_style',
			[
				'label' => esc_html__( 'Radio', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'radio_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .input-wrapper.aae-radio' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'radio_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .input-wrapper.aae-radio' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'radio_slide_width',
			[
				'label'      => esc_html__( 'Slide Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-radio .input' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'radio_slide_height',
			[
				'label'      => esc_html__( 'Slide Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-radio .input' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_button_style_controls() {
		$this->start_controls_section(
			'_section_button_style',
			[
				'label' => esc_html__( 'Button', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'animation-addons-for-elementor-pro' ),
				'selector' => '{{WRAPPER}} .wcf-mc-button',
			]
		);

		$this->add_responsive_control(
			'button_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mc-button i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wcf-mc-button svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'button_normal_and_hover_tabs'
		);

		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-mc-button' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'label'    => esc_html__( 'Background', 'animation-addons-for-elementor-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wcf-mc-button',
				'exclude'  => [
					'image',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-mc-button:hover, {{WRAPPER}} .wcf-mc-button:focus' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background_hover',
				'label'    => esc_html__( 'Background', 'animation-addons-for-elementor-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wcf-mc-button:hover, {{WRAPPER}} .wcf-mc-button:focus',
				'exclude'  => [
					'image',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-mc-button:hover, {{WRAPPER}} .wcf-mc-button:focus' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'separator' => 'before',
				'label'     => esc_html__( 'Border', 'animation-addons-for-elementor-pro' ),
				'selector'  => '{{WRAPPER}} .wcf-mc-button',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'animation-addons-for-elementor-pro' ),
				'selector' => '{{WRAPPER}} .wcf-mc-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mc-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mc-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_transition',
			[
				'label'      => esc_html__( 'Button Transition', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 's', '%' ],
				'range'      => [
					's' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-mc-button' => 'transition: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$additional_fields = $settings['additional_fields'];

		$this->add_render_attribute( 'wrapper', 'class', 'wcf--mailchimp' );

		$this->add_render_attribute( 'wcf-mailchimp-form', [
			'class'           => 'wcf-mailchimp-form wcf--form-wrapper',
			'data-key'        => ! empty( $settings['mailchimp_api'] ) ? base64_encode( 'w1c2f' . get_option( 'aae_mailchimp_api' ) . 'w1c2f' ) : '',
			'data-list-id'    => ! empty( $settings['mailchimp_lists'] ) ? ltrim( $settings['mailchimp_lists'] ) : '',
			'data-double-opt' => ! empty( $settings['enable_double_opt_in'] ) ? $settings['enable_double_opt_in'] : '',
			'data-list-tags'  => ! empty( $settings['mailchimp_list_tags'] ) ? $settings['mailchimp_list_tags'] : '',
		] );

		$maichimp_all_fields = get_option( 'aae_addon_mailchimp_form_field' ) ? (array) get_option( 'aae_addon_mailchimp_form_field' ) : [];

		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="mailchimp-response-message"></div>
            <form <?php $this->print_render_attribute_string( 'wcf-mailchimp-form' ); ?>>
                <div class="input-wrapper aae-email">
                    <label for="email"><?php echo esc_attr( $settings['email_label'] ); ?></label>
                    <div class="input">
                        <input type="email" name="email" id="email" class="aae-email email"
                               placeholder="<?php echo esc_attr( $settings['email_placeholder'] ); ?>">
                        <input type="hidden" name="advanced-mailchimp" value="1">
                    </div>
                </div>
				<?php
				foreach ( $additional_fields as $item ) {
					$field = $this->findFieldType( $maichimp_all_fields, $item['field_type'] );

					if ( is_object( $field ) ) {

						if ( $field->type == 'phone' ) {
							$this->render_phone( $field, $item );
						} elseif ( $field->type == 'text' ) {
							$this->render_text_input( $field, $item );
						} elseif ( $field->type == 'radio' ) {
							$this->render_radio( $field, $item );
						} elseif ( $field->type == 'birthday' ) {
							$this->render_birthday_input( $field, $item );
						} elseif ( $field->type == 'address' ) {
							$this->render_address_input( $field, $item );
						} elseif ( $field->type == 'dropdown' ) {
							$this->render_dropdown_input( $field, $item );
						}
					}elseif ( is_array( $field ) ) {
						if ( $field['type'] == 'phone' ) {
							$this->render_phone( $field, $item );
						} elseif ( $field['type'] == 'text' ) {
							$this->render_text_input( $field, $item );
						} elseif ( $field['type'] == 'radio' ) {
							$this->render_radio( $field, $item );
						} elseif ( $field['type'] == 'birthday' ) {
							$this->render_birthday_input( $field, $item );
						} elseif ( $field['type'] == 'address' ) {
							$this->render_address_input( $field, $item );
						} elseif ( $field['type'] == 'dropdown' ) {
							$this->render_dropdown_input( $field, $item );
						}
					}

				}

				// Button
				$this->render_submit_button( $settings );
				?>
            </form>
        </div>
		<?php
	}

	public function findFieldType( $arr, $key ) {
		$found  = null;
		$search = '';
		$expkey = explode( '-', $key );
		if ( isset( $expkey[1] ) ) {
			$search = $expkey[1];
		}
		foreach ( $arr as $item ) {
			if(is_array($item)){
				if ( strtolower( $item['merge_id'] ) === strtolower( $search ) ) {
					$found = $item;
					break;
				}
			}else{
				if ( strtolower( $item->merge_id ) === strtolower( $search ) ) {
					$found = $item;
					break;
				}	
			}			
		}

		return $found;
	}

	protected function render_icon( $icon, $settings ) {
		if ( empty( $settings[ $icon ]['value'] ) ) {
			return;
		}
		?>
        <div class="icon">
			<?php Icons_Manager::render_icon( $settings[ $icon ], [ 'aria-hidden' => 'true' ] ); ?>
        </div>
		<?php
	}

	protected function render_submit_button( $settings ) {
		$this->add_render_attribute( 'button_wrapper',
			[
				'class' => 'wcf-mc-button',
				'type'  => 'submit',
				'name'  => 'wcf-mailchimp',
			]
		);

		if ( 'after' === $settings['button_icon_position'] ) {
			$this->add_render_attribute( 'button_wrapper', 'class', 'icon-position-after' );
		}
		?>
        <button <?php $this->print_render_attribute_string( 'button_wrapper' ); ?>
                aria-label="<?php echo esc_html__( 'Mailchimp Button', 'animation-addons-for-elementor-pro' ); ?>">
			<?php
			Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] );
			$this->print_unescaped_setting( 'button_text' );
			?>
        </button>
		<?php
	}

	protected function render_text_input( $settings, $item ) {
		
		if(is_array($settings)){
			$name        = isset( $settings['tag'] ) ? $settings['tag'] : 'text_input';
			$id          = isset( $settings['tag'] ) ? $settings['tag'] : 'text_field';
			$class       = isset( $settings['tag'] ) ? strtolower( $settings['tag'] ) : 'input-class';
		}else{
			$name        = isset( $settings->tag ) ? $settings->tag : 'text_input';
			$id          = isset( $settings->tag ) ? $settings->tag : 'text_field';
			$class       = isset( $settings->tag ) ? strtolower( $settings->tag ) : 'input-class';	
		}
		$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';
		$label       = isset( $item['field_label'] ) ? $item['field_label'] : '';
		
		?>
        <div class="input-wrapper aae-text">
            <label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_attr( $label ); ?></label>
            <div class="input">
                <input type="text"
                       name="<?php echo esc_attr( $name ); ?>"
                       id="<?php echo esc_attr( $id ); ?>"
                       class="<?php echo esc_attr( $class ); ?>"
                       placeholder="<?php echo esc_attr( $placeholder ); ?>">
            </div>
        </div>
		<?php
	}

	protected function render_birthday_input( $settings, $item ) {
		if(is_array($settings)){
			$name        = isset( $settings['tag'] ) ? $settings['tag'] : 'text_input';
			$id          = isset( $settings['tag'] ) ? $settings['tag'] : 'text_field';
			$class       = isset( $settings['tag'] ) ? strtolower( $settings['tag'] ) : 'input-class';
		}else{
			$name        = isset( $settings->tag ) ? $settings->tag : 'text_input';
			$id          = isset( $settings->tag ) ? $settings->tag : 'text_field';
			$class       = isset( $settings->tag ) ? strtolower( $settings->tag ) : 'input-class';
		}
		$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';
		$label       = isset( $item['field_label'] ) ? $item['field_label'] : '';
		?>
        <div class="input-wrapper aae-birthday">
            <label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_attr( $label ); ?></label>
            <div class="input">
                <input type="text"
                       name="<?php echo esc_attr( $name ); ?>"
                       id="<?php echo esc_attr( $id ); ?>"
                       class="<?php echo esc_attr( $class ); ?>"
                       placeholder="<?php echo esc_attr( $placeholder ); ?>">
            </div>
        </div>
		<?php
	}

	protected function render_dropdown_input( $settings, $item ) {
		
		if(is_array($settings)){
			$name        = isset( $settings['tag'] ) ? $settings['tag'] : 'text_input';
			$id          = isset( $settings['tag'] ) ? $settings['tag'] : 'text_field';
			$class       = isset( $settings['tag'] ) ? strtolower( $settings['tag'] ) : 'input-class';
		}else{
			$name        = isset( $settings->tag ) ? $settings->tag : 'text_input';
			$id          = isset( $settings->tag ) ? $settings->tag : 'text_field';
			$class       = isset( $settings->tag ) ? strtolower( $settings->tag ) : 'input-class';
		}
		$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';
		$label       = isset( $item['field_label'] ) ? $item['field_label'] : '';

		$options = isset( $settings->options->choices ) && is_array( $settings->options->choices ) ? $settings->options->choices : [
			'Choice One',
			'Choice Two'
		];
		?>
        <div class="input-wrapper aae-dropdown">
            <label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_attr( $label ); ?></label>

            <div class="input">
                <select class="<?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $id ); ?>"
                        name="<?php echo esc_attr( $name ); ?>">
					<?php foreach ( $options as $index => $option ) { ?>
                        <option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_attr( $option ); ?></option>
					<?php } ?>
                </select>
            </div>
        </div>
		<?php
	}

	protected function render_address_input( $settings, $item ) {
	
		if(is_array($settings)){
			$name        = isset( $settings['tag'] ) ? $settings['tag'] : 'text_input';
			$id          = isset( $settings['tag'] ) ? $settings['tag'] : 'text_field';
			$class       = isset( $settings['tag'] ) ? strtolower( $settings['tag'] ) : 'input-class';
		}else{
			$name        = isset( $settings->tag ) ? $settings->tag : 'text_input';
			$id          = isset( $settings->tag ) ? $settings->tag : 'text_field';
			$class       = isset( $settings->tag ) ? strtolower( $settings->tag ) : 'input-class';
		}
		$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';
		$label       = isset( $item['field_label'] ) ? $item['field_label'] : '';
		?>
        <div class="input-wrapper aae-address">
            <label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_attr( $label ); ?></label>
            <div class="input">
                <input type="text"
                       name="<?php echo esc_attr( $name ); ?>"
                       id="<?php echo esc_attr( $id ); ?>"
                       class="<?php echo esc_attr( $class ); ?>"
                       placeholder="<?php echo esc_attr( $placeholder ); ?>">
            </div>
        </div>
		<?php
	}

	protected function render_phone( $settings, $item ) {

		if(is_array($settings)){
			$name        = isset( $settings['tag'] ) ? $settings['tag'] : 'phone';
			$id          = isset( $settings['tag'] ) ? $settings['tag'] : 'phone_field';
			$class       = isset( $settings['tag'] ) ? strtolower( $settings['tag'] ) : 'phone';
		}else{
			$name        = isset( $settings->tag ) ? $settings->tag : 'phone';
			$id          = isset( $item['id'] ) ? $item['id'] : 'phone_field';
			$class       = strtolower( isset( $settings->tag ) ? $settings->tag : 'phone' );
		}
		
		$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';
		$label       = isset( $item['field_label'] ) ? $item['field_label'] : '';
		?>

        <div class="input-wrapper aae-address">
            <label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_attr( $label ); ?></label>
			<?php
			echo sprintf(
				'<div class="input">
            <input type="tel" name="%s" id="%s" class="%s aae-phone" placeholder="%s">
        </div>',
				esc_attr( $name ),
				esc_attr( $id ),
				esc_attr( $class ),
				esc_attr( $placeholder )
			);
			?>
        </div>
		<?php
	}

	protected function render_radio( $settings, $item ) {
		
		if(is_array($settings)){
			$name        = isset( $settings['tag'] ) ? $settings['tag'] : 'radio';
			$id          = isset( $settings['tag'] ) ? $settings['tag'] : 'radio';
			$class       = isset( $settings['tag'] ) ? strtolower( $settings['tag'] ) : 'radio_field';
		}else{
			$name  = isset( $settings->tag ) ? $settings->tag : 'radio';
			$class = strtolower( isset( $settings->tag ) ? $settings->tag : 'radio' );
			$id    = isset( $item['id'] ) ? $item['id'] : 'radio_field';
		}

		$options = isset( $settings->options->choices ) && is_array( $settings->options->choices ) ? $settings->options->choices : [
			'Yes',
			'No'
		];
		$label   = isset( $item['field_label'] ) ? $item['field_label'] : '';
		?>
        <div class="input-wrapper aae-radio">
            <label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_attr( $label ); ?></label>
            <div class="input">
				<?php foreach ( $options as $index => $option ) : ?>
					<?php $option_id = $id . '_' . $index; ?>
                    <label for="<?php echo esc_attr( $option_id ); ?>">
                        <input type="radio" name="<?php echo esc_attr( $name ); ?>"
                               id="<?php echo esc_attr( $option_id ); ?>"
                               class="<?php echo esc_attr( $class ); ?>"
                               value="<?php echo esc_attr( $option ); ?>">
						<?php echo esc_html( $option ); ?>
                    </label>
				<?php endforeach; ?>
            </div>
        </div>
		<?php
	}
}
