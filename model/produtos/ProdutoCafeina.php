<?php
    // model/produtos/ProdutoCafeina.php
    namespace Aliexpresso\Model\Produtos;

    interface ProdutoCafeina {
        public function getNome(): string;
        public function getDescricao(): string;
        public function getPreco(): float;
        public function getImagem(): string;
    }
?>