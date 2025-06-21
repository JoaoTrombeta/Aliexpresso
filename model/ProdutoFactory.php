<?php
    namespace Aliexpresso\Model;

    use Aliexpresso\Model\Produtos\ProdutoCafeina;
    use Aliexpresso\Model\Produtos\CafeEmGraos;
    use InvalidArgumentException;

    class ProdutoFactory {
        public static function criar(string $tipo, string $nome, string $descricao, float $preco, string $imagem): ProdutoCafeina {
            switch (strtolower($tipo)) {
                case 'graos':
                    return new CafeEmGraos($nome, $descricao, $preco, $imagem);
                
                case 'capsula':
                    return new CapsulaCafe($nome, $descricao, $preco, $imagem);
                
                default:
                    throw new InvalidArgumentException("Tipo de produto '{$tipo}' inválido.");
            }
        }
    }
?>