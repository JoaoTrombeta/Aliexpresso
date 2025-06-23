document.addEventListener('DOMContentLoaded', () => {
    const listaProdutosCarrinho = document.querySelector('.lista-produtos-carrinho');
    const resumoPedidoCard = document.querySelector('.resumo-pedido.card');
    const carrinhoVazioContainer = document.querySelector('.carrinho-vazio-container');

    // Seletores para elementos do resumo usando IDs
    const resumoSubtotalLabelEl = document.getElementById('resumo-subtotal-label');
    const resumoSubtotalValorEl = document.getElementById('resumo-subtotal-valor');
    const resumoTotalValorEl = document.getElementById('resumo-total-valor');

    function formatCurrency(value) {
        return `R$ ${value.toFixed(2).replace('.', ',')}`;
    }

    function parseCurrency(text) {
        if (typeof text !== 'string') return 0;
        return parseFloat(text.replace('R$ ', '').replace(',', '.'));
    }

    function updateTotals() {
        let grandSubtotal = 0;
        const currentCartItems = listaProdutosCarrinho.querySelectorAll('.item-carrinho');
        let itemCount = currentCartItems.length;

        currentCartItems.forEach(item => {
            const priceString = item.querySelector('.preco-unitario-carrinho strong').textContent;
            const price = parseCurrency(priceString);
            const quantityInput = item.querySelector('.quantidade-carrinho input[type="number"]');
            let quantity = parseInt(quantityInput.value);

            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                quantityInput.value = 1; // Corrige no input visualmente
            }

            const itemSubtotal = price * quantity;
            item.querySelector('.subtotal-item-carrinho strong').textContent = formatCurrency(itemSubtotal);
            grandSubtotal += itemSubtotal;
        });

        if (resumoSubtotalLabelEl) {
            resumoSubtotalLabelEl.textContent = `Subtotal (${itemCount} ${itemCount === 1 ? 'item' : 'itens'}):`;
        }
        if (resumoSubtotalValorEl) {
            resumoSubtotalValorEl.textContent = formatCurrency(grandSubtotal);
        }
        if (resumoTotalValorEl) {
            // Assume Frete Grátis e sem outros custos/descontos por enquanto
            resumoTotalValorEl.textContent = formatCurrency(grandSubtotal);
        }

        checkEmptyCart(itemCount);
    }

    function handleQuantityChange(itemElement, operation) {
        const quantityInput = itemElement.querySelector('.quantidade-carrinho input[type="number"]');
        let quantity = parseInt(quantityInput.value);

        if (operation === 'increase') {
            quantity++;
        } else if (operation === 'decrease') {
            if (quantity > 1) {
                quantity--;
            }
        }
        quantityInput.value = quantity;
        updateTotals();
    }

    function handleQuantityInputChange(inputElement) {
        let quantity = parseInt(inputElement.value);
        if (isNaN(quantity) || quantity < 1) {
            inputElement.value = 1; // Corrige se o usuário digitar um valor inválido
        }
        updateTotals();
    }

    function removeItem(itemElement) {
        itemElement.remove(); // Remove o elemento do DOM
        updateTotals();
    }

    function checkEmptyCart(itemCount) {
        if (itemCount === 0) {
            if (carrinhoVazioContainer) carrinhoVazioContainer.style.display = 'block';
            if (listaProdutosCarrinho) listaProdutosCarrinho.style.display = 'none';
            if (resumoPedidoCard) resumoPedidoCard.style.display = 'none';
        } else {
            if (carrinhoVazioContainer) carrinhoVazioContainer.style.display = 'none';
            // Garante que a lista de produtos seja exibida como flex para manter o layout
            if (listaProdutosCarrinho) listaProdutosCarrinho.style.display = 'flex'; 
            if (resumoPedidoCard) resumoPedidoCard.style.display = 'block'; // ou 'flex' dependendo do seu CSS
        }
    }

    function initializeCartItemEventListeners(itemElement) {
        const decreaseBtn = itemElement.querySelector('.btn-quantidade[aria-label="Diminuir quantidade"]');
        const increaseBtn = itemElement.querySelector('.btn-quantidade[aria-label="Aumentar quantidade"]');
        const quantityInput = itemElement.querySelector('.quantidade-carrinho input[type="number"]');
        const removeBtn = itemElement.querySelector('.btn-remover-item');

        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', () => handleQuantityChange(itemElement, 'decrease'));
        }
        if (increaseBtn) {
            increaseBtn.addEventListener('click', () => handleQuantityChange(itemElement, 'increase'));
        }
        if (quantityInput) {
            quantityInput.addEventListener('change', () => handleQuantityInputChange(quantityInput));
            // O evento 'input' pode ser usado para feedback mais imediato, mas 'change' é bom para o valor final.
            // quantityInput.addEventListener('input', () => handleQuantityInputChange(quantityInput));
        }
        if (removeBtn) {
            removeBtn.addEventListener('click', (event) => {
                event.preventDefault(); // Previne comportamento padrão se o botão for um link ou submit
                removeItem(itemElement);
            });
        }
    }

    // Inicializa os event listeners para todos os itens já presentes no HTML
    if (listaProdutosCarrinho) {
        const initialCartItems = listaProdutosCarrinho.querySelectorAll('.item-carrinho');
        initialCartItems.forEach(item => {
            initializeCartItemEventListeners(item);
        });
    }

    // Calcula os totais iniciais e verifica se o carrinho está vazio ao carregar a página
    updateTotals();

    // OBS: Se você adicionar itens ao carrinho dinamicamente (ex: de uma página de produtos sem recarregar a página),
    // você precisará chamar initializeCartItemEventListeners(novoItemAdicionado) para o novo item.
});