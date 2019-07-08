/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
;(function($, lema){
    "use strict";
    var category = window.category || {};

    // Dropdown list
    category.dropdownList = function() {
        var backdrop = '.dropdown-backdrop';
        var toggle = '[data-toggle="lema-dropdown"]';
        var Dropdown = function(element) {
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
            $(toggle).each(function() {
                var $this = $(this);
                var $parent = getParent($this);
                var relatedTarget = { relatedTarget: this };

                if (!$parent.hasClass('open')) return;

                if (e && e.type == 'click' && /input|textarea/i.test(e.target.tagName) && $.contains($parent[0], e.target)) return;

                $parent.trigger(e = $.Event('hide.bs.lema-dropdown', relatedTarget));

                if (e.isDefaultPrevented()) return;

                $this.attr('aria-expanded', 'false');
                $parent.removeClass('open').trigger($.Event('hidden.bs.lema-dropdown', relatedTarget));
            });
        }

        Dropdown.prototype.toggle = function(e) {
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

                var relatedTarget = { relatedTarget: this };
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

        Dropdown.prototype.keydown = function(e) {
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
            return this.each(function() {
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

        $.fn.dropdown.noConflict = function() {
            $.fn.dropdown = old;
            return this;
        };


        // APPLY TO STANDARD DROPDOWN ELEMENTS
        // ===================================

        $(document)
            .on('click.bs.lema-dropdown.data-api', clearMenus)
            .on('click.bs.lema-dropdown.data-api', '.lema-dropdown form', function(e) { e.stopPropagation(); })
            .on('click.bs.lema-dropdown.data-api', toggle, Dropdown.prototype.toggle)
            .on('keydown.bs.lema-dropdown.data-api', toggle, Dropdown.prototype.keydown)
            .on('keydown.bs.lema-dropdown.data-api', '.dropdown-menu', Dropdown.prototype.keydown);
    };

    category.mainFunction = function() {
        // $('.lema-course-all').slick({
        //     dots: true,
        //     infinite: false,
        //     speed: 300,
        //     slidesToShow: 1,
        //     slidesToScroll: 1,
        // });
    };
    category.collapseDropdown = function() {
        $('.lema-category-dropdown-block.inline-list-style').each(function(index, el) {
            var itemWidth = $(this).width();
            var i, itemsmw = 0;
            for(i = 0; i < $(this).find('.course-categories-list > .cate-item').length; i++) {
                itemsmw += $(this).find('.course-categories-list > .cate-item:eq(' + i + ')').innerWidth();
                itemsmw += parseInt($(this).find('.course-categories-list > .cate-item:eq(' + i + ')').css('margin-right'));
                lema.log(itemsmw);
                if((itemsmw + 40) >= itemWidth) {
                    if($('.course-categories-list .more').length) {
                        $more = $('.course-categories-list .more');
                    }
                    else {
                        var $more = $('<li class="more lema-dropdown has-sub"><a href="javascript:void(0)" class="link">more</a><ul class="dropdown-menu"></ul></li>');
                    }
                    $more.insertAfter($(this).find('.course-categories-list >.cate-item:eq(' + i + ')'));
                    break;
                }
            }
            for (var j = $(this).find('.course-categories-list > .cate-item').length; j >= i; j--) {
                $(this).find('.course-categories-list > .cate-item:eq(' + j + ')').prependTo($(this).find('.course-categories-list .dropdown-menu'));
            }
            $(".more > a", this).on("click",function(){
                $('.more .has-sub').removeClass('open');
                $(this).closest(".more").toggleClass("open").siblings("li.has-sub").removeClass("open");
            })
        });
    };
    category.cateDropdown =function() {
        var $win = $(window);
        var $el = $(".lema-category-dropdown-block");
        $(document).on("click.Bst", $win, function(event){ 
                if ($('.lema-category-dropdown-block').has(event.target).length == 0 && !$('.lema-category-dropdown-block').is(event.target)){
                    $('.lema-category-dropdown-block').removeClass("open");
                    $('.lema-category-dropdown-block').find('li.has-sub').removeClass('open');
                } 
            });
        $(document).on('click','.lema-category-dropdown-block >.lema-dropdown-toggle',function(e){
            e.preventDefault();
            $(this).parents('.lema-category-dropdown-block').toggleClass('open');
        })
         $(document).on('click','.course-categories-list .arrow-has-sub',function(){
            if($(this).closest('li.has-sub').has("ul")){
                $(this).closest('li.has-sub').toggleClass('open').siblings("li.has-sub").removeClass("open");
            }
        })
    };
    lema.shortcodes.register('lema_course_category', {
        run : function () {
            // category.mainFunction();
            lema.log('Category is running from default');
            // category.dropdownList();
            // category.collapseDropdown();
            category.cateDropdown();
            var $window = $(window).width();
            if($window >1024) {
                category.collapseDropdown();
            }
            
        }
    });
})(jQuery, lema);
