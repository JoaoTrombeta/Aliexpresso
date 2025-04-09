<?php
    require_once 'database/conexao.php';

    class Produto {
        public static function listar() {
            $pdo = Conexao::conectar();
            $sql = "SELECT * FROM produtos";
            return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function cadastrar($nome, $descricao, $preco, $imagem) {
            $pdo = Conexao::conectar();
            $sql = "INSERT INTO produtos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$nome, $descricao, $preco, $imagem]);
        }

        public static function buscarPorId($id) {
            $pdo = Conexao::conectar();
            $sql = "SELECT * FROM produtos WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
    }
?>