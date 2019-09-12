(function f() {

    jQuery(function () {

        var color = $('#color-details li.active').data('color');

        // on load page show sizes, current price and photo for active color
        var $block_size = $('#size-details ul[data-color = ' + color + ']');
        if ($block_size.length != 1 || $block_size.find('li a').html() != '-') {
            $block_size.show();
        } else {
            $('.pro-details-size').hide();
        }

        var size = $block_size.find('li a.active').data('size');
        $('#price-details div.product-details-price[data-color = ' + color + '][data-size = ' + size + ']').show();
        $('#gallery .slick-list .slick-track a[data-color = ' + color + ']').show();

        var $code = $('#detail-block-code');
        var $color = $('#detail-block-color');

        $code.find('ul li[data-color = ' + color + ']').show();
        $color.find('ul li[data-color = ' + color + ']').show();


        $('#color-details li').click(function () {

            var old_color = $('#color-details li.active').data('color');

            var new_color = $(this).data('color');

            if (old_color != new_color) {

                // remove active from one and setup active to another color
                $('#color-details li').removeClass('active');
                $('#color-details li[data-color = ' + new_color + ']').addClass('active');

                // clear sizes and setup new block with one active size
                $('#size-details ul').hide();

                var $block_size = $('#size-details ul[data-color = ' + new_color + ']');

                console.log($block_size.find('li a').html());

                if ($block_size.length == 1 && $block_size.find('li a').html() == '-') {
                    $('.pro-details-size').hide();
                } else {
                    $block_size.show();
                }

                var new_size = $('#size-details ul[data-color = ' + new_color + ']').find('li a.active').data('size');

                // clear price and then setup new value
                $('#price-details div.product-details-price').hide();
                let block_price = $('#price-details div.product-details-price[data-color = ' + new_color + '][data-size' +
                    ' = ' + new_size + ']');
                if (block_price.length) {
                    block_price.show();
                } else {
                    $('#no-stock').show();
                }

                $code.find('ul li').hide();
                $code.find('ul li[data-color = ' + new_color + ']').show();

                $color.find('ul li').hide();
                $color.find('ul li[data-color = ' + new_color + ']').show();

                $('input.cart-plus-minus-box').val(1);

                $('#gallery .slick-list .slick-track a[data-color=' + new_color + ']:not(.slick-cloned)').click();


                // clear gallery and setup new photos for active color

                // find method from original js and call him for new color !!!
                // $('#gallery .slick-list .slick-track a').hide();
                // var src = $('#gallery .slick-list .slick-track a[data-color = ' + new_color + ']').show().find('img:first').attr('src');
                // $('#preview img').attr('src', src).attr('data-zoom-image', src);
                // $('.zoomContainer .zoomWindowContainer .zoomWindow').css('background-image', 'url(' + src + ')');

            }
        });

        $('#size-details li a').click(function () {

            var old_size = $('#size-details ul li a.active').data('size');

            var new_size = $(this).data('size');

            if (old_size != new_size) {

                // get current color
                var color = $('#color-details li.active').data('color');

                // clear sizes and setup new
                $('#size-details ul[data-color = ' + color + '] li a').removeClass('active');
                $('#size-details ul[data-color = ' + color + '] li a[data-size = ' + new_size + ']').addClass('active');

                // clear price and then setup new value
                $('#price-details div.product-details-price').hide();
                let block_price = $('#price-details div.product-details-price[data-color = ' + color + '][data-size' +
                    ' = ' + new_size + ']');
                if (block_price.length) {
                    block_price.show();
                } else {
                    $('#no-stock').show();
                }

                $('input.cart-plus-minus-box').val(1);
            }
        });
    })
})();
