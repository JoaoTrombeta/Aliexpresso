<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>AliExpresso - Home</title>
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
    <a href="index.php?page=home" class="site-logo">
        <h1>AliExpresso</h1>
    </a>
    <div class="header-right">
        <a href="index.php?page=Carrinho" class="header-cart-icon-link" aria-label="Carrinho de Compras">
            <i class="fas fa-shopping-cart cart-icon"></i>
            <span class="cart-badge" id="cartItemCount">0</span> 
        </a>

        <img src="https://placehold.co/30x30/eeeeee/777777?text=User" alt="Usuário" class="icon user-icon" id="userIconTrigger">
        <div class="menu-container"> <nav class="dropdown-menu" id="userDropdownMenu">
                <a href="#">Meu Perfil</a>
                <a href="#">Meus Pedidos</a>
                <a href="#">Configurações</a>
                <a href="index.php?page=logout">Sair</a>
            </nav>
        </div>
    </div>
    </header>


    <main>
        <section class="banner">
            <div class="banner-conteudo">
                <h2>Seja Bem-vindo ao AliExpresso</h2>
                <p>O seu e-commerce favorito de produtos com cafeína, para quem vive acelerado e produtivo.</p>
                <a href="index.php?page=produtos" class="btn">Ver Produtos</a>
            </div>
        </section>

        <section class="sobre">
            <h2>Por que escolher o AliExpresso?</h2>
            <div class="grid-beneficios">
                <div class="card">
                    <h3>Produtos Premium</h3>
                    <p>Selecionamos os melhores cafés, chocolates e bebidas energéticas para você se manter focado.</p>
                </div>
                <div class="card">
                    <h3>Entrega Rápida</h3>
                    <p>Seu pedido chega até você rapidamente para que nunca falte energia no seu dia.</p>
                </div>
                <div class="card">
                    <h3>Descontos e Cupons</h3>
                    <p>Aproveite ofertas exclusivas, cupons de desconto e frete promocional.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 AliExpresso. Todos os direitos reservados.</p>
    </footer>
    <script src="./assets/js/header.js"></script>
</body>
</html>
