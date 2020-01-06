jQuery(document).ready(function () {
    $(document)
        .on('click', '.show-full-table', function () {
            $('.show-full-table').hide();
            var table_list = $('table');
            table_list.find('th').removeClass('hidden-xs');
            table_list.find('td').removeClass('hidden-xs');
        })
        .on('change', '.submit-on-change', function () {
            $(this).closest('form').submit();
        });
});
