<?php
    require_once 'database/conexao.php';

    class Usuario {
        public static function buscarPorEmail($email) {
            $pdo = Conexao::conectar();
            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function cadastrar($nome, $email, $senha, $tipo = 'cliente') {
            $pdo = Conexao::conectar();
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$nome, $email, $senha, $tipo]);
        }

        public static function listarPorTipo($tipo) {
            $pdo = Conexao::conectar();
            $sql = "SELECT * FROM usuarios WHERE tipo = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$tipo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }
?>