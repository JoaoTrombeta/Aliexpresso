<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Vendas - Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            margin: 0; 
        }
        
        /* [NOVO] Container principal mais largo, específico para o dashboard */
        main.dashboard-container {
            width: 100%;
            max-width: 1600px; /* Largura máxima maior */
            margin: 0 auto;
            padding: 2rem;
            box-sizing: border-box;
            flex-grow: 1;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-left: 5px solid var(--cor-destaque);
        }
        .stat-card h3 {
            margin: 0 0 0.5rem 0;
            color: #555;
            font-size: 1rem;
        }
        .stat-card p {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
            color: var(--cor-primaria);
        }
        .dashboard-section {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        /* [NOVO] Layout em grelha para o gráfico e a tabela */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 1fr; /* Uma coluna por defeito (mobile) */
            gap: 2rem;
        }

        /* Em ecrãs maiores, o layout divide-se em duas colunas */
        @media (min-width: 1200px) {
            .dashboard-layout {
                grid-template-columns: 2fr 1.5fr; /* Gráfico ocupa mais espaço que a tabela */
            }
        }
    </style>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="dashboard-container">
        <h1>Dashboard de Vendas</h1>
        <a href="index.php?page=admin" class="back-link">&larr; Voltar ao Painel</a>

        <!-- Cartões de Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Faturamento Total</h3>
                <p>R$ <?= number_format($stats['faturamento_total'] ?? 0, 2, ',', '.') ?></p>
            </div>
            <div class="stat-card">
                <h3>Total de Vendas</h3>
                <p><?= $stats['total_vendas'] ?? 0 ?></p>
            </div>
            <div class="stat-card">
                <h3>Ticket Médio</h3>
                <p>R$ <?= number_format($stats['ticket_medio'] ?? 0, 2, ',', '.') ?></p>
            </div>
        </div>

        <!-- [NOVO] Div para organizar o layout principal -->
        <div class="dashboard-layout">
            <!-- Coluna do Gráfico -->
            <div class="dashboard-section">
                <h2>Produtos Mais Vendidos</h2>
                <canvas id="bestSellersChart"></canvas>
            </div>

            <!-- Coluna dos Pedidos Recentes -->
            <div class="dashboard-section table-container">
                <h2>Pedidos Recentes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentOrders)): foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order['id_pedido'] ?></td>
                            <td><?= htmlspecialchars($order['nome_cliente']) ?></td>
                            <td><?= date('d/m/Y', strtotime($order['data_pedido'])) ?></td>
                            <td>R$ <?= number_format($order['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Nenhum pedido recente encontrado.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... (código do Chart.js para o gráfico, sem alterações) ...
        });
    </script>
</body>
</html>