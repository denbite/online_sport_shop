(function f() {

    jQuery(function () {

        // init cart on page load
        renderCart();

        var $cartWrap = $('.cart-wrap');

        var $cartIndex = $('.cart-main-area');

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

                        renderCart(function () {
                            show_cart();
                        });

                    } else {
                        alert("К сожалению, данного товара уже нет на складе");
                    }
                }
            })
        });

        // remove one item from cart
        $cartWrap.on('click', 'ul.cart-items .item-close i', function () {

            var product = $(this).closest('li.single-shopping-cart').data('cart-id');

            sendRequestRemoveItem(product, function () {
                renderCart();
                if ($cartIndex.length) {
                    console.log('cart page');
                    $cartIndex.find('tr[data-product=' + product + ']').remove();
                }
            });
        });

        // clear cart by clicking on close button in dropdown cart
        $cartWrap.on('click', 'a.cart-close:visible', function () {
            sendRequestClearCart(function () {
                renderCart();
                if ($cartIndex.length) {
                    $cartIndex.find('tbody').empty();
                }
            });
        });

        // remove one product on cart page
        $cartIndex.on('click', '.product-remove i:visible', function () {
            var product = $(this).closest('tr').data('product');

            sendRequestRemoveItem(product, function () {

                $cartIndex.find('tr[data-product=' + product + ']').remove();
                renderCart();
            });
        });

        // clear cart on cart page
        $cartIndex.on('click', 'a.cart-close', function () {
            console.log('clear');
            sendRequestClearCart(function () {
                renderCart();
                $cartIndex.find('tbody').empty();
            });
        });

        function sendRequestRemoveItem(product_id, foo = null) {
            $.ajax({
                type: "POST",
                url: "/main/cart/remove-item",
                dataType: "json",
                data: "product=" + product_id,
                error: function () {
                    alert("При выполнении запроса возникла ошибка");
                },
                success: function (data) {
                    if (data['success']) {
                        if (typeof (foo) == 'function') {
                            foo();
                        }
                    }
                }
            })
        }

        function sendRequestClearCart(foo = null) {
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
                        if (typeof (foo) == 'function') {
                            foo();
                        }
                    }
                }
            })
        }

        // show cart
        function show_cart() {
            // if (!$('.cart-wrap').hasClass('show')) {
            $cartWrap.addClass('show').find('.shopping-cart-content').addClass('show');
            // }
        }

        // send request and insert response into cart div. can also show cart after inserting
        function renderCart(afterRender = null) {
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

                    if (typeof (afterRender) == 'function') {
                        afterRender();
                    }
                }
            });
        }
    })
})();
