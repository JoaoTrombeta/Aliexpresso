<?php 
// view/carrinho/sucesso_compra.php

// Formata a data para o padrão brasileiro
$dataFormatada = date('d/m/Y H:i', strtotime($pedido['data_pedido']));

// Lógica de cores do status (Mantendo semântica mas com tons pastéis/terrosos)
$statusClass = '';
$statusLabel = $pedido['status'];

if ($pedido['status'] === 'Aprovado' || $pedido['status'] === 'concluido') {
    $statusClass = 'status-aprovado';
} else {
    $statusClass = 'status-pendente';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recibo #<?= $pedido['id_pedido'] ?> - Aliexpresso</title>
    
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* === TEMA ALIEXPRESSO (COFFEE STYLE) === */
        :root {
            --coffee-dark: #5d5341;   /* Marrom escuro do banner */
            --coffee-med: #8c7b64;    /* Marrom médio */
            --coffee-light: #a3835f;  /* Cor do botão 'Ver Produtos' */
            --cream-bg: #f2e6c2;      /* Fundo do site */
            --cream-card: #fffbf2;    /* Fundo do recibo (quase branco) */
            --text-main: #3e3221;     /* Texto escuro */
            --text-light: #6b5e4f;    /* Texto secundário */
            --success-green: #4a6840; /* Verde "Oliva" para sucesso */
        }

        body {
            background-color: var(--cream-bg); /* Fundo bege do site */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding-bottom: 50px;
        }

        .extrato-container {
            max-width: 750px;
            margin: 40px auto;
            background: var(--cream-card);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(93, 83, 65, 0.15); /* Sombra marrom suave */
            overflow: hidden;
            border: 1px solid #e6dcc3;
        }

        /* Cabeçalho do Recibo */
        .extrato-header {
            background-color: var(--coffee-dark);
            color: #fff;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .logo-icon {
            font-size: 40px;
            margin-bottom: 10px;
            color: #e6dcc3;
        }

        .extrato-header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .extrato-header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.8;
            color: #e6dcc3;
        }

        .extrato-content { padding: 40px; }

        /* Blocos de Informação */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 2px dashed #e6dcc3;
            padding-bottom: 30px;
        }

        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-light);
            display: block;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Badges de Status */
        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: bold;
        }
        .status-aprovado {
            background-color: #dcedc8; /* Verde pastel */
            color: var(--success-green);
        }
        .status-pendente {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        /* Tabela */
        .tabela-itens {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .tabela-itens th {
            text-align: left;
            color: var(--text-light);
            font-size: 13px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e6dcc3;
        }
        .tabela-itens td {
            padding: 15px 0;
            border-bottom: 1px solid #f5efdf;
            color: var(--text-main);
        }
        .tabela-itens .col-total { text-align: right; font-weight: 600; }
        
        .item-box { display: flex; align-items: center; }
        .item-img {
            width: 45px; height: 45px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 15px;
            border: 1px solid #e6dcc3;
        }

        /* Box Fidelidade */
        .fidelidade-box {
            background: #fff8e1; /* Amarelo creme */
            border: 1px solid #ffe082;
            color: var(--coffee-dark);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        .fidelidade-box::before {
            content: '★';
            font-size: 20px;
            color: #ffb300;
            display: block;
            margin-bottom: 5px;
        }

        /* Total */
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-top: 20px;
        }
        .total-label { font-size: 18px; color: var(--text-main); }
        .total-value { font-size: 32px; font-weight: 800; color: var(--coffee-dark); }

        /* Botões */
        .acoes-container {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn {
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }
        .btn-imprimir {
            background-color: #e8e0d0;
            color: var(--coffee-dark);
        }
        .btn-imprimir:hover { background-color: #dcd0bc; }

        .btn-novo {
            background-color: var(--coffee-dark);
            color: white;
            box-shadow: 0 4px 10px rgba(93, 83, 65, 0.3);
        }
        .btn-novo:hover {
            background-color: var(--coffee-med);
            transform: translateY(-2px);
        }

        @media print {
            body { background: white; }
            .extrato-container { box-shadow: none; border: none; margin: 0; max-width: 100%; }
            .acoes-container { display: none; }
            .extrato-header { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <div class="extrato-container">
        <div class="extrato-header">
            <div class="logo-icon"><i class="fas fa-coffee"></i></div>
            <h1>Recibo de Compra</h1>
            <p>Pedido #<?= $pedido['id_pedido'] ?> • Aliexpresso</p>
        </div>

        <div class="extrato-content">
            
            <div class="info-grid">
                <div>
                    <span class="info-label">Cliente</span>
                    <span class="info-value">
                        <i class="far fa-user"></i> <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>
                    </span>
                </div>
                <div style="text-align: right;">
                    <span class="info-label">Data e Hora</span>
                    <span class="info-value" style="justify-content: flex-end;">
                        <?= $dataFormatada ?> <i class="far fa-clock"></i>
                    </span>
                </div>
                <div>
                    <span class="info-label">Pagamento</span>
                    <span class="info-value">
                        <?= htmlspecialchars($pedido['forma_pagamento'] ?? 'Cartão') ?>
                    </span>
                </div>
                <div style="text-align: right;">
                    <span class="info-label">Status</span>
                    <span class="status-badge <?= $statusClass ?>">
                        <?= htmlspecialchars($pedido['status']) ?>
                    </span>
                </div>
            </div>

            <table class="tabela-itens">
                <thead>
                    <tr>
                        <th>PRODUTO</th>
                        <th style="text-align: center;">QTD</th>
                        <th class="col-total">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itensPedido as $item): ?>
                    <tr>
                        <td>
                            <div class="item-box">
                                <img src="<?= htmlspecialchars($item['imagem'] ?? 'assets/img/placeholder.png') ?>" class="item-img" onerror="this.src='assets/img/placeholder.png'">
                                <div>
                                    <strong style="color: var(--text-main);"><?= htmlspecialchars($item['nome']) ?></strong>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center; color: var(--text-light);">x<?= $item['quantidade'] ?></td>
                        <td class="col-total">
                            R$ <?= number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (!empty($mensagemFidelidade)): ?>
                <div class="fidelidade-box">
                    <?= $mensagemFidelidade ?>
                </div>
            <?php endif; ?>

            <div class="total-row">
                <span class="total-label">Valor Total Pago</span>
                <span class="total-value">R$ <?= number_format($pedido['valor_final'], 2, ',', '.') ?></span>
            </div>

            <div class="acoes-container">
                <button onclick="window.print()" class="btn btn-imprimir">
                    <i class="fas fa-print"></i> Imprimir Recibo
                </button>
                <a href="index.php" class="btn btn-novo">
                    Fazer Nova Compra
                </a>
            </div>

        </div>
    </div>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
</body>
</html>