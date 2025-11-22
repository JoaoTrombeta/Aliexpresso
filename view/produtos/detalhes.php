<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($produto['nome']) ?> - Aliexpresso</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* CSS Específico da Página de Detalhes (Tema Café) */
        body { background-color: #f2e6c2; font-family: 'Segoe UI', sans-serif; }
        
        .detalhe-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        /* Área do Produto */
        .produto-wrapper {
            display: flex; gap: 40px; background: #fffbf2; padding: 40px;
            border-radius: 16px; box-shadow: 0 5px 20px rgba(93, 83, 65, 0.1);
            border: 1px solid #e6dcc3;
        }
        .produto-img img {
            max-width: 400px; width: 100%; border-radius: 12px; border: 1px solid #e6dcc3;
        }
        .produto-info h1 { color: #5d5341; margin-top: 0; font-size: 32px; }
        .produto-desc { color: #6b5e4f; line-height: 1.6; font-size: 18px; margin: 20px 0; }
        .produto-preco { font-size: 36px; color: #a3835f; font-weight: bold; display: block; margin-bottom: 20px; }
        
        .btn-add {
            background-color: #5d5341; color: white; padding: 15px 40px;
            text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 18px;
            display: inline-block; transition: background 0.3s; border: none; cursor: pointer;
        }
        .btn-add:hover { background-color: #8c7b64; }

        /* Área de Avaliações */
        .avaliacoes-section { margin-top: 50px; }
        .avaliacoes-header { border-bottom: 2px solid #a3835f; padding-bottom: 10px; margin-bottom: 30px; color: #5d5341; }
        
        /* Formulário */
        .form-avaliacao {
            background: #fff; padding: 30px; border-radius: 12px; margin-bottom: 40px;
            border: 1px solid #e6dcc3;
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #5d5341; font-weight: 600; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        textarea.form-control { resize: vertical; height: 100px; }
        
        /* Estrelas na listagem */
        .star-rating { color: #ffb300; font-size: 18px; }
        
        /* Lista de Comentários */
        .review-card {
            background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px;
            border-left: 5px solid #a3835f; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .review-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .review-user { font-weight: bold; color: #5d5341; }
        .review-date { font-size: 12px; color: #999; }
        .review-img { max-width: 100px; margin-top: 10px; border-radius: 5px; cursor: pointer; }
        
        /* Ajuste responsivo */
        @media (max-width: 768px) { .produto-wrapper { flex-direction: column; } }
    </style>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="detalhe-container">
        
        <div class="produto-wrapper">
            <div class="produto-img">
                <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
            </div>
            <div class="produto-info">
                <h1><?= htmlspecialchars($produto['nome']) ?></h1>
                <span class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                <p class="produto-desc"><?= htmlspecialchars($produto['descricao']) ?></p>
                
                <a href="index.php?page=carrinho&action=ajax_add&id=<?= $produto['id_produto'] ?>" class="btn-add js-add-to-cart">
                    <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                </a>
            </div>
        </div>

        <div class="avaliacoes-section">
            <h2 class="avaliacoes-header">Avaliações dos Clientes (<?= count($avaliacoes) ?>)</h2>

            <?php if (isset($_SESSION['usuario'])): ?>
                <div class="form-avaliacao">
                    <h3>Deixe sua opinião</h3>
                    <form action="index.php?page=produto&action=avaliar" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                        
                        <div class="form-group">
                            <label>Nota:</label>
                            <select name="nota" class="form-control" style="max-width: 150px;" required>
                                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                <option value="4">⭐⭐⭐⭐ (4)</option>
                                <option value="3">⭐⭐⭐ (3)</option>
                                <option value="2">⭐⭐ (2)</option>
                                <option value="1">⭐ (1)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Comentário:</label>
                            <textarea name="comentario" class="form-control" placeholder="O que achou do café?" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Foto do produto (Opcional):</label>
                            <input type="file" name="imagem" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn-add" style="font-size: 16px; padding: 10px 30px;">Enviar Avaliação</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="fidelidade-box" style="text-align:center; margin-bottom:30px;">
                    Para avaliar, faça <a href="index.php?page=usuario&action=login">Login</a>.
                </div>
            <?php endif; ?>

            <?php if (empty($avaliacoes)): ?>
                <p style="color:#666;">Seja o primeiro a avaliar este produto!</p>
            <?php else: ?>
                <?php foreach ($avaliacoes as $av): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div>
                                <span class="review-user"><?= htmlspecialchars($av['nome_usuario']) ?></span>
                                <div class="star-rating">
                                    <?= str_repeat('★', $av['nota']) . str_repeat('☆', 5 - $av['nota']) ?>
                                </div>
                            </div>
                            <span class="review-date"><?= date('d/m/Y', strtotime($av['data_avaliacao'])) ?></span>
                        </div>
                        <p><?= nl2br(htmlspecialchars($av['comentario'])) ?></p>
                        
                        <?php if (!empty($av['imagem'])): ?>
                            <img src="assets/img/avaliacoes/<?= htmlspecialchars($av['imagem']) ?>" class="review-img" alt="Foto do cliente" onclick="window.open(this.src)">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
    
    <script src="assets/js/cart-ajax.js"></script>
</body>
</html>