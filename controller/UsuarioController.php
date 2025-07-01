<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Model\UsuarioModel;

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
                header('Location: index.php?page=usuarios&action=login&sucesso=1');
            } else {
                header('Location: index.php?page=usuarios&action=register&erro=1');
            }
            exit();
        }

        public function authenticate() {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $userModel = new UsuarioModel();
            $usuario = $userModel->findByEmail($email);

            // A verificação agora é com o banco de dados e senha criptografada
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Sucesso! Armazena o array completo do usuário.
                $_SESSION['usuario'] = $usuario;
                header('Location: index.php?page=home');
                exit();
            } else {
                // Falha no login
                header('Location: index.php?page=usuarios&action=login&erro=1');
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