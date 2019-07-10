(function f() {
    "use strict";

    jQuery(function () {
        $('#color-details li').click(function () {

            var old_color = $('#color-details li.active').data('color');

            var new_color = $(this).data('color');

            if (old_color != new_color) {
                console.log('true');

                $.ajax({
                    type: "POST",
                    url: "/main/products/query",
                    dataType: "json",
                    data: "query=changeColor&data=" + new_color,
                    error: function () {
                        alert("При выполнении запроса возникла ошибка");
                    },
                    success: function (data) {
                        $('#color-details li[data-color = ' + old_color + ']').removeClass('active');
                        $('#color-details li[data-color = ' + new_color + ']').addClass('active');

                        $('#size-details li').remove();

                        console.log(data);
                        for (var i in data['allSizes']) {
                            if (data['allSizes'][i]['quantity'] > 0) {
                                $('#size-details').append('<li data-size="' + data['allSizes'][i]['id'] + '"><a>' + data['allSizes'][i]['size'] + '</a></li>');
                            } else {
                                $('#size-details').append('<li><a class="disabled">' + data['allSizes'][i]['size'] + '</a></li>');
                            }
                        }

                        $('#size-details li a:not(.disabled)').first().addClass('active');

                        $('#price-details span').text(data['allSizes'][0]['new_price']);
                    }
                })
            }
        });

        $('#size-details li a').click(function () {

            var old_size = $('#size-details li').has('a.active').data('size');

            var new_size = $(this).data('size');

            console.log('old: ' + old_size);
            console.log('new: ' + new_size);

            if (old_size != new_size) {
                console.log('true');

                $.ajax({
                    type: "POST",
                    url: "/main/products/query",
                    dataType: "json",
                    data: "query=changeSize&data=" + new_size,
                    error: function () {
                        alert("При выполнении запроса возникла ошибка");
                    },
                    success: function (data) {

                        $('#size-details li[data-size = ' + old_size + ']').find('a').removeClass('active');
                        $('#size-details li[data-size = ' + new_size + ']').find('a').addClass('active');
                    }
                })
            }
        });
    })
})();
