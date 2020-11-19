jQuery(document).ready(function () {
    let position_array = [];
    let current;
    let other;
    let prompt;
    let i;
    let j;
    let line_player;
    let line_player_id;
    let captainSelect = $('#captain');

    let captain_id = captainSelect.data('id');
    let select_captain_html = '<option value="0"></option>';

    let players = [
        player_1_id,
        player_2_id,
        player_3_id,
        player_4_id,
        player_5_id,
        player_6_id,
        player_7_id,
        player_8_id,
        player_9_id,
        player_10_id,
        player_11_id,
        player_12_id,
        player_13_id,
        player_14_id,
        player_15_id,
    ];
    let data = [
        {'current': player_1_id, 'prompt': '1 -', 'position_array': player_1_array},
        {'current': player_2_id, 'prompt': '2 -', 'position_array': player_2_array},
        {'current': player_3_id, 'prompt': '3 -', 'position_array': player_3_array},
        {'current': player_4_id, 'prompt': '4 -', 'position_array': player_4_array},
        {'current': player_5_id, 'prompt': '5 -', 'position_array': player_5_array},
        {'current': player_6_id, 'prompt': '6 -', 'position_array': player_6_array},
        {'current': player_7_id, 'prompt': '7 -', 'position_array': player_7_array},
        {'current': player_8_id, 'prompt': '8 -', 'position_array': player_8_array},
        {'current': player_9_id, 'prompt': '9 -', 'position_array': player_9_array},
        {'current': player_10_id, 'prompt': '10 -', 'position_array': player_10_array},
        {'current': player_11_id, 'prompt': '11 -', 'position_array': player_11_array},
        {'current': player_12_id, 'prompt': '12 -', 'position_array': player_12_array},
        {'current': player_13_id, 'prompt': '13 -', 'position_array': player_13_array},
        {'current': player_14_id, 'prompt': '14 -', 'position_array': player_14_array},
        {'current': player_15_id, 'prompt': '15 -', 'position_array': player_15_array},
    ];

    for (i = 0; i < data.length; i++) {
        current = data[i]['current'];
        prompt = data[i]['prompt'];
        position_array = data[i]['position_array'];
        other = [...players];
        other.splice(i, 1);
        let select_html = '<option value="0">' + prompt + '</option>';

        for (j = 0; j < position_array.length; j++) {
            if (position_array[j][0] === current) {
                select_html = select_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '" selected>' + position_array[j][1] + '</option>';
                if (captain_id === current) {
                    select_captain_html = select_captain_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '" selected>' + position_array[j][1] + '</option>';
                } else {
                    select_captain_html = select_captain_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '">' + position_array[j][1] + '</option>';
                }
            } else if (-1 === $.inArray(position_array[j][0], other)) {
                select_html = select_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '">' + position_array[j][1] + '</option>';
            }
        }

        $('#line-' + (i + 1)).html(select_html);
    }

    captainSelect.html(select_captain_html);

    player_change();
    if ($('.div-template-load')) {
        get_templates();
    }

    $('.lineup-change').on('change', function () {
        let position = parseInt($(this).data('position'));
        let player_id = parseInt($(this).val());
        let captain_id = parseInt(captainSelect.val());

        let players = [
            parseInt($('#line-1').val()),
            parseInt($('#line-2').val()),
            parseInt($('#line-3').val()),
            parseInt($('#line-4').val()),
            parseInt($('#line-5').val()),
            parseInt($('#line-6').val()),
            parseInt($('#line-7').val()),
            parseInt($('#line-8').val()),
            parseInt($('#line-9').val()),
            parseInt($('#line-10').val()),
            parseInt($('#line-11').val()),
            parseInt($('#line-12').val()),
            parseInt($('#line-13').val()),
            parseInt($('#line-14').val()),
            parseInt($('#line-15').val())
        ];

        let data = [
            {'prompt': '1 -', 'position_array': player_1_array},
            {'prompt': '2 -', 'position_array': player_2_array},
            {'prompt': '3 -', 'position_array': player_3_array},
            {'prompt': '4 -', 'position_array': player_4_array},
            {'prompt': '5 -', 'position_array': player_5_array},
            {'prompt': '6 -', 'position_array': player_6_array},
            {'prompt': '7 -', 'position_array': player_7_array},
            {'prompt': '8 -', 'position_array': player_8_array},
            {'prompt': '9 -', 'position_array': player_9_array},
            {'prompt': '10 -', 'position_array': player_10_array},
            {'prompt': '11 -', 'position_array': player_11_array},
            {'prompt': '12 -', 'position_array': player_12_array},
            {'prompt': '13 -', 'position_array': player_13_array},
            {'prompt': '14 -', 'position_array': player_14_array},
            {'prompt': '15 -', 'position_array': player_15_array},
        ];

        let select_captain_html = '<option value="0"></option>';
        for (i = 0; i < data.length; i++) {
            line_player = $('#line-' + (i + 1));
            line_player_id = parseInt(line_player.val());
            prompt = data[i]['prompt'];
            position_array = data[i]['position_array'];
            let select_html = '<option value="0">' + prompt + '</option>';

            for (j = 0; j < position_array.length; j++) {
                if (position_array[j][0] === player_id) {
                    if (i === position - 1) {
                        select_html = select_html + '<option selected value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '">' + position_array[j][1] + '</option>';
                        if (captain_id === player_id) {
                            select_captain_html = select_captain_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '" selected>' + position_array[j][1] + '</option>';
                        } else {
                            select_captain_html = select_captain_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '">' + position_array[j][1] + '</option>';
                        }
                    } else {
                        if (-1 === $.inArray(position_array[j][0], players)) {
                            select_html = select_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '">' + position_array[j][1] + '</option>';
                        }
                    }
                } else {
                    if (position_array[j][0] === line_player_id) {
                        select_html = select_html + '<option selected value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '">' + position_array[j][1] + '</option>';
                        if (captain_id === line_player_id) {
                            select_captain_html = select_captain_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '" selected>' + position_array[j][1] + '</option>';
                        } else {
                            select_captain_html = select_captain_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '">' + position_array[j][1] + '</option>';
                        }
                    } else {
                        if (-1 === $.inArray(position_array[j][0], players)) {
                            select_html = select_html + '<option value="' + position_array[j][0] + '" style="background-color: ' + position_array[j][2] + '">' + position_array[j][1] + '</option>';
                        }
                    }
                }
            }

            $('#line-' + (i + 1)).html(select_html);
        }

        captainSelect.html(select_captain_html);
    });

    $('.player-change').on('change', function () {
        player_change();
    });

    $('#template-save-submit').on('click', function () {
        $.ajax({
            'data': $('#template-save, #lineup-send').serialize(),
            'method': 'post',
            'url': $('#template-save').attr('action'),
            'complete': function () {
                get_templates();
                $('.div-template-save').hide(400);
                $('.div-template-load').show(400);
            }
        });
        return false;
    });

    $(document).on('click', '.template-delete', function () {
        $.ajax({
            beforeSend: function () {
                return confirm("Вы собираетесь удалить шаблон. Вы уверены?");
            },
            'url': $(this).data('url'),
            'complete': function () {
                get_templates();
            }
        });
    });

    $(document).on('click', '.template-load', function () {
        $.ajax({
            'dataType': 'json',
            'url': $(this).data('url'),
            'success': function (data) {
                $('#gamesend-tactic_1').val(data.lineup_template_tactic_id_1);
                $('#gamesend-tactic_2').val(data.lineup_template_tactic_id_2);
                $('#gamesend-tactic_3').val(data.lineup_template_tactic_id_3);
                $('#gamesend-tactic_4').val(data.lineup_template_tactic_id_4);
                $('#gamesend-rudeness_1').val(data.lineup_template_rudeness_id_1);
                $('#gamesend-rudeness_2').val(data.lineup_template_rudeness_id_2);
                $('#gamesend-rudeness_3').val(data.lineup_template_rudeness_id_3);
                $('#gamesend-rudeness_4').val(data.lineup_template_rudeness_id_4);
                $('#gamesend-style_1').val(data.lineup_template_style_id_1);
                $('#gamesend-style_2').val(data.lineup_template_style_id_2);
                $('#gamesend-style_3').val(data.lineup_template_style_id_3);
                $('#gamesend-style_4').val(data.lineup_template_style_id_4);
                $('#line-0-0').val(data.lineup_template_player_gk_1);
                $('#line-1-0').val(data.lineup_template_player_gk_2);
                $('#line-1-1').val(data.lineup_template_player_ld_1);
                $('#line-1-2').val(data.lineup_template_player_rd_1);
                $('#line-1-3').val(data.lineup_template_player_lw_1);
                $('#line-1-4').val(data.lineup_template_player_cf_1);
                $('#line-1-5').val(data.lineup_template_player_rw_1);
                $('#line-2-1').val(data.lineup_template_player_ld_2);
                $('#line-2-2').val(data.lineup_template_player_rd_2);
                $('#line-2-3').val(data.lineup_template_player_lw_2);
                $('#line-2-4').val(data.lineup_template_player_cf_2);
                $('#line-2-5').val(data.lineup_template_player_rw_2);
                $('#line-3-1').val(data.lineup_template_player_ld_3);
                $('#line-3-2').val(data.lineup_template_player_rd_3);
                $('#line-3-3').val(data.lineup_template_player_lw_3);
                $('#line-3-4').val(data.lineup_template_player_cf_3);
                $('#line-3-5').val(data.lineup_template_player_rw_3);
                $('#line-4-1').val(data.lineup_template_player_ld_4);
                $('#line-4-2').val(data.lineup_template_player_rd_4);
                $('#line-4-3').val(data.lineup_template_player_lw_4);
                $('#line-4-4').val(data.lineup_template_player_cf_4);
                $('#line-4-5').val(data.lineup_template_player_rw_4).trigger('change');
                $('#captain').val(data.lineup_template_captain);
                player_change();
            }
        });
    });

    $('#reset-lineup').on('click', function () {
        $('#line-0-0').val('');
        $('#line-1-0').val('');
        $('#line-1-1').val('');
        $('#line-1-2').val('');
        $('#line-1-3').val('');
        $('#line-1-4').val('');
        $('#line-1-5').val('');
        $('#line-2-1').val('');
        $('#line-2-2').val('');
        $('#line-2-3').val('');
        $('#line-2-4').val('');
        $('#line-2-5').val('');
        $('#line-3-1').val('');
        $('#line-3-2').val('');
        $('#line-3-3').val('');
        $('#line-3-4').val('');
        $('#line-3-5').val('');
        $('#line-4-1').val('');
        $('#line-4-2').val('');
        $('#line-4-3').val('');
        $('#line-4-4').val('');
        $('#line-4-5').val('').trigger('change');
        $('#captain').val('');
    });
});

