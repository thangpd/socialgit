jQuery(function($) {
    "use strict";
    var lema = window.lema || {};
    // show all comments
    lema.showAllComments =function(){

    };
    //count height of description to show all or not
    lema.CheckShowAll=function (){
        var lengthOfContent=$(".lema-view-more-content").height();
        //120 is maxheight of .view-more class
        if(lengthOfContent<=110){
            lema.triggerShowall($(".lema-view-more .show-more-content"));
            $('.show-more-content').remove();
        }
    };
    //trigger Show All content of description
    lema.triggerShowall=function(thisbutton){
        if(thisbutton.hasClass('active')){
            thisbutton.removeClass('active').parents('.lema-view-more').find(".lema-view-more-content").removeClass('show-all');
        }
        else{
            thisbutton.addClass('active').parents('.lema-view-more').find(".lema-view-more-content").addClass('show-all');
        }
    };
    // view more
    lema.Viewmorefunction =function(){
        $(document).on("click",".lema-view-more .show-more-content",function(e){
            e.preventDefault();
            lema.triggerShowall($(this));
        })
    };

    // smoothscroll lema-sidebar-anchor
    lema.clickScroll = function(menu){
        $(menu + " div a[href^='#']").on("click", function(e){
            e.preventDefault();
            var anchor = $(this).attr('href');

            // smoothscroll effect
            $('html, body').animate({
                scrollTop: $(anchor).offset().top-100,},1000);

            // add active
            $(menu, 'div').removeClass('active');
            $(this).parent().addClass('active');
        });
    }
    $(document).ready(function() {
       
        lema.clickScroll('#lema-sidebar-anchor');
    });

    // sticky
    $(window).on('resize load', function(){
        var window_width = $(this).width();
        var heightfooter = $(".slz-wrapper-footer").height();
        if(window_width <= 991){
            $('#lema-sidebar-anchor').unstick();
        }
        else if(window_width <= 1024){
            $('#lema-sidebar-anchor').sticky({topSpacing: 40, bottomSpacing: heightfooter + 230});
        }
        else{
            $('#lema-sidebar-anchor').sticky({topSpacing: 20, bottomSpacing: heightfooter + 230});
        }
    });
    // init function
    $(document).ready(function() {
        lema.showAllComments();
        lema.Viewmorefunction();
        lema.CheckShowAll();
    });

});