jQuery(document).ready(function () {
    'use strict';
    jQuery('.sale_price_dates_field').each(function () {
        var $these_sale_dates = jQuery(this);
        var sale_schedule_set = false;
        var $wrap = $these_sale_dates.parent().parent();

        $these_sale_dates.find('input[type="text"]').each(function () {
            if ('' !== jQuery(this).val()) {
                sale_schedule_set = true;
            }
        });
        if (sale_schedule_set) {
            $wrap.find('.sale_schedule').hide();
            $wrap.find('.sale_price_dates_field').show();
        } else {
            $wrap.find('.sale_schedule').show();
            $wrap.find('.sale_price_dates_field').hide();
        }
    });
    jQuery('#woocommerce-product-data').on('click', '.sale_schedule', function () {
        var $wrap = jQuery(this).parent().parent().parent();
        jQuery(this).hide();
        $wrap.find('.cancel_sale_schedule').show();
        $wrap.find('.sale_price_dates_field').show();
        return false;
    });
    jQuery('#woocommerce-product-data').on('click', '.cancel_sale_schedule', function () {
        var $wrap = jQuery(this).parent().parent().parent();
        jQuery(this).hide();
        $wrap.find('.sale_schedule').show();
        $wrap.find('.sale_price_dates_field').hide();
        $wrap.find('.sale_price_dates_field').find('input').val('');
        return false;
    });
    jQuery('.sale_price_dates_field').each(function () {
        jQuery(this).find('input[type="text"]').datepicker({
            defaultDate: '',
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            showButtonPanel: true,
            onSelect: function () {
                date_picker_select(jQuery(this));
            }
        });
        jQuery(this).find('input[type="text"]').each(function () {
            date_picker_select(jQuery(this));
        });
    });
    jQuery('#_woo_ctr_progress_bar_initial').unbind().on('click',function () {
        let goal = jQuery(this).closest('.woo-sctr-countdown-timer-admin-product').find('#_woo_ctr_progress_bar_goal').val();
        if (!goal) {
            jQuery(this).closest('.woo-sctr-countdown-timer-admin-product').find('#_woo_ctr_progress_bar_goal').focus();
            return false;
        }
    }).on('change',function () {
        let goal = parseInt(jQuery(this).closest('.woo-sctr-countdown-timer-admin-product').find('#_woo_ctr_progress_bar_goal').val()),
            init = parseInt(jQuery(this).val());
        if (init > goal) {
            jQuery(this).val('');
        }
    });
    jQuery('#woocommerce-product-data').on('woocommerce_variations_loaded', function (event) {
        jQuery('.sale_price_dates_field').each(function () {
            jQuery(this).find('input[type="text"]').datepicker({
                defaultDate: '',
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true,
                onSelect: function () {
                    date_picker_select(jQuery(this));
                }
            });
            jQuery(this).find('input[type="text"]').each(function () {
                date_picker_select(jQuery(this));
            });
        });
        jQuery('.sale_price_dates_field').each(function () {
            var $these_sale_dates = jQuery(this);
            var sale_schedule_set = false;
            var $wrap = $these_sale_dates.parent().parent();

            $these_sale_dates.find('input[type="text"]').each(function () {
                if ('' !== jQuery(this).val()) {
                    sale_schedule_set = true;
                }
            });
            if (sale_schedule_set) {
                $wrap.find('.sale_schedule').hide();
                $wrap.find('.sale_price_dates_field').show();
            } else {
                $wrap.find('.sale_schedule').show();
                $wrap.find('.sale_price_dates_field').hide();
            }
        });

        //select variation display default
        jQuery('._woo_ctr_display_enable').unbind().on('click', function () {
            if (jQuery(this).prop('checked')) {
                jQuery('._woo_ctr_display_enable').prop('checked', false);
                jQuery(this).prop('checked', true);
            }
        });

        jQuery('._woo_ctr_progress_bar_initial').unbind().on('click',function () {
            let goal = jQuery(this).closest('.woo-sctr-countdown-timer-admin-product').find('._woo_ctr_progress_bar_goal').val();
            if (!goal) {
                jQuery(this).closest('.woo-sctr-countdown-timer-admin-product').find('._woo_ctr_progress_bar_goal').focus();
                return false;
            }
        }).on('change',function () {
            let goal = parseInt(jQuery(this).closest('.woo-sctr-countdown-timer-admin-product').find('._woo_ctr_progress_bar_goal').val()),
                init = parseInt(jQuery(this).val());
            if (init > goal) {
                jQuery(this).val('');
            }
        });
    });

    // Date picker fields.
    function date_picker_select(datepicker) {
        var option = jQuery(datepicker).next().next().is('.hasDatepicker') ? 'minDate' : 'maxDate', otherDateField,
            date;
        otherDateField = 'minDate' === option ? jQuery(datepicker).next().next() : jQuery(datepicker).prev().prev();
        date = jQuery(datepicker).datepicker('getDate');
        if (!date) {
            option = 'minDate';
            date = new Date();
        }
        jQuery(otherDateField).datepicker('option', option, date);
        jQuery(datepicker).trigger('change');
    }
});