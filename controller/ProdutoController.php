<?php
namespace Aliexpresso\Controller;

use Aliexpresso\Model\ProdutoModel;
use Aliexpresso\Model\AvaliacaoModel;
use Aliexpresso\Service\SentimentService;

class ProdutoController {

    public function index() {
        $produtoModel = new ProdutoModel();
        $produtos = $produtoModel->getAllVisible();
        require_once __DIR__ . '/../view/produtos/index.php';
    }

    public function detalhes() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            header('Location: index.php?page=produto');
            exit;
        }

        $produtoModel = new ProdutoModel();
        $produto = $produtoModel->getById($id);

        if (!$produto) {
            header('Location: index.php?page=produto');
            exit;
        }

        // Busca avaliações
        $avaliacaoModel = new AvaliacaoModel();
        $avaliacoes = $avaliacaoModel->getByProduct($id);

        require_once __DIR__ . '/../view/produtos/detalhes.php';
    }

    public function avaliar() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?page=usuario&action=login');
            exit;
        }

        $idProduto = (int)$_POST['id_produto'];
        $nota = (int)$_POST['nota'];
        $comentario = trim($_POST['comentario']);
        $idUsuario = $_SESSION['usuario']['id_usuario'];
        
        $imagemNome = null;

        // Upload de imagem da avaliação
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $novoNome = "avaliacao_{$idUsuario}_{$idProduto}_" . time() . "." . $extensao;
            $destino = __DIR__ . '/../assets/img/avaliacoes/';

            if (!is_dir($destino)) mkdir($destino, 0777, true);
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino . $novoNome)) {
                $imagemNome = $novoNome;
            }
        }

        // CHAMA A IA
        $sentimentService = new SentimentService();
        $analiseIA = $sentimentService->analisar($comentario);
        
        $avaliacaoModel = new AvaliacaoModel();
        $avaliacaoModel->create($idProduto, $idUsuario, $nota, $comentario, $imagemNome, $analiseIA);

        header("Location: index.php?page=produto&action=detalhes&id=$idProduto&msg=avaliacao_sucesso");
        exit;
    }
}