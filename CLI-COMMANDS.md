# Courier Notices WP-CLI Commands

This document describes the WP-CLI commands available for managing Courier Notices from the command line.

## Overview

The Courier Notices plugin provides a comprehensive set of WP-CLI commands under the `courier-notices` namespace for creating, managing, and testing notices programmatically. These commands are automatically registered when WP-CLI is available and the plugin is active.

## Available Commands

### `wp courier-notices create`

Create a new Courier notice with optional expiration.

#### Usage

```bash
wp courier-notices create <title> <content> [options]
```

#### Arguments

- `<title>` - The notice title (required)
- `<content>` - The notice content (required)

#### Options

- `--expires=<minutes>` - Minutes from now when the notice should expire (default: no expiration)
- `--type=<type>` - Notice type (default: Info)
- `--style=<style>` - Notice style (default: Informational)
- `--placement=<placement>` - Notice placement (default: header)
- `--global` - Make this a global notice (default: false)
- `--dismissible` - Make the notice dismissible (default: true)
- `--user-id=<user-id>` - User ID for user-specific notice (default: 0 for global)

#### Examples

```bash
# Create a simple notice
wp courier-notices create "Welcome Message" "Welcome to our site!"

# Create a notice that expires in 5 minutes
wp courier-notices create "Temporary Notice" "This will expire soon" --expires=5

# Create a global notice with custom type
wp courier-notices create "Important Update" "Please read this" --global --type="Warning" --expires=30

# Create a user-specific notice
wp courier-notices create "Personal Message" "This is for you" --user-id=123 --expires=60
```

### `wp courier-notices expire`

Expire a notice by ID.

#### Usage

```bash
wp courier-notices expire <notice-id> [options]
```

#### Arguments

- `<notice-id>` - The ID of the notice to expire (required)

#### Options

- `--force` - Force expiration even if the notice is already expired

#### Examples

```bash
# Expire a notice
wp courier-notices expire 123

# Force expire a notice
wp courier-notices expire 123 --force
```

### `wp courier-notices list`

List Courier notices with various filtering options.

#### Usage

```bash
wp courier-notices list [options]
```

#### Options

- `--status=<status>` - Filter by status (publish, courier_expired, draft, etc.)
- `--type=<type>` - Filter by notice type
- `--placement=<placement>` - Filter by placement
- `--expired` - Show only expired notices
- `--active` - Show only active (non-expired) notices
- `--format=<format>` - Output format (table, csv, json, count)
- `--fields=<fields>` - Comma-separated list of fields to display

#### Examples

```bash
# List all notices
wp courier-notices list

# List only expired notices
wp courier-notices list --expired

# List notices in JSON format
wp courier-notices list --format=json

# List notices with specific fields
wp courier-notices list --fields=ID,Title,Status,Expires

# List notices by type
wp courier-notices list --type="Warning"
```

### `wp courier-notices get`

Get detailed information about a specific notice.

#### Usage

```bash
wp courier-notices get <notice-id> [options]
```

#### Arguments

- `<notice-id>` - The ID of the notice to get information about (required)

#### Options

- `--format=<format>` - Output format (table, json)

#### Examples

```bash
# Get notice information
wp courier-notices get 123

# Get notice information in JSON format
wp courier-notices get 123 --format=json
```

## Testing Action Scheduler Integration

### Creating Test Notices

```bash
# Create a notice that expires in 2 minutes
wp courier-notices create "Test Notice" "This will expire in 2 minutes" --expires=2 --global

# Create multiple test notices with different expiration times
wp courier-notices create "Test 1" "Expires in 1 minute" --expires=1 --global
wp courier-notices create "Test 2" "Expires in 5 minutes" --expires=5 --global
wp courier-notices create "Test 3" "Expires in 10 minutes" --expires=10 --global
```

### Monitoring Expiration

```bash
# List all notices to see their status
wp courier-notices list

# List only active notices
wp courier-notices list --active

# List only expired notices
wp courier-notices list --expired

# Get detailed info about a specific notice
wp courier-notices get 123
```

### Manual Testing

```bash
# Manually expire a notice for testing
wp courier-notices expire 123

# Check Action Scheduler status
wp action-scheduler list

# Run Action Scheduler manually
wp action-scheduler run
```

## Integration with Action Scheduler

When you create a notice with expiration using the CLI commands:

1. **Action Scheduler Available**: The notice expiration is automatically scheduled via Action Scheduler
2. **Action Scheduler Not Available**: The notice uses WordPress Cron for expiration
3. **Manual Expiration**: The `expire` command properly cleans up any scheduled actions

## Output Formats

The `list` and `get` commands support multiple output formats:

- `table` - Human-readable table (default)
- `csv` - Comma-separated values
- `json` - JSON format for scripting
- `count` - Just the count of results

## Error Handling

The CLI commands include comprehensive error handling:

- Validates all input parameters
- Checks for notice existence
- Provides clear error messages
- Handles Action Scheduler integration gracefully

## Use Cases

### Development Testing

```bash
# Create test notices for development
wp courier-notices create "Dev Test" "Testing expiration" --expires=1 --global

# Monitor expiration
wp courier-notices list --expired
```

### Production Management

```bash
# Create important notices
wp courier-notices create "System Maintenance" "Site will be down for maintenance" --global --type="Warning" --expires=1440

# List all active notices
wp courier-notices list --active
```

### Automation

```bash
# Create notices from scripts
wp courier-notices create "Auto Notice" "Created by script" --expires=30 --global

# Get notice info for processing
wp courier-notices get 123 --format=json
```

## Troubleshooting

### Common Issues

1. **Plugin Not Active**: Ensure Courier Notices plugin is activated
2. **Invalid Notice ID**: Use `wp courier-notices list` to find valid notice IDs
3. **Permission Issues**: Ensure you have proper WordPress permissions
4. **Action Scheduler Issues**: Check if Action Scheduler is available with `wp eval "echo class_exists('ActionScheduler') ? 'Available' : 'Not Available';"`

### Debug Commands

```bash
# Check if plugin is active
wp eval "echo function_exists('courier_notices_add_notice') ? 'Active' : 'Not Active';"

# Check Action Scheduler availability
wp eval "echo class_exists('ActionScheduler') ? 'Available' : 'Not Available';"

# List all notice types
wp eval "print_r(get_terms(['taxonomy' => 'courier_type', 'hide_empty' => false]));"
``` 