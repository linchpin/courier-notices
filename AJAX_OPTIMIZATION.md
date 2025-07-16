# Courier Notices AJAX Optimization

## Overview

This optimization prevents unnecessary AJAX calls to the server when no notices exist. Previously, the plugin would always make an AJAX call to check for notices, even when the database had no published notices. This resulted in unnecessary server load and network traffic.

## How It Works

### 1. Backend Check
- A new function `courier_notices_has_any_notices()` checks if any published notices exist in the database
- This check is performed on the server side when the page loads
- The result is cached for 5 minutes to avoid repeated database queries

### 2. Frontend Prevention
- The result of the check is passed to the frontend via the localized script data
- If `has_notices` is `false`, the JavaScript code exits early without:
  - Setting up IntersectionObserver
  - Making any AJAX calls
  - Processing any notice containers

### 3. Cache Management
- The `has_notices` cache is automatically cleared when:
  - A new notice is created
  - A notice is updated or deleted
  - The general cache is cleared

## Performance Benefits

1. **Zero AJAX calls** when no notices exist
2. **Reduced server load** from unnecessary database queries
3. **Faster page load** due to no network requests
4. **Better user experience** with no unnecessary processing

## Code Changes

### PHP Changes

1. **New Function** in `includes/Helper/Functions.php`:
   ```php
   function courier_notices_has_any_notices( $user_id = 0 )
   ```

2. **Updated Script Localization** in `includes/Controller/Courier_Notices.php`:
   ```php
   'has_notices' => courier_notices_has_any_notices( get_current_user_id() )
   ```

3. **Cache Clearing** in `includes/Helper/Functions.php`:
   - Updated `courier_notices_clear_cache()` to also clear the `has_notices` cache

### JavaScript Changes

1. **Early Exit Check** in `assets/js/frontend/core.js`:
   ```javascript
   // Check if any notices exist at all
   if ( ! courier_notices_data.has_notices ) {
       // No notices exist, so don't set up observers or make AJAX calls
       return;
   }
   ```

## Testing

You can test this optimization by:

1. **Check browser console** - Look for the message about whether AJAX calls will be made
2. **Monitor network tab** - Verify no calls to `/wp-json/courier-notices/` endpoints when no notices exist
3. **Create a notice** - Verify AJAX calls resume when notices are published
4. **Delete all notices** - Verify AJAX calls stop again

## Backward Compatibility

This optimization is fully backward compatible:
- If the `has_notices` flag is not present, the code behaves as before
- The optimization only affects frontend behavior, not the API or notice display logic
- All existing functionality remains unchanged