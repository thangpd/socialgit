;(function($, lema) {
    lema.search = {
        init : function () {
            var self = this;
            $(document).ready(function() {
                jQuery.fn.draggit = function (el) {
                    var thisdiv = this;
                    var thistarget = $(el);
                    var relX;
                    var relY;
                    var targetw = thistarget.width();
                    var targeth = thistarget.height();
                    var docw;
                    var doch;
                    var ismousedown;
                    thistarget.css('position','absolute');
                    thisdiv.bind('mousedown', function(e){
                        var pos = $(el).offset();
                        var srcX = pos.left;
                        var srcY = pos.top;

                        docw = $(document).width();
                        doch = $(document).height();

                        relX = e.pageX - srcX;
                        relY = e.pageY - srcY;

                        ismousedown = true;
                    });

                    $(document).bind('mousemove', function(e){
                        if(ismousedown)
                        {
                            targetw = thistarget.width();
                            targeth = thistarget.height();

                            var maxX = docw - targetw - 10;
                            var maxY = doch - targeth - 10;

                            var mouseX = e.pageX;
                            var mouseY = e.pageY;

                            var diffX = mouseX - relX;
                            var diffY = mouseY - relY;

                            // check if we are beyond document bounds ...
                            if(diffX < 0)   diffX = 0;
                            if(diffY < 0)   diffY = 0;
                            if(diffX > maxX) diffX = maxX;
                            if(diffY > maxY) diffY = maxY;

                            $(el).css('top', (diffY)+'px');
                            $(el).css('left', (diffX)+'px');
                        }
                    });

                    $(window).bind('mouseup', function(e){
                        ismousedown = false;
                    });

                    return this;
                } // end jQuery draggit function //

                /*$(".lema-modal").wrap("<div class='wrapper'>");
                $(".lema-modal .lema-page-advanced-search").wrapAll("<div class='lema-modal-body'>");
                $(".lema-modal").append("<div class='lema-modal-button'><a href='#'>Close</a></div>");
                $(".lema-modal-button a").click(function(event) {
                    event.preventDefault();
                    $(".lema-modal").parent().fadeOut();
                });*/
                $(document).on('click', "[data-modal]", function() {
                    var toload = $(this).attr("data-modal");
                    $("."+toload).parent().fadeIn();
                });
                $(document).click(function(e){
                    /*var elems = document.getElementsByClassName('wrapper');
                    for (var i=0;i<elems.length;i+=1){
                        if(elems[i].style.display == 'block'){
                            $('body').css('overflow-y', 'hidden');
                        }
                        else {
                            $('body').css('overflow-y', 'auto');
                        }
                    }
                    if($(e.target).is(".wrapper")){
                        $(".wrapper").fadeOut();
                        $('body').css('overflow-y', 'auto');
                    }*/
                });
                $(document).on('change', '#lema-search-form input[type=checkbox], #lema-search-form input[type=radio]', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    var name =  $(this).attr('name');
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
                    var name =  $(this).attr('name');
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
                $(document).on('click', '.button-view .view-more', function () {
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
            });
        },
        addFilter : function (name, value) {
            var reg = /\[(.+)\]\[(.+)\]/;
            var param = name.match(reg);

            if(param){
                name = param[1];
                value = param[2];
                lema.utils.updateQueryStringParameterSearch(location.href, param[1], param[2], true);
            }else{
                lema.utils.updateQueryStringParameter(location.href, name, value, true);
            }

            this.applyFilter(name);
        },
        removeFiter : function (name, value) {
            var reg = /\[(.+)\]\[(.+)\]/;
            var param = name.match(reg);

            if(param){
                name = param[1];
                value = param[2];
                lema.utils.removeQueryStringParameterSearch(location.href, param[1], param[2], true);
            }else{
                lema.utils.removeQueryStringParameter(location.href, name, value, true);
            }
            this.applyFilter(name);
        },
        applyFilter : function (name) {
            //Apply filter to current searching result
            lema.utils.removeQueryStringParameter(location.href, 'paging', false, true);
            var filters =  document.location.search;
            var url = '';
            if (filters.length > 1) {
                url = lemaConfig.ajaxurl + filters + '&action=lema_apply_course_filter&change=' + name;
            } else {
                url = lemaConfig.ajaxurl  + '?action=lema_apply_course_filter';
            }
            lema.ui.loading.show();
            lema.request.get(url,  function (response) {
                if (response && response.code == 200) {
                    $('.lema-shortcode-lema_courselist_filtered').remove();
                    $('.lema-filter-course').remove();
                    $('#lema-filtered-courses').html(response.data);
                    $('#lema-filtered-filters').html(response.filters);

                }
                lema.ui.loading.hide();
            })
        }
    };

    lema.search.init();
})(jQuery, lema);
