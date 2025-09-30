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