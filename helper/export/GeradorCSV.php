<?php
namespace Aliexpresso\Helper\Export;

/**
 * Classe que sabe gerar uma string CSV, mas tem uma interface "diferente".
 * Este é o nosso "Adaptee".
 */
class GeradorCsv {
    public function converterArrayParaCsvString(array $dados): string {
        if (empty($dados)) {
            return '';
        }

        // Usa um buffer de memória para não precisar criar um arquivo físico
        $output = fopen('php://memory', 'w');

        // Adiciona o cabeçalho (nomes das colunas)
        fputcsv($output, array_keys($dados[0]));

        // Adiciona as linhas de dados
        foreach ($dados as $linha) {
            fputcsv($output, $linha);
        }

        // Volta para o início do buffer
        rewind($output);
        // Pega todo o conteúdo do buffer como uma string
        $csvString = stream_get_contents($output);
        // Fecha o buffer
        fclose($output);

        return $csvString;
    }
}