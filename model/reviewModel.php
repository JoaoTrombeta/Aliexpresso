<?php
namespace Aliexpresso\Model;

use PDO;

class Review {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($productId, $userName, $comment, $sentimentData) {
        $sql = "INSERT INTO reviews (product_id, user_name, comment, sentiment, score, stars, created_at) 
                VALUES (:pid, :user, :comm, :sent, :score, :stars, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':pid'   => $productId,
            ':user'  => $userName,
            ':comm'  => $comment,
            ':sent'  => $sentimentData['sentiment'],
            ':score' => $sentimentData['score'],
            ':stars' => $sentimentData['stars']
        ]);
    }

    public function getAllByProduct($productId) {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE product_id = :pid ORDER BY created_at DESC");
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}