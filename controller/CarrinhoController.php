<?php
namespace Aliexpresso\Controller;

use Aliexpresso\Model\ProdutoModel;
use Aliexpresso\Model\CupomModel;
use Aliexpresso\Model\PedidoModel;
use Aliexpresso\Model\ItemPedidoModel;
use Aliexpresso\Service\MailerService;
use Aliexpresso\Observer\PedidoSubject;
use Aliexpresso\Observer\FidelidadeObserver;
use Aliexpresso\Observer\EmailObserver;
use Aliexpresso\Observer\SessaoObserver;

class CarrinhoController
{
    private $produtoModel;
    private $cupomModel;
    private $pedidoModel;
    private $itemPedidoModel;
    private $mailerService;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->cupomModel = new CupomModel();
        $this->pedidoModel = new PedidoModel();
        $this->itemPedidoModel = new ItemPedidoModel();
        $this->mailerService = new MailerService();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho']) || !isset($_SESSION['carrinho']['produtos'])) {
            $_SESSION['carrinho'] = ['produtos' => [], 'total' => 0];
        }
    }

    private function sincronizarCarrinhoComBanco()
    {
        if (!isset($_SESSION['usuario']['id_usuario'])) {
            return;
        }
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

    public function ajax_add()
    {
        header('Content-Type: application/json');
        $productId = (int) ($_GET['id'] ?? 0);
        $response = ['success' => false];

        if ($productId > 0) {
            $produto = $this->produtoModel->getById($productId);
            if ($produto) {
                if (isset($_SESSION['carrinho']['produtos'][$productId])) {
                    $_SESSION['carrinho']['produtos'][$productId]['quantidade']++;
                } else {
                    $_SESSION['carrinho']['produtos'][$productId] = ['id' => $productId, 'quantidade' => 1, 'preco' => $produto['preco']];
                }

                $this->sincronizarCarrinhoComBanco();

                $quantities = array_column($_SESSION['carrinho']['produtos'], 'quantidade');
                $totalItems = array_sum($quantities);

                $response['success'] = true;
                $response['totalItems'] = $totalItems;
                $response['cartTotal'] = number_format($_SESSION['carrinho']['total'], 2, ',', '.');
            }
        }
        echo json_encode($response);
        exit();
    }

    public function index()
    {
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

    public function remove()
    {
        $productId = (int) ($_GET['id'] ?? 0);
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

    public function update()
    {
        $productId = (int) ($_POST['id'] ?? 0);
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

    // --- CORRIGIDO: Agora finaliza corretamente o pedido ---
    public function finalizar()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?page=usuario&action=login');
            exit();
        }
        if (empty($_SESSION['carrinho']['produtos'])) {
            header('Location: index.php?page=carrinho');
            exit();
        }

        try {
            $userId = $_SESSION['usuario']['id_usuario'];
            $this->sincronizarCarrinhoComBanco();

            $pedidoAtual = $this->pedidoModel->findCartByUserId($userId);
            if (!$pedidoAtual)
                throw new \Exception("Carrinho não encontrado.");

            $idPedido = $pedidoAtual->id_pedido;

            // 1. FINALIZA NO BANCO (Isso continua sendo responsabilidade do Controller ou Model)
            $this->pedidoModel->finalizarPedido($idPedido, 'Aprovado', 'Cartão');

            // =================================================================
            // APLICAÇÃO DO PATTERN OBSERVER
            // =================================================================

            // 1. Cria o Evento/Sujeito com os dados fundamentais
            $eventoPedido = new PedidoSubject(
                $userId,
                $idPedido,
                $_SESSION['carrinho']['total'],
                $_SESSION['usuario']['nome'],
                $_SESSION['usuario']['email']
            );

            // 2. Adiciona os Observadores (A ORDEM IMPORTA!)
            // Primeiro Fidelidade (para calcular se tem cupom)
            $eventoPedido->attach(new FidelidadeObserver());

            // Depois Email (para ler se o cupom foi gerado e enviar)
            $eventoPedido->attach(new EmailObserver());

            // Por último Sessão (para limpar tudo)
            $eventoPedido->attach(new SessaoObserver());

            // 3. DISPARA TUDO!
            $eventoPedido->notify();

            // =================================================================

            // 4. PREPARA DADOS PARA A VIEW (Extrato)
            // Como a sessão foi limpa pelo Observer, não podemos mais usar $_SESSION['carrinho'] aqui,
            // mas já temos os dados salvos no banco.
            $pedido = $this->pedidoModel->getById($idPedido);
            $itensPedido = $this->itemPedidoModel->getItemsByOrderId($idPedido);

            // Recupera a mensagem de fidelidade do Objeto Subject se foi gerada
            $mensagemFidelidade = "";
            if ($eventoPedido->cupomGerado) {
                $mensagemFidelidade = "<strong>PARABÉNS!</strong> Ganhaste um cupom: " . $eventoPedido->cupomGerado;
            }

            require_once __DIR__ . '/../view/carrinho/sucesso_compra.php';
            exit();

        } catch (\Exception $e) {
            echo "<h1 style='color:red; text-align:center; margin-top:50px;'>Erro: " . $e->getMessage() . "</h1>";
            echo "<center><a href='index.php?page=carrinho'>Voltar</a></center>";
        }
    }

    public function applyCoupon()
    {
        $code = trim($_POST['coupon_code'] ?? '');
        if ($code === '') {
            $_SESSION['coupon_message'] = ['type' => 'error', 'text' => 'Digite um código de cupom válido.'];
            header('Location: index.php?page=carrinho');
            exit();
        }
        $coupon = $this->cupomModel->findByCode($code);
        if ($coupon) {
            $_SESSION['applied_coupon'] = $coupon;
            $_SESSION['coupon_message'] = ['type' => 'success', 'text' => 'Cupom aplicado com sucesso!'];
        } else {
            $_SESSION['coupon_message'] = ['type' => 'error', 'text' => 'Cupom inválido ou expirado.'];
        }
        header('Location: index.php?page=carrinho');
        exit();
    }

    public function removeCoupon()
    {
        unset($_SESSION['applied_coupon']);
        $_SESSION['coupon_message'] = ['type' => 'success', 'text' => 'Cupom removido com sucesso.'];
        header('Location: index.php?page=carrinho');
        exit();
    }
}
?>