$(document).ready(function () {
    $('.add-to-cart').click(function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var quantity = parseInt($('#quantity').val());
        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: {
                productId: productId,
                quantity: quantity
            },
            success: function (response) {
                console.log(JSON.parse(response));
                $('#cartModal').modal('show');

                var cartItems = JSON.parse(response).products;
                var cartItemsHtml = '';
                var total = 0;
                for (var i = 0; i < cartItems.length; i++) {
                    var item = cartItems[i];
                    cartItemsHtml += `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td><img src="${item.img}" alt="${item.name}" width="50"></td>
                                        <td>${item.price}</td>
                                        <td>${item.quantity}</td>
                                        <td>${item.price * item.quantity}</td>
                                    </tr>
                                `;
                    total += item.price * item.quantity;
                }
                cartItemsHtml += `
                    <tr>
                        <td colspan='6' class='text-end'>Tổng tiền: ${total} đ</td>
                    </tr>
                `
                $('#cartItems').html(cartItemsHtml);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

    $('.open-cart').click(function(){
        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: {
                renderCart: true,
            },
            success: function (response) {
                var cartItems = JSON.parse(response).products;
                var cartItemsHtml = '';
                var total = 0;
                for (var i = 0; i < cartItems.length; i++) {
                    var item = cartItems[i];
                    cartItemsHtml += `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td><img src="${item.img}" alt="${item.name}" width="50"></td>
                                        <td>${item.price}</td>
                                        <td>${item.quantity}</td>
                                        <td>${item.price * item.quantity}</td>
                                    </tr>
                                `;
                    total += item.price * item.quantity;
                }
                cartItemsHtml += `
                    <tr>
                        <td colspan='6' class='text-end'>Tổng tiền: ${total} đ</td>
                    </tr>
                `
                $('#cartItems').html(cartItemsHtml);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }})
    })

});