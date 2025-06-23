<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nossos Produtos - Aliexpresso</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- [ATUALIZADO] CSS para estilizar o catálogo como na imagem -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--cor-fundo);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .catalog-container {
            flex-grow: 1;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .catalog-container h1 {
            color: var(--cor-primaria);
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 500;
        }
        .grid-produtos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }
        .card-produto {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
        }
        .card-produto:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        .card-produto img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 1px solid #f0f0f0;
        }
        .card-info {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .card-info h3 {
            margin: 0 0 0.5rem 0;
            color: var(--cor-texto);
            font-size: 1.1rem;
            font-weight: 600;
        }
        .card-info .descricao {
            font-size: 0.9rem;
            color: #6c757d;
            flex-grow: 1;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        .card-info .preco {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--cor-primaria);
            margin-bottom: 1.25rem;
        }
        .card-info .btn-comprar {
            background-color: var(--cor-primaria);
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }
        .card-info .btn-comprar:hover {
            background-color: #4E342E; /* Um tom de marrom mais escuro */
        }
    </style>
</head>
<body>
    <?php \Aliexpresso\Controller\PageController::renderHeader(); ?>

    <main class="catalog-container">
        <h1>Conheça Nossos Produtos</h1>
        <div class="grid-produtos">
            <?php if (empty($produtos)): ?>
                <p>Nenhum produto encontrado.</p>
            <?php else: ?>
                <?php foreach($produtos as $produto): ?>
                    <div class="card-produto">
                        <!-- Usando a sintaxe de array e htmlspecialchars para segurança -->
                        <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <div class="card-info">
                            <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                            <p class="descricao"><?= htmlspecialchars($produto['descricao']) ?></p>
                            <strong class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></strong>
                            <a href="#" class="btn-comprar">Adicionar ao Carrinho</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php \Aliexpresso\Controller\PageController::renderFooter(); ?>
</body>
</html>