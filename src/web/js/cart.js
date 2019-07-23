(function f() {

    jQuery(function () {

        // init cart on page load
        renderCart();

        var $cartWrap = $('.cart-wrap');

        // add item to cart
        $('#add-to-cart').on('click', function () {
            var color = $('#color-details li.active').data('color');

            var size = $('#size-details ul[data-color=' + color + '] li a.active').data('size');

            var quantity = $('.cart-plus-minus input.cart-plus-minus-box').val();

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
                        renderCart(true);
                    } else {
                        alert("К сожалению, данного товара уже нет на складе");
                    }
                }
            })
        });

        // remove one item from cart
        $cartWrap.on('click', 'ul.cart-items .item-close i', function () {

            var product = $(this).closest('li.single-shopping-cart').data('cart-id');

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
                        // $('.count-style').html(data['totalCount']);
                        // $('.cart-price').html(data['totalCost']);
                        // $('.shop-total').html(data['totalCost']);
                        // $('ul.cart-items li[data-cart-id="' + data['id'] + '"]').remove();
                        renderCart();
                    }
                }
            })
        });

        // clear cart
        $cartWrap.on('click', 'a.cart-close:visible', function () {
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
                        // $('.count-style').html(0);
                        // $('.cart-price').html('₴ 0');
                        // $('ul.cart-items').empty();
                        // $('.shopping-cart-total span').html('₴ 0');
                        renderCart();
                    }
                }
            })
        });

        // show cart
        function show_cart() {
            // if (!$('.cart-wrap').hasClass('show')) {
            $cartWrap.addClass('show').find('.shopping-cart-content').addClass('show');
            // }
        }

        // send request and insert response into cart div. can also show cart after inserting
        function renderCart(show = false) {
            return $.ajax({
                type: "POST",
                url: "/main/cart/cart",
                dataType: "html",
                data: "",
                error: function () {
                    alert("Проблемы при загрузке корзины");
                },
                success: function (data) {
                    if (data) {
                        $cartWrap.html(data);
                    }

                    if (show) {
                        show_cart();
                    }
                }
            });
        }
    })
})();
