<?php
    require_once __DIR__ . "/../../controller/pageController.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Aliexpresso</title>
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
        <h1>Painel Administrativo</h1>

        <a href="index.php?page=home" class="back-link" style="margin-bottom: 1rem;">&larr; Voltar para a tela inicial</a>

        <p>Bem-vindo, <?= htmlspecialchars(\Aliexpresso\Helper\Auth::user()['nome']) ?>!</p>
        <nav class="admin-nav">
            <a href="index.php?page=admin&action=usuarios" class="admin-nav-link">Gerenciar Usuários</a>
            <a href="index.php?page=admin&action=produtos" class="admin-nav-link">Gerenciar Produtos</a>
            <a href="index.php?page=admin&action=cupons" class="admin-nav-link">Gerenciar Cupons</a>
        </nav>
        <div class="admin-content">
            <p>Selecione uma opção acima para começar.</p>
        </div>
    </main>
    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
</body>
</html>