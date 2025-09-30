<?php
namespace Aliexpresso\Helper\Export;

/**
 * O Adaptador. Ele implementa nossa interface padrão e "conversa" com a classe GeradorCsv.
 */
class CsvExportAdapter implements ExportadorInterface {
    
    private $geradorCsv;

    public function __construct() {
        $this->geradorCsv = new GeradorCsv();
    }

    public function exportar(array $dados, string $nomeArquivo) {
        // 1. Chama o método da classe "incompatível" para gerar a string
        $csvString = $this->geradorCsv->converterArrayParaCsvString($dados);

        // 2. Prepara os cabeçalhos HTTP para forçar o download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $nomeArquivo . '.csv"');

        // 3. Imprime a string CSV, que será enviada como o arquivo
        echo $csvString;
    }
}