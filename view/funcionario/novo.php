<?php include 'view/layout/header.php'; ?>

<h2>Cadastrar Funcionário</h2>

<form action="?controller=funcionario&action=cadastrar" method="post">
    <label>Nome:</label>
    <input type="text" name="nome" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Senha:</label>
    <input type="password" name="senha" required>

    <button type="submit">Cadastrar</button>
</form>

<?php include 'view/layout/footer.php'; ?>
