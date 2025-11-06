<?php
/**
 * ARQUIVO: controller/pageController.php (ATUALIZADO E CORRIGIDO)
 *
 * 1. Adiciona a lógica do Avatar Dinâmico.
 * 2. Adiciona o link "Meu Perfil".
 * 3. Corrige o erro de sintaxe no renderFooter() que impedia o header.js de carregar.
 */
    namespace Aliexpresso\Controller;

    use Aliexpresso\Helper\Auth;

    class PageController
    {
        public static function renderHeader() {
            $logado = Auth::isLoggedIn();

            $totalItems = 0; 
            if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho']['produtos'])) {
                $quantities = array_column($_SESSION['carrinho']['produtos'], 'quantidade');
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

                    <!-- [LÓGICA DO AVATAR DINÂMICO COMEÇA AQUI] -->
                    <?php if ($logado): ?>
                        <?php 
                            $userImage = Auth::user()['imagem_perfil'] ?? null;
                            $userName = Auth::user()['nome'];
                            $userInitial = mb_strtoupper(mb_substr($userName, 0, 1));
                        ?>
                        
                        <?php if (!empty($userImage)): ?>
                            <!-- Se tem imagem de perfil, mostra a <img> -->
                            <img src="<?= htmlspecialchars($userImage) ?>" alt="Imagem de Perfil" class="icon user-icon" id="userIconTrigger">
                        <?php else: ?>
                            <!-- Se não tem imagem, mostra a <div> com a inicial -->
                            <div class="user-avatar-default icon user-icon" id="userIconTrigger" title="<?= htmlspecialchars($userName) ?>">
                                <span><?= $userInitial ?></span>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- Se não está logado, mostra o placeholder padrão -->
                        <img src="https://placehold.co/30x30/eeeeee/777777?text=User" alt="Usuário" class="icon user-icon" id="userIconTrigger">
                    <?php endif; ?>
                    <!-- [FIM DA LÓGICA DO AVATAR] -->
                    
                    <div class="menu-container"> 
                        <nav class="dropdown-menu" id="userDropdownMenu">
                            <?php if ($logado): ?>
                                <div class="user-info">
                                    <?= htmlspecialchars(Auth::user()['nome']) ?>
                                </div>
                                <a href="index.php?page=usuario&action=perfil">Meu Perfil</a>
                                <?php if (Auth::isAdmin()): ?>
                                    <a href="index.php?page=admin">Painel do Admin</a>
                                <?php endif; ?>
                                <?php if (Auth::isClient()): ?>
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
            <!-- [CORREÇÃO DE SINTAXE] A tag <script> agora está fechada corretamente -->
            <script src="./assets/js/header.js"></script>
        <?php
        }
    }
?>