var countdownInterval = null;

var Countdown = {
    init(duration, callback) {
        var element = this;
        var timer = duration, minutes, seconds;
        countdownInterval = setInterval(function() {
            minutes = parseInt(timer / 60, 10)
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            element.text(minutes + ":" + seconds);

            if (--timer < 0) {
                timer = duration;
            }
        }, 1000);
    },

    reset() {
        clearInterval(countdownInterval);
        this.text('');
    }
};

var Loading = function(state, message) {
    this.find('#otp').val('');

    if (state) {
        this.find('.loader-wrapper').show();
        this.find('.content').hide();
    } else {
        this.find('.loader-wrapper').hide();
        this.find('.content').show();
    }

    if (message == undefined || message == '') {
        this.find('.loader-wrapper .message').hide();
    } else {
        this.find('.loader-wrapper .message').text(message).show();
    }

    return this;
};

(function($) {
    $.fn.countDown = Countdown.init;
    $.fn.countDownReset = Countdown.reset;
    $.fn.loading = Loading;

    function displayError(message) {
        if (message == 'Client have existed in Shop') {
            return;
        }

        $('#messages').html(
            '<div class="notice notice-error settings-error is-dismissible">' +
            '<p><strong>' + message + '</strong></p>' +
            '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>' +
            '</div>'
            );
        $('.notice-dismiss').on('click', function() {
            $(this).closest('.notice').remove();
        });
    }

    function displayMessage(message) {
        $('#messages').html(
            '<div class="notice notice-success settings-error is-dismissible">' +
            '<p><strong>' + message + '</strong></p>' +
            '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>' +
            '</div>'
            );
        $('.notice-dismiss').on('click', function() {
            $(this).closest('.notice').remove();
        })
    }

    function getOTPPromise() {
        return $.ajax({
            url: Settings.platform_api_url.otp,
            type: 'POST',
            dataType: 'json',
            data: {
                phone: $('#ghn_registered_phone').val()
            },
            beforeSend: function() {
                $('#otp-modal').loading(true, 'Đang yêu cầu OTP').modal({
                    escapeClose: false,
                    clickClose: false,
                    showClose: true
                });
            }
        }).fail(function(error) {
            if (error.status == 0) {
                displayError('Yêu cầu OTP không thành công');
            } else if (error.status == 400) {
                displayError(error.responseJSON.code_message_value || error.responseJSON.message);
            }
            $.modal.close();
        });
    }

    function loadShops() {
        return $.ajax({
            'url': Settings.platform_api_url.shops,
            'method': 'GET',
            'timeout': 0,
            'headers': {
                'Content-Type': 'application/json',
                'Token': Settings.ghn_token
            }
        }).fail(function(error) {
            if (error.status == 0) {
                displayError('Lấy danh sách cửa hàng không thành công');
                $('#shops-list').html('');
                return null;
            }
        }).done(function(response) {
            var shopsHtml = response.data.map(function(shop) {
                var shopAttribute = [
                'data-shop_id="' + shop.id + '"',
                'data-shop_name="' + shop.name + '"',
                'data-shop_phone="' + shop.phone + '"',
                'data-shop_address="' + shop.address + '"',
                'data-district_id="' + shop.district_id + '"',
                'data-district_name="' + shop.combine_name + '"',
                'data-ward_code="' + shop.ward_code + '"',
                'data-ward_name="' + shop.ward_name + '"'
                ].join(' ');

                var name = '<div class="shop_name">' + shop.name + '</div>';
                var phone = '<div class="shop_phone">' + shop.phone + '</div>';
                var address = '<div class="shop_address">' + shop.address + '</div>';
                var region = '<div class="shop_address">' + [shop.ward_name, shop.combine_name.split(' - ').reverse().join(', ')].join(', ') + '</div>';

                return [
                '<div class="shops-list-item" id="shop_' + shop.id + '" ' + shopAttribute + '>',
                name,
                phone,
                address,
                region,
                '</div>'
                ].join('');
            }).join('');
            $('#shops-list').html(shopsHtml);
        });
    }

    function toggleEditShop() {
        $('#submit').show();
        $('#shop-form').hide();
        $('#shop-info').hide();
        $('#shops-list').html('Đang tải danh sách cửa hàng ...').show();
        loadShops().done(function() {
            $('#shops-list').find('#shop_' + Settings.ghn_shop_id).addClass('active');
            $('#shops-list').on('click', '.shops-list-item', function() {
                $('#shops-list').find('.shops-list-item.active').removeClass('active');
                $(this).addClass('active');
                $('#mulutu_options_ghn_shop_id').val($(this).data('shop_id'));
                $('#mulutu_options_ghn_shop_name').val($(this).data('shop_name'));
                $('#mulutu_options_ghn_shop_phone').val($(this).data('shop_phone'));
                $('#mulutu_options_ghn_shop_address').val($(this).data('shop_address'));
                $('#mulutu_options_ghn_district_id').val($(this).data('district_id'));
                $('#mulutu_options_ghn_ward_code').val($(this).data('ward_code'));
                $('#mulutu_options_ghn_district_name').val($(this).data('district_name'));
                $('#mulutu_options_ghn_ward_name').val($(this).data('ward_name'));
            });
        });
    }

    function toggleShopCreate() {
        $('#shops-list').hide();
        $('#shop-info').hide();
        $('#submit').hide();
        $('#shop-form').show();
        $('.btn-cancel').click(function(e) {
            e.preventDefault();
            $('#submit').show();
            $('#shop-info').show();
            $('#shop-form').hide();
        });

        // Districts, wards settle
        $('#view_ghn_shop_districts').select2();
        $('#view_ghn_shop_wards').select2();
        $.ajax({
            'url': Settings.platform_api_url.districts,
            'method': 'GET',
            'timeout': 0,
            'headers': {
                'Content-Type': 'application/json',
                'Token': Settings.ghn_token
            }
        }).done(function(response) {
            if (response.status == 0) {
                return;
            }
            response.data.map(function(district) {
                var newOption = new Option(district.combine_name, district.district_id, false, false);
                $('#view_ghn_shop_districts').append(newOption);
            });
            $('#view_ghn_shop_districts').trigger({
                type: 'select2:select',
                params: {
                    data: {
                        id: response.data[0].district_id,
                        text: response.data[0].combine_name
                    }
                }
            });
        });
        $('#view_ghn_shop_districts').on('select2:select', function(e) {
            var data = e.params.data;
            $.ajax({
                'url': Settings.platform_api_url.wards.replace(':district_id', data.id),
                'method': 'GET',
                'timeout': 0,
                'headers': {
                    'Content-Type': 'application/json',
                    'Token': Settings.ghn_token
                }
            }).done(function(response) {
                if (response.status == 0) {
                    return;
                }
                $('#view_ghn_shop_wards').find('option').remove();
                response.data.map(function(ward) {
                    var newOption = new Option(ward.ward_name, ward.ward_code, false, false);
                    $('#view_ghn_shop_wards').append(newOption);
                });
                $('#view_ghn_shop_wards').trigger({
                    type: 'select2:select',
                    params: {
                        data: {
                            id: response.data[0].ward_code,
                            text: response.data[0].ward_name
                        }
                    }
                });
            });
        });
    }

    function updateShopToAffiliate(data) {
        return $.ajax({
            url: Settings.platform_api_url.create_affiliate_with_shop,
            type: 'POST',
            dataType: 'json',
            data: {
                ...data,
                phone: $('#ghn_registered_phone').val()
            },
            beforeSend: function() {
                $('#otp-modal').loading(true, 'Đang cập nhật');
            }
        });
    }

    function createShopWithAffiliate(data) {
        return $.ajax({
            url: Settings.platform_api_url.create_affiliate,
            type: 'POST',
            dataType: 'json',
            data: {
                ...data,
                phone: $('#ghn_registered_phone').val()
            },
            beforeSend: function() {
                $('#otp-modal').loading(true, 'Đang tạo cửa hàng');
            }
        });
    }

    function updateShopWebhookMapping(ghn_shop_id = null, ghn_token = null) {
        $.ajax({
            url: Settings.platform_api_url.create_shop_webhook_mapping,
            type: 'POST',
            dataType: 'json',
            data: {
                shop_id: ghn_shop_id || Settings.ghn_shop_id,
                webhook_url: window.location.origin + '/?rest_route=/' + Settings.webhook_url,
                ghn_token: ghn_token || Settings.ghn_token
            },
            beforeSend: function() {
                $('#otp-modal').loading(true, 'Đang tạo cửa hàng');
            }
        });
    }

    $(document).ready(function() {
        $('.btn-shop-edit').click(function(e) {
            e.preventDefault();
            toggleEditShop();
        });

        $('.btn-shop-create').click(function(e) {
            e.preventDefault();
            toggleShopCreate();
        });

        $('#otp-modal').on($.modal.BEFORE_CLOSE, function(event, modal) {
            $('#countdown').countDownReset();
        });

        // Update a shop to affiliate
        if (!!needUpdateAffiliate) {
            updateShopWebhookMapping();
            getOTPPromise().done(function(otpResponse) {
                $('#countdown').countDown(10 * 60);
                $('#otp-modal').loading(false);
                $('#btn-otp-submit').unbind('click').bind('click', function(e) {
                    e.preventDefault();
                    var otp = $('#otp').val();
                    if (otp.length < 4 || otp.length > 10) {
                        $('#otp-message').text('OTP không hợp lệ');
                        return false;
                    }

                    $('#countdown').countDownReset();
                    updateShopToAffiliate({
                        shop_id: $('#mulutu_options_ghn_shop_id').val(),
                        otp
                    }).fail(function(affiliateResponse) {
                        if (affiliateResponse.status == 0) {
                            displayError('Lỗi cập nhật thông tin gian hàng, vui lòng thử lại');
                        } else if (affiliateResponse.status == 400) {
                            displayError(affiliateResponse.responseJSON.code_message_value || affiliateResponse.responseJSON.message);
                        }
                    }).done(function(affiliateResponse) {
                        displayMessage(affiliateResponse.responseJSON.code_message_value || affiliateResponse.responseJSON.message);
                    }).always(function() {
                        $.modal.close();
                    });
                });
            });
        }

        // Create shop with affiliate
        $('.btn-submit-shop').on('click', function(e) {
            e.preventDefault();
            getOTPPromise().done(function(otpResponse) {
                $('#countdown').countDown(10 * 60);
                $('#otp-modal').loading(false);
                $('#btn-otp-submit').unbind('click').bind('click', function(e) {
                    e.preventDefault();
                    var otp = $('#otp').val();
                    if (otp.length < 4 || otp.length > 10) {
                        $('#otp-message').text('OTP không hợp lệ');
                        return false;
                    }

                    $('#countdown').countDownReset();
                    createShopWithAffiliate({
                        address: $('#view_ghn_shopaddress').val(),
                        district_id: parseInt($('#view_ghn_shop_districts').val()) || 0,
                        ward_code: $('#view_ghn_shop_wards').val(),
                        otp
                    }).done(function(createAffiliateResponse) {
                        $.modal.close();
                        if (createAffiliateResponse.code == 400) {
                            displayError(createAffiliateResponse.code_message_value || createAffiliateResponse.message);
                            throw new Error(createAffiliateResponse.code_message_value || createAffiliateResponse.message);
                        }
                        updateShopWebhookMapping(createAffiliateResponse.data.shop_id);
                        displayMessage('Đã tạo cửa hàng thành công');
                        $('.btn-cancel').click();
                    });
                });
            });
        });
    });
})(jQuery);
