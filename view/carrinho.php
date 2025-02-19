<!-- views/carrinho.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
</head>
<body>
    <h1>Carrinho de Compras</h1>
    <ul>
        <?php foreach ($produtos as $produto): ?>
            <li>
                <?= $produto->nome ?> - R$ <?= $produto->preco ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <h3>Total: R$ <?= $total ?></h3>
</body>
</html>
