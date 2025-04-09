<?php include 'template/header.php'; ?>

<main style="text-align: center; padding: 40px;">
    <h2>Login</h2>
    
    <?php if (isset($erro)) echo "<p style='color:red;'>".$erro."</p>"; ?>

    <form action="?rota=autenticar" method="POST">
        <input type="email" name="email" placeholder="E-mail" required><br><br>
        <input type="password" name="senha" placeholder="Senha" required><br><br>
        <button type="submit">Entrar</button>
    </form>

    <p><a href="?rota=cadastroCliente">Não tem uma conta? Cadastre-se</a></p>
</main>

<?php include 'template/footer.php'; ?>