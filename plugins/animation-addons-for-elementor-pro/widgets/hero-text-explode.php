<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hero_Text_Explode extends Widget_Base {

	public function get_name() {
		return 'hero-text-explode';
	}

	public function get_title() {
		return __( 'Hero Text Explode', 'animation-addons-for-elementor-pro' );
	}

	public function get_icon() {
		return 'wcf eicon-animated-headline';
	}

	public function get_categories() {
		return [ 'animation-addons-for-elementor-pro' ];
	}

	public function get_style_depends() {
		return [ 'aae-hero-text-explode' ];
	}

	public function get_script_depends() {
		wp_register_script(
			'Physics2DPlugin',
			WCF_ADDONS_PRO_URL . 'assets/lib/Physics2DPlugin.min.js',
			[ 'gsap' ],
			false,
			true
		);

		return [ 'aae-hero-text-explode' ];
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_animation_controls();
		$this->register_style_controls();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'pre_text',
			[
				'label'       => esc_html__( 'Pre Text', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'we are', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'highlight_text',
			[
				'label'       => esc_html__( 'Highlight Word', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'not', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
				'description' => esc_html__( 'This word will explode with physics animation.', 'animation-addons-for-elementor-pro' ),
			]
		);

		$repeater->add_control(
			'post_text',
			[
				'label'       => esc_html__( 'Post Text', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'ready to ship', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'sentences',
			[
				'label'       => esc_html__( 'Sentences', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'pre_text'       => esc_html__( 'we are', 'animation-addons-for-elementor-pro' ),
						'highlight_text' => esc_html__( 'not', 'animation-addons-for-elementor-pro' ),
						'post_text'      => esc_html__( 'ready to ship', 'animation-addons-for-elementor-pro' ),
					],
					[
						'pre_text'       => esc_html__( 'This word is about to', 'animation-addons-for-elementor-pro' ),
						'highlight_text' => esc_html__( 'explode', 'animation-addons-for-elementor-pro' ),
						'post_text'      => '',
					],
					[
						'pre_text'       => esc_html__( 'layout is', 'animation-addons-for-elementor-pro' ),
						'highlight_text' => esc_html__( 'not', 'animation-addons-for-elementor-pro' ),
						'post_text'      => esc_html__( 'responsive', 'animation-addons-for-elementor-pro' ),
					],
					[
						'pre_text'       => esc_html__( 'performance is', 'animation-addons-for-elementor-pro' ),
						'highlight_text' => esc_html__( 'never', 'animation-addons-for-elementor-pro' ),
						'post_text'      => esc_html__( 'smooth', 'animation-addons-for-elementor-pro' ),
					],
					[
						'pre_text'       => esc_html__( 'the app is', 'animation-addons-for-elementor-pro' ),
						'highlight_text' => esc_html__( 'barely', 'animation-addons-for-elementor-pro' ),
						'post_text'      => esc_html__( 'accessible', 'animation-addons-for-elementor-pro' ),
					],
					[
						'pre_text'       => esc_html__( 'workflow is', 'animation-addons-for-elementor-pro' ),
						'highlight_text' => esc_html__( 'hardly', 'animation-addons-for-elementor-pro' ),
						'post_text'      => esc_html__( 'efficient', 'animation-addons-for-elementor-pro' ),
					],
				],
				'title_field' => '{{{ pre_text }}} <b>{{{ highlight_text }}}</b> {{{ post_text }}}',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				],
			]
		);

		$this->add_responsive_control(
			'min_height',
			[
				'label'      => esc_html__( 'Min Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'svh' ],
				'range'      => [
					'px'  => [ 'min' => 100, 'max' => 1200 ],
					'vh'  => [ 'min' => 10, 'max' => 100 ],
					'svh' => [ 'min' => 10, 'max' => 100 ],
				],
				'default'    => [
					'unit' => 'svh',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-hero-text-explode' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_animation_controls() {
		$this->start_controls_section(
			'section_animation',
			[
				'label' => esc_html__( 'Animation', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'entry_duration',
			[
				'label'   => esc_html__( 'Entry Duration (s)', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [ 'min' => 0.1, 'max' => 3, 'step' => 0.1 ],
				],
				'default' => [ 'size' => 0.7, 'unit' => 'px' ],
			]
		);

		$this->add_control(
			'entry_stagger',
			[
				'label'   => esc_html__( 'Entry Stagger (s)', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [ 'min' => 0.01, 'max' => 0.5, 'step' => 0.01 ],
				],
				'default' => [ 'size' => 0.1, 'unit' => 'px' ],
			]
		);

		$this->add_control(
			'explode_delay',
			[
				'label'   => esc_html__( 'Explode Delay (s)', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [ 'min' => 0.5, 'max' => 5, 'step' => 0.1 ],
				],
				'default' => [ 'size' => 1.5, 'unit' => 'px' ],
			]
		);

		$this->add_control(
			'explode_duration',
			[
				'label'   => esc_html__( 'Explode Duration (s)', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [ 'min' => 0.5, 'max' => 5, 'step' => 0.1 ],
				],
				'default' => [ 'size' => 2.2, 'unit' => 'px' ],
			]
		);

		$this->add_control(
			'gravity',
			[
				'label'   => esc_html__( 'Gravity', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [ 'min' => 100, 'max' => 2000, 'step' => 50 ],
				],
				'default' => [ 'size' => 800, 'unit' => 'px' ],
			]
		);

		$this->add_control(
			'velocity_min',
			[
				'label'   => esc_html__( 'Velocity Min', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [ 'min' => 50, 'max' => 1000, 'step' => 50 ],
				],
				'default' => [ 'size' => 300, 'unit' => 'px' ],
			]
		);

		$this->add_control(
			'velocity_max',
			[
				'label'   => esc_html__( 'Velocity Max', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [ 'min' => 100, 'max' => 2000, 'step' => 50 ],
				],
				'default' => [ 'size' => 600, 'unit' => 'px' ],
			]
		);

		$this->add_control(
			'exit_delay',
			[
				'label'   => esc_html__( 'Exit Delay (s)', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [ 'min' => 0.5, 'max' => 5, 'step' => 0.1 ],
				],
				'default' => [ 'size' => 1.5, 'unit' => 'px' ],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .aae-hero-text-explode__title',
			]
		);

		$this->add_responsive_control(
			'title_font_size',
			[
				'label'      => esc_html__( 'Font Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'vw', 'rem' ],
				'range'      => [
					'px' => [ 'min' => 10, 'max' => 300 ],
					'vw' => [ 'min' => 1, 'max' => 30 ],
					'em' => [ 'min' => 1, 'max' => 20 ],
				],
				'default'    => [
					'unit' => 'vw',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-hero-text-explode__title' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f6f6ff',
				'selectors' => [
					'{{WRAPPER}} .aae-hero-text-explode__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'accent_color',
			[
				'label'     => esc_html__( 'Highlight Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c3ff00',
				'selectors' => [
					'{{WRAPPER}} .aae-hero-text-explode__accent' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_align',
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
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .aae-hero-text-explode__title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Background section
		$this->start_controls_section(
			'section_style_background',
			[
				'label' => esc_html__( 'Background', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0b0b0b',
				'selectors' => [
					'{{WRAPPER}} .aae-hero-text-explode' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'show_grid',
			[
				'label'        => esc_html__( 'Show Grid Pattern', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'vw' ],
				'default'    => [
					'top'    => '4',
					'right'  => '4',
					'bottom' => '4',
					'left'   => '4',
					'unit'   => 'vw',
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-hero-text-explode' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$widget_id = $this->get_id();
		$tag       = $settings['title_tag'];

		$sentences = [];
		if ( ! empty( $settings['sentences'] ) ) {
			foreach ( $settings['sentences'] as $item ) {
				$sentences[] = [
					'pre'       => esc_html( $item['pre_text'] ),
					'highlight' => esc_html( $item['highlight_text'] ),
					'post'      => esc_html( $item['post_text'] ),
				];
			}
		}

		$grid_class = ( 'yes' === $settings['show_grid'] ) ? ' aae-hero-text-explode--grid' : '';

		$animation_data = [
			'entryDuration'  => ! empty( $settings['entry_duration']['size'] ) ? (float) $settings['entry_duration']['size'] : 0.7,
			'entryStagger'   => ! empty( $settings['entry_stagger']['size'] ) ? (float) $settings['entry_stagger']['size'] : 0.1,
			'explodeDelay'   => ! empty( $settings['explode_delay']['size'] ) ? (float) $settings['explode_delay']['size'] : 1.5,
			'explodeDuration' => ! empty( $settings['explode_duration']['size'] ) ? (float) $settings['explode_duration']['size'] : 2.2,
			'gravity'        => ! empty( $settings['gravity']['size'] ) ? (int) $settings['gravity']['size'] : 800,
			'velocityMin'    => ! empty( $settings['velocity_min']['size'] ) ? (int) $settings['velocity_min']['size'] : 300,
			'velocityMax'    => ! empty( $settings['velocity_max']['size'] ) ? (int) $settings['velocity_max']['size'] : 600,
			'exitDelay'      => ! empty( $settings['exit_delay']['size'] ) ? (float) $settings['exit_delay']['size'] : 1.5,
			'sentences'      => $sentences,
		];
		?>
		<section class="aae-hero-text-explode<?php echo esc_attr( $grid_class ); ?>"
		         data-id="<?php echo esc_attr( $widget_id ); ?>"
		         data-settings="<?php echo esc_attr( wp_json_encode( $animation_data ) ); ?>">
			<<?php echo esc_html( $tag ); ?> class="aae-hero-text-explode__title"></<?php echo esc_html( $tag ); ?>>
		</section>
		<?php
	}
}
