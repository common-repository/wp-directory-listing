jQuery(document).ready(function ($) {


    /*
    * On changse Field
    */
    $(document).on('change', '.meta-fields .meta-field .meta-field-type-selector', function () {

        if( $.inArray( $(this).val(), [ 'select', 'checkbox', 'radio' ] ) !== -1 ) {
            $(this).parent().find('.meta-type-data').fadeIn();
        }
        else {
            $(this).parent().find('.meta-type-data').fadeOut();
        }

    });


    /*
    * Remove Meta Field
    */
    $(document).on('click', '.meta-fields .meta-field-controller .remove-meta-field', function () {

        if( ! $(this).hasClass('icofont-check') ) {
            $(this).removeClass('icofont-close').addClass('icofont-check');
            return;
        }

        meta_field = $(this).parent().parent();
        meta_field.fadeOut();
        setTimeout(function () {
            meta_field.remove();
        }, 400);
    });


    /*
    * Add Meta Field
    */

    $(document).on('click', '.add-new-meta-field', function () {

        if( $(this).hasClass('wpdl-working') ) return;

        __html__ = $(this).html();
        $(this).addClass('wpdl-working').html( wpdl.text.working );

        group_id = $(this).attr('group-id');

        if( typeof group_id === "undefined" || group_id.length == 0 ) return;

        $.ajax(
            {
                type: 'POST',
                url: wpdl.ajaxurl,
                context: this,
                data: {
                    "action": "wpdl_add_meta_field",
                    "group_id" : group_id,
                },
                success: function (response) {
                    if (response.success) {
                        $(response.data).css('display', 'none').appendTo($(this).parent()).fadeIn('400');
                    }
                    $(this).removeClass('wpdl-working').html( __html__ );
                }
            });
    });


    /*
    * Toggle Meta Group
    */

    $(document).on('click', '.meta-field-groups .group-controller .expand-meta-group', function () {

        meta_field_group = $(this).parent().parent().parent();
        meta_field_group.find('.meta-fields').slideDown();

        $(this).removeClass( 'fa-chevron-up fa-chevron-down');

        if (meta_field_group.hasClass('meta_field_group_active')) {
            meta_field_group.find('.meta-fields').slideUp();
            $(this).addClass( 'fa-chevron-down' );
        } else {
            $(this).addClass( 'fa-chevron-up' );
        }

        meta_field_group.toggleClass('meta_field_group_active');
    });


    /*
    * Remove Meta Group
    */

    $(document).on('click', '.meta-field-groups .group-controller .remove-meta-group', function () {

        if( ! $(this).hasClass('icofont-check') ) {
            $(this).removeClass('icofont-close').addClass('icofont-check');
            return;
        }

        meta_field_group = $(this).parent().parent().parent();
        meta_field_group.slideUp();
        setTimeout(function () {
            meta_field_group.remove();
        }, 400);
    });


    /*
    * Add new Meta Group
    */

    $(document).on('click', '.add-new-meta-group', function () {

        if( $(this).hasClass('wpdl-working') ) return;

        __html__ = $(this).html();
        $(this).addClass('wpdl-working').html( wpdl.text.working );

        $.ajax(
            {
                type: 'POST',
                url: wpdl.ajaxurl,
                context: this,
                data: {
                    "action": "wpdl_add_meta_group",
                },
                success: function (response) {
                    if (response.success) $(response.data).css('display', 'none').appendTo($('.meta-field-groups')).slideDown('400');
                    $(this).removeClass('wpdl-working').html( __html__ );
                }
            });
    })
});
