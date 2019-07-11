(function f() {

    jQuery(function () {

        var color = $('#color-details li.active').data('color');

        var size = $('#size-details[data-color = ' + color + ']').show().find('ul li a.active').data('size');

        $('#price-details[data-color = ' + color + '][data-size = ' + size + ']').show();

        $('#gallery .slick-list .slick-track a[data-color = ' + color + ']').show();

        $('#color-details li').click(function () {

            var old_color = $('#color-details li.active').data('color');

            var new_color = $(this).data('color');

            if (old_color != new_color) {
                console.log('true');

                $('#color-details li[data-color = ' + old_color + ']').removeClass('active');
                $('#color-details li[data-color = ' + new_color + ']').addClass('active');

                $('#size-details[data-color = ' + new_color + ']').show();
                $('#size-details[data-color = ' + old_color + ']').hide();

                console.log('new_color: ' + new_color);
                // $('#price-details[data-color = ' + new_color + '][data-size = ' + new_size + ']').show();

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
