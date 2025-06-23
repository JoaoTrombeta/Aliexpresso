<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Model\ProdutoModel;

    class ProdutoController {

        /**
         * Ação principal: exibe o catálogo de produtos.
         */
        public function index() {
            $produtoModel = new ProdutoModel();
            
            // Usa o novo método para buscar apenas produtos "à venda"
            $produtos = $produtoModel->getAllVisible();

            // Carrega a view do catálogo e passa a variável $produtos para ela
            require_once __DIR__ . '/../view/produtos/index.php';
        }
    }
?>