<?php
    namespace Aliexpresso\Model;

    use Aliexpresso\Model\Produtos\ProdutoCafeina;
    use Aliexpresso\Model\Produtos\CafeEmGraos;
    // Adicione outras classes de produto aqui, como CapsulaCafe
    // use Aliexpresso\Model\Produtos\CapsulaCafe;
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
                // Adicione outros tipos aqui
                // 'energetico' => 'Bebida Energética'
            ];
        }

        public static function criar(string $tipo, string $nome, string $descricao, float $preco, string $imagem): ProdutoCafeina {
            switch (strtolower($tipo)) {
                case 'graos':
                    return new CafeEmGraos($nome, $descricao, $preco, $imagem);
                
                // case 'capsula':
                //    return new CapsulaCafe($nome, $descricao, $preco, $imagem);

                default:
                    throw new InvalidArgumentException("Tipo de produto '{$tipo}' inválido.");
            }
        }
    }