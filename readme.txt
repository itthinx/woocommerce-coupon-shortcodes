=== WooCommerce Coupon Shortcodes ===
Contributors: itthinx
Donate link: http://www.itthinx.com/plugins/woocommerce-coupon-shortcodes
Tags: conditional, coupon, coupons, discount, discounts, display, info, information, marketing, promotion, shortcode, shortcodes, subscription, subscriptions, woocommerce
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 1.3.0
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

= 1.3.0 =
* Tested with WordPress 4.4 and WooCommerce 2.4.10.
* Added the `[coupon_is_not_applied]` shortcode.

= 1.2.6 =
* Tested with WordPress 4.3 and WooCommerce 2.4.6.
* Updated the documentation links.

= 1.2.5 =
* WordPress 4.1 and WooCommerce 2.3.x compatibility checked
* Updated the version required

= 1.2.4 =
* WordPress 3.9 compatibility checked

= 1.2.3 =
* Improved coupon currency symbol rendering, now using wp_price() to render amount and currency when available.

= 1.2.2 =
* WordPress 3.8 compatibility checked

= 1.2.1 =
* Fixed a PHP Warning when no codes are supplied to a shortcode.

= 1.2.0 =
* Added [coupon_enumerate] shortcode
* Added support for subscription coupons
* Added the option to display the coupon code as prefix with the [coupon_description] and [coupon_discount] shortcodes.
* Fixed bug caused by undeclared variable used to check excluded product IDs

= 1.1.0 =
* Added: [coupon_is_applied] shortcode
* Improved: allow more flexible description and discount listings, the `element_tag` attribute can be used to specify enclosing tags other than the default `span`

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.3.0 =
* WordPress 4.4 compatibility checked and added the `[coupon_is_applied]` shortcode.
