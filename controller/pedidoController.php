<?php
namespace Aliexpresso\Controller;

use Aliexpresso\Model\PedidoModel;
use Aliexpresso\Model\ItemPedidoModel;
use Aliexpresso\Helper\Auth;
use Aliexpresso\Model\CupomModel;

class PedidoController {

    private $pedidoModel;
    private $itemPedidoModel;
    private $cupomModel;

    public function __construct() {
        // Garante que apenas usuários logados possam acessar esta área
        if (!Auth::isLoggedIn()) {
            header('Location: /?page=usuario&action=login');
            exit();
        }
        $this->pedidoModel = new PedidoModel();
        $this->itemPedidoModel = new ItemPedidoModel();
        $this->cupomModel = new CupomModel();
    }

    public function finalizar() {
        $userId = Auth::user()['id_usuario'];
        $carrinho = $this->pedidoModel->findCartByUserId($userId);

        if (!$carrinho || empty($_SESSION['carrinho']['produtos'])) {
            header('Location: /?page=carrinho&erro=vazio');
            exit();
        }

        $total = $_SESSION['carrinho']['total'] ?? $carrinho->valor_total;
        $desconto = 0;

        // ==========================================================
        // [LÓGICA DE CUPOM]
        // ==========================================================
        if (isset($_SESSION['applied_coupon'])) {
            $coupon = $_SESSION['applied_coupon'];

            if ($coupon['tipo'] === 'percentual') {
                $desconto = $total * ($coupon['valor_desconto'] / 100);
            } elseif ($coupon['tipo'] === 'fixo') {
                $desconto = $coupon['valor_desconto'];
            }

            // Marcar cupom como usado
            $this->cupomModel->markAsUsed(
                (int)$coupon['id_cupom'],
                $userId,
                (int)$carrinho->id_pedido
            );

            // Se for fidelidade, inativar
            if (str_starts_with($coupon['codigo'], 'FIDELIDADE')) {
                $this->cupomModel->deactivateCoupon((int)$coupon['id_cupom']);
            }
        }

        $valorFinal = max($total - $desconto, 0);

        // Salvar no banco
        $this->pedidoModel->finalizeCartWithDiscount(
            $carrinho->id_pedido,
            $total,
            $desconto,
            $valorFinal
        );

        unset($_SESSION['carrinho'], $_SESSION['applied_coupon']);

        // ==========================================================
        // [LÓGICA DO CUPOM DE FIDELIDADE EXISTENTE]
        // ==========================================================
        $totalPedidosCliente = $this->pedidoModel->countCompletedOrdersByUserId($userId);
        if ($totalPedidosCliente > 0 && $totalPedidosCliente % 5 === 0) {
            $codigoCupom = 'FIDELIDADE-' . strtoupper(uniqid());
            $dadosCupom = [
                'codigo'        => $codigoCupom,
                'descricao'     => "Cupom de 10% por ter feito {$totalPedidosCliente} compras!",
                'valor_desconto'=> 10,
                'tipo'          => 'percentual',
                'data_validade' => date('Y-m-d H:i:s', strtotime('+60 days')),
                'status'        => 'ativo'
            ];
            $this->cupomModel->create($dadosCupom);
            $_SESSION['mensagem_cupom_fidelidade'] = 
                "Parabéns! Você ganhou um cupom de 10%: <strong>{$codigoCupom}</strong>";
        }

        header('Location: ./?page=pedido&action=historico&sucesso=1');
        exit();
    }


    /**
     * Exibe o histórico de todos os pedidos finalizados do cliente.
     */
    public function historico() {
        $userId = Auth::user()['id_usuario'];
        
        // 1. Busca no banco todos os pedidos do usuário que NÃO são 'carrinho'
        $pedidos = $this->pedidoModel->findOrdersByUserId($userId);
        
        // 2. Para cada pedido encontrado, busca os produtos correspondentes com seus detalhes
        if ($pedidos) {
            foreach ($pedidos as $key => $pedido) {
                // Anexa os itens ao objeto do pedido
                $pedidos[$key]->itens = $this->itemPedidoModel->findItemsByOrderIdWithDetails($pedido->id_pedido);
            }
        }
        
        // 3. Renderiza a página de histórico, passando a lista completa de pedidos
        require_once __DIR__ . '/../view/pedidos/historico.php';
    }

}