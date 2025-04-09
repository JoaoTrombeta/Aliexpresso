<?php
    require_once 'model/usuario.php';

    class LoginController {
        public function index() {
            include 'view/login/index.php';
        }

        public function autenticar() {
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            
            $usuario = Usuario::buscarPorEmail($email);
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario'] = $usuario;
                header("Location: index.php");
            } else {
                echo "Email ou senha inválidos.";
            }
        }
        
        public function sair() {
            session_destroy();
            header("Location: index.php?controller=login&action=index");
        }
    }
?>