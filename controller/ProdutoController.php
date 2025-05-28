<?php
require_once './model/Produto.php';
require_once './model/ProdutoFactory.php';

class ProdutoController {
    public function index() {
        // Simulando produtos (normalmente viriam do banco)
        $produtos = [
            ProdutoFactory::criarProduto([
                'id' => 1,
                'nome' => 'Café Gourmet',
                'descricao' => 'Café especial torrado.',
                'preco' => 29.90,
                'imagem' => 'cafe1.jpg'
            ]),
            ProdutoFactory::criarProduto([
                'id' => 2,
                'nome' => 'Energético Focus',
                'descricao' => 'Bebida energética premium.',
                'preco' => 9.90,
                'imagem' => 'energetico.jpg'
            ]),
            ProdutoFactory::criarProduto([
                'id' => 3,
                'nome' => 'Chocolate com Cafeína',
                'descricao' => 'C. amargo com cafeína.',
                'preco' => 14.90,
                'imagem' => 'chocolate.jpg'
            ]),
        ];

        include './view/produtos/index.php';
    }
}
?>
