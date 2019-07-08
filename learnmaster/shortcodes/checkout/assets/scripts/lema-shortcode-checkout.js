/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

;(function($, lema){
    function Checkout()
    {

    }


    Checkout.prototype.run = function () {
        lema.log('Checkout shortcode is running (default)...');
        $(document).on('click', '.payment-block', function () {
            $('.payment-block').removeClass('payment-method-active');
            $(this).addClass('payment-method-active');
            $('#lema-checkout-confirm').prop('disabled', false);
        });
    }


    var checkout = new Checkout();
    lema.shortcodes.register('lema_checkout', checkout);
})(jQuery, lema);