<?php
namespace Aliexpresso\Controller;

<<<<<<< HEAD
=======
<<<<<<< HEAD
use Aliexpresso\Model\ProdutoModel;

class ProdutoController {
    /**
     * Busca todos os produtos ativos e os exibe em uma view.
     */
    public function listar() {
        $produtoModel = new ProdutoModel();
        
        // Chama o método que busca apenas produtos ativos
        $produtos = $produtoModel->buscarTodosAtivos();

        // A view recebe os objetos e os exibe
        require_once '/../view/produtos/produtos.php';
=======
>>>>>>> bacc9bfc235210900b6db3f9a913727e3e34920c
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
>>>>>>> f9d1cbfdad54ec8ff87926193adbdb430121d076
    }
}
