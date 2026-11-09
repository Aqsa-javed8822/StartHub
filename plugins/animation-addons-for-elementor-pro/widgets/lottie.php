<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class Lottie extends Widget_Base {

	public function get_name() {
		return 'wcf--lottie-animation';
	}

	public function get_title() {
		return esc_html__( 'Lottie', 'animation-addons-for-elementor-pro' );
	}

	public function get_icon() {
		return 'wcf eicon-animation';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_keywords() {
		return [ 'animation', 'lottie' ];
	}
	
	public function get_script_depends() {
	   
		return [ 'wcf-lottie' ];
	}
		
    protected function register_controls() {
        $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Settings', 'animation-addons-for-elementor-pro'),
			]
		);
		
        $this->add_control(
			'source',
			[
				'label' => esc_html__( 'Source', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'media_file',
				'options' => [
					'media_file' => esc_html__( 'Media File', 'animation-addons-for-elementor-pro'),
					'external_url' => esc_html__( 'External URL', 'animation-addons-for-elementor-pro'),
				],
				
			]
		);

		$this->add_control(
			'source_external_url',
			[
				'label' => esc_html__( 'External URL', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::URL,
				'condition' => [
					'source' => 'external_url',
				],
				'dynamic' => [
					'active' => true,
				],				
				'placeholder' => esc_html__( 'Enter your URL', 'animation-addons-for-elementor-pro'),				
			]
		);

		$this->add_control(
			'source_json',
			[
				'label' => esc_html__( 'Upload JSON File', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'media_types' => [ 'application/json' ],
						
				'condition' => [
					'source' => 'media_file',
				],
			]
		);
		
		$this->add_control(
			'wcf_interactivity_event',
			[
				'label' => esc_html__( 'Trigger', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => '',		
				'options' => [
					'' => esc_html__( 'None', 'animation-addons-for-elementor-pro'),
					'scroll' => esc_html__( 'On Scroll', 'animation-addons-for-elementor-pro'),
					'hover' => esc_html__( 'On Hover', 'animation-addons-for-elementor-pro'),
					'cursor_move'  => esc_html__( 'Mouse Cursor Move', 'animation-addons-for-elementor-pro'),					
					'click'  => esc_html__( 'On Click', 'animation-addons-for-elementor-pro'),					
					'viewport'  => esc_html__( 'Viewport', 'animation-addons-for-elementor-pro'),					
				],				
			]
		);
		
		$this->add_control(
			'wcf_interactivity_event_pause',
			[
				'label' => esc_html__( 'Pause', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => '',	
				'condition' => ['wcf_interactivity_event' => ['click','hover','viewport']],
				'options' => [
					'' => esc_html__( 'None', 'animation-addons-for-elementor-pro'),
					'onmouseleave' => esc_html__( 'On Mouseleave', 'animation-addons-for-elementor-pro'),
					'onclick' => esc_html__( 'On Click', 'animation-addons-for-elementor-pro'),						
										
				],				
			]
		);
		
		$this->add_control(
			'wcf_interactivity_event_replay',
			[
				'label' => esc_html__( 'Replay', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'condition' => ['wcf_interactivity_event' => ['click','hover','viewport'], 'wcf_interactivity_event_pause!' => ['']],
				'default' => '',				
				'options' => [
					'' => esc_html__( 'None', 'animation-addons-for-elementor-pro'),
					'onhover' => esc_html__( 'On Hover', 'animation-addons-for-elementor-pro'),
					'onclick' => esc_html__( 'On Click', 'animation-addons-for-elementor-pro'),
					'inview'  => esc_html__( 'Viewport', 'animation-addons-for-elementor-pro')		
				],				
			]
		);

		$this->add_control(
			'trigger_selector',
			[
				'label' => esc_html__( 'Start Trigger Selector', 'animation-addons-for-elementor-pro'),
				'placehoder' => '#id',
				'type' => Controls_Manager::TEXT,				
				'condition' => ['wcf_interactivity_event' => ['scroll']],								
				
			]
		);

		$this->add_control(
			'endtrigger_selector',
			[
				'label' => esc_html__( 'End Trigger Selector', 'animation-addons-for-elementor-pro'),
				'placehoder' => '#id',
				'type' => Controls_Manager::TEXT,				
				'condition' => ['wcf_interactivity_event' => ['scroll']],								
				
			]
		);
		
		$this->add_control(
			'start_point',
			[
				'label' => esc_html__( 'Start Point', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'condition' => ['wcf_interactivity_event' => ['scroll']],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 5,
					],					
				]				
				
			]
		);
		
		$this->add_control(
			'end_point',
			[
				'label' => esc_html__( 'End Point', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SLIDER,
				'condition' => ['wcf_interactivity_event' => ['scroll']],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 1500,
						'step' => 5,
					],					
				]				
				
			]
		);
		
		
		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay?', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro'),
				'label_off' => esc_html__( 'No', 'animation-addons-for-elementor-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => ['wcf_interactivity_event' => ['']]
			]
		);		
	
		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop?', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro'),
				'label_off' => esc_html__( 'No', 'animation-addons-for-elementor-pro'),
				'condition' => ['wcf_interactivity_event!' => ['scroll','cursor_move']],
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->add_control(
			'loop_count',
			[
				'label' => esc_html__( 'Times', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 100,
				'step' => 1,
				'default' => 10,			
				'condition' => ['wcf_interactivity_event!' => ['cursor_move'], 'loop' => ['yes']],
				'description' => esc_html__('Set -1 for infinite loop','animation-addons-for-elementor-pro'),
			]
		);		
		
		// $this->add_control(
		// 	'backward',
		// 	[
		// 		'label' => esc_html__( 'Backward Direction?', 'animation-addons-for-elementor-pro'),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'label_on' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro'),
		// 		'label_off' => esc_html__( 'No', 'animation-addons-for-elementor-pro'),
		// 		'condition' => ['wcf_interactivity_event' => ['hover']],
		// 		'return_value' => 'yes',
		// 		'description' => esc_html__('Play it backward.','animation-addons-for-elementor-pro'),
		// 		'default' => '',
		// 	]
		// );		
		
		
		
		$this->add_control(
			'wcf_speed',
			[
				'label' => esc_html__( 'Speed', 'animation-addons-for-elementor-pro'),
				'description' => esc_html__('Standard value: 1','animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SLIDER,
				'condition' => ['wcf_interactivity_event!' => ['cursor_move']],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 0.2,
					],					
				]				
				
			]
		);

			$this->add_control(
			'wcf_duration',
			[
				'label' => esc_html__( 'Duration', 'animation-addons-for-elementor-pro'),
				'description' => esc_html__('Standard value: 0.5','animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SLIDER,
				'condition' => ['wcf_interactivity_event' => ['cursor_move']],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.2,
					],					
				]				
				
			]
		);
		
	
		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_properties_section',
			[
				'label' => esc_html__( 'Additional Properties', 'animation-addons-for-elementor-pro'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		
		$this->add_control(
			'wcf_renderer',
			[
				'label' => esc_html__( 'Renderer', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'animation-addons-for-elementor-pro'),
					'svg' => esc_html__( 'Svg', 'animation-addons-for-elementor-pro'),		
				],				
			]
		);	

		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'animation-addons-for-elementor-pro'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'con_width',
				[
					'label' => esc_html__( 'Width', 'animation-addons-for-elementor-pro'),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2100,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],					
					'selectors' => [
						'{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'con_height',
				[
					'label' => esc_html__( 'Height', 'animation-addons-for-elementor-pro'),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2100,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],					
					'selectors' => [
						'{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
					

		$this->end_controls_section();

	}

	protected function render() {
	
        $settings = $this->get_settings_for_display();
        $source   = $settings['source'];
        $url      = '';

        if($source == 'media_file'){
            $source_json = $settings['source_json'];
            $url = isset($source_json['url']) ? $source_json['url'] : $url;
        }elseif($source == 'external_url'){
           $source_json = $settings['source_external_url'];
           $url = isset($source_json['url']) ? $source_json['url'] : $url;
        }
   	
		$is_ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;	

        $id = $this->get_id();
		if ( $is_ssl ) {
			$url = str_replace( 'http://', 'https://', $url );  
		}
		?>

		<?php

		$this->add_render_attribute(
			'wrapper',
			[
				'id' => 'aae-lottie-player-'.esc_attr($id),
				'class' => [ 'aae-lottie-wrp' ],		
				'data-loop' => $settings['loop'] == 'yes' ? true: false,	
				'data-src' => esc_url($url),
				'data-settings' => json_encode(
					[ 
						'event'         => $settings['wcf_interactivity_event'],
						'pause'         => $settings['wcf_interactivity_event_pause'], 
						'play'          => $settings['wcf_interactivity_event_replay'] ,
						'start_point'   => isset( $settings['start_point']['size'] ) ?$settings['start_point']['size'] : 0, 
						'end_point'     => isset($settings['end_point']['size']) ? $settings['end_point']['size'] : 300
					] 
				),
			]
		);

		$this->add_render_attribute(
			'aae_lottie',
			[
				'id' => 'wcf-lottie-player-'.esc_attr($id),
				'class' => [ 'wcf-lottie-wrp' ]	,		
				'loop' => $settings['loop'] == 'yes' ? true: false,		
			]
		);
		
		$this->set_properties_option();		
		
		?>
		  <span style="display:none" <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>></span>		
          <div <?php echo $this->get_render_attribute_string( 'aae_lottie' ); ?>></div>	
	
		<?php
	}
	
	public function set_properties_option(){

		$settings = $this->get_settings_for_display();

		if($settings['trigger_selector'] !== '')
		{
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-triggerselector' => $settings['trigger_selector'],				
				]
			);
		}
		if($settings['endtrigger_selector'] !== '')
		{
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-endtriggerselector' => $settings['endtrigger_selector'],				
				]
			);
		}
		
		if($settings['autoplay'] == 'yes')
		{
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-autoplay' => true,				
				]
			);
		}
		
		
		if( $settings[ 'loop' ] == 'yes' )
		{		
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-count' => $settings['loop_count'],				
				]
			);	
		}
		
		if($settings['wcf_interactivity_event'] == 'hover')
		{		
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-hover' => true,				
				]
			);	
		}
		
		if(isset($settings['backward']) && $settings['backward'] == 'yes'){
		
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-direction' => -1,				
				]
			);	
		}		
	
		if($settings['wcf_renderer'] != ''){
		
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-renderer' => $settings['wcf_renderer'],				
				]
			);
			
		}
				
		
		if( isset( $settings['wcf_speed']['size'] ) &&  is_numeric( $settings['wcf_speed']['size'] ) ){
		
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-speed' => $settings['wcf_speed']['size']				
				]
			);				
		}
		
		if( isset( $settings['wcf_duration']['size'] ) &&  is_numeric( $settings['wcf_duration']['size'] ) ){
		
			$this->add_render_attribute(
				'wrapper',
				[					
					'data-duration' => $settings['wcf_duration']['size']				
				]
			);				
		}
	}
}