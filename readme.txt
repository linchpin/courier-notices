=== Courier Notices ===
Contributors: linchpin_agency, aware, ncallen, maxinacube, fischfood, desrosj
Tags: notifications, notices, alerts, linchpin, front end, dismissible, gdpr
Requires at least: 5.0
Tested up to: 6.5.5
x-release-please-start-version
Stable tag: 1.9.4
x-release-please-end
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add dismissible and non-dismissible notices throughout your website.

== Description ==

Add dismissible and non-dismissible notices throughout your website.

* Customize the colors and icons used by your Courier Notices **no coding needed**
* Add new "types" of notices as needed
* Comes with a common library of useful notices (Modal, Header, Footer)
* Place courier notices within the header, footer or any where using CSS selectors (similar to selectors in jQuery)
* Display popover/page take over modal style notices
* Can be used in conjunction with other popular plugins

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `courier-notices` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Does this plugin work with Full Page Caching or WordPress Object Caching? =

Yes, just be sure to enable the option to load Courier Notices using AJAX

= I'm looking for a specific type of notice that courier doesn't have, what's next? =

The great thing is that Courier Notices is highly extendable and our team is adding new features constantly since release. You can always create a pull request in GitHub or add an issue. You can also take a look at the Pro version which also has some great features.

== Screenshots ==

1. List of "Types" of Informational Courier Notices within the WordPress admin
2. Editing colors and icons of a Courier Notice
3. Frontend display of a few Courier Notices, including dismissible notices.
4. Frontend display of a modal Courier Notice.

== Changelog ==

See changelog https://github.com/linchpin/courier-notices/blob/master/CHANGELOG.md

== Shortcodes ==

`[courier_notice id="1"]`

Displays a Courier notice based on the Courier post ID, displays a flag to call out the notice as well.

Flag prepends a span containing the string in the flag parameter, to display no flag set "show_flag" to false.
