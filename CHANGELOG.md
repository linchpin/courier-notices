# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.8.0] (2024-XX-XX)

### Added
- Action Scheduler dependency for improved notice expiration management
- Individual notice scheduling instead of bulk checking
- Automatic migration from WP Cron to Action Scheduler
- New Action Scheduler controller with better error handling
- Admin notice for successful migration
- Comprehensive migration documentation
- New action hooks for developers (`courier_notices_notice_expired`, `courier_notices_notices_purged`, `courier_notices_bulk_expired`)
- Fallback systems for bulk expiration and purging

### Changed
- **BREAKING**: Migrated from WP Cron to Action Scheduler for better reliability
- Notice expiration now uses individual scheduling for each notice
- Purge actions now run daily instead of every 5 minutes
- Improved performance and scalability for sites with many notices

### Deprecated
- Legacy Cron controller (still functional but marked as deprecated)
- WP Cron scheduling system for notice expiration

### Fixed
- More reliable notice expiration timing
- Reduced server load during notice processing
- Better handling of notice expiration in high-traffic environments

### Security
- Improved nonce verification for admin actions
- Better permission checks for migration processes

## [1.7.1](https://github.com/linchpin/courier-notices/compare/v1.7.0...v1.7.1) (2024-08-26)


### Miscellaneous Chores 🧹

