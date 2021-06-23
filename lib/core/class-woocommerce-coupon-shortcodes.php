<?php
/**
 * class-woocommerce-coupon-shortcodes.php
 *
 * Copyright (c) "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package woocommerce-coupon-shortcodes
 * @since woocommerce-coupon-shortcodes 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class (boots the plugin conditionally).
 */
class WooCommerce_Coupon_Shortcodes {

	/**
	 * @since 1.21.0
	 *
	 * @var int
	 */
	const HARD_LIMIT = 1000;

	private static $admin_messages = array();

	/**
	 * Put hooks in place and activate.
	 */
	public static function init() {
		//register_activation_hook( WOO_CODES_FILE, array( __CLASS__, 'activate' ) );
		//register_deactivation_hook( WOO_CODES_FILE, array( __CLASS__, 'deactivate' ) );
		//register_uninstall_hook( WOO_CODES_FILE, array( __CLASS__, 'uninstall' ) );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
		add_action( 'init', array( __CLASS__, 'wp_init' ) );
	}
	
	/**
	 * Loads translations and shortcode handler.
	 */
	public static function wp_init() {
		load_plugin_textdomain( WOO_CODES_PLUGIN_DOMAIN, null, 'woocommerce-coupon-shortcodes/languages' );
		if ( self::check_dependencies() ) {
			require_once( WOO_CODES_VIEWS_LIB . '/class-woocommerce-coupon-shortcodes-views.php' );
			// notice
			if ( is_admin() ) {
				if ( current_user_can( 'activate_plugins' ) ) { // important: after init hook
					require_once WOO_CODES_ADMIN_LIB . '/class-woocommerce-coupon-shortcodes-admin-notice.php';
				}
				require_once WOO_CODES_ADMIN_LIB . '/class-woocommerce-coupon-shortcodes-admin-coupon.php';
			}
		}
	}

	/**
	 * Activation hook.
	 * 
	 * @param boolean $network_wide
	 */
	public static function activate( $network_wide = false ) {
	}

	/**
	 * Deactivation hook.
	 * 
	 * @param boolean $network_wide
	 */
	public static function deactivate( $network_wide = false ) {
	}

	/**
	 * Uninstall hook.
	 */
	public static function uninstall() {
	}

	/**
	 * Prints admin notices.
	 */
	public static function admin_notices() {
		if ( !empty( self::$admin_messages ) ) {
			foreach ( self::$admin_messages as $msg ) {
				echo $msg;
			}
		}
	}

	/**
	 * Check plugin dependencies (WooCommerce), nag if missing.
	 * @param boolean $disable disable the plugin if true, defaults to false
	 */
	public static function check_dependencies( $disable = false ) {
		$result = true;
		$active_plugins = get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_sitewide_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$active_sitewide_plugins = array_keys( $active_sitewide_plugins );
			$active_plugins = array_merge( $active_plugins, $active_sitewide_plugins );
		}
		$woocommerce_is_active = in_array( 'woocommerce/woocommerce.php', $active_plugins );
		if ( !$woocommerce_is_active ) {
			self::$admin_messages[] = "<div class='error'>" . __( '<em>WooCommerce Coupon Shortcodes</em> needs the <a href="https://woocommerce.com" target="_blank">WooCommerce</a> plugin. Please install and activate it.', WOO_CODES_PLUGIN_DOMAIN ) . "</div>";
		}
		if ( !$woocommerce_is_active ) {
			if ( $disable ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				deactivate_plugins( array( __FILE__ ) );
			}
			$result = false;
		}
		return $result;
	}

	/**
	 * The maximum number of coupon codes to handle.
	 *
	 * Introduced to avoid performance issues with queries on sites that have very large numbers of coupon codes.
	 *
	 * This is specifically important with [coupon_enumerate code="*"], as all published coupon codes would be processed and would lead to overuse of database and server resources while processing them.
	 *
	 * @since 1.21.0
	 *
	 * @return int
	 */
	public static function get_hard_limit() {
		$n = self::HARD_LIMIT;
		if ( is_numeric( WOOCOMMERCE_COUPON_SHORTCODES_HARD_LIMIT ) ) {
			$n = max( 1, intval( WOOCOMMERCE_COUPON_SHORTCODES_HARD_LIMIT ) );
		}
		return $n;
	}
}
WooCommerce_Coupon_Shortcodes::init();
