<?php
/**
 * Dynamic Tag: Post Meta (text/number)
 *
 * Provides various post meta outputs such as counts, taxonomy lists,
 * author info, dates, and status for Elementor dynamic tags.
 *
 * @package WCFAddonsPro
 * @since 1.0.0
 */

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class AAE_Post_Meta
 *
 * Elementor dynamic tag implementation for post meta/text values.
 */
class AAE_Post_Meta extends \Elementor\Core\DynamicTags\Tag {
 	use CustomPostIdTrait;
	/**
	 * Get dynamic tag name (unique identifier).
	 *
	 * @return string
	 */
	public function get_name() {
		return 'aae-post-meta';
	}

	/**
	 * Get dynamic tag title (UI label).
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Meta', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Get tag groups.
	 *
	 * @return array<int,string>
	 */
	public function get_group() {
		return array( 'aae-posts' );
	}

	/**
	 * Get supported categories for this tag.
	 *
	 * @return array<int,string>
	 */
	public function get_categories(): array {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY, \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY ); // Can also use `TEXT_CATEGORY` for numeric fields.
	}

	/**
	 * Register controls for the tag settings.
	 *
	 * @return void
	 */
	protected function register_controls() {
		// Meta Type Control (e.g., comment count, view count, etc.).
		$this->add_control(
			'meta_type',
			array(
				'label'   => __( 'Meta Type', 'animation-addons-for-elementor-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'comments'      => __( 'Comments Count', 'animation-addons-for-elementor-pro' ),
					'views'         => __( 'Views Count', 'animation-addons-for-elementor-pro' ),
					'categories'    => __( 'Categories (Comma Separated)', 'animation-addons-for-elementor-pro' ),
					'tags'          => __( 'Tags (Comma Separated)', 'animation-addons-for-elementor-pro' ),
					'author'        => __( 'Author Name with Avatar', 'animation-addons-for-elementor-pro' ),
					'author_name'   => __( 'Author Name', 'animation-addons-for-elementor-pro' ),
					'shares'        => __( 'Total Share Count', 'animation-addons-for-elementor-pro' ),
					'reactions'     => __( 'Total Reaction Count', 'animation-addons-for-elementor-pro' ),
					'date'          => __( 'Post Date', 'animation-addons-for-elementor-pro' ),
					'created_date'  => __( 'Post Created Date', 'animation-addons-for-elementor-pro' ), // New option added.
					'modified_date' => __( 'Post Modified Date', 'animation-addons-for-elementor-pro' ),
					'excerpt'       => __( 'Post Excerpt', 'animation-addons-for-elementor-pro' ),
					'title'         => __( 'Post Title', 'animation-addons-for-elementor-pro' ),
					'permalink'     => __( 'Post Permalink', 'animation-addons-for-elementor-pro' ),
					'status'        => __( 'Post Status', 'animation-addons-for-elementor-pro' ),
					'post_type'     => __( 'Post Type', 'animation-addons-for-elementor-pro' ),
				),
				'default' => '',
			)
		);

		$this->add_control('aae_d_exceprp_limit',array(
				'label'     => esc_html__( 'Limit', 'animation-addons-for-elementor-pro' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => '10',
				'condition' => array(
					'meta_type' => 'excerpt',
				),
			));	
	}

	/**
	 * Render tag output based on selected meta-type.
	 *
	 * @return void
	 */
	public function render() {
		$meta_type = $this->get_settings( 'meta_type' );

		switch ( $meta_type ) {
			case 'comments':
				$this->render_comment_count();
				break;
			case 'views':
				$this->render_view_count();
				break;
			case 'categories':
				$this->render_category();
				break;
			case 'tags':
				$this->render_tags();
				break;
			case 'author':
				$this->render_author_info();
				break;
			case 'author_name':
				$this->render_author_info( 'name' );
				break;
			case 'shares':
				$this->render_share_count();
				break;
			case 'reactions':
				$this->render_reactions_count();
				break;
			case 'date':
				$this->render_post_date();
				break;
			case 'created_date': // New case for Post Created Date.
				$this->render_created_date();
				break;
			case 'modified_date':
				$this->render_modified_date();
				break;
			case 'excerpt':
				$this->render_excerpt();
				break;
			case 'title':
				$this->render_title();
				break;
			case 'permalink':
				$this->render_permalink();
				break;
			case 'status':
				$this->render_post_status();
				break;
			case 'post_type':
				$this->render_post_type();
				break;
		}
	}

	/**
	 * Render Comment Count.
	 *
	 * @return void
	 */
	private function render_comment_count() {
		$comments_count = get_comments_number();
		echo esc_html( $comments_count );
	}

	/**
	 * Render total Reactions count.
	 *
	 * @return void
	 */
	private function render_reactions_count() {
		$reactions = (int) get_post_meta( get_the_ID(), 'aaeaddon_post_total_reactions', true );
		$reactions = aaeaddon_format_number_count( $reactions );
		echo esc_html( $reactions );
	}

	/**
	 * Render Views Count.
	 *
	 * @return void
	 */
	private function render_view_count() {
		$view_count = (int) get_post_meta( get_the_ID(), 'wcf_post_views_count', true );
		$view_count = aaeaddon_format_number_count( $view_count );
		echo esc_html( $view_count ?: 0 );
	}

	/**
	 * Render Categories as comma-separated links.
	 *
	 * @return void
	 */
	private function render_category() {
		$categories = array();
		if ( get_post_type() === 'wcf-addons-template' ) {
			$args         = array(
				'numberposts' => 1,
				'post_type'   => 'post',
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
			);
			$latest_posts = get_posts( $args );
			if ( ! is_wp_error( $latest_posts ) && ! empty( $latest_posts ) && isset( $latest_posts[0] ) ) {
				$categories = get_the_category( $latest_posts[0]->ID );
			}
		} else {
			$categories = get_the_category();
		}

		if ( ! empty( $categories ) ) {
			$category_links = array();
			foreach ( $categories as $category ) {
				$category_links[] = sprintf( '<a href="%s">%s</a>', esc_url( get_category_link( $category->term_id ) ), esc_html( $category->name ) );
			}
			echo implode( ', ', $category_links );
		}
	}

	/**
	 * Render Tags as comma-separated links.
	 *
	 * @return void
	 */
	private function render_tags() {
		$categories = array();
		if ( get_post_type() === 'wcf-addons-template' ) {
			$args         = array(
				'numberposts' => 1,
				'post_type'   => 'post',
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
			);
			$latest_posts = get_posts( $args );
			if ( ! is_wp_error( $latest_posts ) && ! empty( $latest_posts ) && isset( $latest_posts[0] ) ) {
				$categories = get_the_tags( $latest_posts[0]->ID );
			}
		} else {
			$categories = get_the_tags();
		}

		if ( ! empty( $categories ) ) {
			$category_links = array();
			foreach ( $categories as $category ) {
				$category_links[] = sprintf( '<a href="%s">%s</a>', esc_url( get_category_link( $category->term_id ) ), esc_html( $category->name ) );
			}
			echo implode( ', ', $category_links );
		}
	}

	/**
	 * Render Author Info with avatar and name (clickable).
	 *
	 * @param bool|string $type Optional. 'name' to render only name link.
	 * @return void
	 */
	private function render_author_info( $type = false ) {
		$author_id     = get_the_author_meta( 'ID' );
		$author_name   = get_the_author();
		$author_avatar = get_avatar( $author_id, 32 ); // 32px avatar size.
		$author_url    = get_author_posts_url( $author_id ); // Get the author archive URL.
		if ( $type == 'name' ) {
			printf(
				'<a class="author-name" href="%s">%s</a>',
				esc_url( $author_url ),
				esc_html( $author_name )
			);
		} else {
			printf(
				'<span class="author-avatar">%s</span> <a class="author-name" href="%s">%s</a>',
				$author_avatar,
				esc_url( $author_url ),
				esc_html( $author_name )
			);
		}
	}

	/**
	 * Render Share Count (custom meta key).
	 *
	 * @return void
	 */
	private function render_share_count() {
		$share_count = (int) get_post_meta( get_the_ID(), 'aae_post_shares_count', true );
		$share_count = aaeaddon_format_number_count( $share_count );
		echo esc_html( $share_count );
	}

	/**
	 * Render Post Date.
	 *
	 * @return void
	 */
	private function render_post_date() {
		$post_date = get_the_date();
		echo esc_html( $post_date );
	}

	/**
	 * Render Post Created Date.
	 *
	 * @return void
	 */
	private function render_created_date() {
		$created_date = get_the_date( 'F j, Y' ); // Or use any format like 'Y-m-d'.
		echo esc_html( $created_date );
	}

	/**
	 * Render Post Modified Date.
	 *
	 * @return void
	 */
	private function render_modified_date() {
		$modified_date = get_the_modified_date();
		echo esc_html( $modified_date );
	}

	/**
	 * Render Post Excerpt.
	 *
	 * @return void
	 */
	private function render_excerpt() {

		$post_id = $this->get_custom_id();

		// Fallback for Elementor templates or archives
		if ( ! $post_id ) {
			$post_id = apply_filters( 'elementor/dynamic_tags/post_id', 0 );
		}

		// Get word limit from widget control
		$limit = (int) $this->get_settings( 'aae_d_exceprp_limit' );

		// Sensible default
		if ( empty( $limit ) ) {
			$limit = 20;
		}

		// Get excerpt (manual excerpt preferred)
		$excerpt = get_the_excerpt( $post_id );

		// Fallback to content if excerpt is empty
		if ( empty( $excerpt ) ) {
			$excerpt = wp_strip_all_tags(
				get_post_field( 'post_content', $post_id )
			);
		}

		// Trim excerpt
		$excerpt = wp_trim_words(
			$excerpt,
			$limit,
			'…'
		);

		echo esc_html( $excerpt );
	}


	/**
	 * Render Post Title.
	 *
	 * @return void
	 */
	private function render_title() {
		$title = get_the_title();
		echo esc_html( $title );
	}

	/**
	 * Render Post Permalink.
	 *
	 * @return void
	 */
	private function render_permalink() {
		$permalink = get_permalink();
		echo esc_url( $permalink );
	}

	/**
	 * Render Post Status.
	 *
	 * @return void
	 */
	private function render_post_status() {
		$post_status = get_post_status();
		echo esc_html( ucfirst( $post_status ) );
	}

	/**
	 * Render Post Type.
	 *
	 * @return void
	 */
	private function render_post_type() {
		$post_type = get_post_type();
		echo esc_html( ucfirst( $post_type ) );
	}
}
