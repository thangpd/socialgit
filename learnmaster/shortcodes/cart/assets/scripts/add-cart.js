/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
;(function ($, lema) {
    /**
     * Reload rating block
     * @param element
     * @param html
     */
    function addCart() {
    }

    var lemaModal = {
        html: '<div class="wrapper lema-modal-container"><div class="lema-modal-enroll"><div class=\'lema-modal\' id="lema-modal">\n' +
            '    <div class="lema-page-advanced-search">\n' +
            '        <div class="title-advanced-search" id="lema-modal-title"><?php echo esc_html__(\'Cart\',\'lema\')?></div>\n' +
            '        <div class="lema-columns lema-column-4">\n' +
            '            <div class="lema-modal-content"><center><i class="fa fa-spin fa-spinner"></i> Adding to cart...</center></div>\n' +
            '            <div class="button-wrapper">\n' +
            '                    <a href="' + lema.config.urls.lema_cart + '" id="lema-cart-url" >\n' +
            '                        <i class="fa fa-shopping-basket fa-fw"></i> Go to cart\n' +
            '                    </a>\n' +
            '                    <a id="lema-url-checkout" href="' + lema.config.urls.lema_checkout + '" >\n' +
            '                        <i class="fa fa-shopping-cart fa-fw"></i> Go to Checkout\n' +
            '                    </a>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div></div></div>\n',
        show: function () {

        },
        hide: function () {

        }
    };

    var open_modal_login = function (modal) {
        $(document).on('click', 'a.open-modal-login', function (e) {
            e.preventDefault();
            modal.hide();
            var home_url = $(this).attr('href');
            var login_button = $('.slz-header-wrapper .slz-wrapper-pc .btn-login');
            if (login_button) {
                login_button.trigger('click');
            } else {
                window.location.href = home_url + "/wp-admin";
            }
        });
    };
    addCart.prototype.run = function () {
        var action_add = 'lema-add-cart';

        $(document).on('click', '[data-' + action_add + ']', function () {
            var btn = $(this);
            var btnHtml = $(this)[0].outerHTML;
            var object_id = btn.data(action_add);
            var modal = $(lemaModal.html);
            var content_modal = modal.find('.lema-modal-content');
            var _data = $(this).data();

            btn.prop('disabled', true);

            if ($('.lema-modal-container').length > 0) {
                $('.lema-modal-container').remove();
                
            }
            $('body').focus().append(modal);
            $(".lema-modal>div").append("<div class='lema-modal-button'><a role=\"button\">Close</a></div>");
            modal.fadeIn();
            $('body').addClass("modal-open");
            //send data
            var data = {
                action: action_add,
                post_id: object_id,
                quantity: btn.data('quantity')
            };
            $(".lema-modal-button a").click(function (event) {
                event.preventDefault();
                $(this).parents('.wrapper').fadeOut();
                $('body').removeClass("modal-open");
            });
            $(".wrapper>div").on("click", function (e) {
                if (e.target !== this)
                {
                    e.stopPropagation();
                }
                else {
                    e.preventDefault();
                    $(this).parents('.wrapper').fadeOut();
                    $('body').removeClass("modal-open");
                }
            });
            lema.request.post(lemaConfig.ajaxurl, data, function (res) {
                if (res.status === 'login') {
                    open_modal_login(modal);
                }
                if (res.status === 'enrolled') {
                    $(document).find('#lema-modal .button-wrapper').remove();
                }
                $('.lema-cart-icon').first().html(res.html);
                content_modal.html(res.modal);
                //btn.replaceWith(btnHtml);
                if (_data.url_checkout == '') {
                    var attrs = {
                        'href': 'javascript:void(0)',
                        'data-toggle': 'modal',
                        'data-target': '#login-modal'
                    }
                } else {
                    var attrs = {
                        'href': _data.url_checkout
                    }
                }
                $('#lema-url-checkout').attr(attrs);

                $('#total-item-cart').text(res.total);
                btn.prop('disabled', false);
            })
        })
    };

    var add = new addCart();
    lema.shortcodes.register('lema_add_cart', add);
})(jQuery, lema);