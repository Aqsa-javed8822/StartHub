<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Advanced Button Widget
 *
 * Feature-rich button widget with hover animations, subtitle, and icon support.
 *
 * @since 2.7.0
 */
class Advanced_Button extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'aae--adv-button';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'Advance Button', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'wcf eicon-button';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return array( 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Get style dependencies.
	 */
	public function get_style_depends() {
		return array( 'aae--advanced-button' );
	}

	/**
	 * Get script dependencies.
	 */
	public function get_script_depends() {
		return array( 'aae--advanced-button' );
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		// Content Section
		$this->register_content_controls();
		// Style Sections
		$this->register_button_style_controls();
		$this->register_icon_style_controls();
		$this->register_subtitle_style_controls();
	}

	/**
	 * Content controls.
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Button', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->add_control(
			'btn_text',
			array(
				'label'   => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Click Here', 'animation-addons-for-elementor-pro' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'btn_subtitle',
			array(
				'label'   => esc_html__( 'Subtitle', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'btn_link',
			array(
				'label'   => esc_html__( 'Link', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::URL,
				'options' => array( 'url', 'is_external', 'nofollow' ),
				'default' => array(
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'btn_icon',
			array(
				'label'       => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->add_control(
			'btn_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'before' => esc_html__( 'Before', 'animation-addons-for-elementor-pro' ),
					'after'  => esc_html__( 'After', 'animation-addons-for-elementor-pro' ),
				),
				'condition' => array(
					'btn_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'btn_size',
			array(
				'label'     => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => esc_html__( 'Extra Small', 'animation-addons-for-elementor-pro' ),
					'sm' => esc_html__( 'Small', 'animation-addons-for-elementor-pro' ),
					'md' => esc_html__( 'Medium', 'animation-addons-for-elementor-pro' ),
					'lg' => esc_html__( 'Large', 'animation-addons-for-elementor-pro' ),
					'xl' => esc_html__( 'Extra Large', 'animation-addons-for-elementor-pro' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'btn_hover_effect',
			array(
				'label'   => esc_html__( 'Hover Effect', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'       => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'slide-left' => esc_html__( 'Slide Left', 'animation-addons-for-elementor-pro' ),
					'slide-right'=> esc_html__( 'Slide Right', 'animation-addons-for-elementor-pro' ),
					'slide-up'   => esc_html__( 'Slide Up', 'animation-addons-for-elementor-pro' ),
					'slide-down' => esc_html__( 'Slide Down', 'animation-addons-for-elementor-pro' ),
					'curtain-h'  => esc_html__( 'Curtain Horizontal', 'animation-addons-for-elementor-pro' ),
					'curtain-v'  => esc_html__( 'Curtain Vertical', 'animation-addons-for-elementor-pro' ),
					'shutter-h'  => esc_html__( 'Shutter Horizontal', 'animation-addons-for-elementor-pro' ),
					'shutter-v'  => esc_html__( 'Shutter Vertical', 'animation-addons-for-elementor-pro' ),
					'icon-grow'  => esc_html__( 'Icon Grow', 'animation-addons-for-elementor-pro' ),
					'icon-spin'  => esc_html__( 'Icon Spin', 'animation-addons-for-elementor-pro' ),
					'ripple'     => esc_html__( 'Ripple', 'animation-addons-for-elementor-pro' ),
				),
			)
		);

		$this->add_control(
			'btn_full_width',
			array(
				'label'        => esc_html__( 'Full Width', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_responsive_control(
			'btn_align',
			array(
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn-wrapper' => 'text-align: {{VALUE}};',
				),
				'condition' => array( 'btn_full_width!' => 'yes' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'btn_id',
			array(
				'label'       => esc_html__( 'Button ID', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'separator'   => 'before',
				'description' => esc_html__( 'Set a unique ID for the button. Useful for anchor links.', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Button style controls.
	 */
	protected function register_button_style_controls() {
		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => esc_html__( 'Button', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typo',
				'selector' => '{{WRAPPER}} .aae-adv-btn .aae-adv-btn__text',
			)
		);

		$this->add_responsive_control(
			'btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .aae-adv-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .aae-adv-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Normal / Hover Tabs
		$this->start_controls_tabs( 'btn_style_tabs' );

		// Normal Tab
		$this->start_controls_tab(
			'btn_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->add_control(
			'btn_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'btn_bg',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .aae-adv-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'btn_border',
				'selector' => '{{WRAPPER}} .aae-adv-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'btn_shadow',
				'selector' => '{{WRAPPER}} .aae-adv-btn',
			)
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'btn_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->add_control(
			'btn_h_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'btn_h_bg',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .aae-adv-btn:hover',
			)
		);

		$this->add_control(
			'btn_h_bg_overlay',
			array(
				'label'     => esc_html__( 'Hover Overlay Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn::before' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'btn_hover_effect!' => array( 'none', 'icon-grow', 'icon-spin', 'ripple' ),
				),
			)
		);

		$this->add_control(
			'btn_h_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'btn_h_shadow',
				'selector' => '{{WRAPPER}} .aae-adv-btn:hover',
			)
		);

		$this->add_control(
			'btn_hover_transition',
			array(
				'label'      => esc_html__( 'Transition Duration (ms)', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2000,
						'step' => 50,
					),
				),
				'default'    => array(
					'size' => 400,
				),
				'selectors'  => array(
					'{{WRAPPER}} .aae-adv-btn'         => 'transition-duration: {{SIZE}}ms;',
					'{{WRAPPER}} .aae-adv-btn::before'  => 'transition-duration: {{SIZE}}ms;',
					'{{WRAPPER}} .aae-adv-btn .aae-adv-btn__icon' => 'transition-duration: {{SIZE}}ms;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Icon style controls.
	 */
	protected function register_icon_style_controls() {
		$this->start_controls_section(
			'section_style_icon',
			array(
				'label'     => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'btn_icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'btn_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .aae-adv-btn__icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'btn_icon_gap',
			array(
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .aae-adv-btn__content' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'icon_style_tabs' );

		$this->start_controls_tab(
			'icon_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->add_control(
			'btn_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn__icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->add_control(
			'btn_icon_h_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn:hover .aae-adv-btn__icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Subtitle style controls.
	 */
	protected function register_subtitle_style_controls() {
		$this->start_controls_section(
			'section_style_subtitle',
			array(
				'label'     => esc_html__( 'Subtitle', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'btn_subtitle!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typo',
				'selector' => '{{WRAPPER}} .aae-adv-btn__subtitle',
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn__subtitle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'subtitle_h_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aae-adv-btn:hover .aae-adv-btn__subtitle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'subtitle_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .aae-adv-btn__subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$hover_effect  = $settings['btn_hover_effect'];
		$size          = $settings['btn_size'];
		$full_width    = 'yes' === $settings['btn_full_width'] ? ' aae-adv-btn--full' : '';
		$icon_position = $settings['btn_icon_position'];
		$has_icon      = ! empty( $settings['btn_icon']['value'] );
		$has_subtitle  = ! empty( $settings['btn_subtitle'] );

		// Button classes
		$btn_classes = array(
			'aae-adv-btn',
			'aae-adv-btn--' . $size,
			'aae-adv-btn--hover-' . $hover_effect,
			$full_width,
		);

		if ( $has_icon ) {
			$btn_classes[] = 'aae-adv-btn--icon-' . $icon_position;
		}

		$this->add_render_attribute( 'button', 'class', implode( ' ', array_filter( $btn_classes ) ) );

		if ( ! empty( $settings['btn_link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['btn_link'] );
		}

		if ( ! empty( $settings['btn_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['btn_id'] );
		}

		?>
		<div class="aae-adv-btn-wrapper">
			<a <?php $this->print_render_attribute_string( 'button' ); ?>>
				<span class="aae-adv-btn__content">
					<?php if ( $has_icon && 'before' === $icon_position ) : ?>
						<span class="aae-adv-btn__icon">
							<?php Icons_Manager::render_icon( $settings['btn_icon'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
					<?php endif; ?>

					<span class="aae-adv-btn__text-wrap">
						<span class="aae-adv-btn__text"><?php echo esc_html( $settings['btn_text'] ); ?></span>
						<?php if ( $has_subtitle ) : ?>
							<span class="aae-adv-btn__subtitle"><?php echo esc_html( $settings['btn_subtitle'] ); ?></span>
						<?php endif; ?>
					</span>

					<?php if ( $has_icon && 'after' === $icon_position ) : ?>
						<span class="aae-adv-btn__icon">
							<?php Icons_Manager::render_icon( $settings['btn_icon'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
					<?php endif; ?>
				</span>
			</a>
		</div>
		<?php
	}
}
