<?php
/**
 * woocommerce-coupon-shortcodes.php
 *
 * Copyright (c) 2013-2024 "kento" Karim Rahimpur www.itthinx.com
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
 * Plugin URI: https://www.itthinx.com/plugins/woocommerce-coupon-shortcodes
 * Description: Show coupon discount info using shortcodes. Allows to render coupon information and content conditionally.
 * Version: 2.7.0
 * Author: itthinx
 * Author URI: https://www.itthinx.com
 * Donate-Link: https://www.itthinx.com/shop/
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * WC requires at least: 8.2
 * WC tested up to: 9.4
 * Woo: 244762:d9f372bcea062d4a9eedccb2a80eb49d
 * License: GPLv3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WOO_CODES_PLUGIN_VERSION', '2.7.0' );
define( 'WOO_CODES_PLUGIN_DOMAIN', 'woocommerce-coupon-shortcodes' );
define( 'WOO_CODES_FILE', __FILE__ );
define( 'WOO_CODES_LOG', false );
define( 'WOO_CODES_CORE_DIR', WP_PLUGIN_DIR . '/woocommerce-coupon-shortcodes' );
define( 'WOO_CODES_CORE_LIB', WOO_CODES_CORE_DIR . '/lib/core' );
define( 'WOO_CODES_ADMIN_LIB', WOO_CODES_CORE_DIR . '/lib/admin' );
define( 'WOO_CODES_VIEWS_LIB', WOO_CODES_CORE_DIR . '/lib/views' );
define( 'WOO_CODES_PLUGIN_URL', WP_PLUGIN_URL . '/woocommerce-coupon-shortcodes' );

// @since 1.21.0
if ( !defined( 'WOOCOMMERCE_COUPON_SHORTCODES_HARD_LIMIT' ) ) {
	define( 'WOOCOMMERCE_COUPON_SHORTCODES_HARD_LIMIT', 1000 );
}

require_once WOO_CODES_CORE_LIB . '/class-woocommerce-coupon-shortcodes.php';
