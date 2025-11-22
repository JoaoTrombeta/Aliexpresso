<?php
namespace Aliexpresso\Observer;

class PedidoSubject implements \SplSubject {
    private $observers;
    
    // Dados que os observadores vão precisar ler
    public $userId;
    public $idPedido;
    public $totalPedido;
    public $nomeUsuario;
    public $emailUsuario;
    
    // Dados que os observadores podem alterar (Ex: Fidelidade gera um cupom)
    public $cupomGerado = null;
    public $descontoGerado = 0;

    public function __construct($userId, $idPedido, $totalPedido, $nomeUsuario, $emailUsuario) {
        $this->observers = new \SplObjectStorage();
        $this->userId = $userId;
        $this->idPedido = $idPedido;
        $this->totalPedido = $totalPedido;
        $this->nomeUsuario = $nomeUsuario;
        $this->emailUsuario = $emailUsuario;
    }

    public function attach(\SplObserver $observer) {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer) {
        $this->observers->detach($observer);
    }

    public function notify() {
        // Avisa cada observador um por um
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}