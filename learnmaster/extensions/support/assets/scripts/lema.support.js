/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


;(function($, lema){
    $(document).ready(function() {
        $('#lema-ticket-form').on('submit', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var submitButton = $(this).find('button[type=submit]').first();
            var btnHtml = submitButton.html();
            submitButton.html('<i class="fa fa-spinner fa-spin"></i> Sending....');
            submitButton.prop('disabled');
            lema.ui.loading.show();
            var data = $(this).serialize();
            var self = this;
            lema.request.post(lema.config.ajaxurl, data, function (response) {
                console.log(response);
                submitButton.html(btnHtml);
                submitButton.prop('disabled', false);
                lema.ui.loading.hide();
                $(self)[0].reset();
                if (response) {
                    if (response.code === 200) {
                        lema.ui.toaster.success(response.message);
                    } else {
                        lema.ui.toaster.error(response.message);
                    }
                }
            });

            return false;
        })
    });
})(jQuery,lema);