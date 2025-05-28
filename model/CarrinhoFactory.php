<?php
class Produto {
    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $imagem;

    public function __construct($id, $nome, $descricao, $preco, $imagem) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->imagem = $imagem;
    }
}

class ProdutoFactory {
    public static function criarProduto($dados) {
        return new Produto(
            $dados['id'],
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['imagem']
        );
    }
}
?>
