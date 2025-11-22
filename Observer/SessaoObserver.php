<?php
namespace Aliexpresso\Observer;

use SplObserver;
use SplSubject;

class SessaoObserver implements SplObserver {
    
    // [CORREÇÃO] Adicionado ": void" no retorno
    public function update(SplSubject $subject): void {
        // Garante que a sessão esteja iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpa o carrinho de compras após o pedido ser finalizado com sucesso
        if (isset($_SESSION['carrinho'])) {
            unset($_SESSION['carrinho']);
        }
    }
}