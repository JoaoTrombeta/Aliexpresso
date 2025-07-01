<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Model\ProdutoModel;

    class CarrinhoController {

        private $produtoModel;

        public function __construct() {
            $this->produtoModel = new ProdutoModel();
            if (!isset($_SESSION['carrinho'])) {
                $_SESSION['carrinho'] = [];
            }
        }

        /**
         * [NOVO] Adiciona um item ao carrinho via AJAX e retorna uma resposta JSON.
         */
        public function ajax_add() {
            // Define o tipo de conteúdo da resposta como JSON
            header('Content-Type: application/json');

            $productId = (int)($_GET['id'] ?? 0);
            // Prepara uma resposta padrão de falha
            $response = ['success' => false, 'cartCount' => count($_SESSION['carrinho'] ?? [])];

            if ($productId > 0) {
                // Lógica para adicionar o produto à sessão
                if (isset($_SESSION['carrinho'][$productId])) {
                    $_SESSION['carrinho'][$productId]++;
                } else {
                    $_SESSION['carrinho'][$productId] = 1;
                }
                // Atualiza a resposta para sucesso e envia a nova contagem de itens
                $response['success'] = true;
                $response['cartCount'] = count($_SESSION['carrinho']);
            }

            // Envia a resposta em formato JSON e encerra o script
            echo json_encode($response);
            exit();
        }

        /**
         * Exibe a página do carrinho.
         */
        public function index() {
            $cartSession = $_SESSION['carrinho'];
            $cartItems = [];
            $subtotal = 0;

            if (!empty($cartSession)) {
                $productIds = array_keys($cartSession);
                $productsFromDB = $this->produtoModel->findByIds($productIds);

                foreach ($productsFromDB as $product) {
                    $quantity = $cartSession[$product['id_produto']];
                    $cartItems[] = [
                        'id' => $product['id_produto'],
                        'nome' => $product['nome'],
                        'descricao' => $product['descricao'],
                        'preco' => $product['preco'],
                        'imagem' => $product['imagem'],
                        'quantidade' => $quantity,
                        'subtotal_item' => $product['preco'] * $quantity
                    ];
                    $subtotal += $product['preco'] * $quantity;
                }
            }
            
            require_once __DIR__ . '/../view/carrinho/index.php';
        }

        /**
         * Adiciona um item ao carrinho.
         */
        public function add() {
            $productId = (int)($_GET['id'] ?? 0);
            if ($productId > 0) {
                if (isset($_SESSION['carrinho'][$productId])) {
                    $_SESSION['carrinho'][$productId]++;
                } else {
                    $_SESSION['carrinho'][$productId] = 1;
                }
            }
            header('Location: index.php?page=carrinho');
            exit();
        }

        /**
         * Remove um item do carrinho.
         */
        public function remove() {
            $productId = (int)($_GET['id'] ?? 0);

            if (isset($_SESSION['carrinho'][$productId])) {
                // Remove o item da sessão
                unset($_SESSION['carrinho'][$productId]);
            }

            // [REFORÇO] Força a gravação dos dados da sessão antes de redirecionar
            session_write_close();

            // Redireciona de volta para a página do carrinho
            header('Location: index.php?page=carrinho');
            exit();
        }

        /**
         * [ATUALIZADO] Atualiza a quantidade de um item via POST.
         */
        public function update() {
            $productId = (int)($_POST['id'] ?? 0);
            $action = $_POST['action'] ?? ''; // 'increase' ou 'decrease'

            if (isset($_SESSION['carrinho'][$productId]) && in_array($action, ['increase', 'decrease'])) {
                if ($action === 'increase') {
                    $_SESSION['carrinho'][$productId]++;
                } elseif ($action === 'decrease') {
                    // Só diminui se a quantidade for maior que 1
                    if ($_SESSION['carrinho'][$productId] > 1) {
                        $_SESSION['carrinho'][$productId]--;
                    }
                }
            }
            header('Location: index.php?page=carrinho');
            exit();
        }
    }
?>