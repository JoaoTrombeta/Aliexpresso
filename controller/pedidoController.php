<?php
namespace Aliexpresso\Controller;

use Aliexpresso\Model\PedidoModel;
use Aliexpresso\Model\ItemPedidoModel;
use Aliexpresso\Helper\Auth;

class PedidoController {

    private $pedidoModel;
    private $itemPedidoModel;

    public function __construct() {
        // Garante que apenas usuários logados possam acessar esta área
        if (!Auth::isLoggedIn()) {
            header('Location: /?page=usuario&action=login');
            exit();
        }
        $this->pedidoModel = new PedidoModel();
        $this->itemPedidoModel = new ItemPedidoModel();
    }

    public function finalizar() {
        $userId = Auth::user()['id_usuario'];

        // 1. Encontra o carrinho ativo do usuário no banco
        $carrinho = $this->pedidoModel->findCartByUserId($userId);

        // 2. Validação: Se não houver carrinho ou a sessão estiver vazia, volta para o carrinho
        if (!$carrinho || empty($_SESSION['carrinho']['produtos'])) {
            header('Location: ./?page=carrinho&erro=vazio');
            exit();
        }
        
        // 3. Calcula o total final a partir da sessão (considerando cupons)
        $subtotal = 0;
        foreach ($_SESSION['carrinho']['produtos'] as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        
        $discount = 0;
        if (isset($_SESSION['applied_coupon'])) {
            $coupon = $_SESSION['applied_coupon'];
            if ($coupon['tipo'] === 'fixo') {
                $discount = $coupon['valor_desconto'];
            } elseif ($coupon['tipo'] === 'percentual') {
                $discount = ($subtotal * $coupon['valor_desconto']) / 100;
            }
        }
        $totalFinal = $subtotal - $discount;

        // 4. Usa o método do Model para "oficializar" o pedido no banco de dados
        // Ele vai mudar o status de 'carrinho' para 'concluido' e atualizar a data e o valor.
        $this->pedidoModel->finalizeCart($carrinho->id_pedido, $totalFinal);

        // 5. Limpa o carrinho da sessão, pois a compra foi concluída
        unset($_SESSION['carrinho']);
        unset($_SESSION['applied_coupon']); // Limpa também o cupom aplicado
        
        // 6. Redireciona o usuário para a página de histórico com uma mensagem de sucesso
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

    // O método finalizar() que criamos antes entrará aqui também, 
    // mas vamos focar no histórico primeiro.
}