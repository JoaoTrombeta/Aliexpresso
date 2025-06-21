<?php
    function renderHeader() {
    // Verifica se o usuário está logado
    $logado = isset($_SESSION['usuario']);
        ?>
            <header>
                <a href="index.php?page=home" class="site-logo">
                    <!-- Usando o seu caminho de imagem -->
                    <h1><img src="./assets/img/logo_aliexpresso4.png"></h1>
                </a>
                <div class="header-right">
                    <a href="index.php?page=Carrinho" class="header-cart-icon-link" aria-label="Carrinho de Compras">
                        <i class="fas fa-shopping-cart cart-icon"></i>
                        <span class="cart-badge" id="cartItemCount">0</span> 
                    </a>

                    <!-- Ícone que dispara o dropdown -->
                    <img src="https://placehold.co/30x30/eeeeee/777777?text=User" alt="Usuário" class="icon user-icon" id="userIconTrigger">
                    
                    <div class="menu-container"> 
                        <nav class="dropdown-menu" id="userDropdownMenu">
                            <?php if ($logado): ?>
                                <!-- OPÇÕES PARA USUÁRIO LOGADO -->
                                <!-- Exibe o nome do usuário -->
                                <div style="padding: 10px 15px; color: #555; font-weight: bold; border-bottom: 1px solid #eee;">
                                    <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>
                                </div>
                                <a href="index.php?page=pedidos&action=listar">Meus Pedidos</a>
                                <a href="index.php?page=usuario&action=config">Configurações</a>
                                <a href="index.php?page=usuario&action=logout">Sair</a>
                            <?php else: ?>
                                <!-- OPÇÕES PARA VISITANTE -->
                                <a href="index.php?page=usuario&action=login">Fazer Login</a>
                                <a href="index.php?page=usuario&action=register">Cadastrar-se</a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </header>
        <?php
    }

    function renderFooter() {
        echo '
        <footer>
            <p>&copy; 2025 AliExpresso. Todos os direitos reservados.</p>
        </footer>
    </body>
    </html>
    ';
    }
?>