var courier = courier || {};

courier.dismiss = courier.dismiss || {};

courier.dismiss = (function ($) {

    var $body = $('body'),
        $notices,
        self;

    return {

        /**
         * Initialize our dismiss
         * Add our events
         */
        init: function () {
            self = courier.dismiss;

            $body
                .on('click', '.courier-close', self.close_click);
        },

        /**
         * Utility wrapper to block clicks.
         *
         * Grab the data-courier-notice-id from current notice.
         *
         * @param event
         */
        close_click: function (event) {

            var $this = $(this);

            if (true !== $this.data('dismiss')) {
                event.preventDefault();
                event.stopPropagation();

                // Store that our notice should be dismissed
                // This will stop an infinite loop.
                $this.data('dismiss', true);

                $notices = $this.parent();

                self.ajax(parseInt($notices.data('courier-notice-id')));
            }
        },

        /**
         * Send all the notices in a comma delimited list.
         *
         * @param event
         */
        close_all_click: function (event) {

            var $this = $(this);

            if (true !== $this.data('dismiss')) {
                event.preventDefault();
                event.stopPropagation();

                // Store that our notice should be dismissed
                // This will stop an infinite loop.
                $this.data('dismiss', true);

                $notices = $('.courier-notices').find('.courier-notice');

                var notice_ids = $notices.data('courier-notice-id');

                self.ajax(notice_ids.join(','));
            }
        },

        /**
         * Call our to our ajax endpoint pass either a single id or a comma
         * delimited list of id
         *
         * @param notice_ids example 1 or 1,2,3
         */
        ajax: function (notice_ids) {
            $.get(courier_data.endpoint + notice_ids + '/').done(function () {
                $notices.find('.courier-close').trigger('click');

                notice_ids = String(notice_ids).split(',');

                $.each(notice_ids, function (index, value) {
                    $(".courier_notice[data-courier-notice-id='" + value + "']").fadeOut();
                });
            });
        }

    };

})(jQuery);

courier.dismiss.init();
