<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nossos Produtos - Aliexpresso</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/produtos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="./assets/js/header.js"></script>
    <script src="assets/js/carrinho.js"></script>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="catalog-container">
        <h1>Conheça Nossos Produtos</h1>
        <div class="grid-produtos">
            <?php if (empty($produtos)): ?>
                <p>Nenhum produto encontrado.</p>
            <?php else: ?>
                <?php foreach($produtos as $produto): ?>
                    <div class="card-produto">
                        <!-- Usando a sintaxe de array e htmlspecialchars para segurança -->
                        <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <div class="card-info">
                            <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                            <p class="descricao"><?= htmlspecialchars($produto['descricao']) ?></p>
                            <strong class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></strong>
                            <a href="index.php?page=carrinho&action=ajax_add&id=<?= $produto['id_produto'] ?>" class="btn-comprar js-add-to-cart">Adicionar ao Carrinho</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
</body>
</html>