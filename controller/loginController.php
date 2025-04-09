<?php
    require_once 'model/usuario.php';

    class LoginController {
        public function index() {
            include 'view/login.php';
        }

        public function autenticar() {
            if (isset($_POST['email']) && isset($_POST['senha'])) {
                $email = $_POST['email'];
                $senha = $_POST['senha'];

                $usuario = Usuario::buscarPorEmail($email);

                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    session_start();
                    $_SESSION['usuario'] = $usuario;
                    header('Location: ?rota=home');
                    exit;
                } else {
                    $erro = "E-mail ou senha inválidos!";
                    include 'view/login.php';
                }
            }
        }

        public function sair() {
            session_start();
            session_destroy();
            header('Location: ?controller=site&action=home');
            exit;
        }
    }
?>