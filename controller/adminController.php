<?php
    require_once 'model/usuario.php';

    class AdminController {
        // Exibe a view para cadastro de cliente pelo admin
        public function novoCliente() {
            include 'view/admin/cadastroCliente.php';
        }
        
        public function cadastrarCliente() {
            // Apenas admins devem acessar esse método, verifique a sessão
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
                echo "Acesso negado";
                exit;
            }
            
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            
            $resultado = Usuario::cadastrar($nome, $email, $senha, 'cliente');
            
            if ($resultado) {
                echo "Cliente cadastrado com sucesso.";
            } else {
                echo "Erro ao cadastrar o cliente.";
            }
        }
    }
?>