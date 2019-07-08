jQuery(function ($) {
    var lema = window.lema || {};
    "use strict";
    lema.updatecart = function (data = {}) {
        data.action = 'lema-update-cart-dropdown';
        // block.remove();
        $.ajax({
            url: ajaxurl,
            data: data,
            type: 'POST',
            success: function success(res) {
                var dropdown_item_cart = $('.lema-cart-dropdown .dropdown-menu ');
                dropdown_item_cart.html(res.data);
                $('.lema-cart-dropdown #total-item-cart').text(dropdown_item_cart.find('.item').length)
            },
        });
    };


    $(document).ready(function () {
        $(document).on('click', '.cart-remove', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var data = {};
            data.cart_item_key = $(this).data('cart_item_key');
            lema.updatecart(data);
        })
        $(document).on('click', '.lema-cart-dropdown .dropdown-menu *:not(a)', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })
    });
});