<?php
    namespace Aliexpresso\Model\Produtos;

    class CafeEmGraos implements ProdutoCafeina 
    {
        private $nome;
        
        public function __construct(string $nome) {
            $this->nome = $nome;
        }

        public function getNome(): string {
            return $this->nome;
        }

        public function exibir() {
            echo "<div class='produto'><h3>☕ {$this->getNome()} (Grãos)</h3></div>";
        }
    }
?>