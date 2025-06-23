<?php
namespace Aliexpresso\Model;

<<<<<<< HEAD
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
=======
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
<<<<<<< HEAD
    }
=======
    }
>>>>>>> f9d1cbfdad54ec8ff87926193adbdb430121d076
>>>>>>> bacc9bfc235210900b6db3f9a913727e3e34920c
