 /**
  * @copyright © 2017 by Solazu Co.,LTD
  * @project Learn Master Plugin
  *
  * @since 1.0
  *
  */

 ;(function($, lema){
     lema.search = lema.search || {};

     function isCharKey(e) {
         var keycode = e.keyCode;
         var valid =
             (keycode > 47 && keycode < 58)   || // number keys
             keycode == 32 || keycode == 13   || // spacebar & return key(s) (if you want to allow carriage returns)
             (keycode > 64 && keycode < 91)   || // letter keys
             (keycode > 95 && keycode < 112)  || // numpad keys
             (keycode > 185 && keycode < 193) || // ;=,-./` (in order)
             (keycode > 218 && keycode < 223);   // [\]' (in order)

         return valid;
     }
     lema.search.box = {
         init : function () {

             var search_elms = $('.lema-autocomplete-search input[name="q"]');
             if (search_elms.length > 0) {
                 search_elms.each(function (idx, dom) {
                     new autoComplete({
                         selector: dom,
                         delay: 150,
                         source: function (term, source) {
                             lema.request.abort();
                             var input = $(dom); // input element
                             var action = input.data('action');
                             var url = lemaConfig.ajaxurl + '?action=' + action + '&term=' + term;
                             var button = input.parent().find('.lema-searchbox-submit').first();
                             var btbk = button.html();
                             button.html('<i class="fa fa-spinner fa-spin"></i>');
                             lema.request.get(url, function (response) {
                                 button.html(btbk);
                                 if (response && response.code === 200) {
                                     source(response.data);
                                 }
                             });

                         },
                         cache: false,
                         renderItem: function (item, search) {
                             /*var prefix = '';
                             prefix = '<div class="search-box-suggest-title" style="font-size: 12px; font-weight: bold"><u>Quick result(s)</u> : </div>';
                             return prefix + '<div class="autocomplete-suggestion" data-value="' + item.keyword + '" style="clear: both"><div style="float: left"><img src="' + item.thumb +  '"/></div> <div style="float: right"> ' + item.title  + '</div></div>';*/
                             return item;
                         },
                         onSelect: function (e, term, item) {
                             var input = $(dom);
                             input.val($(item).data('value'));
                             input.closest('.lema-search-box-form').submit();
                         }
                     });
                 });
             }


             var sTimeout;
             /*$(document).on('keyup', '#lema-searchbox-input', function(e) {
                 if (isCharKey(e)) {
                     clearTimeout(sTimeout);
                     lema.request.abort();
                     var action = $(this).data('action');
                     var term = $(this).val();
                     var url = lemaConfig.ajaxurl + '?action=' + action + '&term=' + term;
                     sTimeout = setTimeout(function(){
                         lema.request.get(url, function (response) {
                             if (response && response.code == 200) {

                             }
                         });
                     }, 100);
                 }

             });*/

             $(document).on('submit', '.lema-search-box-form', function (e) {
                 e.stopPropagation();
                 e.preventDefault();
                 lema.request.abort();
                 var form_elm = $(this);
                 var url = location.href;
                 if (url.indexOf(lemaConfig.urls.lema_search) < 0) {
                     url = lemaConfig.urls.lema_search;
                 }
                 var name = $('.lema-searchbox-input', form_elm).attr('name');
                 var value = $('.lema-searchbox-input', form_elm).val();
                 url = lema.utils.updateQueryStringParameter(url, name, value, false);
                 window.location.href = url;
                 return false;
             });

            /* new Awesomplete('#lema-searchbox-input', {
                 list: ["aol.com", "att.net", "comcast.net", "facebook.com", "gmail.com", "gmx.com", "googlemail.com", "google.com", "hotmail.com", "hotmail.co.uk", "mac.com", "me.com", "mail.com", "msn.com", "live.com", "sbcglobal.net", "verizon.net", "yahoo.com", "yahoo.co.uk"],
                 data: function (text, input) {
                     return input.slice(0, input.indexOf("@")) + "@" + text;
                 },
                 filter: Awesomplete.FILTER_STARTSWITH
             });*/
         }

     };

     lema.search.box.init();
 })(jQuery, lema);