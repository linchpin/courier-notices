# Courier Notices Block Editor Implementation

## Overview

This implementation adds comprehensive Block Editor support to the Courier Notices plugin, allowing users to create rich notice content using WordPress blocks and place notice blocks directly in pages and posts.

## Features Implemented

### 1. Settings Integration
- Added a new setting "Enable Block Editor for Courier Notices" in the General Settings
- When enabled, allows the use of Block Editor for creating courier notices
- Maintains backward compatibility - existing functionality is preserved

### 2. Block Types Created

#### Courier Notices Container Block
- **Name**: `courier-notices/courier-notices-container`
- **Purpose**: Parent container that holds multiple courier notice blocks
- **Features**:
  - Configurable placement (header, footer, modal, inline)
  - Supports all common block settings (spacing, colors, typography, borders)
  - Uses InnerBlocks to contain individual notice blocks

#### Courier Notice Block  
- **Name**: `courier-notices/courier-notice`
- **Purpose**: Individual notice with customizable content and settings
- **Features**:
  - Rich text content editing
  - Notice type selection (informational, success, warning, error)
  - Notice style selection (informational, modal, banner)
  - Scope settings (global, user-specific, role-specific)
  - Dismissible toggle
  - Optional title display
  - Custom icon class support
  - WordPress Interactivity API integration for frontend behavior

### 3. WordPress Interactivity API Integration

The blocks use the modern WordPress Interactivity API for frontend functionality:
- **Smooth animations** for notice display and dismissal
- **State management** for tracking dismissed notices
- **Event system** for integration with other scripts
- **Server-side synchronization** for persistent dismissals
- **Graceful degradation** when JavaScript is disabled

### 4. Design & Styling

- **Editor styles**: Custom CSS for block editor experience
- **Frontend styles**: Inherits from existing Courier Notices styles
- **Type-specific styling**: Different colors/borders for notice types
- **Responsive design**: Works on all device sizes
- **Animation support**: Smooth transitions for show/hide

### 5. Technical Architecture

#### File Structure
```
assets/js/blocks/
├── blocks.js                           # Main entry point
├── shared/
│   └── utils.js                        # Shared utilities
├── courier-notices-container/
│   ├── index.js                        # Container block logic
│   ├── block.json                      # Block metadata
│   ├── editor.css                      # Editor styles
│   └── style.css                       # Frontend styles
└── courier-notice/
    ├── index.js                        # Notice block logic
    ├── block.json                      # Block metadata
    ├── editor.css                      # Editor styles
    ├── style.css                       # Frontend styles
    └── view.js                         # Interactivity API script
```

#### PHP Controllers
- `includes/Controller/Blocks.php` - Manages block registration and assets
- Updated `includes/Controller/Admin/Settings/General.php` - Adds block editor setting
- Updated `includes/Model/Settings.php` - Includes default setting
- Updated `includes/Controller/Admin/Courier_Notice_Metabox.php` - Conditional block editor enabling

### 6. Build Process Integration

- Updated `webpack.config.js` to include blocks build
- New entry point: `courier-notices-blocks`
- Generates optimized JavaScript and CSS for blocks
- Includes dependency extraction for WordPress packages

## Usage Instructions

### For Administrators

1. **Enable Block Editor Support**:
   - Go to Courier Notices → Settings → General Settings
   - Check "Enable Block Editor for Courier Notices (experimental)"
   - Save settings

2. **Creating Notices with Blocks**:
   - When block editor is enabled, courier notice post type will use Block Editor
   - Traditional metabox interface is still available when block editor is disabled

3. **Using Blocks in Pages/Posts**:
   - Add "Courier Notices Container" block to any page/post
   - Configure placement (inline, header, footer, modal)
   - Add individual "Courier Notice" blocks inside the container
   - Customize each notice's type, style, content, and settings

### For Developers

1. **Extending Blocks**:
   - Blocks follow WordPress Block Editor best practices
   - Use `wp.hooks` API to modify block behavior
   - Extend the Interactivity API store for custom functionality

2. **Styling Customization**:
   - Blocks inherit existing Courier Notices styles
   - Override CSS in theme for custom styling
   - Use WordPress block supports for common styling options

3. **Interactivity Customization**:
   - Listen for `courierNoticeDismissed` events
   - Extend the `courier-notices` interactivity store
   - Add custom actions and callbacks

## Dependencies

### WordPress Packages Used
- `@wordpress/blocks` - Block registration
- `@wordpress/block-editor` - Block editor components  
- `@wordpress/components` - UI components
- `@wordpress/element` - React-like element creation
- `@wordpress/i18n` - Internationalization
- `@wordpress/data` - Data layer
- `@wordpress/compose` - Higher-order components
- `@wordpress/interactivity` - Frontend interactivity
- `@wordpress/core-data` - Core data entities
- `@wordpress/hooks` - Hooks system

## Browser Support

- Modern browsers supporting ES6+
- Graceful degradation for older browsers
- Progressive enhancement for JavaScript-disabled environments

## Performance Considerations

- Blocks only load when Block Editor setting is enabled
- Frontend scripts load only when blocks are present on page
- Optimized build process with code splitting
- Minimal CSS footprint (inherits existing styles)

## Future Enhancements

Potential future improvements could include:
- REST API integration for dynamic notice loading
- Advanced targeting options (device, geolocation, etc.)
- Template library for common notice patterns
- Integration with WordPress Full Site Editing
- Analytics and performance tracking
- A/B testing capabilities

## Compatibility

- **WordPress Version**: 5.7+ (follows plugin requirements)
- **PHP Version**: 7.4+ (follows plugin requirements)
- **Block Editor**: Full compatibility with WordPress 6.0+
- **Classic Editor**: Maintains full backward compatibility
- **Themes**: Works with any WordPress theme
- **Plugins**: Compatible with other block editor plugins