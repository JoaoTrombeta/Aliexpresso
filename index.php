<?php
    session_start();

    require_once __DIR__ . '/autoloader.php';
    require_once __DIR__ . '/controller/pageController.php';

    $page = $_GET['page'] ?? 'home';
    // [MUDANÇA] Renomeado para ser mais claro
    $controller_key = strtolower($_GET['page'] ?? 'home'); 

    if ($controller_key === 'home') {
        require_once __DIR__ . '/view/home/index.php';
    } else {
        $controllerName = 'Aliexpresso\\Controller\\' . ucfirst($controller_key) . 'Controller';

        if (class_exists($controllerName)) {
            $action = $_GET['action'] ?? 'index';
            $controller = new $controllerName();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                http_response_code(404);
                echo "Erro 404: Ação '{$action}' não encontrada.";
            }
        } else {
            http_response_code(404);
            echo "Erro 404: Página '{$controller_key}' não encontrada.";
        }
    }
?>