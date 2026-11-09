<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Hooks and filters for the Post Ratings custom post type.
add_action( 'init', 'aaeaddon_register_post_rating_cpt' );
add_action( 'admin_footer-post.php', 'aaeaddon_disable_post_rating_title_field' );
add_action( 'admin_footer-post-new.php', 'aaeaddon_disable_post_rating_title_field' );
add_filter( 'manage_aaeaddon_post_rating_posts_columns', 'aaeaddon_post_rating_columns' );
add_action( 'add_meta_boxes', 'aaeaddon_add_review_meta_boxes' );
add_action( 'save_post', 'aaeaddon_save_review_meta_box' );
add_action( 'wp_ajax_aaeaddon_submit_post_review_rating', 'handle_post_rating_submission' );
add_action( 'wp_ajax_nopriv_aaeaddon_submit_post_review_rating', 'handle_post_rating_submission' );

/* add_action( 'manage_aaeaddon_post_rating_posts_custom_column', 'aaeaddon_post_rating_custom_column_content', 10, 2 ); */

/**
 * Register the Post Ratings custom post type.
 *
 * @return void
 */
function aaeaddon_register_post_rating_cpt() {

	if ( ! wcf_addons_get_settings( 'wcf_save_widgets', 'post-rating-form' ) ) {
		return;
	}
	register_post_type(
		'aaeaddon_post_rating',
		array(
			'labels'    => array(
				'name'          => 'Post Ratings',
				'singular_name' => 'Post Rating',
			),
			'public'    => false,
			'show_ui'   => true,
			'menu_icon' => 'dashicons-star-filled',
			'supports'  => array( 'title' ),
		)
	);
}

/**
 * Customize the columns displayed in the Post Ratings list table.
 *
 * @param array $columns Default columns for the Post Ratings list table.
 *
 * @return array
 */
function aaeaddon_post_rating_columns( $columns ) {
	return array(
		'cb'                 => '<input type="checkbox" />',
		'title'              => 'Post Title',
		'reviewed_post_type' => 'Post Type',
		'name'               => 'Author',
		'rating'             => 'Rating',
		'review'             => 'Review',
		'date'               => 'Date',
	);
}

/**
 * Add a meta-box to the Post Ratings edit screen for entering review details.
 *
 * @return void
 */
function aaeaddon_add_review_meta_boxes() {
	add_meta_box( 'aaeaddon_review_details', 'Review Details', 'aaeaddon_review_meta_box_callback', 'aaeaddon_post_rating', 'normal', 'default' );
}

/**
 * Render the meta-box content for entering review details.
 *
 * @param object $post Post object.
 *
 * @return void
 */
function aaeaddon_review_meta_box_callback( $post ) {
	$user_id = get_post_meta( $post->ID, 'user_id', true );
	$name    = get_post_meta( $post->ID, 'name', true );
	$email   = get_post_meta( $post->ID, 'email', true );
	$rating  = get_post_meta( $post->ID, 'rating', true );
	$review  = get_post_meta( $post->ID, 'review', true );

	wp_nonce_field( 'aaeaddon_review_meta_box', 'aaeaddon_review_meta_box_nonce' );

	?>
	<p>
		<label><strong>Name:</strong></label><br>
		<input type="text" name="aae_name"
				value="<?php echo esc_attr( $user_id ? get_the_author_meta( 'display_name', $user_id ) : $name ); ?>" <?php echo $user_id ? 'readonly' : ''; ?>
				class="widefat"/>
	</p>
	<p>
		<label><strong>Email:</strong></label><br>
		<input type="email" name="aae_email"
				value="<?php echo esc_attr( $user_id ? get_the_author_meta( 'user_email', $user_id ) : $email ); ?>" <?php echo $user_id ? 'readonly' : ''; ?>
				class="widefat"/>
	</p>
	<p>
		<label><strong>Rating (1-5):</strong></label><br>
		<input type="number" name="aae_rating" value="<?php echo esc_attr( $rating ); ?>" min="1" max="5"
				class="small-text"/>
	</p>
	<p>
		<label><strong>Review:</strong></label><br>
		<textarea name="aae_review" rows="5" class="widefat"><?php echo esc_textarea( $review ); ?></textarea>
	</p>
	<?php
}

/**
 * Save the review details for a post.
 *
 * @param int $post_id Post ID.
 *
 * @return void
 */
