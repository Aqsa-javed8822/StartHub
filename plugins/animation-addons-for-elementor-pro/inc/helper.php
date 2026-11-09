<?php

declare( strict_types=1 );

use Elementor\Plugin;

if ( ! function_exists( 'wcf_addon_elementor_get_setting' ) ) {
	function wcf_addon_elementor_get_setting( $setting_id ) {
		global $wcfaddon_elementor_settings;
		$return = '';
		if ( ! isset( $hello_elementor_settings['kit_settings'] ) ) {
			$kit                                      = \Elementor\Plugin::$instance->kits_manager->get_active_kit();
			$hello_elementor_settings['kit_settings'] = $kit->get_settings();
		}
		if ( isset( $hello_elementor_settings['kit_settings'][ $setting_id ] ) ) {
			$return = $hello_elementor_settings['kit_settings'][ $setting_id ];
		}

		return apply_filters( 'wcfaddons_elementor_' . $setting_id, $return );
	}
}

/**
 * Is this request "AJAX-like"?
 * - True for admin-ajax.php (wp_doing_ajax)
 * - True for REST requests (wp_is_json_request / REST_REQUEST)
 * - True if sent with X-Requested-With: XMLHttpRequest
 * - Optional: allow custom query flag ?_ajax=1
 */
function aae_addon_is_ajax_like_request(): bool {
	// Native WP AJAX.
	if ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) {
		return true;
	}

	// REST requests.
	if ( ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) || defined( 'REST_REQUEST' ) ) {
		return true;
	}

	// XHR header, e.g. fetch/XMLHttpRequest set by JS.
	if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
		return true;
	}

	// Optional explicit flag you control from the frontend.
	if ( isset( $_REQUEST['_ajax'] ) && $_REQUEST['_ajax'] ) {
		return true;
	}

	return false;
}

/**
 * AAE_Dumper class.
 *
 * A robust utility for dumping variable representations. Handles recursion, objects,
 * and arrays with depth control.
 */
class AAE_Dumper {
	/** @var array Holds references to processed objects to prevent infinite loops */
	private static array $_objects = [];

	/** @var string Holds the formatted output */
	private static string $_output = '';

	/** @var int Maximum recursion depth */
	private static int $_depth = 10;

	/**
	 * Converts a variable into a string representation.
	 *
	 * @param mixed $var Variable to dump.
	 * @param int $depth Maximum depth for recursion. Default is 10.
	 *
	 * @return string The string representation of the variable.
	 */
	public static function dump( mixed $var, int $depth = 10 ): string {
		self::resetInternals();
		self::$_depth = $depth;
		self::dumpInternal( $var, 0 );

		$output = self::$_output;

		self::resetInternals(); // Clean up after the operation

		return $output;
	}

	/**
	 * Resets internal static variables.
	 */
	private static function resetInternals(): void {
		self::$_output  = '';
		self::$_objects = [];
		self::$_depth   = 10;
	}

	/**
	 * Recursive internal method for processing variables.
	 *
	 * @param mixed $var The variable to process.
	 * @param int $level Current recursion level.
	 */
	private static function dumpInternal( mixed $var, int $level ): void {
		$spaces = str_repeat( ' ', $level * 4 );

		switch ( gettype( $var ) ) {
			case 'boolean':
				self::$_output .= $var ? 'true' : 'false';
				break;

			case 'integer':
			case 'double':
				self::$_output .= (string) $var;
				break;

			case 'string':
				self::$_output .= "'$var'";
				break;

			case 'resource':
				self::$_output .= '{resource}';
				break;

			case 'NULL':
				self::$_output .= 'null';
				break;

			case 'array':
				if ( self::$_depth <= $level ) {
					self::$_output .= 'array(...)';
				} elseif ( empty( $var ) ) {
					self::$_output .= 'array()';
				} else {
					self::$_output .= "array\n" . $spaces . '(';
					foreach ( $var as $key => $value ) {
						self::$_output .= "\n" . $spaces . "    [$key] => ";
						self::dumpInternal( $value, $level + 1 );
					}
					self::$_output .= "\n" . $spaces . ')';
				}
				break;

			case 'object':
				if ( ( $id = array_search( $var, self::$_objects, true ) ) !== false ) {
					self::$_output .= get_class( $var ) . '(...)';
				} elseif ( self::$_depth <= $level ) {
					self::$_output .= get_class( $var ) . '(...)';
				} else {
					self::$_objects[] = $var;
					$className        = get_class( $var );
					$members          = (array) $var;
					self::$_output    .= "$className\n" . $spaces . '(';
					foreach ( $members as $key => $value ) {
						$keyDisplay    = strtr( trim( (string) $key ), [ "\0" => ':' ] );
						self::$_output .= "\n" . $spaces . "    [$keyDisplay] => ";
						self::dumpInternal( $value, $level + 1 );
					}
					self::$_output .= "\n" . $spaces . ')';
				}
				break;

			default:
				self::$_output .= '{unknown}';
		}
	}
}

if ( ! function_exists( 'wcf__addons__pro__status' ) ) {
	function wcf__addons__pro__status() {

		static $cachedStatus = null;
		if ( $cachedStatus !== null ) {
			return $cachedStatus;
		}

		$prefix = 'wcf_addon_sl';

		global $wpdb;
		// Use fixed, sanitized option name directly
		$option_name = $prefix.'_license_status';
		// Securely get the option value from DB
		$raw_status = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
				$option_name
			)
		);

		// Sanitize & apply filter
		$status = apply_filters( 'wcf__addons__pro__status', sanitize_text_field( $raw_status ) );
		// Strict check for "valid" only
		$cachedStatus = ( $status === WCF_ADDON_PRO_STS);
		return $cachedStatus;
	}
}


