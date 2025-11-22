<?php
namespace Aliexpresso\Observer;

use SplObserver;
use SplSubject;
use Aliexpresso\Model\CupomModel;
use PDO;

class FidelidadeObserver implements SplObserver {
    
    public function update(SplSubject $subject): void {
        // Verifica se é o Subject correto
        if ($subject instanceof PedidoSubject) {
            $dados = $subject->getPedidoData();
            $idUsuario = $dados['id_usuario'] ?? null;
            $valorTotal = $dados['total'] ?? 0;

            if (!$idUsuario) return;

            // Tenta carregar a classe Database se ela não estiver carregada
            if (!class_exists('Database')) {
                $dbFile = __DIR__ . '/../model/Database.php';
                if (file_exists($dbFile)) {
                    require_once $dbFile;
                }
            }

            try {
                // Conecta ao banco para contar o histórico do cliente
                $pdo = \Database::getInstance()->getConnection();
                
                // Conta pedidos finalizados (exclui o que ainda está como 'Carrinho', se houver algum perdido)
                // O pedido atual já deve ter mudado de status para 'Aprovado' no Controller antes de chamar o Observer
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE id_usuario = ? AND status != 'Carrinho'");
                $stmt->execute([$idUsuario]);
                $qtdPedidos = (int)$stmt->fetchColumn();

                // === REGRA DE FIDELIDADE CORRIGIDA ===
                // Gera cupom apenas se a contagem for múltiplo de 5 (5, 10, 15...)
                if ($qtdPedidos > 0 && ($qtdPedidos % 5) === 0) {
                    
                    $codigoCupom = 'FIDELIDADE' . strtoupper(substr(md5(uniqid()), 0, 5));
                    
                    // Lógica de valor do cupom (ex: 10% do valor desta compra)
                    $valorDesconto = $valorTotal * 0.10; 

                    // 1. Salva no banco
                    if (class_exists('Aliexpresso\Model\CupomModel')) {
                        $cupomModel = new CupomModel();
                        if (method_exists($cupomModel, 'criarCupomAutomatico')) {
                            $cupomModel->criarCupomAutomatico($codigoCupom, $valorDesconto);
                        }
                    }

                    // 2. Avisa o Subject para aparecer no recibo e no email
                    $subject->cupomGerado = $codigoCupom;
                    $subject->descontoGerado = $valorDesconto;
                }

            } catch (\Exception $e) {
                // Falha silenciosa para não travar a finalização da compra
            }
        }
    }
}