function aaeaddon_save_review_meta_box( $post_id ) {
	if ( ! isset( $_POST['aaeaddon_review_meta_box_nonce'] ) ||
		! wp_verify_nonce( $_POST['aaeaddon_review_meta_box_nonce'], 'aaeaddon_review_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( get_post_type( $post_id ) !== 'aaeaddon_post_rating' ) {
		return;
	}

	if ( isset( $_POST['aae_rating'] ) ) {
		update_post_meta( $post_id, 'rating', intval( $_POST['aae_rating'] ) );
	}

	if ( isset( $_POST['aae_review'] ) ) {
		update_post_meta( $post_id, 'review', sanitize_text_field( $_POST['aae_review'] ) );
	}

	$user_id = get_post_meta( $post_id, 'user_id', true );
	if ( ! $user_id ) {
		if ( isset( $_POST['aae_name'] ) ) {
			update_post_meta( $post_id, 'name', sanitize_text_field( $_POST['aae_name'] ) );
		}
		if ( isset( $_POST['aae_email'] ) ) {
			update_post_meta( $post_id, 'email', sanitize_email( $_POST['aae_email'] ) );
		}
	}
}

/**
 * Handle AJAX submission of a post-rating.
 *
 * @return void
 */
function handle_post_rating_submission() {
	if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wcf-addons-frontend' ) ) {
		wp_send_json_error( array( 'message' => 'Security check failed.' ) );
	}

	if ( ! isset( $_POST['post_id'], $_POST['rating'], $_POST['review'] ) ) {
		wp_send_json_error( array( 'message' => 'Invalid data.' ) );
	}

	$post_id     = intval( $_POST['post_id'] );
	$rating      = intval( $_POST['rating'] );
	$review_text = sanitize_text_field( $_POST['review'] );
	$user_id     = get_current_user_id();

	$name  = $user_id ? get_the_author_meta( 'display_name', $user_id ) : sanitize_text_field( $_POST['name'] ?? '' );
	$email = $user_id ? get_the_author_meta( 'user_email', $user_id ) : sanitize_email( $_POST['email'] ?? '' );

	$require_approval = isset( $_POST['require_approval'] ) && $_POST['require_approval'] === 'yes';
	$post_status      = $require_approval ? 'pending' : 'publish';

	$post_title = get_the_title( $post_id );
	$post_type  = get_post_type( $post_id );

	$rating_post_id = wp_insert_post(
		array(
			'post_type'   => 'aaeaddon_post_rating',
			'post_title'  => $post_title,
			'post_status' => $post_status,
			'meta_input'  => array(
				'post_id'            => $post_id,
				'user_id'            => $user_id,
				'name'               => $name,
				'email'              => $email,
				'rating'             => $rating,
				'review'             => $review_text,
				'reviewed_post_type' => $post_type,
			),
		)
	);

	$existing_count = (int) get_post_meta( $post_id, 'review_count', true );
	update_post_meta( $post_id, 'review_count', $existing_count + 1 );

	if ( $rating_post_id ) {
		wp_send_json_success( array( 'message' => $require_approval ? 'Review submitted for approval.' : 'Review submitted successfully!' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Failed to save review.' ) );
	}
}


/**
 * Disable the title field for the Post Rating post type on the admin screens.
 *
 * @return void
 */
function aaeaddon_disable_post_rating_title_field() {
	global $post;

	if ( get_post_type( $post ) !== 'aaeaddon_post_rating' ) {
		return;
	}
	?>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const titleField = document.getElementById('title');
			if (titleField) {
				titleField.setAttribute('readonly', 'readonly');
				titleField.style.backgroundColor = '#f9f9f9';
			}
		});
	</script>
	<?php
}

/**
 * Display the custom column content for the Post Ratings list table.
 *
 * @param array $column Column array.
 * @param int   $post_id Post ID.
 *
 * @return void
 */
function aaeaddon_post_rating_custom_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'reviewed_post_type':
			$type = get_post_meta( $post_id, 'reviewed_post_type', true );
			echo $type ? esc_html( $type ) : 'N/A';
			break;

		case 'name':
			$user_id = get_post_meta( $post_id, 'user_id', true );
			if ( $user_id ) {
				$name = get_the_author_meta( 'display_name', $user_id );
			} else {
				$name = get_post_meta( $post_id, 'name', true );
			}
			echo esc_html( $name ?: 'Anonymous' );
			break;

		case 'rating':
			echo intval( get_post_meta( $post_id, 'rating', true ) ) ?: 'N/A';
			break;

		case 'review':
			echo esc_html( get_post_meta( $post_id, 'review', true ) ) ?: 'N/A';
			break;
	}
}


/**
 * Remove the "Add New" button from the Post Ratings list screen.
 *
 * @return void
 */
add_action(
	'admin_menu',
	function () {
		remove_submenu_page( 'edit.php?post_type=aaeaddon_post_rating', 'post-new.php?post_type=aaeaddon_post_rating' );
	}
);

/**
 * Prevent the creation of new Post Ratings from the frontend.
 *
 * @return void
 */
add_action(
	'load-post-new.php',
	function () {
		if ( isset( $_GET['post_type'] ) && 'aaeaddon_post_rating' === $_GET['post_type'] ) {
			wp_die( 'You are not allowed to create a new post rating manually.' );
		}
	}
);

/**
 * Hide the "Add New" button from the Post Ratings edit screen.
 *
 * @return void
 */
add_action(
	'admin_head',
	function () {
		$screen = get_current_screen();
		if ( $screen && 'aaeaddon_post_rating' === $screen->post_type && 'edit' === $screen->base ) {
			echo '<style>
			.page-title-action {
				display: none !important;
			}
        </style>';
		}
	}
);