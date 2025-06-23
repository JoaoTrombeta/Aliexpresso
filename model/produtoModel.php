<?php
    namespace Aliexpresso\Model;

    require_once __DIR__ . './Database.php';

    class ProdutoModel {
        private $pdo;

        public function __construct() {
            $this->pdo = \Database::getInstance()->getConnection();
        }

        /**
         * [NOVO] Busca apenas os produtos visíveis para os clientes.
         */
        public function getAllVisible() {
            return $this->pdo->query("SELECT * FROM produtos WHERE status = 'a venda' ORDER BY nome ASC")->fetchAll();
        }

        // --- Métodos para o Admin (permanecem os mesmos) ---
        public function getAll() {
            return $this->pdo->query("SELECT * FROM produtos ORDER BY id_produto ASC")->fetchAll();
        }
        
        public function getById(int $id) {
            $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        }

        public function create(array $data): bool {
            $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade_estoque, categoria, imagem, status, id_vendedor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['nome'], $data['descricao'], $data['preco'], $data['quantidade_estoque'],
                $data['categoria'], $data['imagem'], $data['status'], null
            ]);
        }

        public function update(int $id, array $data): bool {
            $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, quantidade_estoque = ?, categoria = ?, imagem = ?, status = ? WHERE id_produto = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['nome'], $data['descricao'], $data['preco'], $data['quantidade_estoque'],
                $data['categoria'], $data['imagem'], $data['status'], $id
            ]);
        }

        public function delete(int $id): bool {
            $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id_produto = ?");
            return $stmt->execute([$id]);
        }
    }
?>