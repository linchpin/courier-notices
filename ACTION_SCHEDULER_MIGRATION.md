# Action Scheduler Migration Guide

## Overview

Courier Notices version 1.8.0 introduces a major improvement by migrating from WordPress's native WP Cron system to Action Scheduler for more reliable and scalable notice expiration management.

## What Changed

### Before (WP Cron)
- Bulk checking of all notices every 5 minutes
- Reliant on website traffic for execution
- Less reliable timing
- Higher server load during bulk operations

### After (Action Scheduler)
- Individual scheduling for each notice expiration
- Runs independently of website traffic
- More reliable timing and execution
- Better performance and scalability
- Built-in error handling and retry logic

## Installation Requirements

### Composer Dependency
This update adds Action Scheduler as a dependency. Run the following command to install:

```bash
composer install
```

### Manual Installation
If you cannot use Composer, you can manually download and include Action Scheduler:

1. Download Action Scheduler from https://github.com/woocommerce/action-scheduler
2. Extract to your `vendor/` directory
3. Update your autoloader to include Action Scheduler

## Migration Process

### Automatic Migration
The migration happens automatically when the plugin is updated to version 1.8.0:

1. **Cron Cleanup**: Existing WP Cron jobs are cleared
2. **Option Update**: Plugin is marked to use Action Scheduler
3. **Notice Rescheduling**: All existing notices with future expiration dates are migrated to Action Scheduler
4. **Recurring Actions**: Purge and bulk expire actions are set up as fallbacks

### Manual Migration
If you need to manually trigger the migration:

```php
// Get the upgrade controller
$upgrade = new CourierNotices\Controller\Upgrade();

// Run the migration
$upgrade->migrate_to_action_scheduler();
```

## New Features

### Individual Notice Scheduling
Each notice now gets its own scheduled action:
- More accurate expiration timing
- Less server load
- Better error handling

### Fallback Systems
- **Bulk Expire**: Runs hourly to catch any missed notices
- **Purge**: Runs daily to clean up old notices

### Admin Interface
- Migration success notice in admin area
- Debugging information for developers

## Developer Information

### New Action Hooks
```php
// Fired when a notice expires via Action Scheduler
do_action( 'courier_notices_notice_expired', $post_id );

// Fired when notices are purged via Action Scheduler  
do_action( 'courier_notices_notices_purged', $purged_ids );

// Fired after bulk expiration via Action Scheduler
do_action( 'courier_notices_bulk_expired', $expired_ids );
```

### New Controller Methods
```php
// Access the Action Scheduler controller
$action_scheduler = new CourierNotices\Controller\Action_Scheduler();

// Get scheduled actions for a notice
$actions = $action_scheduler->get_scheduled_actions_for_notice( $post_id );

// Get all scheduled actions
$all_actions = $action_scheduler->get_all_scheduled_actions();

// Cancel all scheduled actions
$action_scheduler->cancel_all_scheduled_actions();
```

### Constants and Settings
```php
// Action hooks
CourierNotices\Controller\Action_Scheduler::EXPIRE_NOTICE_ACTION
CourierNotices\Controller\Action_Scheduler::PURGE_NOTICES_ACTION
CourierNotices\Controller\Action_Scheduler::BULK_EXPIRE_ACTION

// Group name
CourierNotices\Controller\Action_Scheduler::SCHEDULER_GROUP
```

## Troubleshooting

### Action Scheduler Not Working
1. Verify Action Scheduler is installed: `function_exists( 'as_schedule_single_action' )`
2. Check if WordPress cron is disabled: `defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON`
3. Verify your server supports running background tasks

### Legacy Cron Still Running
If you need to force disable the legacy cron system:

```php
// Add to wp-config.php or functions.php
add_filter( 'courier_notices_use_legacy_cron', '__return_false' );
```

### Debugging
Enable debugging to see migration and scheduling information:

```php
// Add to wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```

## Performance Impact

### Improved Performance
- **Reduced server load**: No more bulk checking every 5 minutes
- **Better timing**: Individual scheduling is more accurate
- **Scalability**: Can handle thousands of notices efficiently

### Resource Usage
- **Database**: Minimal increase due to Action Scheduler tables
- **Memory**: Reduced memory usage during expiration checks
- **CPU**: More efficient processing

## Support

For issues related to Action Scheduler migration:

1. Check the Action Scheduler documentation
2. Review WordPress error logs
3. Verify all dependencies are installed
4. Test with a minimal plugin setup

## Changelog

### Version 1.8.0
- Added Action Scheduler dependency
- Migrated from WP Cron to Action Scheduler
- Improved notice expiration reliability
- Added individual notice scheduling
- Deprecated legacy cron system
- Added migration tools and documentation