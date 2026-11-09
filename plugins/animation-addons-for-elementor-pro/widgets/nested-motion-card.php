<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Modules\NestedElements\Base\Widget_Nested_Base;
use Elementor\Modules\NestedElements\Controls\Control_Nested_Repeater;
use Elementor\Repeater;
use Elementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Nested_Motion_Card extends Widget_Nested_Base {

	public function get_name() {
		return 'aae-nested-motion-card';
	}

	public function get_title() {
		return esc_html__( 'Nested Motion Card', 'animation-addons-for-elementor-pro' );
	}

    /**
     * Get widget categories.
     *
     * @since 2.5.2
     * @return array
     */
    public function get_categories() {
        return array( 'wcf-wc-addon' );
    }

	public function get_icon() {
		return 'wcf eicon-nested-carousel';
	}

	public function get_keywords() {
		return [ 'Carousel', 'Slides', 'Nested', 'Media', 'Gallery', 'Image' ];
	}

	// TODO: Replace this check with `is_active_feature` on 3.28.0 to support is_active_feature second parameter.
	public function show_in_panel() {
		return Plugin::$instance->experiments->is_feature_active( 'nested-elements' ) && Plugin::$instance->experiments->is_feature_active( 'container' );
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 3.24.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return ['aae-scrollmotion-cards'];
	}

	/**
	 * Get script dependencies.
	 *
	 * Retrieve the list of script dependencies the widget requires.
	 *
	 * @since 3.27.0
	 * @access public
	 *
	 * @return array Widget script dependencies.
	 */
	public function get_script_depends(): array {

		return ['aae-scrollmotion-cards'];
	}

	protected function get_default_children_elements() {
		return [
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Card #1', 'animation-addons-for-elementor-pro' ),
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Card #2', 'animation-addons-for-elementor-pro' ),
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Card #3', 'animation-addons-for-elementor-pro' ),
				],
			],
		];
	}

	protected function get_default_repeater_title_setting_key() {
		return 'card_title';
	}

	protected function get_default_children_title() {
		/* translators: %d: Slide number. */
		return esc_html__( 'Card #%d', 'animation-addons-for-elementor-pro' );
	}

	protected function get_default_children_placeholder_selector() {
		return '.aae-toolkit-item-card';
	}
	protected function get_default_children_container_placeholder_selector() {
		return '.aae-motion-card';
	}

	protected function get_html_wrapper_class() {
		return 'aae-motion-nested-card';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Cards', 'animation-addons-for-elementor-pro' ),
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'card_title',
			[
				'label' => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Card Title', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Card Title', 'animation-addons-for-elementor-pro' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'card_items',
			[
				'label' => esc_html__( 'Card Items', 'animation-addons-for-elementor-pro' ),
				'type' => Control_Nested_Repeater::CONTROL_TYPE,				
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'card_title' => esc_html__( 'Card #1', 'animation-addons-for-elementor-pro' ),
					],
					[
						'card_title' => esc_html__( 'Card #2', 'animation-addons-for-elementor-pro' ),
					],
					[
						'card_title' => esc_html__( 'Card #3', 'animation-addons-for-elementor-pro' ),
					],
				],
				'frontend_available' => true,
				'title_field' => '{{{ card_title }}}',
			]
		);
		$this->add_responsive_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors_dictionary' => [
					''    => 'none',
					'yes' => 'block',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--aae-show-title: {{VALUE}};',
				],
			]
		);
		$this->add_control(
		'nmc_action_btn',
			[
				'type'  => Controls_Manager::RAW_HTML,
				'raw' => '<button type="button" class="button button-primary nmc_btn"
				onclick="try{ var ed=parent&&parent.elementor; if(ed&&ed.channels&&ed.channels.editor){ ed.channels.editor.trigger(\'nmc:run\'); } }catch(e){}">
				Run Animations
				</button>',
				'separator' => 'before',
				'content_classes' => 'nmc_btn-wrap',
				'render_type' => 'none',
			]
		);

		$this->add_control(
		'nmc_back_btn',
			[
				'type'  => \Elementor\Controls_Manager::RAW_HTML,
				'raw'   => '<button type="button" class="button nmc_btn_back"
				onclick="try{var ed=parent&&parent.elementor;if(ed&&ed.channels&&ed.channels.editor){ed.channels.editor.trigger(\'nmc:reset\');}}catch(e){}">
				Back to Design
				</button>',
				'content_classes' => 'nmc_btn-wrap',
				'render_type' => 'none',
			]
		);

		$this->end_controls_section();
		$this->nmc_pinstack_controls();
		$this->nmc_pinUnstack_controls();
		$this->nmc_style_controls();
	}

	protected function content_template_single_repeater_item() {
		?>
		<#
		const idx        = view.collection.indexOf( data );
		const slideCount = ( idx || 0 ) + 1;
		const key        = 'single-card-' + view.getIDInt() + '-' + slideCount;

		view.addRenderAttribute( key, {
			'class': 'aae-motion-card',
			'data-slide': slideCount
		} );
		#>
		
		<div {{{ view.getRenderAttributeString( key ) }}}></div>
		
		<?php
	}

	// gsap setting controls
	protected function nmc_pinstack_controls(){
		$this->start_controls_section(
			'gsap_controls',
			[
				'label' => esc_html__( 'Pin Stack Options', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'nmc_enable_on',
			[
				'label'       => esc_html__( 'Enable Animation On', 'animation-addons-for-elementor-pro' ),
				'description' => esc_html__( 'Choose the minimum screen width where the scroll animation runs. Below this, cards display as a static grid.', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '1200',
				'options'     => [
					'0'    => esc_html__( 'All Devices', 'animation-addons-for-elementor-pro' ),
					'768'  => esc_html__( 'Tablet & Up (≥ 768px)', 'animation-addons-for-elementor-pro' ),
					'1024' => esc_html__( 'Desktop & Up (≥ 1024px)', 'animation-addons-for-elementor-pro' ),
					'1200' => esc_html__( 'Desktop Only (≥ 1200px)', 'animation-addons-for-elementor-pro' ),
				],
				'render_type' => 'none',
			]
		);

        $this->add_responsive_control(
            'wcf_pin_area_start',
            [
                'label'              => esc_html__('Start', 'animation-addons-for-elementor-pro'),
                'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'frontend_available' => true,
                'default'            => 'top top',
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

 		$this->add_responsive_control(
            'wcf_pin_area_end',
            [
                'label'              => esc_html__('End', 'animation-addons-for-elementor-pro'),
                'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'default'            => 'bottom bottom',
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
            ]
        );

        $this->add_responsive_control(
            'wcf_pin_area_end_custom',
            [
                'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::TEXT,
                'frontend_available' => true,
                'render_type'        => 'none',
                'default'            => esc_html__('bottom bottom', 'animation-addons-for-elementor-pro'),
                'placeholder'        => esc_html__('bottom bottom', 'animation-addons-for-elementor-pro'),
                'condition'          => [
                    'wcf_pin_area_end'     => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
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

	protected function nmc_pinUnstack_controls(){

		$this->start_controls_section(
			'nmc_unstack_controls',
			[
				'label' => esc_html__( 'Pin UnStack Options', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_responsive_control(
            'wcf_pinUnstack_area_start',
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
                'render_type'        => 'none',
            ]
        );

        $this->add_responsive_control(
            'wcf_pinUnstack_area_start_custom',
            [
                'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::TEXT,
                'default'            => esc_html__('top 20%', 'animation-addons-for-elementor-pro'),
                'placeholder'        => esc_html__('top top+=100', 'animation-addons-for-elementor-pro'),
                'frontend_available' => true,
                'render_type'        => 'none',
                'condition'          => [
                    'wcf_pinUnstack_area_start'   => 'custom',
                ],
            ]
        );

 		$this->add_responsive_control(
            'wcf_pinUnstack_area_end',
            [
                'label'              => esc_html__('End', 'animation-addons-for-elementor-pro'),
                'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'default'            => 'bottom -=800',
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
                    'bottom -=800' => esc_html__('Bottom -=800', 'animation-addons-for-elementor-pro'),
                    'custom'        => esc_html__('custom', 'animation-addons-for-elementor-pro'),
                ],
            ]
        );
		$this->add_responsive_control(
            'wcf_pinUnstack_area_end_custom',
            [
                'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
                'type'               => Controls_Manager::TEXT,
                'frontend_available' => true,
                'render_type'        => 'none',
                'default'            => esc_html__('bottom -=800', 'animation-addons-for-elementor-pro'),
                'placeholder'        => esc_html__('bottom -=800', 'animation-addons-for-elementor-pro'),
                'condition'          => [
                    'wcf_pinUnstack_area_end'     => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'wcf_pinUnstack_end_trigger',
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
		$this->end_controls_section();
	}

	// style controls
	protected function nmc_style_controls() {

		$this->nmc_wrapper_style_controls();
		$this->nmc_card_style_controls();

		// Style section (shows under the "Style" tab in the editor)
		$this->start_controls_section(
			'section_style_titles',
			[
				'label' => esc_html__( 'Titles', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'titles_typography',
				'selector' => '{{WRAPPER}} .toolkit-top-title',
			]
		);

		$this->add_responsive_control(
			'titles_align',
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
				'selectors' => [
					'{{WRAPPER}} .aae-toolkit-item:has(.aae-toolkit-title)' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Tabs: Normal / Hover
		$this->start_controls_tabs( 'tabs_titles_colors' );

		// Normal
		$this->start_controls_tab(
			'tab_titles_normal',
			[ 'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ) ]
		);

		$this->add_control(
			'titles_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .toolkit-top-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'tab_titles_hover',
			[ 'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ) ]
		);

		$this->add_control(
			'titles_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .toolkit-top-title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	// Wrapper style (outer container + grid)
	protected function nmc_wrapper_style_controls() {
		$this->start_controls_section(
			'section_style_wrapper',
			[
				'label' => esc_html__( 'Wrapper', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'wrapper_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 2000, 'step' => 5 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
					'vw' => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-card-area'                   => 'width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
					'{{WRAPPER}} .aae-card-area .custom-container' => 'width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_min_height',
			[
				'label'      => esc_html__( 'Min Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 2000, 'step' => 10 ],
					'vh' => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-card-area' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .aae-card-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .aae-card-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_grid_gap',
			[
				'label'      => esc_html__( 'Grid Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 200 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-toolkit-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'wrapper_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .aae-card-area',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'wrapper_border',
				'selector'  => '{{WRAPPER}} .aae-card-area',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .aae-card-area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .aae-card-area',
			]
		);

		$this->end_controls_section();
	}

	// Card style (each individual card)
	protected function nmc_card_style_controls() {
		$this->start_controls_section(
			'section_style_cards',
			[
				'label' => esc_html__( 'Cards', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'card_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [ 'min' => 100, 'max' => 1000, 'step' => 5 ],
					'%'  => [ 'min' => 10, 'max' => 100 ],
				],
				'default'    => [ 'unit' => 'px', 'size' => 300 ],
				'selectors'  => [
					'{{WRAPPER}} .aae-toolkit-item-cards' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .aae-motion-card'        => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh' ],
				'range'      => [
					'px' => [ 'min' => 100, 'max' => 1200, 'step' => 5 ],
					'%'  => [ 'min' => 10, 'max' => 100 ],
					'vh' => [ 'min' => 10, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-toolkit-item-cards' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .aae-motion-card'        => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .aae-toolkit-item-cards' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'card_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .aae-toolkit-item-cards, {{WRAPPER}} .aae-motion-card',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'card_border',
				'selector'  => '{{WRAPPER}} .aae-toolkit-item-cards, {{WRAPPER}} .aae-motion-card',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'card_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .aae-toolkit-item-cards' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .aae-motion-card'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .aae-toolkit-item-cards, {{WRAPPER}} .aae-motion-card',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides   = ! empty( $settings['card_items'] ) ? $settings['card_items'] : [];

		$nmc_pinstack_controls = [
			'start'       => $settings['wcf_pin_area_start']    ?? '',
			'end'         => $settings['wcf_pin_area_end']      ?? '',
			'trigger'     => $settings['wcf_pin_area_trigger']  ?? '',
			'endTrigger'  => $settings['wcf_pin_end_trigger']   ?? '',
			'unStackStart'    => $settings['wcf_pinUnstack_area_start']    ?? '',
			'unStackEnd'      => $settings['wcf_pinUnstack_area_end']    ?? '',
			'unStackTrigger'  => $settings['wcf_pinUnstack_end_trigger']    ?? '',
			'enableMinWidth'  => $settings['nmc_enable_on'] ?? '1200',
		];

		// Outer wrapper 
		$this->add_render_attribute( 'outside', [
			'class'                => 'aaee-n-motions-card-wrapper',
			'role'                 => 'region',
			'aria-roledescription' => 'carousel',
		] );

		?>
		<div class="aae-card-area section-padding-bottom" data-scroll-trigger="<?php echo esc_attr( wp_json_encode( $nmc_pinstack_controls, JSON_UNESCAPED_SLASHES ) ); ?>">
			<div class="custom-container">
				<div class="aae-toolkit-grid">
					<div class="aae-toolkit-item">
						<div <?php $this->print_render_attribute_string( 'outside' ); ?>>
							<div class="aae-toolkit-item-card">
								<?php
								foreach ( $slides as $index => $slide ) {
									$slide_count = $index + 1;
									// Correct: control id 'card_title', repeater id 'card_items'
									$slide_key = $this->get_repeater_setting_key( 'card_title', 'card_items', $index );

									// We'll put attributes on the inner div (.aae-motion-card),
									$this->add_render_attribute( $slide_key, [
										'class'                => 'aae-motion-card',
										'data-slide'           => $slide_count,
										'role'                 => 'group',
										'aria-roledescription' => 'slide',
										'aria-label'           => sprintf(
											esc_attr__( '%1$s of %2$s', 'animation-addons-for-elementor-pro' ),
											$slide_count,
											count( $slides )
										),
									] );
									?>
									<div class="aae-toolkit-item-cards toolkit-card-anim">
										<div <?php $this->print_render_attribute_string( $slide_key ); ?>>
											<?php $this->print_child( $index ); ?>
										</div>
									</div>
									<?php if ( ! empty( $slide['card_title'] ) ) : ?>
										<span class="aae-mobile-card-title"><?php echo esc_html( $slide['card_title'] ); ?></span>
									<?php endif; ?>
									<?php
								}
								?>
							</div>
						</div>
					</div>
					<div class="aae-toolkit-item aae-toolkit-item--titles">
						<h3 class="aae-toolkit-title toolkit-title-anim">
						<div class="top-titles">
							<?php if ( ! empty( $settings['card_items'] ) ) :
							foreach ( $settings['card_items'] as $item ) : ?>
								<span class="toolkit-top-title"><?php echo $item['card_title'] ? esc_html( $item['card_title'] ) : ''; ?></span>
							<?php endforeach; endif; ?>
						</div>
						</h3>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	protected function get_initial_config(): array {
		return array_merge( parent::get_initial_config(), [
			'support_improved_repeaters' => true,
			'target_container'           => [ '.aaee-n-motions-card-wrapper > .aae-toolkit-item-card' ],
			'node'                       => 'div',
			'is_interlaced'              => true,		
		] );
	}	

	protected function content_template() {
		?>
		<#
		const hasItems   = Array.isArray( settings.card_items ) && settings.card_items.length > 0;
		const elementUid = view.getIDInt().toString().substr(0, 3);
		#>

		<div class="aae-card-area section-padding-bottom">
			<div class="custom-container">
				<div class="aae-toolkit-grid">  
					<div class="aae-toolkit-item">
						<div class="aaee-n-motions-card-wrapper" role="region" aria-roledescription="carousel">
							<div class="aae-toolkit-item-card new">							
								
							</div>
						</div> <!--  aaee-n-motions-card-wrapper end -->
					</div><!--  aae-toolkit-item end -->
						<div class="aae-toolkit-item aae-toolkit-item--titles">
						<h3 class="aae-toolkit-title toolkit-title-anim">
							<div class="top-titles">
							<# if ( settings.card_items && settings.card_items.length ) { #>
								<# _.each( settings.card_items, function( item ) { #>
								<span class="toolkit-top-title">{{ item.card_title }}</span>
								<# }); #>
							<# } #>
							</div>
						</h3>
						</div>
				</div> <!-- aae-toolkit-grid end -->
		</div>  <!-- custom-container end -->
		</div>
		<?php
	}

}
