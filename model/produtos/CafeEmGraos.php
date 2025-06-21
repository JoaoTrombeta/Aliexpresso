<?php
    namespace Aliexpresso\Model\Produtos;

    class CafeEmGraos implements ProdutoCafeina {
        private $nome;
        private $descricao;
        private $preco;
        private $imagem;

        public function __construct(string $nome, string $descricao, float $preco, string $imagem) {
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->preco = $preco;
            $this->imagem = $imagem;
        }

        public function getNome(): string {
            return $this->nome;
        }

        public function getDescricao(): string {
            return $this->descricao;
        }

        public function getPreco(): float {
            return $this->preco;
        }

        public function getImagem(): string {
            return $this->imagem;
        }
    }
?>