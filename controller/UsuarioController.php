<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Model\UsuarioModel;
    use Aliexpresso\Model\PedidoModel;
    use Aliexpresso\Model\ItemPedidoModel;

    class UsuarioController {

        public function login() {
            require_once __DIR__ . '/../view/usuarios/login.php';
        }

        public function register() {
            require_once __DIR__ . '/../view/usuarios/register.php';
        }

        public function store() {
            $userModel = new UsuarioModel();
            if ($userModel->create($_POST)) {
                header('Location: index.php?page=usuario&action=login&sucesso=1');
            } else {
                header('Location: index.php?page=usuario&action=register&erro=1');
            }
            exit();
        }

        public function authenticate() {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $userModel = new UsuarioModel();
            $usuario = $userModel->findByEmail($email);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Sucesso! Armazena o array completo do usuário.
                $_SESSION['usuario'] = $usuario;
                
                // ===== INÍCIO DA LÓGICA DE SINCRONIZAÇÃO DO CARRINHO =====
                
                $pedidoModel = new PedidoModel();
                $itemPedidoModel = new ItemPedidoModel();
                
                // Pega o ID do usuário que acabou de logar
                $userId = $_SESSION['usuario']['id_usuario'];
                
                // Procura por um carrinho salvo no banco para este usuário
                $carrinhoDB = $pedidoModel->findCartByUserId($userId);
                
                // Inicializa/limpa o carrinho na sessão para receber os dados do banco
                $_SESSION['carrinho'] = ['produtos' => [], 'total' => 0]; 

                if ($carrinhoDB) {
                    // Se encontrou um carrinho no banco, carrega seus itens
                    $itensDB = $itemPedidoModel->findItemsByOrderId($carrinhoDB->id_pedido);
                    
                    foreach ($itensDB as $item) {
                        // Recria o array do carrinho na sessão com os dados do banco
                        $_SESSION['carrinho']['produtos'][$item->id_produto] = [
                            'id' => $item->id_produto,
                            'quantidade' => $item->quantidade,
                            'preco' => $item->preco_unitario
                            // Você pode adicionar mais detalhes do produto aqui se precisar
                        ];
                    }
                    // Atualiza o valor total do carrinho na sessão
                    $_SESSION['carrinho']['total'] = $carrinhoDB->valor_total;
                }
                
                // ===== FIM DA LÓGICA DE SINCRONIZAÇÃO =====

                header('Location: index.php?page=home');
                exit();
            } else {
                // Falha no login
                header('Location: index.php?page=usuario&action=login&erro=1');
                exit();
            }
        }

        public function logout() {
            session_unset();
            session_destroy();
            header('Location: index.php');
            exit();
        }
    }
?>