# Courier Notices

Add dismissible and non-dismissible notices throughout your WordPress website.

<!-- x-release-please-start-version -->

## Latest Release: 1.9.15

<!-- x-release-please-end -->

## Description

Add dismissible and non-dismissible notices throughout your website.

- Customize the colors and icons used by your Courier Notices **no coding needed**
- Add new "types" of notices as needed
- Comes with a common library of useful notices (Modal, Header, Footer)
- Place courier notices within the header, footer or anywhere using CSS selectors (similar to selectors in jQuery)
- Display popover/page take over modal style notices
- Can be used in conjunction with other popular plugins

## Installation

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

## Screenshots

1. List of "Types" of Informational Courier Notices within the WordPress admin
2. Editing colors and icons of a Courier Notice
3. Frontend display of a few Courier Notices, including dismissible notices.
4. Frontend display of a modal Courier Notice.

## Changelog

See changelog https://github.com/linchpin/courier-notices/blob/master/CHANGELOG.md

## Shortcodes (deprecated) v2 will introduce blocks

`[courier_notice id="1"]`

Displays a Courier notice based on the Courier post ID, displays a flag to call out the notice as well.

Flag prepends a span containing the string in the flag parameter, to display no flag set "show_flag" to false.

![Linchpin](https://github.com/linchpin/brand-assets/blob/master/github-banner@2x.jpg)

## Usage Examples

### Creating a Notice Programmatically

```php
// Add a global success notice
courier_notices_add_notice(
    'Your order has been processed successfully!',
    ['success'],
    true, // global
    true, // dismissible
    0, // user_id (0 for global)
    'informational',
    ['header']
);
```

### Display Notices in Custom Locations

```php
// Display notices in a custom location
courier_notices_display_notices([
    'placement' => 'header',
    'style' => 'informational'
]);
```

## Configuration

### Notice Types

- **Primary**: General information (#039ad6)
- **Success**: Positive confirmations (#04a84e)
- **Alert**: Important warnings (#f97600)
- **Warning**: Critical alerts (#ea3118)
- **Feedback**: Form responses (#8839d3)
- **Info**: General information (#878787)

### Placement Options

- **Header**: Displays at the top of the page
- **Footer**: Fixed position at the bottom
- **Modal**: Full-screen overlay popup
- **Custom**: Use CSS selectors for specific placement

### Settings

- **AJAX Loading**: Enable for better caching compatibility
- **CSS Disable**: Option to disable default styles
- **Title Display**: Control notice title visibility

## Performance & Compatibility

### Caching Compatibility

✅ **Full Page Caching**: Works with all major caching plugins when AJAX loading is enabled
✅ **Object Caching**: Compatible with Redis, Memcached, and other object caches
✅ **CDN Support**: Works seamlessly with content delivery networks

### Performance Features

- **Smart Loading**: Only loads notices when containers are visible
- **Consolidated Requests**: Single AJAX call for multiple notice placements
- **Intelligent Caching**: 5-minute object cache + 10-minute transient cache
- **Early Exit**: Skips processing when no notices exist

### Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile responsive design
- Progressive enhancement approach

## Developer Resources

### REST API Endpoints

- `GET /wp-json/courier-notices/v1/notices/display/` - Get notices for specific placement
- `GET /wp-json/courier-notices/v1/notices/display/all/` - Get all notices for multiple placements
- `POST /wp-json/courier-notices/v1/notice/dismiss/` - Dismiss a specific notice

### Available Filters

- `courier_notices_get_notices_default_settings` - Modify default notice query settings
- `courier_notices_localized_data` - Customize JavaScript data
- `courier_notices` - Filter notice output HTML

### Available Actions

- `courier_notices_before_metabox_content` - Add content before notice metabox
- `courier_notices_after_settings_init` - Hook into settings initialization

## Troubleshooting

### Notices Not Appearing

1. Check if notices are published and not expired
2. Verify placement settings match your theme structure
3. Enable AJAX loading if using caching plugins
4. Check browser console for JavaScript errors

### Performance Issues

1. Enable AJAX loading in settings
2. Consider reducing the number of concurrent notices
3. Check for conflicting JavaScript on your site

### Styling Issues

1. Disable default CSS and add custom styles if needed
2. Check for theme CSS conflicts
3. Use browser developer tools to inspect notice elements
