document.addEventListener('DOMContentLoaded', function() {
    // Removemos ouvintes antigos para evitar duplicidade (caso o script rode 2x)
    const botoes = document.querySelectorAll('.js-add-to-cart');
    
    botoes.forEach(button => {
        // Truque: Clona e substitui o botão para remover listeners antigos acumulados
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function(e) {
            e.preventDefault(); // Impede ir para o link
            e.stopImmediatePropagation(); // <--- A MÁGICA: Impede cliques duplos no mesmo instante
            
            // Desabilita o botão temporariamente para evitar "clique frenético"
            this.style.pointerEvents = 'none';
            this.style.opacity = '0.6';
            
            const url = this.href;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualiza contadores (seu código original)
                        const cartCountElement = document.getElementById('cart-count');
                        if (cartCountElement) cartCountElement.textContent = data.totalItems; // Note: usei data.totalItems que definimos no PHP

                        const cartTotalElement = document.getElementById('cart-total');
                        if (cartTotalElement && data.cartTotal) {
                             cartTotalElement.textContent = 'R$ ' + data.cartTotal;
                        }
                        
                        showCartNotification('Produto adicionado ao carrinho!', 'success');
                    } else {
                        showCartNotification('Erro ao adicionar produto.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro no fetch:', error);
                    showCartNotification('Erro de comunicação.', 'error');
                })
                .finally(() => {
                    // Reabilita o botão
                    this.style.pointerEvents = 'auto';
                    this.style.opacity = '1';
                });
        });
    });
});

// Função de notificação (Mantive igual, pois estava ótima)
function showCartNotification(message, type = 'success') {
    const existing = document.querySelector('.cart-notification');
    if (existing) existing.remove(); // Remove notificação anterior para não empilhar

    const notification = document.createElement('div');
    notification.className = `cart-notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}