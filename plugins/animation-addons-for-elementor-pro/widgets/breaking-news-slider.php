<?php
namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WCF_ADDONS\WCF_Post_Query_Trait;
use WCFAddonsPro\AAE_Post_Handler_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * BrandSlider
 *
 * Elementor widget for brand slider.
 *
 * @since 1.0.0
 */
class Breaking_News_Slider extends Widget_Base {
	use WCF_Post_Query_Trait;
	use AAE_Post_Handler_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--breaking-news-slider';
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
		return esc_html__( 'Breaking News Slider','animation-addons-for-elementor-pro' );
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
		return 'wcf eicon-slides';
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
		return [ 'wcf--breaking-news-slider' ];
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
	public function get_style_depends() {
		return [ 'wcf--breaking-news-slider' ];
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
		$this->register_query_controls();

		$this->start_controls_section( "section_settings", [
			"label" => esc_html__( "Settings", 'animation-addons-for-elementor-pro' ),
			"tab"   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_responsive_control( "marquer_duration", [
			"label"       => esc_html__( "Marquee Duration", 'animation-addons-for-elementor-pro' ),
			"type"        => Controls_Manager::NUMBER,
			"min"         => 1,
			"max"         => 100,
			"step"        => 5,
			"default"     => 10,
		] );

		$this->add_responsive_control( "post_show", [
			"label"       => esc_html__( "Number of Posts", 'animation-addons-for-elementor-pro' ),
			"type"        => Controls_Manager::NUMBER,
			"min"         => 1,
			"max"         => 10,
			"step"        => 1,
			"default"     => 3,
			"description" => esc_html__(
				"Please keep the Number of Columns and Number of Posts the same for a balanced design.",
				'animation-addons-for-elementor-pro'
			),
		] );

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title HTML Tag', 'animation-addons-for-elementor-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label'              => esc_html__( 'Show Separator', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'label_on'           => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'          => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_separator_icon',
			[
				'label'   => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default' => [
					'value'   => 'far fa-flag',
					'library' => 'fa-regular',
				],
				'condition'  => [ 'show_separator' => 'yes' ]
			]
		);
		

		$this->end_controls_section();

		$this->register_layout_style_controls();
	}

	protected function register_layout_style_controls() {
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'post_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .breaking_news' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .breaking_news' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .breaking_news',
			]
		);
	
		$this->end_controls_section();


		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Title Style', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		//style
		
		$this->start_controls_tabs( 'tabs_title' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .wcf-breaking-news-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-breaking-news-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_tile_hover',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography_hover',
				'selector' => '{{WRAPPER}} .wcf-breaking-news-title:hover',
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-breaking-news-title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'section_icon_layout',
			[
				'label' => esc_html__( 'Icon Style', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_icon_color',
			[
				'label'     => esc_html__( 'Icon Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .breaking_news_icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .breaking_news_icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_icon_size',
			[
				'label'      => esc_html__( 'Icon Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .breaking_news_icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .breaking_news_icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	

	/**
	 * Register the slider controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */


	/**
	 * Register the slider image style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	
	/**
	 * Register the slider text style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	

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

		$args  = [
			"post_type"      => "post",
			"posts_per_page" => $settings["post_show"],
		];

		$query = new \WP_Query( $args );

		if ( ! $query->found_posts ) {
			return;
		}
		
		?>
		<div class="breaking_news marquee" data-marquee-duration="<?php echo $settings["marquer_duration"] ?>">
			<div class="marquee__inner">
			<?php
			$i = 1;
			while ( $query->have_posts() ) {
				$query->the_post();
				$title         = get_the_title();
				$post_classes  = [ 'wcf-post-breaking-news marquee__content' ];
				?>
				<article <?php post_class( $post_classes ); ?>>
						<?php
							echo sprintf( 
								'<%1$s class="wcf-breaking-news-title"><a href="%2$s">%3$s</a></%1$s>',
								esc_html( $settings["title_tag"] ),
								esc_url( get_permalink() ),
								esc_html( $title )
							);
						?>
						<?php if($settings['show_separator'] =='yes'): ?>
							<span class="breaking_news_icon">
								<?php \Elementor\Icons_Manager::render_icon( $settings['show_separator_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
						<?php endif; ?>
				</article>
				<?php
				$i++;
			}
			?>
			</div>
		</div>	
		<?php
	}

}
