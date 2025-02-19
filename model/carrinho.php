
<?php

class Carrinho {
    private $produtos = [];

    public function adicionarProduto($produto) {
        $this->produtos[] = $produto;
    }

    public function obterProdutos() {
        return $this->produtos;
    }

    public function calcularTotal() {
        $total = 0;
        foreach ($this->produtos as $produto) {
            $total += $produto->preco;
        }
        return $total;
    }
}
?>
