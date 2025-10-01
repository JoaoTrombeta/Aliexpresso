<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos - Aliexpresso</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos para a página de histórico */
        .historico-container { padding: 2rem 5%; max-width: 900px; margin: 2rem auto; font-family: sans-serif; }
        .pedido-card { border: 1px solid #e0e0e0; border-radius: 8px; margin-bottom: 2rem; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .pedido-header { background-color: #f8f9fa; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e0e0e0; }
        .pedido-header div { line-height: 1.4; }
        .pedido-header p { margin: 0; color: #6c757d; font-size: 0.9em; }
        .pedido-itens { padding: 1rem 1.5rem; }
        .item { display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f0f0f0; }
        .item:last-child { border-bottom: none; padding-bottom: 0; }
        .item img { width: 65px; height: 65px; object-fit: cover; border-radius: 4px; margin-right: 1.5rem; }
        .item-info p { margin: 0; font-weight: bold; }
        .item-info small { color: #6c757d; }
        .status { padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8em; font-weight: bold; color: white; }
        .status-concluido { background-color: #28a745; }
        .status-processando { background-color: #ffc107; color: #333; }
        .status-enviado { background-color: #17a2b8; }
    </style>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="historico-container">
        <h1>Meus Pedidos</h1>

        <?php if (isset($_SESSION['mensagem_cupom_fidelidade'])): ?>
            <div class="mensagem-sucesso" style="background-color: #d1ecf1; color: #0c5460; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #bee5eb;">
                <?= $_SESSION['mensagem_cupom_fidelidade'] ?>
            </div>
            <?php unset($_SESSION['mensagem_cupom_fidelidade']); // Limpa a mensagem para não mostrar de novo ?>
        <?php endif; ?>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="mensagem-sucesso">Seu pedido foi finalizado com sucesso!</div>
        <?php endif; ?>

        <?php if (empty($pedidos)): ?>
            <div class="pedido-card">
                <div style="padding: 1.5rem;">
                    <p>Você ainda não tem nenhum pedido registrado.</p>
                    <a href="./?page=produto" style="text-decoration: none; color: white; background-color: #007bff; padding: 0.5rem 1rem; border-radius: 5px; display: inline-block;">Ver Produtos</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($pedidos as $pedido): ?>
                <div class="pedido-card">
                    <div class="pedido-header">
                        <div>
                            <strong>Pedido #<?= $pedido->id_pedido ?></strong>
                            <p>Data: <?= date('d/m/Y', strtotime($pedido->data_pedido)) ?></p>
                        </div>
                        <div style="text-align: right;">
                            <strong>Total pago: R$ <?= number_format($pedido->valor_final, 2, ',', '.') ?></strong>
                            <?php if ($pedido->desconto > 0): ?>
                                <p style="color: #28a745; font-size: 0.85em;">
                                    Você economizou R$ <?= number_format($pedido->desconto, 2, ',', '.') ?>
                                </p>
                            <?php endif; ?>
                            <p>Status: <span class="status status-<?= strtolower($pedido->status) ?>"><?= ucfirst($pedido->status) ?></span></p>
                        </div>
                    </div>
                    <div class="pedido-itens">
                        <h4>Itens do Pedido:</h4>
                        <?php foreach ($pedido->itens as $item): ?>
                            <div class="item">
                                <img src="<?= htmlspecialchars($item->imagem) ?>" alt="<?= htmlspecialchars($item->nome) ?>">
                                <div class="item-info">
                                    <p><?= htmlspecialchars($item->nome) ?></p>
                                    <small>Quantidade: <?= $item->quantidade ?> | Preço Unit.: R$ <?= number_format($item->preco_unitario, 2, ',', '.') ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
</body>
</html>