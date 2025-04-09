<?php include 'view/template/header.php'; ?>

<h2>Cadastro de Cliente</h2>

<form method="POST" action="index.php?controller=cliente&action=cadastrar">
    <label>Nome:</label>
    <input type="text" name="nome" required><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Senha:</label>
    <input type="password" name="senha" required><br>

    <button type="submit">Cadastrar</button>
</form>

<?php include 'view/template/footer.php'; ?>