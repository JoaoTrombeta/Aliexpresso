<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários - Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="./assets/js/header.js"></script>
    <script src="assets/js/carrinho.js"></script>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="admin-container">
        <h1>Gerenciar Usuários</h1>
        <a href="index.php?page=admin" class="back-link">&larr; Voltar ao Painel</a>

        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?= isset($userToEdit) ? 'Editar Usuário' : 'Adicionar Novo Usuário' ?></h2>
            <form action="index.php?page=admin&action=saveUser" method="post">
                <input type="hidden" name="id_usuario" value="<?= $userToEdit['id_usuario'] ?? '' ?>">

                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($userToEdit['nome'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userToEdit['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" <?= isset($userToEdit) ? '' : 'required' ?>>
                    <?php if (isset($userToEdit)): ?>
                        <small>Deixe em branco para não alterar.</small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select id="tipo" name="tipo" required>
                        <option value="vendedor" <?= (($userToEdit['tipo'] ?? '') === 'vendedor') ? 'selected' : '' ?>>Vendedor</option>
                        <option value="gerente" <?= (($userToEdit['tipo'] ?? '') === 'gerente') ? 'selected' : '' ?>>Gerente</option>
                        <option value="admin" <?= (($userToEdit['tipo'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary"><?= isset($userToEdit) ? 'Atualizar' : 'Criar' ?></button>
                <?php if (isset($userToEdit)): ?>
                    <a href="index.php?page=admin&action=users" class="btn-secondary">Cancelar Edição</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabela de Usuários -->
        <div class="table-container">
            <h2>Usuários Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($user['nome']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['tipo']) ?></td>
                        <td class="actions">
                            <a href="index.php?page=admin&action=users&edit_id=<?= $user['id_usuario'] ?>" class="btn-edit">Editar</a>
                            <a href="index.php?page=admin&action=deleteUser&id=<?= $user['id_usuario'] ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja deletar este usuário?');">Deletar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
</body>
</html>