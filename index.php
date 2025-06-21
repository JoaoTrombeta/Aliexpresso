<?php
    session_start();

    // Habilita a exibição de erros
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Carrega o autoloader para não precisar de 'require_once'
    require_once __DIR__ . '/autoloader.php';
    // Carrega o pageController com a função renderHeader
    require_once __DIR__ . '/controller/pageController.php';

    // Define a 'page' e a 'action' a partir da URL
    $page = $_GET['page'] ?? 'home';
    $action = $_GET['action'] ?? 'index';

    // Define o nome completo da classe do controller
    // Ex: 'produtos' -> 'Aliexpresso\Controller\ProdutoController'
    $controllerName = 'Aliexpresso\\Controller\\' . ucfirst($page) . 'Controller';

    // Roteamento
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            http_response_code(404);
            echo "Erro 404: Ação '{$action}' não encontrada.";
        }
    } else {
        // Lógica para a home ou erro 404 geral
        if ($page === 'home') {
            require_once __DIR__ . '/view/home/index.php'; // Uma view simples para a home
        } else {
            http_response_code(404);
            echo "Erro 404: Página '{$page}' não encontrada.";
        }
    }
?>