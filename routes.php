<?php
    require_once 'controller/LoginController.php';
    require_once 'controller/ClienteController.php';
    require_once 'controller/ProdutoController.php';
    require_once 'controller/CarrinhoController.php';
    require_once 'controller/AdminController.php';

    $controller = isset($_GET['controller']) ? $_GET['controller'] : 'site';
    $action = isset($_GET['action']) ? $_GET['action'] : 'home';

    switch ($controller) {
        case 'site':
            require_once 'controller/SiteController.php';
            $controller = new SiteController();
        
            switch ($action) {
                case 'home':
                    $controller->home();
                    break;
            }
            break;
        
        case 'login':
            $loginController = new LoginController();
            switch ($action) {
                case 'index':
                    $loginController->index();
                    break;
                case 'autenticar':
                    $loginController->autenticar();
                    break;
                case 'sair':
                    $loginController->sair();
                    break;
            }
            break;
            
        case 'cliente':
            $clienteController = new ClienteController();
            switch ($action) {
                case 'cadastro':
                    $clienteController->cadastro();
                    break;
                case 'cadastrar':
                    $clienteController->cadastrar();
                    break;
                case 'perfil':
                    $clienteController->perfil();
                    break;
            }
            break;
            
        case 'admin':
            $adminController = new AdminController();
            switch ($action) {
                case 'novoCliente':
                    $adminController->novoCliente();
                    break;
                case 'cadastrarCliente':
                    $adminController->cadastrarCliente();
                    break;
            }
            break;

        case 'funcionario':
            require_once 'controller/FuncionarioController.php';
            $funcionarioController = new FuncionarioController();
            switch ($action) {
                case 'listar':
                    $funcionarioController->listar();
                    break;
                case 'novo':
                    $funcionarioController->novo();
                    break;
                case 'cadastrar':
                    $funcionarioController->cadastrar();
                    break;
            }
            break;
            
        case 'produto':
            require_once 'controller/ProdutoController.php';
            $produtoController = new ProdutoController();
            switch ($action) {
                case 'listar':
                    $produtoController->listar();
                    break;
                case 'novo':
                    $produtoController->novo();
                    break;
                case 'cadastrar':
                    $produtoController->cadastrar();
                    break;
            }
            break;

        case 'pedido':
            require_once 'controller/PedidoController.php';
            $pedidoController = new PedidoController();
            switch ($action) {
                case 'historico':
                    $pedidoController->historico();
                    break;
                case 'detalhes':
                    $pedidoController->detalhes();
                    break;
            }
            break;                   
            
        case 'carrinho':
            require_once 'controller/CarrinhoController.php';
            $carrinhoController = new CarrinhoController();
            switch ($action) {
                case 'adicionar':
                    $carrinhoController->adicionar();
                    break;
                case 'visualizar':
                    $carrinhoController->visualizar();
                    break;
                case 'remover':
                    $carrinhoController->remover();
                    break;
                case 'finalizar':
                    $carrinhoController->finalizar();
                    break;                    
            }
            break;
            
            
        default:
            include "view/home.php";
            break;
    }
?>