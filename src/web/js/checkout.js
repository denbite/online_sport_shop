(function f() {

    jQuery(function () {

        $('input#checkoutform-booleansignup[type=\'checkbox\']').on('change', function () {
            var value = $(this).is(':checked');

            var $password = $('.field-signupform-password');

            switch (value) {
                case true:
                    $password.show();
                    break;
                case false:
                    $password.hide();
                    break;
                default:
                    break;
            }
        })

        $('#checkoutform-city').on('change', function () {

            $('#checkoutform-department').find('option:not(:first)').remove().end().prop('disabled', true);

            var city_ref = $(this).val();

            if (!city_ref) {
                return;
            }

            $.ajax({
                type: "POST",
                url: "main/checkout/get-department",
                dataType: "json",
                data: "city_ref=" + city_ref,
                error: function () {
                    alert("При выполнении запроса возникла ошибка");
                },
                success: function (data) {

                    var $department = $('#checkoutform-department');

                    for (var i in data) {
                        $department.append('<option value="' + i + '">' + data[i] + '</option>');
                    }

                    $department.prop('disabled', false);
                }
            })

        })
    })
})();
