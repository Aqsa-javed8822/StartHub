<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Embed;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Plugin;


if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * ScrollMotion Cards
 *
 * Elementor widget for ScrollMotion Cards.
 *
 * @since 1.0.0
 */
class ScrollMotion_Cards extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     *
     * @access public
     */
    public function get_name()
    {
        return 'wcf--scrollmotion-cards';
    }

    /**
     * Retrieve the widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     *
     * @access public
     */
    public function get_title()
    {
        return esc_html__('ScrollMotion Cards', 'animation-addons-for-elementor-pro');
    }

    /**
     * Retrieve the widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     *
     * @access public
     */
    public function get_icon()
    {
        return 'wcf     eicon-product-related';
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
    public function get_categories()
    {
        return ['animation-addons-for-elementor-pro'];
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
    public function get_script_depends()
    {
        return ['aae-scrollmotion-cards'];
    }

    /**
     * Requires css files.
     *
     * @return array
     */
    public function get_style_depends()
    {
        return ['aae-scrollmotion-cards'];
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
    protected function register_controls()
    {

        $this->start_controls_section(
            'section_title_layout',
            [
                'label' => __('Section Title', 'animation-addons-for-elementor-pro'),
            ]
        );
        $this->add_control(
            'section_title',
            [
                'label' => esc_html__('Section Title', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Default title', 'animation-addons-for-elementor-pro'),
                'placeholder' => esc_html__('Type your title here', 'animation-addons-for-elementor-pro'),
            ]
        );
        $this->end_controls_section();
        // Layout Controls
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Card Content', 'animation-addons-for-elementor-pro'),
            ]
        );
        // Main Repeater
        $main_repeater = new \Elementor\Repeater();

        $main_repeater->add_control(
            'content_type',
            [
                'label'   => esc_html__('Content Type', 'animation-addons-for-elementor-pro'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'content'  => esc_html__('Content', 'animation-addons-for-elementor-pro'),
                    'template' => esc_html__('Saved Templates', 'animation-addons-for-elementor-pro'),
                ],
                'default' => 'content',
            ]
        );
        $main_repeater->add_control(
            'elementor_templates',
            [
                'label'       => esc_html__('Save Template', 'animation-addons-for-elementor-pro'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => false,
                'multiple'    => false,
                'options'     => wcf_addons_get_saved_template_list(),
                'condition'   => [
                    'content_type' => 'template',
                ],
            ]
        );
        $main_repeater->add_control(
            'group_title',
            [
                'label' => esc_html__('Group Title', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Group Title', 'animation-addons-for-elementor-pro'),
                'label_block' => true,
            ]
        );

        $main_repeater->add_control(
            'group_image',
            [
                'label' => esc_html__('Choose Image', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Nested Repeater
        $nested_repeater = new \Elementor\Repeater();

        $nested_repeater->add_control(
            'list_title',
            [
                'label' => esc_html__('Item Title', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Item Title', 'animation-addons-for-elementor-pro'),
                'label_block' => true,
            ]
        );


        $nested_repeater->add_control(
            'list_icon',
            [
                'label' => esc_html__('Icon', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'circle',
                        'dot-circle',
                        'square-full',
                    ],
                    'fa-regular' => [
                        'circle',
                        'dot-circle',
                        'square-full',
                    ],
                ],
            ]
        );

        // Main Repeater added to Nested Repeater
        $main_repeater->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $nested_repeater->get_controls(),
                'default' => [
                    [
                        'list_title' => esc_html__('Item #1', 'animation-addons-for-elementor-pro'),
                        'list_color' => '#000000',
                    ],
                    [
                        'list_title' => esc_html__('Item #2', 'animation-addons-for-elementor-pro'),
                        'list_color' => '#000000',
                    ],
                ],
                'title_field' => '<# var title = ( typeof list_title !== "undefined" && list_title ) ? list_title : "Item"; #>{{{ title }}}',
                'prevent_empty' => false, // Allow empty items
            ]
        );

        // Main Repeater Add Controls
        $this->add_control(
            'list_groups',
            [
                'label' => esc_html__('List Groups', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $main_repeater->get_controls(),
                'default' => [
                    [
                        'group_title' => esc_html__('Group #1', 'animation-addons-for-elementor-pro'),
                        'group_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'items' => [
                            [
                                'list_title' => esc_html__('Item #1', 'animation-addons-for-elementor-pro'),
                                'list_color' => '#000000',
                            ],
                            [
                                'list_title' => esc_html__('Item #2', 'animation-addons-for-elementor-pro'),
                                'list_color' => '#000000',
                            ],
                        ],
                    ],
                    [
                        'group_title' => esc_html__('Group #2', 'animation-addons-for-elementor-pro'),
                        'group_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'items' => [
                            [
                                'list_title' => esc_html__('Item #1', 'animation-addons-for-elementor-pro'),
                                'list_color' => '#000000',
                            ],
                        ],
                    ],
                    [
                        'group_title' => esc_html__('Group #3', 'animation-addons-for-elementor-pro'),
                        'group_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'items' => [
                            [
                                'list_title' => esc_html__('Item #1', 'animation-addons-for-elementor-pro'),
                                'list_color' => '#000000',
                            ],
                        ],
                    ],
                    [
                        'group_title' => esc_html__('Group #4', 'animation-addons-for-elementor-pro'),
                        'group_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'items' => [
                            [
                                'list_title' => esc_html__('Item #1', 'animation-addons-for-elementor-pro'),
                                'list_color' => '#000000',
                            ],
                        ],
                    ],
                    [
                        'group_title' => esc_html__('Group #5', 'animation-addons-for-elementor-pro'),
                        'group_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'items' => [
                            [
                                'list_title' => esc_html__('Item #1', 'animation-addons-for-elementor-pro'),
                                'list_color' => '#000000',
                            ],
                        ],
                    ],

                ],
                'title_field' => '<# var title = ( typeof group_title !== "undefined" && group_title ) ? group_title : "Group"; #>{{{ title }}}',
                'prevent_empty' => false, // Allow empty groups
            ]
        );

        $this->end_controls_section();

        // Settings
        $this->start_controls_section(
            'general_setting',
            [
                'label' => __('General Setting', 'animation-addons-for-elementor-pro'),
            ]
        );

        $this->add_control(
            'show_section_title',
            [
                'label' => esc_html__('Show Title', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'animation-addons-for-elementor-pro'),
                'label_off' => esc_html__('Hide', 'animation-addons-for-elementor-pro'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'show_card_title',
            [
                'label' => esc_html__('Card Title', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'animation-addons-for-elementor-pro'),
                'label_off' => esc_html__('Hide', 'animation-addons-for-elementor-pro'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->end_controls_section();
        //$this->register_ping_area_controls();

        // style 
        $this->register_style_controls();
    }

    protected function register_style_controls()
    {
        //item width
        $this->start_controls_section(
            'item_align',
            [
                'label' => esc_html__('Item Style', 'animation-addons-for-elementor-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'item_width',
            [
                'label' => esc_html__('Width', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 410,
                ],
                'selectors' => [
                    '{{WRAPPER}} .aae-toolkit-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_width_auto',
            [
                'label' => esc_html__('Alignment', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'animation-addons-for-elementor-pro'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'auto' => [
                        'title' => esc_html__('Center', 'animation-addons-for-elementor-pro'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'animation-addons-for-elementor-pro'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'auto',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .aae-toolkit-item' => 'margin: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_style',
            [
                'label' => esc_html__('Grid', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'animation-addons-for-elementor-pro'),
                    'auto' => esc_html__('Auto', 'animation-addons-for-elementor-pro'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .aae-toolkit-grid' => 'grid-template-columns: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // title 
        $this->start_controls_section(
            'title_section',
            [
                'label' => esc_html__('Title', 'animation-addons-for-elementor-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Section Title', 'animation-addons-for-elementor-pro'),
                'name' => 'sec_title_typography',
                'selector' => '{{WRAPPER}} .aae-toolkit-title',
            ]
        );
        $this->add_control(
            'sec_title_color',
            [
                'label' => esc_html__('Color', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aae-toolkit-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Card Title', 'animation-addons-for-elementor-pro'),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .toolkit-top-title',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .toolkit-top-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_section();

        // Image 

        $this->start_controls_section(
            'image_section',
            [
                'label' => esc_html__('Image Style', 'animation-addons-for-elementor-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Image Width', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .aae-toolkit-card-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_align',
            [
                'label' => esc_html__('Alignment', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'animation-addons-for-elementor-pro'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'animation-addons-for-elementor-pro'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'animation-addons-for-elementor-pro'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .aae-toolkit-card-icon' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'default' => [
                    'top' => 2,
                    'right' => 0,
                    'bottom' => 2,
                    'left' => 0,
                    'unit' => 'em',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .aae-toolkit-card-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // list content 
        $this->start_controls_section(
            'list_section',
            [
                'label' => esc_html__('List', 'animation-addons-for-elementor-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_typography',
                'selector' => '{{WRAPPER}} .aae-content-title',
            ]
        );
        $this->add_control(
            'list_color',
            [
                'label' => esc_html__('Text Color', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aae-content-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_section();

        // list Icon 
        $this->start_controls_section(
            'list_icon',
            [
                'label' => esc_html__('List Icon', 'animation-addons-for-elementor-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_width',
            [
                'label' => esc_html__('Width', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .aae-content-item-bg svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_height',
            [
                'label' => esc_html__('Height', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .aae-content-item-bg svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aae-content-item-bg svg path' => 'fill: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_ping_area_controls()
    {
        $this->start_controls_section(
            'scrlm_section_pin-area',
            [
                'label' => esc_html__('Pin Options', 'animation-addons-for-elementor-pro'),
            ]
        );

        $this->add_control(
            'wcf_pin_area_trigger',
            [
                'label'       => esc_html__('Pin Wrapper', 'animation-addons-for-elementor-pro'),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => [
                    ''       => esc_html__('Default', 'animation-addons-for-elementor-pro'),
                    'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                ],
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'wcf_custom_pin_area',
            [
                'label'              => esc_html__('Custom Pin Area', 'animation-addons-for-elementor-pro'),
                'description'        => esc_html__('Add the section class where the element will be pin. please use the parent section or container class.', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::TEXT,
                'ai'                 => false,
                'placeholder'        => esc_html__('.pin_area', 'animation-addons-for-elementor-pro'),
                'frontend_available' => true,
                'render_type'        => 'none',
                'condition'          => [
                    'wcf_pin_area_trigger' => 'custom',
                ]
            ]
        );

        $this->add_control(
            'wcf_pin_end_trigger',
            [
                'label'              => esc_html__('End Trigger', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::TEXT,
                'ai'                 => false,
                'placeholder'        => esc_html__('.end_trigger', 'animation-addons-for-elementor-pro'),
                'frontend_available' => true,
                'render_type'        => 'none',
                'separator'          => 'after',
            ]
        );

        $this->add_control(
            'wcf_pin_status',
            [
                'label'     => esc_html__('Pin', 'animation-addons-for-elementor-pro'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'true',
                'options'   => [
                    'true'   => esc_html__('True', 'animation-addons-for-elementor-pro'),
                    'false'  => esc_html__('False', 'animation-addons-for-elementor-pro'),
                    'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                ],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'wcf_pin_custom',
            [
                'label'       => esc_html__('Custom Pin', 'animation-addons-for-elementor-pro'),
                'type'        => Controls_Manager::TEXT,
                'frontend_available' => true,
                'render_type' => 'none',
                'placeholder' => esc_html__('.pin_class', 'animation-addons-for-elementor-pro'),
                'condition'   => [
                    'wcf_pin_status' => 'custom',
                ]
            ]
        );

        $this->add_control(
            'wcf_pin_spacing',
            [
                'label'     => esc_html__('Pin Spacing', 'animation-addons-for-elementor-pro'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'false',
                'options'   => [
                    'true'   => esc_html__('True', 'animation-addons-for-elementor-pro'),
                    'false'  => esc_html__('False', 'animation-addons-for-elementor-pro'),
                    'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                ],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'wcf_pin_spacing_custom',
            [
                'label'       => esc_html__('Custom Pin Spacing', 'animation-addons-for-elementor-pro'),
                'type'        => Controls_Manager::TEXT,
                'frontend_available' => true,
                'render_type' => 'none',
                'placeholder' => esc_html__('.custom-class', 'animation-addons-for-elementor-pro'),
            ]
        );

        $this->add_control(
            'wcf_pin_type',
            [
                'label'     => esc_html__('Pin Type', 'animation-addons-for-elementor-pro'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'transform',
                'options'   => [
                    'fixed'   => esc_html__('Fixed', 'animation-addons-for-elementor-pro'),
                    'transform'  => esc_html__('Transform', 'animation-addons-for-elementor-pro'),
                ],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'wcf_pin_scrub',
            [
                'label'     => esc_html__('Pin Scrub', 'animation-addons-for-elementor-pro'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'true',
                'options'   => [
                    'true'   => esc_html__('True', 'animation-addons-for-elementor-pro'),
                    'false'  => esc_html__('False', 'animation-addons-for-elementor-pro'),
                    'number'  => esc_html__('Number', 'animation-addons-for-elementor-pro'),
                ],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'wcf_pin_scrub_number',
            [
                'label' => esc_html__('Scrub Number', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 1,
                'frontend_available' => true,
                'render_type' => 'none',
                'condition'   => [
                    'wcf_pin_scrub' => 'number',
                ],
            ]
        );

        $this->add_control(
            'wcf_pin_markers',
            [
                'label'     => esc_html__('Pin Markers', 'animation-addons-for-elementor-pro'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'false',
                'options'   => [
                    'true'   => esc_html__('True', 'animation-addons-for-elementor-pro'),
                    'false'  => esc_html__('False', 'animation-addons-for-elementor-pro'),
                ],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'wcf_pin_area_start',
            [
                'label'              => esc_html__('Start', 'animation-addons-for-elementor-pro'),
                'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'default'            => 'top top',
                'frontend_available' => true,
                'options'            => [
                    'top top'       => esc_html__('Top Top', 'animation-addons-for-elementor-pro'),
                    'top center'    => esc_html__('Top Center', 'animation-addons-for-elementor-pro'),
                    'top bottom'    => esc_html__('Top Bottom', 'animation-addons-for-elementor-pro'),
                    'center top'    => esc_html__('Center Top', 'animation-addons-for-elementor-pro'),
                    'center center' => esc_html__('Center Center', 'animation-addons-for-elementor-pro'),
                    'center bottom' => esc_html__('Center Bottom', 'animation-addons-for-elementor-pro'),
                    'bottom top'    => esc_html__('Bottom Top', 'animation-addons-for-elementor-pro'),
                    'bottom center' => esc_html__('Bottom Center', 'animation-addons-for-elementor-pro'),
                    'bottom bottom' => esc_html__('Bottom Bottom', 'animation-addons-for-elementor-pro'),
                    'custom'        => esc_html__('custom', 'animation-addons-for-elementor-pro'),
                ],
                'default'           => esc_html__('Top 20%', 'animation-addons-for-elementor-pro'),
                'render_type'        => 'none',

            ]
        );

        $this->add_responsive_control(
            'wcf_pin_area_start_custom',
            [
                'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::TEXT,
                'default'            => esc_html__('top 20%', 'animation-addons-for-elementor-pro'),
                'placeholder'        => esc_html__('top top+=100', 'animation-addons-for-elementor-pro'),
                'frontend_available' => true,
                'render_type'        => 'none',
                'condition'          => [
                    'wcf_pin_area_start'   => 'custom',
                ],
            ]
        );

        $this->add_control(
            'wcf_pin_area_end',
            [
                'label'              => esc_html__('End', 'animation-addons-for-elementor-pro'),
                'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'default'            => 'bottom top',
                'frontend_available' => true,
                'render_type'        => 'none',
                'options'            => [
                    'top top'       => esc_html__('Top Top', 'animation-addons-for-elementor-pro'),
                    'top center'    => esc_html__('Top Center', 'animation-addons-for-elementor-pro'),
                    'top bottom'    => esc_html__('Top Bottom', 'animation-addons-for-elementor-pro'),
                    'center top'    => esc_html__('Center Top', 'animation-addons-for-elementor-pro'),
                    'center center' => esc_html__('Center Center', 'animation-addons-for-elementor-pro'),
                    'center bottom' => esc_html__('Center Bottom', 'animation-addons-for-elementor-pro'),
                    'bottom top'    => esc_html__('Bottom Top', 'animation-addons-for-elementor-pro'),
                    'bottom center' => esc_html__('Bottom Center', 'animation-addons-for-elementor-pro'),
                    'bottom bottom' => esc_html__('Bottom Bottom', 'animation-addons-for-elementor-pro'),
                    'custom'        => esc_html__('custom', 'animation-addons-for-elementor-pro'),
                ],
                'default'           => esc_html__('Bottom -800', 'animation-addons-for-elementor-pro'),
            ]
        );

        $this->add_responsive_control(
            'wcf_pin_area_end_custom',
            [
                'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::TEXT,
                'frontend_available' => true,
                'render_type'        => 'none',
                'default'            => esc_html__('bottom -=800', 'animation-addons-for-elementor-pro'),
                'placeholder'        => esc_html__('bottom top+=100', 'animation-addons-for-elementor-pro'),
                'condition'          => [
                    'wcf_pin_area_end'     => 'custom',
                ],
            ]
        );

        $dropdown_options = [
            '' => esc_html__('None', 'animation-addons-for-elementor-pro'),
            'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
        ];

        $excluded_breakpoints = [
            'laptop',
            'tablet_extra',
            'widescreen',
        ];

        foreach (Plugin::$instance->breakpoints->get_active_breakpoints() as $breakpoint_key => $breakpoint_instance) {
            // Exclude the larger breakpoints from the dropdown selector.
            if (in_array($breakpoint_key, $excluded_breakpoints, true)) {
                continue;
            }

            $dropdown_options[$breakpoint_key] = sprintf(
                /* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value. */
                esc_html__('%1$s (%2$s %3$dpx)', 'animation-addons-for-elementor-pro'),
                $breakpoint_instance->get_label(),
                '>',
                $breakpoint_instance->get_value()
            );
        }

        $this->add_control(
            'wcf_pin_breakpoint',
            [
                'label'              => esc_html__('Breakpoint', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'description'        => esc_html__('Note: Choose at which breakpoint Pin element will work.', 'animation-addons-for-elementor-pro'),
                'options'            => $dropdown_options,
                'frontend_available' => true,
                'render_type'        => 'none',
                'default'            => 'mobile',
            ]
        );
        $this->add_control(
            'wcf_pin_min_media',
            [
                'label' => esc_html__('Min Width', 'animation-addons-for-elementor-pro'),
                'type' => Controls_Manager::NUMBER,
                'condition'          => ['wcf_pin_breakpoint' => 'custom'],
                'frontend_available' => true,
                'render_type'        => 'none',
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
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $list_groups = $settings['list_groups'];

        if (empty($list_groups)) {
            return;
        }
        // $scroll_trigger = [
        //     'start'    => $settings['wcf_pin_area_start'] === 'custom'
        //         ? $settings['wcf_pin_area_start_custom']
        //         : $settings['wcf_pin_area_start'],
        //     'end'      => $settings['wcf_pin_area_end'] === 'custom'
        //         ? $settings['wcf_pin_area_end_custom']
        //         : $settings['wcf_pin_area_end'],
        //     'pin'      => $settings['wcf_pin_status'] === 'custom'
        //         ? $settings['wcf_pin_custom']
        //         : filter_var($settings['wcf_pin_status'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        //     'pinSpacing' => $settings['wcf_pin_spacing'] === 'custom'
        //         ? $settings['wcf_pin_spacing_custom']
        //         : filter_var($settings['wcf_pin_spacing'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        //     'pinType'  => $settings['wcf_pin_type'],
        //     'scrub'    => $settings['wcf_pin_scrub'] === 'number'
        //         ? (float) $settings['wcf_pin_scrub_number']
        //         : filter_var($settings['wcf_pin_scrub'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        //     'markers'  => filter_var($settings['wcf_pin_markers'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        //     'trigger'  => $settings['wcf_pin_area_trigger'] === 'custom'
        //         ? $settings['wcf_custom_pin_area']
        //         : null,
        //     'endTrigger' => !empty($settings['wcf_pin_end_trigger'])
        //         ? $settings['wcf_pin_end_trigger']
        //         : null,
        //     'breakpoint' => $settings['wcf_pin_breakpoint'],
        //     'minWidth'   => $settings['wcf_pin_breakpoint'] === 'custom'
        //         ? (int) $settings['wcf_pin_min_media']
        //         : null,
        // ];
        // data-scroll-trigger='<?php echo wp_json_encode($scroll_trigger);
?>
<div class="aae-card-area section-padding-bottom">
    <div class="custom-container">
        <div class="aae-toolkit-grid">
            <?php
                    if ($settings['show_section_title'] == 'yes') {
                    ?>
            <div class="aae-toolkit-item ">
                <h2 class="aae-toolkit-title" style="text-align: end">
                    <?php echo esc_html__($settings['section_title'], 'animation-addons-for-elementor-pro'); ?>
                </h2>
            </div>
            <?php
                    }
                    ?>
            <div class="aae-toolkit-item">
                <ul class="aae-toolkit-item-card">
                    <?php foreach ($list_groups as $listItem) : ?>
                    <li class="aae-toolkit-item-cards toolkit-card-anim">
                        <?php if ($listItem['elementor_templates'] != '' && $listItem['content_type'] == 'template') { ?>
                        <?php
                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                        echo Plugin::$instance->frontend->get_builder_content($listItem['elementor_templates']);
                                    } ?>
                        <?php if ($listItem['content_type'] == 'content') { ?>
                        <div class="aae-toolkit-item-card-list">
                            <h2 class="aae-toolkit-title toolkit-title-animm two aae-mobile-title">
                                <span><?php echo esc_html($listItem['group_title']); ?></span>
                            </h2>
                            <?php if (! empty($listItem['group_image']['url'])) : ?>
                            <div class="aae-toolkit-card-icon">
                                <img src="<?php echo esc_url($listItem['group_image']['url']); ?>"
                                    alt="<?php echo esc_attr($listItem['group_title']); ?>" />
                            </div>
                            <?php endif; ?>
                            <div class="aae-toolkit-card-content">
                                <?php foreach ($listItem['items'] as $listContent) : ?>
                                <div class="aae-content-item-wrapper">
                                    <div class="aae-content-item-bg">
                                        <?php
                                                            if (! empty($listContent['list_icon'])) {
                                                                Icons_Manager::render_icon($listContent['list_icon'], ['aria-hidden' => 'true']);
                                                            }
                                                            ?>
                                    </div>
                                    <p
                                        class="aae-content-title nested-repeater-item-<?php echo esc_attr($listContent['_id']); ?>">
                                        <?php echo esc_html($listContent['list_title']); ?>
                                    </p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php } ?>
                    </li>
                    <?php endforeach; ?>
                </ul>

            </div>
            <?php
                    if ($settings['show_card_title'] == 'yes') {
                    ?>
            <div class="aae-toolkit-item content toolkit-title-container">
                <div class="title-wrapper-container">
                    <h3 class="aae-toolkit-title toolkit-title-anim">
                        <div class="top-titles">
                            <?php foreach ($list_groups as $listItem) : ?>
                            <span class="toolkit-top-title"><?php echo ($listItem['group_title']); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </h3>
                </div>
            </div>
            <?php
                    }
                    ?>
        </div>
    </div>
</div>
<?php
    }
}