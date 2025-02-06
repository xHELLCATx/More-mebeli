$(document).ready(function() {
    // Обработка клика по кнопке "Добавить в корзину" на странице товара
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        addToCart($(this).data('product-id'), $('#quantity').val());
    });

    // Обработка клика по кнопке "В корзину" в каталоге
    $('.btn-add-to-cart').on('click', function(e) {
        e.preventDefault();
        addToCart($(this).data('product-id'), 1);
    });

    // Функция добавления в корзину
    function addToCart(productId, quantity) {
        // Очищаем контейнер перед добавлением нового уведомления
        $('#alert-container').empty();
        
        $.post('/Online_shop/web/cart/add', {
            id: productId,
            quantity: quantity
        }, function(response) {
            if (response.success) {
                $('#alert-container').html(response.html);
                
                // Обновляем счетчик корзины
                if (typeof updateCartCounter === 'function') {
                    updateCartCounter();
                }
                
                // Автоматически скрываем сообщение через 5 секунд
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 5000);
            } else {
                $('#alert-container').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        }).fail(function() {
            $('#alert-container').html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Произошла ошибка при добавлении товара
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
        });
    }
});
