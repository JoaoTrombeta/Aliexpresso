<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Model\ProdutoModel;
    use Aliexpresso\Model\CupomModel;
    use Aliexpresso\Model\PedidoModel;
    use Aliexpresso\Model\ItemPedidoModel;

    class CarrinhoController {

        private $produtoModel;
        private $cupomModel;
        private $pedidoModel;
        private $itemPedidoModel;

        public function __construct() {
            $this->produtoModel = new ProdutoModel();
            $this->cupomModel = new CupomModel();
            $this->pedidoModel = new PedidoModel();
            $this->itemPedidoModel = new ItemPedidoModel();
            
            if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho']) || !isset($_SESSION['carrinho']['produtos'])) {
                $_SESSION['carrinho'] = ['produtos' => [], 'total' => 0];
            }
        }

        private function sincronizarCarrinhoComBanco() {
            if (!isset($_SESSION['usuario']['id_usuario'])) { return; }
            $userId = $_SESSION['usuario']['id_usuario'];
            $carrinhoDB = $this->pedidoModel->findCartByUserId($userId);
            $pedidoId = $carrinhoDB ? $carrinhoDB->id_pedido : $this->pedidoModel->createCartForUser($userId);
            
            $this->itemPedidoModel->clearItemsByOrderId($pedidoId);
            
            $subtotal = 0;
            if (!empty($_SESSION['carrinho']['produtos'])) {
                foreach ($_SESSION['carrinho']['produtos'] as $produtoId => $item) {
                    $this->itemPedidoModel->addItem($pedidoId, $produtoId, $item['quantidade'], $item['preco']);
                    $subtotal += $item['preco'] * $item['quantidade'];
                }
            }
            $this->pedidoModel->updateTotal($pedidoId, $subtotal);
            $_SESSION['carrinho']['total'] = $subtotal;
        }
        
        public function ajax_add() {
            header('Content-Type: application/json');
            $productId = (int)($_GET['id'] ?? 0);
            $response = ['success' => false];

            if ($productId > 0) {
                $produto = $this->produtoModel->getById($productId);
                if ($produto) {
                    // ... lógica para adicionar na sessão ...
                    if (isset($_SESSION['carrinho']['produtos'][$productId])) {
                        $_SESSION['carrinho']['produtos'][$productId]['quantidade']++;
                    } else {
                        $_SESSION['carrinho']['produtos'][$productId] = [ 'id' => $productId, 'quantidade' => 1, 'preco' => $produto['preco'] ];
                    }
                    
                    $this->sincronizarCarrinhoComBanco();
                    
                    // Lógica para calcular a quantidade total
                    $quantities = array_column($_SESSION['carrinho']['produtos'], 'quantidade');
                    $totalItems = array_sum($quantities);

                    // Resposta com o total de itens
                    $response['success'] = true;
                    $response['totalItems'] = $totalItems; // <-- Esta linha é crucial
                }
            }
            echo json_encode($response);
            exit();
        }

        /**
         * [CORRIGIDO E COMPLETO] Exibe a página do carrinho.
         */
        public function index() {
            $cartSession = $_SESSION['carrinho']['produtos'] ?? [];
            $cartItems = [];
            $subtotal = 0;
            $discount = 0;

            if (!empty($cartSession)) {
                $productIds = array_keys($cartSession);
                $productsFromDB = $this->produtoModel->findByIds($productIds);

                foreach ($productsFromDB as $product) {
                    $quantity = $cartSession[$product['id_produto']]['quantidade'];
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

            $total = $subtotal - $discount;

            require_once __DIR__ . '/../view/carrinho/index.php';
        }

        public function remove() {
            $productId = (int)($_GET['id'] ?? 0);
            if (isset($_SESSION['carrinho']['produtos'][$productId])) {
                unset($_SESSION['carrinho']['produtos'][$productId]);
                $this->sincronizarCarrinhoComBanco();
            }
            if (empty($_SESSION['carrinho']['produtos'])) {
                unset($_SESSION['applied_coupon']);
            }
            header('Location: index.php?page=carrinho');
            exit();
        }

        public function update() {
            $productId = (int)($_POST['id'] ?? 0);
            $action = $_POST['action'] ?? '';

            if (isset($_SESSION['carrinho']['produtos'][$productId]) && in_array($action, ['increase', 'decrease'])) {
                if ($action === 'increase') {
                    $_SESSION['carrinho']['produtos'][$productId]['quantidade']++;
                } elseif ($action === 'decrease') {
                    if ($_SESSION['carrinho']['produtos'][$productId]['quantidade'] > 1) {
                        $_SESSION['carrinho']['produtos'][$productId]['quantidade']--;
                    }
                }
                $this->sincronizarCarrinhoComBanco();
            }
            header('Location: index.php?page=carrinho');
            exit();
        }
        
        // Seus métodos de cupom...
        public function applyCoupon() {
            $code = trim($_POST['coupon_code'] ?? '');

            if ($code === '') {
                $_SESSION['coupon_message'] = [
                    'type' => 'error',
                    'text' => 'Digite um código de cupom válido.'
                ];
                header('Location: index.php?page=carrinho');
                exit();
            }

            $coupon = $this->cupomModel->findByCode($code);

            if ($coupon) {
                $_SESSION['applied_coupon'] = $coupon;
                $_SESSION['coupon_message'] = [
                    'type' => 'success',
                    'text' => 'Cupom aplicado com sucesso!'
                ];
            } else {
                $_SESSION['coupon_message'] = [
                    'type' => 'error',
                    'text' => 'Cupom inválido ou expirado.'
                ];
            }

            header('Location: index.php?page=carrinho');
            exit();
        }

        public function removeCoupon() {
            unset($_SESSION['applied_coupon']);
            $_SESSION['coupon_message'] = [
                'type' => 'success',
                'text' => 'Cupom removido com sucesso.'
            ];
            header('Location: index.php?page=carrinho');
            exit();
        }
    }
?>