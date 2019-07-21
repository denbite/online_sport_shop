(function f() {

    jQuery(function () {
        $('#add-to-cart').on('click', function () {
            var color = $('#color-details li.active').data('color');

            console.log('color: ' + color);

            var size = $('#size-details ul[data-color=' + color + '] li a.active').data('size');

            var quantity = $('.cart-plus-minus input.cart-plus-minus-box').val();

            console.log('size: ' + size);

            console.log('quantity: ' + quantity);

            $.ajax({
                type: "POST",
                url: "/main/cart/add-to-cart",
                dataType: "json",
                data: "color=" + color + "&size=" + size + "&quantity=" + quantity,
                error: function () {
                    alert("При выполнении запроса возникла ошибка");
                },
                success: function (data) {
                    if (data['success']) {

                        // if new cart item
                        if (data['extra']['new']) {
                            // add this size to cart
                            $('ul.cart-items').append('<li data-cart-id="' + data['id'] + '" class="single-shopping-cart"><div' +
                                ' class="shopping-cart-img"><a' +
                                ' href="' + data['extra']['link'] + '"><img alt="' + data['extra']['image_alt'] + '" src="' + data['extra']['image_src'] + '"></a><div class="item-close"><a href="#"><i class="sli sli-close"></i></a>' +
                                '</div> </div> <div class="shopping-cart-title"> <h4> <a href="' + data['extra']['link'] + '">' + data['extra']['title'] + '</a> </h4> <span>' + data['extra']['sum'] + '</span> </div> </li>'
                            );

                        }

                        // show cart
                        show_cart();
                    } else {
                        alert("К сожалению, данного товара уже нет на складе");
                    }

                    $('.count-style').html(data['extra']['totalCount']);
                    $('.cart-price').html(data['extra']['totalCost']);
                    $('.shop-total').html(data['extra']['totalCost']);
                    $('ul.cart-items li[data-cart-id="' + data['id'] + '"] .shopping-cart-title span').html(data['extra']['sum']);

                }
            })
        });

        $('ul.cart-items').on('click', '.item-close i', function () {

            var product = $(this).closest('li.single-shopping-cart').data('cart-id');

            console.log('product: ' + product);

            $.ajax({
                type: "POST",
                url: "/main/cart/remove-item",
                dataType: "json",
                data: "product=" + product,
                error: function () {
                    alert("При выполнении запроса возникла ошибка");
                },
                success: function (data) {
                    console.log(data);
                    if (data['success']) {
                        $('.count-style').html(data['totalCount']);
                        $('.cart-price').html(data['totalCost']);
                        $('.shop-total').html(data['totalCost']);
                        $('ul.cart-items li[data-cart-id="' + data['id'] + '"]').remove();
                    }
                }
            })
        });

        $('a.cart-close:visible').on('click', function () {
            $.ajax({
                type: "POST",
                url: "/main/cart/clear-cart",
                dataType: "json",
                data: "",
                error: function () {
                    alert("При выполнении запроса возникла ошибка");
                },
                success: function (data) {
                    if (data['success']) {
                        $('.count-style').html(0);
                        $('.cart-price').html('₴ 0');
                        $('ul.cart-items').empty();
                        $('.shopping-cart-total span').html('₴ 0');
                    }
                }
            })
        });

        function show_cart() {
            var $this = $('button.icon-cart-active');
            if (!$this.parent().hasClass('show')) {
                $this.siblings('.shopping-cart-content').addClass('show').parent().addClass('show');
            } else {
                $this.siblings('.shopping-cart-content').removeClass('show').parent().removeClass('show');
            }
        }
    })
})();
