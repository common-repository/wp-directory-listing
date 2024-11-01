jQuery(document).ready(function ($) {


    $(document).on('click', '.wpdl-fav-button', function () {

        directory_id = $(this).data('dirid');
        status = $(this).data('status');

        $.ajax(
            {
                type: 'POST',
                url: wpdl.ajaxurl,
                context: this,
                data: {
                    "action": "wpdl_fav_button_clicked",
                    "directory_id": directory_id,
                    "status": status,
                },
                success: function (response) {

                    if (response.success) {
                        $(this).removeClass(status).addClass(response.data).attr('status', response.data);
                    }
                }
            });
    })

    /**
     * Single Directory Rating field
     */

    $('.wpdl-rating-field li')
        .on('mouseover', function () {

            var onStar = parseInt($(this).data('value'), 10);

            $(this).parent().children('li.star').each(function (e) {
                if (e < onStar) $(this).addClass('hover');
                else $(this).removeClass('hover');
            });
        })
        .on('mouseout', function () {

            $(this).parent().children('li.star').each(function (e) {
                $(this).removeClass('hover');
            });
        });

    $(document).on('change', '.directory-archive-sorting select', function () {

        $('.directory-archive-sorting').submit();
    })

    $(document).on('click', '.wpdl-rating-field li', function () {

        var onStar = parseInt($(this).data('value'), 10);
        var stars = $(this).parent().children('li.star');

        for (i = 0; i < stars.length; i++) {
            $(stars[i]).removeClass('selected');
        }

        for (i = 0; i < onStar; i++) {
            $(stars[i]).addClass('selected');
        }

        ratingValue = $('.wpdl-rating-field li.selected').last().attr('data-value');
        $(this).parent().parent().find('input[type=hidden]').val(ratingValue);
    });


    /*
     * Single Directory Tab system
     */

    $(document).on('click', 'ul.wpdl-tabs-head > li > a', function () {

        if ($(this).parent().hasClass('active')) return;

        $('.wpdl-tabs .wpdl-tabs-panel').hide();
        $('#' + $(this).data('target')).fadeIn();

        $('.wpdl-tabs .wpdl-tabs-head li').removeClass('active');
        $(this).parent().addClass('active');
    })


    /**
     * Slider with Gallery Images
     */

    $('.wpdl-directory-gallery-images').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: '<i class="fa fa-angle-right arrow-right" aria-hidden="true"></i>',
        nextArrow: '<i class="fa fa-angle-left arrow-left" aria-hidden="true"></i>',
        fade: true,
        adaptiveHeight: true,
        asNavFor: '.wpdl-directory-gallery-navs'
    });

    $('.wpdl-directory-gallery-navs').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.wpdl-directory-gallery-images',
        dots: false,
        arrows: false,
        centerMode: true,
        adaptiveHeight: true,
        focusOnSelect: true
    });

});