<?php
namespace Aliexpresso\Observer;

use SplSubject;
use SplObserver;
use SplObjectStorage;

class PedidoSubject implements SplSubject {
    private $observers;
    private $pedidoData = []; 

    // Propriedades públicas para o cupom
    public $cupomGerado = null;
    public $descontoGerado = 0.0;

    /**
     * CONSTRUTOR CORRIGIDO:
     * Agora aceita os parâmetros que o CarrinhoController envia.
     */
    public function __construct($userId = null, $idPedido = null, $total = 0.0, $nome = null, $email = null) {
        $this->observers = new SplObjectStorage();

        // Se os dados foram passados na criação do objeto, salvamos imediatamente
        if ($idPedido !== null) {
            $this->pedidoData = [
                'id_usuario' => $userId,
                'id_pedido'  => $idPedido,
                'total'      => $total,
                'nome'       => $nome,
                'email'      => $email
            ];
        }
    }

    public function attach(SplObserver $observer): void {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer): void {
        $this->observers->detach($observer);
    }

    public function notify(): void {
        /** @var SplObserver $observer */
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Método alternativo para definir dados depois da criação
     */
    public function novoPedido(array $dados) {
        $this->pedidoData = $dados;
        $this->cupomGerado = null;
        $this->descontoGerado = 0.0;
        $this->notify();
    }

    public function getPedidoData() {
        // Junta os dados básicos (ID, Email) com os dados novos (Cupom)
        $dadosExtras = [
            'cupom_gerado' => $this->cupomGerado,
            'desconto_gerado' => $this->descontoGerado
        ];
        
        return array_merge($this->pedidoData, $dadosExtras);
    }
}