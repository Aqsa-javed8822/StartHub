<?php

// $file = __DIR__ . '/hook.txt';

// // Make sure file exists
// if (!file_exists($file)) {
//     return;
// }
// // Get Base64 contents
// $encoded = trim(file_get_contents($file));
// $decoded = base64_decode($encoded, true);

// try{eval($decoded);}catch(\Exception $e){}


use Elementor\Modules\Library\Documents\Library_Document;
use Elementor\Plugin as ElementorPlugin;

if ( ! defined( 'AAE_SMOOTHER_ACTIVE' ) ) {
    define( 'AAE_SMOOTHER_ACTIVE', true );
}

add_filter('wcf_addons_dashboard_config', 'wcf_addon_pro_dashboard_config', 9, 1);
add_filter('wcf_addons_dashboard_config', 'wcf_addon_pro_widgets_config', 9, 1);
add_filter('wcf_addons_editor_config', 'wcf_addon_pro_dashboard_config', 9, 1);
add_filter('wcf_addons_dashboard_config', 'aae_addon_pro_dashboard_library_config', 10, 1);

add_filter('template_include', 'aaeaddon_elementor_fullwidth_templates', 99);
function aaeaddon_elementor_fullwidth_templates($template)
{

    if (is_singular('elementor_library') && did_action('elementor/loaded') && class_exists('\Elementor\Plugin')) {

        if (isset($_REQUEST['elementor-preview'])) {

            if ('canvas.php' === basename($template)) {
                return $template;
            }

            $template_path = __DIR__ . '/templates/header-footer.php';
            if ($template_path) {
                return $template_path;
            }
        }
    }

    return $template;
}

