<?php
    // Inicia a sessão ou resume a sessão existente.
    session_start();

    // Habilita a exibição de erros
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Carrega o autoloader para as classes
    require_once __DIR__ . '/autoloader.php';
    // Carrega o arquivo com as funções de layout globais
    require_once __DIR__ . '/controller/pageController.php';

    // Define a 'page' e a 'action' a partir da URL
    $page = $_GET['page'] ?? 'home';
    $action = $_GET['action'] ?? 'index';

    // Define o nome completo da classe do controller
    $controllerName = 'Aliexpresso\\Controller\\' . ucfirst($page) . 'Controller';

    if ($page === 'home') {
        require_once __DIR__ . '/view/home/index.php';
    } elseif (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            http_response_code(404);
            echo "Erro 404: Ação '{$action}' não encontrada no controller.";
        }
    } else {
        http_response_code(404);
        echo "Erro 404: Página '{$page}' não encontrada.";
    }
?>