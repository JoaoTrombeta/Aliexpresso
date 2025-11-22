<?php 
use Aliexpresso\Helper\ReviewHelper; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Avaliações - Aliexpresso</title>
    <style>
        body { font-family: "Segoe UI", Tahoma, sans-serif; background: #fffbf2; color: #3e3221; }
        .container { max-width: 700px; margin: 40px auto; padding: 20px; }
        .card { background: #fff; border: 1px solid #e6dcc3; border-radius: 8px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        h2 { color: #5d5341; border-bottom: 2px solid #e6dcc3; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #e6dcc3; border-radius: 4px; font-family: inherit; }
        button { background: #a3835f; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #8c704f; }
        
        /* Review List Styles */
        .review-item { border-bottom: 1px solid #eee; padding: 15px 0; }
        .review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .user-name { font-weight: bold; color: #d97706; }
        .date { font-size: 12px; color: #999; }
        .ai-badge { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>

<div class="container">
    <!-- Formulário -->
    <div class="card">
        <h2>Deixe sua opinião sobre o Café</h2>
        <form action="/store" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
            
            <div class="form-group">
                <label>Seu Nome</label>
                <input type="text" name="name" required placeholder="Ex: João Barista">
            </div>

            <div class="form-group">
                <label>Sua experiência</label>
                <textarea name="comment" rows="3" required placeholder="O que achou do aroma e sabor?"></textarea>
            </div>

            <button type="submit">Enviar Avaliação</button>
        </form>
    </div>

    <!-- Lista de Avaliações -->
    <div class="card">
        <h3>O que estão dizendo</h3>
        
        <?php if (empty($reviews)): ?>
            <p style="text-align: center; color: #999;">Seja o primeiro a avaliar este café!</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-header">
                        <div>
                            <span class="user-name"><?php echo htmlspecialchars($review['user_name']); ?></span>
                            <!-- Helper Static Call -->
                            <?php echo ReviewHelper::renderStars($review['stars']); ?>
                        </div>
                        <span class="date"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></span>
                    </div>
                    
                    <p style="margin: 5px 0 10px 0;">
                        <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                    </p>

                    <!-- AQUI ENTRA A MÁGICA DA IA (Via Helper) -->
                    <div class="ai-analysis">
                        <small style="color: #8c7b64;">Análise Aliexpresso AI:</small> 
                        <?php echo ReviewHelper::getSentimentBadge($review['sentiment']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>