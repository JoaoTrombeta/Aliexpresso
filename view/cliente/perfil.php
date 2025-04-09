<?php include 'view/layout/header.php'; ?>

<h2>Meu Perfil</h2>
<ul>
    <li><strong>Nome:</strong> <?php echo $_SESSION['usuario']['nome']; ?></li>
    <li><strong>E-mail:</strong> <?php echo $_SESSION['usuario']['email']; ?></li>
    <li><strong>Tipo:</strong> <?php echo ucfirst($_SESSION['usuario']['tipo']); ?></li>
</ul>

<a href="?controller=pedido&action=historico">Ver Histórico de Pedidos</a>

<?php include 'view/layout/footer.php'; ?>
