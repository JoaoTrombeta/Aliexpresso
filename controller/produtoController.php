<?php
// controllers/ProdutoController.php
require_once 'model/Produto.php';

class ProdutoController {
    public function listarProdutos() {
        $produtoModel = new Produto();
        $produtos = $produtoModel->obterTodosProdutos();  // Obter todos os produtos

        // Passar os dados para a view
        include 'view/lista_produtos.php';
    }

    public function detalhesProduto($id_produto) {
        $produtoModel = new Produto();
        $produto = $produtoModel->obterProdutoPorId($id_produto);

        if ($produto) {
            include 'view/produto_detalhes.php';
        } else {
            echo "Produto não encontrado!";
        }
    }
}
?>
