<?php
/**
 * woocommerce-coupon-shortcodes.php
 *
 * Copyright (c) 2013-2018 "kento" Karim Rahimpur www.itthinx.com
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
 *
 * Plugin Name: WooCommerce Coupon Shortcodes
 * Plugin URI: http://www.itthinx.com/plugins/woocommerce-coupon-shortcodes
 * Description: Provides conditional shortcodes [coupon_is_valid], [coupon_is_not_valid], [coupon_is_applied] and [coupon_is_not_applied] to enclose content and [coupon_code], [coupon_description], [coupon_discount] to render coupon information. <a href="http://docs.itthinx.com/document/woocommerce-coupon-shortcodes/">Documentation</a> | <a href="http://www.itthinx.com/plugins/woocommerce-coupon-shortcodes/">Plugin page</a>
 * Version: 1.6.2
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 * WC requires at least: 2.6
 * WC tested up to: 3.4
 * Woo: 244762:d9f372bcea062d4a9eedccb2a80eb49d
 * License: GPLv3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WOO_CODES_PLUGIN_VERSION', '1.6.2' );
define( 'WOO_CODES_PLUGIN_DOMAIN', 'woocommerce-coupon-shortcodes' );
define( 'WOO_CODES_FILE', __FILE__ );
define( 'WOO_CODES_LOG', false );
define( 'WOO_CODES_CORE_DIR', WP_PLUGIN_DIR . '/woocommerce-coupon-shortcodes' );
define( 'WOO_CODES_CORE_LIB', WOO_CODES_CORE_DIR . '/lib/core' );
define( 'WOO_CODES_ADMIN_LIB', WOO_CODES_CORE_DIR . '/lib/admin' );
define( 'WOO_CODES_VIEWS_LIB', WOO_CODES_CORE_DIR . '/lib/views' );
define( 'WOO_CODES_PLUGIN_URL', WP_PLUGIN_URL . '/woocommerce-coupon-shortcodes' );

require_once( WOO_CODES_CORE_LIB . '/class-woocommerce-coupon-shortcodes.php');
