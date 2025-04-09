<?php
    require_once 'database/conexao.php';

    class Funcionario {
        public static function cadastrar($nome, $email, $senha) {
            $con = Conexao::conectar();
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'funcionario')");
            return $stmt->execute([$nome, $email, $senhaHash]);
        }
    }
?>