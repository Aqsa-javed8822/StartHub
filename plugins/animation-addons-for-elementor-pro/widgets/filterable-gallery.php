<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Filterable Gallery
 *
 * Elementor widget for filterable gallery.
 *
 * @since 1.0.0
 */
class Filterable_Gallery extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--filterable-gallery';
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
		return esc_html__( 'Filterable Gallery', 'animation-addons-for-elementor-pro' );
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
		return 'wcf eicon-gallery-grid';
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
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'wcf--filterable-gallery' ];
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
		return ['aae--filterable-gallery','flip','wcf--posts'];
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
		//layout
		$this->register_layout_controls();

		// Filter
		if ( wcf_addons_get_settings( 'wcf_save_extensions', 'gallery-filter' ) ) {
			$this->register_filter_controls();
		}

		//gallery
		$this->register_gallery_controls();
	}

	protected function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'layout_style',
			[
				'label'   => esc_html__( 'Style', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'    => esc_html__( 'Grid', 'animation-addons-for-elementor-pro' ),
					'masonry' => esc_html__( 'Masonry', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SELECT,
				'render_type'    => 'template',
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1'  => '1',
					'2'  => '2',
					'3'  => '3',
					'4'  => '4',
					'5'  => '5',
					'7'  => '7',
					'8'  => '8',
					'9'  => '9',
					'10' => '10',
				],
				'selectors'      => [
					'{{WRAPPER}} .gallery-wrapper' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition'      => [ 'layout_style' => 'grid' ]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => __( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .gallery-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_height',
			[
				'label'      => esc_html__( 'Row Height', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .gallery-wrapper' => 'grid-auto-rows: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_filter_controls() {
		$this->start_controls_section(
			'section_filter',
			[
				'label' => __( 'Filter', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'enable_filter',
			[
				'label'        => esc_html__( 'Enable Filter', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'filter_all_label',
			[
				'label'     => esc_html__( 'Filter All Labels', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'All', 'animation-addons-for-elementor-pro' ),
				'condition' => [ 'enable_filter' => 'yes' ],
			]
		);

		$filter_repeater = new Repeater();

		$filter_repeater->add_control(
			'filter_title',
			[
				'label'       => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Filter Item', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'filter_items',
			[
				'label'       => esc_html__( 'Filter Items', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $filter_repeater->get_controls(),
				'condition'   => [ 'enable_filter' => 'yes' ],
				'default'     => [
					[
						'filter_title' => esc_html__( 'Filter Item 1', 'animation-addons-for-elementor-pro' ),
					],
				],
				'title_field' => '{{{ filter_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter_style',
			[
				'label'     => __( 'Filter', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'enable_filter' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'filter_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .gallery-filter li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_item_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .gallery-filter' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .gallery-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'filter_typography',
				'selector' => '{{WRAPPER}} .gallery-filter li',
			]
		);

		$this->start_controls_tabs(
			'filter_style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'filter_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter li' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'filter_background',
				'types'    => [ 'classic' ],
				'selector' => '{{WRAPPER}} .gallery-filter li',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Hover/Active', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'filter_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter li.mixitup-control-active, {{WRAPPER}} .gallery-filter li:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'filter_hover_background',
				'types'    => [ 'classic' ],
				'selector' => '{{WRAPPER}} .gallery-filter li.mixitup-control-active, {{WRAPPER}} .gallery-filter li:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'filter_align',
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
					'{{WRAPPER}} .gallery-filter' => 'justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function register_gallery_controls() {
		$this->start_controls_section(
			'section_gallery',
			[
				'label' => __( 'Gallery', 'animation-addons-for-elementor-pro' ),
			]
		);

		$gallery_repeater = new Repeater();

		$gallery_repeater->add_control(
			'gallery_item_filter_name',
			[
				'label'       => esc_html__( 'Filter name', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Use the filter name. Separate multiple items with comma (e.g. <strong>Gallery Item, Gallery Item 2</strong>)', 'animation-addons-for-elementor-pro' ),
				'separator'   => 'after',
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$gallery_repeater->add_control(
			'gallery_item_filter_type',
			[
				'label'   => esc_html__( 'Type', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
					'video' => esc_html__( 'Video', 'animation-addons-for-elementor-pro' ),
					'audio' => esc_html__( 'Audio', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$gallery_repeater->add_control(
			'gallery_item_video/audio_link',
			[
				'label'       => esc_html__( 'Video/audio Link', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => 'https://www.youtube.com/watch?v=kB4U67tiQLA',
				'condition'   => [
					'gallery_item_filter_type!' => 'image',
				],
				'ai'          => [
					'active' => false,
				],
			]
		);

		$gallery_repeater->add_control(
			'play_btn_icon',
			[
				'label'       => esc_html__( 'Play Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'default'     => [
					'value'   => 'far fa-play-circle',
					'library' => 'fa-regular',
				],
				'label_block' => false,
				'condition'   => [
					'gallery_item_filter_type!' => 'image',
				],
			]
		);

		$gallery_repeater->add_control(
			'gallery_item_light_box',
			[
				'label'        => esc_html__( 'Lightbox', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'gallery_item_filter_type' => 'image',
				],
			]
		);

		$gallery_repeater->add_control(
			'gallery_item_image',
			[
				'label'   => esc_html__( 'Choose Image', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$gallery_repeater->add_control(
			'masonry_style',
			[
				'label'       => esc_html__( 'Masonry Class', 'animation-addons-for-elementor-pro' ),
				'description' => esc_html__( 'It only works for masonry layout.', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					'default'    => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'big'        => esc_html__( 'Big', 'animation-addons-for-elementor-pro' ),
					'horizontal' => esc_html__( 'Horizontal', 'animation-addons-for-elementor-pro' ),
					'vertical'   => esc_html__( 'Vertical', 'animation-addons-for-elementor-pro' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .your-class' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_items',
			[
				'label'       => esc_html__( 'Gallery Items', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $gallery_repeater->get_controls(),
				'default'     => [
					[
						'masonry_style' => 'big'
					],
					[
						'masonry_style' => 'horizontal'
					],
					[
						'masonry_style' => 'vertical'
					],
					[
						'masonry_style' => 'default'
					],
					[
						'masonry_style' => 'default'
					]
				],
				'title_field' => '{{{ gallery_item_filter_name }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_style',
			[
				'label' => __( 'Gallery', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'gallery_item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .gallery-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'play_button_heading',
			[
				'label'     => esc_html__( 'Play Button', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'play_button_size',
			[
				'label'      => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .play__btn' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'play_button_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .play__btn' => 'color: {{VALUE}}; fill: {{VALUE}};',
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

		$this->add_render_attribute( 'wrapper', [
				'class' => 'wcf--filterable-gallery ' . 'style-' . $settings['layout_style'],
			]
		);
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			$this->render_filter( $settings );
			$this->render_gallery( $settings )
			?>
        </div>
		<?php
	}

	protected function render_filter( $settings ) {
		if ( empty( $settings['enable_filter'] ) ) {
			return;
		}
		?>
        <ul class="gallery-filter">
			<?php if ( ! empty( $settings['filter_all_label'] ) ): ?>
                <li data-filter="all"
                    class="mixitup-control-active"><?php echo esc_html( $settings['filter_all_label'] ); ?></li>
			<?php endif; ?>

			<?php foreach ( $settings['filter_items'] as $item ): ?>
                <li data-filter=".<?php echo esc_attr( str_replace( ' ', '', $item['filter_title'] ) ); ?>"><?php echo esc_html( $item['filter_title'] ) ?></li>
			<?php endforeach; ?>
        </ul>
		<?php
	}

	protected function render_gallery( $settings ) {
		?>
        <div class="gallery-wrapper">
			<?php foreach ( $settings['gallery_items'] as $item ): ?>
				<?php
				$filter_class = isset($item['masonry_style']) ? $item['masonry_style'] : '';
				$filter_items = explode( ',', $item['gallery_item_filter_name'] );
				if ( count( $filter_items ) ) {
					foreach ( $filter_items as $filter_item ) {
						$filter_class = $filter_class . ' ' . str_replace( ' ', '', $filter_item );
					}
				}

				$popup_link = '';
				if ( ! empty( $item['gallery_item_light_box'] ) ) {
					$popup_link = $item['gallery_item_image']['url'];
				}

				if ( 'image' !== $item['gallery_item_filter_type'] ) {
					$popup_link = $item['gallery_item_video/audio_link'];
				}

				// Youtube Link Checking
				if ( strpos( $popup_link, "https://www.youtube.com/" ) === 0 ) {
					parse_str( parse_url( $popup_link, PHP_URL_QUERY ), $query );

					if ( isset( $query['v'] ) ) {
						$ytVideoId  = $query['v'];
						$popup_link = "https://www.youtube.com/embed/" . $ytVideoId;
					}
				}

				// Vimeo Link Checking
				if ( strpos( $popup_link, "https://vimeo.com/" ) === 0 ) {
					$videoId    = str_replace( "https://vimeo.com/", "", $popup_link );
					$popup_link = "https://player.vimeo.com/video/" . $videoId;
				}
				?>
                <div class="mix gallery-item <?php echo esc_attr( $filter_class ); ?>">
                    <img src="<?php echo esc_url( $item['gallery_item_image']['url'] ); ?>"
                         alt="<?php echo esc_attr( get_post_meta( $item['gallery_item_image']['id'], '_wp_attachment_image_alt', true ) ); ?>">
					<?php $this->render_play_button( $item, $popup_link ); ?>
                </div>
			<?php endforeach; ?>
        </div>
		<?php
	}

	protected static function render_play_button( $item, $popup_link ) {
		if ( 'image' === $item['gallery_item_filter_type'] ) {
			return;
		}

		$media_type = $item['gallery_item_filter_type'];
		?>
        <button class="play__btn wcf-post-popup <?php echo esc_attr( $media_type ); ?>"
                data-src="<?php echo esc_url( $popup_link ); ?>">
			<?php Icons_Manager::render_icon( $item['play_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        </button>
		<?php
	}
}
