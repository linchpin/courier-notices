# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5.6](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.5...courier-notices-v1.5.6) (2023-02-06)


### Bug Fixes 🐛

* **#307:** update check for expiration nonce ([6d6ca3f](https://github.com/linchpin/courier-notices/commit/6d6ca3fa2a45f1f7a9a480c54cbd469b8bb68d47))

## [1.5.5](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.4...courier-notices-v1.5.5) (2022-11-14)


### Bug Fixes 🐛

* **#297:** Send just array values to resolve fatal in PHP8 ([d24fb6b](https://github.com/linchpin/courier-notices/commit/d24fb6b2d40f027eeb0eef03a20690fef70c4875))
* **#298:** Check if title rules array exists ([4c6822f](https://github.com/linchpin/courier-notices/commit/4c6822f6ab7d97f7abb493cfa6991621474528ab))

## [1.5.4](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.3...courier-notices-v1.5.4) (2022-11-03)


### Bug Fixes 🐛

* **#265:** debug modal firing consistently, clean-up PHP warnings ([cc35877](https://github.com/linchpin/courier-notices/commit/cc3587781d999b45c4f15fcab58bc9ff1fd5ef81))

## [1.5.3](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.2...courier-notices-v1.5.3) (2022-11-01)


### Miscellaneous Chores 🧹