function player_change() {
    $('.tr-player').removeClass('info');

    let player_change = $('.player-change');

    for (let i = 0; i < player_change.length; i++) {
        $('#tr-' + $(player_change[i]).val()).addClass('info');
    }

    send_ajax();
}

function send_ajax() {
    let form = $('.game-form');
    let form_data = form.serialize();
    let url = form.data('url');

    $.ajax({
        data: form_data,
        dataType: 'json',
        method: 'post',
        url: url,
        success: function (data) {
            $('.span-power').html(data.power);
            $('.span-power-line-1').html(data.power_line_1);
            $('.span-power-line-2').html(data.power_line_2);
            $('.span-power-line-3').html(data.power_line_3);
            $('.span-power-line-4').html(data.power_line_4);
            $('.span-position-percent').html(data.position);
            $('.span-lineup-percent').html(data.lineup);
            $('.span-teamwork-1').html(data.teamwork_1);
            $('.span-teamwork-2').html(data.teamwork_2);
            $('.span-teamwork-3').html(data.teamwork_3);
            $('.span-teamwork-4').html(data.teamwork_4);
        }
    });
}

function get_templates() {
    $.ajax({
        url: $('.div-template-load').data('url'),
        success: function (data) {
            $('.div-template-load').html(data);
        }
    });
}