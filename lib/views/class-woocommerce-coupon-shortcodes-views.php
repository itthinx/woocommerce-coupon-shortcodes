<?php
/**
 * class-woocommerce-coupon-shortcodes-views.php
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

if ( class_exists( 'WC_Discounts' ) ) {
	/**
	 * Extends the WC_Discounts class and uses native methods to evaluate coupon useability.
	 * @since 1.9.0
	 */
	class WooCommerce_Coupon_Shortcodes_WC_Discounts extends WC_Discounts {

		/**
		 * Check if the coupon exists.
		 *
		 * @param WC_Coupon $coupon
		 *
		 * @return boolean
		 */
		public function _wcs_coupon_exists( $coupon ) {
			try {
				$exists = $this->validate_coupon_exists( $coupon );
			} catch ( Exception $exception ) {
				$exists = false;
			}
			return $exists;
		}

		/**
		 * Check if the coupon has expired.
		 *
		 * @param WC_Coupon $coupon
		 *
		 * @return boolean
		 */
		public function _wcs_coupon_is_expired( $coupon ) {
			try {
				$is_expired = !$this->validate_coupon_expiry_date( $coupon );
			} catch ( Exception $exception ) {
				$is_expired = true;
			}
			return $is_expired;
		}

		/**
		 * Check whether the coupon can be used yet,
		 * based on its usage limit and its per user usage limit for the current user.
		 *
		 * @param WC_Coupon $coupon
		 *
		 * @return boolean
		 */
		public function _wcs_coupon_is_useable( $coupon ) {
			try {
				$is_useable = $this->validate_coupon_usage_limit( $coupon );
			} catch ( Exception $exception ) {
				$is_useable = false;
			}
			if ( $is_useable ) {
				try {
					$is_useable = $this->validate_coupon_user_usage_limit( $coupon );
				} catch ( Exception $exception ) {
					$is_useable = false;
				}
			}
			return $is_useable;
		}
	}
}

/**
 * Shortcodes.
 */
class WooCommerce_Coupon_Shortcodes_Views {

	/**
	 * Default limit used for the number attribute of the [coupon_enumerate] shortcode.
	 *
	 * @since 1.21.0
	 *
	 * @var int
	 */
	const NUMBER_LIMIT_DEFAULT = 25;

	/**
	 * Adds shortcodes.
	 */
	public static function init() {
		add_shortcode( 'coupon_enumerate', array( __CLASS__, 'coupon_enumerate' ) );
		add_shortcode( 'coupon_iterate', array( __CLASS__, 'coupon_iterate' ) ); // @since 1.21.0
		add_shortcode( 'coupon_is_applied', array( __CLASS__, 'coupon_is_applied' ) );
		add_shortcode( 'coupon_is_not_applied', array( __CLASS__, 'coupon_is_not_applied' ) );
		add_shortcode( 'coupon_is_valid', array( __CLASS__, 'coupon_is_valid' ) );
		add_shortcode( 'coupon_is_not_valid', array( __CLASS__, 'coupon_is_not_valid' ) );
		add_shortcode( 'coupon_code', array( __CLASS__, 'coupon_code' ) );
		add_shortcode( 'coupon_description', array( __CLASS__, 'coupon_description' ) );
		add_shortcode( 'coupon_discount', array( __CLASS__, 'coupon_discount' ) );
		add_shortcode( 'coupon_show', array( __CLASS__, 'coupon_show' ) );
		// WC >= 3.2
		if ( class_exists( 'WC_Discounts' ) ) {
			add_shortcode( 'coupon_is_active', array( __CLASS__, 'coupon_is_active' ) );
			add_shortcode( 'coupon_is_not_active', array( __CLASS__, 'coupon_is_not_active' ) );
		}
	}

	/**
	 * Evaluate coupons applied based on op and coupon codes.
	 *
	 * @param array $atts
	 * @return boolean
	 */
	private static function _is_applied( $atts ) {

		global $woocommerce_coupon_shortcodes_codes;

		$options = shortcode_atts(
			array(
				'coupon' => null,
				'code'   => null,
				'op'     => 'and'
			),
			$atts
		);

		if ( !isset( $woocommerce_coupon_shortcodes_codes ) ) {
			$code = null;
			if ( !empty( $options['code'] ) ) {
				$code = $options['code'];
			} else if ( !empty( $options['coupon'] ) ) {
				$code = $options['coupon'];
			}
			if ( $code === null ) {
				return '';
			}
			$codes = array_map( 'trim', explode( ',', $code ) );
		} else {
			$codes = $woocommerce_coupon_shortcodes_codes;
		}

		$applied_coupon_codes = self::_get_applied_codes();
		if ( !in_array( '*', $codes ) ) {
			if ( !isset( $woocommerce_coupon_shortcodes_codes ) ) {
				$woocommerce_coupon_shortcodes_codes = $codes;
			}
			$applied = array();
			foreach ( $codes as $code ) {
				$applied[] = in_array( $code, $applied_coupon_codes );
			}
			switch( strtolower( $options['op'] ) ) {
				case 'and' :
					$is_applied = self::conj( $applied );
					break;
				default :
					$is_applied = self::disj( $applied );
			}
		} else {
			if ( !isset( $woocommerce_coupon_shortcodes_codes ) ) {
				$woocommerce_coupon_shortcodes_codes = $applied_coupon_codes;
			}
			$is_applied = !empty( $applied_coupon_codes );
		}
		return $is_applied;
	}

