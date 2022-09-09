(function($) {
    $('#billing_country_field').hide();
    $('#ship-to-different-address-checkbox').hide();

    var objBillingDistrict = $('#billing_district');
    var objBillingWard = $('#billing_ward');

    objBillingDistrict.select2();
    objBillingWard.select2();

    reloadDistricts();
    reloadWards();
    reloadShippingFee();

    function reloadDistricts() {
        $.ajax({
            url: MulutuCheckout.districts_api,
            type: 'GET',
            dataType: 'json',
            headers: {
                'Token': MulutuCheckout.ghn_token,
                'Content-Type': 'application/json'
            }
        }).done(function(districtsResponse) {
            objBillingDistrict.empty();
            for (var i = 0; i < districtsResponse.data.length; i++) {
                objBillingDistrict.append(
                    new Option(districtsResponse.data[i].combine_name, districtsResponse.data[i].district_id, false, false)
                    );
            }
            objBillingDistrict.trigger({
                type: 'select2:select',
                params: {
                    data: {
                        id: districtsResponse.data[0].district_id,
                        text: districtsResponse.data[0].combine_name
                    }
                }
            });
        });
    }

    function reloadWards() {
        objBillingDistrict.on('select2:select', function(e) {
            var data = e.params.data;
            $.ajax({
                url: MulutuCheckout.district_wards_api.replace(':district_id', data.id),
                type: 'GET',
                dataType: 'json',
                headers: {
                    'Token': MulutuCheckout.ghn_token,
                    'Content-Type': 'application/json'
                }
            }).done(function(wardsResponse) {
                objBillingWard.empty();
                for (var i = 0; i < wardsResponse.data.length; i++) {
                    objBillingWard.append(
                        new Option(wardsResponse.data[i].ward_name, wardsResponse.data[i].ward_code, false, false)
                        );
                }
                objBillingWard.trigger({
                    type: 'select2:select',
                    params: {
                        data: {
                            id: wardsResponse.data[0].ward_code,
                            text: wardsResponse.data[0].ward_name
                        }
                    }
                });
            });
        });
    }

    function reloadShippingFee() {
        objBillingWard.on('select2:select', function() {
            var toDistrictId = objBillingDistrict.val();
            var toWardCode = objBillingWard.val();

            if (toDistrictId == -1 || toWardCode == -1) {
                return;
            }

            var districtText = $('#billing_district option:selected').text().split(' - ');
            var wardText = $('#billing_ward option:selected').text();
            var address1Text = $('#billing_address_1_text').val();

            $('#billing_address_1').val(address1Text + ', ' + wardText + ', ' + districtText.pop());
            $('#billing_city').val(districtText.join(' - '));
            $('#shipping_city').val(districtText.join(' - '));

            $.ajax({
                url: MulutuCheckout.fees_api,
                method: 'POST',
                timeout: 0,
                dataType: 'json',
                headers: {
                    'Token': MulutuCheckout.ghn_token,
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    shop_id          : +MulutuCheckout.shop_id,
                    from_district_id : +MulutuCheckout.from_district_id,
                    from_ward_code   : MulutuCheckout.from_ward_code,
                    to_district_id   : +toDistrictId,
                    to_ward_code     : toWardCode,
                    width            : +MulutuCheckout.width,
                    height           : +MulutuCheckout.height,
                    length           : +MulutuCheckout.length,
                    weight           : +MulutuCheckout.weight,
                    coupon           : ''
                })
            }).done(function(feesResponse) {
                $('.tmp_mulutu_service_fee').remove();
                var wrapper = objBillingDistrict.closest('.woocommerce-billing-fields__field-wrapper');
                for (var i = 0; i < feesResponse.data.length; i++) {
                    wrapper.append(
                        '<input class="tmp_mulutu_service_fee" type="hidden" name="service_fee[' + i + '][id]" value="' + feesResponse.data[i].service_id + '">' +
                        '<input class="tmp_mulutu_service_fee" type="hidden" name="service_fee[' + i + '][title]" value="' + feesResponse.data[i].short_name + '">' +
                        '<input class="tmp_mulutu_service_fee" type="hidden" name="service_fee[' + i + '][cost]" value="' + feesResponse.data[i].fee.total + '">' +
                        '<input class="tmp_mulutu_service_fee" type="hidden" name="service_fee[' + i + '][leadtime]" value="' + feesResponse.data[i].leadtime.leadtime + '">' +
                        '<input class="tmp_mulutu_service_fee" type="hidden" name="service_fee[' + i + '][order_date]" value="' + feesResponse.data[i].leadtime.order_date + '">'
                        );
                }

                $('body').trigger('update_checkout');
                $(document).on('updated_checkout', function() {
                    const regex = /#(\d*)#/;
                    $('.woocommerce-shipping-methods li label').each(function() {
                        this.innerHTML = this.innerHTML.replace(regex, function(match, p) {
                            return ' <span class="leadtime"> - ' + (new Date(p * 1000)).toLocaleDateString('en-GB') + '</span>'
                        });
                    });
                });
            });
        });
    }
})(jQuery);
