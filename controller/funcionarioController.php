<?php
    require_once 'model/usuario.php';

    class FuncionarioController {
        public function listar() {
            // Verifica se é admin
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
                echo "Acesso negado.";
                return;
            }

            $funcionarios = Usuario::listarPorTipo('funcionario');
            include 'view/funcionario/listar.php';
        }

        public function novo() {
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
                echo "Acesso negado.";
                return;
            }

            include 'view/funcionario/novo.php';
        }

        public function cadastrar() {
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
                echo "Acesso negado.";
                return;
            }

            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

            $resultado = Usuario::cadastrar($nome, $email, $senha, 'funcionario');

            if ($resultado) {
                echo "Funcionário cadastrado com sucesso. <a href='?controller=funcionario&action=listar'>Voltar</a>";
            } else {
                echo "Erro ao cadastrar funcionário.";
            }
        }
    }
?>