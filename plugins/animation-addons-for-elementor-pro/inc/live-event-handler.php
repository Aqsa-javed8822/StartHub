<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Live Events Post Type
function aae_register_live_events_post_type() {
	if(!wcf_addons_get_settings( 'wcf_save_widgets', 'live-events' )){
		return;
	}
	
	
	$labels = [
		'name'               => 'Live Events',
		'singular_name'      => 'Live Event',
		'add_new'            => 'Add New Event',
		'add_new_item'       => 'Add New Live Event',
		'edit_item'          => 'Edit Live Event',
		'new_item'           => 'New Live Event',
		'all_items'          => 'All Live Events',
		'view_item'          => 'View Live Event',
		'search_items'       => 'Search Live Events',
		'not_found'          => 'No live events found',
		'not_found_in_trash' => 'No live events found in Trash',
		'menu_name'          => 'Live Events'
	];

	$args = [
		'labels'       => $labels,
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-calendar',
		'supports'     => [ 'title', 'editor', 'thumbnail', 'elementor', 'page-attributes' ],
		'hierarchical' => true,
		'show_in_rest' => true,
	];

	register_post_type( 'aae_live_event', $args );
}

add_action( 'init', 'aae_register_live_events_post_type' );


// Location Custom Field
function aae_add_live_event_meta_box() {
	$post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
	if (!$post_id) return;

	$post = get_post($post_id);
	if (!$post || $post->post_type !== 'aae_live_event') return;

	// Only add meta box if it's a parent post
	if ((int) $post->post_parent === 0) {
		add_meta_box(
			'aae_live_event_location',
			'Location',
			'aae_render_live_event_location',
			'aae_live_event',
			'normal',
			'default'
		);
	}
}
add_action('add_meta_boxes', 'aae_add_live_event_meta_box');


function aae_render_live_event_location( $post ) {
	$value = get_post_meta( $post->ID, '_aae_event_location', true );
	wp_nonce_field( 'aae_save_event_location', 'aae_event_location_nonce' );
	echo '<label for="aae_event_location" style="margin-right: 10px">Location:</label>';
	echo '<input type="text" id="aae_event_location" name="aae_event_location" value="' . esc_attr( $value ) . '">';
}

function aae_save_live_event_location( $post_id ) {
	if ( ! isset( $_POST['aae_event_location_nonce'] ) || ! wp_verify_nonce( $_POST['aae_event_location_nonce'], 'aae_save_event_location' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['aae_event_location'] ) ) {
		update_post_meta( $post_id, '_aae_event_location', sanitize_text_field( $_POST['aae_event_location'] ) );
	}
}

add_action( 'save_post', 'aae_save_live_event_location' );
