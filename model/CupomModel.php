<?php
namespace Aliexpresso\Model;

require_once __DIR__ . '/Database.php';

use PDO;

class CupomModel 
{
    private $pdo;

    public function __construct() {
        // CORREÇÃO: Acessamos a classe Database do mesmo namespace, sem a barra '\'
        $this->pdo = \Database::getInstance()->getConnection();
    }

    public function findByCode(string $code) {
        // A consulta está ótima, apenas mantendo o padrão do resto do código.
        $sql = "SELECT * FROM cupons WHERE codigo = :code AND status = 'ativo' AND (data_validade IS NULL OR data_validade >= CURDATE())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':code', $code);
        $stmt->execute();
        // Usamos FETCH_ASSOC para garantir que o resultado seja um array, como o resto do sistema espera.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM cupons ORDER BY id_cupom ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cupons WHERE id_cupom = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO cupons (codigo, descricao, valor_desconto, tipo, data_validade, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        $data_validade = !empty($data['data_validade']) ? $data['data_validade'] : null;

        return $stmt->execute([
            $data['codigo'],
            $data['descricao'],
            $data['valor_desconto'],
            $data['tipo'],
            $data_validade,
            $data['status']
        ]);
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE cupons SET codigo = ?, descricao = ?, valor_desconto = ?, tipo = ?, data_validade = ?, status = ? WHERE id_cupom = ?";
        $stmt = $this->pdo->prepare($sql);

        $data_validade = !empty($data['data_validade']) ? $data['data_validade'] : null;

        return $stmt->execute([
            $data['codigo'],
            $data['descricao'],
            $data['valor_desconto'],
            $data['tipo'],
            $data_validade,
            $data['status'],
            $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM cupons WHERE id_cupom = ?");
        return $stmt->execute([$id]);
    }

    public function markAsUsed(int $idCupom, int $idUsuario, int $idPedido): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO cupons_usados (id_cupom, id_usuario, id_pedido, data_uso)
            VALUES (:id_cupom, :id_usuario, :id_pedido, NOW())
        ");
        return $stmt->execute([
            ':id_cupom' => $idCupom,
            ':id_usuario' => $idUsuario,
            ':id_pedido' => $idPedido
        ]);
    }

    public function deactivateCoupon(int $idCupom): bool {
        $stmt = $this->pdo->prepare("UPDATE cupons SET status = 'inativo' WHERE id_cupom = :id_cupom");
        return $stmt->execute([':id_cupom' => $idCupom]);
    }

}