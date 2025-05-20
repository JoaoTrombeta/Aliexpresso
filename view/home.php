<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aliexpresso - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' type='text/css' media='screen' href='./assets/css/header.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='./assets/css/footer.css'>
    <link rel="stylesheet" href="./assets/css/home.css">
</head>
<body>

<!-- Primeira barra: Título e menu -->
<header>
    <div class="header-inner">
        <h1>Aliexpresso</h1>
        <div class="header-right">
            <img src="https://cdn-icons-png.flaticon.com/512/263/263142.png" alt="Ícone de carrinho" class="icon">
            <div class="menu-icon" id="menuIcon" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div id="dropdownMenu" class="dropdown-menu">
            <a href="index.php?controller=cliente&action=perfil">Perfil</a>
            <a href="index.php?controller=pedido&action=historico">Pedidos</a>
            <a href="index.php?controller=login&action=logout">Sair</a>
        </div>
    </div>
</header>

<!-- Segunda barra: Navegação -->
<div style="background-color: #c9a57b; padding: 10px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <nav role="navigation" aria-label="Menu principal">
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; gap: 20px;">
                <li><a href="index.php?controller=site&action=home" style="color: #fff; text-decoration: none;">Início</a></li>
                <li><a href="index.php?controller=produto&action=listar" style="color: #fff; text-decoration: none;">Produtos</a></li>
            </ul>
        </nav>
    </div>
</div>

<main>
    <!-- Seção Top 3 Vendas -->
    <section class="top-3-vendas">
        <h1 id="topVenda">Top em Vendas</h1>
        <div class="big-three">
            <div class="top3">
                <div class="podio">
                    <p class="titulo-podio">Top 2</p>
                    <p class="text-content">☕☕</p>
                </div>
                <img src="abababa" alt="Imagem do produto Top 2">
            </div>
            <div class="top3">
                <div class="podio">
                    <p class="titulo-podio">Top 1</p>
                    <p class="text-content">☕☕☕</p>
                </div>
                <img src="abababa" alt="Imagem do produto Top 1">
            </div>
            <div class="top3">
                <div class="podio">
                    <p class="titulo-podio">Top 3</p>
                    <p class="text-content">☕</p>
                </div>
                <img src="abababa" alt="Imagem do produto Top 3">
            </div>
        </div>
    </section>

    <article>
        <!-- Conteúdo adicional futuro -->
        asdhaishdshfusfufdsfdshfgdsgdsgdsfsfdfdsf
    </article>

    <section>
        <!-- Promoções, destaques, etc. -->
        shijshfishfdiushdjsckjdsnvbvjdsijfdoiwjdoiajsdosanckjdsn
    </section>
</main>
<?php include 'view/template/footer.php'; ?>
<script src="./assets/js/header.js"></script>
</body>
</html>