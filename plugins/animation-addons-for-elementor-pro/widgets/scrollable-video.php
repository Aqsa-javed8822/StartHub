<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
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
class Scrollable_Video extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'aae--scrollable-video';
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
		return esc_html__( 'Scrollable Video', 'animation-addons-for-elementor-pro' );
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
		return 'wcf eicon-video-playlist';
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
		return [ 'animation-addons-for-elementor-pro' ];
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
		return [ 'aae-scrollable-video' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'aae-scrollable-video' ];
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
		// Video Content
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'video_source',
			[
				'label'   => __( 'Video Source', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'upload'  => __( 'Upload Video', 'animation-addons-for-elementor-pro' ),
					'custom'  => __( 'Custom MP4 URL', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'video_file',
			[
				'label'      => __( 'Upload Video', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'video',
				'condition'  => [
					'video_source' => 'upload',
				],
			]
		);

		$this->add_control(
			'video_link',
			[
				'label'       => esc_html__( 'Video Link', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => 'https://www.apple.com/105/media/us/airpods/2024/b4e8e99e-af2c-413e-84d4-50a9a5ee4fe3/anim/airpods/large.mp4',
				'placeholder' => esc_html__( 'Type your link here', 'animation-addons-for-elementor-pro' ),
				 'condition'   => [
					'video_source' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		// Video Settings
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_responsive_control(
			'pin_options',
			[
				'label' => esc_html__( 'Pin Options', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => [
					'' => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'false' => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
					'true'  => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .aae-scroll-height' => '--pin: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'video_control',
			[
				'label' => esc_html__( 'Video Control', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .aae-scroll-height' => '--video-control: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'scrollable_height',
			[
				'label' => esc_html__( 'Scroll Height', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 10000, 'step' => 5 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 1500 ],
				'selectors' => [
					'{{WRAPPER}} .aae-scroll-height' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style Section', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
				
		$this->add_responsive_control(
			'video_height',
			[
				'label' => esc_html__( 'Video Height', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'size' => 1000,
				],
				'selectors' => [
					'{{WRAPPER}} .aae--s-video-wrapper'      => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .aae--scrollable-video'     => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'video_width',
			[
				'label' => esc_html__( 'Video Width', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .aae--s-video-wrapper'      => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .aae--scrollable-video'     => 'width: {{SIZE}}{{UNIT}};',
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

		$source   = $settings['video_source'];
		$mp4_file = $settings['video_file']['url'] ?? '';
		$custom   = $settings['video_link'] ?? '';
		?>
		<div class="aae--s-video-wrapper">

			<div class="aae-scroll-height" style="display:none;"></div>

			<?php
				if ( $source === 'upload' && $mp4_file ) : ?>

					<video class="aae--scrollable-video" preload="auto" muted playsinline>
						<source src="<?php echo esc_url( $mp4_file ); ?>" type="video/mp4" />
					</video>

				<?php elseif ( $source === 'custom' && $custom ) : ?>
					<video class="aae--scrollable-video" preload="auto" muted playsinline>
						<source src="<?php echo esc_url($custom); ?>" type="video/mp4" />
					</video> 

				<?php endif; ?>
			
			
		</div>
		<?php
	}

}
