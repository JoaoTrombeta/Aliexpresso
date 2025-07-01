<?php
    namespace Aliexpresso\Model\Produtos;

    class CapsulaCafe implements ProdutoCafeina 
    {
        private $nome, $descricao, $preco, $imagem;
        
        public function __construct(string $nome, string $descricao, float $preco, string $imagem = 'default.jpg') {
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->preco = $preco;
            $this->imagem = $imagem;
        }
        // ... getters para cada propriedade
        public function getNome(): string { return $this->nome; }
        public function getDescricao(): string { return $this->descricao; }
        public function getPreco(): float { return $this->preco; }
        public function getImagem(): string { return $this->imagem; }
    }
?>