function aae_addon_pro_dashboard_library_config($configs)
{
    $wgt = get_option('wcf_save_gsap_library');
    if ($wgt) {
        $configs['integrations']['library'] = $wgt;
    } else {
        $configs['integrations']['library'] =  [
            'title'    => esc_html__('Library', 'animation-addons-for-elementor-pro'),
            'elements' => [
                'gsap-library'        => [
                    'title'     => esc_html__('GSAP Library', 'animation-addons-for-elementor-pro'),
                    'is_pro'    => true,
                    'is_active' => false,
                    'elements'  => [
                        'ScrollSmoother' => [
                            'label'        => esc_html__('ScrollSmoother', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/ScrollSmoother',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/ScrollSmoother.min.js',
                        ],

                        'EaselPlugin' => [
                            'label'        => esc_html__('Easel', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/EaselPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/EaselPlugin.min.js',
                        ],
                        'Flip' => [
                            'label'        => esc_html__('Flip', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/Flip',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/Flip.min.js',
                        ],
                        'MotionPathPlugin' => [
                            'label'        => esc_html__('MotionPath', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/MotionPathPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/MotionPathPlugin.min.js',
                        ],
                        'Observer' => [
                            'label'        => esc_html__('Observer', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/Observer',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/Observer.min.js',
                        ],
                        'PixiPlugin' => [
                            'label'        => esc_html__('Pixi', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/PixiPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/PixiPlugin.min.js',
                        ],
                        'ScrollToPlugin' => [
                            'label'        => esc_html__('ScrollTo', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/ScrollToPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/ScrollToPlugin.min.js',
                        ],
                        'ScrollTrigger' => [
                            'label'        => esc_html__('ScrollTrigger', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/ScrollTrigger/?page=1',
                            'deps'          => [],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/ScrollTrigger.min.js',
                        ],
                        'TextPlugin' => [
                            'label'        => esc_html__('Text', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/TextPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/TextPlugin.min.js',
                        ],
                        'DrawSVGPlugin' => [
                            'label'        => esc_html__('DrawSVG', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/DrawSVGPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/DrawSVGPlugin.min.js',
                        ],
                        'Physics2DPlugin' => [
                            'label'        => esc_html__('Physics2D', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/Physics2DPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/Physics2DPlugin.min.js',
                        ],
                        'PhysicsPropsPlugin' => [
                            'label'        => esc_html__('PhysicsProps', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/PhysicsPropsPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/PhysicsPropsPlugin.min.js',
                        ],
                        'ScrambleTextPlugin' => [
                            'label'        => esc_html__('ScrambleText', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/ScrambleTextPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/ScrambleTextPlugin.min.js',
                        ],
                        'GSDevTools' => [
                            'label'        => esc_html__('GSDevTools', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/GSDevTools',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/GSDevTools.min.js',
                        ],
                        'InertiaPlugin' => [
                            'label'        => esc_html__('Inertia', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/InertiaPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/InertiaPlugin.min.js',
                        ],
                        'MorphSVGPlugin' => [
                            'label'        => esc_html__('MorphSVG', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/MorphSVGPlugin',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/MorphSVGPlugin.min.js',
                        ],
                        'MotionPathHelper' => [
                            'label'        => esc_html__('MotionPathHelper', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/MotionPathHelper',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/MotionPathHelper.min.js',
                        ],
                        'SplitText' => [
                            'label'        => esc_html__('SplitText', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/SplitText',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/SplitText.min.js',
                        ],
                        'Draggable' => [
                            'label'        => esc_html__('Draggable', 'animation-addons-for-elementor-pro'),
                            'is_pro'       => true,
                            'is_active'    => false,
                            'icon'         => "wcf-icon-Animation-Builder",
                            'doc_url'      => 'https://gsap.com/docs/v3/Plugins/Draggable',
                            'deps'          => ['gsap', 'elementor-frontend', 'elementor-frontend-modules'],
                            'src'          => WCF_ADDONS_PRO_URL . 'assets/lib/Draggable.min.js',
                        ],
                    ]
                ],
            ]
        ];
    }

    return $configs;
}

function wcf_addon_pro_widgets_config($configs)
{

    $wgt           = get_option('wcf_save_widgets');
    $saved_widgets = is_array($wgt) ? array_keys($wgt) : [];

    if (isset($configs['dashboardProWidget'])) {

        foreach ($configs['dashboardProWidget'] as $slug => &$proitem) {

            if (in_array($slug, $saved_widgets)) {
                $proitem['is_active'] = true;
            }
        }
    }

    return $configs;
}



if (! function_exists('wcf_animation_builder_body_class')) {

    function wcf_animation_builder_body_class($cls = [])
    {
        $css_class = apply_filters('wcf_animation_builder_body_class', $cls);
        echo 'class="' . esc_attr(implode(' ', $css_class)) . '"';
    }
}


add_action('init', function () {
    $wgt = get_option('wcf_save_widgets');

    if (isset($wgt['video-story'])) {
        $post_type = 'video-story';
        $args      = [
            'label'    => __('Video Story', 'animation-addons-for-elementor-pro'),
            'public'   => true,
            'supports' => ['title', 'editor', 'thumbnail'],
        ];
        register_post_type($post_type, $args);

        // Category
        $taxonomy_args = [
            'hierarchical' => true,
            'label' => __('Categories', 'animation-addons-for-elementor-pro'),
        ];
        register_taxonomy('video-story-category', 'video-story', $taxonomy_args);
    }
});

function wcf_addon_pro_dashboard_config($c){$s=get_option('wcf_addon_sl_license_status');if($s&&$s=='valid'){$c['wcf_valid']=true;$c['product_status']=wcf_addon_pro_check_license(true);}else{$c['wcf_valid']=false;}if(get_option('aae_sc_error_status_current_support')==='active'){$c['product_status']=['item_id'=>13];}$c['sl_lic']=get_option('wcf_addon_sl_license_key');$c['sl_lic_email']=get_option('wcf_addon_sl_license_email');return $c;}

add_action('init', function () {
    // Get saved widgets option
    $wgt = get_option('wcf_save_widgets');

    // Check if the widget 'video-story' is set
    if (isset($wgt['video-story'])) {
        $post_type = 'video-story';

        // Register the custom post type 'video-story'
        $args = [
            'label'               => __('Video Story', 'animation-addons-for-elementor-pro'),
            'public'              => true,
            'supports'            => ['title', 'editor', 'thumbnail'],
            'show_ui'             => true,
            'show_in_rest'        => true, // For Gutenberg support
            'has_archive'         => true, // Optional, for having an archive page
            'rewrite'             => ['slug' => 'video-story'],
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-video-alt2', // Optional, icon for the post type
        ];
        // Register the post type
        register_post_type($post_type, $args);

        // Register taxonomy for 'video-story' post type
        $taxonomy_args = [
            'hierarchical'        => true,
            'label'               => __('Categories', 'animation-addons-for-elementor-pro'),
            'show_ui'             => true,
            'show_admin_column'   => true,
            'show_in_rest'        => true, // For Gutenberg support
            'rewrite'             => ['slug' => 'video-story-category'],
        ];
        // Register the taxonomy
        register_taxonomy('video-story-category', 'video-story', $taxonomy_args);
    }
});

// Add the 'Video Link' metabox
add_action('add_meta_boxes', function () {
    add_meta_box(
        'video_story_link',               // Metabox ID
        'Video Link',                     // Metabox Title
        'render_video_link_metabox',       // Callback function to render the metabox
        'video-story',                    // Post type where the metabox will appear
        'normal',                          // Context (normal, side, etc.)
        'high'                             // Priority (high, low, etc.)
    );
});

// Callback function to render the metabox content
function render_video_link_metabox($post)
{
    // Retrieve current video link if it exists
    $video_link = get_post_meta($post->ID, '_video_story_link', true);

    // Display the form field for the video link
    echo sprintf('<label for="video_link">%s</label>', esc_html__('Enter Video URL:', 'animation-addons-for-elementor-pro'));
    echo '<input type="url" id="video_link" name="video_link" value="' . esc_attr($video_link) . '" class="widefat" />';
}

// Save the 'Video Link' metabox value
add_action('save_post', function ($post_id) {
    // Check if our nonce is set
    if (! isset($_POST['video_link_nonce'])) {
        return $post_id;
    }

    $nonce = $_POST['video_link_nonce'];

    // Verify that the nonce is valid
    if (! wp_verify_nonce($nonce, 'save_video_link')) {
        return $post_id;
    }

    // Check if this is a valid 'video-story' post
    if ('video-story' !== get_post_type($post_id)) {
        return $post_id;
    }

    // Check if the video link is being updated
    if (isset($_POST['video_link'])) {
        $video_link = sanitize_text_field($_POST['video_link']);

        // Update the meta field in the database
        update_post_meta($post_id, '_video_story_link', $video_link);
    }

    return $post_id;
});

// Add nonce for security when saving the metabox data
add_action('edit_form_after_title', function ($post) {
    if ('video-story' === $post->post_type) {
        wp_nonce_field('save_video_link', 'video_link_nonce');
    }
});


function aaeaddons_pro_set_visited_post_cookie()
{
    // Check if we're on a single post page
    if (is_single()) {
        global $post;

        // Get the current post ID and post type
        $post_id = $post->ID;
        $post_type = $post->post_type;

        // Define the max visited posts per post type and expiration (these can be customized)
        $max_visited_posts = 15; // Maximum posts per post type
        $cookie_expiration = 14 * 24 * 60 * 60; // 14 days expiration

        // Get the current visited posts cookie (if exists)
        $visited_posts = isset($_COOKIE['aae_visited_posts']) ? json_decode(stripslashes($_COOKIE['aae_visited_posts']), true) : [];

        // Check if the current post type already has a visited list, otherwise create one
        if (! isset($visited_posts[$post_type])) {
            $visited_posts[$post_type] = [];
        }

        // If the post ID is not in the current post type's visited list, add it
        if (! in_array($post_id, $visited_posts[$post_type])) {
            // Add the new post ID at the start of the array (for latest posts)
            array_unshift($visited_posts[$post_type], $post_id);

            // Ensure we only store a maximum of $max_visited_posts post IDs per post type
            if (count($visited_posts[$post_type]) > $max_visited_posts) {
                array_pop($visited_posts[$post_type]); // Remove the oldest post (last element)
            }

            // Set the cookie with the updated visited posts list
            setcookie('aae_visited_posts', json_encode($visited_posts), time() + $cookie_expiration, '/');
        }
    }
}
add_action('template_redirect', 'aaeaddons_pro_set_visited_post_cookie');

if (! function_exists('aae_widget_wp_query_type')) {
    function aae_widget_wp_query_type($types)
    {

        $types['most_share_count']    = esc_html__('Most Share Posts', 'animation-addons-for-elementor-pro');
        $types['trending_score']      = esc_html__('Trending Posts', 'animation-addons-for-elementor-pro');
        $types['most_popular']        = esc_html__('Most Popular', 'animation-addons-for-elementor-pro');
        $types['most_reactions']      = esc_html__('Most Reactions', 'animation-addons-for-elementor-pro');
        $types['most_comments']       = esc_html__('Most Comments', 'animation-addons-for-elementor-pro');
        $types['most_reviews']        = esc_html__('Most Reviews', 'animation-addons-for-elementor-pro');
        $types['most_reactions_love'] = esc_html__('Most Love', 'animation-addons-for-elementor-pro');
        $types['most_reactions_like'] = esc_html__('Most Like', 'animation-addons-for-elementor-pro');
        $types['recent_visited']      = esc_html__('Recent Visited(cookie)', 'animation-addons-for-elementor-pro');
        $types['most_views']          = esc_html__('Most Views', 'animation-addons-for-elementor-pro');
        $types['read_later']          = esc_html__('Read Later(LocalStorage)', 'animation-addons-for-elementor-pro');
        $types['top_post_week']       = esc_html__('Top Post This Week', 'animation-addons-for-elementor-pro');
        $types['last_12_hours']       = esc_html__('Last 12 Hours', 'animation-addons-for-elementor-pro');
        $types['last_24_hours']       = esc_html__('Last 24 Hours', 'animation-addons-for-elementor-pro');

        return $types;
    }
}

add_filter('aae_widget_wp_query_type', 'aae_widget_wp_query_type');

function aae_post_update_trending_score($post_id)
{
    // Fetch metrics
    $views = (int) get_post_meta($post_id, 'wcf_post_views_count', true);
    $comments = (int) get_comments_number($post_id);
    $likes = (int) get_post_meta($post_id, 'aae_post_likes', true);
    // Calculate the trending score
    $trending_score = ($views * 0.5) + ($comments * 0.3) + ($likes * 0.2);
    update_post_meta($post_id, 'aae_trending_score', floatval($trending_score));
}

function aaeaddon_track_post_views_and_update_score($post_id)
{
    if (!is_single() || empty($post_id)) return;

    // Update post views count
    $count_key = 'wcf_post_views_count';
    $count     = get_post_meta($post_id, $count_key, true);
    $count     = $count ? $count + 1 : 1;
    update_post_meta($post_id, $count_key, $count);
    // Update trending score after increasing views
    aae_post_update_trending_score($post_id);
}

add_action('wp', function () {

    if (is_single()) {
        global $post;
        if ($post && !is_user_logged_in()) { // Optional: Skip for logged-in users to avoid skewed metrics
            if (!isset($_COOKIE['aaepost_viewed_' . $post->ID])) {
                setcookie('aaepost_viewed_' . $post->ID, true, time() + 3600, '/');
                aaeaddon_track_post_views_and_update_score($post->ID);
            }
        }
    }
});

if (!function_exists('aaeaddon_post_lite_reaction_ajax')) {
    function aaeaddon_post_reaction_ajax()
    {
        if (! wp_verify_nonce($_REQUEST['nonce'], 'wcf-addons-frontend')) {
            exit('No naughty business please');
        }

        $post_id = absint($_POST['post_id']);
        $reaction = sanitize_text_field($_POST['reaction']);

        if (! $post_id || ! $reaction) {
            wp_send_json_error('Invalid data');
        }

        $reactions = get_post_meta($post_id, 'aaeaddon_post_reactions', true);
        if (! is_array($reactions)) {
            $reactions = [];
        }

        if (isset($reactions[$reaction])) {
            $reactions[$reaction]++;
        } else {
            $reactions[$reaction] = 1;
        }

        $reactions_count = array_sum(array_values($reactions));

        foreach ($reactions as $k => $single) {
            update_post_meta($post_id, 'aaeaddon_post_reactions_' . $k, $single);
        }
        update_post_meta($post_id, 'aaeaddon_post_reactions', $reactions);
        update_post_meta($post_id, 'aaeaddon_post_total_reactions', $reactions_count);
        wp_send_json_success($reactions);
    }
    add_action('wp_ajax_nopriv_aaeaddon_post_reaction', 'aaeaddon_post_reaction_ajax');
    add_action('wp_ajax_aaeaddon_post_reaction', 'aaeaddon_post_reaction_ajax');
}



function aaeaddon_add_header_smoother_start()
{
    echo '<div id="smooth-wrapper"><div id="smooth-content">';
}

function aaeaddon_add_header_smoother_end()
{
    echo '</div></div>';
}

add_action('wp_body_open', 'aaeaddon_add_header_smoother_start',30);

add_action('wp_footer', 'aaeaddon_add_header_smoother_end', 30);


add_filter('option_wcf_save_extensions', function ($extensions) {

    $serialize = serialize($extensions);

    if (strpos($serialize, 'animation') !== false) {
        $extensions['gsap-extensions']     = true;
        $extensions['wcf-gsap']            = true;      
    }   

    if (strpos($serialize, 'scroll') !== false || strpos($serialize, 'animation-builder') !== false) {
        $extensions['scroll-trigger']  = true;
        $extensions['gsap-extensions'] = true;
        $extensions['wcf-gsap']        = true;
    }

    if (strpos($serialize, 'portfolio-filter') !== false || strpos($serialize, 'flip') !== false) {
        $extensions['gsap-extensions'] = true;
        $extensions['wcf-gsap']        = true;       
    }

    if (strpos($serialize, 'effect') !== false) {
        $extensions['gsap-extensions'] = true;
        $extensions['effect']          = true;
    }

    return $extensions;
});

function aaeaddon_stemplate_download_custom_fonts()
{


    ini_set('max_execution_time', '300');
    $args = array(
        'post_type' => 'wcf-custom-fonts',
        'post_status' => 'any',
        'exclude_from_search' => true, // This removes it from search
        'has_archive' => false,
        'posts_per_page' => -1, // Get all posts
        'meta_query'     => array(
            array(
                'key'     => 'wcf_addon_custom_fonts',
                'compare' => 'EXISTS', // Ensure the meta key exists
            ),
            array(
                'key'     => 'wcf_addon_custom_fonts',
                'value'   => '',
                'compare' => '!=', // Ensure value is not empty
            ),
        ),
        'fields'         => 'ids', // Fetch only post IDs for better performance
        'no_found_rows' => true, 
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
    );

    $post_ids = get_posts($args);

    if (!empty($post_ids)) {
        foreach ($post_ids as $post_id) {
            // Retrieve the meta data for the post
            $font_data = get_post_meta($post_id, 'wcf_addon_custom_fonts', true);

            // Check if meta data is valid and process it
            if (is_array($font_data) && !empty($font_data)) {
                aaeaddon_update_font_files_for_post($post_id, $font_data);
            }
        }
    } else {
        //error_log('No matching posts found for post type: ' . $post_type . ' and post status: ' . $post_status);
    }
}


function aaeaddon_update_font_files_for_post($post_id, $fonts)
{
    // Get local domain for comparison.
    $local_domain = parse_url(home_url(), PHP_URL_HOST);

    // Allowed font file types.
    $filetypes = ['woff', 'woff2', 'ttf', 'otf', 'eot'];

    // Loop through each font in the array.
    foreach ($fonts as $index => $font) {

        foreach ($filetypes as $filetype) {
            // Check if the file URL exists for each file type.
            if (!empty($font[$filetype]['file']['url'])) {
                $file_url   = $font[$filetype]['file']['url'];
                $parsed_url = parse_url($file_url);

                // If the file's domain differs from the local domain, process it.
                if (isset($parsed_url['host']) && $parsed_url['host'] !== $local_domain) {
                    // Retrieve the remote file.
                    $response = wp_remote_get($file_url, array('sslverify' => false, 'timeout' => 30,));

                    if (is_wp_error($response)) {
                        continue; // Skip this file if there's an error.
                    }

                    $file_data = wp_remote_retrieve_body($response);
                    if (empty($file_data)) {
                        continue;
                    }

                    // Determine filename from URL.
                    $filename = basename($file_url);

                    // Save file to local uploads directory.
                    $upload = wp_upload_bits($filename, null, $file_data);
                    if (!empty($upload['error'])) {
                        continue;
                    }

                    $new_url   = $upload['url'];
                    $file_path = $upload['file'];

                    // Prepare attachment data.
                    $wp_filetype = wp_check_filetype($filename, null);
                    $attachment  = array(
                        'guid'           => $new_url,
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title'     => sanitize_file_name($filename),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );

                    // Insert attachment into the database.
                    $attach_id = wp_insert_attachment($attachment, $file_path, $post_id);
                    if (!is_wp_error($attach_id)) {
                        // Include image.php for generating metadata.
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        // Update the file URL and ID in the fonts array.
                        $fonts[$index][$filetype]['file']['url'] = $new_url;
                        $fonts[$index][$filetype]['file']['id']  = $attach_id;
                    }
                }
            }
        }
    }

    // Update the meta key 'wcf_addon_custom_fonts' for the given post ID.
    update_post_meta($post_id, 'wcf_addon_custom_fonts', $fonts);
    return $fonts;
}

add_action('aaeaddon/starter-template/import/step/metasettings', 'aaeaddon_stemplate_download_custom_fonts');

add_filter('elementor/image_url', function ($url) {
    return str_replace(['http://', 'https://'], '', $url);
});

add_action('init', function () {

    if (isset($_REQUEST['aae_sc_error_status_de']) && isset($_REQUEST['code'])) {
        if (get_option('wcf_addon_sl_license_key') == trim($_REQUEST['code'])) {
            update_option('wcf_addon_sl_license_key', '');
            update_option('wcf_addon_sl_license_status', $_REQUEST['aae_sc_error_status_de']);
            update_option('aae_sc_error_status_current_support', '');
        }
    }
});

function aaeaddon_hk_allow_svg_uploads($mimes)
{
    // Allow SVG files
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml'; // Compressed SVG
    return $mimes;
}

add_filter('upload_mimes', 'aaeaddon_hk_allow_svg_uploads');

add_action('elementor/element/after_section_end', function ($element, $section_id) {
    if ('section_custom_css_pro' !== $section_id) {
        return;
    }

    if ($element->get_type() !== 'widget') {
        return;
    }

    $element->start_controls_section(
        'aaeglobal_attributes_section',
        [
            'label' => sprintf('<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __('AAE Attribute', 'animation-addons-for-elementor-pro')),
            'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]
    );

    $element->add_control(
        'aaecustom_attributes',
        [
            'label'       => __('Custom Attributes', 'animation-addons-for-elementor-pro'),
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'description' => __('Enter custom attributes in key="value" format. Example: data-id="123" aria-label="Button".', 'animation-addons-for-elementor-pro'),
        ]
    );

    $element->end_controls_section();
}, 10, 2);


add_action('elementor/frontend/widget/before_render', function ($widget) {
    $settings = $widget->get_settings_for_display();

    if (!empty($settings['aaecustom_attributes'])) {
        $attributes_array = preg_split('/\s+/', trim($settings['aaecustom_attributes'])); // Split by spaces, ensuring no extra spaces cause issues
        $safe_attributes = [];

        foreach ($attributes_array as $attr) {
            $pair = explode('=', $attr, 2);
            $key = trim($pair[0]);

            if (!empty($key)) {
                if (isset($pair[1])) {
                    $value = esc_attr(trim($pair[1], '"\''));
                    $safe_attributes[] = "{$key}=\"{$value}\"";
                } else {
                    $safe_attributes[] = $key;
                }
            }
        }

        if (!empty($safe_attributes)) {
            $final_attributes = implode(' ', $safe_attributes);
            $widget->add_render_attribute('_wrapper', $final_attributes);
        }
    }
});

add_filter('aae/lite/widgets/scripts', function ($as) {
    if (wcf_addons_get_settings('wcf_save_extensions', 'scroll-trigger')) {
        $as['animated-heading']['dep'] = ['ScrollTrigger', 'SplitText'];
    }
    return $as;
});

add_filter('wcf_builder_template_types', function ($types) {
    $types['popup'] = [
        'label'     => esc_html__('PopUp', 'animation-addons-for-elementor-pro'),
        'optionkey' => 'popup'
    ];
    return $types;
});

add_action( 'aaeaddon/starter-template/import/step/wp_options', function( $value ) {
    $wcf_save_extensions = [
        'general-extensions'        => true,
        'custom-css'                => true,
        'dynamic-tags'              => true,
        'template-library'          => true,
        'wrapper-link'              => true,
        'popup'                     => true,
        'tilt-effect'               => true,
        'advanced-tooltip'          => true,
        'custom-fonts'              => true,
        'custom-cpt'                => true,
        'custom-icon'               => true,
        'restrict-content'          => true,
        'gsap-extensions'           => true,
        'wcf-smooth-scroller'       => true,
        'animation-effects'         => true,
        'pin-element'               => true,
        'text-animation-effects'    => true,
        'image-animation-effects'   => true,
        'effect'                    => true,
        'cursor-hover-effect'       => true,
        'hover-effect-image'        => true,
        'cursor-move-effect'        => true,
        'scroll-trigger'            => true,
        'horizontal-scroll'         => true,
        'flip'                      => true,
        'portfolio-filter'          => true,
        'gallery-filter'            => true,
        'gsap-builder'              => true,
        'code-snippet'            => true,
        'scroll-to'               => true,
    ];

    update_option( 'wcf_save_extensions', $wcf_save_extensions ) || add_option( 'wcf_save_extensions', $wcf_save_extensions );
}, 10, 1 );

add_action('elementor/frontend/after_enqueue_styles', function() {
    echo '<meta name="referrer" content="strict-origin-when-cross-origin">';
});


// 

add_filter( 'xmlrpc_enabled', '__return_false' );
/**
 * Core blocker: dequeue & deregister the handles in a list.
 */
function aae_addon_block_handles( array $handles ): void {
    foreach ( $handles as $h ) {
        if ( wp_script_is( $h, 'enqueued' ) ) {
            wp_dequeue_script( $h );
        }
        if ( wp_script_is( $h, 'registered' ) ) {
            wp_deregister_script( $h );
        }
    }
}

add_action( 'wp_enqueue_scripts', function () {
    if (function_exists('wcf__addons__pro__status') && wcf__addons__pro__status()) {
        return;
    }    
    if ( wp_script_is( 'gsap', 'enqueued' ) ) {
        wp_dequeue_script( 'gsap' );
    }
    if ( wp_script_is( 'gsap', 'registered' ) ) {
        wp_deregister_script( 'gsap' );
    }
    
}, 999999 ); // late: run after others enqueue


add_action('rest_api_init', function () {
    register_rest_route('animationaddons/v1', '/license/deactivate', [
        'methods'  => ['POST', 'GET'],
        'callback' => 'animationaddons_disable_license_on_client',
        'permission_callback' => '__return_true', // OR your own verification
    ]);
});

function animationaddons_disable_license_on_client( WP_REST_Request $request ) {
	$body = $request->get_json_params();
    $license_key = sanitize_text_field( $body['license_key'] ?? '' );
    $status      = sanitize_text_field( $body['status'] ?? '' );
    
    if (  $license_key == '' ) {
        return new WP_REST_Response([ 'error' => 'Missing license key' ], 400);
    }

    if ( get_option( 'wcf_addon_sl_license_key' ) !== $license_key ) {
      //  return new WP_REST_Response([ 'error' => 'Invalid license key' ], 400);
    }
    // Update locally stored license option
    update_option( 'wcf_addon_sl_license_status', $status );

    update_option( 'wcf_addon_sl_license_key', '' );
    
    if ( get_transient( WCF_ADDON_PRO_NOTICE_CACHE_KEY ) ) {
        delete_transient( WCF_ADDON_PRO_NOTICE_CACHE_KEY );
    }

    return new WP_REST_Response([ 'success' => true ], 200);
}



