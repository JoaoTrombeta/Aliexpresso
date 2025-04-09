<?php
    require_once 'model/usuario.php';

    class ClienteController {
        // Exibe a view de cadastro público
        public function cadastro() {
            include 'view/cliente/cadastro.php';
        }

        // Processa o cadastro público
        public function cadastrar() {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

            // Aqui, o método 'cadastrar' no model insere o usuário com tipo "cliente"
            $resultado = Usuario::cadastrar($nome, $email, $senha, 'cliente');

            if ($resultado) {
                echo "Cadastro realizado com sucesso. <a href='?controller=login&action=index'>Clique aqui para fazer login</a>";
            } else {
                echo "Erro ao cadastrar.";
            }
        }

        public function perfil() {
            if (!isset($_SESSION['cliente'])) {
                header("Location: index.php?controller=cliente&action=login");
                exit;
            }

            $cliente = $_SESSION['cliente']; // Dados do cliente logado
            include 'view/cliente/perfil.php';
        }

        public function editarPerfil() {
            include 'view/cliente/editarPerfil.php';
        }
        
        public function salvarPerfil() {
            require_once 'model/cliente.php';
        
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
        
            Cliente::atualizar($id, $nome, $email, $senha);
        
            // Atualiza a sessão do cliente
            $_SESSION['cliente']['nome'] = $nome;
            $_SESSION['cliente']['email'] = $email;
        
            header('Location: index.php?rota=perfil');
        }
    }
?>