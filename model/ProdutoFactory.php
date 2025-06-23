<?php
namespace Aliexpresso\Model;

// Importa as classes que a fábrica pode construir
use Aliexpresso\Model\Produtos\Cafe;
use Aliexpresso\Model\Produtos\Grao;
use Aliexpresso\Model\Produtos\Energetico;
use InvalidArgumentException;

class ProdutoFactory {
    /**
     * Cria uma instância de um produto com base na categoria.
     * @param array $dadosProduto Os dados vindos do banco.
     * @return ProdutoInterface A instância do produto criada.
     */
    public static function criar(array $dadosProduto): ProdutoInterface {
        // A lógica agora usa o campo "categoria"
        if (!isset($dadosProduto['categoria'])) {
            throw new InvalidArgumentException("O campo 'categoria' é obrigatório nos dados do produto.");
        }

        switch ($dadosProduto['categoria']) {
            case 'café':
                return new Cafe($dadosProduto);
            case 'grão':
                return new Grao($dadosProduto);
            case 'energético':
                return new Energetico($dadosProduto);
            default:
                // Trata casos de categorias não esperadas
                throw new InvalidArgumentException("Categoria de produto desconhecida: " . $dadosProduto['categoria']);
        }
    }
}