=== Courier ===
Contributors: linchpin_agency, aware, ncallen, desrosj, fischfood
Tags: linchpin, front end, notices, notifications, alerts, dismissible, responsive, foundation, gravity forms
Requires at least: 4.0
Tested up to: 5.2.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

#Courier

Add dismissible and non-dismissible notices throughout your website. Courier notices can be site specific or assigned to specific users.

Courier works as a Gravity forms add-on for displaying form confirmations.
Courier integrates with the popular Stream Plugin

##Shortcodes

`[get_courier_notice id="1" flag="Alert" show_flag="true"]`

Displays a Courier notice based on the Courier post ID, displays a flag to call out the notice as well.

Flag prepends a span containing the string in the flag parameter, to display no flag set "show_flag" to false.

##Changelog

####1.0 - Feb 20, 2017

Public Release!

* Cleaned up UI for date and time selection.
* You can no longer select an expiration date from the past.
* Implemented datetimepicker so time selection is easier.
* Minor typo fix in admin area.
* Minor data sanitization/security hardening.

####0.5.5 - July 25, 2016

* New: Add shortcode to display Courier notice, add an admin display to copy shortcode from the Courier post. Shortcode get_courier_notice accepts 3 parameters for id (equal to Courier post ID), flag (string, default is Alert) and show_flag (boolean, default is true).
