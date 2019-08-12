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


        var $gallery = $('.gallery a');
        if ($gallery.length) {
            $gallery.simpleLightbox();
        }

        $(".toggle-mnu").click(function() {
            $(this).toggleClass("on");
            $("#matildamenu").slideToggle();
            return false;
        });
    });

    $(document).on('click','#js-send-form',function(e){

        e.preventDefault();
        var $btn = $(this);
        var html = $btn.html();
        var url = $btn.attr('data-url');
        var $result = $('#form-message');
        $result.html('');

        if($btn.hasClass('disabled')){
            return false;
        }

        var $name = $('#form-name');
        var $email = $('#form-email');
        var $telefon = $('#form-telefon');
        var $anliegen = $('#form-anliegen');
        var $nachricht = $('#form-nachricht');
        var termin = 'Nein';
        if($('#checkbox3').is(':checked')){
            termin = 'Ja';
        }
        var wagen = 'Nein';
        if($('#checkbox4').is(':checked')){
            wagen = 'Ja';
        }
        var datenschutz = 'Nein';
        if($('#checkbox5').is(':checked')){
            datenschutz = 'Ja';
        }

        if(datenschutz === 'Nein'){
            $result.html('Bitte lesen Sie die Hinweise zur Datenverarbeitung');
            return false;
        }

        $btn.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i> Sende...');

        $.post(url, {
            name: $.trim($name.val()),
            email: $.trim($email.val()),
            telefon: $.trim($telefon.val()),
            anliegen: $.trim($anliegen.val()),
            nachricht: $.trim($nachricht.val()),
            wagen: wagen,
            termin: termin,
            datenschutz: datenschutz
        })
            .done(function (data) {
                $btn.html(html).removeClass('disabled');
                if (data.result === 'success') {
                    $result.removeClass('text-danger').addClass('text-success').html(data.message);
                    $btn.remove();
                } else {
                    $result.html(data.message);
                    return false;
                }
            })
            .fail(function () {
                $result.html('Fehler beim Versenden der Email. Bitte versuchen Sie es erneut.');
                $btn.html(html).removeClass('disabled');
                return false;
            })
        ;

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