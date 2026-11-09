<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class Tiktok_Feed extends Widget_Base {

	public function get_name() {
		return 'aae--tiktok-feed';
	}

	public function get_title() {
		return esc_html__( 'Tiktok Feed', 'animation-addons-for-elementor-pro' );
	}

	public function get_icon() {
		return 'wcf fab fa-tiktok';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_keywords() {
		return [ 'tiktok', 'video', 'feed' ];
	}

	public function get_style_depends() {
		return [ 'aae-tiktok-feed' ];
	}

	public function get_script_depends() {
		return [ 'aae-tiktok-feed' ];
	}

	protected function register_controls() {
		$this->register_tiktok_content();

		$this->style_tiktok_layout();

		$this->style_tiktok_video();

		$this->style_tiktok_user_info();
	}

	protected function register_tiktok_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'tiktok_layout',
			[
				'label'     => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					'1' => esc_html__( 'No Cover Image', 'animation-addons-for-elementor-pro' ),
					'2' => esc_html__( 'with Cover Image', 'animation-addons-for-elementor-pro' ),
					'3' => esc_html__( 'Popup', 'animation-addons-for-elementor-pro' ),
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'video_count',
			[
				'label'   => __( 'Show Videos', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 100,
			]
		);

		$this->add_control(
			'user_info_show',
			[
				'label'     => esc_html__( 'Show User Info', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'show',
				'options'   => [
					'show' => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
					'none' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'.ttw-{{ID}}.wcf--tiktok-video-wrapper .user-info-wrapper' => 'display: {{VALUE}};',
				],
				'condition' => [ 'tiktok_layout' => '3' ],
			]
		);

		$this->end_controls_section();
	}

	protected function style_tiktok_layout() {
		$this->start_controls_section(
			'style_tt_layout',
			[
				'label' => __( 'Layout', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .aae--tiktok-feed' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_responsive_control(
			'grid_col_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .aae--tiktok-feed' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'grid_row_gap',
			[
				'label'      => esc_html__( 'Row Gap', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .aae--tiktok-feed' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_tiktok_video() {
		$this->start_controls_section(
			'style_tt_video',
			[
				'label' => __( 'Video', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'video_width',
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
					'{{WRAPPER}} .tiktok-video' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'video_height',
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
					'{{WRAPPER}} .tiktok-video' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'video_b_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .tiktok-video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_tiktok_user_info() {
		$this->start_controls_section(
			'style_tt_user_info',
			[
				'label'     => __( 'User Info', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'user_info_show' => 'show' ],
			]
		);

		$this->add_responsive_control(
			'user_info_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'.ttw-{{ID}}.wcf--tiktok-video-wrapper .user-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'user_img_heading',
			[
				'label'     => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'user_img_width',
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
					'.ttw-{{ID}}.wcf--tiktok-video-wrapper .image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'user_img_height',
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
					'.ttw-{{ID}}.wcf--tiktok-video-wrapper .image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'user_name_heading',
			[
				'label'     => esc_html__( 'User Name', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.ttw-{{ID}}.wcf--tiktok-video-wrapper .name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typo',
				'selector' => '.ttw-{{ID}}.wcf--tiktok-video-wrapper .name',
			]
		);

		$this->add_control(
			'user_bio_heading',
			[
				'label'     => esc_html__( 'User Bio', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.ttw-{{ID}}.wcf--tiktok-video-wrapper .user-info p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'bio_typo',
				'selector' => '.ttw-{{ID}}.wcf--tiktok-video-wrapper .user-info p',
			]
		);

		$this->add_control(
			'user_stats_heading',
			[
				'label'     => esc_html__( 'User Stats', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'stats_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.ttw-{{ID}}.wcf--tiktok-video-wrapper .stat' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'stats_typo',
				'selector' => '.ttw-{{ID}}.wcf--tiktok-video-wrapper .stat',
			]
		);


		$this->end_controls_section();
	}

	// Render
	protected function render() {
		$settings = $this->get_settings_for_display();

		$tiktok_api  = get_option( 'aae_tiktok_api_advanced_settings' );
		$tiktok_data = json_decode( $tiktok_api, true );

		$access_token = $tiktok_data['accessToken'];
		$video_count  = $settings['video_count'];

		if ( empty( $access_token ) ) {
			echo '<div class="tiktok-feed-error">Please provide a valid access token.</div>';

			return;
		}

		$response = wp_remote_post(
			'https://open.tiktokapis.com/v2/video/list/?fields=id,title,video_description,duration,cover_image_url,embed_link',
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $access_token,
					'Content-Type'  => 'application/json',
				],
				'body'    => json_encode( [
					'max_count' => $video_count,
				] ),
				'timeout' => 15,
			]
		);

		if ( is_wp_error( $response ) ) {
			echo '<div class="tiktok-feed-error">API request failed: ' . esc_html( $response->get_error_message() ) . '</div>';

			return;
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $data['data']['videos'] ) ) {
			echo '<div class="tiktok-feed-error">No videos found.</div>';

			return;
		}

		// User Info
		$user_info_res = wp_remote_get(
			'https://open.tiktokapis.com/v2/user/info/?fields=avatar_url,display_name,bio_description,follower_count,following_count,likes_count,video_count',
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $access_token,
					'Content-Type'  => 'application/json',
				],
				'timeout' => 15,
			]
		);

		if ( is_wp_error( $user_info_res ) ) {
			echo 'Request failed: ' . $user_info_res->get_error_message();

			return;
		}

		$user_info_body = wp_remote_retrieve_body( $user_info_res );
		$user_data      = json_decode( $user_info_body, true );

		// Layout
		if ( '3' === $settings['tiktok_layout'] ) {
			$this->render_tiktok_layout_popup( $data, $user_data );
		} else if ( '2' === $settings['tiktok_layout'] ) {
			$this->render_layout_with_cover_img( $data );
		} else {
			$this->render_layout_no_cover_img( $data );
		}
	}

	protected function render_layout_no_cover_img( $data ) {
		?>
        <div class="aae--tiktok-feed style-1">
			<?php
			foreach ( $data['data']['videos'] as $video ) { ?>
                <div class="tiktok-video">
                    <iframe src="https://www.tiktok.com/player/v1/<?php echo $video['id']; ?>?autoplay=0&mute=0"
                            allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
				<?php
			}
			?>
        </div>
		<?php
	}

	protected function render_layout_with_cover_img( $data ) {
		?>
        <div class="aae--tiktok-feed style-2">
			<?php
			foreach ( $data['data']['videos'] as $video ) { ?>
                <div class="tiktok-video"
                     data-video-id="<?php echo $video['id']; ?>"
                     data-thumb="<?php echo $video['cover_image_url']; ?>">
                    <img src="<?php echo esc_url( $video['cover_image_url'] ); ?>"
                         alt="<?php echo esc_attr( $video['title'] ); ?>">
                    <button class="play-icon"></button>
                </div>
				<?php
			}
			?>
        </div>
		<?php
	}

	protected function render_tiktok_layout_popup( $data, $user_data ) {
		$user_info = $user_data['data']['user'];

		$this->add_render_attribute( 'wrapper', [
			'class'          => 'aae--tiktok-feed style-3',
			'data-name'      => $user_info['display_name'],
			'data-avatar'    => $user_info['avatar_url'],
			'data-bio'       => $user_info['bio_description'],
			'data-followers' => $user_info['follower_count'],
			'data-following' => $user_info['following_count'],
			'data-likes'     => $user_info['likes_count'],
			'data-videos'    => $user_info['video_count'],
		] );
		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ) ?>>
			<?php
			foreach ( $data['data']['videos'] as $video ) { ?>
                <div class="tiktok-video"
                     data-video-id="<?php echo $video['id']; ?>"
                     data-thumb="<?php echo $video['cover_image_url']; ?>">
                    <img src="<?php echo esc_url( $video['cover_image_url'] ); ?>"
                         alt="<?php echo esc_attr( $video['title'] ); ?>">
                    <button class="play-icon"></button>
                </div>
				<?php
			}
			?>
        </div>
		<?php
	}
}