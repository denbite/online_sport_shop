(function f() {
    "use strict";

    jQuery(function () {

        $('#import-upload_excel').submit(function (event) {
            // event.preventDefault();

            if ($('.form-group.has-error').length === 0) {
                console.log('valid');

                $.post(
                    '/admin/import/import-from-excel',
                    {
                        'ImportFromExcelForm[token]': $('#importfromexcelform-token').val(),
                        'ImportFromExcelForm[from]': $('#importfromexcelform-from').val(),
                        'ImportFromExcelForm[to]': $('#importfromexcelform-to').val(),
                        'ImportFromExcelForm[category_id]': $('#importfromexcelform-category_id').val(),
                        'submit': true,
                    },
                    function (data) {
                        if (data['success']) {
                            console.log('from: ' + data['from']);
                            console.log('to: ' + data['to']);
                        } else {
                            console.log('i get error');
                            console.log(data);
                        }

                    }
                );
            }


        });
    })
})();
