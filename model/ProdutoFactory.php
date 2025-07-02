<?php
    namespace Aliexpresso\Model;

    use Aliexpresso\Model\Produtos\ProdutoCafeina;
    use Aliexpresso\Model\Produtos\CafeEmGraos;
    use Aliexpresso\Model\Produtos\CapsulaCafe;
    use Aliexpresso\Model\Produtos\Energeticos;
    use Aliexpresso\Model\Produtos\Doces;
    
    use InvalidArgumentException;

    class ProdutoFactory {
        /**
         * [NOVO] Retorna uma lista de tipos de produtos disponíveis.
         * A chave é o valor que será salvo no banco, e o valor é o texto que aparecerá na tela.
         */
        public static function getAvailableTypes(): array {
            return [
                'graos' => 'Café em Grãos',
                'capsula' => 'Cápsula de Café',
                'energetico' => 'Bebida Energética',
                'doces' => 'Doces'
            ];
        }

        public static function criar(string $tipo, string $nome, string $descricao, float $preco, string $imagem): ProdutoCafeina {
            switch (strtolower($tipo)) {
                case 'graos':
                    return new CafeEmGraos($nome, $descricao, $preco, $imagem);
                
                case 'capsula':
                    return new CapsulaCafe($nome, $descricao, $preco, $imagem);
                
                case 'energetico':
                    return new Energeticos($nome, $descricao, $preco, $imagem);
                
                case 'doces':
                    return new Doces($nome, $descricao, $preco, $imagem);

                default:
                    throw new InvalidArgumentException("Tipo de produto '{$tipo}' inválido.");
            }
        }
    }