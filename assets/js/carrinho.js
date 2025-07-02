document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.js-add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Impede o redirecionamento do link

            const url = this.href;
            const originalText = this.innerHTML;

            // Feedback visual para o utilizador enquanto processa
            this.innerHTML = 'A adicionar...';
            this.disabled = true;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualiza o contador do carrinho no header
                        updateCartCounter(data.cartCount);
                        // Mostra uma notificação de sucesso
                        showCartNotification('Produto adicionado ao carrinho!');
                        // Altera o texto do botão para confirmar a ação
                        this.innerHTML = 'Adicionado!';
                    } else {
                        showCartNotification('Erro ao adicionar o produto.', 'error');
                        this.innerHTML = originalText; // Restaura o texto original em caso de erro
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    showCartNotification('Ocorreu um erro de comunicação.', 'error');
                    this.innerHTML = originalText;
                })
                .finally(() => {
                    // Após 2 segundos, restaura o botão ao seu estado original
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
        // Garante que o badge seja visível se houver itens
        cartBadge.style.display = count > 0 ? 'flex' : 'none';
    }
}

function showCartNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `cart-notification ${type}`;
    notification.innerText = message;

    document.body.appendChild(notification);

    // Animação de entrada
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Remove a notificação após 3 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        notification.addEventListener('transitionend', () => notification.remove());
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('coupon-toggle-btn');
            const formContainer = document.getElementById('coupon-form-container');

            if (toggleBtn && formContainer) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Impede que o link navegue
                    // Mostra ou esconde o formulário
                    if (formContainer.style.display === 'block') {
                        formContainer.style.display = 'none';
                    } else {
                        formContainer.style.display = 'block';
                    }
                });
            }
        });