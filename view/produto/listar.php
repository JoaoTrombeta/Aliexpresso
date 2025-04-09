<?php include 'view/layout/header.php'; ?>

<h2>Produtos</h2>

<?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['tipo'], ['admin', 'funcionario'])): ?>
    <a href="?controller=produto&action=novo">Cadastrar Produto</a>
<?php endif; ?>

<div class="produtos">
    <?php foreach ($produtos as $p): ?>
        <div class="produto-card">
            <img src="assets/img/<?php echo $p['imagem']; ?>" width="150">
            <h3><?php echo $p['nome']; ?></h3>
            <p><?php echo $p['descricao']; ?></p>
            <p><strong>R$ <?php echo $p['preco']; ?></strong></p>
        </div>
        <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'cliente'): ?>
            <p><a href="?controller=carrinho&action=adicionar&id=<?php echo $p['id']; ?>">Adicionar ao Carrinho</a></p>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<?php include 'view/layout/footer.php'; ?>
