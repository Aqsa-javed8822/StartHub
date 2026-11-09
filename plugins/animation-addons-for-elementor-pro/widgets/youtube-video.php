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
	exit;
}

class Youtube_Video extends Widget_Base {

	public function get_name() {
		return 'aae-pro-youtube-videos';
	}

	public function get_title() {
		return __( 'YouTube Videos', 'animation-addons-for-elementor-pro' );
	}

	public function get_keywords() {
		return [ 'youtube', 'video', 'feed' ];
	}

	public function get_icon() {
		return 'wcf eicon-youtube';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_script_depends() {
		return [ 'swiper', 'aae--youtube-video' ];
	}

	public function get_style_depends() {
		return [ 'swiper', 'aae-ytube-videos' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_layout',
			[ 'label' => __( 'Layout', 'animation-addons-for-elementor-pro' ) ]
		);

		$this->add_control(
			'yt_video_layout',
			[
				'label'   => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'     => esc_html__( 'Grid', 'animation-addons-for-elementor-pro' ),
					'playlist' => esc_html__( 'Playlist', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'playlist_position',
			[
				'label'        => esc_html__( 'Playlist Position', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'prefix_class' => 'position-',
				'options'      => [
					'row-reverse'    => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column-reverse' => [
						'title' => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-top',
					],
					'row'            => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-right',
					],
					'column'         => [
						'title' => esc_html__( 'Bottom', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'      => 'row',
				'selectors'    => [
					'{{WRAPPER}} .aae--yt-playlist-wrapper' => 'flex-direction: {{VALUE}};',
				],
				'condition'    => [ 'yt_video_layout' => 'playlist' ],
			]
		);

		$this->add_control(
			'playlist_icon',
			[
				'label'       => esc_html__( 'Playlist Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-play',
					'library' => 'fa-solid',
				],
				'condition'   => [ 'yt_video_layout' => 'playlist' ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[ 'label' => __( 'Content', 'animation-addons-for-elementor-pro' ) ]
		);

		// Source type dropdown
		$this->add_control(
			'source_type',
			[
				'label'   => __( 'Source Type', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'username' => __( 'Username', 'animation-addons-for-elementor-pro' ),
					'playlist' => __( 'Playlist ID', 'animation-addons-for-elementor-pro' ),
				],
				'default' => 'username',
			]
		);

		$this->add_control(
			'short_videos',
			[
				'label'        => esc_html__( 'Short Videos', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Enable', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Disable', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Username field
		$this->add_control(
			'username',
			[
				'label'       => __( 'YouTube Username', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'GoogleDevelopers',
				'condition'   => [ 'source_type' => 'username' ],
			]
		);

		// Playlist ID field
		$this->add_control(
			'playlist_id',
			[
				'label'       => __( 'Playlist ID', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'PL_xxx',
				'condition'   => [ 'source_type' => 'playlist' ],
			]
		);

		// Common controls
		$this->add_control(
			'max_results',
			[
				'label'   => __( 'Max Results', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 50,
				'step'    => 1,
				'default' => 6,
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => __( 'Order By', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'date'      => __( 'Publish Date', 'animation-addons-for-elementor-pro' ),
					'viewCount' => __( 'View Count', 'animation-addons-for-elementor-pro' ),
					'relevance' => __( 'Relevance', 'animation-addons-for-elementor-pro' ),
				],
				'default' => 'date',
			]
		);

		$this->add_control(
			'enable_popup',
			[
				'label'              => __( 'Enable Popup', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Yes', 'animation-addons-for-elementor-pro' ),
				'label_off'          => __( 'No', 'animation-addons-for-elementor-pro' ),
				'return_value'       => 'yes',
				'default'            => 'no',
				'frontend_available' => true,
				'condition'          => [ 'yt_video_layout' => 'grid' ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'video_options',
			[
				'label'      => esc_html__( 'Options', 'animation-addons-for-elementor-pro' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'enable_popup',
							'operator' => '!==',
							'value'    => 'yes',
						],
						[
							'name'     => 'yt_video_layout',
							'operator' => '===',
							'value'    => 'playlist',
						],
					],
				],
			]
		);

		$this->add_control(
			'video_controls',
			[
				'label'   => esc_html__( 'Video Controls', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Enable', 'animation-addons-for-elementor-pro' ),
					'0' => esc_html__( 'Disable', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'video_autoplay',
			[
				'label'   => esc_html__( 'Video Autoplay', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Autoplay', 'animation-addons-for-elementor-pro' ),
					'0' => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->end_controls_section();

		// Style
		$this->style_youtube_video_layout();

		$this->style_youtube_video_box();

		$this->style_youtube_video_title();

		$this->style_youtube_video_playlist();

		$this->style_youtube_playlist_item();
	}

	protected function style_youtube_video_layout() {
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
					'{{WRAPPER}} .aae-addon-yt-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [ 'yt_video_layout' => 'grid' ],
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
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-addon-yt-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'yt_video_layout' => 'grid' ],
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
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-addon-yt-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'yt_video_layout' => 'grid' ],
			]
		);

		$this->add_responsive_control(
			'layout_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae--yt-playlist-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'yt_video_layout' => 'playlist' ],
			]
		);


		$this->end_controls_section();
	}

	protected function style_youtube_video_box() {
		$this->start_controls_section(
			'style_video',
			[
				'label' => esc_html__( 'Video Box', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'box_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-addon-yt-item' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .aae--yt-videos'    => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_height',
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
					'{{WRAPPER}} .aae-addon-yt-item, {{WRAPPER}} .aae--yt-videos .video-wrap, {{WRAPPER}} .aae--yt-playlist' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_b_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .aae-addon-yt-item, {{WRAPPER}} .aae--yt-videos' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_b_shadow',
				'selector' => '{{WRAPPER}} .aae-addon-yt-item, {{WRAPPER}} .aae--yt-videos',
			]
		);

		$this->end_controls_section();
	}

	protected function style_youtube_video_title() {
		$this->start_controls_section(
			'style_video_title',
			[
				'label'     => esc_html__( 'Video Title', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'yt_video_layout' => 'grid' ],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .title',
			]
		);

		$this->add_responsive_control(
			'title_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .title-wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
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
					'{{WRAPPER}} .title' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function style_youtube_video_playlist() {
		$this->start_controls_section(
			'style_playlist',
			[
				'label'     => esc_html__( 'Playlist', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'yt_video_layout' => 'playlist' ],
			]
		);

		$this->add_responsive_control(
			'playlist_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae--yt-playlist' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'playlist_border',
				'selector' => '{{WRAPPER}} .aae--yt-playlist',
			]
		);

		$this->add_responsive_control(
			'playlist_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .aae--yt-playlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'playlist_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .aae--yt-playlist' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_youtube_playlist_item() {
		$this->start_controls_section(
			'style_pl_item',
			[
				'label'     => esc_html__( 'Playlist Item', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'yt_video_layout' => 'playlist' ],
			]
		);

		$this->add_responsive_control(
			'pl_item_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .item-wrap' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pl_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .item-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Number/Icon
		$this->add_control(
			'pl_num_heading',
			[
				'label'     => esc_html__( 'Number/Icon', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'number_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .number-wrap' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'number_typo',
				'selector' => '{{WRAPPER}} .number-wrap',
			]
		);

		// Image
		$this->add_control(
			'pl_img_heading',
			[
				'label'     => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'pl_img_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .aae--yt-playlist .thumb' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
			]
		);


		// Title
		$this->add_control(
			'pl_title_heading',
			[
				'label'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pl_title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pl_title_typo',
				'selector' => '{{WRAPPER}} .title',
			]
		);

		// Hover/Active
		$this->add_control(
			'pl_hover_heading',
			[
				'label'     => esc_html__( 'Hover/Active', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'pl_hover_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .item-wrap:hover, {{WRAPPER}} .item-wrap.active',
			]
		);

		$this->add_control(
			'pl_icon_h_color',
			[
				'label'     => esc_html__( 'Icon Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-wrap:hover .number-wrap, {{WRAPPER}} .item-wrap.active .number-wrap' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pl_title_h_color',
			[
				'label'     => esc_html__( 'Title Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-wrap:hover .title, {{WRAPPER}} .item-wrap.active .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	// Render
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$type       = $settings['source_type'];
		$username   = sanitize_text_field( $settings['username'] );
		$playlistId = sanitize_text_field( $settings['playlist_id'] );
		$maxResults = absint( $settings['max_results'] );
		$order      = sanitize_text_field( $settings['order'] );
		$usePopup   = ( 'yes' === $settings['enable_popup'] );


		$cache = new \WCFAddonsPro\AAE_Addon_YT_Cache( 'AIzaSyAmAEEn4fZhvWaFTIYroVfvEDi4dlr26Hc' );

		switch ( $type ) {
			case 'playlist':
				$data = $cache->get_playlists_videos_Page( $playlistId, '', $maxResults );

				break;
			case 'username':
				if ( $settings['short_videos'] == 'yes' ) {
					$data = $cache->get_short_videos( $username, $maxResults, '', $order );
				} else {
					$data = $cache->get_videos_by_username( $username, '', $maxResults );
				}
				break;

			default:
				$data = false;
				break;
		}

		if ( empty( $data['items'] ) ) {
			echo '<p>' . __( 'No videos found.', 'animation-addons-for-elementor-pro' ) . '</p>';

			return;
		}

		if ( 'playlist' === $settings['yt_video_layout'] ) {
			$this->render_playlist_layout( $settings, $data );
		} else {
			$this->render_grid_layout( $settings, $data, $usePopup );
		}
	}

	protected function render_grid_layout( $settings, $data, $usePopup ) {
		// wrapper class
		$this->add_render_attribute(
			'wrapper',
			[
				'class'         => [ 'aae-addon-yt-grid', 'aae_' . $this->get_id() ],
				'data-autoplay' => $settings['video_autoplay'],
				'data-controls' => $settings['video_controls'],
			]
		);
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			foreach ( $data['items'] as $item ) {
				$vid = isset( $item['id']['videoId'] )
					? $item['id']['videoId']
					: ( $item['snippet']['resourceId']['videoId'] ?? '' );
				if ( ! $vid ) {
					continue;
				}
				$vid   = esc_attr( $vid );
				$thumb = esc_url( $item['snippet']['thumbnails']['medium']['url'] );
				$title = esc_html( $item['snippet']['title'] );

				if ( $usePopup ) {
					$popup = 'open-popup';
				} else {
					$popup = 'no-popup';
				}
				?>
                <div class="aae-addon-yt-item <?php echo $popup; ?>" data-video-id="<?php echo esc_attr( $vid ); ?>"
                     data-thumb="<?php echo $thumb; ?>" data-title="<?php echo esc_attr( $title ); ?>">
                    <div class="item-inner">
                        <img src="<?php echo $thumb; ?>" alt="<?php echo $title; ?>">
                        <div class="aae-play-button"></div>
                    </div>
                    <div class="title-wrap">
                        <p class="title"><?php echo $title; ?></p>
                    </div>
                </div>
				<?php
			}
			?>
        </div>
		<?php
	}

	protected function render_playlist_layout( $settings, $data ) {
		$autoplay = $settings['video_autoplay'];
		$controls = $settings['video_controls'];

		$this->add_render_attribute(
			'playlist_wrapper',
			[
				'class'         => 'aae--yt-playlist-wrapper aae--normalize-smoother',
				'data-autoplay' => $autoplay,
				'data-controls' => $controls,
			]
		);

		$initial_vid = $data['items'][0]['id']['videoId'] ?? $data['items'][0]['snippet']['resourceId']['videoId'];
		?>
        <div <?php $this->print_render_attribute_string( 'playlist_wrapper' ); ?>>
            <div class="aae--yt-videos">
                <div class="video-wrap">
                    <iframe
                            src="https://www.youtube.com/embed/<?php echo esc_attr( $initial_vid ); ?>?autoplay=<?php echo $autoplay; ?>&controls=<?php echo $controls; ?>"
                            allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
            <div class="aae--yt-playlist">
				<?php foreach ( $data['items'] as $index => $item ) :
					$vid = isset( $item['id']['videoId'] )
						? $item['id']['videoId']
						: ( $item['snippet']['resourceId']['videoId'] ?? '' );
					if ( ! $vid ) {
						continue;
					}
					$thumb = esc_url( $item['snippet']['thumbnails']['medium']['url'] );
					$title = esc_html( $item['snippet']['title'] );
					?>
                    <div class="item-wrap" data-video-id="<?php echo esc_attr( $vid ); ?>">
                        <div class="number-wrap">
                            <span class="number"><?php echo $index + 1; ?></span>
                            <span class="icon">
                                <?php Icons_Manager::render_icon( $settings['playlist_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </span>
                        </div>
                        <div class="thumb">
                            <img src="<?php echo $thumb; ?>" alt="<?php echo $title; ?>">
                        </div>
                        <div class="title-wrap">
                            <h3 class="title"><?php echo $title; ?></h3>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
		<?php
	}
}
