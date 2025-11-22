<?php
namespace Aliexpresso\Model;

use PDO;

class ItemPedidoModel
{

    private $db; // A propriedade chama-se $db

    public function __construct()
    {
        $this->db = \Database::getInstance()->getConnection();
    }

    public function clearItemsByOrderId($pedidoId)
    {
        $stmt = $this->db->prepare("DELETE FROM itens_pedido WHERE id_pedido = :pedidoId");
        $stmt->bindValue(':pedidoId', $pedidoId);
        return $stmt->execute();
    }

    public function addItem($pedidoId, $produtoId, $quantidade, $preco)
    {
        $stmt = $this->db->prepare("INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (:pedidoId, :produtoId, :qtd, :preco)");
        $stmt->bindValue(':pedidoId', $pedidoId);
        $stmt->bindValue(':produtoId', $produtoId);
        $stmt->bindValue(':qtd', $quantidade);
        $stmt->bindValue(':preco', $preco);
        return $stmt->execute();
    }

    public function findItemsByOrderId($pedidoId)
    {
        $stmt = $this->db->prepare("SELECT * FROM itens_pedido WHERE id_pedido = :pedidoId");
        $stmt->bindValue(':pedidoId', $pedidoId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function findItemsByOrderIdWithDetails($pedidoId)
    {
        $query = "SELECT ip.*, p.nome, p.imagem 
                  FROM itens_pedido ip 
                  JOIN produtos p ON ip.id_produto = p.id_produto 
                  WHERE ip.id_pedido = :pedidoId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':pedidoId', $pedidoId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // --- CORREÇÃO AQUI ---
    public function getItemsByOrderId($orderId)
    {
        $sql = "SELECT ip.*, p.nome, p.imagem 
                FROM itens_pedido ip
                JOIN produtos p ON ip.id_produto = p.id_produto
                WHERE ip.id_pedido = :orderId";

        // Antes estava $this->pdo (que não existe). Mudei para $this->db
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}