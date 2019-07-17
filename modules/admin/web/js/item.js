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

        $("div[id^='dynamic_fields-'] a#add-field").on('click', function () {

            // find id of parent's div
            var id = $(this).parent().attr('id');

            // clone element
            $('#' + id).find('.form-group:last').after($('#' + id).find('.form-group:last').clone());

            $('#' + id).find('.form-group:last input').val('');

            //count input's
            var count = $('#' + id).find('.form-group').length;

            console.log(count);

            if (count >= 2) {
                $('#' + id).find('a#minus-field').show();
            } else {
                $('#' + id).find('a#minus-field').hide();
            }
        });

        $("div[id^='dynamic_fields-'] a#minus-field").on('click', function () {

            // find id of parent's div
            var id = $(this).parent().attr('id');

            // count input's
            var count = $('#' + id).find('.form-group').length;

            if (count > 2) {
                $('#' + id).find('.form-group:last').remove();
            } else if (count === 2) {
                $('#' + id).find('.form-group:last').remove();
                $('#' + id).find('a#minus-field').hide();
            } else {
                $('#' + id).find('a#minus-field').hide();
            }
        });


    })
})();
