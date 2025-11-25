<?php
// Imports para facilitar o uso na view
use Aliexpresso\Helper\ReviewHelper;
use Aliexpresso\Helper\Auth;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($produto['nome']) ?> - Aliexpresso</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/produtos.css"> 
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* CSS Inline específico para detalhes (pode mover para produtos.css depois) */
        .detalhes-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .produto-img img { width: 100%; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .produto-info h1 { font-size: 2.5rem; color: #3e3221; margin-bottom: 10px; }
        .preco-destaque { font-size: 2rem; color: #a3835f; font-weight: bold; margin: 20px 0; }
        .btn-add { background: #27ae60; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 1.1rem; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-block;}
        .btn-add:hover { background: #219150; }

        /* Área de Reviews */
        .reviews-section { grid-column: 1 / -1; margin-top: 60px; border-top: 1px solid #ddd; padding-top: 40px; }
        .review-card { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #eee; display: flex; gap: 20px; }
        .review-avatar { width: 50px; height: 50px; background: #eee; border-radius: 50%; object-fit: cover; }
        .review-content { flex: 1; }
        .review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .review-ia-tag { margin-top: 10px; font-size: 0.85rem; color: #666; background: #f9f9f9; padding: 8px; border-radius: 6px; display: inline-block; }
        
        .form-review { background: #fdfdfd; padding: 25px; border-radius: 8px; border: 1px dashed #ccc; margin-bottom: 40px; }
        .form-review textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>

    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="detalhes-container">
        <!-- Imagem -->
        <div class="produto-img">
            <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
        </div>

        <!-- Informações -->
        <div class="produto-info">
            <h1><?= htmlspecialchars($produto['nome']) ?></h1>
            <p><?= htmlspecialchars($produto['categoria']) ?></p>
            
            <div class="preco-destaque">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
            
            <p><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>

            <br>
            <a href="index.php?page=carrinho&action=ajax_add&id=<?= $produto['id_produto'] ?>" class="btn-add">
                <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
            </a>
        </div>

        <!-- Seção de Avaliações -->
        <div class="reviews-section">
            <h2>Avaliações dos Clientes</h2>
            
            <!-- Formulário (Só aparece se logado) -->
            <?php if (Auth::isLoggedIn()): ?>
                <div class="form-review">
                    <h3>Deixe sua opinião</h3>
                    <form action="index.php?page=produto&action=avaliar" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                        
                        <label>Sua Nota:</label>
                        <select name="nota" required style="padding: 5px;">
                            <option value="5">★★★★★ - Excelente</option>
                            <option value="4">★★★★☆ - Muito Bom</option>
                            <option value="3">★★★☆☆ - Regular</option>
                            <option value="2">★★☆☆☆ - Ruim</option>
                            <option value="1">★☆☆☆☆ - Péssimo</option>
                        </select>

                        <textarea name="comentario" rows="4" placeholder="O que você achou deste produto? (A IA irá analisar seu comentário!)" required></textarea>
                        
                        <label>Foto (Opcional):</label>
                        <input type="file" name="imagem" accept="image/*">

                        <button type="submit" class="btn-add" style="font-size: 0.9rem; padding: 10px 20px;">Enviar Avaliação</button>
                    </form>
                </div>
            <?php else: ?>
                <p><a href="index.php?page=usuario&action=login" style="color: #a3835f; font-weight: bold;">Faça login</a> para avaliar este produto.</p>
            <?php endif; ?>

            <!-- Lista de Reviews -->
            <?php if (empty($avaliacoes)): ?>
                <p style="color: #777;">Ainda não há avaliações para este produto. Seja o primeiro!</p>
            <?php else: ?>
                <?php foreach ($avaliacoes as $av): ?>
                    <div class="review-card">
                        <!-- Avatar -->
                        <img src="<?= $av['foto_usuario'] ? './assets/images/perfil/'.$av['foto_usuario'] : 'assets/img/icon_site.png' ?>" class="review-avatar">
                        
                        <div class="review-content">
                            <div class="review-header">
                                <div>
                                    <strong><?= htmlspecialchars($av['nome_usuario']) ?></strong>
                                    <small style="color: #999;"> • <?= date('d/m/Y', strtotime($av['data_avaliacao'])) ?></small>
                                </div>
                                <?= ReviewHelper::renderEstrelas($av['nota']) ?>
                            </div>

                            <p><?= nl2br(htmlspecialchars($av['comentario'])) ?></p>
                            
                            <?php if ($av['imagem']): ?>
                                <div style="margin-top: 10px;">
                                    <img src="assets/img/avaliacoes/<?= $av['imagem'] ?>" style="height: 60px; border-radius: 4px;">
                                </div>
                            <?php endif; ?>

                            <!-- TAG DA IA -->
                            <div class="review-ia-tag">
                                <strong>Análise IA:</strong> 
                                <?= ReviewHelper::getBadgeIA($av['sentimento_ia'] ?? 'Neutro') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>

</body>
</html>