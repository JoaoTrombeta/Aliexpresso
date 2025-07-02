<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Cupons - Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="./assets/js/header.js"></script>
    <script src="assets/js/carrinho.js"></script>
    <style> 
        body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        main.admin-container { flex-grow: 1; }
    </style>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="admin-container">
        <h1>Gerenciar Cupons</h1>
        <a href="index.php?page=admin" class="back-link">&larr; Voltar ao Painel</a>

        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?= isset($couponToEdit) ? 'Editar Cupom' : 'Adicionar Novo Cupom' ?></h2>
            <form action="index.php?page=admin&action=saveCoupon" method="post">
                <input type="hidden" name="id_cupom" value="<?= $couponToEdit['id_cupom'] ?? '' ?>">

                <div class="form-group">
                    <label for="codigo">Código do Cupom:</label>
                    <input type="text" id="codigo" name="codigo" value="<?= htmlspecialchars($couponToEdit['codigo'] ?? '') ?>" required>
                </div>
                 <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($couponToEdit['descricao'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="valor_desconto">Valor do Desconto:</label>
                    <input type="number" step="0.01" id="valor_desconto" name="valor_desconto" value="<?= htmlspecialchars($couponToEdit['valor_desconto'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo de Desconto:</label>
                    <select id="tipo" name="tipo" required>
                        <option value="fixo" <?= (($couponToEdit['tipo'] ?? '') === 'fixo') ? 'selected' : '' ?>>Fixo (R$)</option>
                        <option value="percentual" <?= (($couponToEdit['tipo'] ?? '') === 'percentual') ? 'selected' : '' ?>>Percentual (%)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="data_validade">Data de Validade (opcional):</label>
                    <input type="date" id="data_validade" name="data_validade" value="<?= htmlspecialchars($couponToEdit['data_validade'] ?? '') ?>">
                </div>
                <!-- [MUDANÇA] O campo Status só aparece no modo de edição -->
                <?php if (isset($couponToEdit)): ?>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="ativo" <?= ($couponToEdit['status'] === 'ativo') ? 'selected' : '' ?>>Ativo</option>
                            <option value="expirado" <?= ($couponToEdit['status'] === 'expirado') ? 'selected' : '' ?>>Expirado</option>
                        </select>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-primary"><?= isset($couponToEdit) ? 'Atualizar' : 'Criar' ?></button>
                <?php if (isset($couponToEdit)): ?>
                    <a href="index.php?page=admin&action=cupons" class="btn-secondary">Cancelar Edição</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabela de Cupons Cadastrados -->
        <div class="table-container">
            <h2>Cupons Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Desconto</th>
                        <th>Tipo</th>
                        <th>Validade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coupons as $coupon): ?>
                    <tr>
                        <td><?= $coupon['id_cupom'] ?></td>
                        <td><?= htmlspecialchars($coupon['codigo']) ?></td>
                        <td><?= htmlspecialchars($coupon['descricao']) ?></td>
                        <td>
                            <?= ($coupon['tipo'] === 'fixo' ? 'R$ ' : '') . number_format($coupon['valor_desconto'], 2, ',', '.') . ($coupon['tipo'] === 'percentual' ? '%' : '') ?>
                        </td>
                        <td><?= ucfirst($coupon['tipo']) ?></td>
                        <td><?= $coupon['data_validade'] ? date('d/m/Y', strtotime($coupon['data_validade'])) : 'N/A' ?></td>
                        <td><?= ucfirst($coupon['status']) ?></td>
                        <td class="actions">
                            <a href="index.php?page=admin&action=cupons&edit_id=<?= $coupon['id_cupom'] ?>" class="btn-edit">Editar</a>
                            <a href="index.php?page=admin&action=deleteCoupon&id=<?= $coupon['id_cupom'] ?>" class="btn-delete" onclick="return confirm('Tem certeza?');">Deletar</a>
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