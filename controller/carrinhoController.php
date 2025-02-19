
<?php

require_once 'model/Carrinho.php';

class CarrinhoController {
    public function adicionarCarrinho($id_produto) {
        // Criação de um carrinho na sessão (simulando banco de dados)
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = new Carrinho();
        }

        // Supondo que a instância do produto já foi criada
        $produto = Produto::obterProdutoPorId($id_produto);
        if ($produto) {
            $_SESSION['carrinho']->adicionarProduto($produto);
            echo "Produto adicionado ao carrinho!";
        } else {
            echo "Produto não encontrado!";
        }
    }

    public function visualizarCarrinho() {
        if (isset($_SESSION['carrinho'])) {
            $produtos = $_SESSION['carrinho']->obterProdutos();
            $total = $_SESSION['carrinho']->calcularTotal();
            include 'view/carrinho.php';
        } else {
            echo "Carrinho vazio.";
        }
    }
}
?>
