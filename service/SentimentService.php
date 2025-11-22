<?php
namespace Aliexpresso\Service;

class SentimentService {

    // URL onde o script Python está rodando
    private $apiUrl = 'http://127.0.0.1:5000/analisar';

    /**
     * Envia o comentário para a IA Python e retorna a análise.
     * Retorna array com keys: 'sentimento', 'score', 'estrelas_ia'
     */
    public function analisar(string $texto): array {
        // Se o texto for muito curto, não vale a pena gastar recurso da IA
        if (strlen(trim($texto)) < 3) {
            return $this->fallbackResponse();
        }

        $payload = json_encode(['comentario' => $texto]);

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Timeout de 2 segundos. Se a IA demorar, o site não trava.
        curl_setopt($ch, CURLOPT_TIMEOUT, 2); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Se der erro (Python desligado, timeout, etc), retorna neutro
        if (curl_errno($ch) || $httpCode !== 200) {
            // Opcional: Logar erro aqui (error_log("Erro IA: " . curl_error($ch)));
            curl_close($ch);
            return $this->fallbackResponse();
        }

        curl_close($ch);
        return json_decode($result, true);
    }

    /**
     * Resposta padrão caso a IA esteja offline
     */
    private function fallbackResponse(): array {
        return [
            'sentimento' => 'Neutro', 
            'score' => 0.0, 
            'estrelas_ia' => 3
        ];
    }
}