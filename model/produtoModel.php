<?php
namespace Aliexpresso\Model;

// Inclui todos os arquivos necessários
require_once '/Database.php';
require_once '/ProdutoFactory.php';
require_once '/ProdutoInterface.php';
require_once '/produtos/Cafe.php';
require_once '/produtos/Grao.php';
require_once '/produtos/Energetico.php';

use PDO;

class ProdutoModel {
    private $conn;

    public function __construct() {
        // Usa a sua classe Database Singleton
        $this->conn = \Database::getInstance()->getConnection();
    }

    /**
     * Busca todos os produtos ativos no banco e os retorna como um array de objetos.
     * @return ProdutoInterface[]
     */
    public function buscarTodosAtivos(): array {
        $produtos = [];
        // SQL atualizado para pegar apenas produtos com status 'ativo'
        $sql = "SELECT * FROM produtos WHERE status = 'ativo' ORDER BY nome ASC";

        try {
            $stmt = $this->conn->query($sql);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $dados) {
                // A fábrica cria o objeto correto para cada produto
                $produtos[] = ProdutoFactory::criar($dados);
            }

        } catch (\PDOException $e) {
            error_log("Erro ao buscar produtos: " . $e->getMessage());
            // Em produção, evite usar die(). Retorne um array vazio ou lance uma exceção.
            return [];
        }

        return $produtos;
    }
}
