<?php
/**
 * class-woocommerce-coupon-shortcodes-admin-notice.php
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

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Notices
 */
class WooCommerce_Coupon_Shortcodes_Admin_Notice {

	/**
	 * Time mark.
	 *
	 * @var string
	 */
	const INIT_TIME = 'woocommerce-coupon-shortcodes-init-time';

	/**
	 * Used to store user meta and hide the notice asking to review.
	 *
	 * @var string
	 */
	const HIDE_REVIEW_NOTICE = 'woocommerce-coupon-shortcodes-hide-review-notice';

	/**
	 * Used to check next time.
	 *
	 * @var string
	 */
	const REMIND_LATER_NOTICE = 'woocommerce-coupon-shortcodes-remind-later-notice';

	/**
	 * The number of seconds in five days, since init date to show the notice.
	 *
	 * @var int
	 */
	const SHOW_LAPSE = 432000;

	/**
	 * The number of seconds in one day, used to show notice later again.
	 *
	 * @var int
	 */
	const REMIND_LAPSE = 86400;

	/**
	 * Adds actions.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__,'admin_init' ) );
	}

	/**
	 * Hooked on the admin_init action.
	 */
	public static function admin_init() {
		if ( current_user_can( 'activate_plugins' ) ) {
			$user_id = get_current_user_id();
			if ( !empty( $_GET[self::HIDE_REVIEW_NOTICE] ) && wp_verify_nonce( $_GET['woocommerce-coupon-shortcodes_notice'], 'hide' ) ) {
				add_user_meta( $user_id, self::HIDE_REVIEW_NOTICE, true );
			}
			if ( !empty( $_GET[self::REMIND_LATER_NOTICE] ) && wp_verify_nonce( $_GET['woocommerce-coupon-shortcodes_notice'], 'later' ) ) {
				update_user_meta( $user_id, self::REMIND_LATER_NOTICE, time() + self::REMIND_LAPSE );
			}
			$hide_review_notice = get_user_meta( $user_id, self::HIDE_REVIEW_NOTICE, true );
			if ( empty( $hide_review_notice ) ) {
				$d = time() - self::get_init_time();
				if ( $d >= self::SHOW_LAPSE ) {
					$remind_later_notice = get_user_meta( $user_id, self::REMIND_LATER_NOTICE, true );
					if ( empty( $remind_later_notice ) || ( time() > $remind_later_notice ) ) {
						add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
					}
				}
			}
		}
	}

	/**
	 * Initializes if necessary and returns the init time.
	 */
	public static function get_init_time() {
		$init_time = get_site_option( self::INIT_TIME, null );
		if ( $init_time === null ) {
			$init_time = time();
			add_site_option( self::INIT_TIME, $init_time );
		}
		return $init_time;
	}

	/**
	 * Adds the admin notice.
	 */
	public static function admin_notices() {

		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$hide_url    = wp_nonce_url( add_query_arg( self::HIDE_REVIEW_NOTICE, true, $current_url ), 'hide', 'woocommerce-coupon-shortcodes_notice' );
		$remind_url  = wp_nonce_url( add_query_arg( self::REMIND_LATER_NOTICE, true, $current_url ), 'later', 'woocommerce-coupon-shortcodes_notice' );

		$output = '';

		$output .= '<style type="text/css">';

		$output .= '.woocommerce-message a.woocommerce-message-close::before {';
		$output .= 'position: relative;';
		$output .= 'top: 18px;';
		$output .= 'left: -20px;';
		$output .= '-webkit-transition: all .1s ease-in-out;';
		$output .= 'transition: all .1s ease-in-out;';
		$output .= '}';

		$output .= '.woocommerce-message a.woocommerce-message-close {';
		$output .= 'position: static;';
		$output .= 'float: right;';
		$output .= 'top: 0;';
		$output .= 'right: 0;';
		$output .= 'padding: 0 15px 10px 28px;';
		$output .= 'margin-top: -10px;';
		$output .= 'font-size: 13px;';
		$output .= 'line-height: 1.23076923;';
		$output .= 'text-decoration: none;';
		$output .= '}';

		$output .= 'div.woocommerce-message {';
		$output .= 'overflow: hidden;';
		$output .= 'position: relative;';
		$output .= 'border-left-color: #cc99c2 !important;';
		$output .= '}';

		$output .= 'div.woocommerce-coupon-shortcodes-rating {';
		$output .= sprintf( 'background: url(%s) #fff no-repeat 8px 8px;', WOO_CODES_PLUGIN_URL . '/images/icon-256x256.png' );
		$output .= 'padding-left: 84px ! important;';
		$output .= 'background-size: 64px 64px;';
		$output .= '}';
		$output .= '</style>';

		$output .= '<div class="updated woocommerce-message woocommerce-coupon-shortcodes-rating">';

		$output .= sprintf(
			'<a class="woocommerce-message-close notice-dismiss" href="%s">%s</a>',
			esc_url( $hide_url ),
			esc_html__( 'Dismiss', 'woocommerce-coupon-shortcodes' )
		);

		$output .= '<h2 style="font-size: 2.1em; font-weight: 600; margin: 18px 0 24px 0; line-height:36px;">';
		$output .= __( 'Many thanks for using <a style="text-decoration: none; color: #a64c84;" target="_blank" href="https://wordpress.org/plugins/woocommerce-coupon-shortcodes/">WooCommerce Coupon Shortcodes</a>!', 'woocommerce-coupon-shortcodes' );
		$output .= '</h2>';

		$output .= '<div style="margin-bottom:24px;">';
		$output .= '<p>';
		$output .= __( 'Could you please spare a minute and give it a review over at WordPress.org?', 'woocommerce-coupon-shortcodes' );
		$output .= '</p>';

		$output .= '<p>';
		$output .= sprintf(
			'<a class="button button-primary" href="%s" target="_blank">%s</a>',
			esc_url( 'https://wordpress.org/support/view/plugin-reviews/woocommerce-coupon-shortcodes?filter=5#postform' ),
			__( 'Yes, here we go!', 'woocommerce-coupon-shortcodes' )
		);
		$output .= '&emsp;';
		$output .= sprintf(
			'<a class="button" href="%s">%s</a>',
			esc_url( $remind_url ),
			esc_html( __( 'Remind me later', 'woocommerce-coupon-shortcodes' ) )
		);
		$output .= '</p>';
		$output .= '</div>';

		$output .= WooCommerce_Coupon_Shortcodes_Admin_Coupon::extensions();

		$output .= '<p>';
		$output .= sprintf(
			__( 'Follow <a href="%s">@itthinx</a> and visit <a href="%s" target="_blank">itthinx.com</a> to see more free and premium plugins we provide.', 'woocommerce-coupon-shortcodes' ),
			esc_url( 'https://twitter.com/itthinx' ),
			esc_url( 'https://www.itthinx.com' )
		);
		$output .= '</p>';

		$output .= '</div>';

		echo $output;
	}
}
WooCommerce_Coupon_Shortcodes_Admin_Notice::init();
