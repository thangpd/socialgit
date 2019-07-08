(function($) {
    'use strict';
    $(document).ready(function() {
        // wizard
        var steps_link = $('div.steps div a'),
            steps_contents = $('.step-content'),
            nexts = $('.nextBtn'),
            nextBar = $('.button-bar .button-next');

        steps_contents.hide();

        steps_link.click(function(e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                str = $(this).attr('href').slice(1),
                $item = $(this);
            // alert(str);
            if (!$item.hasClass('disabled')&& str!='step-5') {
                steps_link.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                $item.parents('.steps').find(".wizard-step").removeClass('current');
                $item.parents('.wizard-step').addClass('active current');
                $item.parents('.wizard-step').nextAll().removeClass('active');
                steps_contents.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
                $('.button-bar .button-next').attr('data-step', str);
                $('.button-bar .button-next.button-secondary').show();
            }
            if(str =="step-5"){
                steps_link.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                $item.parents('.steps').find(".wizard-step").removeClass('current');
                $item.parents('.wizard-step').addClass('active current');
                $item.parents('.wizard-step').nextAll().removeClass('active');
                $target.find('input:eq(0)').focus();
                $('.button-bar .button-next').attr('data-step', str);
                $('.button-bar .button-next.button-secondary').show();

            }
            if(str =="step-5"){
                 $('.button-bar .button-next.button-tertiary').text('Install');
            }
            else if(str=="step-6"){
                $('.button-bar .button-next.button-tertiary').text('Finish');
            }
            else{
                $('.button-bar .button-next.button-tertiary').text('Continue');
            }
        });

        nexts.click(function() {
            var curStep = $(this).closest(".step-content"),
                curStepBtn = curStep.attr("id"),
                nextwizard = $('div.steps div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='url']"),
                isValid = true;
            $(".form-group").removeClass("has-error");
            for (var i = 0; i < curInputs.length; i++) {
                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if (isValid) {
                if (curStepBtn == 'step-4') {
                    setTimeout(function() {
                        nextwizard.removeClass('disabled').trigger('click');
                    }, 1000);
                }
                else if (curStepBtn != 'step-5') {
                    $(this).parents('.well').find('fieldset').addClass('la-loading');
                    setTimeout(function() {
                        nextwizard.removeClass('disabled').trigger('click');
                        $('.well').find('fieldset').removeClass('la-loading');
                    }, 1000);
                }
                 else {
                     // $(this).parents('.well').find('fieldset').addClass('la-loading');
                    setTimeout(function() {
                        nextwizard.removeClass('disabled').trigger('click');
                        // $('.well').find('fieldset').removeClass('la-loading');
                    }, 5000);
                }
            }
        });
        nextBar.click(function() {
            var step = $(this).attr("data-step");
            var str = step.replace(/[^0-9]/gi, '');
            var num = parseInt(str);
            console.log(str);
            $(this).parents('.well').find('#' + step + ' .nextBtn').trigger('click');
            
                $('.button-bar .button-next').attr('data-step', 'step-' + parseInt(num + 1) + '');
                if ($('.button-bar .button-next').attr('data-step') == 'step-5') {

                    $('#ftp-modal').addClass('open');
                    $('body').append('<div class="rb-backdrop"></div>');
                    // setTimeout(function(){
                    //     $('.button-bar .button-next.button-tertiary').text('Install');
                    // },1000);
                } else if ($('.button-bar .button-next').attr('data-step') == 'step-6') {
                     setTimeout(function(){
                        $('.button-bar .button-next.button-tertiary').text('Finish');
                        $('.button-bar .button-next.button-secondary').hide();
                    },5000);

                } 
                else {
                    setTimeout(function(){
                        $('.button-bar .button-next.button-tertiary').text('Continue');
                        $('.button-bar .button-next.button-secondary').show();
                    },1000);
                }

        });

        $('div.steps div a.btn-primary').trigger('click');

        // End wizard

        // select 2
        $('.la-select-general').select2({
            placeholder: "Select option"
        })

        // temp payment method
        $(".wc-wizard-payment-gateways").on("change", ".wc-wizard-gateway-enable input", function() {
            $(this).is(":checked") ? $(this).closest("li").addClass("checked") : $(this).closest("li").removeClass("checked")
        });
        $(".wc-wizard-payment-gateways").on("click", "li.wc-wizard-gateway", function() {
            var b = $(this).find(".wc-wizard-gateway-enable input");
            b.prop("checked", !b.prop("checked")).change()
        });
        $(".wc-wizard-payment-gateways").on("click", "li.wc-wizard-gateway table, li.wc-wizard-gateway a", function(a) {
            a.stopPropagation()
        })

        // check demo list
        $('.rb-demo-list >.item').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            $('.rb-demo-list >.item').removeClass('active');
            $(this).addClass('active');
        });
        // check feature list and click action
        $('.rb-feature-list > .item').on('click', function(event) {
            event.preventDefault();
            $(this).toggleClass('active');
        });

        // click modal
        $('#ftp-modal .button-connect').on('click', function(e) {
            e.preventDefault();
            /* Act on the event */
            var curInputs = $(this).parents('#ftp-modal').find("input[type='text'],input[type='password']"),
            isValid = true;
            $(".la-form-group").removeClass("has-error");
            for (var i = 0; i < curInputs.length; i++) {
                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".la-form-group").addClass("has-error");
                }
            }
            if(isValid!=false){
                $('#ftp-modal').removeClass('open');
                $('.rb-backdrop').remove();
                $('#step-4 .nextBtn').click();
                $('.button-bar .button-next.button-tertiary').text('Install');
                steps_contents.hide();
                $('#step-5').show();
            }
        });

        // click add class install example 
        $('.button-bar .button-next').on('click', function() {
            var str = $(this).attr('data-step');
            var item = $('#step-5 .rb-table tbody >tr').length,
                i=0;
            if(str=='step-6') {
                $('#step-5 .rb-table tbody >tr:nth-child('+i+') .install-status').addClass('waiting');
                for(i=0; i <=item; i++){
                    $('#step-5 .rb-table tbody >tr:nth-child('+i+') .install-status').addClass('waiting').delay(parseInt(i*1000)).queue(function(){
                        $(this).removeClass("waiting").dequeue();
                        $(this).addClass('installed');
                    });
                    // $('#step-5 .rb-table tbody >tr:nth-child('+i+') .install-status').delay(parseInt(i*1000)).removeClass('waiting');
                    // $('#step-5 .rb-table tbody >tr:nth-child('+i+') .install-status').delay(parseInt(i*1000)).addClass('installed');
                }
            }
        });
    });
})(jQuery);
