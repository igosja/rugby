jQuery(document).ready(function () {
    jQuery(document).ready(function () {
        $(document)
            .on('click', '.show-full-table', function () {
                $('.show-full-table').hide();
                var table_list = $('table');
                table_list.find('th').removeClass('hidden-xs');
                table_list.find('td').removeClass('hidden-xs');
            })
            .on('change', '#select-squad, #select-national-squad', function () {
                var line_id = $(this).val();
                var url = $(this).data('url');
                $.ajax({
                    url: url + '?squad=' + line_id
                });
            })
            .on('click', '#btnTransferApplicationFrom', function () {
                $('#formTransferApplicationFrom').submit();
            })
            .on('click', '#btnLoanApplicationFrom', function () {
                $('#formLoanApplicationFrom').submit();
            })
            .on('change', '.submit-on-change', function () {
                $(this).closest('form').submit();
            })
            .on('click', '.physical-change-cell', function () {
                var physical_id = $(this).data('physical');
                var player_id = $(this).data('player');
                var schedule_id = $(this).data('schedule');
                var url = $('#physical-available').data('url');

                $.ajax({
                    url: url + '?physicalId=' + physical_id + '&playerId=' + player_id + '&scheduleId=' + schedule_id,
                    dataType: 'json',
                    success: function (data) {
                        for (var i = 0; i < data['list'].length; i++) {
                            var list_id = $('#' + data['list'][i].id);
                            list_id.removeClass(data['list'][i].remove_class_1);
                            list_id.removeClass(data['list'][i].remove_class_2);
                            list_id.addClass(data['list'][i].class);
                            list_id.data('physical', data['list'][i].physical_id);
                            list_id.html(
                                '<img alt="'
                                + data['list'][i].physical_name
                                + '%" src="/img/physical/'
                                + data['list'][i].physical_id
                                + '.png" title="'
                                + data['list'][i].physical_name
                                + '%">'
                            );
                        }
                        $('#physical-available').html(data['available']);
                    }
                });
            })
            .on('click', '.planning-change-cell', function () {
                var tire = $(this).data('tire');
                var player_id = $(this).data('player');
                var schedule_id = $(this).data('schedule');
                var age = $(this).data('age');
                var game_row = $(this).data('game_row');
                var game_row_old = $(this).data('game_row_old');
                var url = $('#planning-available').data('url');

                $.ajax({
                    url: url
                        + '?tire=' + tire
                        + '&playerId=' + player_id
                        + '&scheduleId=' + schedule_id
                        + '&age=' + age
                        + '&gameRow=' + game_row
                        + '&gameRowOld=' + game_row_old,
                    dataType: 'json',
                    success: function (data) {
                        for (var i = 0; i < data['list'].length; i++) {
                            var list_id = $('#' + data['list'][i].id);
                            list_id.removeClass(data['list'][i].remove_class_1);
                            list_id.removeClass(data['list'][i].remove_class_2);
                            list_id.addClass(data['list'][i].class);
                            list_id.data('tire', data['list'][i].tire);
                            list_id.data('game_row', data['list'][i].game_row);
                            list_id.data('game_row_old', data['list'][i].game_row_old);
                            list_id.html(data['list'][i].tire);
                        }
                    }
                });
            })
            .on('change', '#stadium-increase-input', function () {
                var stadiumIncreaseInput = $(this);
                var capacityNew = parseInt(stadiumIncreaseInput.val());
                var stadiumIncreaseSitPrice = stadiumIncreaseInput.data('sit_price');
                var url = stadiumIncreaseInput.data('url');

                var stadiumIncreaseCurrent = stadiumIncreaseInput.data('current');

                var price = getIncreasePrice(capacityNew, stadiumIncreaseCurrent, stadiumIncreaseSitPrice);

                $.ajax({
                    url: url + '?value=' + price,
                    dataType: 'json',
                    success: function (data) {
                        $('#stadium-increase-price').html(data.value);
                    }
                });
            })
            .on('change', '#stadium-decrease-input', function () {
                var stadiumDecreaseInput = $(this);
                var capacityNew = parseInt(stadiumDecreaseInput.val());
                var stadiumIncreaseSitPrice = stadiumDecreaseInput.data('sit_price');
                var url = stadiumDecreaseInput.data('url');

                var stadiumIncreaseCurrent = stadiumDecreaseInput.data('current');

                var price = getDecreasePrice(capacityNew, stadiumIncreaseCurrent, stadiumIncreaseSitPrice);

                $.ajax({
                    url: url + '?value=' + price,
                    dataType: 'json',
                    success: function (data) {
                        $('#stadium-decrease-price').html(data.value);
                    }
                });
            })
            .on('keydown', '#chat-text', function (e) {
                if (e.ctrlKey && e.keyCode === 13) {
                    $(this).closest('form').submit();
                }
            })
            .on('beforeSubmit', '#chat-form', function () {
                var chatForm = $(this);
                $.ajax({
                    data: chatForm.serialize(),
                    type: 'post',
                    url: chatForm.attr('action'),
                    success: function () {
                        chatForm.find('textarea').val('');
                        chatMessage(false);
                    }
                });
                return false;
            }).on('click', '#relation-link', function () {
            var relation_body = $('.relation-body');
            if (relation_body.hasClass('hidden')) {
                relation_body.removeClass('hidden');
            } else {
                relation_body.addClass('hidden');
            }
        }).on('click', '.forum-quote', function () {
            var forumMessageField = $('#forummessage-text');
            var forumMessageText = forumMessageField.val() + $(this).data('text');
            forumMessageField.val(forumMessageText);
        }).on('click', '.link-template-save', function () {
            $('.div-template-load').hide(400);
            $('.div-template-save').toggle(400);
        }).on('click', '.link-template-load', function () {
            $('.div-template-save').hide(400);
            $('.div-template-load').toggle(400);
        }).on('beforeValidate', '#sign-up-form', function () {
            gtag('event', 'sign_up');
        });
    });

});

function getIncreasePrice(capacityNew, capacityCurrent, oneSitPrice) {
    return parseInt((Math.pow(capacityNew, 1.1) - Math.pow(capacityCurrent, 1.1)) * oneSitPrice);
}

function getDecreasePrice(capacityNew, capacityCurrent, oneSitPrice) {
    return parseInt((Math.pow(capacityCurrent, 1.1) - Math.pow(capacityNew, 1.1)) * oneSitPrice);
}

function chatMessage(scroll) {
    var messageChat = $('.chat-scroll');
    $.ajax({
        url: messageChat.data('url') + '?lastDate=' + messageChat.data('date'),
        success: function (data) {
            var html = '';
            for (var i = 0; i < data.message.length; i++) {
                html = html + '<div class="row margin-top">'
                    + '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-size-3">'
                    + data.message[i].date
                    + ', '
                    + data.message[i].userLink
                    + '</div></div><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 message '
                    + data.message[i].class
                    + '">'
                    + data.message[i].text
                    + '</div></div>';
            }
            messageChat.append(html);
            messageChat.data('date', data.date);
            if (scroll) {
                messageChat.scrollTop(messageChat.prop('scrollHeight'));
            }
        }
    });
}
