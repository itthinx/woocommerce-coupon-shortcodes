<?php
/**
 * class-woocommerce-coupon-shortcodes-admin-coupon.php
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
 * @since woocommerce-coupon-shortcodes 1.11.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the Shortcodes tab to coupons.
 */
class WooCommerce_Coupon_Shortcodes_Admin_Coupon {

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_filter( 'woocommerce_coupon_data_tabs', array( __CLASS__, 'woocommerce_coupon_data_tabs' ) );
		add_action( 'woocommerce_coupon_data_panels', array( __CLASS__, 'woocommerce_coupon_data_panels' ) );
	}

	/**
	 * Data panel actions.
	 */
	public static function wp_init() {
		
	}

	/**
	 * Adds the Shortcodes tab.
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	public static function woocommerce_coupon_data_tabs( $tabs ) {
		$tabs['groups'] = array(
			'label'  => __( 'Shortcodes', 'woocommerce-coupon-shortcodes' ),
			'target' => 'custom_coupon_shortcodes',
			'class'  => 'coupon-shortcodes'
		); 
		return $tabs;
	}

	/**
	 * Renders group options.
	 */
	public static function woocommerce_coupon_data_panels() {

		global $wpdb, $post;

		echo '<style type="text/css">';
		echo '</style>';

		echo '<div id="custom_coupon_shortcodes" class="panel woocommerce_options_panel">';

		echo '<div class="options_group">';
		echo self::extensions();
		echo '</div>'; // .options_group

		echo '</div>'; // #custom_coupon_shortcodes .panel .woocommerce_options_panel

		if ( !( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.1.9' ) >= 0 ) ) {
			echo '<script type="text/javascript">';
			echo 'if (typeof jQuery !== "undefined"){';
			echo 'jQuery(document).ready(function(){';
			echo 'jQuery("#custom_coupon_shortcodes").insertAfter(jQuery(".woocommerce_options_panel").last());';
			echo '});';
			echo '}';
			echo '</script>';
		}
	}

	/**
	 * Returns extensions output.
	 *
	 * @return string
	 */
	public static function extensions() {

		global $woocommerce_coupon_shortcodes_extensions;

		$output = '';

		if ( !isset( $woocommerce_coupon_shortcodes_extensions ) ) {

			$woocommerce_coupon_shortcodes_extensions = true;

			$output .= '<style type="text/css">';
			$output .= '.woocommerce-coupon-shortcodes-extensions {';
			$output .= 'display: flex; flex-wrap: wrap;';
			$output .= '}';
			$output .= '.woocommerce-coupon-shortcodes-extension-container {';
			$output .= 'flex: 1; margin: 0.62em;';
			$output .= '}';
			$output .= '.woocommerce-coupon-shortcodes-extension-container a {';
			$output .= 'padding: 1em; margin: 0.62em; display: block; border: 1px solid #ccc; text-align: center; border-radius: 3px; text-decoration: none; color: #666;';
			$output .= '}';
			$output .= 'div.woocommerce-coupon-shortcodes-extension-container .extension-title {';
			$output .= 'display: block; font-size: 1.6em; font-weight: 900; line-height: 1.62em;';
			$output .= '}';
			$output .= 'div.woocommerce-coupon-shortcodes-extension-container .extension-description {';
			$output .= 'display: block; padding: 0.6em;';
			$output .= '}';
			$output .= '</style>';

			$output .= '<div class="woocommerce-coupon-shortcodes-extensions">';

			$output .= '<div class="woocommerce-coupon-shortcodes-extension-container">';
			$output .= sprintf(
				'<a target="_blank" href="%s"><div class="extension-title">%s</div><div class="extension-description">%s</div></a>',
				'https://woocommerce.com/products/group-coupons/?aff=7223&cid=2409803',
				'Group Coupons',
				esc_html__( 'Offer exclusive, automatic and targeted coupon discounts for your customers! Use group memberships and roles to control the validity of coupons.', 'woocommerce-coupon-shortcodes' )
			);
			$output .= '</div>';

			$output .= '<div class="woocommerce-coupon-shortcodes-extension-container">';
			$output .= sprintf(
				'<a target="_blank" href="%s"><div class="extension-title">%s</div><div class="extension-description">%s</div></a>',
				'https://woocommerce.com/products/woocommerce-product-search/?aff=7223&cid=2409803',
				'WooCommerce Product Search',
				esc_html__( 'The essential extension for every WooCommerce store! The perfect Search Engine for your store helps your customers to find and buy the right products quickly.', 'woocommerce-coupon-shortcodes' )
			);
			$output .= '</div>';

			$output .= '<div class="woocommerce-coupon-shortcodes-extension-container">';
			$output .= sprintf(
				'<a target="_blank" href="%s"><div class="extension-title">%s</div><div class="extension-description">%s</div></a>',
				'https://woocommerce.com/products/groups-woocommerce/?aff=7223&cid=2409803',
				'Groups WooCommerce',
				esc_html__( 'Sell Memberships with Groups and WooCommerce! Groups WooCommerce grants memberships based on products. It automatically assigns a customer to one or more groups based on the products ordered.', 'woocommerce-coupon-shortcodes' )
			);
			$output .= '</div>';

			$output .= '</div>';
		}
		return $output;
	}
}
WooCommerce_Coupon_Shortcodes_Admin_Coupon::init();
