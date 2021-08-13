jQuery(document).ready(function () {
    admin_bell();

    if ($('#admin-bell').length) {
        setInterval(function () {
            admin_bell();
        }, 30000);
    }
});

const data = {};
data['bell'] = 0;
data['support'] = 0;
data['vote'] = 0;
data['logo'] = 0;
data['photo'] = 0;
data['complaint'] = 0;
data['freeTeam'] = 0;


function admin_bell() {
    $.ajax({
        dataType: 'json',
        success: function (data) {
            const seoTitle = $('title');
            const titleText = seoTitle.text().split(')');
            $('#admin-bell').html(data.bell);
            if (data.bell > 0) {
                seoTitle.text('(' + data.bell + ') ' + titleText[titleText.length - 1]);
            } else {
                seoTitle.text(titleText[titleText.length - 1]);
            }

            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    $('.admin-' + key).html(data[key]);
                    if (data[key] > 0) {
                        $('.panel-' + key).show();
                    } else {
                        $('.panel-' + key).hide();
                    }
                }
            }
        },
        url: $('#admin-bell').data('url')
    });
}