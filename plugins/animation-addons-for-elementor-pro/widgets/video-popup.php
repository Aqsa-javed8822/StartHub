<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Video Popup
 *
 * Elementor widget for Video Popup.
 *
 * @since 1.0.0
 */
class Video_Popup extends Widget_Base {

    /**
     * Retrieve the widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     *
     * @access public
     */
    public function get_name() {
        return 'wcf--video-popup';
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
        return esc_html__( 'Video Popup', 'animation-addons-for-elementor-pro' );
    }


        public function get_style_depends() {
                return ['aae-video-popup'];
        }

    public function get_script_depends() {
        return ['aae-video-popup-mix'];
    }

    /**
     * Static flag to ensure meta tag is only added once
     *
     * @var bool
     */
    private static $meta_tag_added = false;

    /**
     * Add referrer meta tag to head for CORS support
     *
     * @since 1.0.0
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        // Add action to inject meta tag in head (only once)
        if ( ! self::$meta_tag_added ) {
            self::$meta_tag_added = true;
        }
    }

    /**
     * Add referrer meta tag for CORS support
     * This is output directly in PHP for better performance
     *
     * @since 1.0.0
     */

    /**
     * Retrieve the widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     *
     * @access public
     */
    public function get_icon() {
        return 'wcf eicon-youtube';
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
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function register_controls() {

        // Button Controls
        $this->register_button_controls();

        // Video Link
        $this->start_controls_section(
                'section_video_content',
                [
                        'label' => __( 'Video', 'animation-addons-for-elementor-pro' ),
                ]
        );

        $this->add_control(
                'video_link',
                [
                        'label'       => esc_html__( 'Video Link', 'animation-addons-for-elementor-pro' ),
                        'type'        => Controls_Manager::TEXT,
                        'input_type'  => 'url',
                        'placeholder' => 'https://www.youtube.com/watch?v=MLpWrANjFbI',
                        'description' => esc_html__( 'YouTube/Vimeo link, or link to video file (mp4 is recommended). Please reload the page seeing update.', 'animation-addons-for-elementor-pro' ),
                        'render_type' => 'template',
                        'label_block' => true,
                        'dynamic'     => [
                                'active' => true,
                        ],
                        'default'     => 'https://www.youtube.com/watch?v=MLpWrANjFbI',
                ]
        );

        $this->end_controls_section();

        // Popup Content
        $this->start_controls_section(
                'section_popup',
                [
                        'label' => __( 'Popup', 'animation-addons-for-elementor-pro' ),
                ]
        );

        $this->add_control(
                'popup_animation',
                [
                        'label'   => esc_html__( 'Animation', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::SELECT,
                        'default' => 'scale-reveal',
                        'options' => [
                                'scale-reveal'   => esc_html__( 'Scale Reveal (Default)', 'animation-addons-for-elementor-pro' ),
                                'zoom-in'        => esc_html__( 'Zoom In', 'animation-addons-for-elementor-pro' ),
                                'fade-slide-up'  => esc_html__( 'Fade Slide Up', 'animation-addons-for-elementor-pro' ),
                                'flip-3d'        => esc_html__( 'Flip 3D', 'animation-addons-for-elementor-pro' ),
                                'curtain-split'  => esc_html__( 'Curtain Split', 'animation-addons-for-elementor-pro' ),
                                'blur-in'        => esc_html__( 'Blur In', 'animation-addons-for-elementor-pro' ),
                                'rotate-in'      => esc_html__( 'Rotate In', 'animation-addons-for-elementor-pro' ),
                                'elastic-bounce' => esc_html__( 'Elastic Bounce', 'animation-addons-for-elementor-pro' ),
                        ],
                ]
        );

        $this->add_control(
                'animation_duration',
                [
                        'label'   => esc_html__( 'Animation Duration (s)', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::SLIDER,
                        'range'   => [
                                'px' => [ 'min' => 0.1, 'max' => 3, 'step' => 0.1 ],
                        ],
                        'default' => [ 'unit' => 'px', 'size' => 0.8 ],
                ]
        );

        $this->add_control(
                'close_icon',
                [
                        'label'            => esc_html__( 'Close Icon', 'animation-addons-for-elementor-pro' ),
                        'type'             => Controls_Manager::ICONS,
                        'fa4compatibility' => 'icon',
                        'default'          => [
                                'value'   => 'eicon-close',
                                'library' => 'eicons',
                        ],
                        'skin'             => 'inline',
                        'label_block'      => false,
                ]
        );

        $this->end_controls_section();

        // Popup Style
        $this->start_controls_section(
                'section_popup_style',
                [
                        'label' => __( 'Popup', 'animation-addons-for-elementor-pro' ),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'popup_width',
                [
                        'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%', 'vw' ],
                        'range'      => [
                                'px' => [ 'min' => 200, 'max' => 1600, 'step' => 10 ],
                                '%'  => [ 'min' => 10, 'max' => 100 ],
                                'vw' => [ 'min' => 10, 'max' => 100 ],
                        ],
                        'default'    => [ 'unit' => 'px', 'size' => 900 ],
                ]
        );

        $this->add_responsive_control(
                'popup_height',
                [
                        'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%', 'vh' ],
                        'range'      => [
                                'px' => [ 'min' => 200, 'max' => 1200, 'step' => 10 ],
                                '%'  => [ 'min' => 10, 'max' => 100 ],
                                'vh' => [ 'min' => 10, 'max' => 100 ],
                        ],
                        'default'    => [ 'unit' => 'px', 'size' => 520 ],
                ]
        );

        $this->add_responsive_control(
                'popup_border_radius',
                [
                        'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                ]
        );

        $this->add_control(
                'overlay_color',
                [
                        'label'   => esc_html__( 'Overlay Color', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::COLOR,
                        'default' => 'rgba(11, 11, 11, 0.9)',
                ]
        );

        $this->end_controls_section();

        // Close Icon Style
        $this->start_controls_section(
                'section_close_style',
                [
                        'label' => __( 'Close Icon', 'animation-addons-for-elementor-pro' ),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'close_icon_size',
                [
                        'label'   => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::SLIDER,
                        'range'   => [ 'px' => [ 'min' => 8, 'max' => 80 ] ],
                        'default' => [ 'unit' => 'px', 'size' => 18 ],
                ]
        );

        $this->add_responsive_control(
                'close_btn_size',
                [
                        'label'   => esc_html__( 'Button Size', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::SLIDER,
                        'range'   => [ 'px' => [ 'min' => 20, 'max' => 120 ] ],
                        'default' => [ 'unit' => 'px', 'size' => 44 ],
                ]
        );

        $this->add_responsive_control(
                'close_offset_top',
                [
                        'label'   => esc_html__( 'Offset Top', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::SLIDER,
                        'range'   => [ 'px' => [ 'min' => -200, 'max' => 200 ] ],
                        'default' => [ 'unit' => 'px', 'size' => -40 ],
                ]
        );

        $this->add_responsive_control(
                'close_offset_right',
                [
                        'label'   => esc_html__( 'Offset Right', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::SLIDER,
                        'range'   => [ 'px' => [ 'min' => -200, 'max' => 200 ] ],
                        'default' => [ 'unit' => 'px', 'size' => -40 ],
                ]
        );

        $this->start_controls_tabs( 'tabs_close_style' );

        $this->start_controls_tab( 'tab_close_normal', [ 'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ) ] );

        $this->add_control(
                'close_color',
                [
                        'label'   => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::COLOR,
                        'default' => '#fff',
                ]
        );

        $this->add_control(
                'close_bg_color',
                [
                        'label'   => esc_html__( 'Background', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::COLOR,
                        'default' => 'transparent',
                ]
        );

        $this->add_control(
                'close_border_color',
                [
                        'label'   => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
                        'type'    => Controls_Manager::COLOR,
                        'default' => '#aaa',
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_close_hover', [ 'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ) ] );

        $this->add_control(
                'close_color_hover',
                [
                        'label' => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
                        'type'  => Controls_Manager::COLOR,
                ]
        );

        $this->add_control(
                'close_bg_color_hover',
                [
                        'label' => esc_html__( 'Background', 'animation-addons-for-elementor-pro' ),
                        'type'  => Controls_Manager::COLOR,
                ]
        );

        $this->add_control(
                'close_border_color_hover',
                [
                        'label' => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
                        'type'  => Controls_Manager::COLOR,
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function register_button_controls() {
        $this->start_controls_section(
                'section_button',
                [
                        'label' => __( 'Button', 'animation-addons-for-elementor-pro' ),
                ]
        );

        $this->add_control(
                'btn_text',
                [
                        'label'       => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
                        'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                                'active' => true,
                        ],
                        'default'     => esc_html__( 'Play', 'animation-addons-for-elementor-pro' ),
                        'placeholder' => esc_html__( 'Play', 'animation-addons-for-elementor-pro' ),
                ]
        );

        $this->add_control(
                'btn_icon',
                [
                        'label'            => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
                        'type'             => Controls_Manager::ICONS,
                        'fa4compatibility' => 'icon',
                        'skin'             => 'inline',
                        'label_block'      => false,
                ]
        );

        $this->add_control(
                'icon_indent',
                [
                        'label'     => esc_html__( 'Icon Spacing', 'animation-addons-for-elementor-pro' ),
                        'type'      => Controls_Manager::SLIDER,
                        'range'     => [
                                'px' => [
                                        'max' => 50,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .wcf-popup-btn' => 'gap: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_control(
                'active_ripple',
                [
                        'label'        => esc_html__( 'Active Ripple', 'animation-addons-for-elementor-pro' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__( 'yes', 'animation-addons-for-elementor-pro' ),
                        'label_off'    => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
                        'return_value' => 'yes',
                        'default'      => 'yes',
                ]
        );

        $this->add_control(
                'ripple_color',
                [
                        'label'     => esc_html__( 'Ripple Color', 'animation-addons-for-elementor-pro' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .wcf-popup-btn:before' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wcf-popup-btn:after'  => 'color: {{VALUE}}',
                        ],
                        'condition' => [ 'active_ripple' => 'yes' ],
                ]
        );

        $this->add_control(
                'active_spinner',
                [
                        'label'        => esc_html__( 'Active spinner', 'animation-addons-for-elementor-pro' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__( 'yes', 'animation-addons-for-elementor-pro' ),
                        'label_off'    => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
                        'return_value' => 'yes',
                ]
        );

        $this->add_control(
                'sipper_image',
                [
                        'label'     => esc_html__( 'Spinner Image', 'animation-addons-for-elementor-pro' ),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition' => [ 'active_spinner' => 'yes' ],
                ]
        );

        $this->add_responsive_control(
                'align',
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
                        'default'   => '',
                        'separator' => 'before',
                        'selectors' => [
                                '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                        ],
                ]
        );

        $this->end_controls_section();

        //style
        $this->start_controls_section(
                'section_button_style',
                [
                        'label' => __( 'Button', 'animation-addons-for-elementor-pro' ),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'button_typography',
                        'selector' => '{{WRAPPER}} .wcf-popup-btn',
                ]
        );

        $this->add_responsive_control(
                'button_width',
                [
                        'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                        'separator'  => 'before',
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 500,
                                        'step' => 5,
                                ],
                                '%'  => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .wcf-popup-btn' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_responsive_control(
                'button_height',
                [
                        'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 500,
                                        'step' => 5,
                                ],
                                '%'  => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .wcf-popup-btn' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name'      => 'button_border',
                        'selector'  => '{{WRAPPER}} .wcf-popup-btn',
                        'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'button_border_radius',
                [
                        'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                        'selectors'  => [
                                '{{WRAPPER}} .wcf-popup-btn'                => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .wcf-popup-btn:after'          => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .wcf-popup-btn:before'         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .wcf-popup-btn .spinner_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name'     => 'button_box_shadow',
                        'selector' => '{{WRAPPER}} .wcf-popup-btn',
                ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
                'tab_button_normal',
                [
                        'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
                ]
        );

        $this->add_control(
                'button_text_color',
                [
                        'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .wcf-popup-btn' => 'fill: {{VALUE}}; color: {{VALUE}};',
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                        'name'     => 'button_background',
                        'types'    => [ 'classic', 'gradient' ],
                        'exclude'  => [ 'image' ],
                        'selector' => '{{WRAPPER}} .wcf-popup-btn',
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_button_hover',
                [
                        'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
                ]
        );

        $this->add_control(
                'button_hover_text_color',
                [
                        'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus'         => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wcf-popup-btn:hover svg, {{WRAPPER}} .wcf-popup-btn:focus svg' => 'fill: {{VALUE}};',
                        ],
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                        'name'     => 'button_background_hover',
                        'types'    => [ 'classic', 'gradient' ],
                        'exclude'  => [ 'image' ],
                        'selector' => '{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus',
                ]
        );

        $this->add_control(
                'button_hover_border_color',
                [
                        'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
                        'type'      => Controls_Manager::COLOR,
                        'condition' => [
                                'button_border_border!' => '',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus' => 'border-color: {{VALUE}};',
                        ],
                ]
        );

        $this->add_control(
                'hover_animation',
                [
                        'label' => esc_html__( 'Hover Animation', 'animation-addons-for-elementor-pro' ),
                        'type'  => Controls_Manager::HOVER_ANIMATION,
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Parse and convert video URL to embed URL
     *
     * @param string $video_link The original video URL
     * @return string The embed URL with proper parameters
     */
    protected function parse_video_url( $video_link ) {
        $video_link = trim( $video_link );

        // YouTube URL patterns
        if ( preg_match( '/(?:youtube\.com|youtu\.be)/', $video_link ) ) {
            $video_id = '';

            // Handle different YouTube URL formats
            if ( preg_match( '/youtube\.com\/watch\?v=([^\&\?\/]+)/', $video_link, $matches ) ) {
                $video_id = $matches[1];
            } elseif ( preg_match( '/youtube\.com\/embed\/([^\&\?\/]+)/', $video_link, $matches ) ) {
                $video_id = $matches[1];
            } elseif ( preg_match( '/youtu\.be\/([^\&\?\/]+)/', $video_link, $matches ) ) {
                $video_id = $matches[1];
            } elseif ( preg_match( '/youtube\.com\/v\/([^\&\?\/]+)/', $video_link, $matches ) ) {
                $video_id = $matches[1];
            }

            if ( ! empty( $video_id ) ) {
                // Add proper parameters to fix CORS and enable features
                return add_query_arg( [
                        'autoplay' => '1',
                        'rel' => '0',
                        'modestbranding' => '1',
                        'enablejsapi' => '1',
                        'origin' => home_url()
                ], 'https://www.youtube.com/embed/' . $video_id );
            }
        }

        // Vimeo URL patterns
        if ( preg_match( '/vimeo\.com/', $video_link ) ) {
            $video_id = '';

            // Handle different Vimeo URL formats
            if ( preg_match( '/vimeo\.com\/(\d+)/', $video_link, $matches ) ) {
                $video_id = $matches[1];
            } elseif ( preg_match( '/vimeo\.com\/video\/(\d+)/', $video_link, $matches ) ) {
                $video_id = $matches[1];
            } elseif ( preg_match( '/vimeo\.com\/channels\/[^\/]+\/(\d+)/', $video_link, $matches ) ) {
                $video_id = $matches[1];
            } elseif ( preg_match( '/player\.vimeo\.com\/video\/(\d+)/', $video_link, $matches ) ) {
                $video_id = $matches[1];
            }

            if ( ! empty( $video_id ) ) {
                // Add proper parameters for Vimeo with CORS support
                return add_query_arg( [
                        'autoplay' => '1',
                        'title' => '0',
                        'byline' => '0',
                        'portrait' => '0',
                        'dnt' => '1'
                ], 'https://player.vimeo.com/video/' . $video_id );
            }
        }

        // Return original URL if not YouTube or Vimeo (for direct video files)
        return $video_link;
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

        $video_link = $this->parse_video_url( $settings['video_link'] );

        $this->add_render_attribute( 'wrapper', 'class', 'wcf--video-popup' );
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php
            $this->render_popup_styles( $settings );
            $this->render_popup_button( $settings, $video_link );
            ?>
        </div>
        <?php
    }

    protected function render_popup_styles( $settings ) {
        $id    = 'vp-' . $this->get_id();
        $base  = '.wcf--popup-video-wrapper[data-wid="' . esc_attr( $id ) . '"]';
        $pop   = $base . ' .wcf--popup-video';
        $close = $base . ' .wcf--popup-close';

        $out = [ 'd' => '', 't' => '', 'm' => '' ];

        foreach ( [ 'd' => '', 't' => 'tablet', 'm' => 'mobile' ] as $k => $device ) {
            $p = [];
            $c = [];
            $w = $device ? $this->dim_resp( $settings, 'popup_width',  $device ) : $this->dim( $settings, 'popup_width' );
            $h = $device ? $this->dim_resp( $settings, 'popup_height', $device ) : $this->dim( $settings, 'popup_height' );
            if ( $w ) $p[] = "width:{$w};max-width:none;";
            if ( $h ) $p[] = "height:{$h};";

            if ( ! $device ) {
                if ( $r = $this->dim_box( $settings, 'popup_border_radius' ) ) $p[] = "border-radius:{$r};";
            }

            $cs = $device ? $this->dim_resp( $settings, 'close_btn_size', $device )   : $this->dim( $settings, 'close_btn_size' );
            $is = $device ? $this->dim_resp( $settings, 'close_icon_size', $device )  : $this->dim( $settings, 'close_icon_size' );
            $ct = $device ? $this->dim_resp( $settings, 'close_offset_top', $device ) : $this->dim( $settings, 'close_offset_top' );
            $cr = $device ? $this->dim_resp( $settings, 'close_offset_right', $device ) : $this->dim( $settings, 'close_offset_right' );
            if ( $cs ) $c[] = "width:{$cs};height:{$cs};";
            if ( $is ) $c[] = "font-size:{$is};";
            if ( $ct ) $c[] = "top:{$ct};";
            if ( $cr ) $c[] = "right:{$cr};";

            $buf = '';
            if ( $p ) $buf .= "{$pop}{"   . implode( '', $p ) . '}';
            if ( $c ) $buf .= "{$close}{" . implode( '', $c ) . '}';
            $out[ $k ] = $buf;
        }

        if ( ! $out['d'] && ! $out['t'] && ! $out['m'] ) return;

        echo '<style>';
        if ( $out['d'] ) echo $out['d'];
        if ( $out['t'] ) echo '@media(max-width:1024px){' . $out['t'] . '}';
        if ( $out['m'] ) echo '@media(max-width:767px){'  . $out['m'] . '}';
        echo '</style>';
    }

    protected function render_popup_button( $settings, $video_link ) {
        $this->add_render_attribute( 'button', 'class', 'wcf-popup-btn ' );
        $this->add_render_attribute( 'button', 'data-src', esc_url( $video_link ) );

        // Add CORS-related attributes for iframe
        $this->add_render_attribute( 'button', 'data-iframe-allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' );
        $this->add_render_attribute( 'button', 'data-iframe-referrerpolicy', 'strict-origin-when-cross-origin' );

        // Popup variation + duration
        $this->add_render_attribute( 'button', 'data-popup-animation', $settings['popup_animation'] ?? 'scale-reveal' );
        $this->add_render_attribute( 'button', 'data-popup-duration', $settings['animation_duration']['size'] ?? 0.8 );

        // Unique widget id for scoped popup CSS
        $this->add_render_attribute( 'button', 'data-popup-wid', 'vp-' . $this->get_id() );

        // Overlay color
        if ( ! empty( $settings['overlay_color'] ) ) {
            $this->add_render_attribute( 'button', 'data-overlay-color', $settings['overlay_color'] );
        }

        // Close icon override
        if ( ! empty( $settings['close_icon']['value'] ) ) {
            ob_start();
            Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] );
            $close_icon_html = trim( ob_get_clean() );
            if ( $close_icon_html ) {
                $this->add_render_attribute( 'button', 'data-close-icon', $close_icon_html );
            }
        }

        // Close icon color bundle (colors only — sizes/offsets handled via scoped CSS)
        $close_style = [
            'color'       => $settings['close_color'] ?? '',
            'bg'          => $settings['close_bg_color'] ?? '',
            'border'      => $settings['close_border_color'] ?? '',
            'colorHover'  => $settings['close_color_hover'] ?? '',
            'bgHover'     => $settings['close_bg_color_hover'] ?? '',
            'borderHover' => $settings['close_border_color_hover'] ?? '',
        ];
        $this->add_render_attribute( 'button', 'data-close-style', wp_json_encode( array_filter( $close_style ) ) );
        
        if ( ! empty( $settings['active_ripple'] ) ) {
            $this->add_render_attribute( 'button', 'class', 'ripple' );
        }

        if ( ! empty( $settings['hover_animation'] ) ) {
            $this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
        }

        $migrated = isset( $settings['__fa4_migrated']['btn_icon'] );
        $is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
        ?>
        <button <?php $this->print_render_attribute_string( 'button' ); ?>
                aria-label="<?php echo esc_html__( 'Popup Video Open Icon', 'animation-addons-for-elementor-pro' ); ?>">
            <?php
            if ( ! empty( $settings['active_spinner'] ) ) {
                echo '<img class="spinner_image" src="' . esc_url( $settings['sipper_image']['url'] ) . '" alt="">';
            }
            ?>
            <?php $this->print_unescaped_setting( 'btn_text' ); ?>
            <?php if ( $is_new || $migrated ) :
                Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] );
            else : ?>
                <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
            <?php endif; ?>
        </button>
        <?php
    }

    private function dim_resp( $settings, $key, $device ) {
        $k = $key . '_' . $device;
        if ( empty( $settings[ $k ] ) || ! isset( $settings[ $k ]['size'] ) || $settings[ $k ]['size'] === '' ) {
            return '';
        }
        $unit = $settings[ $k ]['unit'] ?? 'px';
        return $settings[ $k ]['size'] . $unit;
    }

    private function dim( $settings, $key ) {
        if ( empty( $settings[ $key ] ) || ! isset( $settings[ $key ]['size'] ) || $settings[ $key ]['size'] === '' ) {
            return '';
        }
        $unit = $settings[ $key ]['unit'] ?? 'px';
        return $settings[ $key ]['size'] . $unit;
    }

    private function dim_box( $settings, $key ) {
        if ( empty( $settings[ $key ] ) ) return '';
        $v = $settings[ $key ];
        if ( ! isset( $v['top'] ) || $v['top'] === '' ) return '';
        $u = $v['unit'] ?? 'px';
        return "{$v['top']}{$u} {$v['right']}{$u} {$v['bottom']}{$u} {$v['left']}{$u}";
    }
}