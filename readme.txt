=== Courier Notices ===
Contributors: linchpin_agency, aware, ncallen, maxinacube, fischfood, desrosj
Tags: notifications, notices, alerts, modal, dismissible
Requires at least: 6.0
Tested up to: 6.8.1
x-release-please-start-version
Stable tag: 1.9.15
x-release-please-end
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add dismissible and non-dismissible notices throughout your WordPress website with customizable colors, icons, and placement options.

== Description ==

**Courier Notices** is a powerful WordPress plugin that allows you to create and display custom notices, alerts, and modals throughout your website. Perfect for announcements, promotions, warnings, or any important information you want to share with your visitors.

**Key Features:**

* **Easy Customization** - Customize colors and icons with no coding required
* **Multiple Notice Types** - Create different types of notices (Primary, Success, Alert, Warning, Feedback, Info)
* **Flexible Placement** - Display notices in header, footer, as modals, or anywhere using CSS selectors
* **Dismissible Options** - Choose whether notices can be dismissed by users
* **Modal Support** - Create full-screen overlay popup notices
* **AJAX Loading** - Compatible with caching plugins and CDNs
* **Developer Friendly** - REST API endpoints and hooks for custom development

**Perfect for:**
* Website announcements and updates
* Promotional campaigns and special offers
* Important warnings or maintenance notices
* User feedback and success messages
* Emergency alerts and notifications

**Notice Types Include:**
* **Primary** - General information (#039ad6)
* **Success** - Positive confirmations (#04a84e)
* **Alert** - Important warnings (#f97600)
* **Warning** - Critical alerts (#ea3118)
* **Feedback** - Form responses (#8839d3)
* **Info** - General information (#878787)

**Placement Options:**
* **Header** - Displays at the top of the page
* **Footer** - Fixed position at the bottom
* **Modal** - Full-screen overlay popup
* **Custom** - Use CSS selectors for specific placement

== Installation ==

### Automatic Installation (Recommended)

1. Go to **Plugins > Add New** in your WordPress admin
2. Search for "Courier Notices"
3. Click **Install Now** and then **Activate**

### Manual Installation

1. Download the plugin ZIP file
2. Upload to `/wp-content/plugins/courier-notices/`
3. Activate through **Plugins > Installed Plugins**

### Post-Installation Setup

1. Go to **Courier Notices > Settings** to configure global options
2. Create your first notice under **Courier Notices > Add New**
3. Configure notice types and styles as needed

== Frequently Asked Questions ==

= Does this plugin work with Full Page Caching or WordPress Object Caching? =

Yes! Courier Notices is fully compatible with caching plugins.

= I'm looking for a specific type of notice that courier doesn't have, what's next? =

Courier Notices is highly extensible and our team is constantly adding new features. You can:
* Create a pull request on GitHub
* Submit an issue with your feature request
* Check out the Pro version for additional features

= Can I customize the styling of the notices? =

Yes! You can customize colors and icons through the admin interface, or disable the default CSS and add your own custom styles.

= Does this plugin work with my theme? =

Courier Notices is designed to work with any WordPress theme. It uses standard WordPress hooks and can be placed anywhere using CSS selectors.

= Can I display notices only to specific users? =

Yes, you can set notices to display globally or target specific user roles and individual users.

= Is there a limit to how many notices I can create? =

No, you can create as many notices as you need. However, for performance reasons, we recommend keeping concurrent notices to a reasonable number.

= Can I use Courier Notices with other plugins? =

Absolutely! Courier Notices is designed to work alongside other plugins and won't conflict with most WordPress plugins.

== Screenshots ==

1. List of "Types" of Informational Courier Notices within the WordPress admin
2. Editing colors and icons of a Courier Notice
3. Frontend display of a few Courier Notices, including dismissible notices.
4. Frontend display of a modal Courier Notice.

== Changelog ==

See changelog https://github.com/linchpin/courier-notices/blob/master/CHANGELOG.md

== Shortcodes ==

**Note:** Shortcodes are deprecated in favor of blocks in the upcoming version 2.0

`[courier_notice id="1"]`

Displays a Courier notice based on the Courier post ID, displays a flag to call out the notice as well.

**Parameters:**

* `id` - The ID of the notice to display

== Upgrade Notice ==

= 1.9.9 =

This version includes performance improvements and bug fixes. Update for the best experience.
