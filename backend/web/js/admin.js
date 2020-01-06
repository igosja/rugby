jQuery(document).ready(function () {
    admin_bell();

    if ($('#admin-bell').length) {
        setInterval(function () {
            admin_bell();
        }, 30000);
    }
});

function admin_bell() {
    $.ajax({
        dataType: 'json',
        success: function (data) {
            var seoTitle = $('title');
            var titleText = seoTitle.text().split(')');
            $('#admin-bell').html(data.bell);
            if (data.bell > 0) {
                seoTitle.text('(' + data.bell + ') ' + titleText[titleText.length - 1]);
            } else {
                seoTitle.text(titleText[titleText.length - 1]);
            }

            $('.admin-support').html(data.support);
            if (data.support > 0) {
                $('.panel-support').show();
            } else {
                $('.panel-support').hide();
            }

            $('.admin-poll').html(data.poll);
            if (data.poll > 0) {
                $('.panel-poll').show();
            } else {
                $('.panel-poll').hide();
            }

            $('.admin-logo').html(data.logo);
            if (data.logo > 0) {
                $('.panel-logo').show();
            } else {
                $('.panel-logo').hide();
            }

            $('.admin-photo').html(data.photo);
            if (data.photo > 0) {
                $('.panel-photo').show();
            } else {
                $('.panel-photo').hide();
            }

            $('.admin-complaint').html(data.complaint);
            if (data.complaint > 0) {
                $('.panel-complaint').show();
            } else {
                $('.panel-complaint').hide();
            }

            $('.admin-freeTeam').html(data.freeTeam);
        },
        url: $('#admin-bell').data('url')
    });
}