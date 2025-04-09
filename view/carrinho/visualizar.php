<?php include 'view/layout/header.php'; ?>

<h2>Seu Carrinho</h2>

<?php if (empty($produtos)): ?>
    <p>Seu carrinho está vazio.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Qtd</th>
            <th>Subtotal</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?php echo $p['nome']; ?></td>
                <td>R$ <?php echo $p['preco']; ?></td>
                <td><?php echo $p['quantidade']; ?></td>
                <td>R$ <?php echo number_format($p['subtotal'], 2); ?></td>
                <td><a href="?controller=carrinho&action=remover&id=<?php echo $p['id']; ?>">Remover</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><strong>Total: R$ <?php echo number_format($total, 2); ?></strong></p>
<?php endif; ?>
<p>
    <a href="?controller=carrinho&action=finalizar" class="btn-finalizar">Finalizar Pedido</a>
</p>
<?php include 'view/layout/footer.php'; ?>
