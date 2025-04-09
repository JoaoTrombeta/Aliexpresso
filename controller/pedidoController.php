<?php
    require_once 'model/pedido.php';

    class PedidoController {
        public function historico() {
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'cliente') {
                echo "Apenas clientes podem ver o histórico.";
                return;
            }

            $pedidos = Pedido::listarPorCliente($_SESSION['usuario']['id']);
            include 'view/pedido/historico.php';
        }

        public function detalhes() {
            $id = $_GET['id'] ?? null;
            if (!$id) return;

            $itens = Pedido::listarItens($id);
            include 'view/pedido/detalhes.php';
        }
    }
?>