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
		$tabs['shortcodes'] = array(
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
		echo 'li.coupon-shortcodes a::before {';
		echo 'content: "\f491" !important;';
		echo 'font-family: dashicons;';
		echo '}';
		echo '#custom_coupon_shortcodes {';
		echo 'padding: 1em;';
		echo '}';
		echo '#custom_coupon_shortcodes h3 {';
		echo 'margin: 1.6em 0 0 0;';
		echo '}';
		echo '#custom_coupon_shortcodes pre {';
		echo 'overflow: scroll;';
		echo 'padding: 0.62em;';
		echo 'background-color: #fff;';
		echo 'color: #333;';
		echo 'border: 1px solid #999';
		echo '}';
		echo '</style>';

		echo '<div id="custom_coupon_shortcodes" class="panel woocommerce_options_panel">';

		echo '<div class="options_group">';

		echo '<p class="description">';
		esc_html_e( 'Here are examples of shortcodes that you can use with this coupon.', 'woocommerce-coupon-shortcodes' );
		echo ' ';
		printf(
			__( 'For more details on these and other available shortcodes, please refer to the <a href="%s">documentation</a>.', 'woocommerce-coupon-shortcodes' ),
			esc_url( 'https://docs.itthinx.com/document/woocommerce-coupon-shortcodes/' )
		);
		echo '</p>';

		echo '<p>';
		printf(
			esc_html__( 'You can support the development of this extension by purchasing tools from the %s and %s for WooCommerce.', 'woocommerce-coupon-shortcodes' ),
			sprintf( '<a href="https://www.itthinx.com/shop/">%s</a>', esc_html__( 'Shop', 'woocommerce-coupon-shortcodes' ) ),
			sprintf( '<a href="https://woocommerce.com/vendor/itthinx/?aff=7223&cid=2409803">%s</a>', esc_html__( 'Extensions', 'woocommerce-coupon-shortcodes' ) )
		);
		echo '</p>';

		$code = '&hellip;';
		if ( $post->post_status !== 'auto-draft' ) {
			if ( class_exists( 'WC_Coupon' ) && method_exists( 'WC_Coupon', 'get_code' ) ) {
				$coupon = new WC_Coupon( $post->ID );
				$code = $coupon->get_code();
			}
		}

		// coupon_is_active
		echo '<h3>';
		echo '[coupon_is_active]';
		echo '</h3>';

		echo '<p>';
		esc_html_e( 'A coupon is considered active while it has not expired and its usage limits have not been exhausted.', 'woocommerce-coupon-shortcodes' );
		echo ' ';
		esc_html_e( 'The shortcode reveals the content it encloses when the condition evaluates favorably.', 'woocommerce-coupon-shortcodes' );
		echo '</p>';

		echo '<pre>';
		printf( '[coupon_is_active code="%s"]', esc_attr( $code ) );
		echo "\n";
		esc_html_e( 'This text is shown when the coupon is active.', 'woocommerce-coupon-shortcodes' );
		echo "\n";
		echo '[/coupon_is_active]';
		echo '</pre>';

		// coupon_is_not_active
		echo '<h3>';
		echo '[coupon_is_not_active]';
		echo '</h3>';

		echo '<p>';
		esc_html_e( 'This shortcode reveals the content it encloses when the code is not considered active.', 'woocommerce-coupon-shortcodes' );
		echo '</p>';

		echo '<pre>';
		printf( '[coupon_is_not_active code="%s"]', esc_attr( $code ) );
		echo "\n";
		esc_html_e( 'This text is shown when the coupon is not active.', 'woocommerce-coupon-shortcodes' );
		echo "\n";
		echo '[/coupon_is_not_active]';
		echo '</pre>';

		// coupon_is_applied
		echo '<h3>';
		echo '[coupon_is_applied]';
		echo '</h3>';

		echo '<p>';
		esc_html_e( 'Used to show content if a coupon is currently applied to the cart.', 'woocommerce-coupon-shortcodes' );
		echo '</p>';

		echo '<pre>';
		printf( '[coupon_is_applied code="%s"]', esc_attr( $code ) );
		echo "\n";
		esc_html_e( 'This text is shown if the coupon is currently applied to the cart.', 'woocommerce-coupon-shortcodes' );
		echo "\n";
		echo '[/coupon_is_applied]';
		echo '</pre>';

		// coupon_is_not_applied
		echo '<h3>';
		echo '[coupon_is_not_applied]';
		echo '</h3>';

		echo '<p>';
		esc_html_e( 'This shortcode will show the enclosed content if the coupon is currently not applied to the cart.', 'woocommerce-coupon-shortcodes' );
		echo '</p>';

		echo '<pre>';
		printf( '[coupon_is_not_applied code="%s"]', esc_attr( $code ) );
		echo "\n";
		esc_html_e( 'This text is shown if the coupon is currently not applied to the cart.', 'woocommerce-coupon-shortcodes' );
		echo "\n";
		echo '[/coupon_is_not_applied]';
		echo '</pre>';

		// coupon_is_valid
		echo '<h3>';
		echo '[coupon_is_valid]';
		echo '</h3>';

		echo '<p>';
		esc_html_e( 'This shortcode will display the content it encloses if the coupon is currently valid.', 'woocommerce-coupon-shortcodes' );
		echo '</p>';

		echo '<pre>';
		printf( '[coupon_is_valid code="%s"]', esc_attr( $code ) );
		echo "\n";
		esc_html_e( 'This text is shown if the coupon is valid.', 'woocommerce-coupon-shortcodes' );
		echo "\n";
		echo '[/coupon_is_valid]';
		echo '</pre>';

		// coupon_is_not_valid
		echo '<h3>';
		echo '[coupon_is_not_valid]';
		echo '</h3>';

		echo '<p>';
		esc_html_e( 'This shortcode will display the content while the coupon is not valid.', 'woocommerce-coupon-shortcodes' );
		echo '</p>';

		echo '<pre>';
		printf( '[coupon_is_not_valid code="%s"]', esc_attr( $code ) );
		echo "\n";
		esc_html_e( 'This text is shown if the coupon is not valid.', 'woocommerce-coupon-shortcodes' );
		echo "\n";
		echo '[/coupon_is_not_valid]';
		echo '</pre>';

		echo '</div>'; // .options_group

		echo self::extensions();

		echo '</div>'; // #custom_coupon_shortcodes .panel .woocommerce_options_panel
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
			$output .= '.woocommerce-coupon-shortcodes-extension-container.featured {';
			$output .= 'flex: 2;';
			$output .= '}';
			$output .= '.woocommerce-coupon-shortcodes-extension-container a {';
			$output .= 'padding: 1em; margin: 0.62em; display: block; border: 1px solid #ccc; text-align: center; border-radius: 3px; text-decoration: none; color: #666;';
			$output .= '}';
			$output .= 'div.woocommerce-coupon-shortcodes-extension-container .extension-title {';
			$output .= 'color: #7f54b3; display: block; font-size: 1.2em; font-weight: 700; line-height: 1.22em;';
			$output .= '}';
			$output .= 'div.woocommerce-coupon-shortcodes-extension-container.featured .extension-title {';
			$output .= 'color: #7f54b3; display: block; font-size: 1.6em; font-weight: 900; line-height: 1.62em;';
			$output .= '}';
			$output .= 'div.woocommerce-coupon-shortcodes-extension-container .extension-description {';
			$output .= 'display: block; padding: 0.6em;';
			$output .= '}';
			$output .= '</style>';

			$extensions = array(
				array(
					'title'       => 'Group Coupons',
					'description' => esc_html__( 'Offer exclusive, automatic and targeted coupon discounts for your customers! Use group memberships and roles to control the validity of coupons.', 'woocommerce-coupon-shortcodes' ),
					'url'         => 'https://woocommerce.com/products/group-coupons/?aff=7223&cid=2409803',
					'featured'    => true
				),
				array(
					'title'       => 'WooCommerce Product Search',
					'description' => esc_html__( 'The essential extension for every WooCommerce store! The perfect Search Engine for your store helps your customers to find and buy the right products quickly.', 'woocommerce-coupon-shortcodes' ),
					'url'         => 'https://woocommerce.com/products/woocommerce-product-search/?aff=7223&cid=2409803',
					'featured'    => true
				),
				array(
					'title'       => 'Group Memberships',
					'description' => esc_html__( 'Sell Memberships with Groups and WooCommerce! Groups WooCommerce grants memberships based on products. It automatically assigns a customer to one or more groups based on the products ordered.', 'woocommerce-coupon-shortcodes' ),
					'url'         => 'https://woocommerce.com/products/groups-woocommerce/?aff=7223&cid=2409803',
					'featured'    => true
				),
				array(
					'title'       => 'Sales Analysis',
					'description' => esc_html__( 'Sales Analysis oriented at Marketing & Management. Get in-depth views on fundamental Business Intelligence, focused on Sales and net Revenue Trends, International Sales Reports, Product Market and Customer Trends.', 'woocommerce-coupon-shortcodes' ),
					'url'         => 'https://woocommerce.com/products/sales-analysis-for-woocommerce/?aff=7223&cid=2409803',
					'featured'    => true
				),
				array(
					'title'       => 'Volume Discount Coupons',
					'description' => esc_html__( 'Provides automatic discounts and coupons based on the quantities of products in the cart.', 'woocommerce-coupon-shortcodes' ),
					'url'         => 'https://woocommerce.com/products/volume-discount-coupons/?aff=7223&cid=2409803',
					'featured'    => true
				),
				array(
					'title'       => 'Coupons Countdown',
					'description' => esc_html__( 'Provides pretty coupons with real-time countdown counters. Show your customers the coupons they can use and when they expire.', 'woocommerce-coupon-shortcodes' ),
					'url'         => 'https://www.itthinx.com/shop/woocommerce-coupons-countdown/'
				),
				array(
					'title'       => 'Coupon Exclusions',
					'description' => esc_html__( 'WooCommerce Coupon Exclusions is a powerful and easy to use WooCommerce extension which provides extended coupon usage restrictions.', 'woocommerce-coupon-shortcodes' ),
					'url'         => 'https://www.itthinx.com/shop/woocommerce-coupon-exclusions/'
				),
				array(
					'title'       => 'WooCommerce Documentation',
					'description' => esc_html__( 'This extension is based on the free Documentation plugin. It allows to link documentation pages to products and display them automatically on the product pages.', 'woocommerce-coupon-shortcodes' ),
					'url'         => 'https://www.itthinx.com/shop/woocommerce-documentation/'
				),
			);

			$output .= '<div class="options_group">';
			$output .= '<p style="padding-top: 1em; font-size: 1.1em; font-weight: 600;">';
			$output .= esc_html__( 'Please also have a look at these premium extensions that help to improve your store!', 'woocommerce-coupon-shortcodes' );
			$output .= '</p>';

			$output .= '<div class="woocommerce-coupon-shortcodes-extensions">';

			foreach ( $extensions as $ext ) {
				$output .= sprintf(
					'<div class="woocommerce-coupon-shortcodes-extension-container %s">',
					isset( $ext['featured'] ) && $ext['featured'] ? 'featured' : ''
				);
				$output .= sprintf(
					'<a target="_blank" href="%s"><div class="extension-title">%s</div><div class="extension-description">%s</div></a>',
					$ext['url'],
					$ext['title'],
					$ext['description']
				);
				$output .= '</div>';
			}

			$output .= '</div>'; // .woocommerce-coupon-shortcodes-extensions

			$output .= '</div>'; // .options_group
		}
		return $output;
	}
}
WooCommerce_Coupon_Shortcodes_Admin_Coupon::init();
