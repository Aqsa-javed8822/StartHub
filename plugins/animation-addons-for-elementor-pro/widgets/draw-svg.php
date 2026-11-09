<?php

namespace WCFAddonsPro\Widgets;

if (! defined('ABSPATH')) {
    exit;   // Exit if accessed directly.
}

class Draw_Svg extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'wcf-gsap-drawsvg';
    }

    public function get_title()
    {
        return __('DrawSVG', 'animation-addons-for-elementor-pro');
    }

    public function get_icon()
    {
        return 'wcf eicon-animation';
    }

    public function get_categories()
    {
        return ['animation-addons-for-elementor-pro'];
    }


    public function get_style_depends()
    {
        return [];
    }

    public function get_script_depends()
    {
        // Register DrawSVG Plugin
        wp_register_script('DrawSVGPlugin', WCF_ADDONS_PRO_URL . 'assets/lib/DrawSVGPlugin.min.js', ['gsap'], '3.13.0', true);
        wp_register_script('MotionPathPlugin', WCF_ADDONS_PRO_URL . 'assets/lib/MotionPathPlugin.min.js', ['gsap'], '3.13.0', true);
        wp_register_script('wcf-gsapdrawsvg', WCF_ADDONS_PRO_URL . 'assets/js/gsap-draw.js', ['DrawSVGPlugin', 'ScrollTrigger', 'MotionPathPlugin'], false, true);
        return ['wcf-gsapdrawsvg'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'animation_content',
            [
                'label' => __('Content', 'animation-addons-for-elementor-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'svg_type',
            [
                'label' => esc_html__('SVG Type', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'svg_image',
                'options' => [
                    'svg_code' => esc_html__('SVG Code', 'animation-addons-for-elementor-pro'),
                    'svg_image' => esc_html__('SVG Image', 'animation-addons-for-elementor-pro'),
                ],
            ]
        );

        $this->add_control(
            'svg_image',
            [
                'label' => esc_html__('Choose Image', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['svg'],
                'condition' => [
                    'svg_type' => ['svg_image'],
                ],
                'default' => [
                    'url' => WCF_ADDONS_PRO_URL . 'image/draw.svg',
                ],
            ]
        );

        $this->add_control(
            'svg_code',
            [
                'label' => __('SVG Code', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'condition' => [
                    'svg_type' => ['svg_code'],
                ],
                'description' => __('Paste your SVG code here. Ensure paths are used.', 'animation-addons-for-elementor-pro'),
                'default' => '<svg version="1.1" id="hello" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 1920 350" enable-background="new 0 0 1920 350" xml:space="preserve">
<circle id="hello_dot" fill="#EB5E66" cx="1263" cy="307" r="9"/>
<path id="hello_path" fill="none" stroke="#FFFFFF" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M-38.026,302.923c408-27.63,633.885-42.641,779.446-124.426c157.935-150.86,73.548-204.151,41.216-137.429 c-21.052,42.679-49.359,183.118-52.915,202.439c-1.565,8.507-11.806,44.698-0.284,47.438c11.949,2.884,24.751-31.721,35.846-47.149c13.371-18.6,22.759-15.14,27.169-12.833c12.66,6.777,2.876,36.269,0.458,62.223c-6.117,68.345,41.394,12.558,56.899-5.033 c21.764-24.656,34.645-36.968,47.032-63.29c9.531-29.27-21.886-35.138-33.266-14.951c-8.819,15.572-2.987,39.796,10.668,51.331 c61.023,51.186,155.617-121.838,165.147-168.555c9.104-44.265-22.901-44.41-37.553-17.447c-19.203,35.182-33.57,72.67-45.092,110.88 c-11.664,38.498-25.746,79.303-20.057,120.108c1.422,9.949,5.263,22.349,16.785,23.503c4.694,0.433,9.53-0.433,13.798-2.451 c63.726-31.433,141.819-148.08,153.056-166.969c45.234-76.275-9.957-89.973-37.979-33.307 c-45.376,91.703-49.501,140.727-48.932,174.755c1.138,85.647,65.149,4.902,80.796-15.861c18.208-24.079,37.126-49.745,69.985-49.745 c6.97,0,13.94,1.442,20.057,4.758c21.479,11.968,25.604,38.786,10.811,56.954c-12.233,14.995-33.143,16.726-45.234,6.2 c-9.815-8.651-12.66-23.647-9.104-36.479c3.556-12.688,11.522-24.656,22.617-31.577c70.52-72.501,736.66,49.821,873.434,74.595"/>
</svg>'
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'animation_settings',
            [
                'label' => __('Animation', 'animation-addons-for-elementor-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'timeline',
            [
                'label' => esc_html__('Timeline', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Enable', 'animation-addons-for-elementor-pro'),
                'label_off' => esc_html__('Disable', 'animation-addons-for-elementor-pro'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'animation_method',
            [
                'label' => __('Animation Method', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'fromTo' => 'FromTo',
                    'from'   => 'From',
                    'to'     => 'To',
                ],
                'default' => 'from',
                'label_block' => true

            ]
        );


        // From (Start) Value Control
        $this->add_control(
            'animation_from',
            [
                'label' => __('From', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '0%',
                'label_block' => true,
                'condition' => [
                    'animation_method' => ['from', 'fromTo'],
                ],
            ]
        );

        // To (End) Value Control
        $this->add_control(
            'animation_to',
            [
                'label' => __('To Value (End)', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '100%',
                'label_block' => true,
                'condition' => [
                    'animation_method' => ['to', 'fromTo'],
                ],
            ]
        );


        $this->add_control(
            'aae_duration',
            [
                'label' => __('Animation Duration', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
            ]
        );

        $this->add_control(
            'aae_delay',
            [
                'label' => __('Delay', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.5,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
            ]
        );


        $this->add_control(
            'aae_ease',
            [
                'label' => __('Easing', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none'       => 'None',
                    'linear'       => 'Linear',
                    'power1.inOut' => 'Power1 InOut',
                    'power2.inOut' => 'Power2 InOut',
                    'elastic.out'  => 'Elastic Out',
                    'sine.inOut'   => 'Sine Out',
                    'sine.in'      => 'Sine IN',
                    'expo.inOut'   => 'Expo inOut',
                    'quad.inOut'   => 'Quad InOut',
                    'circ.inOut'   => 'Circ InOut',
                ],
                'default' => 'sine.inOut',
            ]
        );

        $this->add_control(
            'enable_yoyo',
            [
                'label' => __('Enable Yoyo', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => __('Reverse the animation after completing a loop.', 'animation-addons-for-elementor-pro'),
            ]
        );

        $this->add_control(
            'aae_repeat_count',
            [
                'label' => __('Repeat', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition' => [
                    'enable_yoyo' => 'yes',
                ],
                'default' => [
                    'size' => -1,
                ],
                'range' => [
                    'px' => [
                        'min' => -1,
                        'max' => 5,
                        'step' => 1,
                    ],
                ],
            ]
        );


        $this->add_control(
            'repeatDelay',
            [
                'label' => __('RepeatDelay', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.5,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'animation_settings_scroll',
            [
                'label' => __('ScrollTrigger', 'animation-addons-for-elementor-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        // ScrollTrigger Controls
        $this->add_control(
            'scroll_trigger',
            [
                'label' => __('Enable ScrollTrigger', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'trigger_start',
            [
                'label' => __('Trigger Start', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'top 75%',
                'description' => __('Position where the animation starts (e.g., "top 75%").', 'animation-addons-for-elementor-pro'),
                'condition' => [
                    'scroll_trigger' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'trigger_end',
            [
                'label' => __('Trigger End', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'bottom 0%',
                'description' => __('Position where the animation ends (e.g., "top 25%").', 'animation-addons-for-elementor-pro'),
                'condition' => [
                    'scroll_trigger' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'scrub',
            [
                'label' => esc_html__('Scrub', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'false',
                'options' => [
                    'false' => esc_html__('False', 'animation-addons-for-elementor-pro'),
                    'true' => esc_html__('True', 'animation-addons-for-elementor-pro'),
                    'number' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                ],
                'condition' => [
                    'scroll_trigger' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'scrub_number',
            [
                'label' => esc_html__('Scrub Number', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => .1,
                'default' => 1,
                'condition' => [
                    'scrub' => 'number',
                ],
            ]

        );

        $this->end_controls_section();

        $this->start_controls_section(
            'animation_settings_linkable',
            [
                'label' => __('Linkable', 'animation-addons-for-elementor-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'aae_svg_linkable',
            [
                'label' => __('Enable', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'aae_website_link',
            [
                'label' => esc_html__('Link', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow'],
                'frontend_available' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                    // 'custom_attributes' => '',
                ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'svg_style_section',
            [
                'label' => esc_html__('Style', 'animation-addons-for-elementor-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'svg_stroke',
            [
                'label' => esc_html__('Stroke', 'animation-addons-for-elementor-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} svg path' => 'stroke: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
			'svg_width',
			[
				'label' => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

         $this->add_responsive_control(
			'svg_height',
			[
				'label' => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],			
				'selectors' => [
					'{{WRAPPER}} svg' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Get animation settings
        $scroll_trigger = isset($settings['scroll_trigger']) ? $settings['scroll_trigger'] : '';
        $method         = $settings['animation_method'];
        $from           = $settings['animation_from'];
        $to             = $settings['animation_to'];

        $duration       = isset($settings['aae_duration']['size']) ? $settings['aae_duration']['size'] : 1;
        $delay          = isset($settings['aae_delay']['size']) ? $settings['aae_delay']['size'] : 0.5;
        $repeat         = isset($settings['aae_repeat_count']['size']) ? $settings['aae_repeat_count']['size'] : 0;
        $repeatDelay    = isset($settings['repeatDelay']['size']) ? $settings['repeatDelay']['size'] : 0;
        $ease           = $settings['aae_ease'];
        $scrub          = isset($settings['scrub']) ? $settings['scrub'] : false;
        $scrub_number          = isset($settings['scrub_number']) ? $settings['scrub_number'] : 1;
        $timeline_yoyo  = $settings['enable_yoyo'];
        $timeline  = $settings['timeline'];
        $start          = isset($settings['trigger_start']) ? $settings['trigger_start'] : '';
        $end            = isset($settings['trigger_end']) ? $settings['trigger_end'] : '';
        $svg_code       = null;
        if ($settings['svg_type'] == 'svg_code') {
            $svg_code = $settings["svg_code"];
        } elseif (isset($settings['svg_image']['url']) && $settings['svg_image']['url'] != '') {
            $svg_code = file_get_contents($settings['svg_image']['url']);
        }

        // Render HTML and pass animation data through data attributes
        echo '<div class="aae-gsap-draw-svg" 
                 data-scroll_trigger="' . esc_attr($scroll_trigger) . '" 
                 data-method="' . esc_attr($method) . '"                  
                 data-from="' . esc_attr($from) . '" 
                 data-to="' . esc_attr($to) . '" 
                 data-duration="' . esc_attr($duration) . '" 
                 data-timeline="' . esc_attr($timeline) . '" 
                 data-delay="' . esc_attr($delay) . '" 
                 data-repeat="' . esc_attr($repeat) . '" 
                 data-repeatDelay="' . esc_attr($repeatDelay) . '" 
                 data-ease="' . esc_attr($ease) . '" 
                 data-scrub="' . esc_attr($scrub) . '" 
                 data-scrub_number="' . esc_attr($scrub_number) . '" 
                 data-yoyo="' . esc_attr($timeline_yoyo) . '" 
                 data-scrolltriggerStart="' . esc_attr($start) . '" 
                 data-scrolltriggerEnd="' . esc_attr($end) . '">
                  ' . $svg_code . '
              </div>';
    }
}
