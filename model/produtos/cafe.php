<?php
namespace Aliexpresso\Model\Produtos;

use Aliexpresso\Model\ProdutoInterface;

class Cafe implements ProdutoInterface {
    private $id_produto;
    private $nome;
    private $descricao;
    private $preco;
    private $quantidade_estoque;
    private $categoria;
    private $imagem;
    private $status;

    public function __construct(array $dados) {
        $this->id_produto = (int)$dados['id_produto'];
        $this->nome = $dados['nome'];
        $this->descricao = $dados['descricao'] ?? '';
        $this->preco = (float)$dados['preco'];
        $this->quantidade_estoque = (int)$dados['quantidade_estoque'];
        $this->categoria = $dados['categoria'];
        $this->imagem = $dados['imagem'] ?? 'https://placehold.co/400x400?text=Sem+Imagem';
        $this->status = $dados['status'];
    }

    public function getIdProduto(): int { return $this->id_produto; }
    public function getNome(): string { return $this->nome; }
    public function getDescricao(): string { return $this->descricao; }
    public function getPrecoFormatado(): string { return 'R$ ' . number_format($this->preco, 2, ',', '.'); }
    public function getQuantidadeEstoque(): int { return $this->quantidade_estoque; }
    public function getCategoria(): string { return $this->categoria; }
    public function getImagem(): string { return $this->imagem; }
    public function getStatus(): string { return $this->status; }
}