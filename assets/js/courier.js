var courier = courier || {};

courier.dismiss = courier.dismiss || {};

courier.dismiss = (function ($) {

    var $body   = $('body'),
		$window = $(window),
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
                .on('click', '.courier-close', self.close_click )
                .on('click', '.trigger-close', self.close_click );

			document.addEventListener("DOMContentLoaded", function() {
				var $notice_container = $('.courier-notices[data-courier-ajax="true"]' );
					$notice_container.data('loaded', false);

					// If no notice containers expecting ajax, die early.
					if ( $notice_container.length === 0 ) {
						return;
					}

				var observer = new IntersectionObserver( function( entries, observer ) {

					entries.forEach( function( entry ) {
						if ( entry.intersectionRatio === 1  && false === $notice_container.data('loaded') ) {

							var settings = {
								contentType: "application/json",
								placement: $notice_container.data('courier-placement'),
								format: 'html',
							};

							var data = $.extend({}, courier_data.post_info, settings );

							$.ajax( {
									method: 'GET',
									beforeSend: function ( xhr ) {
										xhr.setRequestHeader( 'X-WP-Nonce', courier_data.wp_rest_nonce );
									},
									'url' : courier_data.notices_endpoint,
									'data' : data,
								} ).success( function( response ) {

								if ( response.notices ) {

									$.each( response.notices, function( index ) {

										var $notice = $( response.notices[ index ] ).hide();

										$( '.courier-notices[data-courier-placement="' +  data.placement + '"]' )
											.append( $notice );

										$notice.slideDown('fast');
									} );
								}
							});

							$notice_container.data('loaded', true );
						}
					} );
				}, { threshold: 1 } );
				observer.observe( document.querySelector( '.courier-notices[data-courier-ajax="true"]') );
			});
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

	        var href = $this.attr('href');

	        if (true !== $this.data('dismiss')) {
		        event.preventDefault();
		        event.stopPropagation();

		        // Store that our notice should be dismissed
		        // This will stop an infinite loop.
		        $this.data('dismiss', true);

		        $notices = $this.parents('.courier-notices');

		        self.ajax(parseInt($notices.data('courier-notice-id')));

		        if ( href != undefined && href != '#') {
			        $(document).ajaxComplete(function (event, request, settings) {
				        window.location = href ;
			        });
		        }
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
         * Call out to our ajax endpoint pass either a single id or a comma
         * delimited list of id
         *
         * @param notice_ids example 1 or 1,2,3
         */
        ajax: function ( notice_ids ) {
            $.get( courier_data.endpoint + notice_ids + '/' ).done( function () {
                $notices.find('.courier-close').trigger('click');

                notice_ids = String(notice_ids).split(',');

                $.each( notice_ids, function ( index, value ) {
                    $(".courier_notice[data-courier-notice-id='" + value + "']").fadeOut();
                    $('.courier-modal-overlay').hide();
                    self.set_cookie( value );
                });
            });
        },

        set_cookie: function( notice_id ) {
            var notice_ids = courier.cookie.getItem( 'dismissed_notices' );

            notice_ids = JSON.parse( notice_ids );
            notice_ids = notice_ids || [];

            // Add to array if not already in there
            if ( notice_ids.indexOf( notice_id ) === -1 ) {
                notice_ids.push( notice_id );
            }

            courier.cookie.setItem('dismissed_notices', JSON.stringify( notice_ids ) );
        }

    };

})(jQuery);

courier.modal = courier.modal || {};

courier.modal = (function ($) {

    var $body = $('body'),
        $window = $(window),
        self;

    return {

        /**
         * Initialize our modal
         * Add our events
         */
        init: function () {
            self = courier.modal;

            $window
                .on('load', self.display_modal);
        },

        /**
         * Shows the modal (if there is one) after the page is fully loaded
         */
        display_modal: function () {

	        var notice_ids = courier.cookie.getItem( 'dismissed_notices' );

	        notice_ids = JSON.parse( notice_ids );
	        notice_ids = notice_ids || [];

	        $.each( notice_ids, function( i, val ) {
		        $('div[data-courier-notice-id="' + val + '"]').remove();
	        });

	        var $modal_overlay = $('.courier-modal-overlay');

	        if ( $modal_overlay.length < 1 || $('div.courier-notices', $modal_overlay).length < 1) {
		        return;
	        }

	        $modal_overlay.show();
        }

    };

})(jQuery);

courier.cookie = courier.cookie || {};

courier.cookie = (function () {

    return {

        getItem: function (sKey) {
            if (!sKey) { return null; }
            return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
        },
        setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
            if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }

            if (!sPath) {
            	sPath = '/';
            }

            var sExpires = "";
            if (vEnd) {
                switch (vEnd.constructor) {
                    case Number:
                        sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
                        break;
                    case String:
                        sExpires = "; expires=" + vEnd;
                        break;
                    case Date:
                        sExpires = "; expires=" + vEnd.toUTCString();
                        break;
                }
            }
            document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
            return true;
        },
        removeItem: function (sKey, sPath, sDomain) {
            if (!this.hasItem(sKey)) { return false; }
            document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
            return true;
        },
        hasItem: function (sKey) {
            if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
            return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
        },
        keys: function () {
            var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
            for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
                aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]);
            }
            return aKeys;
        },
        clear: function (sPath, sDomain) {
            var aKeys = this.keys();
            for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
                this.removeItem(aKeys[nIdx], sPath, sDomain);
            }
        }

    };

})();

courier.dismiss.init();
courier.modal.init();
