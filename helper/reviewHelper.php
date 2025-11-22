<?php
namespace Aliexpresso\Helper;

class ReviewHelper {

    /**
     * Retorna o HTML da badge (etiqueta) baseada no sentimento da IA.
     */
    public static function getBadgeIA($sentimento) {
        $cor = '#7f8c8d'; // Cinza (Neutro)
        $icone = 'fa-meh';
        $texto = 'Neutro';
        $bg = '#f0f0f0';

        // Normaliza para garantir que a comparação funcione
        $sentimento = ucfirst(strtolower($sentimento ?? 'Neutro'));

        if ($sentimento === 'Bom' || $sentimento === 'Positivo') {
            $cor = '#27ae60'; // Verde
            $bg = '#eafaf1';
            $icone = 'fa-smile';
            $texto = 'Positivo';
        } elseif ($sentimento === 'Ruim' || $sentimento === 'Negativo') {
            $cor = '#c0392b'; // Vermelho
            $bg = '#fdedec';
            $icone = 'fa-frown';
            $texto = 'Negativo';
        }

        return "
            <span style='background-color: {$bg}; color: {$cor}; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; border: 1px solid {$cor};' title='Análise automática via IA'>
                <i class='fas {$icone}'></i> {$texto}
            </span>
        ";
    }

    /**
     * Renderiza as estrelas douradas baseadas na nota (1 a 5).
     */
    public static function renderEstrelas($nota) {
        $html = '<div class="estrelas" style="color: #f1c40f; display: inline-block;">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $nota) {
                $html .= '<i class="fas fa-star"></i>';
            } else {
                $html .= '<i class="far fa-star"></i>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}