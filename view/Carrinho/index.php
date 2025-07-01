<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho - Aliexpresso</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/carrinho.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/carrinho.js"></script>
    <script src="./assets/js/header.js"></script>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="carrinho-container">
        <h1>Meu Carrinho</h1>
        <?php if (empty($cartItems)): ?>
            <div class="carrinho-vazio">
                <div class="carrinho-vazio card">
                    <p>Seu carrinho está vazio.</p>
                    <button class="btn"><a href="index.php?page=produto">Explorar Produtos</a></button>
                </div>
            </div>
        <?php else: ?>
            <div class="conteudo-carrinho">
                <div class="lista-produtos-carrinho">
                    <?php foreach($cartItems as $item): ?>
                        <div class="item-carrinho card">
                            <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                            <div class="info-produto-carrinho">
                                <h3><?= htmlspecialchars($item['nome']) ?></h3>
                                <p><?= htmlspecialchars($item['descricao']) ?></p>
                                <!-- [ATUALIZADO] Botão de remover funcional -->
                                <a href="index.php?page=carrinho&action=remove&id=<?= $item['id'] ?>" class="btn-remover-item">
                                    <i class="fas fa-trash-alt"></i> Remover
                                </a>
                            </div>
                            <div class="preco-unitario-carrinho">
                                <span>Preço</span>
                                <strong>R$ <?= number_format($item['preco'], 2, ',', '.') ?></strong>
                            </div>
                            <div class="quantidade-carrinho">
                                <span>Qtd.</span>
                                <form action="index.php?page=carrinho&action=update" method="post" class="controle-quantidade">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="action" value="decrease" class="btn-quantidade" <?= $item['quantidade'] <= 1 ? 'disabled' : '' ?>>-</button>
                                    <span class="quantidade-valor"><?= $item['quantidade'] ?></span>
                                    <button type="submit" name="action" value="increase" class="btn-quantidade">+</button>
                                </form>
                            </div>
                            <div class="subtotal-item-carrinho">
                                <span>Subtotal</span>
                                <strong>R$ <?= number_format($item['subtotal_item'], 2, ',', '.') ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <aside class="resumo-pedido card">
                    <h2>Resumo do Pedido</h2>
                    <div class="linha-resumo">
                        <span>Subtotal (<?= count($cartItems) ?> itens):</span>
                        <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                    </div>
                    <div class="linha-resumo">
                        <span>Frete:</span>
                        <span>Grátis</span>
                    </div>
                    <div class="linha-resumo total-pedido">
                        <span>Total:</span>
                        <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                    </div>
                    <button class="btn btn-finalizar">Finalizar Compra</button>
                    <a href="index.php?page=produto" class="btn-continuar-comprando">Continuar Comprando</a>
                </aside>
            </div>
        <?php endif; ?>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
</body>
</html>