<?php
namespace Aliexpresso\Observer;

use Aliexpresso\Model\PedidoModel;

class FidelidadeObserver implements \SplObserver {
    private $pedidoModel;

    public function __construct() {
        $this->pedidoModel = new PedidoModel();
    }

    public function update(\SplSubject $subject) {
        // 1. Verifica quantos pedidos o cliente tem
        $qtdPedidos = $this->pedidoModel->countCompletedOrdersByUserId($subject->userId);

        // 2. Aplica a regra (múltiplo de 5)
        if ($qtdPedidos > 0 && $qtdPedidos % 5 == 0) {
            // Gera o cupom e SALVA dentro do Subject para o próximo observador usar
            $subject->cupomGerado = 'FIDELIDADE' . $qtdPedidos . '-' . strtoupper(substr(md5(uniqid()), 0, 4));
            $subject->descontoGerado = 25.00;
            
            // Aqui você poderia também salvar o cupom no banco de dados (tabela cupons)
        }
    }
}