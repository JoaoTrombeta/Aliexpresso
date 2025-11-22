<?php
namespace Aliexpresso\Controller;

use Aliexpresso\Service\SentimentService;
use Aliexpresso\Model\Review;
use Aliexpresso\Helper\ReviewHelper;

class ReviewController {
    private $reviewModel;
    private $sentimentService;

    public function __construct(Review $reviewModel) {
        $this->reviewModel = $reviewModel;
        $this->sentimentService = new SentimentService();
    }

    public function index($productId) {
        // Busca reviews existentes
        $reviews = $this->reviewModel->getAllByProduct($productId);
        
        // Carrega a view (passando dados e o Helper class name se precisar)
        require __DIR__ . '/../../views/product_reviews.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $name = $_POST['name'];
            $comment = $_POST['comment'];

            // 1. Chama a IA para julgar o comentário
            $analysis = $this->sentimentService->analisarComentario($comment);

            // 2. Salva no banco com o resultado da IA
            $this->reviewModel->create($productId, $name, $comment, $analysis);

            // 3. Redireciona
            header("Location: /?id={$productId}&status=success");
            exit;
        }
    }
}