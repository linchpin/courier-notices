# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.8](https://github.com/linchpin/courier-notices/compare/v1.4.7...v1.4.8) (2022-09-02)


### Bug Fixes

* build paths were incorrect from migrating ([e5cf620](https://github.com/linchpin/courier-notices/commit/e5cf620c1555f27758a2fecdaa57f6c7a6a682d5))

## [1.4.7](https://github.com/linchpin/courier-notices/compare/v1.4.6...v1.4.7) (2022-09-02)


### Features

* Add documentation ([41c0792](https://github.com/linchpin/courier-notices/commit/41c0792c48243a9c279c2424d99ca0dba13fda72))


### Bug Fixes

* **deps:** update npm ([819c875](https://github.com/linchpin/courier-notices/commit/819c87533d6c68f6cb7b91a4fbc0a0db401083f0))
* **Model:** Fixing a fatal error when referencing settings ([cca0ad1](https://github.com/linchpin/courier-notices/commit/cca0ad19b7a794388480bf7b342438425be23292))


### Miscellaneous Chores

* build assets ([9b94151](https://github.com/linchpin/courier-notices/commit/9b94151d9222ffe369484e6558565bb7199370d7))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2.1.1 ([1b3cb5a](https://github.com/linchpin/courier-notices/commit/1b3cb5a77c9a83797edb1ca9dd11b0646b62da8f))
* **deps:** update dependency glob-parent to v6 ([b181bb6](https://github.com/linchpin/courier-notices/commit/b181bb60a70a0ed32a7419cf4e01283280b5a32c))
* **deps:** update node.js to v16 ([4802101](https://github.com/linchpin/courier-notices/commit/48021017e36559e1fefff2de2ead74910c8426c2))
* **deps:** update peter-evans/create-pull-request action to v4 ([a6deef5](https://github.com/linchpin/courier-notices/commit/a6deef5042e319a8e28e94ffb1707b9ac7e92c68))
* generate build assets ([a739466](https://github.com/linchpin/courier-notices/commit/a739466f0e7d6f34f6a6bc1a9738714bada03794))
* Make sure additional files can be updated ([38334e1](https://github.com/linchpin/courier-notices/commit/38334e12e6b32c6be810f5e9d379a5e314607952))
* update lock file ([9b94151](https://github.com/linchpin/courier-notices/commit/9b94151d9222ffe369484e6558565bb7199370d7))
* Updated timestamp of plugin update ([38334e1](https://github.com/linchpin/courier-notices/commit/38334e12e6b32c6be810f5e9d379a5e314607952))

## [Unreleased]

## [1.4.6] - 2022-05-24

### Fixes

- fix: Fixed an issue where user_id was not being passed in the request for notices

## [1.4.5] - 2022-03-24

### Fixes
- fixed a minor issue with deployments having a nested zip (of the deployment)

## [1.4.4] - 2022-02-10

### Updated
- improve the build process
- updated all dev dependencies

### Fixes
- fixed an issue with releases not being created properly on GitHub

## [1.4.3] - 2022-02-10
- Updated - Removed an extra trigger click on courier-close that could fire a redirect if multiple were used

## [1.4.2] - 2022-02-09
- Fixed - Cleanup build process

## [1.4.1] - 2022-02-09
- Fixed - Swapped colors for feedback and info notices
- Fixed - Allowing trigger-close class to fire for logged-out users
- Updated - Adjusted welcome panel classes to not rely on WordPress's default welcome styles
- Updated - Adjusted notice metabox to make sure data is an array before imploding
- Updated - Increased maximum visible notices from 4 to 10
- Updated - All dependencies
- Removed - Unneeded resolutions

## [1.4.0]
- Added - WordPress PHPCS Coding Standards Autofixer
- Fixed - Adjusted an in_array check to not compare with a string
- Improved - Allowed buttons within a notice to dismiss and change window location without needed ajax
- Updated - Adjusted default maximum of notices displayed
- Updated - Bumped minimum PHP requirement to 7.3
- Updated - Plugin description, github slugs, and homepage url

## [1.3.1]
- Fixed a javascript issue impacting other plugins
- Updated - All javascript dependencies have been updated ot the latest versions
- Updated - Minor security fixes

## [1.3.0]
- Added - REST endpoint for saving settings changes (preparation for new UI in future versions)
- Added - Settings now utilize ajax/REST for updating settings
- Added - New Feature. You can now choose to display the title of your Courier Notice for all Styles of notices.
- Added - Added some FAQs to the readme.txt
- Added - FAQ.md that is updated and generated from the readme.txt
- Added - Some new screenshots were added so users can get a sense of what Courier Notices look like by default.
- Updated - Popup/Modal notices now only show 1 at a time!
- Fixed - Minor display issue for "Sub Tabs" within the Settings area of the WordPress admin
- Fixed - Cleaned up spacing of Headers and Sub Titles within Settings to make things easier to read.
- Fixed - Numerous nitpicky CSS things that you may or may not notice (margins, column spacing and more)
- Fixed - When adding a new "type" of "Informational" Courier notices, the color pickers were not initializing properly
- Fixed - Quality of life fix: Removed theme based "editor styles" on courier notices

## [1.2.7]
- Updated - Window load for modal placement

## [1.2.6]
- Fixed - Fixed issue with modal dialogs displaying multiple times due to intersectObserver

## [1.2.5]
- Updated - Options namespace

## [1.2.4]
- Fixed - Issue with 403 error "rest_cookie_invalid_nonce" for logged out users.
- Updated - Banner shown in the WordPress.org plugin directory.

## [1.2.3]

- Fixed - issue with notice placement (whoops)

## [1.2.1]

- Updated sanitization to match wordpress.org audit.

## [1.2.0]

- Updated - Namespace changed from courier to courier-notices due to plugin conflict on wordpress.org
- Fixed   - Duplicate modal/popup issue
- Submission to wordpress.org

## [1.1.4]

- Fixed - Fatal error when assigning data to a template view

## [1.1.3]

- Fixed - Icon font specificity

## [1.1.2]

- Remove - Notice font styles, allow styling to inherit from theme

## [1.1.1]

- Fixed - Issue with default styles not being created on install
- Fixed - Security updates provided by github audit

## [1.1.0]

- Fixed - Minor security updates
- Fixed - Minor code cleanup
- Fixed - Link to Types/Design was broken
- Fixed - Link to Settings was broken
- Fixed - Minor updates to strings to allow for translation
- Fixed - Modal notice was not working properly (dismissible)
- Fixed - Error log was being utilized and should not have been
- Fixed - Cron was running hourly and not every 5 minutes
- Fixed - Various typos (We talk pretty one day)
- Fixed - utilizing iris wpColorPicker (For the time being)
- Fixed - Fixed an issue with color changes in the design panel did not show until page refresh
- Added - New UI/UX for creating and styling "Types" of notices
- Added - Courier actually has some branding now
- Added - Default data on plugin activation
- Added - Utility method to sanitize kses content
- Added - Cleaned up CSS across the entire plugin
- Added - New cron schedule (Every 5 minutes)
- Added - New taxonomy for "Style of Notice". This will allow for all different kinds of notices in the future
- Added - Base for CRUD in the future. Mainly just R right now.
- Improved - Added more flexibility to how tabs and subtabs can extend the plugin
- Improved - CSS is only generated and output if CSS is not disabled
- Improved - Placement logic is more flexible now
- Improved - UI/UX to show different notice options depending on other selections
- Improved - How css and javascript is enqueued based on context of admin
- Improved - Code Organization
- Improved - Templates
- Improved - Updated the expiration of notices to increment every 5 minutes for better accuracy and less stress on servers

## [1.0.4]

- Cleaned up deployment process further.

## [1.0.2]

- Migrated to using composer as our autoloader instead of a proprietary one
- Added Parsedown dependency for Markdown display within the plugin
- Added a changelog.md display to the settings page as a tab
- Added more automation for release to get releases out the door quicker
- Minor code formatting changes

## [1.0.1]

- Updated dependencies based on github security notification

## [1.0.0]

Initial Release

- Cleaned up UI for date and time selection.
- You can no longer select an expiration date from the past.
- Implemented datetimepicker so time selection is easier.
- Minor typo fix in admin area.
- Minor data sanitization/security hardening.
