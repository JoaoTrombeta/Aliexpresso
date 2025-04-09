<?php
    require_once 'model/Produto.php';

    class ProdutoController {
        public function listar() {
            $produtos = Produto::listar();
            include 'view/produto/listar.php';
        }

        public function novo() {
            if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['tipo'], ['admin', 'funcionario'])) {
                echo "Acesso negado.";
                return;
            }

            include 'view/produto/novo.php';
        }

        public function cadastrar() {
            if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['tipo'], ['admin', 'funcionario'])) {
                echo "Acesso negado.";
                return;
            }

            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $imagem = $_FILES['imagem']['name'];

            if ($imagem) {
                $caminho = 'assets/img/' . basename($imagem);
                move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
            }

            Produto::cadastrar($nome, $descricao, $preco, $imagem);
            header('Location: ?controller=produto&action=listar');
        }
    }
?>