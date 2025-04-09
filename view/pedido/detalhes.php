<?php include 'view/layout/header.php'; ?>

<h2>Detalhes do Pedido</h2>

<table>
    <tr><th>Produto</th><th>Quantidade</th><th>Preço Unitário</th></tr>
    <?php foreach ($itens as $item): ?>
        <tr>
            <td><?php echo $item['nome']; ?></td>
            <td><?php echo $item['quantidade']; ?></td>
            <td>R$ <?php echo number_format($item['preco_unitario'], 2); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include 'view/layout/footer.php'; ?>
