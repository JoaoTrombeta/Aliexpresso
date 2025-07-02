<?php
    namespace Aliexpresso\Model;

    require_once __DIR__ . '/Database.php';

    class CupomModel 
    {
        private $pdo;

        public function __construct() {
            $this->pdo = \Database::getInstance()->getConnection();
        }

        public function findByCode(string $code) {
            // A consulta verifica se o código existe, se está ativo e se a data de validade não passou.
            $sql = "SELECT * FROM cupons WHERE codigo = ? AND status = 'ativo' AND (data_validade IS NULL OR data_validade >= CURDATE())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$code]);
            return $stmt->fetch();
        }

        public function getAll() {
            return $this->pdo->query("SELECT * FROM cupons ORDER BY id_cupom ASC")->fetchAll();
        }
        
        public function getById(int $id) {
            $stmt = $this->pdo->prepare("SELECT * FROM cupons WHERE id_cupom = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        }

        public function create(array $data): bool {
            $sql = "INSERT INTO cupons (codigo, descricao, valor_desconto, tipo, data_validade, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            
            // Trata a data de validade, permitindo que seja nula
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
    }
?>