(function($) {
    $.fn.mulutuOrderInject = function(newContent, options) {
        // Hide current wp-body-content to display mulutu-order-iframe
        $('#wpbody-content').attr('id', 'wpbody-content-old').hide();

        // Remove last iframe
        $('#mulutu-order-iframe').remove();

        // Insert new iframe
        $('#wpbody').prepend(newContent);
        // $('#mulutu-order-iframe').css({
        //     'margin-top': document.querySelector('.woocommerce-layout__header').offsetHeight + 'px',
        //     height: '1000px'
        // });

        // Insert breadcrumb
        $('.tmp_breadcrumb').remove();
        if (!!options.orderUrl) {
            var secondBreadcrumItem = $('.woocommerce-layout__header-breadcrumbs').find('span:eq(1)');
            $('.woocommerce-layout__header-breadcrumbs').find('span:eq(1)').html(
                "<a href='" + options.orderUrl + "'>" + secondBreadcrumItem.text() + "</a>"
            );
            $('.woocommerce-layout__header-breadcrumbs').append(
                "<span class='tmp_breadcrumb'>" + options.pageTitle + "</span>"
            );
        }

        // Insert close button
        $('#woocommerce-activity-panel .woocommerce-layout__activity-panel-tabs').append(
            "<button class='tmp_breadcrumb' id='mulutu-order-iframe-close' title='Đóng'><i class='close-icon'>x</i>Đóng</button>"
        );

        // Autosize
        document.querySelector('#mulutu-order-iframe').contentWindow.postMessage('abc', '*');
        window.addEventListener('message', function(e) {
            var data = JSON.parse(e.data);
            if (!!data.eventType) {
                if (data.eventType == 'resize') {
                    $('#mulutu-order-iframe').css({
                        height: data.height + 50 + 'px'
                    });
                }
                if (data.eventType == 'forceScrollTop') {
                    window.scrollTo(0, 0);
                }
            }
        });

        // Handle callback
        $('#mulutu-order-iframe-close').unbind('click');
        $(document).on('click', '#mulutu-order-iframe-close', function(e) {
            e.preventDefault();
            $('.tmp_breadcrumb').remove();
            $('#mulutu-order-iframe').remove();
            $('#mulutu-order-iframe-close').remove();
            $('#wpbody-content-old').attr('id', 'wpbody-content').show();
            if (!!options && !!options.onClosed) {
                var callback = options.onClosed;
                callback();
            }
            return;
        });
    }

    function generateIframe(url) {
        return '<iframe id="mulutu-order-iframe" width="100%" height="100%" frameborder="0" src="' + url + '" frameborder="0"></iframe>';
    }

    function getIframeURL(button, preOrderInfoAPIURL, needCheckUpdatedAt) {
        $.get(preOrderInfoAPIURL).done(function(response) {
            if (!!needCheckUpdatedAt) {
                if (response.data.updated_at != button.attr('data-updated_at')) {
                    window.location.reload();
                }
            }

            button.text(response.data.text)
                .removeAttr('disabled')
                .attr('data-action', response.data.action)
                .attr('data-updated_at', response.data.updated_at || '')
                .click(function(e) {
                    e.preventDefault();
                    button.text('...');
                    $('#wpbody').mulutuOrderInject(generateIframe(response.data.url), {
                        pageTitle: button.text(),
                        onClosed: function() {
                            getIframeURL(button, button.attr('data-api-url'), true);
                        }
                    });
                });
        });
    }

    function getIframeURLFromWCList(button, preOrderInfoAPIURL) {
        button.addClass('opening');
        if (button.attr('href') == '#') {
            $.ajax({
                url: preOrderInfoAPIURL,
                method: 'GET',
                async: false
            }).done(function(response) {
                button.text(response.data.text)
                    .attr('data-action', response.data.action)
                    .attr('href', response.data.url);
            });
        }

        $('#wpbody').mulutuOrderInject(generateIframe(button.attr('href')), {
            orderUrl: document.location.href,
            pageTitle: button.text(),
            onClosed: function() {
                if (!button.hasClass('opening')) {
                    return;
                }
                button.removeClass('opening');
                button.text('...');
                $.get(preOrderInfoAPIURL).done(function(newResponse) {
                    button
                        .attr('data-action', newResponse.data.action)
                        .text(newResponse.data.text)
                        .removeAttr('disabled')
                        .attr('href', newResponse.data.url);
                })
            }
        });
    }

    function getOrderHistory(button, orderInfoAPIURL) {
        $.get(orderInfoAPIURL).done(function(response) {
            if (response.status != 1) {
                return;
            }

            var historyHTML = [];
            for (var key in response.data.history) {
                if (response.data.history.hasOwnProperty(key)) {
                    var item = response.data.history[key];
                    var time = new Date(item.time);

                    item.status = GHN_ORDER_STATUS[item.status] || item.status;

                    historyHTML.push(
                        '<li><p>' +
                        time.toLocaleDateString('en-GB') + ' ' +
                        time.toLocaleTimeString('en-GB', { hour12: false }) + '</p><span>' +
                        item.status +
                        '</span></li>'
                    );
                }
            }
            if (historyHTML.length > 0) {
                $('#order-status-title').show();
                button.html('<ul>' + historyHTML.reverse().join('') + '</ul>');
            }
        });
    }

    $(document).ready(function() {
        if ($('#btnWCMetaBoxOrder').length != 0) {
            var button = $('#btnWCMetaBoxOrder');
            getIframeURL(button, button.attr('data-api-url'));
        }

        $('.btn-wclist-order').unbind('click').click(function(e) {
            e.preventDefault();
            getIframeURLFromWCList($(this), $(this).attr('data-api-url'));
        });

        if ($('#order-history').length != 0) {
            var button = $('#order-history');
            getOrderHistory(button, button.attr('data-api-url'));
        }
    });
})(jQuery);
