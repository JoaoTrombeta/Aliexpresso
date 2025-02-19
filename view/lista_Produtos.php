<!-- views/lista_produtos.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
</head>
<body>
    <h1>Lista de Produtos</h1>
    <ul>
        <?php foreach ($produtos as $produto): ?>
            <li>
                <a href="index.php?controller=produto&action=detalhesProduto&id_produto=<?= $produto->ID ?>">
                    <?= $produto->produto ?> - R$ <?= number_format($produto->valor, 2, ',', '.') ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