* **NO-JIRA:** Touch file to create build ([fa420fd](https://github.com/linchpin/courier-notices/commit/fa420fd02c48e1838f5c90bce6590895efe9154e))

## [1.5.2](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.1...courier-notices-v1.5.2) (2022-10-31)


### Bug Fixes 🐛

* **NO-JIRA:** missing dependencies ([ce8a93c](https://github.com/linchpin/courier-notices/commit/ce8a93c94dba842c6881c3c7a91bfd8d520eb02f))

## [1.5.1](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.0...courier-notices-v1.5.1) (2022-09-12)


### Changes to Existing Features 💅

* Updated the distigore to remove unneeded files ([576d020](https://github.com/linchpin/courier-notices/commit/576d020afb6d8e9bc08d134181bd861cf6234f0f))
* Updated the release please workflow ([6844a9f](https://github.com/linchpin/courier-notices/commit/6844a9f7a0556ef99bc3fdb43b77c63c159394ea))

## [1.5.0](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.4.7...courier-notices-v1.5.0) (2022-09-07)


### Reverts ⏪️

* cant spend time looking at coding standards ([6ce4874](https://github.com/linchpin/courier-notices/commit/6ce48748c8ce1ffc6c672b670a528f63ff1448ad))


### Features ✨

* Add documentation ([41c0792](https://github.com/linchpin/courier-notices/commit/41c0792c48243a9c279c2424d99ca0dba13fda72))
* Added controls for toggling show/hide of titles within the admin javascript (Classic Editor) [#93](https://github.com/linchpin/courier-notices/issues/93) ([5a445d0](https://github.com/linchpin/courier-notices/commit/5a445d0b019cdf65cfb2e8b5d0a7efbd0d74b902))
* Added css for admin UI elements related to toggling show/hide of notice titles [#93](https://github.com/linchpin/courier-notices/issues/93) ([a6ff5c0](https://github.com/linchpin/courier-notices/commit/a6ff5c060a3c5e9f6d67dcba595f50afce5635da))
* Making it so modals will only show 1 at a time ([f812325](https://github.com/linchpin/courier-notices/commit/f8123251013646a94ce8054d9f950c77fec36a98))
* Making modals more efficient. ([f812325](https://github.com/linchpin/courier-notices/commit/f8123251013646a94ce8054d9f950c77fec36a98))
* Split global design styles and "Notice Style" styles into different views. ([3664791](https://github.com/linchpin/courier-notices/commit/3664791a044cd5c2ef7258df22668f8831f98d38))
* Updated how modals work to only show one at a time ([2b599da](https://github.com/linchpin/courier-notices/commit/2b599da50d2775828ceeab3d9839bf0e4c6d6f5f))


### Changes to Existing Features 💅

* adding coding standards scanning ([ee48930](https://github.com/linchpin/courier-notices/commit/ee489300d2474a938d6d96d0da7e29e39fcb517c))
* Adjusted same notice type stacking to use a divider as a before to work better with coloring above bar and icon background coloring ([c8b33d7](https://github.com/linchpin/courier-notices/commit/c8b33d7e4deadcdaa578d079afbe899c9265f4c4))
* adjusting welcome panel classes to not rely on WordPress's default welcome styles ([67743f6](https://github.com/linchpin/courier-notices/commit/67743f640e94ee11f8146ab6fa7efccc6ace2150))
* **build:** improve/cleanup the build process ([1307e09](https://github.com/linchpin/courier-notices/commit/1307e0913252abde61fe45df76f8032d1f719cfa))
* **deploy:** Improve how deployments work [#262](https://github.com/linchpin/courier-notices/issues/262) ([e7896af](https://github.com/linchpin/courier-notices/commit/e7896afc09891d0143d8e859b54f2fecfc7b01c6))
* Improve workflows for coding standards ([0286a0b](https://github.com/linchpin/courier-notices/commit/0286a0becbfab220eb07aae080114eb1ffbcdede))
* Improved the build process for the plugin to be more streamlined ([07d963c](https://github.com/linchpin/courier-notices/commit/07d963c5d1774298f824487bade7ec466bd312d9))
* Remove ajax toggle from admin ([e266529](https://github.com/linchpin/courier-notices/commit/e26652977db3a014d3b9e9826ad12bc2d005d34e))
* removed a bunch of deprecated instances of division being down outside calc() ([9b94151](https://github.com/linchpin/courier-notices/commit/9b94151d9222ffe369484e6558565bb7199370d7))
* removing an extra trigger click on courier-close / trigger-close that was firing a redirect if an href was used ([0d6503e](https://github.com/linchpin/courier-notices/commit/0d6503ee32c613f3d4f40aa3398675ad96c8fa1e))
* removing unneeded action ([403b76d](https://github.com/linchpin/courier-notices/commit/403b76db3b7e90438a2e992ee97b408d70c31679))
* Replaced node-sass library with "sass" (dart sass) implementation ([9b94151](https://github.com/linchpin/courier-notices/commit/9b94151d9222ffe369484e6558565bb7199370d7))


### Bug Fixes 🐛

* add "hide" css class to hide elements within the modal overlay when needed ([3253349](https://github.com/linchpin/courier-notices/commit/3253349f641ba1875ebda4abac1e2bb3984c6f12))
* added show/hide title to default template ([a3a2f3c](https://github.com/linchpin/courier-notices/commit/a3a2f3c093f37706fead93474f84c8b86885432d))
* **admin:** Adding in missing semicolons ([bf1c70f](https://github.com/linchpin/courier-notices/commit/bf1c70f7f7daa7853bf875733173d01948317f59))
* **admin:** Make sure $selected_styles is an array before running in_array, adding link to Global Design Settings page in the callout ([e725427](https://github.com/linchpin/courier-notices/commit/e7254278c2b26ca87880eaeda3aaf48c0ceff1b2))
* **admin:** Removed courier_notice post type specific javascript being enqueued on all post edit pages. ([140a208](https://github.com/linchpin/courier-notices/commit/140a208a8d4d6f1f5977b172600d55387956c6b4))
* Allow trigger-close to fire dismissal and window.location even if ajax isn't being called, add ajaxComplete method specifically to user_id after post ([dcb159a](https://github.com/linchpin/courier-notices/commit/dcb159aeb8e3d93ec748a1963b48ce8656aef34d))
* build paths were incorrect from migrating ([e5cf620](https://github.com/linchpin/courier-notices/commit/e5cf620c1555f27758a2fecdaa57f6c7a6a682d5))
* **deps:** update npm ([819c875](https://github.com/linchpin/courier-notices/commit/819c87533d6c68f6cb7b91a4fbc0a0db401083f0))
* fixed an incorrect selector when adding a new informational notice type ([58f17b1](https://github.com/linchpin/courier-notices/commit/58f17b1265e262af8ae39e35307fb006a06c36ab))
* Fixed an issue where the link to design was incorrect due to other updates ([063b88f](https://github.com/linchpin/courier-notices/commit/063b88f62ca2e5e44fac32c9eb3a064c8fcb9762))
* fixed an issue with releases not being created properly on GitHub ([1307e09](https://github.com/linchpin/courier-notices/commit/1307e0913252abde61fe45df76f8032d1f719cfa))
* Fixed an issue with zips being nested within the deployment ([4ea6f32](https://github.com/linchpin/courier-notices/commit/4ea6f32881fc756e5b1895bf49051da7c0b6e016))
* fixed undefined show_hide_title within templates ([c214831](https://github.com/linchpin/courier-notices/commit/c2148310fd035c1c84b89553784bf52e13827f91))
* Fixing an issue with vendor assets that are committed in the repo incorrectly ([9cf10f8](https://github.com/linchpin/courier-notices/commit/9cf10f80a50f44578cdf88dc33368afefeecaae4))
* Fixing an issue with vendor assets that are committed in the repo incorrectly ([5bd4f5c](https://github.com/linchpin/courier-notices/commit/5bd4f5c6cb36fc4d257dd8880f018edbafbfb920))
* Fixing an issue with vendor assets that are committed in the repo incorrectly ([a51aed5](https://github.com/linchpin/courier-notices/commit/a51aed559964e2a39d8264cd753e72f4b66a379d))
* incorrectly looking for delete_transient instead of delete_option ([d100bce](https://github.com/linchpin/courier-notices/commit/d100bceefa3e5f67b94be35d04b376b7c9ae0bed))
* invalid workflow ([a7cf5f2](https://github.com/linchpin/courier-notices/commit/a7cf5f29ded0b94c60dcb9a2bce571edf3b30aea))
* modal notices were skipping display of first notice in some circumstances ([a0bf6e0](https://github.com/linchpin/courier-notices/commit/a0bf6e0668441ef1bda8fe0343bf1fd294b7b9b7))
* **Model:** Fixing a fatal error when referencing settings ([cca0ad1](https://github.com/linchpin/courier-notices/commit/cca0ad19b7a794388480bf7b342438425be23292))
* No build task was available ([caf2a8e](https://github.com/linchpin/courier-notices/commit/caf2a8eda38a9f356e67f94b7a08bbe612a477d8))
* Pass user_id within request for notices ([ecd5fdd](https://github.com/linchpin/courier-notices/commit/ecd5fdde093b1924c22a0bb12b827e256a997945))
* removing node specific checks ([d79229f](https://github.com/linchpin/courier-notices/commit/d79229f5414995e15e766134bc5d9004e084a96a))
* removing node specific checks ([bffe3db](https://github.com/linchpin/courier-notices/commit/bffe3db590ccfe95d70f0d0109e48a440a7f2796))
* replaced a deprecated method ([a3a2f3c](https://github.com/linchpin/courier-notices/commit/a3a2f3c093f37706fead93474f84c8b86885432d))
* **workflow:** Adding composer install to master workflow ([bccf1be](https://github.com/linchpin/courier-notices/commit/bccf1be5b591d27ac6836ea8be3a7c010087eaf1))


### Miscellaneous Chores 🧹

* added screenshots ([c214831](https://github.com/linchpin/courier-notices/commit/c2148310fd035c1c84b89553784bf52e13827f91))
* Adding brand assets to readme ([c6d87fe](https://github.com/linchpin/courier-notices/commit/c6d87fe483a338225bbbc7f33be8ce5d03efeb13))
* build assets ([9b94151](https://github.com/linchpin/courier-notices/commit/9b94151d9222ffe369484e6558565bb7199370d7))
* Bumping all dependencies. ([31b04ae](https://github.com/linchpin/courier-notices/commit/31b04ae5dddb6a2dd7fd0c9b02824780e5f0925b))
* bumping dependencies ([1ca901e](https://github.com/linchpin/courier-notices/commit/1ca901e60fc264ec8a0db05454386abbc992b8ab))
* bumping some dependencies ([ee48930](https://github.com/linchpin/courier-notices/commit/ee489300d2474a938d6d96d0da7e29e39fcb517c))
* **changelog:** Release v1.4.0 ([6ca255c](https://github.com/linchpin/courier-notices/commit/6ca255cb94e6d93140b1c6c30ed0462400e32080))
* **changelog:** v1.4.0 Release information ([109bcb7](https://github.com/linchpin/courier-notices/commit/109bcb78401c974564442cdd1a79d264ea45be32))
* composer updates ([07792b7](https://github.com/linchpin/courier-notices/commit/07792b7215218b6ffbbb1f0f3c4aff0a6712b353))
* composer updates ([e4432a1](https://github.com/linchpin/courier-notices/commit/e4432a1afb6b385d05d42d8cf72f1221c4dfaff1))
* configuring extra-files ([c1226c8](https://github.com/linchpin/courier-notices/commit/c1226c8f4f72682bc913996106a68fc2778f2b98))
* **deps:** bump browserslist from 4.16.4 to 4.20.3 ([c597b21](https://github.com/linchpin/courier-notices/commit/c597b2166eac1808ad9a6de7f4d6ac00efc40f15))
* **deps:** bump follow-redirects from 1.13.3 to 1.14.8 ([f1fc483](https://github.com/linchpin/courier-notices/commit/f1fc483b71fd9b05ebde6e2030060f3ddf5c6034))
* **deps:** bump minimist from 1.2.5 to 1.2.6 ([324a73a](https://github.com/linchpin/courier-notices/commit/324a73a16cc28bd47bd354ba29cb328db9c49769))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2 ([413a0be](https://github.com/linchpin/courier-notices/commit/413a0beb9ce279a2de99a95df96c6115b91cac09))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2.1.0 ([ec5f773](https://github.com/linchpin/courier-notices/commit/ec5f7733a9b35c9237c58eb825bd03dafeb9ffad))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2.1.1 ([1b3cb5a](https://github.com/linchpin/courier-notices/commit/1b3cb5a77c9a83797edb1ca9dd11b0646b62da8f))
* **deps:** update actions/cache action to v3 ([8d999eb](https://github.com/linchpin/courier-notices/commit/8d999eb606e3b3ff1ccb69b9f0269c0e443f98d4))
* **deps:** update actions/checkout action to v3 ([a1bee17](https://github.com/linchpin/courier-notices/commit/a1bee17a67a593aecb4f6041aea6980b27edc925))
* **deps:** update dependency glob-parent to v6 ([b181bb6](https://github.com/linchpin/courier-notices/commit/b181bb60a70a0ed32a7419cf4e01283280b5a32c))
* **deps:** update dependency php-parallel-lint/php-console-highlighter to v1 ([56a7b2e](https://github.com/linchpin/courier-notices/commit/56a7b2e5e1b2b435130b916c7fa33f692c459721))
* **deps:** update node.js to v16 ([4802101](https://github.com/linchpin/courier-notices/commit/48021017e36559e1fefff2de2ead74910c8426c2))
* **deps:** update npm ([8445587](https://github.com/linchpin/courier-notices/commit/844558747d408239b3db8ba5824ca1cb3f4d19df))
* **deps:** update peter-evans/create-pull-request action to v4 ([a6deef5](https://github.com/linchpin/courier-notices/commit/a6deef5042e319a8e28e94ffb1707b9ac7e92c68))
* generate build assets ([a739466](https://github.com/linchpin/courier-notices/commit/a739466f0e7d6f34f6a6bc1a9738714bada03794))
* Make sure additional files can be updated ([38334e1](https://github.com/linchpin/courier-notices/commit/38334e12e6b32c6be810f5e9d379a5e314607952))
* **master:** release 1.4.7 ([942eed1](https://github.com/linchpin/courier-notices/commit/942eed121182ac80ceb7bbe9c585c9a15df1c2bf))
* Minor cleanup of readme and home.md ([13342cf](https://github.com/linchpin/courier-notices/commit/13342cf6e0cd4f26a3a7fd0938131efe1649b46a))
* **NO-JIRA:** trying another area ([5bb96c6](https://github.com/linchpin/courier-notices/commit/5bb96c61c8d9b8f83b5004c6d1af50eb9ab6829f))
* **NO-JIRA:** trying to update manifest based on google feedback ([2d10fb8](https://github.com/linchpin/courier-notices/commit/2d10fb82bdf3d27da26a8b7dd24bfd5dfb127551))
* phpcbf automated cleanup ([c861fa0](https://github.com/linchpin/courier-notices/commit/c861fa096e8b3dafe1a535495aba6f96b657d4dc))
* trying to use manifest ([5c3cc72](https://github.com/linchpin/courier-notices/commit/5c3cc72c8ed0a2c717561df34823c11f8bcdce0c))
* update lock file ([9b94151](https://github.com/linchpin/courier-notices/commit/9b94151d9222ffe369484e6558565bb7199370d7))
* update manifest ([14c2980](https://github.com/linchpin/courier-notices/commit/14c2980cf23eeafa0ed6a59bc8cbcfdcca06f72a))
* updated all dev dependencies ([1307e09](https://github.com/linchpin/courier-notices/commit/1307e0913252abde61fe45df76f8032d1f719cfa))
* Updated changelog, release date bump ([e44c246](https://github.com/linchpin/courier-notices/commit/e44c2469cce46f9b81309dc5955d0be79106d00a))
* updated manifest ([e34e59c](https://github.com/linchpin/courier-notices/commit/e34e59cb1e27fe458c12f4baadf41b847e5c46c6))
* updated readme ([c214831](https://github.com/linchpin/courier-notices/commit/c2148310fd035c1c84b89553784bf52e13827f91))
* Updated release and manifest ([12b20ff](https://github.com/linchpin/courier-notices/commit/12b20ff77339a9eef3f218cb2369834dffad3147))
* Updated timestamp of plugin update ([38334e1](https://github.com/linchpin/courier-notices/commit/38334e12e6b32c6be810f5e9d379a5e314607952))
* Updating release date and WordPress tested with version number ([4a614be](https://github.com/linchpin/courier-notices/commit/4a614beaf99325db06409b93a65efdabfdcb3196))
* Updating What's new ([7041885](https://github.com/linchpin/courier-notices/commit/7041885386f1145a3c46c11df68b8c895eb6b35a))
* Version bump, compiled JS, and changelog updates for release v1.4.0 ([3ebcccb](https://github.com/linchpin/courier-notices/commit/3ebcccbc825ec99e9519d0ae696977cbe2e5cdb4))
* Working to optimize workflows ([b22e56c](https://github.com/linchpin/courier-notices/commit/b22e56c66b955341a948b531bfe667cce48f1467))

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
