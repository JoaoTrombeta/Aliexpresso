<?php
namespace Aliexpresso\Controller;

use Aliexpresso\Model\ProdutoModel;
use Aliexpresso\Model\AvaliacaoModel;

class ProdutoController {

    public function index() {
        $produtoModel = new ProdutoModel();
        $produtos = $produtoModel->getAllVisible();
        require_once __DIR__ . '/../view/produtos/index.php';
    }

    // [NOVO] Exibe a página individual do produto
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

        // Busca as avaliações deste produto
        $avaliacaoModel = new AvaliacaoModel();
        $avaliacoes = $avaliacaoModel->getByProduct($id);

        require_once __DIR__ . '/../view/produtos/detalhes.php';
    }

    // [NOVO] Salva a avaliação enviada pelo formulário
    public function avaliar() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Verifica login
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?page=usuario&action=login');
            exit;
        }

        $idProduto = (int)$_POST['id_produto'];
        $nota = (int)$_POST['nota'];
        $comentario = trim($_POST['comentario']);
        $idUsuario = $_SESSION['usuario']['id_usuario'];
        
        $imagemNome = null;

        // Lógica de Upload de Imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            // Gera nome único: avaliacao_IDUSER_IDPROD_TIMESTAMP.jpg
            $novoNome = "avaliacao_{$idUsuario}_{$idProduto}_" . time() . "." . $extensao;
            $destino = __DIR__ . '/../assets/img/avaliacoes/';

            // Cria pasta se não existir
            if (!is_dir($destino)) {
                mkdir($destino, 0777, true);
            }

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino . $novoNome)) {
                $imagemNome = $novoNome;
            }
        }

        $avaliacaoModel = new AvaliacaoModel();
        $avaliacaoModel->create($idProduto, $idUsuario, $nota, $comentario, $imagemNome);

        // Volta para a página do produto
        header("Location: index.php?page=produto&action=detalhes&id=$idProduto");
        exit;
    }
}
?>