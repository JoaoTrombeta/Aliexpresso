<?php
namespace Aliexpresso\Controller;

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
    }
}
