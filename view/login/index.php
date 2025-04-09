<?php include 'view/layout/header.php'; ?>

<h2>Login</h2>

<form action="?controller=login&action=autenticar" method="post">
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Senha:</label>
    <input type="password" name="senha" required>

    <button type="submit">Entrar</button>
</form>

<p>Não tem conta? <a href="?controller=cliente&action=cadastro">Cadastre-se aqui</a></p>

<?php include 'view/layout/footer.php'; ?>