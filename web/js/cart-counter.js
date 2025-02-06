function updateCartCounter() {
    fetch('/Online_shop/web/cart/count', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            const counter = document.querySelector('#cart-counter');
            if (counter) {
                counter.textContent = data.count;
                // Скрываем счетчик, если корзина пуста
                counter.style.display = data.count > 0 ? 'inline-block' : 'none';
            }
        })
        .catch(error => console.error('Ошибка при обновлении счетчика корзины:', error));
}

// Обновляем счетчик каждую секунду
setInterval(updateCartCounter, 1000);

// Также обновляем при загрузке страницы
document.addEventListener('DOMContentLoaded', updateCartCounter);
