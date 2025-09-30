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

    /**
     * [NOVO] Busca as estatísticas gerais de vendas.
     * Considera como "venda" qualquer pedido com status 'pago', 'enviado' ou 'entregue'.
     */
    public function getSalesStatistics(): array {
        $stmt = $this->pdo->query("
            SELECT
                COUNT(id_pedido) as total_vendas,
                SUM(valor_total) as faturamento_total
            FROM pedidos
            WHERE status IN ('pago', 'enviado', 'entregue')
        ");
        $stats = $stmt->fetch();
        
        // Calcula a média, evitando divisão por zero
        $stats['ticket_medio'] = ($stats['total_vendas'] > 0) 
            ? $stats['faturamento_total'] / $stats['total_vendas'] 
            : 0;

        return $stats;
    }

    /**
     * [NOVO] Busca os produtos mais vendidos.
     *
     * @param int $limit O número de produtos a retornar.
     * @return array Lista dos produtos mais vendidos.
     */
    public function getBestSellingProducts(int $limit = 5): array {
        $stmt = $this->pdo->query("
            SELECT
                p.nome,
                SUM(ip.quantidade) as total_vendido
            FROM itens_pedido ip
            JOIN produtos p ON ip.id_produto = p.id_produto
            JOIN pedidos o ON ip.id_pedido = o.id_pedido
            WHERE o.status IN ('pago', 'enviado', 'entregue')
            GROUP BY p.id_produto, p.nome
            ORDER BY total_vendido DESC
            LIMIT $limit
        ");
        return $stmt->fetchAll();
    }

    /**
     * [NOVO] Busca os pedidos mais recentes.
     *
     * @param int $limit O número de pedidos a retornar.
     * @return array Lista dos pedidos mais recentes.
     */
    public function getRecentOrders(int $limit = 5): array {
        $stmt = $this->pdo->query("
            SELECT
                o.id_pedido,
                u.nome as nome_cliente,
                o.data_pedido,
                o.valor_total,
                o.status
            FROM pedidos o
            JOIN usuarios u ON o.id_usuario = u.id_usuario
            WHERE o.status IN ('pago', 'enviado', 'entregue')
            ORDER BY o.data_pedido DESC
            LIMIT $limit
        ");
        return $stmt->fetchAll();
    }
}