if ( ! function_exists( 'aae_print' ) ) {
	/**
	 * Enhanced debugging function to display data in a styled format.
	 *
	 * @param mixed $value The data to print (array, object, string, etc.).
	 */
	function aae_print( $value ) {
		static $first_time = true;

		// Load the styling once
		if ( $first_time ) {
			echo '<style type="text/css">
                div.aae_print_r {
                    max-height: 500px;
                    overflow-y: scroll;
                    background: #23282d;
                    margin: 10px 30px;
                    padding: 0;
                    border: 1px solid #F5F5F5;
                    border-radius: 3px;
                    position: relative;
                    z-index: 11111;
                }
                div.aae_print_r pre {
                    color: #78FF5B;
                    background: #23282d;
                    text-shadow: 1px 1px 0 #000;
                    font-family: Consolas, monospace;
                    font-size: 12px;
                    margin: 0;
                    padding: 5px;
                    display: block;
                    line-height: 16px;
                    text-align: left;
                }
                div.aae_print_r_group {
                    background: #f1f1f1;
                    margin: 10px 30px;
                    padding: 1px;
                    border-radius: 5px;
                    position: relative;
                    z-index: 11110;
                }
                div.aae_print_r_group div.aae_print_r {
                    margin: 9px;
                    border-width: 0;
                }
                </style>';
			$first_time = false;
		}

		// Handle multiple arguments
		if ( func_num_args() > 1 ) {
			echo '<div class="aae_print_r_group">';
			foreach ( func_get_args() as $param ) {
				aae_print( $param );
			}
			echo '</div>';
		} else {
			echo '<div class="aae_print_r"><pre>';
			echo htmlspecialchars( print_r( $value, true ) );
			echo '</pre></div>';
		}
	}
}

/**
 * Alias for `aae_print`
 *
 * @see aae_print()
 */
if ( ! function_exists( 'debug' ) ) {
	function debug() {
		call_user_func_array( 'aae_print', func_get_args() );
	}
}

if ( ! function_exists( 'aae_validate_content_json' ) ) {
	function aae_validate_content_json( $input ) {
		// Check if the input is a valid string and not empty
		if ( ! is_string( $input ) || empty( $input ) ) {
			return false;  // Invalid input
		}

		// Attempt to decode the JSON
		$decoded = json_decode( $input, true );

		// Check for JSON decoding errors
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return false;  // Invalid JSON
		}

		// Return the decoded JSON if valid, otherwise false
		return $decoded;
	}
}


function free_import_elementor_kit_from_uploads( $kit_filename ) {
	// Get the WordPress uploads directory.
	$upload_dir = wp_upload_dir();
	$file_path  = trailingslashit( $upload_dir['basedir'] ) . $kit_filename;

	// Verify that the file exists.
	if ( ! file_exists( $file_path ) ) {
		return new WP_Error( 'file_not_found', 'The kit file was not found in the uploads directory.' );
	}

	// Ensure Elementor is active.
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return new WP_Error( 'elementor_inactive', 'The Elementor plugin is not active.' );
	}

	// Read the contents of the file.
	$file_content = file_get_contents( $file_path );

	// Base64 encode the file content.
	$encoded_file = base64_encode( $file_content );

	// Prepare the data for import.
	$data = [
		'fileData' => $encoded_file,
		'fileName' => basename( $file_path ),
	];

	// Check if the kits manager import method exists.
	if ( ! method_exists( \Elementor\Plugin::instance()->kits_manager, 'import_kit' ) ) {
		return new WP_Error( 'import_method_missing', 'The kit import method is not available. Please ensure you have the correct version of Elementor.' );
	}

	// Import the kit using Elementor's kits manager.
	$result = \Elementor\Plugin::instance()->kits_manager->import_kit( $data );

	// Check for errors during the import.
	if ( is_wp_error( $result ) ) {
		return $result;
	}

	// Return the import details (kit ID and related data).
	return $result;
}


if ( ! function_exists( 'aae__addons__pro__error_status' ) ) {
	function aae__addons__pro__error_status() {
		static $cachedStatus = null;
        $prefix = 'wcf_addon_sl';
		if ( $cachedStatus !== null ) {
			return $cachedStatus;
		}

		global $wpdb;

		// Safely get option value directly from DB using prepared statement
		$option_name = $prefix.'_license_status';

		$raw_status = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1",
				$option_name
			)
		);

		// Sanitize and filter the status
		$status = apply_filters( 'aae__addons__pro__error_status', sanitize_text_field( $raw_status ) );
		
		// Strict check
		$cachedStatus = ( $status === WCF_ADDON_PRO_STS );

		return $cachedStatus;
	}
}


if ( ! function_exists( 'info_render_elementor_icons' ) ) {

	function info_render_elementor_icons( $content = array(), $class = '' ) {

		if ( ! is_array( $content ) ) {
			return false;
		}

		if ( is_array( $content['value'] ) ) {
			$svg_icon = $content['value']['url'];
		} else {
			$font_icon = $content['value'];
		}

		if ( ! is_array( $content['value'] ) && $font_icon ) {
			if ( $class ) {
				return '<i class="' . $class . ' ' . esc_attr( $font_icon ) . '"></i>';
			} else {
				return '<i class="' . esc_attr( $font_icon ) . '"></i>';
			}
		}

		if ( $content['library'] == 'svg' && isset( $content['value']['id'] ) ) {
			return Svg::get_inline_svg( $content['value']['id'] );
		}
	}
}


    
      
    
