=== WooCommerce Coupon Shortcodes ===
Contributors: itthinx
Donate link: http://www.itthinx.com/shop/
Tags: woocommerce, shortcode, coupon, discount, marketing, theme, conditional, coupons, discounts, display, info, information, promotion, subscription, subscriptions
Requires at least: 4.6
Requires PHP: 5.5.38
Tested up to: 4.9
Stable tag: 1.6.2
License: GPLv3

Show coupon discount info using shortcodes. Allows to render coupon information and content conditionally, based on the validity of coupons.

== Description ==

This extension for [WooCommerce](http://wordpress.org/extend/plugins/woocommerce) allows you to render coupon information and show content based on the validity of coupons.

Customers can be motivated to proceed with their purchase, offering them to use specific coupons
when the contents in the cart qualify for it, or by offering them to purchase additional items
so they can use a coupon.

Extended coupon discount info for volume discounts is shown automatically, if the [WooCommerce Volume Discount Coupons](http://www.itthinx.com/plugins/woocommerce-volume-discount-coupons) is installed.

It also works with [WooCommerce Coupons Countdown](http://www.itthinx.com/plugins/woocommerce-coupons-countdown).

= Conditional Shortcodes =

It provides the following conditional shortcodes that allow to enclose content which is shown if coupons are applied, valid or not valid.

`[coupon_is_applied]`
`[coupon_is_not_applied]`
`[coupon_is_valid]`
`[coupon_is_not_valid]`

= Coupon Info Shortcodes =

It also provides shortcodes that allow to render the coupon code, its description and an automatic description of the discount:

`[coupon_code]` (this one makes sense mostly when used inside one of the conditional shortcodes).
`[coupon_description]`
`[coupon_discount]`

A coupon enumerator shortcode allows to list all or a set of coupons, to show their code, description or discount information:

`[coupon_enumerate]`

= Documentation =

Please refer to the plugin's [documentation pages](http://docs.itthinx.com/document/woocommerce-coupon-shortcodes/) for detailed descriptions.


= Examples =

Showing a coupon when the cart contents qualify for a coupon to be applied: 

`[coupon_is_valid code="superdiscount"]
You qualify for a discount!
Use the coupon code [coupon_code] to take advantage of this great discount : [coupon_discount]
[/coupon_is_valid]`

Showing a coupon that is not valid for the current cart and motivating to add items:

`[coupon_is_not_valid code="25off"]
If you purchase 5 Widgets, you can use the coupon [coupon_code] to get 25% off your purchase!
[/coupon_is_not_valid]`

= Documentation and Support =

Full usage instructions and help is provided on these pages:

- Please refer to the plugin's documentation pages for detailed information [Documentation](http://docs.itthinx.com/document/woocommerce-coupon-shortcodes/)
- Questions, feedback and suggestions can be posted on the plugin page [WooCommerce Coupon Shortcodes plugin page and Support](http://www.itthinx.com/plugins/woocommerce-coupon-shortcodes/)


== Installation ==

1. Upload or extract the `woocommerce-coupon-shortcodes` folder to your site's `/wp-content/plugins/` directory. You can also use the *Add new* option found in the *Plugins* menu in WordPress.  
2. Enable the plugin from the *Plugins* menu in WordPress.

== Frequently Asked Questions ==

= Where is the documentation? =

[Documentation](http://docs.itthinx.com/document/woocommerce-coupon-shortcodes/)

= I have a question, where do I ask? =

You can leave a comment at the [WooCommerce Coupon Shortcodes plugin page](http://www.itthinx.com/plugins/woocommerce-coupon-shortcodes/).


== Screenshots ==

See the plugin page [WooCommerce Coupon Shortcodes](http://www.itthinx.com/plugins/woocommerce-discount-coupons/)


== Changelog ==

= 1.6.2 =
* Added the Woo plugin header tag.

= 1.6.1 =
* Updated compatibility with WooCommerce 3.4

= 1.6.0 =
* Tested with WordPress 4.9.
* Tested with WooCommerce 3.3.5
* Tested with WooCommerce 3.4.0-beta.1

[Complete Changelog](https://github.com/itthinx/woocommerce-coupon-shortcodes/blob/master/changelog.txt)

== Upgrade Notice ==

This release has been tested with the latest versions of WordPress and WooCommerce.
