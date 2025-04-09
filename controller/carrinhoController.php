<?php
    require_once 'model/produto.php';

    class CarrinhoController {
        public function adicionar() {
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'cliente') {
                echo "Apenas clientes podem adicionar ao carrinho.";
                return;
            }

            $id = $_GET['id'] ?? null;
            if (!$id) return;

            if (!isset($_SESSION['carrinho'])) {
                $_SESSION['carrinho'] = [];
            }

            if (isset($_SESSION['carrinho'][$id])) {
                $_SESSION['carrinho'][$id]++;
            } else {
                $_SESSION['carrinho'][$id] = 1;
            }

            header('Location: ?controller=carrinho&action=visualizar');
        }

        public function visualizar() {
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'cliente') {
                echo "Apenas clientes podem visualizar o carrinho.";
                return;
            }

            $produtos = [];
            $total = 0;

            if (isset($_SESSION['carrinho'])) {
                foreach ($_SESSION['carrinho'] as $id => $qtd) {
                    $p = Produto::buscarPorId($id);
                    if ($p) {
                        $p['quantidade'] = $qtd;
                        $p['subtotal'] = $p['preco'] * $qtd;
                        $total += $p['subtotal'];
                        $produtos[] = $p;
                    }
                }
            }

            include 'view/carrinho/visualizar.php';
        }

        public function remover() {
            $id = $_GET['id'] ?? null;
            if ($id && isset($_SESSION['carrinho'][$id])) {
                unset($_SESSION['carrinho'][$id]);
            }
            header('Location: ?controller=carrinho&action=visualizar');
        }

        public function finalizar() {
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'cliente') {
                echo "Apenas clientes podem finalizar pedidos.";
                return;
            }
        
            if (empty($_SESSION['carrinho'])) {
                echo "Seu carrinho está vazio!";
                return;
            }
        
            $id_cliente = $_SESSION['usuario']['id'];
            $total = 0;
            $itens = [];
        
            foreach ($_SESSION['carrinho'] as $id => $qtd) {
                $produto = Produto::buscarPorId($id);
                if ($produto) {
                    $subtotal = $produto['preco'] * $qtd;
                    $total += $subtotal;
                    $itens[] = [
                        'id_produto' => $id,
                        'quantidade' => $qtd,
                        'preco_unitario' => $produto['preco']
                    ];
                }
            }
        
            $pdo = Conexao::conectar();
            $pdo->beginTransaction();
        
            $sql = "INSERT INTO pedidos (id_cliente, data_pedido, total) VALUES (?, NOW(), ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_cliente, $total]);
            $id_pedido = $pdo->lastInsertId();
        
            foreach ($itens as $item) {
                $sql_item = "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
                $stmt_item = $pdo->prepare($sql_item);
                $stmt_item->execute([$id_pedido, $item['id_produto'], $item['quantidade'], $item['preco_unitario']]);
            }
        
            $pdo->commit();
            unset($_SESSION['carrinho']);
        
            include 'view/carrinho/sucesso.php';
        }
        
    }
?>