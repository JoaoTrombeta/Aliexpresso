<?php
namespace Aliexpresso\Helper\Export;

/**
 * Interface (Contrato) que todos os exportadores devem seguir.
 */
interface ExportadorInterface {
    /**
     * Exporta os dados e envia para o navegador.
     * @param array $dados Os dados a serem exportados.
     * @param string $nomeArquivo O nome do arquivo para download.
     */
    public function exportar(array $dados, string $nomeArquivo);
}