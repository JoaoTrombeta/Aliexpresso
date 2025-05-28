<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Loja Caffeine Boost</title>
    <link rel="stylesheet" href="./assets/css/produtos.css">
</head>
<body>
    <header>
        <h1>AliExpresso</h1>
        <nav>
            <a href="index.php?page=home">Home</a>
            <a href="index.php?page=produtos">Produtos</a>
            <a href="index.php?page=Carrinho">Carrinho</a>
        </nav>
    </header>

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
        <p>&copy; 2025 Caffeine Boost. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
