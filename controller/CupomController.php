<?php
namespace Aliexpresso\Controller;

use Aliexpresso\Model\CupomModel;
use Aliexpresso\Service\MailerService; // Importamos nosso novo serviço

class CupomController {
    private $cupomModel;
    private $mailerService;

    public function __construct() {
        $this->cupomModel = new CupomModel();
        $this->mailerService = new MailerService();
    }

    /**
     * Ação para gerar um cupom para o usuário logado
     * Ex: Chamado via AJAX ou botão "Gerar Cupom"
     */
    public function gerarCupomPromocional() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Verifica se usuário está logado
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
            exit;
        }

        $usuario = $_SESSION['usuario']; // Array com id_usuario, nome, email
        
        // 2. Lógica de geração do código (Ex: CUPOM + 4 digitos aleatorios)
        $codigo = 'DESC' . strtoupper(substr(md5(uniqid()), 0, 4));
        $valorDesconto = 15.00; // Exemplo: 15 reais

        $dadosCupom = [
            'codigo' => $codigo,
            'descricao' => 'Cupom Promocional Gerado Automaticamente',
            'valor_desconto' => $valorDesconto,
            'tipo' => 'fixo', // ou 'percentual'
            'data_validade' => date('Y-m-d', strtotime('+7 days')), // Válido por 7 dias
            'status' => 'ativo'
        ];

        // 3. Salva no Banco (Model)
        if ($this->cupomModel->create($dadosCupom)) {
            
            // 4. ENVIA O EMAIL (Service)
            // Assumindo que $_SESSION['usuario'] tem os campos 'email' e 'nome'
            $emailEnviado = $this->mailerService->enviarCupomBoasVindas(
                $usuario['email'], 
                $usuario['nome'], 
                $codigo, 
                $valorDesconto
            );

            if ($emailEnviado) {
                $msg = "Cupom gerado e enviado para seu e-mail!";
            } else {
                $msg = "Cupom gerado, mas houve um erro ao enviar o e-mail.";
            }

            echo json_encode(['success' => true, 'message' => $msg, 'codigo' => $codigo]);

        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao criar cupom no banco.']);
        }
        exit;
    }
}
?>