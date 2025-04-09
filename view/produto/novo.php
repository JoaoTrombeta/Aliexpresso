<?php include 'view/layout/header.php'; ?>

<h2>Cadastrar Produto</h2>

<form action="?controller=produto&action=cadastrar" method="post" enctype="multipart/form-data">
    <label>Nome:</label>
    <input type="text" name="nome" required>

    <label>Descrição:</label>
    <textarea name="descricao" required></textarea>

    <label>Preço:</label>
    <input type="number" step="0.01" name="preco" required>

    <label>Imagem:</label>
    <input type="file" name="imagem" required>

    <button type="submit">Salvar</button>
</form>

<?php include 'view/layout/footer.php'; ?>
