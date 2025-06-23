<?php
    require_once "./controller/produtoController.php";
?>


    <main>
        <h2>Produtos</h2>
        <div class="grid-produtos">
            <?php foreach($produtos as $produto): ?>
                <div class="card">
                    <!-- 
                        Corrigido para usar os métodos get() da interface.
                        A imagem agora usa getImagem(), que pode conter uma URL completa ou um caminho.
                        É mais seguro usar htmlspecialchars() para evitar problemas de segurança.
                    -->
                    <img src="<?= htmlspecialchars($produto->getImagem()) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">
                    <h3><?= htmlspecialchars($produto->getNome()) ?></h3>
                    <p><?= htmlspecialchars($produto->getDescricao()) ?></p>
                    <strong><?= $produto->getPrecoFormatado() ?></strong>
                    <button>Adicionar ao carrinho</button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php 
        // Supondo que você tenha uma classe PageController para renderizar o rodapé
        if (class_exists('\Aliexpresso\Controller\PageController')) {
            \Aliexpresso\Controller\PageController::renderFooter();
        }
    ?>
