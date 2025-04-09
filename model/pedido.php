<?php
    require_once 'database/conexao.php';

    class Pedido {
        public static function listarPorCliente($id_cliente) {
            $pdo = Conexao::conectar();
            $sql = "SELECT * FROM pedidos WHERE id_cliente = ? ORDER BY data_pedido DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_cliente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function listarItens($id_pedido) {
            $pdo = Conexao::conectar();
            $sql = "SELECT p.nome, ip.quantidade, ip.preco_unitario FROM itens_pedido ip 
                    JOIN produtos p ON ip.id_produto = p.id
                    WHERE ip.id_pedido = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_pedido]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>