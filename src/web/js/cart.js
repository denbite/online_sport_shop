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
                    console.log('Не удалось загрузить корзину');
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

            sendRequestRemoveItem(product, function (data) {
                $cartIndex.find('tr[data-product=' + product + ']').remove();
                renderCart();
            });
        });

        // clear cart on cart page
        $cartIndex.on('click', 'a.cart-close', function () {
            sendRequestClearCart(function (data) {
                $cartIndex.find('tbody').empty();
                renderCart();
            });
        });

        // change quantity on cart page
        $cartIndex.find('input.cart-plus-minus-box').on('change', function () {
            var quantity = $(this).val();

            var product = $(this).closest('tr').data('product');

            // add check type integer before send request ?
            $.ajax({
                type: "POST",
                url: "/main/cart/change-quantity",
                dataType: "json",
                data: "product=" + product + "&quantity=" + quantity,
                error: function () {
                    alert("При выполнении запроса возникла ошибка");
                },
                success: function (data) {
                    if (data['success']) {
                        $cartIndex.find('tr[data-product=' + data['extra']['id'] + '] .product-subtotal').html(data['extra']['cost']);
                        renderCart();
                    }
                }
            })

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
                            foo(data);
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
                            foo(data);
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
                dataType: "json",
                data: "",
                error: function () {
                    alert("Проблемы при загрузке корзины");
                },
                success: function (data) {
                    if (data) {
                        $cartWrap.html(data['cart']);
                        if ($cartIndex.length) {
                            $cartIndex.find('h4.grand-totall-title span').html(data['totalCost']);
                            $cartIndex.find('.total-shipping ul li:first-child span').html(data['delivery']);
                        }
                    }

                    if (typeof (afterRender) == 'function') {
                        afterRender();
                    }
                }
            });
        }
    })
})();
