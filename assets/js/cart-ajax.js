document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.js-add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const url = this.href;
            const originalText = this.innerHTML;

            this.innerHTML = 'A adicionar...';
            this.disabled = true;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCounter(data.cartCount);
                        showCartNotification('Produto adicionado ao carrinho!');
                        this.innerHTML = 'Adicionado!';
                    } else {
                        showCartNotification('Erro ao adicionar o produto.', 'error');
                        this.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    showCartNotification('Ocorreu um erro de comunicação.', 'error');
                    this.innerHTML = originalText;
                })
                .finally(() => {
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                });
        });
    });
});

function updateCartCounter(count) {
    const cartBadge = document.getElementById('cartItemCount');
    if (cartBadge) {
        cartBadge.innerText = count;
        cartBadge.style.display = count > 0 ? 'flex' : 'none';
    }
}

function showCartNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `cart-notification ${type}`;
    notification.innerText = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    setTimeout(() => {
        notification.classList.remove('show');
        notification.addEventListener('transitionend', () => notification.remove());
    }, 3000);
}