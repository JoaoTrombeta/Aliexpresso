<?php
namespace Aliexpresso\Model;

require_once __DIR__ . '/Database.php';

class AvaliacaoModel {
    private $pdo;

    public function __construct() {
        $this->pdo = \Database::getInstance()->getConnection();
    }

    // Cria uma nova avaliação
    public function create($idProduto, $idUsuario, $nota, $comentario, $imagem = null) {
        $sql = "INSERT INTO avaliacoes (id_produto, id_usuario, nota, comentario, imagem, data_avaliacao) 
                VALUES (:prod, :user, :nota, :coment, :img, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':prod' => $idProduto,
            ':user' => $idUsuario,
            ':nota' => $nota,
            ':coment' => $comentario,
            ':img' => $imagem
        ]);
    }

    // Busca todas as avaliações de um produto (com o nome do usuário)
    public function getByProduct($idProduto) {
        $sql = "SELECT a.*, u.nome as nome_usuario 
                FROM avaliacoes a
                JOIN usuarios u ON a.id_usuario = u.id_usuario
                WHERE a.id_produto = :prod
                ORDER BY a.data_avaliacao DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':prod' => $idProduto]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}