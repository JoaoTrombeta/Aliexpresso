<?php
    function renderHeader() {
        echo '
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
            <div class="menu-container"> 
                <nav class="dropdown-menu" id="userDropdownMenu">
                    <a href="#">Meu Perfil</a>
                    <a href="#">Meus Pedidos</a>
                    <a href="#">Configurações</a>
                    <a href="index.php?page=logout">Sair</a>
                </nav>
            </div>
        </div>
    </header>
    ';
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