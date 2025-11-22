<?php
namespace Aliexpresso\Observer;

use Aliexpresso\Service\MailerService;

class EmailObserver implements \SplObserver {
    private $mailerService;

    public function __construct() {
        $this->mailerService = new MailerService();
    }

    public function update(\SplSubject $subject) {
        // Verifica se o observador anterior (Fidelidade) gerou algum cupom
        if ($subject->cupomGerado) {
            // Envia o Email COMBO
            $this->mailerService->enviarConfirmacaoComCupom(
                $subject->emailUsuario,
                $subject->nomeUsuario,
                $subject->idPedido,
                $subject->totalPedido,
                $subject->cupomGerado,
                $subject->descontoGerado
            );
        } else {
            // Envia o Email SIMPLES
            $this->mailerService->enviarConfirmacaoPedido(
                $subject->emailUsuario,
                $subject->nomeUsuario,
                $subject->idPedido,
                $subject->totalPedido
            );
        }
    }
}