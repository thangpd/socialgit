;(function ($, lema) {
    lema.search = {
        init: function () {
            var self = this;
            $(document).ready(function () {

                $(document).on('click', "[data-modal]", function () {
                    var toload = $(this).attr("data-modal");
                    $("." + toload).parent().fadeIn();
                });
                $(document).on('change', '#lema-search-form input[type=checkbox], #lema-search-form input[type=radio]', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    if ($(this).is(':checked') || $(this).is(':selected')) {
                        self.addFilter(name, value);
                    } else {
                        self.removeFiter(name, value);
                    }
                    return false
                });
                $(document).on('change', '#lema-search-form select', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    self.addFilter(name, value);
                });
                $(document).on('click', '#lema-clear-search-term', function () {
                    var intput = $('#lema-searchbox-input');
                    if (intput.length > 0) {
                        intput.val('');
                    }
                    self.removeFiter(intput.attr('name'));
                });
                $(document).on('keyup', '#lema-search-form #lema-q', function (e) {
                    if (e.keyCode === 13) {
                        var val = $(this).val();
                        var name = $(this).attr('name');
                        if (val.trim().length > 0) {
                            self.addFilter(name, val);
                        } else {
                            self.removeFiter(name);
                        }
                    }
                });
                $(document).on('click', '.button-view .view-more', function (e) {
                    e.stopPropagation();
                    var cont = $(this).closest('.lema-widget').find('.lema-view-more-button');
                    if (cont.length > 0) {
                        if ($(cont).hasClass('lema-hide')) {
                            $(this).html('View less');
                            $(cont).removeClass('lema-hide').addClass('lema-show');
                        } else {
                            $(cont).removeClass('lema-show').addClass('lema-hide');
                            $(this).html('View more');
                        }
                    }

                });
                $(document).on('click', '.lema-filter-category a', function (e) {
                    e.preventDefault();
                    if ($(this).data('name_type') && $(this).data('value_type')) {
                        self.addFilter($(this).data('name_type'), $(this).data('value_type'), false);
                    }
                    var name = $(this).data('name');
                    var value = $(this).data('value');
                    self.addFilter(name, value);
                });
                var data = $('#lema-search-form input[name=data]').val();
                if (data !== 'undefined') {
                    data = JSON.parse(decodeURIComponent(data));
                }
            });
        },
        addFilter: function (name, value, apply = true) {
            var reg = /\[(.+)\]\[(.+)\]/;
            var param = name.match(reg);
            if (param) {
                lema.utils.updateQueryStringParameterSearch(location.href, param[1], param[2], true);
            } else {
                lema.utils.updateQueryStringParameter(location.href, name, value, true);
            }
            if (apply === true) {
                this.applyFilter(name);
            }
        },
        removeFiter: function (name, value) {
            var reg = /\[(.+)\]\[(.+)\]/;
            var param = name.match(reg);
            name = param[1];
            value = param[2];
            if (param) {
                lema.utils.removeQueryStringParameterSearch(location.href, param[1], param[2], true);
            } else {
                lema.utils.removeQueryStringParameter(location.href, name, value, true);
            }
            this.applyFilter(name);
        },
        applyFilter: function (name) {
            var data = $('#lema-search-form input[name=data]').val();
            if (data !== 'undefined' && data !== 'null') {
                data = JSON.parse(decodeURIComponent(data));
                if (!data.hasOwnProperty('course_vc')) {
                    data.course_vc = '';
                }
                if (!data.hasOwnProperty('template')) {
                    data.template = '';
                }
                if (!data.hasOwnProperty('template_1')) {
                    data.template_1 = '';
                }
            } else {
                data = {course_vc: '', template: ''};
            }
            //Apply filter to current searching result
            lema.utils.removeQueryStringParameter(location.href, 'paging', false, true);
            var filters = document.location.search;
            var url = '';
            if (filters.length > 1) {
                url = lemaConfig.ajaxurl + filters + '&action=lema_apply_course_filter_sc&change=' + name + '&course_vc=' + data.course_vc + '&template=' + data.template + '&template_1=' + data.template_1;
            } else {
                url = lemaConfig.ajaxurl + '?action=lema_apply_course_filter_sc';
            }
            lema.ui.loading.show();
            lema.request.get(url, function (response) {
                if (response && response.code == 200) {
                    $('.lema-sc-course-filter .lema-page-course-filter .lema-row').remove();
                    $('.lema-sc-course-filter .lema-page-course-filter ').html('<div class="lema-row">' + response.data + '</div>');
                }
                lema.ui.loading.hide();
            })
        }
    };

    lema.search.init();
})(jQuery, lema);
