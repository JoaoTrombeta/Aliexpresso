<?php include 'view/template/header.php'; ?>

<h2>Catálogo de Cafés</h2>

<div class="produtos">
    <?php foreach ($produtos as $produto): ?>
        <div class="produto">
            <img src="assets/imagens/<?php echo $produto['imagem']; ?>" width="150">
            <h3><?php echo $produto['nome']; ?></h3>
            <p><?php echo $produto['descricao']; ?></p>
            <strong>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></strong><br>
            <a href="index.php?controller=carrinho&action=adicionar&id=<?php echo $produto['id']; ?>">Adicionar ao Carrinho</a>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'view/template/footer.php'; ?>