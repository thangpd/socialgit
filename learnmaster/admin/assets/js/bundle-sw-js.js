jQuery.noConflict();

jQuery(document).ready(function ($) {
    "use strict";
    $('.add-this-course').on('click', function (e) {
        e.preventDefault();
        var select = $('#courselist option:selected');
        // console.log(select.text());
        // console.log(select.val());

        var post_id = $("#post_ID").val();
        var action = $(this).data('action');
        var total_price = $(".totalprice");
        // console.log("totl_price" + total_price.text());
        var dutu = {
            'action': action,
            'bundle_id': post_id,
            'course_id': select.val(),
            'total_price': Number(total_price.text()) + Number(select.data("price"))
        };
        // console.log(dutu);
        $.post(ajaxurl, dutu, function (response) {
            console.log(response['data']);
            if (response['data'] == 1) {
                var item = select.text();
                if (item) {
                    $('#bundle-table').append(
                        '<tr>' +
                        '                <td>' + select.text() + '</td>' +
                        '                <td>' + select.data("price") + '</td>' +
                        '                <td>' +
                        '                    <a class="bundle_ajax" data-price="' + select.data("price") + '" data-course_id="' + select.val() + '" data-action="lema_bundle_remove_item" href="javacript:void(0)">' +
                        '                  <i class="fa fa-close"></i></a>' +
                        '                </td>' +
                        '</tr>'
                    );
                }
                total_price.text(Number(total_price.text()) + Number(select.data("price")));
                select.remove();
            } else {
                console.log('fail');
            }
        });
    });
    $(document).on('click', 'a.bundle_ajax', function (e) {
        e.preventDefault();
        var post_id = $("#post_ID").val();
        var action = $(this).data('action');
        var course_id = $(this).data('course_id');
        var delete_price = $(this).data('price');
        var total_price = $(".totalprice");
        var total_price_after = Number(total_price.text()) - Number(delete_price);
        // console.log(post_id + action + course_id + 'price' + $(this).data("price"));
        var dutu = {
            'action': action,
            'bundle_id': post_id,
            'course_id': course_id,
            'total_price': total_price_after
        };
        $(this).closest('tr').remove();
        $.post(ajaxurl, dutu, function (response) {
            // console.log(JSON.stringify(response));
            total_price.text(total_price_after);
        });
    });


});