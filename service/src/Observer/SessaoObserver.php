<?php
namespace Aliexpresso\Observer;

class SessaoObserver implements \SplObserver {
    public function update(\SplSubject $subject) {
        // Limpa o carrinho
        if (isset($_SESSION['carrinho'])) {
            unset($_SESSION['carrinho']);
            unset($_SESSION['applied_coupon']);
            $_SESSION['carrinho'] = ['produtos' => [], 'total' => 0];
        }
    }
}