<?php

namespace WCFAddonsPro\Posts;

use CSF;

defined( 'ABSPATH' ) || exit;

class WCFPosts {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->include();
		$this->init();
		add_action( 'plugins_loaded', [ $this, 'meta' ], 100 );
	}

	public function meta() {
		$this->add_custom_post_field();
	}

	/**
	 * [init] Assets Initializes
	 * @return [void]
	 */

	public function init() {
		add_action( 'after_setup_theme', [ $this, 'setup_theme' ], 11 );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @return void
	 */
	public function setup_theme() {
		$this->setup_post_formats();
	}

	public function setup_post_formats() {
		global $_wp_theme_features;

		$post_formats = [ 'gallery', 'image', 'video', 'audio' ];

		if ( ! isset( $_wp_theme_features['post-formats'] ) ) {
			add_theme_support( 'post-formats', $post_formats );

			return;
		}

		$post_formats = array_unique( array_merge( $_wp_theme_features['post-formats'][0], $post_formats ) );

		add_theme_support( 'post-formats', $post_formats );
	}

	// Custom meta box
	public function add_custom_post_field() {
		add_action( 'add_meta_boxes', function () {
			add_meta_box(
				'aae_post_format_meta',              // Metabox ID
				'AAE Post Option',           // Title
				[ $this, 'aae_render_post_format_metabox' ], // Callback
				'post'                        // Post type
			);
		} );
		add_action( 'save_post', [ $this, 'aae_save_post_meta' ] );
	}

	// Render the Post Format Metabox
	public function aae_render_post_format_metabox( $post ) {
		$current_format = get_post_format( $post->ID ); // Get the current post format
		$video_url      = get_post_meta( $post->ID, '_video_url', true );
		$audio_url      = get_post_meta( $post->ID, '_audio_url', true );
		$gallery_images = get_post_meta( $post->ID, '_gallery_images', true );
		$gallery_images = is_array( $gallery_images ) ? $gallery_images : []; // Ensure it's an array

		wp_enqueue_media();
		?>
        <style>
            .aae--post-form-field {
                display: grid;
                align-items: center;
                grid-template-columns: 1fr 4fr;
                padding: 15px 0;
            }

            .aae--post-form-field label {
                font-size: 16px;
                font-weight: 600;
            }

            .aae--post-form-field input {
                padding: 5px 10px;
            }
			/*
				Gallery css
			*/

			.aae--post-form-field {
				gap: 20px;
			}
			#gallery-images-container {
				display: flex;
				flex-wrap: wrap;
				gap: 15px;
			}
			#gallery-images-container .gallery-image {
				display: flex;
				flex-direction: column;
				gap: 10px;
			} 
			#gallery-images-container img {
				height: 70px;
				object-fit: cover;
				margin-right: 0 !important;
			}
			.gallery-image {
				display: flex;
				flex-direction: column;
				gap: 10px;
			}
        </style>
        <div class="aae--post-form-field">
			<?php if ( $current_format === 'video' ): ?>
                <label for="video_url"><?php echo esc_html__( 'Video URL:', 'animation-addons-for-elementor-pro' ); ?></label>
                <input type="text" id="video_url" name="video_url" value="<?php echo esc_attr( $video_url ); ?>"
                       style="width: 60%;">
			<?php elseif ( $current_format === 'audio' ): ?>
                <label for="audio_url"><?php echo esc_html__( 'Audio URL:', 'animation-addons-for-elementor-pro' ); ?></label>
                <input type="text" id="audio_url" name="audio_url" value="<?php echo esc_attr( $audio_url ); ?>"
                       style="width: 60%;">
			<?php elseif ( $current_format === 'gallery' ): ?>
                <label for="gallery_images">Gallery Images:</label>
                <div id="gallery-images-container" style="margin-top: 10px;">
					<?php foreach ( $gallery_images as $image_url ): ?>
                        <div class="gallery-image">
                            <img src="<?php echo esc_url( $image_url ); ?>"
                                 style="max-width: 100px; margin-right: 10px;">
                            <input type="hidden" name="gallery_images[]" value="<?php echo esc_url( $image_url ); ?>">
                            <button type="button" class="remove-image button">Remove</button>
                        </div>
					<?php endforeach; ?>
                </div>
                <button type="button" id="add-gallery-images" class="button">Add Images</button>
			<?php else: ?>
                <p>This metabox is only relevant for Video, Audio and Gallery post format.</p>
			<?php endif; ?>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                var mediaUploader;

                $('#add-gallery-images').click(function (e) {
                    e.preventDefault();
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }

                    mediaUploader = wp.media({
                        title: 'Select Images for Gallery',
                        button: {text: 'Add to Gallery'},
                        multiple: true
                    });

                    mediaUploader.on('select', function () {
                        var attachments = mediaUploader.state().get('selection').map(function (attachment) {
                            attachment = attachment.toJSON();
                            return attachment.url;
                        });

                        attachments.forEach(function (url) {
                            var html = `
                            <div class="gallery-image">
                                <img src="${url}" style="max-width: 100px; margin-right: 10px;">
                                <input type="hidden" name="gallery_images[]" value="${url}">
                                <button type="button" class="remove-image button">Remove</button>
                            </div>`;
                            $('#gallery-images-container').append(html);
                        });
                    });

                    mediaUploader.open();
                });

                $(document).on('click', '.remove-image', function () {
                    $(this).closest('.gallery-image').remove();
                });
            });
        </script>
		<?php
	}

	// Save post metadata
	public function aae_save_post_meta( $post_id ) {
		if ( array_key_exists( 'video_url', $_POST ) ) {
			update_post_meta( $post_id, '_video_url', sanitize_text_field( $_POST['video_url'] ) );
		}
		if ( array_key_exists( 'audio_url', $_POST ) ) {
			update_post_meta( $post_id, '_audio_url', sanitize_text_field( $_POST['audio_url'] ) );
		}
		if ( array_key_exists( 'gallery_images', $_POST ) ) {
			$gallery_images = array_map( 'esc_url_raw', $_POST['gallery_images'] );
			update_post_meta( $post_id, '_gallery_images', $gallery_images );
		} else {
			delete_post_meta( $post_id, '_gallery_images' );
		}
	}


	/**
	 * [include] Load Necessary file
	 * @return [void]
	 */
	public function include() {
		require_once 'trait-aae-post-handler.php';
		require_once 'category-fields.php';
	}

}

WCFPosts::instance();
