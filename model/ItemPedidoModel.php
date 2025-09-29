<?php
namespace Aliexpresso\Model;

use PDO;

class ItemPedidoModel {

    private $db; // Esta propriedade irá guardar a CONEXÃO PDO

    public function __construct() {
        // CORREÇÃO: Pegamos a instância DA CAIXA e depois a CONEXÃO DE DENTRO DELA.
        $this->db = \Database::getInstance()->getConnection();
    }
    
    public function clearItemsByOrderId($pedidoId) {
        $stmt = $this->db->prepare("DELETE FROM itens_pedido WHERE id_pedido = :pedidoId");
        $stmt->bindValue(':pedidoId', $pedidoId);
        return $stmt->execute();
    }

    public function addItem($pedidoId, $produtoId, $quantidade, $preco) {
        $stmt = $this->db->prepare("INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (:pedidoId, :produtoId, :qtd, :preco)");
        $stmt->bindValue(':pedidoId', $pedidoId);
        $stmt->bindValue(':produtoId', $produtoId);
        $stmt->bindValue(':qtd', $quantidade);
        $stmt->bindValue(':preco', $preco);
        return $stmt->execute();
    }

    public function findItemsByOrderId($pedidoId) {
        $stmt = $this->db->prepare("SELECT * FROM itens_pedido WHERE id_pedido = :pedidoId");
        $stmt->bindValue(':pedidoId', $pedidoId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}