<?php
namespace Aliexpresso\Observer;

use SplObserver;
use SplSubject;
use Aliexpresso\Service\MailerService;

class EmailObserver implements SplObserver {
    
    public function update(SplSubject $subject): void {
        if ($subject instanceof PedidoSubject) {
            $dados = $subject->getPedidoData();
            
            // 1. Tenta encontrar o ID (Aceita 'id_pedido' ou 'id')
            $idPedido = $dados['id_pedido'] ?? $dados['id'] ?? null;
            
            // 2. Busca o Email e Nome (Com Fallback para a Sessão)
            $email = $dados['email'] ?? null;
            $nome = $dados['nome'] ?? null;

            // Se o controller esqueceu de mandar o email, pegamos da sessão do usuário logado
            if (!$email && isset($_SESSION['usuario']['email'])) {
                $email = $_SESSION['usuario']['email'];
            }

            // O mesmo para o nome
            if (!$nome && isset($_SESSION['usuario']['nome'])) {
                $nome = $_SESSION['usuario']['nome'];
            } else if (!$nome) {
                $nome = 'Cliente';
            }

            $total = $dados['total'] ?? 0.0;

            // 3. Validação final: Se mesmo olhando na sessão não tiver email ou ID, aí sim falha.
            if (!$email || !$idPedido) {
                // DEBUG: Mostra quais chaves chegaram para ajudar a encontrar o erro
                $chavesRecebidas = implode(', ', array_keys($dados));
                trigger_error("EmailObserver Falhou: Faltando Email ou ID. Chaves recebidas: [$chavesRecebidas]", E_USER_WARNING);
                return;
            }

            // 4. Instancia o Mailer com segurança
            if (!class_exists(MailerService::class)) {
                return; // Silencioso para não travar a venda se o Mailer sumir
            }

            $mailer = new MailerService();
            
            // 5. Envia o e-mail
            if (!empty($dados['cupom_gerado'])) {
                $mailer->enviarConfirmacaoComCupom(
                    $email,
                    $nome,
                    $idPedido,
                    $total,
                    $dados['cupom_gerado'],
                    $dados['desconto_gerado']
                );
            } else {
                $mailer->enviarConfirmacaoPedido(
                    $email,
                    $nome,
                    $idPedido,
                    $total
                );
            }
        }
    }
}