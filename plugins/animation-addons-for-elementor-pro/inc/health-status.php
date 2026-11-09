<?php
namespace WCF_ADDONS;

defined( 'ABSPATH' ) || exit;

class AAE_Health_Status {

    private static $instance = null;
    public $cache_key = 'aae_store_wpjson_data';

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // 1) Register the async health check
        add_action('admin_init', [$this,'aae_remote_api_health_check']);
        add_action('admin_init', [$this,'aae_remote_api_health_check_wealcoder']); 
        // 2) Add our custom tab to the navigation
        add_filter( 'site_health_navigation_tabs', [ $this, 'add_tab' ] );
        // 3) Render the content when our tab is active
        add_action( 'site_health_tab_content', [ $this, 'tab_content' ], 10, 1 );
    }

    /**
     * 2) Add a new “Animation Addons” tab
     */
    public function add_tab( array $tabs ) : array {
        $tabs['animation_addons'] = __( 'Animation Addons', 'animation-addons-for-elementor-pro' );
        return $tabs;
    }

    /**
     * 3) When the “Animation Addons” tab is active, print our stored status array
     */
    public function tab_content( string $current_tab ) {

        if ( 'animation_addons' !== $current_tab ) {
            return;
        }

        $status       = get_option( 'aae_addon_remote_request_status', [] );
        $_stirestatus = get_option( 'aae_addon_remote_request_store_status', [] );
        $checked      = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
        
        echo '<div class="health-check-body health-check-debug-tab hide-if-no-js">';
        echo '<h2>' . esc_html__( 'Remote API Status', 'animation-addons-for-elementor-pro' ) . '</h2>';
        echo '<table class="widefat striped site-health-status">';
        echo '<tbody>';

        if ( empty( $status ) || ! is_array( $status ) ) {
            echo '<tr><td colspan="2">' . esc_html__( 'No status data available.', 'animation-addons-for-elementor-pro' ) . '</td></tr>';
        } else {
            // Loop all top‐level keys
            foreach ( $status as $key => $value ) {
                // If it’s a nested array (e.g. badge), print sub‐rows
                if ( is_array( $value ) ) {
                    echo '<tr><th colspan="2">' . esc_html( ucfirst( str_replace( '_', ' ', $key ) ) ) . '</th></tr>';
                    foreach ( $value as $sub_key => $sub_val ) {
                        echo '<tr>';
                        echo '<th style="padding-left:20px;">' . esc_html( ucfirst( str_replace( '_', ' ', $sub_key ) ) ) . '</th>';
                        echo '<td>' . esc_html( $sub_val ) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo sprintf( '<th style="color: %s;font-weight: 700;font-size: 15px;">%s</th>', $status['badge']['color'] ,wp_kses_post( ucfirst( str_replace( '_', ' ', $key ) ) ));
                    echo '<td style="color:rgb(1, 1, 8);font-size: 12px;">' . esc_html( $value ) . '</td>';
                    echo '</tr>';
                }
            }
            // Finally show when we last checked
            echo '<tr>';
            echo '<th>' . esc_html__( 'Checked at', 'animation-addons-for-elementor-pro' ) . '</th>';
            echo '<td>' . esc_html( $checked ) . '</td>';
            echo '</tr>';
        } 
        if ( empty( $_stirestatus ) || ! is_array( $_stirestatus ) ) {
            echo '<tr><td colspan="2">' . esc_html__( 'No status data available.', 'animation-addons-for-elementor-pro' ) . '</td></tr>';
        } else {
            // Loop all top‐level keys
            foreach ( $_stirestatus as $key => $value ) {
                // If it’s a nested array (e.g. badge), print sub‐rows
                if ( is_array( $value ) ) {
                    echo '<tr><th colspan="2">' . wp_kses_post( ucfirst( str_replace( '_', ' ', $key ) ) ) . '</th></tr>';
                    foreach ( $value as $sub_key => $sub_val ) {
                        echo '<tr>';
                        echo '<th style="padding-left:20px;">' . wp_kses_post( ucfirst( str_replace( '_', ' ', $sub_key ) ) ) . '</th>';
                        echo '<td>' . wp_kses_post( $sub_val ) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo sprintf( '<th style="color: %s;font-weight: 700;font-size: 15px;">%s</th>', $_stirestatus['badge']['color'] ,wp_kses_post( ucfirst( str_replace( '_', ' ', $key ) ) ));
                    echo '<td style="color:rgb(0, 0, 2);font-size: 12px;">' . wp_kses_post( $value ) . '</td>';
                    echo '</tr>';
                }
            }
            // Finally show when we last checked
            echo '<tr>';
            echo '<th>' . esc_html__( 'Checked at', 'animation-addons-for-elementor-pro' ) . '</th>';
            echo '<td>' . esc_html( $checked ) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }

    /**
     * 4) The actual health‐check callback WP will call asynchronously.
     *    It returns exactly the array structure WP expects.
     */
    public function aae_remote_api_health_check() : array {
        if ( ! is_admin() ) {
            return [];
        }
        global $pagenow;
        if ( 'site-health.php' !== $pagenow ) {
            return [];
        }

        $cache_ttl     = HOUR_IN_SECONDS;             // cache for 1 hour
    
        // 1) Try to get from cache
        $cached = get_transient( $this->cache_key );
        if ( false !== $cached ) {
            return $cached;
        }
    
        $url   = 'https://themecrowdy.com/wp-json/';
        $start = microtime( true );
        $res   = wp_remote_get( $url, [
            'timeout'         => 60,
            'connect_timeout' => 10,
        ] );
        $time  = round( microtime( true ) - $start, 2 );

        if ( is_wp_error( $res ) ) {
            $result = [
                'label'       => __( 'Remote API connectivity', 'animation-addons-for-elementor-pro' ),
                'status'      => 'critical',
                'badge'       => [
                    'label' => __( 'Connection timeout', 'animation-addons-for-elementor-pro' ),
                    'color' => 'red',
                ],
                'description' => sprintf(
                    /* translators: 1: URL, 2: seconds, 3: error */
                    __( 'Could not fetch %1$s (after %2$s s): %3$s', 'animation-addons-for-elementor-pro' ),
                    $url,
                    $time,
                    $res->get_error_message()
                ),
            ];
            update_option( 'aae_addon_remote_status', 404);
        } else {
            $code   = wp_remote_retrieve_response_code( $res );
            $status = ( 200 === $code ) ? 'good' : 'hosting outbound issue';
            $result = [
                'label'       => __( 'Remote API connectivity', 'animation-addons-for-elementor-pro' ),
                'status'      => $status,
                'badge'       => [
                    'label' => sprintf( __( 'HTTP %s', 'animation-addons-for-elementor-pro' ), $code ),
                    'color' => ( 'good' === $status ) ? '#0300ff' : 'yellow',
                ],
                'description' => sprintf(
                    /* translators: 1: URL, 2: seconds, 3: HTTP code */
                    __( 'Fetched %1$s in %2$s s with HTTP status %3$s.', 'animation-addons-for-elementor-pro' ),
                    $url,
                    $time,
                    $code
                ),
            ];
            update_option( 'aae_addon_remote_status', $code);
        }

        // Persist for later retrieval if you need to
        update_option( 'aae_addon_remote_request_status', $result );
        set_transient( $this->cache_key, $result , $cache_ttl );
        return $result;
    }

    /**
     * 4) The actual health‐check callback WP will call asynchronously.
     *    It returns exactly the array structure WP expects.
     */
    public function aae_remote_api_health_check_wealcoder() : array {
        if ( ! is_admin() ) {
            return [];
        }
        global $pagenow;
        if ( 'site-health.php' !== $pagenow ) {
            return [];
        }       
        $cache_ttl     = HOUR_IN_SECONDS;             // cache for 1 hour
    
        // 1) Try to get from cache
        $cached = get_transient( $this->cache_key );
        if ( false !== $cached ) {
            return $cached;
        }
    
        $url   = 'https://store.wealcoder.com/wp-json/';
        $start = microtime( true );
        $res   = wp_remote_get( $url, [
            'timeout'         => 60,
            'connect_timeout' => 10,
        ] );
        $time  = round( microtime( true ) - $start, 2 );

        if ( is_wp_error( $res ) ) {
            $result = [
                'label'       => '<h4>'.__( 'Remote API connectivity', 'animation-addons-for-elementor-pro' ).'</h4>',
                'status'      => 'critical',
                'badge'       => [
                    'label' => __( 'Connection timeout', 'animation-addons-for-elementor-pro' ),
                    'color' => 'red',
                ],
                'description' => sprintf(
                    /* translators: 1: URL, 2: seconds, 3: error */
                    __( 'Could not fetch %1$s (after %2$s s): %3$s', 'animation-addons-for-elementor-pro' ),
                    $url,
                    $time,
                    $res->get_error_message()
                ),
            ];
            update_option( 'aae_addon_remote_status', 404);
        } else {
            $code   = wp_remote_retrieve_response_code( $res );
            $status = ( 200 === $code ) ? 'good' : 'hosting outbound issue';
            $result = [
                'label'       => '<h4>'.__( 'Remote API connectivity', 'animation-addons-for-elementor-pro' ).'</h4>',
                'status'      => $status,
                'badge'       => [
                    'label' => sprintf( __( 'HTTP %s', 'animation-addons-for-elementor-pro' ), $code ),
                    'color' => ( 'good' === $status ) ? '#0300ff' : 'yellow',
                ],
                'description' => sprintf(
                    /* translators: 1: URL, 2: seconds, 3: HTTP code */
                    __( 'Fetched %1$s in %2$s s with HTTP status %3$s.', 'animation-addons-for-elementor-pro' ),
                    $url,
                    $time,
                    $code
                ),
            ];
            update_option( 'aae_addon_remote_status', $code);
        }

        // Persist for later retrieval if you need to
        update_option( 'aae_addon_remote_request_store_status', $result );
        set_transient( $this->cache_key, $result , $cache_ttl );
        return $result;
    }
}

// Initialize
AAE_Health_Status::instance();
