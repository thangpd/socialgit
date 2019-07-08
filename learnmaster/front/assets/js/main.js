jQuery(function ($) {
    "use strict";
    var lema = window.lema || {};

    // Dropdown list

    lema.dropdownList = function () {
        var backdrop = '.dropdown-backdrop';
        var toggle = '[data-toggle="lema-dropdown"]';
        var Dropdown = function (element) {
            $(element).on('click.bs.lema-dropdown', this.toggle);
        };

        Dropdown.VERSION = '3.3.6';

        function getParent($this) {
            var selector = $this.attr('data-target');

            if (!selector) {
                selector = $this.attr('href');
                selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, ''); // strip for ie7
            }

            var $parent = selector && $(selector);

            return $parent && $parent.length ? $parent : $this.parent();
        }

        function clearMenus(e) {
            if (e && e.which === 3) return;
            $(backdrop).remove();
            $(toggle).each(function () {
                var $this = $(this);
                var $parent = getParent($this);
                var relatedTarget = {relatedTarget: this};

                if (!$parent.hasClass('open')) return;

                if (e && e.type == 'click' && /input|textarea/i.test(e.target.tagName) && $.contains($parent[0], e.target)) return;

                $parent.trigger(e = $.Event('hide.bs.lema-dropdown', relatedTarget));

                if (e.isDefaultPrevented()) return;

                $this.attr('aria-expanded', 'false');
                $parent.removeClass('open').trigger($.Event('hidden.bs.lema-dropdown', relatedTarget));
            });
        }

        Dropdown.prototype.toggle = function (e) {
            var $this = $(this);

            if ($this.is('.disabled, :disabled')) return;

            var $parent = getParent($this);
            var isActive = $parent.hasClass('open');

            clearMenus();

            if (!isActive) {
                if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
                    // if mobile we use a backdrop because click events don't delegate
                    $(document.createElement('div'))
                        .addClass('dropdown-backdrop')
                        .insertAfter($(this))
                        .on('click', clearMenus);
                }

                var relatedTarget = {relatedTarget: this};
                $parent.trigger(e = $.Event('show.bs.lema-dropdown', relatedTarget));

                if (e.isDefaultPrevented()) return;

                $this
                    .trigger('focus')
                    .attr('aria-expanded', 'true');

                $parent
                    .toggleClass('open')
                    .trigger($.Event('shown.bs.lema-dropdown', relatedTarget));
            }

            return false;
        };

        Dropdown.prototype.keydown = function (e) {
            if (!/(38|40|27|32)/.test(e.which) || /input|textarea/i.test(e.target.tagName)) return;

            var $this = $(this);

            e.preventDefault();
            e.stopPropagation();

            if ($this.is('.disabled, :disabled')) return;

            var $parent = getParent($this);
            var isActive = $parent.hasClass('open');

            if (!isActive && e.which != 27 || isActive && e.which == 27) {
                if (e.which == 27) $parent.find(toggle).trigger('focus');
                return $this.trigger('click');
            }

            var desc = ' li:not(.disabled):visible a';
            var $items = $parent.find('.dropdown-menu' + desc);

            if (!$items.length) return;

            var index = $items.index(e.target);

            if (e.which == 38 && index > 0) index--; // up
            if (e.which == 40 && index < $items.length - 1) index++; // down
            if (!~index) index = 0;

            $items.eq(index).trigger('focus');
        };


        // DROPDOWN PLUGIN DEFINITION
        // ==========================

        function Plugin(option) {
            return this.each(function () {
                var $this = $(this);
                var data = $this.data('bs.lema-dropdown');

                if (!data) $this.data('bs.lema-dropdown', (data = new Dropdown(this)));
                if (typeof option == 'string') data[option].call($this);
            });
        }

        var old = $.fn.dropdown;

        $.fn.dropdown = Plugin;
        $.fn.dropdown.Constructor = Dropdown;


        // DROPDOWN NO CONFLICT
        // ====================

        $.fn.dropdown.noConflict = function () {
            $.fn.dropdown = old;
            return this;
        };


        // APPLY TO STANDARD DROPDOWN ELEMENTS
        // ===================================

        $(document)
            .on('click.bs.lema-dropdown.data-api', clearMenus)
            .on('click.bs.lema-dropdown.data-api', '.lema-dropdown form', function (e) {
                e.stopPropagation();
            })
            .on('click.bs.lema-dropdown.data-api', toggle, Dropdown.prototype.toggle)
            .on('keydown.bs.lema-dropdown.data-api', toggle, Dropdown.prototype.keydown)
            .on('keydown.bs.lema-dropdown.data-api', '.dropdown-menu', Dropdown.prototype.keydown);
    };

    lema.mainFunction = function () {
        // $('.lema-course-all').slick({
        //     dots: true,
        //     infinite: false,
        //     speed: 300,
        //     slidesToShow: 1,
        //     slidesToScroll: 1,
        // });
    };
    // Lema Tab
    lema.tab = function () {
        if (localStorage.tabActivated !== undefined && $('[data-tab=' + localStorage.tabActivated + ']').length) {
            $('.lema-tabs .tab-link, .lema-tabs .tab-panel').removeClass('active');
            $('[data-tab=' + localStorage.tabActivated + '], [data-content=' + localStorage.tabActivated + ']').addClass('active');
        }
        $(document).on('click', '.lema-tabs .tab-list .tab-link', function (event) {
            event.preventDefault();
            var id_tablink = $(this).attr('data-tab');
            localStorage.setItem("tabActivated", id_tablink);
            $(this).parents('.tab-list').find('.tab-link').removeClass('active');
            $(this).addClass('active');
            $(this).parents('.lema-tabs').find('.tab-content-wrapper .tab-panel').removeClass('active');
            $(this).parents('.lema-tabs').find('.tab-content-wrapper .tab-panel[data-content=' + id_tablink + ']').addClass('active');
        });
    }

    // Lema Character Limit
    lema.characterLimit = function () {
        function charLimit(inputChar) {
            var char_limit = inputChar.attr('maxlength'),
                char_length = inputChar.val().length;
            inputChar.next().text(char_limit - char_length);
        }

        $('.lema-input-wrapper .character-input-limit').each(function () {
            charLimit($(this));
        });
        $(document).on('keyup', '.lema-input-wrapper .character-input-limit', function () {
            charLimit($(this));
        });
    }
    // Lema Popover
    lema.Popover = function () {
        $(document).on('click', '.lema-popover .btn-close', function (event) {
            event.preventDefault();
            /* Act on the event */
            $(this).parents('.lema-popover').remove();
        });
    }
    // Lema Alert 
    lema.Alert = function () {
        $(document).on('click', '.lema-alert .lema-alert-btn', function (event) {
            event.preventDefault();
            /* Act on the event */
            $(this).parents('.lema-alert').remove();
        });
    }

    // Rating view only
    lema.RatingViewOnly = function () {
        var $item = $('.lema-star-rating');
        $item.each(function () {
            var rating = ($(this).attr("data-rating") * 20);

            // alert(rating);
            $(this).children(".rating").css("width", "" + rating + "%");
        });
    }

    // textarea auto expand
    lema.TextareaExpand = function () {
        $(document)
            .one('focus.lema-autoExpand', 'textarea.lema-autoExpand', function () {
                var savedValue = this.value;
                this.value = '';
                this.baseScrollHeight = this.scrollHeight;
                this.value = savedValue;
            })
            .on('input.lema-autoExpand', 'textarea.lema-autoExpand', function () {
                var minRows = this.getAttribute('data-min-rows') | 0, rows;
                this.rows = minRows;
                rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 17);
                this.rows = minRows + rows;
            });

    }

    lema.upload = function () {
        var file_frame; // variable for the wp.media file_frame
        // attach a click event (or whatever you want) to some element on your page
        $(document).on('click', '#frontend-button', function (event) {
            event.preventDefault();

            // if the file_frame has already been created, just reuse it
            if (file_frame) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader_title'),
                button: {
                    text: $(this).data('uploader_button_text'),
                },
                multiple: false // set this to true for multiple file selection
            });

            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                // do something with the file here
                lema.log(attachment);
                //$( '#frontend-button' ).hide();
            });

            file_frame.open();
        });
    }
    lema.getInstructorData = function (id) {
        var content;
        var inputid = id;
        var editor = tinyMCE.get(inputid);
        var textArea = jQuery('textarea#' + inputid);
        if (textArea.length > 0 && textArea.is(':visible')) {
            content = textArea.val();
        } else {
            content = editor.getContent();
        }
        return content;
    }
    lema.ajax_form = function () {
        $('form.lema-ajax-form').submit(function (e) {
            e.preventDefault();
            // My goals Instructor metadata form: return value: string html
            var mygoals_id = ['instructor_goals', 'instructor_description'];

            var btn = $(this).find('[type=submit]');
            var btnHtml = btn.html();
            btn.find('i').attr('class', 'fa fa-spin fa-spinner');
            btn.prop('disabled', true);
            var data = $(this).serializeArray();
            $(data).each(function (i, field) {
                //add data from wp_editor to serialize form;
                $.each(mygoals_id,function(i,value){
                    if (field.name === value) {
                        field.name = 'meta[' + value + ']';
                        field.value = lema.getInstructorData(value);
                    }
                });
            });
            console.log(data);
            $('#lema-res-message').html('');
            $.ajax({
                type: 'POST',
                url: lemaConfig.ajaxurl,
                data: data,
            }).success(function (res) {
                btn.prop('disabled', false);
                btn.html(btnHtml);
                if (res.message !== '') {
                    $('#lema-res-message').html(res.message);
                } else {
                    location.reload();
                }
            })
        })
    }
    $(document).ready(function () {
        lema.ajax_form();
        lema.upload();
        lema.mainFunction();
        lema.tab();
        // lema.dropdownList();
        lema.characterLimit();
        lema.Popover();
        lema.Alert();
        lema.TextareaExpand();
    });
});