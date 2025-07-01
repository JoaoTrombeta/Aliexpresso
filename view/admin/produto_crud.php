<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos - Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="./assets/js/header.js"></script>
    <script src="assets/js/carrinho.js"></script>
    <style> 
        body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        main.admin-container { flex-grow: 1; }
        .product-image-thumb { max-width: 60px; height: auto; border-radius: 4px; vertical-align: middle; }
        .image-preview-container { margin-top: 15px; }
        #imagePreview { max-width: 150px; max-height: 150px; border: 1px solid #ddd; padding: 5px; border-radius: 4px; display: block; }
        #imagePreview.hidden { display: none; }
    </style>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="admin-container">
        <h1>Gerenciar Produtos</h1>
        <a href="index.php?page=admin" class="back-link">&larr; Voltar ao Painel</a>

        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?= isset($productToEdit) ? 'Editar Produto' : 'Adicionar Novo Produto' ?></h2>
            <form action="index.php?page=admin&action=saveProduct" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_produto" value="<?= $productToEdit['id_produto'] ?? '' ?>">
                <input type="hidden" name="imagem_atual" value="<?= $productToEdit['imagem'] ?? '' ?>">

                <!-- Campos de nome, descrição, preço, estoque e categoria -->
                <div class="form-group">
                    <label for="nome">Nome do Produto:</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($productToEdit['nome'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="3" required><?= htmlspecialchars($productToEdit['descricao'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="preco">Preço (R$):</label>
                    <input type="number" step="0.01" id="preco" name="preco" value="<?= htmlspecialchars($productToEdit['preco'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="quantidade_estoque">Qtd. em Estoque:</label>
                    <input type="number" id="quantidade_estoque" name="quantidade_estoque" value="<?= htmlspecialchars($productToEdit['quantidade_estoque'] ?? '0') ?>" required>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoria:</label>
                    <select id="categoria" name="categoria" required>
                        <?php foreach ($productTypes as $key => $value): ?>
                            <option value="<?= $key ?>" <?= (($productToEdit['categoria'] ?? '') === $key) ? 'selected' : '' ?>>
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Campo de Imagem -->
                <div class="form-group">
                    <label for="imagemInput">Imagem do Produto:</label>
                    <input type="file" id="imagemInput" name="imagem" accept="image/png, image/jpeg, image/webp, image/jpg">
                    <div class="image-preview-container">
                        <img id="imagePreview" 
                             src="<?= !empty($productToEdit['imagem']) ? htmlspecialchars($productToEdit['imagem']) : '' ?>" 
                             alt="Pré-visualização da imagem"
                             class="<?= empty($productToEdit['imagem']) ? 'hidden' : '' ?>">
                    </div>
                </div>

                <!-- [MUDANÇA] O campo Status só aparece no modo de edição -->
                <?php if (isset($productToEdit)): ?>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="a venda" <?= ($productToEdit['status'] === 'a venda') ? 'selected' : '' ?>>À venda</option>
                            <option value="descontinuado" <?= ($productToEdit['status'] === 'descontinuado') ? 'selected' : '' ?>>Descontinuado</option>
                        </select>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-primary"><?= isset($productToEdit) ? 'Atualizar' : 'Criar' ?></button>
                <?php if (isset($productToEdit)): ?>
                    <a href="index.php?page=admin&action=produtos" class="btn-secondary">Cancelar Edição</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabela de Produtos Cadastrados (sem alterações) -->
        <div class="table-container">
            <h2>Produtos Cadastrados</h2>
            <table>
                <!-- ... header da tabela ... -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <!-- ... corpo da tabela ... -->
                <tbody class="table-container">
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id_produto'] ?></td>
                        <td>
                            <?php if (!empty($product['imagem'])): ?>
                                <img src="<?= htmlspecialchars($product['imagem']) ?>" alt="<?= htmlspecialchars($product['nome']) ?>" class="product-image-thumb">
                            <?php else: ?>
                                Sem imagem
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['nome']) ?></td>
                        <td>R$ <?= number_format($product['preco'], 2, ',', '.') ?></td>
                        <td><?= $product['quantidade_estoque'] ?></td>
                        <td><?= htmlspecialchars($product['categoria']) ?></td>
                        <td><?= htmlspecialchars($product['status']) ?></td>
                        <td class="actions">
                            <a href="index.php?page=admin&action=products&edit_id=<?= $product['id_produto'] ?>" class="btn-edit">Editar</a>
                            <a href="index.php?page=admin&action=deleteProduct&id=<?= $product['id_produto'] ?>" class="btn-delete" onclick="return confirm('Tem certeza?');">Deletar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>

    <!-- [MUDANÇA] Carrega o script específico da pré-visualização -->
    <script src="/assets/js/image-preview.js"></script>
    <script>
        // Ativa a função de pré-visualização, dizendo a ela para conectar
        // o input 'imagemInput' com a imagem 'imagePreview'.
        setupImagePreview('imagemInput', 'imagePreview');
    </script>
</body>
</html>