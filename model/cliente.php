<?php
    require_once 'database/conexao.php';

    class Cliente {
        public static function cadastrar($nome, $email, $senha) {
            $con = Conexao::conectar();
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'cliente')");
            return $stmt->execute([$nome, $email, $senhaHash]);
        }

        public static function atualizar($id, $nome, $email, $senha = null) {
            $con = Conexao::conectar();
        
            if (!empty($senha)) {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $sql = "UPDATE clientes SET nome = ?, email = ?, senha = ? WHERE id = ?";
                $stmt = $con->prepare($sql);
                $stmt->execute([$nome, $email, $senhaHash, $id]);
            } else {
                $sql = "UPDATE clientes SET nome = ?, email = ? WHERE id = ?";
                $stmt = $con->prepare($sql);
                $stmt->execute([$nome, $email, $id]);
            }
        }        
    }
?>