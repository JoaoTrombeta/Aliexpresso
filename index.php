<?php
    // index.php
    // Configurações de erro (Ideal para desenvolvimento)
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    // Inicia a sessão (Necessário para o Carrinho e Mensagens de Cupom)
    session_start();

    // Inclusões essenciais
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/autoloader.php';
    
    // Se tiver controllers manuais fora do autoloader, mantenha aqui:
    // require_once __DIR__ . '/controller/pageController.php'; 

    // Captura a página e ação da URL (Ex: index.php?page=cupom&action=gerarCupomPromocional)
    $page = $_GET['page'] ?? 'home';
    $controller_key = strtolower($page); 

    // Rota da Home
    if ($controller_key === 'home') {
        require_once __DIR__ . '/view/home/index.php';
    } else {
        // Monta o nome qualificado da classe (Namespace + Nome)
        // Ex: Aliexpresso\Controller\CupomController
        $controllerName = 'Aliexpresso\\Controller\\' . ucfirst($controller_key) . 'Controller';

        if (class_exists($controllerName)) {
            try {
                // Instancia o Controller
                $controller = new $controllerName();
                
                $action = $_GET['action'] ?? 'index';

                // Verifica se o método existe no Controller (Ex: gerarCupomPromocional)
                if (method_exists($controller, $action)) {
                    $controller->$action();
                } else {
                    // Erro 404 se a ação não existir
                    http_response_code(404);
                    die("Erro 404: A ação '{$action}' não existe no controller '{$controller_key}'.");
                }
            } catch (Throwable $e) {
                // [INTEGRAÇÃO IMPORTANTE]
                // Captura erros fatais (como falha no envio de e-mail ou conexão DB)
                // Se for uma requisição AJAX (espera JSON), retorna JSON
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Erro interno no servidor: ' . $e->getMessage()
                    ]);
                } else {
                    // Se for acesso normal, mostra erro na tela
                    echo "<div style='color: red; border: 1px solid red; padding: 15px; margin: 20px;'>";
                    echo "<strong>Erro Crítico:</strong> " . $e->getMessage();
                    echo "</div>";
                }
            }
        } else {
            // Erro 404 se o arquivo/classe do Controller não for encontrado
            http_response_code(404);
            echo "Erro 404: Página '{$controller_key}' não encontrada. <br>";
            echo "Tentou carregar: $controllerName"; 
        }
    }
?>