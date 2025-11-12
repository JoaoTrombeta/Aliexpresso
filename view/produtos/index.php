<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nossos Produtos - Aliexpresso</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/produtos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ... (seu CSS do catálogo) ... */
        .cart-notification {
            position: fixed; bottom: 20px; right: 20px;
            background-color: #28a745; color: white;
            padding: 15px 25px; border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000; opacity: 0; transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .cart-notification.error { background-color: #dc3545; }
        .cart-notification.show { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="catalog-container">
        <h1>Conheça Nossos Produtos</h1>
        <div class="grid-produtos">
            <?php foreach($produtos as $produto): ?>
                <div class="card-produto">
                    <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <div class="card-info">
                        <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                        <p class="descricao"><?= htmlspecialchars($produto['descricao']) ?></p>
                        <strong class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></strong>
                        <a href="index.php?page=carrinho&action=ajax_add&id=<?= $produto['id_produto'] ?>" class="btn-comprar js-add-to-cart">Adicionar ao Carrinho</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>

    <!-- Carrega o novo script de AJAX para o carrinho -->
    <script src="assets/js/cart-ajax.js"></script>
</body>
</html>