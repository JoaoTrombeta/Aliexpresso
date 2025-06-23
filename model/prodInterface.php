<?php
namespace Aliexpresso\Model;

interface ProdutoInterface {
    public function getIdProduto(): int;
    public function getNome(): string;
    public function getDescricao(): string;
    public function getPrecoFormatado(): string;
    public function getQuantidadeEstoque(): int;
    public function getCategoria(): string;
    public function getImagem(): string;
    public function getStatus(): string;
    // Opcional: se precisar do ID do vendedor.
    // public function getIdVendedor(): ?int;
}