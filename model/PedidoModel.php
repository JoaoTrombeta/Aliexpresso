<?php
namespace Aliexpresso\Model;

use PDO;

require_once __DIR__ . '/Database.php';

class PedidoModel {
    
    private $pdo; // Esta propriedade irá guardar a CONEXÃO PDO

    public function __construct() {
        $this->pdo = \Database::getInstance()->getConnection();
    }

    public function findCartByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = :userId AND status = 'carrinho'");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function createCartForUser($userId) {
        $stmt = $this->pdo->prepare("INSERT INTO pedidos (id_usuario, data_pedido, status) VALUES (:userId, NOW(), 'carrinho')");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }
    
    public function updateTotal($pedidoId, $total) {
        $stmt = $this->pdo->prepare("UPDATE pedidos SET valor_total = :total WHERE id_pedido = :pedidoId");
        $stmt->bindValue(':total', $total);
        $stmt->bindValue(':pedidoId', $pedidoId);
        return $stmt->execute();
    }

    public function findOrdersByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = :userId AND status != 'carrinho' ORDER BY data_pedido DESC");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function finalizeCart($pedidoId, $total) {
        $stmt = $this->pdo->prepare(
            "UPDATE pedidos 
             SET status = 'concluido', data_pedido = NOW(), valor_total = :total 
             WHERE id_pedido = :pedidoId"
        );
        $stmt->bindValue(':total', $total);
        $stmt->bindValue(':pedidoId', $pedidoId);
        return $stmt->execute();
    }

    public function finalizeCartWithDiscount($pedidoId, $total, $desconto, $valorFinal) {
    $stmt = $this->pdo->prepare(
        "UPDATE pedidos 
         SET status = 'concluido',
             data_pedido = NOW(),
             valor_total = :total,
             desconto = :desconto,
             valor_final = :valorFinal
         WHERE id_pedido = :pedidoId"
    );
    $stmt->bindValue(':total', $total);
    $stmt->bindValue(':desconto', $desconto);
    $stmt->bindValue(':valorFinal', $valorFinal);
    $stmt->bindValue(':pedidoId', $pedidoId);
    return $stmt->execute();
}

    public function getSalesStats() {
        // Agora usamos valor_final em vez de valor_total
        $stmt = $this->pdo->prepare(
            "SELECT 
                SUM(valor_final) as faturamento_total, 
                COUNT(id_pedido) as total_vendas 
            FROM pedidos 
            WHERE status != 'carrinho'"
        );
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getRecentOrders($limit = 5) {
        // Esta query busca os pedidos e junta (JOIN) com a tabela de usuários para pegar o nome
        $stmt = $this->pdo->prepare(
            "SELECT p.id_pedido, u.nome as nome_cliente, p.data_pedido, p.valor_total
             FROM pedidos p
             JOIN usuarios u ON p.id_usuario = u.id_usuario
             WHERE p.status != 'carrinho'
             ORDER BY p.data_pedido DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countCompletedOrdersByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(id_pedido) as total FROM pedidos WHERE id_usuario = :userId AND status != 'carrinho'");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }
}