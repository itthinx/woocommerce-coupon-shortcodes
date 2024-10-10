=== WooCommerce Coupon Shortcodes ===
Contributors: itthinx, proaktion
Donate link: https://www.itthinx.com/shop/
Tags: woocommerce, shortcode, coupon, discount, marketing, theme, conditional, coupons, discounts, display, info, information, promotion, subscription, subscriptions
Requires at least: 6.2
Requires PHP: 7.4
Tested up to: 6.6
Stable tag: 2.7.0
License: GPLv3

Show coupon discount info using shortcodes. Allows to render coupon information and content conditionally, based on the validity of coupons.

== Description ==

This extension for [WooCommerce](https:/woo.com) allows you to render coupon information and show content based on the validity of coupons.

Customers can be motivated to proceed with their purchase, offering them to use specific coupons when the contents in the cart qualify for it, or by offering them to purchase additional items so they can use a coupon.

Extended coupon discount info for volume discounts is shown automatically, if [Volume Discount Coupons](https://www.itthinx.com/shop/woocommerce-volume-discount-coupons) is installed.

It also works with [WooCommerce Group Coupons](https://www.itthinx.com/shop/woocommerce-group-coupons) and [WooCommerce Coupons Countdown](https://www.itthinx.com/shop/woocommerce-coupons-countdown).

= Conditional Shortcodes =

It provides the following conditional shortcodes that allow to enclose content which is shown if certain coupons are applied, valid or active:

`[coupon_is_active]`
`[coupon_is_not_active]`
`[coupon_is_applied]`
`[coupon_is_not_applied]`
`[coupon_is_valid]`
`[coupon_is_not_valid]`

These conditional shortcodes allow to render content if any coupons are applied, valid or active:

`[coupon_has_valid]`
`[coupon_has_not_valid]`
`[coupon_has_active]`
`[coupon_has_not_active]`
`[coupon_has_applied]`
`[coupon_has_not_applied]`

= Coupon Info Shortcodes =

It also provides shortcodes that allow to render the coupon code, its description and an automatic description of the discount:

`[coupon_code]` (this one makes sense mostly when used inside one of the conditional shortcodes).
`[coupon_description]`
`[coupon_discount]`
`[coupon_show]`

A coupon iterator shortcode makes it easy to work with a set of coupons, the enclosed content is rendered for each coupon in sequence. The informational shortcodes can be used inside the content of this shortcode to display details about each coupon code.

`[coupon_iterate]`

For example: `[coupon_iterate code="test,welcome,20off"][coupon_code] – [coupon_discount][/coupon_iterate]`

A coupon enumerator shortcode allows to list all or a set of coupons, to show their code, description or discount information, or combinations of those using the `[coupon_show]` shortcode:

`[coupon_enumerate]`

= Documentation =

Please refer to the plugin's [documentation pages](https://docs.itthinx.com/document/woocommerce-coupon-shortcodes/) for detailed descriptions.


= Examples =

Show a text when a coupon can be used (active) - this is useful to show promotional info while coupons can be used, as active means that the coupon has not reached its expiration date nor exceeded its usage limits:

`[coupon_is_active code="eastereggs"]
Happy Easter!
Use the coupon code [coupon_code] to hop away with a great discount : [coupon_discount]
[/coupon_is_active]`

This is an example of a text shown when a promotion is over:

`[coupon_is_not_active code="specialdiscount"]
Our special discount sale has ended. Come back often to see more!
[/coupon_is_not_active]`

Showing a coupon when the cart contents qualify for a coupon to be applied:

`[coupon_is_valid code="superdiscount"]
You qualify for a discount!
Use the coupon code [coupon_code] to take advantage of this great discount : [coupon_discount]
[/coupon_is_valid]`

Showing a coupon that is not valid for the current cart and motivating to add items:

`[coupon_is_not_valid code="25off"]
If you purchase 5 Widgets, you can use the coupon [coupon_code] to get 25% off your purchase!
[/coupon_is_not_valid]`

Iterate over several coupons and show their discount info:

`[coupon_iterate code="test,welcome,20off"]
Use the Coupon Code: [coupon_code] for [coupon_discount]
[/coupon_iterate]`

Iterate over all coupons and show their discount info:

`[coupon_iterate code="*"]
Use the Coupon Code: [coupon_code] for [coupon_discount]
[/coupon_iterate]`

Show information about three random coupons, including the coupon code, its description and discount info together on each entry:

`[coupon_enumerate code="*" orderby="rand" number="3"]
[coupon_show show="code,description,discount"]
[/coupon_enumerate]`

Show a single random coupon code:

`[coupon_enumerate code="*" orderby="rand" number="1"]
[coupon_code]
[/coupon_enumerate]`

= Documentation and Support =

Full usage instructions and help is provided on these pages:

- Please refer to the plugin's documentation pages for detailed information [Documentation](https://docs.itthinx.com/document/woocommerce-coupon-shortcodes/)
- Questions, feedback and suggestions can be posted on the plugin page [WooCommerce Coupon Shortcodes plugin page and Support](https://www.itthinx.com/plugins/woocommerce-coupon-shortcodes/)


== Installation ==

1. Upload or extract the `woocommerce-coupon-shortcodes` folder to your site's `/wp-content/plugins/` directory. You can also use the *Add new* option found in the *Plugins* menu in WordPress.
2. Enable the plugin from the *Plugins* menu in WordPress.

== Frequently Asked Questions ==

= Where is the documentation? =

[Documentation](https://docs.itthinx.com/document/woocommerce-coupon-shortcodes/)

= I have a question, where do I ask? =

You can leave a comment at the [WooCommerce Coupon Shortcodes plugin page](https://www.itthinx.com/plugins/woocommerce-coupon-shortcodes/).


== Screenshots ==

See the plugin page [WooCommerce Coupon Shortcodes](https://www.itthinx.com/plugins/woocommerce-coupon-shortcodes/)


== Changelog ==

[Complete Changelog](https://github.com/itthinx/woocommerce-coupon-shortcodes/blob/master/changelog.txt)


== Upgrade Notice ==

This release has been tested with the latest versions of WordPress and WooCommerce.
