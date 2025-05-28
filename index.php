<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<?php

$page = $_GET['page'] ?? 'home';

switch($page) {
    case 'produtos':
        require_once './controller/ProdutoController.php';
        $controller = new ProdutoController();
        $controller->index();
        break;

    case 'home':
    default:
        require_once './view/home/index.php';
        break;
}
?>
