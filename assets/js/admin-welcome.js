/**
 * Controls Welcome area display
 *
 * @package    Courier
 * @subpackage Welcome
 * @since      1.1
 */

var courier = courier || {};

courier.welcome = function ($) {

    var $body = $('body'),
        // Instance of our welcome controller
        self,
        $welcomePanel = $('#mesh-template-welcome-panel');

    return {

        /**
         * Initialize our welcome functionality
         */
        init: function () {

            self = courier.welcome;

            $welcomePanel.find('.courier-notices-welcome-panel-close').on('click', function (event) {
                event.preventDefault();

                $welcomePanel.addClass('hidden');

                self.updateWelcomePanel(0);
            });
        },

        /**
         * Show or Hide our Courier Notices Welcome Panel
         * Based on the Welcome Panel in WP Core
         *
         * @param visible
         */
        updateWelcomePanel: function (visible) {
            $.post( ajaxurl, {
                action: 'courier_notices_update_welcome_panel',
                visible: visible,
                courier_notices_welcome_nonce: $('#courier-notices-welcome-panel-nonce').val()
            });
        },
    };

}(jQuery);