	/**
	 * Evaluate coupons not applied based on op and coupon codes.
	 *
	 * @param array $atts
	 * @return boolean
	 */
	private static function _is_not_applied( $atts ) {

		global $woocommerce_coupon_shortcodes_codes;

		$options = shortcode_atts(
			array(
				'coupon' => null,
				'code'   => null,
				'op'     => 'and'
			),
			$atts
		);

		if ( !isset( $woocommerce_coupon_shortcodes_codes ) ) {
			// remove * if present
			if ( isset( $options['code'] ) ) {
				$codes = array_map( 'trim', explode( ',', $options['code'] ) );
				if ( in_array( '*', $codes ) ) {
					$codes = array_diff( $codes, array( '*' ) );
				}
				$options['code'] = implode( ',', $codes );
			}

			$code = null;
			if ( !empty( $options['code'] ) ) {
				$code = $options['code'];
			} else if ( !empty( $options['coupon'] ) ) {
				$code = $options['coupon'];
			}
			if ( $code === null ) {
				return '';
			}
			$codes = array_map( 'trim', explode( ',', $code ) );
		} else {
			$codes = $woocommerce_coupon_shortcodes_codes;
		}

		$applied_coupon_codes = self::_get_applied_codes();

		if ( !isset( $woocommerce_coupon_shortcodes_codes ) ) {
			$woocommerce_coupon_shortcodes_codes = $codes;
		}

		$not_applied = array();
		foreach ( $codes as $code ) {
			$not_applied[] = !in_array( $code, $applied_coupon_codes );
		}
		switch( strtolower( $options['op'] ) ) {
			case 'and' :
				$is_not_applied = self::conj( $not_applied );
				break;
			default :
				$is_not_applied = self::disj( $not_applied );
		}

		return $is_not_applied;
	}

	/**
	 * Returns the valid coupon codes currently applied to the cart.
	 * 
	 * @return array of string with coupon codes
	 */
	private static function _get_applied_codes() {
		global $woocommerce;
		$applied_coupon_codes = array();
		if ( isset( $woocommerce ) && isset( $woocommerce->cart ) ) {
			$cart = $woocommerce->cart;
			if ( ! empty( $cart->applied_coupons ) ) {
				foreach ( $cart->applied_coupons as $key => $code ) {
					$coupon = new WC_Coupon( $code );
					if ( ! is_wp_error( $coupon->is_valid() ) ) {
						$applied_coupon_codes[] = $code;
					}
				}
			}
		}
		return $applied_coupon_codes;
	}

