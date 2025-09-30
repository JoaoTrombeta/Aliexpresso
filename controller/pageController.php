<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Helper\Auth;

    class PageController
    {
        public static function renderHeader() {
            $logado = Auth::isLoggedIn();

            $totalItems = 0; // Inicia a contagem como 0
            
            // Verifica se o carrinho e a chave 'produtos' existem na sessão
            if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho']['produtos'])) {
                // Pega apenas a coluna 'quantidade' de cada produto no carrinho
                $quantities = array_column($_SESSION['carrinho']['produtos'], 'quantidade');
                // Soma todos os valores da coluna 'quantidade' para ter o total real de itens
                $totalItems = array_sum($quantities);
            }
        ?>
            <header>
                <a href="index.php?page=home" class="site-logo">
                    <h1><img src="./assets/img/logo_aliexpresso4.png" alt="Logo Aliexpresso"></h1>
                </a>
                <div class="header-right">
                    <?php if (\Aliexpresso\Helper\Auth::isClient()): ?>
                        <a href="index.php?page=Carrinho" class="header-cart-icon-link cart-icon-container" aria-label="Carrinho de Compras">
                            <i class="fas fa-shopping-cart cart-icon"></i>
                            
                            <?php if ($totalItems > 0): ?>
                                <span class="cart-badge" id="cart-count"><?= $totalItems ?></span>
                            <?php endif; ?> 
                        </a>
                    <?php endif; ?>

                    <img src="https://placehold.co/30x30/eeeeee/777777?text=User" alt="Usuário" class="icon user-icon" id="userIconTrigger">
                    
                    <div class="menu-container"> 
                        <nav class="dropdown-menu" id="userDropdownMenu">
                            <?php if ($logado): ?>
                                <div class="user-info">
                                    <?= htmlspecialchars(\Aliexpresso\Helper\Auth::user()['nome']) ?>
                                </div>

                                <?php if (\Aliexpresso\Helper\Auth::isAdmin()): ?>
                                    <a href="index.php?page=admin">Painel do Admin</a>
                                <?php endif; ?>
                                <?php if (\Aliexpresso\Helper\Auth::isClient()): ?>
                                    <a href="index.php?page=pedido&action=historico">Meus Pedidos</a>
                                <?php endif; ?>
                                <a href="index.php?page=usuario&action=config">Configurações</a>
                                <a href="index.php?page=usuario&action=logout">Sair</a>
                            <?php else: ?>
                                <a href="index.php?page=usuario&action=login">Fazer Login</a>
                                <a href="index.php?page=usuario&action=register">Cadastrar-se</a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </header>
        <?php
        }

        public static function renderFooter() {
        ?>
            <footer>
                <p>&copy; <?= date('Y') ?> Aliexpresso. Todos os direitos reservados.</p>
            </footer>
            <script src="./assets/js/header.js"
        <?php
        }
    }
?>