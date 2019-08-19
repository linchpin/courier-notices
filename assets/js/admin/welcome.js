/**
 * Controls Welcome area display
 *
 * @package    Courier
 * @subpackage Welcome
 * @since      1.0
 */

import jQuery from 'jquery';

let $ = jQuery;

export default function welcome() {

    // Private Variables
    let $window = $(window),
        $doc = $(document),
        $body = $('body'),
        $welcomePanel = $('#mesh-template-welcome-panel');

    function init() {
        $welcomePanel.find('.courier-notices-welcome-panel-close').on('click', function (event) {
            event.preventDefault();

            $welcomePanel.addClass('hidden');

            updateWelcomePanel(0);
        });
    }

    /**
     * Show or Hide our Courier Notices Welcome Panel
     * Based on the Welcome Panel in WP Core
     *
     * @param visible
     */
    function updateWelcomePanel(visible) {
        $.post(ajaxurl, {
            action: 'courier_notices_update_welcome_panel',
            visible: visible,
            courier_notices_welcome_nonce: $('#courier-notices-welcome-panel-nonce').val()
        });
    }

    init(); // kick everything off for welcome onboarding
}
