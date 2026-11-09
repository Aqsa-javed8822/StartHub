<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Posts_Read_Later extends Widget_Base {

	public function get_name() {
		return 'aae--posts-read-later';
	}

	public function get_title() {
		return __( 'Posts Read Later', 'animation-addons-for-elementor-pro' );
	}

	public function get_icon() {
		return 'wcf eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'animation-addons-for-elementor-pro' ];
	}

	public function get_script_depends() {
		return [ 'aae-posts-read-later' ];
	}

	public function get_style_depends() {
		return [ 'aae-posts-read-later' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			[ 'label' => __( 'Layout', 'animation-addons-for-elementor-pro' ) ]
		);

		$this->add_control(
			'read_later_style',
			[
				'label'   => __( 'Style', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'grid'     => __( 'Grid', 'animation-addons-for-elementor-pro' ),
					'dropdown' => __( 'Dropdown', 'animation-addons-for-elementor-pro' ),
				],
				'default' => 'grid',
			]
		);

		$this->add_control(
			'open_icon',
			[
				'label'       => esc_html__( 'Open Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-bookmark',
					'library' => 'fa-solid',
				],
				'condition'   => [ 'read_later_style' => 'dropdown' ],
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label'       => esc_html__( 'Close Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'far fa-window-close',
					'library' => 'fa-solid',
				],
				'condition'   => [ 'read_later_style' => 'dropdown' ],
			]
		);

		$this->add_control(
			'dropdown_position',
			[
				'label'       => esc_html__( 'Dropdown Position', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'prefix_class' => 'position-',
				'options'     => [
					'left'  => [
						'title' => esc_html__( 'left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'fa fa-angle-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'fa fa-angle-right',
					],
				],
				'default'     => 'right',
				'condition'   => [ 'read_later_style' => 'dropdown' ],
			]
		);

		$this->end_controls_section();

		// Style
		$this->style_read_later_layout();

		$this->style_read_later_thumb();

		$this->style_read_later_content();
	}

	protected function style_read_later_layout() {
		$this->start_controls_section(
			'style_layout',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'layout_grid',
			[
				'label'     => esc_html__( 'Grid', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => [
					'1' => esc_html__( '1', 'animation-addons-for-elementor-pro' ),
					'2' => esc_html__( '2', 'animation-addons-for-elementor-pro' ),
					'3' => esc_html__( '3', 'animation-addons-for-elementor-pro' ),
					'4' => esc_html__( '4', 'animation-addons-for-elementor-pro' ),
					'5' => esc_html__( '5', 'animation-addons-for-elementor-pro' ),
					'6' => esc_html__( '6', 'animation-addons-for-elementor-pro' ),
					'7' => esc_html__( '7', 'animation-addons-for-elementor-pro' ),
					'8' => esc_html__( '8', 'animation-addons-for-elementor-pro' ),
					'9' => esc_html__( '9', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .grid #aae--read-later-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [ 'read_later_style' => 'grid' ],
			]
		);

		$this->add_responsive_control(
			'layout_col_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .grid #aae--read-later-list' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'read_later_style' => 'grid' ],
			]
		);

		$this->add_responsive_control(
			'layout_row_gap',
			[
				'label'      => esc_html__( 'Row Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .grid #aae--read-later-list' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'read_later_style' => 'grid' ],
			]
		);

		$this->add_responsive_control(
			'dd_item_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dropdown #aae--read-later-list .aae-post-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'read_later_style' => 'dropdown' ],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'dropdown_bg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .dropdown #aae--read-later-list',
				'condition' => [ 'read_later_style' => 'dropdown' ],
			]
		);

		$this->add_responsive_control(
			'dd_b_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .dropdown #aae--read-later-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ 'read_later_style' => 'dropdown' ],
			]
		);

		$this->add_responsive_control(
			'dd_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .dropdown #aae--read-later-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ 'read_later_style' => 'dropdown' ],
			]
		);

		$this->end_controls_section();
	}

	protected function style_read_later_thumb() {
		$this->start_controls_section(
			'style_thumb',
			[
				'label'     => esc_html__( 'Thumb', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'read_later_style' => 'grid' ],
			]
		);

		$this->add_responsive_control(
			'img_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'{{WRAPPER}} .thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'{{WRAPPER}} .thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_b_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_read_later_content() {
		$this->start_controls_section(
			'style_content',
			[
				'label' => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ 'read_later_style' => 'grid' ],
			]
		);

		$this->add_responsive_control(
			'content_align',
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
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .content' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Title
		$this->add_control(
			'title_heading',
			[
				'label'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #aae--read-later-list .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_h_color',
			[
				'label'     => esc_html__( 'Hover Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #aae--read-later-list .title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} #aae--read-later-list .title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} #aae--read-later-list .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Date
		$this->add_control(
			'date_heading',
			[
				'label'     => esc_html__( 'Date', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .date' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typo',
				'selector' => '{{WRAPPER}} #aae--read-later-list .date',
			]
		);

		$this->end_controls_section();
	}

	// Render
	protected function render() {
		$settings = $this->get_settings_for_display();

		?>
        <div class="aae--read-later-wrapper <?php echo $settings['read_later_style']; ?>">
			<?php if ( 'dropdown' === $settings['read_later_style'] ) { ?>
                <button class="aae--read-later-toggle">
                    <span class="open-icon">
                        <?php Icons_Manager::render_icon( $settings['open_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                    <span class="close-icon">
                        <?php Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                </button>
			<?php } ?>

            <div id="aae--read-later-list"></div>
        </div>
		<?php
	}
}
