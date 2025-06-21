<?php
// views/usuario/formulario.php
$isEdit = isset($usuario) && $usuario['id_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $isEdit ? 'Editar' : 'Adicionar' ?> Usuário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3><?= $isEdit ? 'Editar Usuário' : 'Adicionar Novo Usuário' ?></h3>
            </div>
            <div class="card-body">
                <form action="index.php?action=<?= $isEdit ? 'update' : 'store' ?>" method="post">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= $usuario['nome'] ?? '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $usuario['email'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="senha" name="senha" <?= $isEdit ? '' : 'required' ?>>
                        <?php if ($isEdit): ?><small class="form-text text-muted">Deixe em branco para não alterar.</small><?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Atualizar' : 'Salvar' ?></button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>