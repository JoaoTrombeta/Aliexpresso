<?php include_once 'template/header.php'; ?>

<main class="container">
    <h2>Editar Perfil</h2>
    <form action="index.php?rota=salvarPerfil" method="POST">
        <input type="hidden" name="id" value="<?php echo $_SESSION['cliente']['id']; ?>">
        
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $_SESSION['cliente']['nome']; ?>" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="<?php echo $_SESSION['cliente']['email']; ?>" required>

        <label for="senha">Nova Senha (deixe em branco para não alterar):</label>
        <input type="password" id="senha" name="senha">

        <button type="submit">Salvar Alterações</button>
    </form>
</main>

<?php include_once 'template/footer.php'; ?>