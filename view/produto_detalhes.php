<!-- views/produto_detalhes.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $produto->nome ?></title>
</head>
<body>
    <h1><?= $produto->nome ?></h1>
    <p><?= $produto->descricao ?></p>
    <p>Preço: R$ <?= $produto->preco ?></p>
    <form action="index.php?controller=carrinho&action=adicionarCarrinho&id_produto=<?= $produto->id_produto ?>" method="post">
        <button type="submit">Adicionar ao Carrinho</button>
    </form>
</body>
</html>
