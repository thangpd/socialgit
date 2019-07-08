/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/17/17.
 */
 ;(function($, lema){
    function updateQuantity($row, $index) {
        var quantity = $($row).find('.product-quantity input').val();
        var price = $($row).find('.product-price .amount').html();
        //format number
        // string.replace(/([^0-9])+/i, "")
        quantity = quantity.replace(/([^0-9])+/i, "");
        var prefix  = price.replace(price.replace(/([^0-9])+/i, ""), "");
        price = price.replace(/([^0-9])+/i, "");
        var affix  = price.replace(parseInt(price), "");

        quantity = parseInt(quantity);
        price = parseFloat(price);
        quantity += $index;
        if (quantity > 0 && price > 0) {
            var total = quantity*price;
            $($row).find('.product-quantity input').val(quantity);
            $($row).find('.product-subtotal .amount').html(prefix+total+affix);
        }
    }
    function extractFloat(str){
        var regex = /[+-]?\d+(\.\d+)?/g;
        var floats = str.match(regex).map(function(v) { return parseFloat(v); });
        return floats;
    }
    lema.shortcodes.register('lema_cart', {
        run : function () {
            $(document).on('click', '.product-remove a', function() {
                if ( confirm('Are you sure?') ) {
                    var current = $(this);
                    var row = current.parents('tr.cart_item');
                    var total = $('.lema-total-cart').text();

                    // minus total
                    var price = row.find('.amount').text();
                    price = extractFloat(price);
                    total_float = extractFloat(total);
                    $('.lema-total-cart').text(total.replace(total_float, total_float-price));

                    //icon cart minus
                    var num_icon = $('#total-item-cart').text();
                    num_icon = parseInt(num_icon) - 1;
                    if(num_icon == 0){
                        $('#total-item-cart').remove();
                    }else{
                        $('#total-item-cart').text(num_icon);
                    }

                    //remove srow
                    row.remove();

                    var fields = $('form.lema-form-cart').serializeArray();
                    var data = {
                        action: "ajax_update_cart",
                        data:fields
                    };
                    lema.request.post(lema.config.ajaxurl, data, function(res){
                    });
                }
                return false;
            });
         }
     });
})(jQuery, lema);