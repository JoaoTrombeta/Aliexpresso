<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Aliexpresso - Produtos</title>
    <link rel="stylesheet" href="./assets/css/produtos.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include './view/template/header.php' ?>

    <main>
        <h2>Produtos</h2>
        <div class="grid-produtos">
            <?php foreach($produtos as $produto): ?>
                <div class="card">
                    <img src="../../assets/<?php echo $produto->imagem; ?>" alt="<?php echo $produto->nome; ?>">
                    <h3><?php echo $produto->nome; ?></h3>
                    <p><?php echo $produto->descricao; ?></p>
                    <strong>R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?></strong>
                    <button>Adicionar ao carrinho</button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Aliexpresso. Todos os direitos reservados.</p>
    </footer>
    <script src="./assets/js/header.js"></script>
</body>
</html>
