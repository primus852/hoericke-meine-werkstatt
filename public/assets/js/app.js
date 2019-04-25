(function ($) {
    "use strict";
    $(document).ready(function () {
        $('#nav-expander').on('click', function (e) {
            e.preventDefault();
            $('body').toggleClass('nav-expanded');
        });
        $('#nav-close').on('click', function (e) {
            e.preventDefault();
            $('body').removeClass('nav-expanded');
        });
        $(".header").sticky({
            topSpacing:0,
            responsiveWidth: true,
            zIndex: 15
        });
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $('.carousel').carousel({
        interval: 4000
    });

    $(window).on('load', function () {
        $("#preloader").on(500).fadeOut();
        $(".preloader").on(600).fadeOut("slow");
    });

})(jQuery);