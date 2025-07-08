/**
 * Controls Welcome area display
 *
 * @package    CourierNotices
 * @subpackage Welcome
 * @since      1.0
 */



const $ = jQuery;

export default function welcome() {

    // Private Variables
    let $window       = $(window),
        $doc          = $(document),
        $body         = $('body'),
        $welcomePanel = $('#courier-notices-welcome-panel');

    /**
     * Add some event listeners
     */
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
            courier_notices_welcome_panel: $('#courier_notices_welcome_panel').val()
        });
    }

    init(); // kick everything off for welcome onboarding
}