* **deps:** bump braces from 3.0.2 to 3.0.3 ([ab58ebc](https://github.com/linchpin/courier-notices/commit/ab58ebc32171319d97d5fb8e140456b0d2c6edcc))
* **NO-TASK:** Release build update ([0b8122c](https://github.com/linchpin/courier-notices/commit/0b8122cb983936391f07ee644aba08804e6f8efc))

## [1.7.0](https://github.com/linchpin/courier-notices/compare/v1.6.0...v1.7.0) (2024-08-26)


### Miscellaneous Chores 🧹

* **NO-TASK:** Bump version, block block editor ([3ff6e06](https://github.com/linchpin/courier-notices/commit/3ff6e06ebf15fd14ee8ce62afeba791c949486b1))

## [1.6.0](https://github.com/linchpin/courier-notices/compare/v1.5.9...v1.6.0) (2024-06-26)


### Features ✨

* Add documentation ([41c0792](https://github.com/linchpin/courier-notices/commit/41c0792c48243a9c279c2424d99ca0dba13fda72))
* Added controls for toggling show/hide of titles within the admin javascript (Classic Editor) [#93](https://github.com/linchpin/courier-notices/issues/93) ([5a445d0](https://github.com/linchpin/courier-notices/commit/5a445d0b019cdf65cfb2e8b5d0a7efbd0d74b902))
* Added css for admin UI elements related to toggling show/hide of notice titles [#93](https://github.com/linchpin/courier-notices/issues/93) ([a6ff5c0](https://github.com/linchpin/courier-notices/commit/a6ff5c060a3c5e9f6d67dcba595f50afce5635da))
* Making it so modals will only show 1 at a time ([f812325](https://github.com/linchpin/courier-notices/commit/f8123251013646a94ce8054d9f950c77fec36a98))
* Making modals more efficient. ([f812325](https://github.com/linchpin/courier-notices/commit/f8123251013646a94ce8054d9f950c77fec36a98))
* Split global design styles and "Notice Style" styles into different views. ([3664791](https://github.com/linchpin/courier-notices/commit/3664791a044cd5c2ef7258df22668f8831f98d38))
* Updated how modals work to only show one at a time ([2b599da](https://github.com/linchpin/courier-notices/commit/2b599da50d2775828ceeab3d9839bf0e4c6d6f5f))


### Bug Fixes 🐛

* **#265:** debug modal firing consistently, clean-up PHP warnings ([cc35877](https://github.com/linchpin/courier-notices/commit/cc3587781d999b45c4f15fcab58bc9ff1fd5ef81))
* **#297:** Send just array values to resolve fatal in PHP8 ([d24fb6b](https://github.com/linchpin/courier-notices/commit/d24fb6b2d40f027eeb0eef03a20690fef70c4875))
* **#298:** Check if title rules array exists ([4c6822f](https://github.com/linchpin/courier-notices/commit/4c6822f6ab7d97f7abb493cfa6991621474528ab))
* **#307:** Add comment to expiration check ([903455f](https://github.com/linchpin/courier-notices/commit/903455fbc246f4766229784354c6d57f23544567))
* **#307:** update check for expiration nonce ([6d6ca3f](https://github.com/linchpin/courier-notices/commit/6d6ca3fa2a45f1f7a9a480c54cbd469b8bb68d47))
* **#312:** Fix an issue with post meta being saving in all post types ([6d12991](https://github.com/linchpin/courier-notices/commit/6d12991afe33eb6577216b4c819a7278707d3065))
* **286:** Pass notice ID vs post ID ([259ae02](https://github.com/linchpin/courier-notices/commit/259ae022ba2d634eb2cd74687606ee33df66cb82))
* add "hide" css class to hide elements within the modal overlay when needed ([3253349](https://github.com/linchpin/courier-notices/commit/3253349f641ba1875ebda4abac1e2bb3984c6f12))
* added show/hide title to default template ([a3a2f3c](https://github.com/linchpin/courier-notices/commit/a3a2f3c093f37706fead93474f84c8b86885432d))
* **admin:** Adding in missing semicolons ([bf1c70f](https://github.com/linchpin/courier-notices/commit/bf1c70f7f7daa7853bf875733173d01948317f59))
* **admin:** Make sure $selected_styles is an array before running in_array, adding link to Global Design Settings page in the callout ([e725427](https://github.com/linchpin/courier-notices/commit/e7254278c2b26ca87880eaeda3aaf48c0ceff1b2))
* **admin:** Removed courier_notice post type specific javascript being enqueued on all post edit pages. ([140a208](https://github.com/linchpin/courier-notices/commit/140a208a8d4d6f1f5977b172600d55387956c6b4))
* Allow trigger-close to fire dismissal and window.location even if ajax isn't being called, add ajaxComplete method specifically to user_id after post ([dcb159a](https://github.com/linchpin/courier-notices/commit/dcb159aeb8e3d93ec748a1963b48ce8656aef34d))
* build paths were incorrect from migrating ([e5cf620](https://github.com/linchpin/courier-notices/commit/e5cf620c1555f27758a2fecdaa57f6c7a6a682d5))
* **deps:** update npm ([a0f013c](https://github.com/linchpin/courier-notices/commit/a0f013cde5cd33ab25cdb21ddaab3011c50b5537))
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
* **NO-JIRA:** missing dependencies ([ce8a93c](https://github.com/linchpin/courier-notices/commit/ce8a93c94dba842c6881c3c7a91bfd8d520eb02f))
* **NO-TASK:** Cleanup deprecation notice ([8a645da](https://github.com/linchpin/courier-notices/commit/8a645daf350461f54565f2e5863814adbc2eaf8d))
* **NO-TASK:** Update git attributes ([9fb54f3](https://github.com/linchpin/courier-notices/commit/9fb54f374c7b146b9e4fabc12a31c057be98b657))
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
* **deps-dev:** bump webpack from 5.75.0 to 5.76.0 ([dc930b9](https://github.com/linchpin/courier-notices/commit/dc930b9f8ecdc8dabf670ae915f4125301890301))
* **deps:** bump @babel/traverse from 7.21.0 to 7.24.7 ([ed4f580](https://github.com/linchpin/courier-notices/commit/ed4f580d0ff4d719c73f0423a962722173a23ad9))
* **deps:** bump browserslist from 4.16.4 to 4.20.3 ([c597b21](https://github.com/linchpin/courier-notices/commit/c597b2166eac1808ad9a6de7f4d6ac00efc40f15))
* **deps:** bump decode-uri-component from 0.2.0 to 0.2.2 ([70b5ba7](https://github.com/linchpin/courier-notices/commit/70b5ba7d86a9005b992fd44131c38bf635f706d8))
* **deps:** bump engine.io from 6.2.0 to 6.2.1 ([9edc50b](https://github.com/linchpin/courier-notices/commit/9edc50b01955d11aac08f3765a99ba62aab9a060))
* **deps:** bump engine.io from 6.4.1 to 6.4.2 ([41925f6](https://github.com/linchpin/courier-notices/commit/41925f663119b0e77b94dfa698edf7f740b53eb2))
* **deps:** bump es5-ext from 0.10.62 to 0.10.64 ([56b454c](https://github.com/linchpin/courier-notices/commit/56b454ccfdf003bab71178b23570701272d6572e))
* **deps:** bump follow-redirects from 1.13.3 to 1.14.8 ([f1fc483](https://github.com/linchpin/courier-notices/commit/f1fc483b71fd9b05ebde6e2030060f3ddf5c6034))
* **deps:** bump follow-redirects from 1.15.2 to 1.15.6 ([ac7ef19](https://github.com/linchpin/courier-notices/commit/ac7ef1998d00ce31b84e66c7ba829e77d4880322))
* **deps:** bump json5 from 2.2.1 to 2.2.3 ([67d3426](https://github.com/linchpin/courier-notices/commit/67d342671f6ffaef8a3ecb7535f2dcf942d1053c))
* **deps:** bump loader-utils from 2.0.2 to 2.0.4 ([651c052](https://github.com/linchpin/courier-notices/commit/651c0520233f7bf2763d0c85a81b5f06cb0b93af))
* **deps:** bump minimist from 1.2.5 to 1.2.6 ([324a73a](https://github.com/linchpin/courier-notices/commit/324a73a16cc28bd47bd354ba29cb328db9c49769))
* **deps:** bump postcss from 7.0.39 to 8.4.38 ([722a11c](https://github.com/linchpin/courier-notices/commit/722a11c170df79f7eb05b2c856d5094cee15aa62))
* **deps:** bump socket.io from 4.6.1 to 4.7.5 ([db9901f](https://github.com/linchpin/courier-notices/commit/db9901fb478427cb8eb7aac5e412b4c76f441733))
* **deps:** bump socket.io-parser from 4.2.2 to 4.2.4 ([705d566](https://github.com/linchpin/courier-notices/commit/705d566d8dadc673cc75003735fa532e835a0562))
* **deps:** lock file maintenance ([6512e23](https://github.com/linchpin/courier-notices/commit/6512e2327c1de422f61b49cc2e19b2bc74a50435))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2 ([413a0be](https://github.com/linchpin/courier-notices/commit/413a0beb9ce279a2de99a95df96c6115b91cac09))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2.1.0 ([ec5f773](https://github.com/linchpin/courier-notices/commit/ec5f7733a9b35c9237c58eb825bd03dafeb9ffad))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2.1.1 ([1b3cb5a](https://github.com/linchpin/courier-notices/commit/1b3cb5a77c9a83797edb1ca9dd11b0646b62da8f))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2.2.2 ([566f3b2](https://github.com/linchpin/courier-notices/commit/566f3b2da0928786eddf188d26444ab068c6f4fe))
* **deps:** update actions/cache action to v3 ([8d999eb](https://github.com/linchpin/courier-notices/commit/8d999eb606e3b3ff1ccb69b9f0269c0e443f98d4))
* **deps:** update actions/cache action to v4 ([1ee9442](https://github.com/linchpin/courier-notices/commit/1ee9442ebffe4ba05dc673f04eb7bd8ba82ff9f4))
* **deps:** update actions/checkout action to v3 ([a1bee17](https://github.com/linchpin/courier-notices/commit/a1bee17a67a593aecb4f6041aea6980b27edc925))
* **deps:** update actions/checkout action to v4 ([0b79d9a](https://github.com/linchpin/courier-notices/commit/0b79d9a05631198eb76183e338eb749b88507182))
* **deps:** update andrew-chen-wang/github-wiki-action action to v4 ([84a9eb1](https://github.com/linchpin/courier-notices/commit/84a9eb1ec10696188b80ef4703a6ff6103d6f094))
* **deps:** update composer ([569a67a](https://github.com/linchpin/courier-notices/commit/569a67af6e52e0514b9eb57bf8f03340c20d0555))
* **deps:** update composer ([735e814](https://github.com/linchpin/courier-notices/commit/735e8142cdfb43a51caf98156eefc71e5b937801))
* **deps:** update composer ([fc6a119](https://github.com/linchpin/courier-notices/commit/fc6a119fc79fffa3a8cb977d232a7700c4dd571c))
* **deps:** update composer ([44d2421](https://github.com/linchpin/courier-notices/commit/44d24212b724502dcaae551fc649b4510e4e0b97))
* **deps:** update dependency dealerdirect/phpcodesniffer-composer-installer to v1 ([dd02f34](https://github.com/linchpin/courier-notices/commit/dd02f3424506ca3eedd00c74cc4fecb123b2ef8b))
* **deps:** update dependency glob-parent to v6 ([b181bb6](https://github.com/linchpin/courier-notices/commit/b181bb60a70a0ed32a7419cf4e01283280b5a32c))
* **deps:** update dependency meow to v11 ([e989d72](https://github.com/linchpin/courier-notices/commit/e989d7275cce553c19c806d4bf78c8ee4a8594da))
* **deps:** update dependency meow to v13 ([676eab5](https://github.com/linchpin/courier-notices/commit/676eab530585a2dd144c3d38b45731cb770ee0ca))
* **deps:** update dependency php-parallel-lint/php-console-highlighter to v1 ([56a7b2e](https://github.com/linchpin/courier-notices/commit/56a7b2e5e1b2b435130b916c7fa33f692c459721))
* **deps:** update dependency php-parallel-lint/php-parallel-lint to ^v1.4.0 ([e6a4a60](https://github.com/linchpin/courier-notices/commit/e6a4a60dc5fad0263cdb082e5a6e9d4ec77e25be))
* **deps:** update dependency postcss to v8.4.31 [security] ([86912ed](https://github.com/linchpin/courier-notices/commit/86912edcedf8fa096a0819523f57797e28110fcb))
* **deps:** update dependency saggre/phpdocumentor-markdown to ^0.1.4 ([5e0b6fc](https://github.com/linchpin/courier-notices/commit/5e0b6fc5312381d00fb12ecd33d1a851024320a9))
* **deps:** update dependency semver to v7.5.2 [security] ([f7ea0d0](https://github.com/linchpin/courier-notices/commit/f7ea0d0635c92d16c060ac9bfeac8a22adaf57df))
* **deps:** update dependency squizlabs/php_codesniffer to ^3.10.1 ([a540b65](https://github.com/linchpin/courier-notices/commit/a540b65a3dea1c426d7282e4756745c72f3e2014))
* **deps:** update dependency trim-newlines to v5 ([f6893f6](https://github.com/linchpin/courier-notices/commit/f6893f6a297a98497997bb2bdba95c747b062199))
* **deps:** update dependency webpack to v5.76.0 [security] ([67d735a](https://github.com/linchpin/courier-notices/commit/67d735aa12eee454f3a373617fd78b3ffa44a2c2))
* **deps:** update node.js to v16 ([4802101](https://github.com/linchpin/courier-notices/commit/48021017e36559e1fefff2de2ead74910c8426c2))
* **deps:** update npm ([e361ec6](https://github.com/linchpin/courier-notices/commit/e361ec623b1b35065bcc2ab6153b5a7cc61cae31))
* **deps:** update npm ([8445587](https://github.com/linchpin/courier-notices/commit/844558747d408239b3db8ba5824ca1cb3f4d19df))
* **deps:** update peter-evans/create-pull-request action to v4 ([a6deef5](https://github.com/linchpin/courier-notices/commit/a6deef5042e319a8e28e94ffb1707b9ac7e92c68))
* **deps:** update rtcamp/action-phpcs-code-review action to v3 ([02282ae](https://github.com/linchpin/courier-notices/commit/02282ae1941b21ed966ac7d949cec1d56631bbd6))
* **deps:** update svenstaro/upload-release-action action to v2.5.0 ([e377024](https://github.com/linchpin/courier-notices/commit/e3770242f914f56563136ac8e24844c143e1f074))
* **deps:** update svenstaro/upload-release-action action to v2.9.0 ([5265775](https://github.com/linchpin/courier-notices/commit/5265775196cc7f460a4d5ac7fe2ff62f4739782f))
* generate build assets ([a739466](https://github.com/linchpin/courier-notices/commit/a739466f0e7d6f34f6a6bc1a9738714bada03794))
* Make sure additional files can be updated ([38334e1](https://github.com/linchpin/courier-notices/commit/38334e12e6b32c6be810f5e9d379a5e314607952))
* **master:** release 1.4.7 ([942eed1](https://github.com/linchpin/courier-notices/commit/942eed121182ac80ceb7bbe9c585c9a15df1c2bf))
* **master:** release courier-notices 1.5.0 ([7da2da0](https://github.com/linchpin/courier-notices/commit/7da2da0c819bf67c49c41209f58631e6e50eb4b5))
* **master:** release courier-notices 1.5.1 ([393fcc4](https://github.com/linchpin/courier-notices/commit/393fcc49b0e7b9f38cc3f6a1f81dcac5597ed2a1))
* **master:** release courier-notices 1.5.2 ([5e4fbfa](https://github.com/linchpin/courier-notices/commit/5e4fbfa06ddc2d9c5edf3994809d04a483697914))
* **master:** release courier-notices 1.5.3 ([73a735f](https://github.com/linchpin/courier-notices/commit/73a735fb69c7c055ce3d437d5d12dc448c7ad787))
* **master:** release courier-notices 1.5.4 ([29c647c](https://github.com/linchpin/courier-notices/commit/29c647c163067d85dd46495536e0fdae76563758))
* **master:** release courier-notices 1.5.5 ([09cfab4](https://github.com/linchpin/courier-notices/commit/09cfab46808380bde780048f6ad83482bb9201dd))
* **master:** release courier-notices 1.5.6 ([2ba85c3](https://github.com/linchpin/courier-notices/commit/2ba85c3692498fedfc9d0b5b53ba581a4ffe9a8b))
* **master:** release courier-notices 1.5.7 ([2d6b861](https://github.com/linchpin/courier-notices/commit/2d6b861ac38706f344110f7e990ec3cef3e489cb))
* **master:** release courier-notices 1.5.8 ([093180a](https://github.com/linchpin/courier-notices/commit/093180a6615721503c1ce5a302f0f99279c10ea2))
* **master:** release courier-notices 1.5.9 ([43ab7e5](https://github.com/linchpin/courier-notices/commit/43ab7e5ea691b5c4fcf9d68198d36e83d9e8322d))
* Minor cleanup of readme and home.md ([13342cf](https://github.com/linchpin/courier-notices/commit/13342cf6e0cd4f26a3a7fd0938131efe1649b46a))
* **NO-JIRA:** Touch file to create build ([fa420fd](https://github.com/linchpin/courier-notices/commit/fa420fd02c48e1838f5c90bce6590895efe9154e))
* **NO-JIRA:** trying another area ([5bb96c6](https://github.com/linchpin/courier-notices/commit/5bb96c61c8d9b8f83b5004c6d1af50eb9ab6829f))
* **NO-JIRA:** trying to update manifest based on google feedback ([2d10fb8](https://github.com/linchpin/courier-notices/commit/2d10fb82bdf3d27da26a8b7dd24bfd5dfb127551))
* **NO-TASK:** Bumping Tested Up To ([ca88b20](https://github.com/linchpin/courier-notices/commit/ca88b208f66fb234f69030b061d33f4bf979aaf2))
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
* Updated the distigore to remove unneeded files ([576d020](https://github.com/linchpin/courier-notices/commit/576d020afb6d8e9bc08d134181bd861cf6234f0f))
* Updated the release please workflow ([6844a9f](https://github.com/linchpin/courier-notices/commit/6844a9f7a0556ef99bc3fdb43b77c63c159394ea))


### Reverts ⏪️

* cant spend time looking at coding standards ([6ce4874](https://github.com/linchpin/courier-notices/commit/6ce48748c8ce1ffc6c672b670a528f63ff1448ad))

## [1.5.9](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.8...courier-notices-v1.5.9) (2024-06-26)


### Bug Fixes 🐛

* **NO-TASK:** Cleanup deprecation notice ([8a645da](https://github.com/linchpin/courier-notices/commit/8a645daf350461f54565f2e5863814adbc2eaf8d))
* **NO-TASK:** Update git attributes ([9fb54f3](https://github.com/linchpin/courier-notices/commit/9fb54f374c7b146b9e4fabc12a31c057be98b657))


### Miscellaneous Chores 🧹

* **deps-dev:** bump webpack from 5.75.0 to 5.76.0 ([dc930b9](https://github.com/linchpin/courier-notices/commit/dc930b9f8ecdc8dabf670ae915f4125301890301))
* **deps:** bump @babel/traverse from 7.21.0 to 7.24.7 ([ed4f580](https://github.com/linchpin/courier-notices/commit/ed4f580d0ff4d719c73f0423a962722173a23ad9))
* **deps:** bump engine.io from 6.4.1 to 6.4.2 ([41925f6](https://github.com/linchpin/courier-notices/commit/41925f663119b0e77b94dfa698edf7f740b53eb2))
* **deps:** bump es5-ext from 0.10.62 to 0.10.64 ([56b454c](https://github.com/linchpin/courier-notices/commit/56b454ccfdf003bab71178b23570701272d6572e))
* **deps:** bump follow-redirects from 1.15.2 to 1.15.6 ([ac7ef19](https://github.com/linchpin/courier-notices/commit/ac7ef1998d00ce31b84e66c7ba829e77d4880322))
* **deps:** bump postcss from 7.0.39 to 8.4.38 ([722a11c](https://github.com/linchpin/courier-notices/commit/722a11c170df79f7eb05b2c856d5094cee15aa62))
* **deps:** bump socket.io from 4.6.1 to 4.7.5 ([db9901f](https://github.com/linchpin/courier-notices/commit/db9901fb478427cb8eb7aac5e412b4c76f441733))
* **deps:** bump socket.io-parser from 4.2.2 to 4.2.4 ([705d566](https://github.com/linchpin/courier-notices/commit/705d566d8dadc673cc75003735fa532e835a0562))
* **deps:** update 10up/action-wordpress-plugin-deploy action to v2.2.2 ([566f3b2](https://github.com/linchpin/courier-notices/commit/566f3b2da0928786eddf188d26444ab068c6f4fe))
* **deps:** update actions/cache action to v4 ([1ee9442](https://github.com/linchpin/courier-notices/commit/1ee9442ebffe4ba05dc673f04eb7bd8ba82ff9f4))
* **deps:** update actions/checkout action to v4 ([0b79d9a](https://github.com/linchpin/courier-notices/commit/0b79d9a05631198eb76183e338eb749b88507182))
* **deps:** update andrew-chen-wang/github-wiki-action action to v4 ([84a9eb1](https://github.com/linchpin/courier-notices/commit/84a9eb1ec10696188b80ef4703a6ff6103d6f094))
* **deps:** update composer ([569a67a](https://github.com/linchpin/courier-notices/commit/569a67af6e52e0514b9eb57bf8f03340c20d0555))
* **deps:** update composer ([735e814](https://github.com/linchpin/courier-notices/commit/735e8142cdfb43a51caf98156eefc71e5b937801))
* **deps:** update dependency meow to v13 ([676eab5](https://github.com/linchpin/courier-notices/commit/676eab530585a2dd144c3d38b45731cb770ee0ca))
* **deps:** update dependency php-parallel-lint/php-parallel-lint to ^v1.4.0 ([e6a4a60](https://github.com/linchpin/courier-notices/commit/e6a4a60dc5fad0263cdb082e5a6e9d4ec77e25be))
* **deps:** update dependency postcss to v8.4.31 [security] ([86912ed](https://github.com/linchpin/courier-notices/commit/86912edcedf8fa096a0819523f57797e28110fcb))
* **deps:** update dependency saggre/phpdocumentor-markdown to ^0.1.4 ([5e0b6fc](https://github.com/linchpin/courier-notices/commit/5e0b6fc5312381d00fb12ecd33d1a851024320a9))
* **deps:** update dependency semver to v7.5.2 [security] ([f7ea0d0](https://github.com/linchpin/courier-notices/commit/f7ea0d0635c92d16c060ac9bfeac8a22adaf57df))
* **deps:** update dependency squizlabs/php_codesniffer to ^3.10.1 ([a540b65](https://github.com/linchpin/courier-notices/commit/a540b65a3dea1c426d7282e4756745c72f3e2014))
* **deps:** update dependency trim-newlines to v5 ([f6893f6](https://github.com/linchpin/courier-notices/commit/f6893f6a297a98497997bb2bdba95c747b062199))
* **deps:** update dependency webpack to v5.76.0 [security] ([67d735a](https://github.com/linchpin/courier-notices/commit/67d735aa12eee454f3a373617fd78b3ffa44a2c2))
* **deps:** update npm ([e361ec6](https://github.com/linchpin/courier-notices/commit/e361ec623b1b35065bcc2ab6153b5a7cc61cae31))
* **deps:** update rtcamp/action-phpcs-code-review action to v3 ([02282ae](https://github.com/linchpin/courier-notices/commit/02282ae1941b21ed966ac7d949cec1d56631bbd6))
* **deps:** update svenstaro/upload-release-action action to v2.9.0 ([5265775](https://github.com/linchpin/courier-notices/commit/5265775196cc7f460a4d5ac7fe2ff62f4739782f))

## [1.5.8](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.7...courier-notices-v1.5.8) (2023-02-22)


### Bug Fixes 🐛

* **#312:** Fix an issue with post meta being saving in all post types ([6d12991](https://github.com/linchpin/courier-notices/commit/6d12991afe33eb6577216b4c819a7278707d3065))
* **deps:** update npm ([a0f013c](https://github.com/linchpin/courier-notices/commit/a0f013cde5cd33ab25cdb21ddaab3011c50b5537))


### Miscellaneous Chores 🧹

* **deps:** update composer ([fc6a119](https://github.com/linchpin/courier-notices/commit/fc6a119fc79fffa3a8cb977d232a7700c4dd571c))
* **deps:** update composer ([44d2421](https://github.com/linchpin/courier-notices/commit/44d24212b724502dcaae551fc649b4510e4e0b97))
* **deps:** update dependency meow to v11 ([e989d72](https://github.com/linchpin/courier-notices/commit/e989d7275cce553c19c806d4bf78c8ee4a8594da))
* **deps:** update svenstaro/upload-release-action action to v2.5.0 ([e377024](https://github.com/linchpin/courier-notices/commit/e3770242f914f56563136ac8e24844c143e1f074))

## [1.5.7](https://github.com/linchpin/courier-notices/compare/courier-notices-v1.5.6...courier-notices-v1.5.7) (2023-02-06)


### Miscellaneous Chores 🧹

* **master:** release courier-notices 1.5.6 ([2ba85c3](https://github.com/linchpin/courier-notices/commit/2ba85c3692498fedfc9d0b5b53ba581a4ffe9a8b))


### Bug Fixes 🐛

* **#307:** Add comment to expiration check ([903455f](https://github.com/linchpin/courier-notices/commit/903455fbc246f4766229784354c6d57f23544567))
* **#307:** update check for expiration nonce ([6d6ca3f](https://github.com/linchpin/courier-notices/commit/6d6ca3fa2a45f1f7a9a480c54cbd469b8bb68d47))

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
