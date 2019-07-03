(function f() {
    "use strict";

    jQuery(function () {
        $('#item').change(function () {
            $('#item-color').find('option:not(:first)').remove().end().prop('disabled', true);
            var item_id = $(this).val();

            if (item_id == 0) {
                return;
            }

            $.ajax({
                type: "POST",
                url: "create-size",
                dataType: "json",
                data: "query=getColors&item_id=" + item_id,
                error: function () {
                    alert("При выполнении запроса возникла ошибка");
                },
                success: function (data) {
                    for (var i in data) {
                        $('#item-color').append('<option value="' + i + '">' + data[i] + '</option>');
                    }
                    $('#item-color').prop('disabled', false);
                }
            })
        });

    })
})();
