<?php
namespace Aliexpresso\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Caminhos corretos baseados na sua estrutura
require_once __DIR__ . '/src/Exception.php';
require_once __DIR__ . '/src/PHPMailer.php';
require_once __DIR__ . '/src/SMTP.php';

class MailerService {

    // --- SEUS DADOS DO MAILTRAP ---
    private $host = 'sandbox.smtp.mailtrap.io';
    private $user = 'c5875af98e3e5d'; 
    private $pass = '6c62d1e69eb76f'; 
    private $port = 2525; 

    /**
     * Método base para envio
     */
    public function enviar($paraEmail, $paraNome, $assunto, $mensagemHTML) {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0; // Desativado para produção
            $mail->Debugoutput = 'html'; 

            $mail->isSMTP();
            $mail->Host       = $this->host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->user;
            $mail->Password   = $this->pass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->port;

            $mail->setFrom('loja@aliexpresso.com', 'Aliexpresso');
            $mail->addAddress($paraEmail, $paraNome);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $assunto;
            $mail->Body    = $mensagemHTML;
            $mail->AltBody = strip_tags($mensagemHTML);

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Em produção, grave log. Para testes, pode descomentar o echo.
            // echo "<br><strong>ERRO EMAIL:</strong> " . $mail->ErrorInfo;
            return false;
        }
    }

    /**
     * Gera o cabeçalho padrão dos e-mails (Estilo Café)
     */
    private function getHeader($titulo) {
        return "
        <div style='font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif; color: #3e3221; max-width: 600px; margin: auto; border: 1px solid #e6dcc3; border-radius: 8px; overflow: hidden; background-color: #fffbf2;'>
            <div style='background-color: #5d5341; color: #ffffff; padding: 25px; text-align: center;'>
                <h1 style='margin: 0; font-size: 24px; letter-spacing: 1px;'>$titulo</h1>
                <p style='margin: 5px 0 0; font-size: 14px; color: #e6dcc3;'>Aliexpresso • Premium Coffee</p>
            </div>
            <div style='padding: 30px;'>";
    }

    /**
     * Gera o rodapé padrão dos e-mails
     */
    private function getFooter() {
        return "
                <hr style='border: 0; border-top: 1px solid #e6dcc3; margin: 30px 0;'>
                <p style='text-align: center; margin: 0; font-size: 12px; color: #8c7b64;'>
                    Obrigado por escolher o Aliexpresso.<br>
                    <em>Energia e foco para o seu dia.</em>
                </p>
            </div>
        </div>";
    }

    /**
     * 1. E-mail Apenas de Confirmação de Compra
     */
    public function enviarConfirmacaoPedido($email, $nome, $idPedido, $total) {
        $assunto = "Pedido #$idPedido Confirmado - Aliexpresso";
        
        $conteudo = "
            <p style='font-size: 16px; margin-top: 0;'>Olá, <strong>$nome</strong>!</p>
            <p style='color: #5d5341;'>O seu pedido <strong>#$idPedido</strong> foi recebido e processado com sucesso.</p>
            
            <div style='background-color: #ffffff; border: 1px solid #e6dcc3; border-radius: 6px; padding: 15px; margin: 20px 0; text-align: center;'>
                <p style='margin: 0; font-size: 14px; color: #6b5e4f; text-transform: uppercase;'>Valor Total</p>
                <p style='margin: 5px 0 0; font-size: 24px; font-weight: bold; color: #4a6840;'>R$ " . number_format($total, 2, ',', '.') . "</p>
            </div>
            
            <p style='font-size: 14px;'>Estamos a preparar tudo com o cuidado que você merece.</p>
            <div style='text-align: center; margin-top: 25px;'>
                <a href='http://localhost/aliexpresso' style='background-color: #a3835f; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 4px; font-weight: bold; font-size: 14px;'>Acessar Minha Conta</a>
            </div>
        ";

        $msgHTML = $this->getHeader("Pedido Confirmado") . $conteudo . $this->getFooter();
        return $this->enviar($email, $nome, $assunto, $msgHTML);
    }

    /**
     * 2. E-mail Apenas de Cupom (Raro uso agora, mas mantido)
     */
    public function enviarCupomFidelidade($email, $nome, $codigo, $desconto) {
        $assunto = "Você ganhou um Cupom Fidelidade! ☕";
        
        $conteudo = "
            <p style='font-size: 16px; margin-top: 0;'>Parabéns, <strong>$nome</strong>!</p>
            <p>Pela sua fidelidade ao nosso café, você ganhou um presente especial.</p>
            
            <div style='background-color: #fff8e1; border: 1px dashed #ffb300; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;'>
                <p style='margin: 0; font-size: 14px; color: #8c7b64;'>USE ESTE CÓDIGO NA PRÓXIMA COMPRA</p>
                <div style='background: #fff; display: inline-block; padding: 10px 25px; border: 1px solid #e6dcc3; border-radius: 4px; margin: 15px 0;'>
                    <span style='font-family: monospace; font-size: 24px; font-weight: bold; color: #d97706; letter-spacing: 2px;'>$codigo</span>
                </div>
                <p style='margin: 0; font-size: 14px; color: #4a6840;'><strong>R$ " . number_format($desconto, 2, ',', '.') . " OFF</strong></p>
            </div>
        ";

        $msgHTML = $this->getHeader("Presente Especial") . $conteudo . $this->getFooter();
        return $this->enviar($email, $nome, $assunto, $msgHTML);
    }

    /**
     * 3. E-mail COMBO (Recibo + Cupom) - O Principal
     */
    public function enviarConfirmacaoComCupom($email, $nome, $idPedido, $total, $codigoCupom, $desconto) {
        $assunto = "Pedido #$idPedido Confirmado + Ganhaste um Presente! 🎁";
        
        $conteudo = "
            <p style='font-size: 16px; margin-top: 0;'>Olá, <strong>$nome</strong>!</p>
            <p>O seu pedido <strong>#$idPedido</strong> foi processado com sucesso.</p>
            
            <div style='background-color: #ffffff; border: 1px solid #e6dcc3; border-radius: 6px; padding: 15px; margin: 20px 0; text-align: center;'>
                <p style='margin: 0; font-size: 14px; color: #6b5e4f;'>TOTAL PAGO</p>
                <p style='margin: 5px 0 0; font-size: 22px; font-weight: bold; color: #5d5341;'>R$ " . number_format($total, 2, ',', '.') . "</p>
            </div>

            <hr style='border: 0; border-top: 1px dashed #e6dcc3; margin: 25px 0;'>

            <div style='text-align: center; background-color: #fff8e1; border: 2px solid #ffe082; padding: 25px; border-radius: 12px; position: relative;'>
                <div style='font-size: 24px; margin-bottom: 10px;'>🎉</div>
                <h2 style='color: #d97706; margin: 0 0 10px 0; font-size: 20px;'>FIDELIDADE PREMIADA</h2>
                <p style='font-size: 14px; color: #5d5341; margin: 0;'>Como esta foi uma compra especial, ganhaste um desconto exclusivo!</p>
                
                <div style='background: #ffffff; display: inline-block; padding: 12px 30px; border-radius: 6px; border: 1px dashed #d97706; margin: 20px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05);'>
                    <span style='font-family: monospace; font-size: 26px; font-weight: bold; color: #d97706; letter-spacing: 1px;'>$codigoCupom</span>
                </div>
                
                <p style='margin: 0; font-size: 13px; color: #8c7b64;'>Válido para desconto de <strong>R$ " . number_format($desconto, 2, ',', '.') . "</strong></p>
            </div>
        ";

        $msgHTML = $this->getHeader("Pedido Confirmado") . $conteudo . $this->getFooter();
        return $this->enviar($email, $nome, $assunto, $msgHTML);
    }
}
