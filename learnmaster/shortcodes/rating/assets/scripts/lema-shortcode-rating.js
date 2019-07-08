/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
;(function($, lema){
    function Rating() {
        this.confirm_message = lema.translations && lema.translations.confirm_rating_message ? lema.translations.confirm_rating_message : 'Are you sure that you really want to rate this object?';
    }

    Rating.prototype.init = function () {

    };

    /**
     * Reload rating block
     * @param element
     * @param html
     */
    Rating.prototype.reloadRatingBlock = function (element, html) {
        element = $(element).closest('.lema-rating');
        if (element) {
            element.html(html);
        }
        lema.log(element, html);
    };

    Rating.prototype.run = function () {
        lema.log('Shortcode rating is running');
        var self = this;
        $(document).on('click', '.lema-rating-content.enabled label', function (e) {
            if (confirm(self.confirm_message)) {
                var data = $(this).data();
                var that = this;
                lema.request.post('', data, function (response) {
                    if (response && response.code == 200) {
                        alert(response.message);
                    }
                    self.reloadRatingBlock(that, response.data);
                })
            }
        });
        
        $(document).on('change','.lema-rating-content [type=radio]',function(){
            $(this).parents('form').find(':input').prop('disabled',false);
            $('.lema-rating-content [type=radio]:checked').not(this).prop('checked', false);
        })
    };

    var rating = new Rating();
    lema.shortcodes.register('lema_rating', rating)
})(jQuery, lema);