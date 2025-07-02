<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Model\ProdutoModel;
    use Aliexpresso\Model\CupomModel;

    class CarrinhoController {

        private $produtoModel;

        public function __construct() {
            $this->produtoModel = new ProdutoModel();
            $this->cupomModel = new CupomModel();
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
            $cartSession = $_SESSION['carrinho'] ?? [];
            $cartItems = [];
            $subtotal = 0;
            $discount = 0;

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
            
            if (isset($_SESSION['applied_coupon'])) {
                $coupon = $_SESSION['applied_coupon'];
                if ($coupon['tipo'] === 'fixo') {
                    $discount = $coupon['valor_desconto'];
                } elseif ($coupon['tipo'] === 'percentual') {
                    $discount = ($subtotal * $coupon['valor_desconto']) / 100;
                }
            }

            // [CORREÇÃO] Garante que o total reflete o desconto.
            $total = $subtotal - $discount;

            require_once __DIR__ . '/../view/carrinho/index.php';
        }

        public function applyCoupon() {
            // Converte o código do cupom para maiúsculas antes de procurar
            $couponCode = strtoupper($_POST['coupon_code'] ?? '');

            if (empty($couponCode)) {
                // Se o campo estiver vazio, remove qualquer cupom existente
                unset($_SESSION['applied_coupon']);
                $_SESSION['coupon_message'] = ['text' => 'Cupom removido.', 'type' => 'info'];
            } else {
                $coupon = $this->cupomModel->findByCode($couponCode);
        
                if ($coupon) {
                    // Cupom válido, guarda na sessão
                    $_SESSION['applied_coupon'] = $coupon;
                    $_SESSION['coupon_message'] = ['text' => 'Cupom aplicado com sucesso!', 'type' => 'success'];
                } else {
                    // Cupom inválido, remove qualquer um que estivesse antes
                    unset($_SESSION['applied_coupon']);
                    $_SESSION['coupon_message'] = ['text' => 'Cupom inválido ou expirado.', 'type' => 'error'];
                }
            }

            header('Location: index.php?page=carrinho');
            exit();
        }

        public function removeCoupon() {
            unset($_SESSION['applied_coupon']);
            $_SESSION['coupon_message'] = ['text' => 'Cupom removido com sucesso.', 'type' => 'info'];
            header('Location: index.php?page=carrinho');
            exit();
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
                unset($_SESSION['carrinho'][$productId]);
            }
            // [NOVO] Se o carrinho ficar vazio, remove também o cupom.
            if (empty($_SESSION['carrinho'])) {
                unset($_SESSION['applied_coupon']);
            }
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