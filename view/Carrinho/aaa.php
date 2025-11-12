<?php
    require_once __DIR__ . "/../../controller/pageController.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AliExpresso - Carrinho</title>
    <link rel="stylesheet" href="./assets/css/carrinho.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php renderHeader(); ?>

    <main class="carrinho-container">
        <h2>Meu Carrinho</h2>

        <div class="conteudo-carrinho">
            <div class="lista-produtos-carrinho">
                <div class="item-carrinho card">
                    <img src="https://placehold.co/100x100/6f4e37/white?text=Café+Grão" alt="Café em Grãos Especial">
                    <div class="info-produto-carrinho">
                        <h3>Café em Grãos Especial</h3>
                        <p>Pacote 250g, torra média</p>
                        <button class="btn-remover-item"><i class="fas fa-trash-alt"></i> Remover</button>
                    </div>
                    <div class="preco-unitario-carrinho">
                        <p>Preço</p>
                        <strong>R$ 25,00</strong>
                    </div>
                    <div class="quantidade-carrinho">
                        <p>Qntd.</p>
                        <div class="controle-quantidade">
                            <button class="btn-quantidade" aria-label="Diminuir quantidade">-</button>
                            <input type="number" value="1" min="1" aria-label="Quantidade">
                            <button class="btn-quantidade" aria-label="Aumentar quantidade">+</button>
                        </div>
                    </div>
                    <div class="subtotal-item-carrinho">
                        <p>Subtotal</p>
                        <strong>R$ 25,00</strong>
                    </div>
                </div>

                <div class="item-carrinho card">
                    <img src="https://placehold.co/100x100/a67c52/white?text=Cafeteira" alt="Cafeteira Italiana">
                    <div class="info-produto-carrinho">
                        <h3>Cafeteira Italiana Média</h3>
                        <p>Alumínio, 6 xícaras</p>
                        <button class="btn-remover-item"><i class="fas fa-trash-alt"></i> Remover</button>
                    </div>
                    <div class="preco-unitario-carrinho">
                        <p>Preço</p>
                        <strong>R$ 89,90</strong>
                    </div>
                    <div class="quantidade-carrinho">
                        <p>Qntd.</p>
                        <div class="controle-quantidade">
                            <button class="btn-quantidade" aria-label="Diminuir quantidade">-</button>
                            <input type="number" value="1" min="1" aria-label="Quantidade">
                            <button class="btn-quantidade" aria-label="Aumentar quantidade">+</button>
                        </div>
                    </div>
                    <div class="subtotal-item-carrinho">
                        <p>Subtotal</p>
                        <strong>R$ 89,90</strong>
                    </div>
                </div>
            </div> <div class="carrinho-vazio-container" style="display: none;">
                <div class="carrinho-vazio card">
                    <p>Seu carrinho está vazio.</p>
                    <a href="index.php?page=produtos" class="btn">Explorar Produtos</a>
                </div>
            </div>

            <aside class="resumo-pedido card">
                <h3>Resumo do Pedido</h3>
                <div class="linha-resumo">
                    <span id="resumo-subtotal-label">Subtotal (2 itens):</span>
                    <span id="resumo-subtotal-valor">R$ 114,90</span>
                </div>
                <div class="linha-resumo">
                    <span>Frete:</span>
                    <span>Grátis</span>
                </div>
                <div class="linha-resumo total-pedido">
                    <span>Total:</span>
                    <strong id="resumo-total-valor">R$ 114,90</strong>
                </div>
                <button class="btn btn-finalizar">Finalizar Compra</button>
                <a href="index.php?page=produtos" class="btn-continuar-comprando">Continuar Comprando</a>
            </aside>
        </div> </main>

    <footer>
        <p>&copy; 2025 AliExpresso. Todos os direitos reservados.</p>
    </footer>
</body>
</html>