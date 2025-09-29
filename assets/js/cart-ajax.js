document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Impede a navegação para o link
            
            const url = this.href;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualiza o contador de itens no header
                        const cartCountElement = document.getElementById('cart-count'); // Certifique-se que este ID exista no seu header
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cartCount;
                        }

                        // [NOVO] Atualiza o valor total no header
                        const cartTotalElement = document.getElementById('cart-total'); // Crie este elemento no header
                        if (cartTotalElement) {
                             cartTotalElement.textContent = 'R$ ' + data.cartTotal;
                        }
                        
                        // Mostra notificação de sucesso
                        showCartNotification('Produto adicionado ao carrinho!', 'success');
                    } else {
                        // Mostra notificação de erro
                        showCartNotification('Erro ao adicionar produto.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro no fetch:', error);
                    showCartNotification('Erro de comunicação.', 'error');
                });
        });
    });
});

function showCartNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `cart-notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    // Faz a notificação aparecer
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Faz a notificação desaparecer depois de 3 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        // Remove o elemento do DOM após a transição de saída
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}