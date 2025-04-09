<?php
    require_once 'model/usuario.php';

    class AuthController {
        public function login() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $senha = $_POST['senha'] ?? '';
        
                $usuario = Usuario::autenticar($email, $senha);
                if (!$usuario) {
                    $usuario = Usuario::autenticarCliente($email, $senha);
                }
        
                if ($usuario) {
                    $_SESSION['usuario'] = $usuario;
                    header("Location: routes.php?controller=produto&action=listar");
                    exit;
                } else {
                    $_SESSION['erro'] = "Email ou senha inválidos";
                    header("Location: view/login.php");
                    exit;
                }
            } else {
                require 'view/login.php';
            }
        }        

        public function logout() {
            session_destroy();
            header("Location: view/login.php");
            exit;
        }
    }
?>