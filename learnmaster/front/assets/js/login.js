jQuery(document).ready(function ($) {
    "use strict";

    function navtab() {
        $(".nav-tabs .tab-login").toggleClass("active");
        $(".nav-tabs .tab-register").toggleClass("active");
    }

    $('.btn-register').on('click', function (e) {
        $(this).tab("show");
        navtab();
    });
    $('.btn-login').on('click', function (e) {
        $(this).tab('show');
        navtab();
    });
    $('.login-register-form').submit(function (e) {
        e.preventDefault();
        var action = $(this).attr('action');
        var form = $(this);
        var submit_button = $(this).find('[type=submit]');
        var submit_button_html = submit_button.text();
        var message_block = $(this).find('.message-result');
        var data = $(this).serialize();
        var return_submit = true;
        message_block.removeClass('text-success').addClass('text-danger');
        var list_email_validate = $(this).find('.email-validate[required]');
        if (list_email_validate.length) {
            list_email_validate.each(function (index, input) {
                var re = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                if (!re.test($(input).val())) {
                    message_block.text(($(input).val() + ' is not email !'));
                    return_submit = false;
                }
            })
        }
        if (return_submit) {
            submit_button.prop('disabled', true);
            submit_button.prepend('<i class="fa fa-spinner fa-spin"></i> ');
            message_block.text('');
            $.ajax({
                    url: action,
                    timeout:3000,
                    type: 'POST',
                    data: data,
                    retryLimit: 2,
                    success: function (res) {
                        if (res.success) {
                            message_block.removeClass('text-danger').addClass('text-success');
                            if (action.indexOf('lema_login') !== -1) {
                                var redirect_url = window.location.href;
                                redirect_url = getParameterByName('redirect_to', redirect_url);
                                if ( !redirect_url || 0 === redirect_url.length) {
                                    window.location.reload(true);
                                } else {
                                    window.location = decodeURIComponent(redirect_url);
                                }
                            }
                        } else {
                            var input = form.find('input');
                            input.eq(0).focus();
                            input.val('');
                        }

                        if (res.message !== undefined && res.message !== '') {
                            message_block.text(res.message);
                        }

                        submit_button.prop('disabled', false);
                        submit_button.text(submit_button_html);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        if (textStatus === 'timeout') {
                            if (this.retryLimit) {
                                message_block.text('Login server did not respond. (Retrying: attempt ' + this.retryLimit + ')');
                                this.retryLimit--;
                                $.ajax(this);
                                return;
                            }
                            return;
                        }
                        if (xhr.status) {
                            console.log(xhr.status);
                            message_block.text('Some errors have been detected on the server. Please reload page and try login again.');
                            submit_button.prop('disabled', false);
                            submit_button.text(submit_button_html);
                        }
                    }
                });

}
})
;
})
;

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}