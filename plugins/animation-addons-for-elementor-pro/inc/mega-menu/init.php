<?php

namespace WCFAddonsPros\Megamenu;

defined( 'ABSPATH' ) || exit;

class WCFMegaMenu {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
	
		if ( is_admin() ) {
			// If the user can manage options, let the fun begin!
			if ( current_user_can( 'manage_options' ) ) {
				add_action( 'admin_init', [ $this, 'register_nav_meta_box' ], 10 );
			}
		}

		// Ajax Callback
		add_action( 'wp_ajax_wcf_mega_menu_ajax_requests', [ $this, 'ajax_requests' ] );

		// Admin Scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

		add_action( 'admin_footer', [ $this, 'mega_menu_templates' ] );
	}

    public function get_elementor_save_template() {
		$templates = [];
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$templates = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
		}
		if ( empty( $templates ) ) {
			$template_lists = [ '0' => __( 'Do not Saved Templates.', 'animation-addons-for-elementor-pro' ) ];
		} else {
			$template_lists = [ '0' => __( 'Select Template', 'animation-addons-for-elementor-pro' ) ];
			foreach ( $templates as $template ) {
				$template_lists[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
		}

		return $template_lists;
	}

	// Meta Box Field render
	public function register_nav_meta_box() {
		global $pagenow;
		if ( 'nav-menus.php' == $pagenow ) {
			add_meta_box(
				'wcf_mega_menu_meta_box',
				__( 'Mega menu Settings', 'animation-addons-for-elementor-pro' ),
				array( $this, 'metabox_contents' ),
				'nav-menus',
				'side',
				'core'
			);
		}
	}

	public function metabox_contents() {
		// Get recently edited nav menu.
		$recently_edited      = absint( get_user_option( 'nav_menu_recently_edited' ) );
		$nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? absint( $_REQUEST['menu'] ) : 0;
		if ( empty( $recently_edited ) && is_nav_menu( $nav_menu_selected_id ) ) {
			$recently_edited = $nav_menu_selected_id;
		}

		// Use $recently_edited if none are selected.
		if ( empty( $nav_menu_selected_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) ) {
			$nav_menu_selected_id = $recently_edited;
		}

		$options = get_option( "wcf_menu_options_" . $nav_menu_selected_id );
		?>
        <div id="wcf-megamenu-menu-metabox">
            <input type="hidden" value="<?php echo esc_attr( $nav_menu_selected_id ); ?>" id="wcf-megamenu-metabox-input-menu-id"/>
            <p>
                <label><strong><?php esc_html_e( "Enable megamenu?", 'animation-addons-for-elementor-pro' ); ?></strong></label>
                <input type="checkbox" class="alignright pull-right-input" id="wcf-megamenu-menu-metabox-input-is-enabled" <?php echo isset( $options['enable_menu'] ) && $options['enable_menu'] == 'on' ? 'checked="checked"' : '' ?>>
            </p>
            <p>
				<?php echo get_submit_button( esc_html__( 'Save', 'animation-addons-for-elementor-pro' ), 'wcf-mega-menu-settings-save button-primary alignright', '', false ); ?>
                <span class='spinner'></span>
            </p>

        </div>
		<?php
	}

	public function ajax_requests() {

		$action = isset( $_POST['sub_action'] ) ? $_POST['sub_action'] : '';

		if ( $action === 'save_menu_settings' ) {

			if ( ! check_ajax_referer( 'wcf_mega_menu_nonce', 'nonce' ) ) {
				wp_send_json_error();
			}

			$form_data = ( ! empty( $_POST['settings'] ) ? sanitize_text_field( $_POST['settings'] ) : '' );

			if ( ! empty( $form_data ) ) {
				parse_str( $form_data, $data );
			} else {
				return;
			}

			$menu_item_id = absint( $_POST['menu_item_id'] );

			update_post_meta( $menu_item_id, 'wcf_mega_menu_settings', $data );

			wp_send_json_success( [
				'message' => esc_html__( 'Successfully data saved', 'animation-addons-for-elementor-pro' )
			] );

		}

		else if( $action === 'save_menu_options' ) {

			if ( ! check_ajax_referer( 'wcf_mega_menu_nonce', 'nonce' ) ) {
				wp_send_json_error();
			}

			$settings = isset( $_POST['settings'] ) ? $_POST['settings'] : array();
			$menu_id  = absint( $_POST['menu_id'] );
			update_option( 'wcf_menu_options_' . $menu_id, $settings );
			wp_die();
		} else {
			$menu_item_id = absint( $_REQUEST['menu_item_id'] );

			$menu_data = !empty( get_post_meta( $menu_item_id, 'wcf_mega_menu_settings', true ) ) ? get_post_meta( $menu_item_id, 'wcf_mega_menu_settings', true ) : '';

			if ( empty( $menu_data ) ) {
				$menu_data = [
					'menu-item-template'        => '',
					'mobile-submenu-type'       => 'builder',
					'menu-item-badgetext'       => '',
					'menu-item-badgecolor'      => '000000',
					'menu-item-badgebgcolor'    => 'EA2958',
					'menu-item-width-type'      => 'default_width',
					'menu-item-menucustomwidth' => '',
					'menu-item-position-type'   => 'relative',
				];
			}

			wp_send_json_success( array(
				'content'   => $menu_data,
			) );
		}
	}

	public function admin_scripts() {
		wp_enqueue_style( 'wcf-mega-menu-admin', WCF_ADDONS_PRO_URL . 'assets/css/mega-menu-admin.css' );
		wp_enqueue_script( 'wcf-mega-menu-admin', WCF_ADDONS_PRO_URL . 'assets/js/mega-menu-admin.js', array(
			'jquery',
			'wp-util'
		), WCF_ADDONS_PRO_VERSION, true );
		
		wp_enqueue_script( 'wcf-addons-pro-admin', WCF_ADDONS_PRO_URL . 'assets/js/admin.js', array(
			'jquery',
			'wp-util'
		), WCF_ADDONS_PRO_VERSION, true );

		wp_localize_script(
			'wcf-addons-pro-admin',
			'AAE_ADMIN',
			[
				'nonce'        => wp_create_nonce( 'wcf_admin_nonce' ),
				'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
				'aae_pro_ping' => get_transient('aae_pro_ping_cooldown') ? true : false,
				'home_url'     => home_url()
			]
		);

		wp_localize_script(
			'wcf-mega-menu-admin',
			'WCFMEGAMENU',
			[
				'nonce'     => wp_create_nonce( 'wcf_mega_menu_nonce' ),
				'templates' => $this->get_elementor_save_template(),
				'button'    => [
					'text'        => esc_html__( 'Save', 'animation-addons-for-elementor-pro' ),
					'lodingtext'  => esc_html__( 'Saving…', 'animation-addons-for-elementor-pro' ),
					'successtext' => esc_html__( 'All Data Saved', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		if(!get_transient('aae_pro_ping_cooldown'))
		{
			set_transient('aae_pro_ping_cooldown', 1, 20 * MINUTE_IN_SECONDS);			
		}
		
	}

	public function mega_menu_templates() {
		?>
        <script type="text/template" id="tmpl-wcf-mega-menu-button">
            <span class="wcf-mega-menu-trigger" data-item-id="{{ data.id }}" data-item-depth="{{ data.depth }}">
                <span class="dashicons dashicons-admin-generic"></span>
                {{{ data.label }}}
            </span>
        </script>
        <script type="text/template" id="tmpl-wcf-mega-menu-popup">
            <div class="wcf-mega-menu-popup" id="wcf-mega-popup-{{ data.id }}" data-id="{{ data.id }}" data-depth="{{ data.depth }}">
                <span class="wcf-mega-menu-popup-close"></span>

                <div class="wcf-mega-menu-popup-content">

                    <span class="wcf-mega-menu-popup-close-btn">&#10005;</span>

                    <form class="wcf-mega-menu-data" id="wcf-mega-menu-form-{{ data.id }}">

                        <!-- Tab Menu Area Start -->
                        <ul class="mega-menu-popup-tab-menu">
                            <# if( data.depth === 0 ){ #>
                            <li class="mega-menu-popup-tab-list-item">
                                <a class="active" href="javascript:void();" data-target="mega-menu-popup-tab-content"><?php esc_html_e( 'Content', 'animation-addons-for-elementor-pro' ); ?></a>
                            </li>
                            <li class="mega-menu-popup-tab-list-item">
                                <a href="javascript:void();" data-target="mega-menu-popup-tab-badge"><?php esc_html_e( 'Badge', 'animation-addons-for-elementor-pro' ); ?></a>
                            </li>
                            <li class="mega-menu-popup-tab-list-item">
                                <a href="javascript:void();" data-target="mega-menu-popup-tab-settings"><?php esc_html_e('Settings','animation-addons-for-elementor-pro'); ?></a>
                            </li>
                            <# } #>
                        </ul>
                        <!-- Tab Menu Area End -->

                        <!-- Tab Menu Content Area Start -->
                        <div class="mega-menu-popup-tab-content">
                            <# if( data.depth === 0 ){ #>

                            <!-- Content Tab Field Area Start -->
                            <div class="mega-menu-popup-tab-pane active" data-id="mega-menu-popup-tab-content">
                                <ul>
                                    <li>
                                        <label for="menu-item-template"><?php esc_html_e('Menu Template','animation-addons-for-elementor-pro');?></label>
                                        <select id="menu-item-template" class="mega-menu-popup-input" name="menu-item-template">
                                            <#
                                            _.each( data.templatelist, function( tilte, key ) {

                                            menu_template = data.content['menu-item-template'];

                                            if( key === menu_template ){
                                            #><option value="{{ key }}" selected>{{{ tilte }}}</option><#
                                            }else{
                                            #><option value="{{ key }}">{{{ tilte }}}</option><#
                                            }

                                            } );
                                            #>
                                        </select>
                                    </li>
                                    <li>
                                        <label for="menu-item-mobilesubmenu"><?php esc_html_e('Use Mobile SubMenu','animation-addons-for-elementor-pro'); ?></label>
                                        <div class="alignright mobile_submenu_lists">
                                            <input type="radio" name="mobile-submenu-type" class="mega-menu-popup-input" id="mobile_submenu_type_builder" value="builder" data-checked="{{ 'builder' === data.content['mobile-submenu-type'] }}">
                                            <label for="mobile_submenu_type_builder">Builder Content</label>
                                            <input type="radio" class="mega-menu-popup-input" id="mobile_submenu_type_wp" name="mobile-submenu-type" value="wp_submenu_list" data-checked="{{ 'wp_submenu_list' === data.content['mobile-submenu-type'] }}">
                                            <label for="mobile_submenu_type_wp">WP submenu list</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- Content Tab Field Area End -->

                            <!-- badge Tab Field Area Start -->
                            <div class="mega-menu-popup-tab-pane" data-id="mega-menu-popup-tab-badge">
                                <ul>
                                    <li>
                                        <label for="menu-item-badgetext"><?php esc_html_e('Badge Text','animation-addons-for-elementor-pro'); ?></label>
                                        <input type="text" id="menu-item-badgetext" name="menu-item-badgetext" class="mega-menu-popup-input" value="{{ data.content['menu-item-badgetext'] }}">
                                    </li>
                                    <li>
                                        <label for="menu-item-badgecolor"><?php esc_html_e('Text Color','animation-addons-for-elementor-pro'); ?></label>
                                        <input type="color" id="menu-item-badgecolor" name="menu-item-badgecolor" class="mega-menu-popup-input" value="#{{ data.content['menu-item-badgecolor'] }}">
                                    </li>
                                    <li>
                                        <label for="menu-item-badgebgcolor"><?php esc_html_e('Background Color','animation-addons-for-elementor-pro'); ?></label>
                                        <input type="color" id="menu-item-badgebgcolor" name="menu-item-badgebgcolor" class="mega-menu-popup-input" value="#{{ data.content['menu-item-badgebgcolor'] }}">
                                    </li>
                                </ul>
                            </div>
                            <!-- Content Tab Field Area End -->

                            <!-- Settings Tab Field Area Start -->
                            <div class="mega-menu-popup-tab-pane" data-id="mega-menu-popup-tab-settings">
                                <ul>
                                    <li>
                                        <label for="menu-item-menuwidth"><?php esc_html_e('Menu Width','animation-addons-for-elementor-pro'); ?></label>
                                        <div class="alignright width_lists">
                                            <input type="radio" name="menu-item-width-type" class="mega-menu-popup-input" id="width_type_default" value="default_width"  data-checked="{{ 'default_width' === data.content['menu-item-width-type'] }}">
                                            <label for="width_type_default">Default Width</label>
                                            <input type="radio" class="mega-menu-popup-input" id="width_type_full" name="menu-item-width-type" value="full_width" data-checked="{{ 'full_width' === data.content['menu-item-width-type'] }}">
                                            <label for="width_type_full">Full Width</label>
                                            <input type="radio" class="mega-menu-popup-input" id="width_type_custom" name="menu-item-width-type" value="custom_width" data-checked="{{ 'custom_width' === data.content['menu-item-width-type'] }}">
                                            <label for="width_type_custom">Custom Width</label>
                                        </div>
                                    </li>
                                    <li class="custom-width-row">
                                        <label for="menu-item-menucustomwidth"><?php esc_html_e('Width','animation-addons-for-elementor-pro'); ?></label>
                                        <input type="text"  id="menu-item-menucustomwidth" name="menu-item-menucustomwidth" class="mega-menu-popup-input" placeholder="750px" value="{{ data.content['menu-item-menucustomwidth'] }}">
                                    </li>
                                    <li>
                                        <label for="menu-item-menuposition"><?php esc_html_e('SubMenu Position','animation-addons-for-elementor-pro'); ?></label>
                                        <div class="alignright position_lists">
                                            <input type="radio" name="menu-item-position-type" class="mega-menu-popup-input" id="position_type_default" value="static" data-checked="{{ 'static' === data.content['menu-item-position-type'] }}">
                                            <label for="position_type_default">Default</label>
                                            <input type="radio" class="mega-menu-popup-input" id="position_type_relative" name="menu-item-position-type" value="relative" data-checked="{{ 'relative' === data.content['menu-item-position-type'] }}">
                                            <label for="position_type_relative">Relative</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- Settings Tab Field Area End -->

                            <# } #>

                            <div class="mega-menu-save-btn-area">
                                <button data-id="{{ data.id }}" class="wcf-mega-menu-submit-btn button button-primary disabled" type="submit" disabled="disabled"><?php esc_html_e( 'All Data Saved', 'animation-addons-for-elementor-pro' ); ?></button>
                            </div>

                        </div>
                        <!-- Tab Menu Content Area End -->

                    </form>

                </div>
            </div>
        </script>
		<?php
	}
}

WCFMegaMenu::instance();
