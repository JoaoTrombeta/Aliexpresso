<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Helper\Auth;

    class PageController
    {
        public static function renderHeader() {
            $logado = Auth::isLoggedIn();
        ?>
            <header>
                <a href="index.php?page=home" class="site-logo">
                    <h1><img src="./assets/img/logo_aliexpresso4.png" alt="Logo Aliexpresso"></h1>
                </a>
                <div class="header-right">
                    <a href="index.php?page=Carrinho" class="header-cart-icon-link" aria-label="Carrinho de Compras">
                        <i class="fas fa-shopping-cart cart-icon"></i>
                        <span class="cart-badge" id="cartItemCount">0</span> 
                    </a>

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

                                <a href="index.php?page=pedidos&action=listar">Meus Pedidos</a>
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

        /**
         * Renderiza o rodapé padrão do site.
         */
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