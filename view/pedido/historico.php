<?php include 'view/layout/header.php'; ?>

<h2>Histórico de Pedidos</h2>

<?php if (empty($pedidos)): ?>
    <p>Você ainda não fez nenhum pedido.</p>
<?php else: ?>
    <ul>
        <?php foreach ($pedidos as $pedido): ?>
            <li>
                Pedido #<?php echo $pedido['id']; ?> - 
                <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?> -
                R$ <?php echo number_format($pedido['total'], 2); ?> -
                <a href="?controller=pedido&action=detalhes&id=<?php echo $pedido['id']; ?>">Ver Itens</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include 'view/layout/footer.php'; ?>
