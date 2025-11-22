<?php
namespace Aliexpresso\Model;

require_once __DIR__ . '/Database.php';

class AvaliacaoModel {
    private $pdo;

    public function __construct() {
        $this->pdo = \Database::getInstance()->getConnection();
    }

    public function create($idProduto, $idUsuario, $nota, $comentario, $imagem, $dadosIA = null) {
        $sql = "INSERT INTO avaliacoes (
                    id_produto, id_usuario, nota, comentario, imagem, data_avaliacao,
                    sentimento_ia, score_ia
                ) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
        
        $sentimento = $dadosIA['sentimento'] ?? 'Neutro';
        $score = $dadosIA['score'] ?? 0;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $idProduto, 
            $idUsuario, 
            $nota, 
            $comentario, 
            $imagem,
            $sentimento,
            $score
        ]);
    }

    public function getByProduct($idProduto) {
        // [CORREÇÃO] Busca u.imagem_perfil (o caminho completo salvo no UsuarioController)
        $sql = "SELECT a.*, u.nome as nome_usuario, u.imagem_perfil as foto_usuario
                FROM avaliacoes a
                JOIN usuarios u ON a.id_usuario = u.id_usuario
                WHERE a.id_produto = ?
                ORDER BY a.data_avaliacao DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idProduto]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}