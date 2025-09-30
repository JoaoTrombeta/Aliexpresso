<?php
namespace Aliexpresso\Model;

use PDO;

require_once __DIR__ . '/Database.php';

class PedidoModel {
    
    private $db; // Esta propriedade irá guardar a CONEXÃO PDO

    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }

    public function findCartByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id_usuario = :userId AND status = 'carrinho'");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function createCartForUser($userId) {
        $stmt = $this->db->prepare("INSERT INTO pedidos (id_usuario, data_pedido, status) VALUES (:userId, NOW(), 'carrinho')");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    
    public function updateTotal($pedidoId, $total) {
        $stmt = $this->db->prepare("UPDATE pedidos SET valor_total = :total WHERE id_pedido = :pedidoId");
        $stmt->bindValue(':total', $total);
        $stmt->bindValue(':pedidoId', $pedidoId);
        return $stmt->execute();
    }

    public function findOrdersByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id_usuario = :userId AND status != 'carrinho' ORDER BY data_pedido DESC");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}