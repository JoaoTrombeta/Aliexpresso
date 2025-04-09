<?php include 'view/template/header.php'; ?>

<h2>Seu Carrinho</h2>

<?php if (empty($_SESSION['carrinho'])): ?>
    <p>Seu carrinho está vazio.</p>
<?php else: ?>
    <ul>
        <?php foreach ($_SESSION['carrinho'] as $item): ?>
            <li>
                <?php echo $item['nome']; ?> - R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include 'view/template/footer.php'; ?>