	/**
	 * Returns all published coupon codes.
	 * 
	 * Options:
	 * 
	 * - type (coupon type) : fixed_cart, percent, fixed_product, percent_product, sign_up_fee, sign_up_fee_percent, recurring_fee, recurring_percent
	 * - type (sets) : cart, fixed, percent, product, recurring, sign_up, subscription
	 * - orderby : code/post_title, ID, rand (*)
	 * - order   : ASC/DESC (*)
	 * - number  : int
	 *
	 * (*) PRE 1.7.0 - order : ID, code
	 * (*) PRE 1.7.0 - orderby : ASC, DESC
	 *
	 * @return array of string with coupon codes
	 */
	private static function _get_coupon_codes( $options = array() ) {
		global $wpdb;

		$types = array();
		if ( isset( $options['type'] ) ) {
			$indicated_types = explode( ',', $options['type'] );
			foreach( $indicated_types as $indicated_type ) {
				$indicated_type = trim( $indicated_type );
				$selected_types = array();
				switch ( $indicated_type ) {
					case 'fixed_cart' :
					case 'percent' :
					case 'fixed_product' :
					case 'percent_product' :
					case 'sign_up_fee' :
					case 'sign_up_fee_percent' :
					case 'recurring_fee' :
					case 'recurring_percent' :
						$selected_types[] = $indicated_type;
						break;
					case 'cart' :
						$selected_types = array(
							'fixed_cart',
							'percent'
						);
						break;
					case 'fixed' :
						$selected_types = array(
							'fixed_cart',
							'fixed_product',
							'sign_up_fee',
							'recurring_fee'
						);
						break;
					case 'percent' :
						$selected_types = array(
							'percent',
							'percent_product',
							'sign_up_fee_percent',
							'recurring_percent'
						);
						break;
					case 'product' :
						$selected_types = array(
							'fixed_product',
							'pecent_product'
						);
						break;
					case 'recurring' :
						$selected_types = array(
							'recurring_fee',
							'recurring_percent'
						);
						break;
					case 'sign_up' :
						$selected_types = array(
							'sign_up_fee',
							'sign_up_fee_percent',
						);
						break;
					case 'subscription' :
						$selected_types = array(
							'sign_up_fee',
							'sign_up_fee_percent',
							'recurring_fee',
							'recurring_percent'
						);
						break;
				}
				if ( count( $selected_types ) > 0 ) {
					foreach( $selected_types as $selected_type ) {
						if ( !in_array( $selected_type, $types ) ) {
							$types[] = $selected_type;
						}
					}
				}
			}
		}

		// prior to 1.7.0 the options order and orderby were mistakenly swapped; cover for cases where these are used (*)

		$_order = null;
		$_orderby = null;

		$order = 'ASC';
		if ( isset( $options['order'] ) ) {
			switch( $options['order'] ) {
				// correct values as of 1.7.0
				case 'asc' :
				case 'ASC' :
				case 'desc' :
				case 'DESC' :
					$order = $options['order'];
					break;
				// (*) old swapped values which are for orderby
				case 'ID' :
				case 'post_title' :
				case 'code' :
					$_orderby = $options['order'];
					break;
				default :
					$order = 'ASC';
			}
		}

		$randomize = false;
		$orderby   = 'post_title';
		if ( isset( $options['orderby'] ) ) {
			switch( $options['orderby'] ) {
				// correct values as of 1.7.0
				case 'ID' :
				case 'post_title' :
					$orderby = $options['orderby'];
					break;
				case 'code' :
					$orderby = 'post_title';
					break;
				case 'rand' :
				case 'RAND' :
					// avoid doing a RAND DB query
					$orderby = 'post_title';
					$randomize = true;
					break;
				// (*) old swapped values which are for order
				case 'asc' :
				case 'ASC' :
				case 'desc' :
				case 'DESC' :
					$_order = $options['orderby'];
					break;
				default :
					$orderby = 'post_title';
			}
		}

		if ( $_order !== null ) {
			$order = $_order;
		}
		if ( $_orderby !== null ) {
			$orderby = $_orderby;
		}

		$number = WooCommerce_Coupon_Shortcodes::get_hard_limit();
		if ( $options['number'] !== null ) {
			$number = max( 1, intval( $options['number'] ) );
		}

		$coupon_codes = array();
		if ( count( $types ) == 0 ) {
			$_coupons = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT DISTINCT ID, post_title FROM $wpdb->posts WHERE post_type = 'shop_coupon' AND post_status = 'publish' ORDER BY $orderby $order LIMIT %d",
					intval( $number )
				)
			);
		} else {
			$types = esc_sql( $types );
			$ts = array();
			foreach( $types as $type ) {
				$ts[] = "'" . $type . "'";
			}
			$_types = ' (' . implode(',', $ts ) . ') ';
			$_coupons = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT DISTINCT ID, post_title FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON p.ID = pm.post_id WHERE p.post_type = 'shop_coupon' AND p.post_status = 'publish' AND pm.meta_key = 'discount_type' AND pm.meta_value IN $_types ORDER BY $orderby $order LIMIT %d",
						intval( number )
					)
			);
		}
		if ( $_coupons && ( count( $_coupons ) > 0 ) ) {

			if ( $randomize ) {
				shuffle( $_coupons );
			}

			if ( $number !== null ) {
				$_coupons = array_slice( $_coupons, 0, $number );
			}

			foreach ( $_coupons as $coupon ) {
				$coupon_code = $coupon->post_title;
				// @since 1.21.0 don't get the coupon objects as this has a considerable performance impact when there are large numbers of coupons
				// $coupon = new WC_Coupon( $coupon_code );
				// if ( $coupon->get_id() ) {
				// 	$coupon_codes[] = $coupon->get_code();
				// }
				// Apply this function instead to the post_title, which is what the setter of WC_Coupon does:
				if ( !empty( $coupon->post_title ) ) {
					$coupon_codes[] = wc_format_coupon_code( $coupon_code );
				}
			}
		}
		return $coupon_codes;
	}

	/**
	 * Evaluates to true if the set of coupons is considered as active.
	 * Active means: the coupon exists, it has not expired and its usage limit has not been exceeded.
	 *
	 * @param array $atts
	 * @return boolean
	 */
	private static function _is_active( $atts ) {

		global $woocommerce_coupon_shortcodes_codes;

		$options = shortcode_atts(
			array(
				'coupon' => null,
				'code'   => null,
				'op'     => 'and',
				'revop'  => false
			),
			$atts
		);

		if ( !isset( $woocommerce_coupon_shortcodes_codes ) ) {
			$code = null;
			if ( !empty( $options['code'] ) ) {
				$code = $options['code'];
			} else if ( !empty( $options['coupon'] ) ) {
				$code = $options['coupon'];
			}
			if ( $code === null ) {
				return '';
			}

			$codes = array_map( 'trim', explode( ',', $code ) );
			$woocommerce_coupon_shortcodes_codes = $codes;
		} else {
			$codes = $woocommerce_coupon_shortcodes_codes;
		}

		$wcs_discounts = new WooCommerce_Coupon_Shortcodes_WC_Discounts();

		$actives = array();
		foreach ( $codes as $code ) {
			$coupon = new WC_Coupon( $code );
			$actives[] =
				$wcs_discounts->_wcs_coupon_exists( $coupon ) &&
				!$wcs_discounts->_wcs_coupon_is_expired( $coupon ) &&
				$wcs_discounts->_wcs_coupon_is_useable( $coupon );
		}

		if ( $options['revop'] ) {
			switch( strtolower( $options['op'] ) ) {
				case 'and' :
					$options['op'] = 'or';
					break;
				case 'or' :
					$options['op'] = 'and';
					break;
			}
		}

		switch( strtolower( $options['op'] ) ) {
			case 'and' :
				$active = self::conj( $actives );
				break;
			default :
				$active = self::disj( $actives );
		}

		return $active;
	}

	/**
	 * Evaluate common validity based on op and coupon codes.
	 * 
	 * @param array $atts
	 * @return boolean
	 */
	private static function _is_valid( $atts ) {

		global $woocommerce_coupon_shortcodes_codes;

		$options = shortcode_atts(
			array(
				'coupon' => null,
				'code'   => null,
				'op'     => 'and',
				'revop'  => false
			),
			$atts
		);

		if ( !isset( $woocommerce_coupon_shortcodes_codes ) ) {
			$code = null;
			if ( !empty( $options['code'] ) ) {
				$code = $options['code'];
			} else if ( !empty( $options['coupon'] ) ) {
				$code = $options['coupon'];
			}
			if ( $code === null ) {
				return '';
			}

			$codes = array_map( 'trim', explode( ',', $code ) );
			$woocommerce_coupon_shortcodes_codes = $codes;
		} else {
			$codes = $woocommerce_coupon_shortcodes_codes;
		}

		$validities = array();
		// @since 1.16.0 $coupon->is_valid() was deprecated in WC 3.2.0
		if ( class_exists( 'WC_Discounts' ) && method_exists( 'WC_Discounts', 'is_coupon_valid' ) ) {
			$discounts = new WC_Discounts( WC()->cart );
			foreach ( $codes as $code ) {
				$coupon = new WC_Coupon( $code );
				if ( $coupon->get_id() ) {
					$validities[] = $discounts->is_coupon_valid( $coupon ) === true;
				}
			}
		} else {
			foreach ( $codes as $code ) {
				$coupon = new WC_Coupon( $code );
				if ( $coupon->get_id() ) {
					$validities[] = $coupon->is_valid();
				}
			}
		}

		if ( $options['revop'] ) {
			switch( strtolower( $options['op'] ) ) {
				case 'and' :
					$options['op'] = 'or';
					break;
				case 'or' :
					$options['op'] = 'and';
					break;
			}
		}

		switch( strtolower( $options['op'] ) ) {
			case 'and' :
				$valid = self::conj( $validities );
				break;
			default :
				$valid = self::disj( $validities );
		}

		return $valid;
	}

	/**
	 * Boolean AND on array elements.
	 * 
	 * @param array $a
	 * @return boolean true if all elements are true and there is at least one in the array, false otherwise
	 */
	public static function conj( $a ) {
		$r = false;
		if ( is_array( $a ) ) {
			$c = count( $a );
			if ( $c > 0 ) {
				$r = true;
				$i = 0;
				while( $r && ( $i < $c ) ) {
					$r = $r && $a[$i];
					$i++;
				}
			}
		}
		return $r;
	}

	/**
	 * Boolean OR on array elements.
	 * 
	 * @param array $a
	 * @return boolean true if at least one true element is in the array, false otherwise
	 */
	public static function disj( $a ) {
		$r = false;
		if ( is_array( $a ) ) {
			$c = count( $a );
			if ( $c > 0 ) {
				$r = false;
				$i = 0;
				while( !$r && ( $i < $c ) ) {
					$r = $r || $a[$i];
					$i++;
				}
			}
		}
		return $r;
	}

	/**
	 * Enumerate the coupons.
	 * 
	 * @param array $atts
	 * @param string $content
	 * @return string
	 */
	public static function coupon_enumerate( $atts, $content = null ) {

		global $woocommerce_coupon_shortcodes_codes;

		$options = shortcode_atts(
			array(
				'coupon'  => null,
				'code'    => null,
				'type'    => null,
				'order'   => null,
				'orderby' => null,
				'number'  => self::NUMBER_LIMIT_DEFAULT
			),
			$atts
		);

		$code = null;
		if ( !empty( $options['code'] ) ) {
			$code = $options['code'];
		} else if ( !empty( $options['coupon'] ) ) {
			$code = $options['coupon'];
		}
		if ( $code === null ) {
			return '';
		}

		if ( $options['number'] !== null ) {
			$options['number'] = max( 1, intval( $options['number'] ) );
		}

		$codes = array_map( 'trim', explode( ',', $code ) );
		if ( !in_array( '*', $codes ) ) {
			$woocommerce_coupon_shortcodes_codes = $codes;
			$existing = array();
			foreach ( $codes as $code ) {
				if ( wc_get_coupon_id_by_code( $code ) > 0 ) {
					$existing[] = $code;
				}
			}
			$woocommerce_coupon_shortcodes_codes = $existing;
		} else {
			$woocommerce_coupon_shortcodes_codes = self::_get_coupon_codes( $options );
		}

		remove_shortcode( 'coupon_enumerate' );
		$content = do_shortcode( $content );
		add_shortcode( 'coupon_enumerate', array( __CLASS__, 'coupon_enumerate' ) );
		return $content;
	}

	/**
	 * Iterate over a set of coupon codes, rendering the enclosed content for each code.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function coupon_iterate( $atts, $content = null ) {
		global $woocommerce_coupon_shortcodes_codes, $woocommerce_coupon_shortcodes_iterator_code;

		$options = shortcode_atts(
			array(
				'coupon'  => null,
				'code'    => null,
				'type'    => null,
				'order'   => null,
				'orderby' => null,
				'number'  => self::NUMBER_LIMIT_DEFAULT
			),
			$atts
		);

		$code = null;
		if ( !empty( $options['code'] ) ) {
			$code = $options['code'];
		} else if ( !empty( $options['coupon'] ) ) {
			$code = $options['coupon'];
		}
		if ( $code === null ) {
			return '';
		}

		if ( $options['number'] !== null ) {
			$options['number'] = max( 1, intval( $options['number'] ) );
		}

		$codes = array();
		$_codes = array_map( 'trim', explode( ',', $code ) );
		if ( !in_array( '*', $_codes ) ) {
			foreach ( $_codes as $code ) {
				if ( wc_get_coupon_id_by_code( $code ) > 0 ) {
					$codes[] = $code;
				}
			}
		} else {
			$codes = self::_get_coupon_codes( $options );
		}

		$output = '';
		foreach ( $codes as $code ) {
			remove_shortcode( 'coupon_iterate' );
			$woocommerce_coupon_shortcodes_iterator_code = $code;
			$woocommerce_coupon_shortcodes_codes = array( $code );
			$output .= do_shortcode( $content );
			add_shortcode( 'coupon_iterate', array( __CLASS__, 'coupon_iterate' ) );
		}
		return $output;
	}

	/**
	 * Conditionally render content based on coupons which are applied.
	 *
	 * Takes a comma-separated list of coupon codes as coupon or code attribute.
	 *
	 * The op attribute determines whether all codes must be applied (and) or
	 * any code can be applied (or) for the content to be rendered.
	 *
	 * @param array $atts attributes
	 * @param string $content content to render
	 * @return string
	 */
	public static function coupon_is_applied( $atts, $content = null ) {
		$output = '';
		if ( !empty( $content ) ) {
			$applied = self::_is_applied( $atts );
			if ( $applied ) {
				remove_shortcode( 'coupon_is_applied' );
				$content = do_shortcode( $content );
				add_shortcode( 'coupon_is_applied', array( __CLASS__, 'coupon_is_applied' ) );
				$output = $content;
			}
		}
		return $output;
	}

	/**
	 * Conditionally render content based on coupons which are not applied.
	 *
	 * Takes a comma-separated list of coupon codes as coupon or code attribute.
	 *
	 * The op attribute determines whether all codes must not be applied (and) or
	 * any code can not be applied (or) for the content to be rendered.
	 *
	 * @param array $atts attributes
	 * @param string $content content to render
	 * @return string
	 */
	public static function coupon_is_not_applied( $atts, $content = null ) {
		$output = '';
		if ( !empty( $content ) ) {
			$not_applied = self::_is_not_applied( $atts );
			if ( $not_applied ) {
				remove_shortcode( 'coupon_is_not_applied' );
				$content = do_shortcode( $content );
				add_shortcode( 'coupon_is_not_applied', array( __CLASS__, 'coupon_is_not_applied' ) );
				$output = $content;
			}
		}
		return $output;
	}

	/**
	 * Conditionally render content if the coupon(s) is (are) active, i.e. existing, not expired and
	 * below general and per user usage limits.
	 *
	 * Takes a comma-separated list of coupon codes as coupon or code attribute.
	 *
	 * The op attribute determines whether all codes must be active (and) or
	 * any code can be active (or) for the content to be rendered.
	 *
	 * @param array $atts attributes
	 * @param string $content content to render
	 * @return string
	 */
	public static function coupon_is_active( $atts, $content = null ) {
		$output = '';
		if ( !empty( $content ) ) {
			$active = self::_is_active( $atts );
			if ( $active ) {
				remove_shortcode( 'coupon_is_active' );
				$content = do_shortcode( $content );
				add_shortcode( 'coupon_is_active', array( __CLASS__, 'coupon_is_active' ) );
				$output = $content;
			}
		}
		return $output;
	}

	/**
	 * Conditionally render content if the coupon is not active, i.e. expired or with usage limits exceeded.
	 *
	 * Takes a comma-separated list of coupon codes as coupon or code attribute.
	 *
	 * The op attribute determines whether all codes must be active (and) or
	 * any code can be active (or) for the content to be rendered.
	 *
	 * @param array $atts attributes
	 * @param string $content content to render
	 * @return string
	 */
	public static function coupon_is_not_active( $atts, $content = null ) {
		$output = '';
		if ( !empty( $content ) ) {
			if ( is_array( $atts ) ) {
				$atts['revop'] = true;
			} else {
				$atts = array( 'revop' => true );
			}
			$active = !self::_is_active( $atts );
			if ( $active ) {
				remove_shortcode( 'coupon_is_not_active' );
				$content = do_shortcode( $content );
				add_shortcode( 'coupon_is_not_active', array( __CLASS__, 'coupon_is_not_active' ) );
				$output = $content;
			}
		}
		return $output;
	}

	/**
	 * Conditionally render content based on coupon validity.
	 * 
	 * Takes a comma-separated list of coupon codes as coupon or code attribute.
	 * 
	 * The op attribute determines whether all codes must be valid (and) or
	 * any code can be valid (or) for the content to be rendered.
	 * 
	 * @param array $atts attributes
	 * @param string $content content to render
	 * @return string
	 */
	public static function coupon_is_valid( $atts, $content = null ) {
		$output = '';
		if ( !empty( $content ) ) {
			$valid = self::_is_valid( $atts );
			if ( $valid ) {
				remove_shortcode( 'coupon_is_valid' );
				$content = do_shortcode( $content );
				add_shortcode( 'coupon_is_valid', array( __CLASS__, 'coupon_is_valid' ) );
				$output = $content;
			}
		}
		return $output;
	}

	/**
	 * Conditionally render content based on coupon non-validity.
	 *
	 * Takes a comma-separated list of coupon codes as coupon or code attribute.
	 *
	 * The op attribute determines whether all codes must be valid (and) or
	 * any code can be valid (or) for the content to be rendered.
	 *
	 * @param array $atts attributes
	 * @param string $content content to render
	 * @return string
	 */
	public static function coupon_is_not_valid( $atts, $content = null ) {
		$output = '';
		if ( !empty( $content ) ) {
			if ( is_array( $atts ) ) {
				$atts['revop'] = true;
			} else {
				$atts = array( 'revop' => true );
			}
			$valid = !self::_is_valid( $atts );
			if ( $valid ) {
				remove_shortcode( 'coupon_is_not_valid' );
				$content = do_shortcode( $content );
				add_shortcode( 'coupon_is_not_valid', array( __CLASS__, 'coupon_is_not_valid' ) );
				$output = $content;
			}
		}
		return $output;
	}

	/**
	 * Returns an array of (potential) coupon codes obtained
	 * through the options or through the global that might have been
	 * set in _is_valid.
	 * 
	 * @param array $options
	 * @return array
	 */
	private static function get_codes( $options ) {
		global $woocommerce_coupon_shortcodes_codes;
		$codes = array();
		$code = null;
		if ( !empty( $options['code'] ) ) {
			$code = $options['code'];
		} else if ( !empty( $options['coupon'] ) ) {
			$code = $options['coupon'];
		}
		if ( $code === null ) {
			if ( !empty( $woocommerce_coupon_shortcodes_codes ) ) {
				$codes = $woocommerce_coupon_shortcodes_codes;
			}
		}
		if ( empty( $codes ) ) {
			$codes = array_map( 'trim', explode( ',', $code ) );
		}
		return $codes;
	}

	/**
	 * Renders the code(s) of coupon(s).
	 *
	 * @param array $atts
	 * @param string $content not used
	 * @return string
	 */
	public static function coupon_code( $atts, $content = null ) {

		$output = '';
		$options = shortcode_atts(
			array(
				'coupon'    => null,
				'code'      => null,
				'separator' => ' '
			),
			$atts
		);

		$codes = self::get_codes( $options );
		foreach ( $codes as $code ) {
			// Tested with WC 5.4.1 during 1.21.0 development.
			// There is no substantial difference in performance between this alterantive code and the existing method using the object.
			//
			// Instead of checking the object, use the API function for the coupon's existence
			// if ( wc_get_coupon_id_by_code( $code ) > 0 ) {
			// 	$coupon_code = wc_format_coupon_code( $code );
			// 	$output .= sprintf( '<span class="coupon code %s">', stripslashes( wp_strip_all_tags( $coupon_code ) ) );
			// 	$output .= stripslashes( wp_strip_all_tags( $coupon_code ) );
			// 	$output .= '</span>';
			// 	$output .= stripslashes( wp_filter_kses( $options['separator'] ) );
			// }
			//
			// => pre-1.21.0 code left as is:
			$coupon = new WC_Coupon( $code );
			if ( $coupon->get_id() ) {
				$output .= sprintf( '<span class="coupon code %s">', stripslashes( wp_strip_all_tags( $coupon->get_code() ) ) );
				$output .= stripslashes( wp_strip_all_tags( $coupon->get_code() ) );
				$output .= '</span>';
				$output .= stripslashes( wp_filter_kses( $options['separator'] ) );
			}
		}
		return $output;
	}

	/**
	 * Renders the description(s) of coupon(s).
	 * 
	 * @param array $atts
	 * @param string $content not used
	 * @return string
	 */
	public static function coupon_description( $atts, $content = null ) {
		$output = '';
		$options = shortcode_atts(
			array(
				'coupon'      => null,
				'code'        => null,
				'separator'   => ' ',
				'element_tag' => 'span',
				'prefix'      => null,
				'prefix_separator' => ' '
			),
			$atts
		);

		switch( $options['element_tag'] ) {
			case 'li' :
			case 'span' :
			case 'div' :
			case 'p' :
				$element_tag = $options['element_tag'];
				break;
			default :
				$element_tag = 'span';
		}

		$prefix_code = false;
		if ( $options['prefix'] == 'code' ) {
			$prefix_code = true;
		}

		$elements = array();
		$codes = self::get_codes( $options );
		foreach ( $codes as $code ) {
			$coupon = new WC_Coupon( $code );
			if ( $coupon->get_id() ) {
				if ( $post = get_post( $coupon->get_id() ) ) {
					if ( !empty( $post->post_excerpt ) ) {

						$element_prefix = '';
						if ( $prefix_code ) {
							$element_prefix .= sprintf( '<span class="coupon code %s">', stripslashes( wp_strip_all_tags( $coupon->get_code() ) ) );
							$element_prefix .= stripslashes( wp_strip_all_tags( $coupon->get_code() ) );
							$element_prefix .= '</span>';
							$element_prefix .= stripslashes( wp_filter_kses( $options['prefix_separator'] ) );
						}

						$elements[] =
							$element_prefix .
							sprintf( '<%s class="coupon description %s">', stripslashes( wp_strip_all_tags( $element_tag ) ), stripslashes( wp_strip_all_tags( $coupon->get_code() ) ) ) .
							stripslashes( wp_filter_post_kses( $post->post_excerpt ) ) .
							sprintf( '</%s>', stripslashes( wp_strip_all_tags( $element_tag ) ) );
					}
				}
			}
		}

		if ( $element_tag == 'li' ) {
			$output .= '<ul>';
		}
		$output .= implode( stripslashes( wp_filter_kses( $options['separator'] ) ), $elements );
		if ( $element_tag == 'li' ) {
			$output .= '</ul>';
		}

		return $output;
	}

	/**
	 * Renders information about the discount for coupon(s).
	 *
	 * @param array $atts
	 * @param string $content not used
	 * @return string
	 */
	public static function coupon_discount( $atts, $content = null ) {
		$output = '';
		$options = shortcode_atts(
			array(
				'coupon'      => null,
				'code'        => null,
				'separator'   => ' ',
				'element_tag' => 'span',
				'renderer'    => 'auto',
				'prefix'      => null,
				'prefix_separator' => ' '
			),
			$atts
		);

		switch( $options['element_tag'] ) {
			case 'li' :
			case 'span' :
			case 'div' :
			case 'p' :
				$element_tag = $options['element_tag'];
				break;
			default :
				$element_tag = 'span';
		}

		$prefix_code = false;
		if ( $options['prefix'] == 'code' ) {
			$prefix_code = true;
		}

		$elements = array();
		$codes = self::get_codes( $options );
		foreach ( $codes as $code ) {
			$element_output = '';
			$coupon = new WC_Coupon( $code );
			if ( $coupon->get_id() ) {
				$element_output .= sprintf( '<%s class="coupon discount %s">', stripslashes( wp_strip_all_tags( $element_tag ) ), stripslashes( wp_strip_all_tags( $coupon->get_code() ) ) );

				$renderer = null;
				if ( $options['renderer'] == 'auto' ) {

					// WooCommerce_Coupons_Countdown_Shortcodes
					// does not differ

					// WooCommerce_Groupons_Shortcodes
					// does not differ

					// WooCommerce_Volume_Discount_Coupons_Shortcodes
					if ( class_exists( 'WooCommerce_Volume_Discount_Coupons_Shortcodes' ) ) {
						$min = get_post_meta( $coupon->get_id(), '_vd_min', true );
						$max = get_post_meta( $coupon->get_id(), '_vd_max', true );
						if ( ( $min > 0 ) || ( $max > 0 ) ) {
							$renderer = 'WooCommerce_Volume_Discount_Coupons_Shortcodes';
						}
					}

				}

				if ( $prefix_code ) {
					$element_output .= sprintf( '<span class="coupon code %s">', stripslashes( wp_strip_all_tags( $coupon->get_code() ) ) );
					$element_output .= stripslashes( wp_strip_all_tags( $coupon->get_code() ) );
					$element_output .= '</span>';
					$element_output .= stripslashes( wp_filter_kses( $options['prefix_separator'] ) );
				}

				if ( $renderer === null ) {
					$element_output .= self::get_discount_info( $coupon, $atts );
				} else {
					switch( $renderer ) {
						case 'WooCommerce_Volume_Discount_Coupons_Shortcodes' :
							$element_output .= WooCommerce_Volume_Discount_Coupons_Shortcodes::get_volume_discount_info( $coupon );
							break;
					}
				}
				$element_output .= sprintf( '</%s>', stripslashes( wp_strip_all_tags( $element_tag ) ) );
			}
			if ( !empty( $element_output ) ) {
				$elements[] = $element_output;
			}
		}
		if ( $element_tag == 'li' ) {
			$output .= '<ul>';
		}
		$output .= implode( stripslashes( wp_filter_kses( $options['separator'] ) ), $elements );
		if ( $element_tag == 'li' ) {
			$output .= '</ul>';
		}
		return $output;
	}

	/**
	 * Returns a description of the discount.
	 *
	 * @param WC_Coupon $coupon
	 * @return string HTML describing the discount
	 */
	public static function get_discount_info( $coupon, $atts = array() ) {
		$product_delimiter = isset( $atts['product_delimiter'] ) ? $atts['product_delimiter'] : ', ';
		$category_delimiter = isset( $atts['category_delimiter'] ) ? $atts['category_delimiter'] : ', ';
		$result = '';

		$amount_suffix = get_woocommerce_currency_symbol();
		if ( function_exists( 'wc_price' ) ) {
			$amount_suffix = null;
		}
		switch( $coupon->get_discount_type() ) {
			case 'percent' :
			case 'percent_product' :
			case 'sign_up_fee_percent' :
			case 'recurring_percent' :
				$amount_suffix = '%';
				break;
		}

		$products = array();
		$categories = array();
		switch ( $coupon->get_discount_type() ) {
			case 'fixed_product' :
			case 'percent_product' :
			case 'sign_up_fee' :
			case 'sign_up_fee_percent' :
			case 'recurring_fee' :
			case 'recurring_percent' :
				if ( sizeof( $coupon->get_product_ids() ) > 0 ) {
					foreach( $coupon->get_product_ids() as $product_id ) {
						$product = wc_get_product( $product_id );
						if ( $product ) {
							$products[] = sprintf(
								'<span class="product-link"><a href="%s">%s</a></span>',
								esc_url( get_permalink( $product_id ) ),
								$product->get_title()
							);
						}
					}
				}
				if ( sizeof( $coupon->get_product_categories() ) > 0 ) {
					foreach( $coupon->get_product_categories() as $term_id ) {
						if ( $term = get_term_by( 'id', $term_id, 'product_cat' ) ) {
							$categories[] = sprintf(
								'<span class="product-link"><a href="%s">%s</a></span>',
								get_term_link( $term->slug, 'product_cat' ),
								esc_html( $term->name )
							);
						}
					}
				}
				break;
		}

		$amount = $coupon->get_amount();
		if ( $amount_suffix === null ) {
			$amount = wc_price( $amount );
			$amount_suffix = '';
		}
		switch ( $coupon->get_discount_type() ) {

			case 'fixed_product' :
			case 'percent_product' :
				if ( sizeof( $coupon->get_product_ids() ) > 0 ) {
					if ( count( $products ) > 0 ) {
						$result = sprintf( __( '%s%s Discount on %s', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix, implode( $product_delimiter, $products ) );
					} else {
						$result = sprintf( __( '%s%s Discount on selected products', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix );
					}
				} else if ( sizeof( $coupon->get_product_categories() ) > 0 ) {
					$result = sprintf( __( '%s%s Discount in %s', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix, implode( $category_delimiter, $categories ) );
				} else if ( sizeof( $coupon->get_exclude_product_ids() ) > 0 || sizeof( $coupon->get_exclude_product_categories() ) > 0 ) {
					$result = sprintf( __( '%s%s Discount on selected products', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix );
				} else {
					$result = sprintf( __( '%s%s Discount', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix );
				}

				break;

			case 'fixed_cart' :
			case 'percent' :
				$result = sprintf( __( '%s%s Discount', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix );
				break;

			case 'sign_up_fee' :
			case 'sign_up_fee_percent' :
			case 'recurring_fee' :
			case 'recurring_percent' :
				$discount_name = __( 'Subscription Discount', WOO_CODES_PLUGIN_DOMAIN );
				if ( $coupon->get_discount_type() == 'sign_up_fee' || $coupon->get_discount_type() == 'sign_up_fee_percent' ) {
					$discount_name = __( 'Sign Up Discount', WOO_CODES_PLUGIN_DOMAIN );
				}
				if ( sizeof( $coupon->get_product_ids() ) > 0 ) {
					if ( count( $products ) > 0 ) {
						$result = sprintf( __( '%s%s %s on %s', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix, $discount_name, implode( $product_delimiter, $products ) );
					} else {
						$result = sprintf( __( '%s%s %s on selected products', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix, $discount_name );
					}
				} else if ( sizeof( $coupon->get_product_categories() ) > 0 ) {
					$result = sprintf( __( '%s%s %s in %s', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix, $discount_name, implode( $category_delimiter, $categories ) );
				} else if ( sizeof( $coupon->get_exclude_product_ids() ) > 0 || sizeof( $coupon->get_exclude_product_categories() ) > 0 ) {
					$result = sprintf( __( '%s%s %s on selected products', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix, $discount_name );
				} else {
					$result = sprintf( __( '%s%s %s', WOO_CODES_PLUGIN_DOMAIN ), $amount, $amount_suffix, $discount_name );
				}
				break;
		}

		return apply_filters( 'woocommerce_coupon_shortcodes_info', $result, $coupon );
	}

	/**
	 * Renders coupon info.
	 *
	 * @param array $atts
	 * @param string $content not used
	 * @return string
	 */
	public static function coupon_show( $atts, $content = null ) {

		$output = '';
		$options = shortcode_atts(
			array(
				'show'         => 'code,discount',
				'code'         => null,
				'before'       => '<div>',
				'after'        => '</div>',
				'before_entry' => '',
				'after_entry'  => '',
				'separator'    => ' '
			),
			$atts
		);

		$show = array();
		$_show = array_map( 'trim', explode( ',', trim( $options['show'] ) ) );
		foreach ( $_show as $what ) {
			switch ( $what ) {
				case 'code' :
				case 'description' :
				case 'discount' :
					$show[] = $what;
					break;
			}
		}

		if ( count( $show ) > 0 ) {
			$codes = self::get_codes( $options );
			foreach ( $codes as $code ) {
				$coupon = new WC_Coupon( $code );
				if ( $coupon->get_id() ) {
					$output .= $options['before'];
					for ( $i = 0; $i < count( $show ); $i++ ) {
						$html = '';
						switch ( $show[$i] ) {
							case 'code' :
								$html = self::coupon_code( array( 'code' => $code ) );
								break;
							case 'description' :
								$html = self::coupon_description( array( 'code' => $code ) );
								break;
							case 'discount' :
								$html = self::coupon_discount( array( 'code' => $code ) );
								break;
						}
						if ( strlen( $html ) > 0 ) {
							$output .= stripslashes( wp_filter_kses( $options['before_entry'] ) );
							$output .= $html;
							$output .= stripslashes( wp_filter_kses( $options['after_entry'] ) );
							if ( $i < count( $show ) - 1 ) {
								$output .= stripslashes( wp_filter_kses( $options['separator'] ) );
							}
						}
					}
					$output .= $options['after'];
				}
			}
		}
		return $output;
	}
}
WooCommerce_Coupon_Shortcodes_Views::init();
