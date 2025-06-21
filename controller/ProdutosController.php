<?php
    namespace Aliexpresso\Controller;

    use Aliexpresso\Model\ProdutoFactory;

    class ProdutoController {
        
        public function index() {
            // Simulação de dados do banco
            $dadosDoBanco = [
                [
                    'tipo' => 'graos',
                    'nome' => 'Café Arábica da Montanha',
                    'descricao' => 'Um café equilibrado com notas de chocolate e frutas vermelhas.',
                    'preco' => 45.50,
                    'imagem' => 'images/cafe-graos-arabica.jpg'
                ],
                [
                    'tipo' => 'graos',
                    'nome' => 'Café Robusta Intenso',
                    'descricao' => 'Sabor forte e marcante, ideal para quem busca energia extra.',
                    'preco' => 38.90,
                    'imagem' => 'images/cafe-graos-robusta.jpg'
                ]
            ];

            $produtos = [];

            foreach ($dadosDoBanco as $dado) {
                $produtos[] = ProdutoFactory::criar(
                    $dado['tipo'],
                    $dado['nome'],
                    $dado['descricao'],
                    $dado['preco'],
                    $dado['imagem']
                );
            }

            // Carrega a view, que agora pode estar em um caminho mais limpo
            require_once __DIR__ . '/../view/produtos/catalogo.php';
        }
    }
?>