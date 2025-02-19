<?php
// Iniciar sessão para uso do carrinho
session_start();

// Verificar se os controladores foram carregados corretamente
require_once 'controller/ProdutoController.php';
require_once 'controller/CarrinhoController.php';

// Definir os controladores e ações padrão
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'produto';
$action = isset($_GET['action']) ? $_GET['action'] : 'listarProdutos';

// Criar as instâncias dos controladores
$produtoController = new ProdutoController();
$carrinhoController = new CarrinhoController();

// Lógica de roteamento
if ($controller === 'produto') {
    if ($action === 'listarProdutos') {
        $produtoController->listarProdutos();
    } elseif ($action === 'detalhesProduto') {
        // Obter o id do produto da URL
        $id_produto = isset($_GET['id_produto']) ? $_GET['id_produto'] : null;
        $produtoController->detalhesProduto($id_produto);
    }
} elseif ($controller === 'carrinho') {
    if ($action === 'adicionarCarrinho') {
        // Obter o id do produto da URL
        $id_produto = isset($_GET['id_produto']) ? $_GET['id_produto'] : null;
        $carrinhoController->adicionarCarrinho($id_produto);
    } elseif ($action === 'visualizarCarrinho') {
        $carrinhoController->visualizarCarrinho();
    }
} else {
    echo "Controlador não encontrado!";
}
?>
