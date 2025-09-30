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
                    <h3>Resumo do Pedido</h3>

                    <div class="coupon-section">
                        <?php if (isset($_SESSION['applied_coupon'])): ?>
                            <div class="applied-coupon">
                                <span>Cupom: <strong><?= htmlspecialchars($_SESSION['applied_coupon']['codigo']) ?></strong></span>
                                <a href="index.php?page=carrinho&action=removeCoupon" class="remove-coupon-btn">Remover</a>
                            </div>
                        <?php else: ?>
                            <a id="coupon-toggle-btn" class="coupon-toggle">Adicionar cupom de desconto</a>
                            <div id="coupon-form-container" class="coupon-form-container">
                                <form action="index.php?page=carrinho&action=applyCoupon" method="post" class="coupon-form">
                                    <input type="text" name="coupon_code" placeholder="Digite seu cupom" oninput="this.value = this.value.toUpperCase()">
                                    <button type="submit">Aplicar</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- [ATUALIZADO] Mensagem de feedback do cupom com botão de fechar -->
                    <?php if (isset($_SESSION['coupon_message'])): ?>
                        <div class="coupon-message <?= $_SESSION['coupon_message']['type'] ?>">
                            <span><?= $_SESSION['coupon_message']['text'] ?></span>
                            <button type="button" class="close-coupon-message" onclick="this.parentElement.style.display='none'">&times;</button>
                        </div>
                        <?php unset($_SESSION['coupon_message']); ?>
                    <?php endif; ?>

                    <div class="linha-resumo">
                        <span>Subtotal (<?= count($cartItems) ?> itens):</span>
                        <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                    </div>

                    <?php if ($discount > 0): ?>
                    <div class="linha-resumo desconto">
                        <span>Desconto (Cupom):</span>
                        <span>- R$ <?= number_format($discount, 2, ',', '.') ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="linha-resumo">
                        <span>Frete:</span>
                        <span>Grátis</span>
                    </div>
                    <div class="linha-resumo total">
                        <strong>Total:</strong>
                        <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong>
                    </div>
                    <a href="/?page=pedido&action=finalizar" style="text-decoration: none;">
                        <button class="btn-finalizar">Finalizar Compra</button>
                    </a>
                    <a href="index.php?page=produto" class="btn-continuar-comprando">Continuar Comprando</a>
                </aside>
            </div>
        <?php endif; ?>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
</body>
</html>