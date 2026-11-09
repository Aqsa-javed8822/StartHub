<?php

namespace WCFAddonsPro\Base\Tags;

use Elementor\Core\DynamicTags\Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_Core_Stats_Count extends Tag {

    public function get_name() {
        return 'wcf-stats-count';
    }

    public function get_title() {
        return __( 'Post Count States', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae-posts' ];
    }

    public function get_categories(): array {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY , \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY ]; // Can also use `TEXT_CATEGORY` for numeric fields.
    }

    protected function register_controls() {
        $this->add_control(
            'stats_type',
            [
                'label' => __( 'Select Statistic Type', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_stat_options(),
                'default' => 'wp_posts_count',
            ]
        );

        $this->add_control(
            'taxonomy_type',
            [
                'label' => __( 'Taxonomy Type', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_taxonomy_types(),
                'description' => __( 'Select the taxonomy to filter categories/tags.', 'animation-addons-for-elementor-pro' ),
                'condition' => [
                    'stats_type' => 'category_posts_count',
                ],
            ]
        );

        $this->add_control(
            'category_id',
            [
                'label' => __( 'Category/Term', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_taxonomy_terms(),
                'description' => __( 'Select category or term based on the taxonomy.', 'animation-addons-for-elementor-pro' ),
                'condition' => [
                    'stats_type' => 'category_posts_count',
                ],
            ]
        );


        $this->add_control(
            'post_type',
            [
                'label' => __( 'Custom Post Type (For CPT Count)', 'animation-addons-for-elementor-pro' ),
                'options' => $this->get_post_types(),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
                'description' => __( 'Provide custom post type name for CPT post count', 'animation-addons-for-elementor-pro' ),
                'condition' => [
                    'stats_type' => 'cpt_posts_count',
                ],
            ]
        );
    }
    private function get_post_types() {
        $post_types = get_post_types( [ 'public' => true ], 'objects' );
        $options = [];

        foreach ( $post_types as $post_type ) {
            $options[ $post_type->name ] = $post_type->labels->singular_name;
        }

        return $options;
    }
    private function get_taxonomy_types() {
        $taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
        $options = [];

        foreach ( $taxonomies as $taxonomy ) {
            $options[ $taxonomy->name ] = $taxonomy->label;
        }

        return $options;
    }

    private function get_taxonomy_terms() {
        $options = [];
        try{
           
            $taxonomy_type = 'category';
            $terms = get_terms( [ 'taxonomy' => $taxonomy_type , 'hide_empty' => false ] );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $options[ $term->term_id ] = $term->name;
                }
            }
        }catch(\Exception $e){}

        return $options;
    }
    public function render() {
        $stats_type = $this->get_settings( 'stats_type' );
        $taxonomy_type = $this->get_settings( 'taxonomy_type' );
        $category_id = $this->get_settings( 'category_id' );
        $post_type = $this->get_settings( 'post_type' );

        $output = '';
     
        switch ( $stats_type ) {
            case 'wp_posts_count':
                $output = wp_count_posts()->publish;
                $output = aaeaddon_format_number_count($output);
                break;
            case 'posts_share_count':
                $output = (int) get_post_meta(get_the_id(), 'aae_post_shares_count', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'aaeaddon_post_total_reactions':
                $output = (int) get_post_meta(get_the_id(), 'aaeaddon_post_total_reactions', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'aaeaddon_post_reactions_love':
                $output = (int) get_post_meta(get_the_id(), 'aaeaddon_post_reactions_love', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'aaeaddon_post_reactions_like':
                $output = (int) get_post_meta(get_the_id(), 'aaeaddon_post_reactions_like', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'aaeaddon_post_reactions_dislike':
                $output = (int) get_post_meta(get_the_id(), 'aaeaddon_post_reactions_dislike', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'aaeaddon_post_reactions_funny':
                $output = (int) get_post_meta(get_the_id(), 'aaeaddon_post_reactions_funny', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'aaeaddon_post_reactions_wow':
                $output = (int) get_post_meta(get_the_id(), 'aaeaddon_post_reactions_wow', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'aaeaddon_post_reactions_sad':
                $output = (int) get_post_meta(get_the_id(), 'aaeaddon_post_reactions_sad', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'aaeaddon_post_reactions_angry':
                $output = (int) get_post_meta(get_the_id(), 'aaeaddon_post_reactions_angry', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'review_count':
                $output = (int) get_post_meta(get_the_id(), 'review_count', true);
                $output = aaeaddon_format_number_count($output);
                break;
            case 'author_posts_count': 
                $author_id = get_current_user_id();
                if ( is_author() ) {
                    $author = get_queried_object();
                    $author_id = $author->ID;                  
                }
                $output = count_user_posts( $author_id );
                $output = aaeaddon_format_number_count($output);
                break;

            case 'last_week_posts_count':
                $output = $this->get_posts_count_by_date_range( '-7 days' );
                $output = aaeaddon_format_number_count($output);
                break;

            case 'today_posts_count':
                $output = $this->get_posts_count_by_date_range( 'today midnight' );
                $output = aaeaddon_format_number_count($output);
                break;

            case 'category_posts_count':
                if ( $taxonomy_type && $category_id ) {
                    $output = $this->get_category_post_count( $taxonomy_type, $category_id );
                } else {
                    $output = __( 'Invalid Taxonomy or Category.', 'animation-addons-for-elementor-pro' );
                }
                break;
            case 'total_comments_count':
                if ( is_singular() ) {
                    $output = get_comments_number();                   
                }else{
                    $output = wp_count_comments()->total_comments;
                }   
                $output = aaeaddon_format_number_count($output);
                break;

            case 'total_author_count':                
                $output = $this->get_author_count_with_posts();
                $output = aaeaddon_format_number_count($output);
                break;

            case 'cpt_posts_count':
                if ( $post_type ) {
                    $output = $this->get_cpt_post_count( $post_type );
                } else {
                    $output = __( 'Invalid Post Type.', 'animation-addons-for-elementor-pro' );
                }
                $output = aaeaddon_format_number_count($output);
                break;
            default:
                $output = __( 'Invalid statistic type selected.', 'animation-addons-for-elementor-pro' );
        }

        echo esc_html( $output );
    }
    private function get_stat_options() {
        return [
            'wp_posts_count' => __( 'Total WP Posts Count', 'animation-addons-for-elementor-pro' ),
            'posts_share_count' => __( 'Total Share Count', 'animation-addons-for-elementor-pro' ),  
            'review_count' => __( 'Total Review Count', 'animation-addons-for-elementor-pro' ),                   
            'author_posts_count' => __( 'Author Posts Count', 'animation-addons-for-elementor-pro' ),
            'last_week_posts_count' => __( 'Last Week Publish Count', 'animation-addons-for-elementor-pro' ),
            'today_posts_count' => __( 'Today Publish Count', 'animation-addons-for-elementor-pro' ),
            'category_posts_count' => __( 'Category-wise Publish Count', 'animation-addons-for-elementor-pro' ),
            'total_comments_count' => __( 'Total Comments Count', 'animation-addons-for-elementor-pro' ),
            'total_author_count' => __( 'Total Authors with Posts', 'animation-addons-for-elementor-pro' ),
            'cpt_posts_count' => __( 'Total Custom Post Type Posts Count', 'animation-addons-for-elementor-pro' ),
            'aaeaddon_post_total_reactions' => __( 'Total Reaction Count', 'animation-addons-for-elementor-pro' ),           
            'aaeaddon_post_reactions_love' => __( 'Love Reaction Count', 'animation-addons-for-elementor-pro' ),
            'aaeaddon_post_reactions_like' => __( 'Like reaction Count', 'animation-addons-for-elementor-pro' ),
            'aaeaddon_post_reactions_dislike	' => __( 'Dislike Reaction Count', 'animation-addons-for-elementor-pro' ),
            'aaeaddon_post_reactions_funny' => __( 'Funny Reaction Count', 'animation-addons-for-elementor-pro' ),
            'aaeaddon_post_reactions_wow	' => __( 'Wow Reaction Count', 'animation-addons-for-elementor-pro' ),
            'aaeaddon_post_reactions_sad	' => __( 'Sad Reaction Count', 'animation-addons-for-elementor-pro' ),
            'aaeaddon_post_reactions_angry	' => __( 'Angry Reaction Count', 'animation-addons-for-elementor-pro' ),
        ];
    }

    private function get_posts_count_by_date_range( $date_start ) {
        $query = new \WP_Query( [
            'date_query' => [
                [
                    'after' => $date_start,
                    'inclusive' => true,
                ],
            ],
            'post_type' => 'post',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'no_found_rows' => true, 
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
        ] );

        return $query->found_posts;
    }

    private function get_category_post_count( $taxonomy, $term_id ) {
        $query = new \WP_Query( [
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $term_id,
                ],
            ],
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true, 
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
        ] );

        return $query->found_posts;
    }
    private function get_author_count_with_posts() {
        global $wpdb;

        $result = $wpdb->get_var( "
            SELECT COUNT(DISTINCT post_author) 
            FROM $wpdb->posts 
            WHERE post_status = 'publish' 
            AND post_type = 'post'
        " );

        return intval( $result );
    }

    private function get_cpt_post_count( $post_type ) {
        $query = new \WP_Query( [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true, 
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
        ] );

        return $query->found_posts;
    }
}
